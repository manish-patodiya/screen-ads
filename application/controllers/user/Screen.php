<?php defined('BASEPATH') or exit('No direct script access allowed');

class Screen extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        user_auth_check(); // check login auth
        $this->load->model('user/auth_model');
        $this->load->model('admin/media_master_model');
        $this->load->model('admin/media_model');
        $this->load->model('admin/user_model');
        $this->load->model('admin/playlist_model');
        $this->load->model('admin/activity_model');
        $this->load->model('admin/doctors_model');
        $this->load->model('admin/template_model');
        $this->load->model('admin/flash_content_model', 'flashcontent_model');
    }

    //--------------------------------------------------------------
    public function index()
    {
        $uid = $this->session->user_id;
        $sess_id= session_id();
        $result = $this->auth_model->select_field($uid, $sess_id);
        // prd($result);
        if($result != ""){
        $screen_data = $this->playlist_model->get_playlist_by_user_id($uid);
        $data['screen_data'] = $screen_data;
        if ($screen_data && $this->session) {
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
            $flash_data = $this->flashcontent_model->get_flashcontent_by_id($screen_data->flash_content_id);
            if (!empty($flash_data)) {
                $flash_data['style'] = str_replace(['{', '}', ',', '"'], ['', '', ';', ''], $flash_data['property']);
                $data['flash_data'] = $flash_data;
            }
        }
        // prd($data);
        $this->load->view('user/screen', $data);
    }
    else{
        // echo json_encode([
        //     "status"=>0,
        // ]);
        redirect(base_url("user/auth/logoutDevice"));
    }
    }

    public function get_template()
    {
        $id = $this->input->post('tid');
        $res = $this->template_model->get_template_by_id($id);
        $doc_cards = [];
        $doctors_list = $this->doctors_model->get_doctors_with_info();
        foreach ($doctors_list as $k => $doc) {
            $doc_cards[] = str_replace(
                ['{department}', '{name}', '{qualification}', '{affiliation}', '{morning_time}', '{evening_time}', '{image}'],
                [ucfirst($doc->department_name), ucwords($doc->name), $doc->qualifications, $doc->affiliations, $doc->availabilities, $doc->availabilities, $doc->image],
                $res['content']);
        }
        if (!empty($res)) {
            echo json_encode([
                'status' => 1,
                "msg" => "Fetch Successfully!",
                'data' => $doc_cards,
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                "msg" => "No Data Found",
                'data' => [],
            ]);
        }
    }

}