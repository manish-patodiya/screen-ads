<?php defined('BASEPATH') or exit('No direct script access allowed');

class General_settings extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->helper('download');

        $this->load->library('zip');

        $this->load->model('admin/setting_model', 'setting_model');
    }

    //-------------------------------------------------------------------------
    // General Setting View
    public function index()
    {

        $data['general_settings'] = $this->setting_model->get_general_settings();
        $data['languages'] = $this->setting_model->get_all_languages();

        $data['title'] = 'General Setting';

        $this->load->view('admin/includes/_header', $data);
        $this->load->view('admin/general_settings/setting', $data);
        $this->load->view('admin/includes/_footer');
    }
    public function backup_export()
    {

        $data['title'] = 'Export Database';

        $this->load->view('admin/includes/_header');

        $this->load->view('admin/export/db_export', $data);

        $this->load->view('admin/includes/_footer');

    }
    public function dbexport()
    {

        $this->load->dbutil();

        $db_format = array(

            'ignore' => array($this->ignore_directories),

            'format' => 'zip',

            'filename' => 'my_db_backup.sql',

            'add_insert' => true,

            'newline' => "\n",

        );

        $backup = &$this->dbutil->backup($db_format);

        $dbname = 'backup-on-' . date('Y-m-d') . '.zip';

        $save = 'uploads/db_backup/' . $dbname;

        write_file($save, $backup);

        force_download($dbname, $backup);

    }
    //-------------------------------------------------------------------------
    public function add()
    {
        $this->rbac->check_operation_access(); // check opration permission

        $data = array(
            'application_name' => $this->input->post('application_name'),
            'timezone' => $this->input->post('timezone'),
            'currency' => $this->input->post('currency'),
            'default_language' => $this->input->post('language'),
            'copyright' => $this->input->post('copyright'),
            'email_from' => $this->input->post('email_from'),
            'smtp_host' => $this->input->post('smtp_host'),
            'smtp_port' => $this->input->post('smtp_port'),
            'smtp_user' => $this->input->post('smtp_user'),
            'smtp_pass' => $this->input->post('smtp_pass'),
            'facebook_link' => $this->input->post('facebook_link'),
            'twitter_link' => $this->input->post('twitter_link'),
            'google_link' => $this->input->post('google_link'),
            'youtube_link' => $this->input->post('youtube_link'),
            'linkedin_link' => $this->input->post('linkedin_link'),
            'instagram_link' => $this->input->post('instagram_link'),
            'recaptcha_secret_key' => $this->input->post('recaptcha_secret_key'),
            'recaptcha_site_key' => $this->input->post('recaptcha_site_key'),
            'recaptcha_lang' => $this->input->post('recaptcha_lang'),
            'created_date' => date('Y-m-d : h:m:s'),
            'updated_date' => date('Y-m-d : h:m:s'),
        );

        $old_logo = $this->input->post('old_logo');
        $old_favicon = $this->input->post('old_favicon');

        $path = "";

        // if (!empty($_FILES['logo']['name'])) {
        //     $this->functions->delete_file($old_logo);

        //     $result = $this->functions->file_insert($path, 'logo', 'image', '9097152');
        //     if ($result['status'] == 1) {
        //         $data['logo'] = $path . $result['msg'];
        //     } else {
        //         $this->session->set_flashdata('error', $result['msg']);
        //         redirect(base_url('admin/general_settings'), 'refresh');
        //     }
        // }

        // // favicon
        // if (!empty($_FILES['favicon']['name'])) {
        //     $this->functions->delete_file($old_favicon);

        //     $result = $this->functions->file_insert($path, 'favicon', 'image', '197152');
        //     if ($result['status'] == 1) {
        //         $data['favicon'] = $path . $result['msg'];
        //     } else {
        //         $this->session->set_flashdata('error', $result['msg']);
        //         redirect(base_url('admin/general_settings'), 'refresh');
        //     }
        // }
        // prd($_FILES);
        if (!empty($_FILES['logo']['name'])) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $profileconfig['upload_path'] = './assets/img/';
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
            $path = base_url('assets/img/' . $file['file_name']);
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
            $data['logo'] = $fullFilePath;
            // prd($fullFilePath);
            // $data['favicon'] = $fullFilePath;
            if ($fullFilePath) {
                unlink($file['file_path']);
            }
        }
        if (!empty($_FILES['favicon']['name'])) {
            $ext = pathinfo($_FILES['favicon']['name'], PATHINFO_EXTENSION);
            $profileconfig['upload_path'] = './assets/img';
            $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg';
            $profileconfig['max_size'] = '2000000';
            $profileconfig['remove_spaces'] = false;
            $profileconfig['overwrite'] = false;
            $profileconfig['max_width'] = '';
            $profileconfig['max_height'] = '';
            $new_name = "favicon" . time();
            $profileconfig['file_name'] = $new_name;
            $this->upload->initialize($profileconfig);
            if (!$this->upload->do_upload('favicon')) {
                $error = array('error' => $this->upload->display_errors());
                $error['error'];
            }
            $file = $this->upload->data();
            // $doctors_image = base_url('assets/img/' . $file['file_name']);
            $file_path = $file['full_path'];
            // prd($file_path);
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
            // prd($fullFilePath);
            $data['favicon'] = $fullFilePath;
            // prd($fullFilePath);
            if ($fullFilePath) {
                unlink($file['file_path']);
            }
        }
        // prd($data);
        $data = $this->security->xss_clean($data);
        $result = $this->setting_model->update_general_setting($data);
        if ($result) {
            $this->session->set_flashdata('success', 'Setting has been changed Successfully!');
            redirect(base_url('admin/general_settings'), 'refresh');
        }
    }

    /*--------------------------
    Email Template Settings
    --------------------------*/

    // ------------------------------------------------------------
    public function email_templates()
    {
        $this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post()) {
            $this->form_validation->set_rules('subject', 'Email Subject', 'trim|required');
            $this->form_validation->set_rules('content', 'Email Body', 'trim|required');
            if ($this->form_validation->run() == false) {
                echo validation_errors();
            } else {

                $id = $this->input->post('id');

                $data = array(
                    'subject' => $this->input->post('subject'),
                    'body' => $this->input->post('content'),
                    'last_update' => date('Y-m-d H:i:s'),
                );
                $data = $this->security->xss_clean($data);
                $result = $this->setting_model->update_email_template($data, $id);
                if ($result) {
                    echo "true";
                }
            }
        } else {
            $data['title'] = '';
            $data['templates'] = $this->setting_model->get_email_templates();

            $this->load->view('admin/includes/_header');
            $this->load->view('admin/general_settings/email_templates/templates_list', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    // ------------------------------------------------------------
    // Get Email Template & Related variables via Ajax by ID
    public function get_email_template_content_by_id()
    {
        $id = $this->input->post('template_id');

        $data['template'] = $this->setting_model->get_email_template_content_by_id($id);

        $variables = $this->setting_model->get_email_template_variables_by_id($id);

        $data['variables'] = implode(',', array_column($variables, 'variable_name'));

        echo json_encode($data);
    }

    //---------------------------------------------------------------
    //
    public function email_preview()
    {
        if ($this->input->post('content')) {
            $data['content'] = $this->input->post('content');
            $data['head'] = $this->input->post('head');
            $data['title'] = 'Send Email to Subscribers';
            echo $this->load->view('admin/general_settings/email_templates/email_preview', $data, true);
        }
    }

}