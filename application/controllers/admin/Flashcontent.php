<?php defined('BASEPATH') or exit('No direct script access allowed');
class Flashcontent extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('admin/Flash_content_model', 'flashcontent_model');
        $this->load->model('admin/language_model', 'language_model');
        $this->load->model('admin/Activity_model', 'activity_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/flashcontent/flashcontent_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');

    }

    public function datatable_json()
    {
        $records['data'] = $this->flashcontent_model->get_all_flashcontent();
        // prd($records);
        $data = array();

        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['is_active'] == 1) ? 'checked' : '';
            $content = $row['content'];
            $positio = $row['position'];

            $data[] = array(
                ++$i,
                $row['title'],
                $row['language'],
                $content,
                $positio,
                // '<img src="' . $row["media_file"] . '" style="width: 37px">',

                '<input class="tgl_checkbox tgl-ios" data-id="' . $row['id'] . '" id="cb_' . $row['id'] . '"
    type="checkbox" ' . $status . '><label for="cb_' . $row['id'] . '"></label>',

                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('admin/flashcontent/view_flashcontent/' . $row['id']) . '"> <i
        class="fa fa-eye"></i></a>
<a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/flashcontent/edit/' . $row['id']) . "?lang=" . $row['language'] . '">
    <i class="fa fa-pencil-square-o"></i></a>
<a title="Delete" class="delete btn btn-sm btn-danger " uid="' . $row['id'] . '" href="#"><i class="fa fa-trash-o"></i></a>',
            );
        }
// prd($records['data']);
        $records['data'] = $data;
        echo json_encode($records);
    }

