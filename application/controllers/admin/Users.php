<?php defined('BASEPATH') or exit('No direct script access allowed');
class Users extends MY_Controller
{
    public $no_of_license;
    public function __construct()
    {

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('admin/user_model', 'user_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('admin/Company_model', 'company_model');
    }

    //-----------------------------------------------------------
    public function index()
    {

        $this->load->view('admin/includes/_header');
        $this->load->view('admin/users/user_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }

    public function datatable_json()
    {
        $records['data'] = $this->user_model->get_all_users();
        // prd($records);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $verify = ($row['is_verify'] == 1) ? 'Verified' : 'Pending';
            $data[] = array(
                $row['username'],
                date_time($row['created_at']),
                $row["remarks"],
                '<input class="tgl_checkbox tgl-ios"
				data-id="' . $row['id'] . '"
				id="cb_' . $row['id'] . '"
				type="checkbox"
				' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('admin/users/edit/' . $row['id']) . '"> <i class="fa fa-eye"></i></a>
				<a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/users/edit/' . $row['id']) . '"> <i class="fa fa-pencil-square-o"></i></a>
				<a title="Delete" class="delete btn btn-sm btn-danger text-white" uid=' . $row['id'] . ' title="Delete"> <i class="fa fa-trash-o"></i></a>',
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    //-----------------------------------------------------------
    public function change_status()
    {
        $this->user_model->change_status();
    }

    public function add()
    {

        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            // $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            // $this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/users/add'), 'refresh');
            } else {
                $data = array(
                    'username' => uc($this->input->post('username')),
                    'company_id' => $this->input->post('company_id'),
                    'remarks' => uc($this->input->post('remarks')),
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                    "is_verify" => 1,
                    // 'email' => $this->input->post('email'),
                    // 'mobile_no' => $this->input->post('mobile_no'),
                    // 'address' => $this->input->post('address'),
                );
                $data = $this->security->xss_clean($data);
                $result = $this->user_model->add_user($data);
                if ($result) {

                    // Activity Log
                    $this->activity_model->add_log(1);

                    $this->session->set_flashdata('success', 'User has been added successfully!');
                    redirect(base_url('admin/users'));
                }
            }
        } else {
            $no_of_license = 0;
            // prd($this->session);
            if ($this->session->admin_role_id != 1) {
                $company = $this->company_model->get_company_by_id($this->session->company_id);
                $no_of_license = $company['license_no'];
            }
            $total_users = $this->user_model->get_user_count();
            $remaining_users = $no_of_license - $total_users;
            $data['rmning_users'] = $remaining_users;
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/users/user_add', $data);
            $this->load->view('admin/includes/_footer');
        }

    }

    public function edit($id = 0)
    {

        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            // $this->form_validation->set_rules('firstname', 'Username', 'trim|required');
            // $this->form_validation->set_rules('lastname', 'Lastname', 'trim|required');
            // $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|required');
            // $this->form_validation->set_rules('mobile_no', 'Number', 'trim|required');
            // $this->form_validation->set_rules('status', 'Status', 'trim|required');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/users/user_edit/' . $id), 'refresh');
            } else {
                $data = array(
                    'username' => uc($this->input->post('username')),
                    'company_id' => $this->input->post('company_id'),
                    'remarks' => uc($this->input->post('remarks')),
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                $data = $this->security->xss_clean($data);
                $result = $this->user_model->edit_user($data, $id);
                if ($result) {
                    // Activity Log
                    $this->activity_model->add_log(2);

                    $this->session->set_flashdata('success', 'User has been updated successfully!');
                    redirect(base_url('admin/users'));
                }
            }
        } else {
            $data['user'] = $this->user_model->get_user_by_id($id);

            $this->load->view('admin/includes/_header');
            $this->load->view('admin/users/user_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission
        $id = $this->input->post('uid');

        $this->db->delete('users', array('id' => $id));

        // Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'Use has been deleted successfully!');
        echo json_encode([
            'status' => 1,
            'msg' => 'Deleted',
        ]);
    }

    public function check_username_existance()
    {
        $username = $this->input->post('username');
        $user_id = $this->input->post('uid');
        $res = $this->user_model->check_username_existance($user_id, $username);
        echo json_encode($res);
    }
}