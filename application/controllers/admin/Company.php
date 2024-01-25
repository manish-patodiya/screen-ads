<?php defined('BASEPATH') or exit('No direct script access allowed');
class Company extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('admin/user_model', 'user_model');
        $this->load->model('admin/company_model', 'company_model');
        $this->load->model('admin/template_model', 'template_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('admin/admin_model', 'admin');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/company/company_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }

    public function datatable_json()
    {
        $records['data'] = $this->company_model->get_all_company();
        // prd($records);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $license = $row['license_no'];
            $data[] = array(
                ++$i,
                $row['name'],
                $row['username'],
                $row['email'],
                $row['mobile'],
                date_time($row['created_at']),
                date_time($row['license_date']),
                $license,
                '<input class="tgl_checkbox tgl-ios"
				data-id="' . $row['id'] . '"
				id="cb_' . $row['id'] . '"
				type="checkbox"
				' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('admin/company/edit/' . $row['id']) . '"> <i class="fa fa-eye"></i></a>
				<a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/company/edit/' . $row['id']) . '"> <i class="fa fa-pencil-square-o"></i></a>
				<a title="Delete" class="delete btn btn-sm  btn-danger sup_delete"  uid="' . $row['id'] . '" href="#" title="Delete" > <i class="fa fa-trash-o"></i></a>',
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    //-----------------------------------------------------------
    public function change_status()
    {
        $this->company_model->change_status();
    }

    public function add()
    {
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('logo', 'Company Logo', 'trim');
            $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('license', 'License', 'trim|required');
            $this->form_validation->set_rules('license_date', 'License Date', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim');

            //User information
            $this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|is_unique[admin.username]|required');
            $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/company/add'), 'refresh');
            } else {
                $admin_id = $this->_add_admin();
                if ($admin_id) {
                    $data = [
                        'name' => uc($this->input->post('companyname')),
                        'license_no' => $this->input->post('license'),
                        'license_date' => $this->input->post('license_date'),
                        'email' => $this->input->post('email'),
                        'mobile' => $this->input->post('mobile_no'),
                        'address' => uc($this->input->post('address')),
                        'is_active' => $this->input->post('status') ?: 1,
                        'admin_id' => $admin_id,
                        'logo' => $this->_upload_logo(),
                        'created_at' => date('Y-m-d : h:m:s'),
                        'updated_at' => date('Y-m-d : h:m:s'),
                    ];
                    $company_id = $this->company_model->add_company($data);
                    $no_of_license = $this->input->post('license');
                    $user_id;
                    if ($company_id) {
                        for ($i = 1; $i <= $no_of_license; $i++) {
                            $data = array(
                                'username' => $company_id . "-device-" . $i,
                                'company_id' => $company_id,
                                'password' => password_hash("12345", PASSWORD_BCRYPT),
                                'created_at' => date('Y-m-d : h:m:s'),
                                'updated_at' => date('Y-m-d : h:m:s'),
                                "is_verify" => 1,
                            );
                            $user_id = $this->user_model->add_user($data);
                        }

                    }
                    $default_tmplt = $this->template_model->get_default_tmplt();
                    // $default_tmplt = count($default_tmplt);
                    foreach ($default_tmplt as $key => $value) {
                        // prd($value);
                        $tmplt_data = array(
                            'company_id' => $company_id,
                            'title' => $value['title'],
                            'content' => $value['content'],
                            'status' => $value['status'],
                            'is_master' => $value['is_master'],
                        );
                        $this->template_model->add_template($tmplt_data);
                    }
                    // prd($user_id);
                    if ($user_id) {
                        // Activity Log
                        $this->activity_model->add_log(1);
                        $this->session->set_flashdata('success', 'Company has been added successfully!');
                        redirect(base_url('admin/company'));
                    }
                } else {
                    $data = array(
                        'errors' => "Internal Server Error.",
                    );
                    $this->session->set_flashdata('errors', $data['errors']);
                    redirect(base_url('admin/company/add'), 'refresh');
                }
            }
        } else {
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/company/company_add');
            $this->load->view('admin/includes/_footer');
        }
    }

    private function _upload_logo()
    {
        $logo = '';
        if (!empty($_FILES['logo']['name'])) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $profileconfig['upload_path'] = './uploads/logo/';
            $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg';
            $profileconfig['max_size'] = '2000000';
            $profileconfig['remove_spaces'] = false;
            $profileconfig['overwrite'] = false;
            $profileconfig['max_width'] = '';
            $profileconfig['max_height'] = '';
            $new_name = "logo" . time();
            $profileconfig['file_name'] = $new_name;
            $this->upload->initialize($profileconfig);
            if (!$this->upload->do_upload('logo')) {
                $error = array('error' => $this->upload->display_errors());
                $error['error'];
            }
            $file = $this->upload->data();
            $file_path = $file['full_path'];
            $this->load->library('S3upload');
            $folderName = "logo";
            $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);

            if ($s3Upload) {
                $bucket = 'iddms';
                $actual_file_path = $folderName . '/' . $file["file_name"];
                $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                //$fullFilePath = $monthPath.'/'.$file_name;
                // $pushString = $this->AppointmentModel->storePdfString($visit_id, $fullFilePath);
                // $data['pdfdata'][0]->prescriptionPdf = $fullFilePath;
            }
            $logo = $fullFilePath;

            if ($fullFilePath) {
                unlink($file['full_path']);
            }
        }
        return $logo;
    }

    private function _add_admin()
    {
        $this->rbac->check_operation_access(); // check opration permission
        $admin_roles = $this->admin->get_admin_roles();
        $admin_role_id = false;
        foreach ($admin_roles as $k => $v) {
            if (strtolower($v["admin_role_title"]) == "admin") {
                $admin_role_id = $v["admin_role_id"];
            }
        }
        $data = array(
            'admin_role_id' => $admin_role_id,
            'username' => $this->input->post('username'),
            'firstname' => uc($this->input->post('firstname')),
            'lastname' => uc($this->input->post('lastname')),
            'email' => $this->input->post('email'),
            'mobile_no' => $this->input->post('mobile_no'),
            'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            'is_active' => 1,
            'is_verify' => 1,
            'created_at' => date('Y-m-d : h:m:s'),
            'updated_at' => date('Y-m-d : h:m:s'),
        );
        $result = $this->admin->add_admin($data);
        if ($result) {
            $admin_id = $this->db->insert_id();
            // Activity Log
            $this->activity_model->add_log(4);
            return $admin_id;
        }
        return false;
    }
    private function _update_admin($id)
    {
        $this->rbac->check_operation_access(); // check opration permission
        $data = array(
            'username' => $this->input->post('username'),
            'firstname' => uc($this->input->post('firstname')),
            'lastname' => uc($this->input->post('lastname')),
            'email' => $this->input->post('email'),
            'mobile_no' => $this->input->post('mobile_no'),
            // 'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
            // 'is_active' => $this->input->post('status'),
            'updated_at' => date('Y-m-d : h:m:s'),
        );
        $result = $this->admin->edit_admin($data, $id);
        if ($result) {
            // Activity Log
            $this->activity_model->add_log(5);
            return $result;
        }
        return false;
    }

    public function edit($id = 0)
    {
        $result = $this->company_model->get_all_company();
        // prd($result[0]["logo"]);
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('companyname', 'Company Name', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('license', 'License', 'trim|required');
            $this->form_validation->set_rules('license_date', 'License Date', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim');

            //User information
            $this->form_validation->set_rules('username', 'Username', 'trim|alpha_numeric|required');
            $this->form_validation->set_rules('firstname', 'Firstname', 'trim|required');
            $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
            // $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('admin_id', 'admin_id', 'trim|required');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/company/edit/' . $id), 'refresh');
            } else {

                $admin_id = $this->_update_admin($this->input->post('admin_id'));
                if ($admin_id) {
                    $data = [
                        'name' => uc($this->input->post('companyname')),
                        'license_no' => $this->input->post('license'),
                        'license_date' => $this->input->post('license_date'),
                        'email' => $this->input->post('email'),
                        'mobile' => $this->input->post('mobile_no'),
                        'address' => uc($this->input->post('address')),
                        // 'is_active' => $this->input->post('status'),
                        // 'logo' => $this->_upload_logo(),
                        'created_at' => date('Y-m-d : h:m:s'),
                        'updated_at' => date('Y-m-d : h:m:s'),
                    ];
                    // prd($id);
                    if (!empty($_FILES['logo']['name'])) {
                        $data = array_merge($data, ['logo' => $this->_upload_logo()]);
                    }
                    $result = $this->company_model->edit_company($data, $id);
                    if ($result) {
                        // Activity Log
                        $this->activity_model->add_log(2);

                        $this->session->set_flashdata('success', 'Company has been updated successfully!');
                        redirect(base_url('admin/company'));
                    }
                } else {
                    $data = array(
                        'errors' => "Internal Server Error.",
                    );
                    $this->session->set_flashdata('errors', $data['errors']);
                    redirect(base_url('admin/company/edit/' . $id), 'refresh');
                }
            }
        } else {
            $data['company'] = $this->company_model->get_company_by_id($id);
            $data['admin'] = $this->company_model->get_admin_by_company_id($id);
            //prd($data);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/company/company_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('companies', array('id' => $id));

        // Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'Company has been deleted successfully!');
        redirect(base_url('admin/company'));
    }
    public function delete_company()
    {
        $id = $this->input->Post('id');
        $department = $this->company_model->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Company was deleted successfully',
        ]);

    }
}