<?php defined('BASEPATH') or exit('No direct script access allowed');

class Support_tickets extends My_Controller
{

    public function __construct()
    {

        parent::__construct();

        auth_check(); // check login auth

        $this->rbac->check_module_access();
        $this->load->model('admin/support_tickets_model', 'support_model');
        $this->load->model('admin/Activity_model', 'activity_model');

    }

    public function index()
    {

        $this->load->view('admin/includes/_header');
        $this->load->view('admin/support_tickets/support_tickets_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/support_tickets_view');
        $this->load->view('admin/sub_views/delete_modal');
    }
    public function datatable_json()
    {
        $records['data'] = $this->support_model->get_all_support_tickets();
        //prd($records['data']);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $data[] = array(
                ++$i,
                $row['subject'],
                '<input class="tgl_checkbox tgl-ios"
				data-id="' . $row['id'] . '"
				id="cb_' . $row['id'] . '"
				type="checkbox"
				' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="Description" class="view btn btn-sm btn-info description"  uid="' . $row['id'] . '" href="#"> <i class="fa fa-eye"></i></a>

                <a title="Delete" class="delete btn btn-sm btn-danger sup_delete " uid="' . $row['id'] . '" href="#">
                 <i class="fa fa-trash-o"></i></a>',
            );
        }

        $records['data'] = $data;
        echo json_encode($records);
    }

    public function add()
    {

        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('subject', 'subject', 'trim|required');
            $this->form_validation->set_rules('description', 'description', 'trim|required');
            $this->form_validation->set_rules('status', 'status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/support_tickets/add'), 'refresh');
            } else {
                $data = array(
                    'subject' => uc($this->input->post('subject')),
                    // 'description' => uc($this->input->post('description')),
                    'company_id' => $this->input->post('company_id'),
                    'created_at' => date('Y-m-d : h:m:s'),
                );
                // prd($data);
                $data = $this->security->xss_clean($data);
                $result = $this->support_model->add_support_tickets($data);
                if ($result) {

                    // Activity Log
                    $this->activity_model->add_log(1);

                    $this->session->set_flashdata('success', 'User has been added successfully!');
                    redirect(base_url('admin/support_tickets'));

                }
            }
        } else {
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/support_tickets/support_tickets_add');
            $this->load->view('admin/includes/_footer');
        }

    }

    public function get_all_support()
    {
        $records['data'] = $this->support_model->get_all_support_tickets();
        $support = $this->support_model;
        $id = $this->input->post('id');
        $name = $support->getFields('description', "id='$id'");
        if (!empty($name)) {
            echo json_encode([
                "status" => 1,
                "name" => $name['description'],
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "name" => $name,
            ]);
        }

    }

    public function delete_Support()
    {
        $id = $this->input->post('id');
        $support = $this->support_model->deleteRow($id);
        // prd($support);
        if ($support) {
            echo json_encode([
                "status" => 1,
                "msg" => "Support Tickets was deleted successfully",
            ]);}
    }
    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('support_tickets', array('id' => $id));

// Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'flashcontent has been deleted successfully!');
        redirect(base_url('admin/support_tickets'));
    }

    public function change_status()
    {
        $this->support_model->change_status();
    }
}