<?php defined('BASEPATH') or exit('No direct script access allowed');

class Media_group extends My_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->has_userdata('is_user_login')) {
            auth_check(); // check login auth
            $this->rbac->check_module_access();
        }
        $this->load->model('admin/Media_group_model', 'media_group_model');
        $this->load->model('admin/Media_master_model', 'media_model');
        $this->load->model('admin/media_model', 'media_t_model');
        $this->load->model('admin/Activity_model', 'activity_model');

    }

    public function index()
    {

        $this->load->view('admin/includes/_header');
        $this->load->view('admin/media_group/media_group_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }

    public function datatable_json()
    {
        $records['data'] = $this->media_group_model->get_all_media_group();
        // prd($records['data']);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $data[] = array(
                ++$i,
                $row['group_name'],
                $row['media_type'],
                $row['remarks'],
                '<input class="tgl_checkbox tgl-ios"
				data-id="' . $row['id'] . '"
				id="cb_' . $row['id'] . '"
				type="checkbox"
				' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="View" class="view btn btn-sm btn-info " href="' . base_url('admin/media_group/media_group_view/' . $row['id']) . '"> <i class="fa fa-eye"></i></a>
                <a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/media_group/edit/' . $row['id']) . '"> <i class="fa fa-pencil-square-o"></i></a>
                 <a title="Delete" class="delete btn btn-sm btn-danger sup_delete"  uid="' . $row['id'] . '" href="#"> <i class="fa fa-trash-o"></i></a>',
            );
        }

        $records['data'] = $data;
        echo json_encode($records);
    }

    public function add()
    {

        $mt = $this->media_t_model->get_all_media();
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('group_name', 'group_name', 'trim|required');
            $this->form_validation->set_rules('media_type', 'media_type', 'trim|required');
            // $this->form_validation->set_rules('media_group', 'media_group', 'trim|required');
            // $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            $this->form_validation->set_rules('status', 'status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/media_group/add'), 'refresh');
            } else {
                $data = array(
                    'group_name' => uc($this->input->post('group_name')),
                    'media_type' => $this->input->post('media_type'),
                    'company_id' => $this->session->company_id,
                    'remarks' => uc($this->input->post('remarks')),
                    'created_at' => date('Y-m-d : h:m:s'),
                );
                $data = $this->security->xss_clean($data);
                // prd($data);
                $media_group_id = $this->media_group_model->add_media_group($data);
                $list = $this->input->post('media_group');
                $result = explode(',',$list);
                foreach ($result as $key => $value) {
                    $content_data = [
                        'media_group_id' => $media_group_id,
                        'media_id' => $value,
                    ];
                    // prd($result);
                    // prd($content_data);
                    $this->media_group_model->add_media_content($content_data);
                }
                if ($media_group_id) {

                    // Activity Log
                    $this->activity_model->add_log(1);

                    $this->session->set_flashdata('success', 'User has been added successfully!');
                    redirect(base_url('admin/media_group'));
                }
            }
        } else {
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/media_group/media_group_add', ['mt' => $mt]);
            $this->load->view('admin/includes/_footer');
        }

    }

    public function content_list()
    {
        $id = $this->input->post('id');
        $result = $this->media_model->get_all_media_content($id);
        echo json_encode([
            "status" => 1,
            "msg" => "Media Group list was  successfully",
            'result' => $result,
        ]);
    }
    public function delete_media_group()
    {
        $id = $this->input->Post('id');
        $media = $this->media_group_model->deleteRow($id);
        echo json_encode([
            "status" => 1,
            "msg" => "Media Master was deleted successfully",
        ]);
    }
    public function change_status()
    {
        $this->media_group_model->change_status();
    }

    public function edit($id)
    {
        $mt = $this->media_t_model->get_all_media();
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('group_name', 'group_name', 'trim|required');
            $this->form_validation->set_rules('media_type', 'media_type', 'trim|required');
            //$this->form_validation->set_rules('duallistbox', 'duallistbox', 'trim|required');
            $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            $this->form_validation->set_rules('status', 'status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/media_group/edit/' . $id), 'refresh');
            } else {
                $list = $this->input->post('media_group');
                $result = explode(',',$list);
                // prd($result);
                $data = array(
                    'group_name' => uc($this->input->post('group_name')),
                    'media_type' => $this->input->post('media_type'),
                    'company_id' => $this->session->company_id,
                    'remarks' => uc($this->input->post('remarks')),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                $data = $this->security->xss_clean($data);
                $media_group_id = $this->media_group_model->edit_media_group($data, $id);

                $this->db->delete('media_group_contents', array('media_group_id' => $id));
                foreach ($result as $key => $value) {
                    $content_data = [
                        'media_group_id' => $id,
                        'media_id' => $value,
                    ];
                    // prd($content_data);
                    $this->media_group_model->add_media_content($content_data);
                }
                if ($media_group_id) {
                    // Activity Log
                    $this->activity_model->add_log(2);

                    $this->session->set_flashdata('success', 'User has been updated successfully!');
                    redirect(base_url('admin/media_group'));
                }
            }
        } else {

            $olddata = $this->media_group_model->get_media_group_by_id($id);
            $mid = $olddata['media_type'];
            $result = $this->media_model->get_all_media_content($mid);
            $data['mt'] = $mt;
            $data['group'] = $olddata;
            $data['list'] = $result;
            // prd($data);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/media_group/media_group_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function get_media_group_contents()
    {
        $id = $this->input->post('mgid');
        $result = $this->media_group_model->get_media_contents($id);
        if ($result) {
            echo json_encode([
                "status" => 1,
                "msg" => 'Fetch Successfully',
                'data' => $result,
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => 'No data found',
                'data' => [],
            ]);
        }
    }
    // public function get_all_media_group()
    // {
    //     $id = $this->input->post('id');
    //     $records['data'] = $this->media_group_model->get_media_group_by_id($id);
    //     $records['data1'] = $this->media_group_model->get_media_contents($id);

    //     // prd($records);
    //     echo json_encode([
    //         "status" => 1,
    //         "records" => $records,
    //     ]);
    // }
    public function media_group_view($id)
    {

        $records['data'] = $this->media_group_model->get_media_group_list_by_id($id);
        $records['data1'] = $this->media_group_model->get_media_contents($id);
        // prd($records);
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/media_group/media_group_view', $records);
        $this->load->view('admin/includes/_footer');
    }
}