//-----------------------------------------------------------
    public function change_status()
    {
        $this->flashcontent_model->change_status();
    }

    public function add()
    {
        $lang = $this->language_model->get_all_language();
        $property = $this->flashcontent_model->get_all_property();
        //prd($property);
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('language', 'language', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_rules('position', 'position', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/flashcontent/add'), 'refresh');
            } else {
                // prd($_POST);
                $mfile = '';
                if (!empty($_FILES['logo']['name'])) {
                    $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                    $profileconfig['upload_path'] = './uploads/flash_content/';
                    $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg';
                    $profileconfig['max_size'] = '';
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
                    // prd($error);
                    $file = $this->upload->data();
                    $mfile = base_url('uploads/flash_content/' . $file['file_name']);
                    $file_path = $file['full_path'];
                    $this->load->library('S3upload');
                    $folderName = "flashcontent";
                    $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);
                    if ($s3Upload) {
                        $bucket = 'iddms';
                        $actual_file_path = $folderName . '/' . $file["file_name"];
                        $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                    }
                    // prd($fullFilePath);
                    $mfile = $fullFilePath;
                    // prd($s3Upload);
                }
                $labels = $this->input->post('label');
                $properties = $this->input->post('property');
                $arr = [];
                foreach ($properties as $k => $v) {
                    $arr[$labels[$k]] = $v;
                }
                $json = json_encode($arr);
                $data = array(
                    'title' => uc($this->input->post('title')),
                    'language' => $this->input->post('language'),
                    'content' => ($this->input->post('content')),
                    'company_id' => $this->session->company_id,
                    'position' => $this->input->post('position'),
                    'property' => $json,
                    'media_file' => $mfile,
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                //prd($data);
                $data = $this->security->xss_clean($data);
                $result = $this->flashcontent_model->add_flashcontent($data);
                if ($result) {
                    if ($mfile) {
                        unlink($file['file_path']);
                    }
                    // Activity Log
                    $this->activity_model->add_log(1);

                    $this->session->set_flashdata('success', 'media has been added successfully!');
                    redirect(base_url('admin/flashcontent'));
                }
            }
        } else {
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/flashcontent/flashcontent_add', ['property' => $property]);
            $this->load->view('admin/includes/_footer');
        }

    }

    public function edit($id)
    {
        $lang = $this->language_model->get_all_language();
        $result = $this->flashcontent_model->get_all_flashcontent();

        // prd($result[0]['media_file']);
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('language', 'language', 'trim|required');
            $this->form_validation->set_rules('content', 'content', 'trim|required');
            $this->form_validation->set_rules('position', 'position', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/flashcontent/edit/' . $id), 'refresh');
            } else {
                $mfile = '';
                if (!empty($_FILES['logo']['name'])) {
                    $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
                    $profileconfig['upload_path'] = './uploads/flash_content/';
                    $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg';
                    $profileconfig['max_size'] = '';
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
                    $mfile = base_url('uploads/flash_content/' . $file['file_name']);
                    $file_path = $file['full_path'];
                    $this->load->library('S3upload');
                    $folderName = "flashcontent";
                    $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);
                    if ($s3Upload) {
                        $bucket = 'iddms';
                        $actual_file_path = $folderName . '/' . $file["file_name"];
                        $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                    }
                    $mfile = $fullFilePath;
                }
                $labels = $this->input->post('label');
                $properties = $this->input->post('property');
                $arr = [];
                foreach ($properties as $k => $v) {
                    $arr[$labels[$k]] = $v;
                }
                $json = json_encode($arr);
                $data = array(
                    'title' => uc($this->input->post('title')),
                    'language' => $this->input->post('language'),
                    'content' => $this->input->post('content'),
                    'company_id' => $this->session->company_id,
                    'property' => $json,
                    'position' => $this->input->post('position'),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                );
                if ($mfile) {
                    $data['media_file'] = $mfile;
                }
                $data = $this->security->xss_clean($data);
                $result = $this->flashcontent_model->edit_flashcontent($data, $id);
                if ($result) {
                    // Activity Log
                    if ($mfile) {
                        unlink($file['file_path']);
                    }
                    $this->activity_model->add_log(2);

                    $this->session->set_flashdata('success', 'flashcontent has been updated successfully!');
                    redirect(base_url('admin/flashcontent'));
                }
            }
        } else {
            $data['flashcontent'] = $this->flashcontent_model->get_flashcontent_by_id($id);
            $pro = $data['flashcontent']['property'];
            $data['json'] = (array) json_decode($pro);
            $data['property'] = $this->flashcontent_model->get_all_property();
            $data['lang'] = $lang;
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/flashcontent/flashcontent_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function delete_flash()
    {
        $id = $this->input->Post('id');
        $flash = $this->flashcontent_model->deleteRow($id);

        echo json_encode([
            'status' => 1,
            'msg' => 'Flash Content was deleted successfully',
        ]);

    }
    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('flashcontent', array('id' => $id));

// Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'flashcontent has been deleted successfully!');
        redirect(base_url('admin/flashcontent'));
    }
    public function status()
    {
        $id = $this->input->Post('status');
        // prd($id);
        $result = $this->flashcontent_model->status($id);
        if ($result) {
            echo json_encode([
                "status" => 1,
            ]);
        }
    }
    public function properties()
    {
        $data = $this->flashcontent_model->get_all_property();
        if ($data) {
            echo json_encode([
                "status" => 1,
                "data" => $data,
            ]);
        }
    }
    public function view_flashcontent($id = 0)
    {
        // $id = $this->input->get('id');
        // prd($id);

        // $content = file_get_contents($_FILES['logo']['tmp_name']);
        // $file = fopen($_FILES['logo']['name'], 'r');
        // prd($file);
        // die;
        if ($id != 0) {
            $result = $this->flashcontent_model->properties($id);
            // prd($result);
            $data['content'] = $result['content'];
            $data['position'] = $result['position'];
            $data['img'] = $result['media_file'];
            $data['css'] = (array) json_decode($result["property"]);
        } else {
            $data['content'] = $this->input->post('content');
            $data['img'] = $this->input->get('img');
            $data['position'] = $this->input->post('position');
            $properties = $this->input->post('property');
            $css = [];
            $labels = $this->input->post('label');
            foreach ($labels as $k => $v) {
                $css[$v] = $properties[$k];
            }
            $data['css'] = $css;
        }
        // prd($data);
        $this->load->view('admin/flashcontent/view_flashcontent', $data);
    }
    public function property()
    {
        $id = $this->input->post('id');
        $data['flashcontent'] = $this->flashcontent_model->get_flashcontent_by_id($id);
        $pro = $data['flashcontent']['property'];
        $data['json'] = (array) json_decode($pro);
        echo json_encode([
            "status" => 1,
            "data" => $data,
        ]);
    }

}