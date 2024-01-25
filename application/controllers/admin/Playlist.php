<?php defined('BASEPATH') or exit('No direct script access allowed');

class Playlist extends My_Controller
{

    public function __construct()
    {
        parent::__construct();

        auth_check(); // check login auth

        $this->rbac->check_module_access();
        $this->load->model('admin/media_master_model');
        $this->load->model('admin/media_model');
        $this->load->model('admin/user_model');
        $this->load->model('admin/playlist_model');
        $this->load->model('admin/activity_model');
        $this->load->model('admin/doctors_model');
        $this->load->model('admin/template_model');
        $this->load->model('admin/flash_content_model');
    }

    public function index()
    {
        $data = [];
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/playlist/list', $data);
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }

    public function add()
    {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('playlist_type', 'Playlist Type', 'trim|required');
            $this->form_validation->set_rules('user', 'User', 'trim|required');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/playlist/add'), 'refresh');
            } else {
                $company_id = $this->session->company_id;
                $playlistData = array(
                    'user_id' => $this->input->post('user'),
                    'playlist_type_id' => $this->input->post('playlist_type'),
                    'flash_content_id' => $this->input->post('flash_content'),
                    'title' => trim(uc($this->input->post('title'))),
                    'company_id' => $company_id ?: '',
                );
                $playlist_id = $this->playlist_model->add_playlist($playlistData);

                $orders_list = $this->input->post('order');
                $media_type = $this->input->post('media_type');
                $media_content = $this->input->post('media_content');
                foreach ($orders_list as $k => $v) {
                    $order_id = $orders_list[$k];
                    $media_type_id = $media_type[$k];
                    $media_id = $media_content[$k];

                    $contentData = [
                        'playlist_id' => $playlist_id,
                        'media_type_id' => $media_type_id,
                        'split_order_id' => $order_id,
                    ];
                    if (in_array($media_type_id, [1, 2, 3, 8])) {
                        $contentData['media_id'] = $media_id;
                    } else if ($media_type_id == 4) {
                        $contentData['template_id'] = $media_id;
                    } else if (in_array($media_type_id, [5, 6, 7])) {
                        $contentData['media_group_id'] = $media_id;
                    } else if (in_array($media_type_id, [9])) {
                        $contentData['user_appointment_tmplt'] = $media_id;
                    }
                    $playlist_content_id = $this->playlist_model->add_playlist_contents($contentData);
                }

                // Activity Log
                $this->activity_model->add_log(1);

                $this->session->set_flashdata('success', 'Playlist has been added successfully!');
                redirect(base_url('admin/playlist'));
            }
        } else {
            $data['playlist_type_list'] = $this->playlist_model->get_playlist_types();
            $data['users_list'] = $this->user_model->get_all_users();
            $data['media_type_list'] = $this->media_model->get_all_media();
            $data['doctors_list'] = $this->doctors_model->get_doctors();
            $data['flash_content_list'] = $this->flash_content_model->get_all_flashcontent();
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/playlist/add_playlist', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function datatable_json()
    {
        $records['data'] = $this->playlist_model->get_all_playlist();
        // prd($records);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = '';
            $data[] = array(
                ++$i,
                $row->title,
                $row->username,
                // '<div style="height:150px;width:100%;">' . $screen . '</div>',
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('admin/playlist/view/' . $row->id) . '"> <i class="fa fa-eye"></i></a>
                <a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/playlist/edit/' . $row->id) . '"> <i class="fa fa-pencil-square-o"></i></a>
                 <a title="Delete" class="delete btn btn-sm btn-danger text-white" playlist_id =' . $row->id . ' ><i class="fa fa-trash-o"></i></a>',
            );
        }

        $records['data'] = $data;
        echo json_encode($records);
    }

    public function delete($id)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('playlists', array('id' => $id));
        $this->db->delete('playlist_contents', array('playlist_id' => $id));

        // Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'Playlist has been deleted successfully!');
        echo json_encode([
            'status' => 1,
            'msg' => 'Deleted',
        ]);
    }

    public function edit($id)
    {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('playlist_type', 'Playlist Type', 'trim|required');
            $this->form_validation->set_rules('user', 'User', 'trim|required');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/playlist/add'), 'refresh');
            } else {
                $company_id = $this->session->company_id;
                $playlistData = array(
                    'id' => $id,
                    'user_id' => $this->input->post('user'),
                    'playlist_type_id' => $this->input->post('playlist_type'),
                    'flash_content_id' => $this->input->post('flash_content'),
                    'title' => trim(uc($this->input->post('title'))),
                    'company_id' => $company_id ?: '',
                );
                $res = $this->playlist_model->update_playlist($playlistData);

                $this->db->delete('playlist_contents', array('playlist_id' => $id));

                $orders_list = $this->input->post('order');
                $media_type = $this->input->post('media_type');
                $media_content = $this->input->post('media_content');

                foreach ($orders_list as $k => $v) {
                    $order_id = $orders_list[$k];
                    $media_type_id = $media_type[$k];
                    $media_id = $media_content[$k];

                    $contentData = [
                        'playlist_id' => $id,
                        'media_type_id' => $media_type_id,
                        'split_order_id' => $order_id,
                    ];
                    if (in_array($media_type_id, [1, 2, 3, 8])) {
                        $contentData['media_id'] = $media_id;
                    } else if ($media_type_id == 4) {
                        $contentData['template_id'] = $media_id;
                    } else if (in_array($media_type_id, [5, 6, 7])) {
                        $contentData['media_group_id'] = $media_id;
                    } else if (in_array($media_type_id, [9])) {
                        $contentData['user_appointment_tmplt'] = $media_id;
                    }
                    $playlist_content_id = $this->playlist_model->add_playlist_contents($contentData);
                }

                // Activity Log
                $this->activity_model->add_log(1);

                $this->session->set_flashdata('success', 'Playlist has been added successfully!');
                redirect(base_url('admin/playlist'));
            }
        } else {
            $company_id = $this->session->userdata['company_id'];
            $data['playlist_type_list'] = $this->playlist_model->get_playlist_types();
            $data['users_list'] = $this->user_model->get_all_users();
            $data['media_type_list'] = $this->media_model->get_all_media();
            $data['doctors_list'] = $this->doctors_model->get_doctors();
            $data['temp_list'] = $this->template_model->get_template($company_id);
            $data['flash_content_list'] = $this->flash_content_model->get_all_flashcontent();
            $data["appointment_templates"] = $this->template_model->get_patient_appointment_templates();
            $edit_data = $this->playlist_model->get_playlist_by_id($id);
            $data['edit_data'] = $edit_data;
            if (empty($this->input->get())) {
                redirect(base_url("admin/playlist/edit/$id?uid=$edit_data->user_id&plid=$edit_data->playlist_type_id"));
            }
            $contents = $this->playlist_model->get_contents($edit_data->id);
            $data['contents'] = $contents;
            // prd($data['contents']);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/playlist/edit_playlist', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function view($pid)
    {
        $screen_data = $this->playlist_model->get_playlist_by_id($pid);
        $data['screen_data'] = $screen_data;
        if ($screen_data) {
            $contents = $this->playlist_model->get_contents($screen_data->id);
            $tags = [];
            foreach ($contents as $k => $v) {
                $tags[] = [
                    'screen_id' => $v->split_order_id,
                    'data' => $v->media_file,
                    'media_type_id' => $v->media_type_id,
                    'media_id' => $v->media_id,
                    'group_id' => $v->media_group_id,
                    'template_id' => $v->template_id,
                ];
            }
            $data['tags'] = $tags;
            $flash_data = $this->flash_content_model->get_flashcontent_by_id($screen_data->flash_content_id);
            if (!empty($flash_data)) {
                $flash_data['style'] = str_replace(['{', '}', ',', '"'], ['', '', ';', ''], $flash_data['property']);
                $data['flash_data'] = $flash_data;
            }
        }
        $this->load->view('admin/playlist/view_playlist', $data);
    }
}