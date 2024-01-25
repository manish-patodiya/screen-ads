<?php defined('BASEPATH') or exit('No direct script access allowed');
class Departments extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('admin/department_model', 'department_model');
        $this->load->model('admin/language_model', 'language_model');
        $this->load->model('admin/Activity_model', 'activity_model');
    }

    //-----------------------------------------------------------
    public function index()
    {

        $this->load->view('admin/includes/_header');
        $this->load->view('admin/departments/department_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }

    public function datatable_json()
    {
        $records['data'] = $this->department_model->get_all_department();
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $data[] = array(
                ++$i,
                $row['language'],
                $row['department_name'],
                $row['remarks'],
                '<input class="tgl_checkbox tgl-ios"
				data-id="' . $row['id'] . '"
				id="cb_' . $row['id'] . '"
				type="checkbox"
				' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/departments/edit/' . $row['id'] . "?lang=" . $row['language']) . '"> <i class="fa fa-pencil-square-o"></i></a>
                 <a title="Delete" class="delete btn btn-sm btn-danger sup_delete"  uid="' . $row['id'] . '" href="#"> <i class="fa fa-trash-o"></i></a>',
            );
        }

        $records['data'] = $data;
        echo json_encode($records);
    }

    //-----------------------------------------------------------
    public function change_status()
    {
        $this->department_model->change_status();
    }

    public function add()
    {
        $lang = $this->language_model->get_all_language();
        // prd($lang);
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('language', 'language', 'trim|required');
            $this->form_validation->set_rules('depart-name', 'depart-name', 'trim|required');
            // $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/departments/add'), 'refresh');
            } else {
                $data = array(
                    'language' => $this->input->post('language'),
                    'department_name' => $this->input->post('depart-name'),
                    'company_id' => $this->session->company_id,
                    'remarks' => uc($this->input->post('remarks')),
                    'created_at' => date('Y-m-d : h:m:s'),
                );
                // prd($data);
                $data = $this->security->xss_clean($data);
                // prd($name);
                $result = $this->department_model->add_department($data);
                if ($result) {

                    // Activity Log
                    $this->activity_model->add_log(1);

                    $this->session->set_flashdata('success', 'User has been added successfully!');
                    redirect(base_url('admin/departments'));
                }
            }
        } else {
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/departments/department_add', ['lang' => $lang]);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function edit($id = 0)
    {
        $lang = $this->language_model->get_all_language();
        // prd($lang);
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('language', 'language', 'trim|required');
            $this->form_validation->set_rules('department_name', 'department_name', 'trim|required');
            // $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/departments/edit/' . $id), 'refresh');
            } else {
                $data = array(
                    'language' => $this->input->post('language'),
                    'department_name' => $this->input->post('department_name'),
                    'company_id' => $this->session->company_id,
                    'remarks' => uc($this->input->post('remarks')),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                $data = $this->security->xss_clean($data);
                $result = $this->department_model->edit_department($data, $id);
                if ($result) {
                    // Activity Log
                    $this->activity_model->add_log(2);

                    $this->session->set_flashdata('success', 'User has been updated successfully!');
                    redirect(base_url('admin/departments'));
                }
            }
        } else {
            $data['department'] = $this->department_model->get_department_by_id($id);
            $data['lang'] = $lang;
            // prd($data);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/departments/department_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function delete_Department()
    {
        $id = $this->input->Post('id');
        $department = $this->department_model->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Department was deleted successfully',
        ]);
    }

    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('departments', array('id' => $id));

        // Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'Use has been deleted successfully!');
        redirect(base_url('admin/departments'));
    }
}