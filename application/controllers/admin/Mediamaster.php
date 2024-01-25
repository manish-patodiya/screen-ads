<?php defined('BASEPATH') or exit('No direct script access allowed');
class Mediamaster extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->has_userdata('is_user_login')) {
            auth_check(); // check login auth
            $this->rbac->check_module_access();
        }

        $this->load->model('admin/Media_master_model', 'media_model');
        $this->load->model('admin/Media_group_model', 'media_group_model');
        $this->load->model('admin/media_model', 'media_t_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('admin/Template_model', 'template_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/media/media_master_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }
    public function media_cards()
    {
        $media = $this->input->get('media');
        // prd($media);
        $filters = [];
        if ($media) {
            $filters["media_id"] = $media;
        }
        // prd($filters);
        $records = $this->media_model->get_all_media($filters);
        // prd($records);
        if ($records) {
            echo json_encode([
                "status" => 1,
                "data" => $records,
            ]);
        } else {
            echo json_encode([
                "msg" => "No data available",
            ]);
        }
        // prd($media);

    }
    public function datatable_json()
    {
        $records['data'] = $this->media_model->get_all_media();
        // prd($records);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $data[] = array(
                ++$i,
                $row['media_type'],
                $row['media_name'],
                '<img src="' . base_url() . '/uploads/media_file/' . $row["media_file"] . '" style="width: 37px">',
                $row['remarks'],
                '<input class="tgl_checkbox tgl-ios" data-id="' . $row['id'] . '" id="cb_' . $row['id'] . '"
    type="checkbox" ' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('admin/mediamaster/edit/' . $row['id']) . '"> <i
        class="fa fa-eye"></i></a>
<a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/mediamaster/edit/' . $row['id']) . '">
    <i class="fa fa-pencil-square-o"></i></a>
<a title="Delete" class="delete btn btn-sm btn-danger"  uid="' . $row['id'] . '"href="#"> <i class="fa fa-trash-o"></i></a>',
            );
        }
// prd($records['data']);
        $records['data'] = $data;
        echo json_encode($records);
    }

//-----------------------------------------------------------
    public function change_status()
    {
        $this->media_model->change_status();
    }

    public function add()
    {
        $mt = $this->media_t_model->get_all_media();

        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('media_type', 'Media Type', 'trim|required');
            $this->form_validation->set_rules('media_name', 'Media Name', 'trim|required');
// $this->form_validation->set_rules('mediafile', 'Media file', 'trim|required');
            // $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/mediamaster/add'), 'refresh');
            } else {
                $mfile = '';
                $mfile = $this->input->post('youtube');
                if (!empty($_FILES['media_file']['name'])) {
                    $ext = pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION);
                    $profileconfig['upload_path'] = './uploads/media_file/';
                    $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg|mp4|mp3|mpc|mkv';
                    $profileconfig['max_size'] = '';
                    $profileconfig['remove_spaces'] = false;
                    $profileconfig['overwrite'] = false;
                    $profileconfig['max_width'] = '';
                    $profileconfig['max_height'] = '';
                    $new_name = "media_file" . time();
                    $profileconfig['file_name'] = $new_name;
                    $this->upload->initialize($profileconfig);
                    if (!$this->upload->do_upload('media_file')) {
                        $data = array(
                            'errors' => $this->upload->display_errors(),
                        );
                        $this->session->set_flashdata('errors', $data['errors']);
                        redirect(base_url('admin/mediamaster/add/'), 'refresh');
                    }
                    $file = $this->upload->data();
                    $file_path = $file['full_path'];
                    $this->load->library('S3upload');
                    $folderName = "media_file";
                    $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);
                    if ($s3Upload) {
                        $bucket = 'iddms';
                        $actual_file_path = $folderName . '/' . $file["file_name"];
                        $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                    }
                    $mfile = $fullFilePath;
                    if ($fullFilePath) {
                        unlink($file['full_path']);
                    }
                }
                // $urlarr= explode("/",$mfile);
                // $url=$urlarr[3];
                // if(strlen($url) > 11){
                //     $urlarr = explode("&",$url);
                //     $url = substr($urlarr[0],8);
                //     $mfile="https://youtu.be/".$url;
                // prd($mfile);
                // }
                $data = array(
                    'media_type_id' => $this->input->post('media_type'),
                    'company_id' => $this->session->company_id,
                    'media_name' => uc($this->input->post('media_name')),
                    'media_file' => $mfile,
                    'remarks' => uc($this->input->post('remarks')),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                // prd($data);
                $data = $this->security->xss_clean($data);
                $result = $this->media_model->add_media($data);
                if ($result) {
                    $this->activity_model->add_log(1);
                    $this->session->set_flashdata('success', 'Media has been added successfully!');
                    redirect(base_url('admin/mediamaster'));
                }
            }
        } else {
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/media/media_master_add', ['mt' => $mt]);
            $this->load->view('admin/includes/_footer');
        }

    }

    public function edit($id)
    {
        $mt = $this->media_t_model->get_all_media();
        $result = $this->media_model->get_all_media();
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('media_type', 'Media Type', 'trim|required');
            $this->form_validation->set_rules('media_name', 'Media Name', 'trim|required');
            $this->form_validation->set_rules('remarks', 'Remarks', 'trim');
            $this->form_validation->set_rules('status', 'Status', 'trim');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/mediamaster/edit/' . $id), 'refresh');
            } else {
                $mfile = '';
                if (!empty($_FILES['media_file']['name'])) {
                    $ext = pathinfo($_FILES['media_file']['name'], PATHINFO_EXTENSION);
                    $profileconfig['upload_path'] = './uploads/media_file/';
                    $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg|mp4|mp3|mpc|mkv';
                    $profileconfig['max_size'] = '0';
                    $profileconfig['remove_spaces'] = false;
                    $profileconfig['overwrite'] = false;
                    $profileconfig['max_width'] = '';
                    $profileconfig['max_height'] = '';
                    $new_name = "media_file" . time();
                    $profileconfig['file_name'] = $new_name;
                    $this->upload->initialize($profileconfig);
                    if (!$this->upload->do_upload('media_file')) {
                        $data = array(
                            'errors' => $this->upload->display_errors(),
                        );
                        $this->session->set_flashdata('errors', $data['errors']);
                        redirect(base_url('admin/mediamaster/edit/' . $id), 'refresh');
                    }
                    $file = $this->upload->data();
                    $mfile = base_url('uploads/media_file/' . $file['file_name']);
                    $file_path = $file['full_path'];
                    $this->load->library('S3upload');
                    $folderName = "media_file";
                    $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);
                    if ($s3Upload) {
                        $bucket = 'iddms';
                        $actual_file_path = $folderName . '/' . $file["file_name"];
                        $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                    }
                    // prd($fullFilePath);
                    $mfile = $fullFilePath;
                    if ($fullFilePath) {
                        unlink($file['file_path']);
                    }
                }

                $data = array(
                    'media_type_id' => $this->input->post('media_type'),
                    'company_id' => $this->session->company_id,
                    'media_name' => uc($this->input->post('media_name')),
                    'remarks' => uc($this->input->post('remarks')),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                // prd($data);
                if (!empty($_FILES['media_file']['name'])) {
                    $data = array_merge($data, ['media_file' => $mfile]);
                }
                $data = $this->security->xss_clean($data);
                $result = $this->media_model->edit_media($data, $id);
                if ($result) {
// Activity Log
                    $this->activity_model->add_log(2);

                    $this->session->set_flashdata('success', 'media has been updated successfully!');
                    redirect(base_url('admin/mediamaster'));
                }
            }
        } else {
            $data['media'] = $this->media_model->get_media_by_id($id);
            //prd($data);
            $data['mt'] = $mt;
//prd($data);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/media/media_master_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('media_master', array('id' => $id));

// Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'media has been deleted successfully!');
        redirect(base_url('admin/mediamaster'));
    }
    public function delete_media()
    {
        $id = $this->input->Post('id');
        $media = $this->media_model->deleteRow($id);
        echo json_encode([
            "status" => 1,
            "msg" => "Media Master was deleted successfully",
        ]);
    }

    public function getMediaByType()
    {
        $id = $this->input->get('id');
        $res = [];
        if (in_array($id, [1, 2, 3, 8])) {
            $res = $this->media_model->getMediaByType($id);
        } else if ($id == 4) {
            $res = $this->template_model->get_template($this->session->userdata['company_id']);
        } else if ($id == 9) {
            $res = $this->template_model->get_patient_appointment_templates();
        } else if (in_array($id, [5, 6, 7])) {
            $type = $id - 4;
            $res = $this->media_group_model->get_media_by_media_type_id($type);
        }
        if (!empty($res)) {
            echo json_encode([
                'status' => 1,
                'data' => $res,
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'data' => $res,
            ]);
        }
    }

    public function getMediaTypeExtension()
    {
        $id = $this->input->post('id');
        $result = $this->media_t_model->getMediaTypeExtension($id);
        // prd($extension);
        echo json_encode([
            'status' => 1,
            'result' => $result,
        ]);

    }
}