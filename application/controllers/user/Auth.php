<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->uri->segment(3) != 'logout' && $this->uri->segment(3) != 'checkLogin' && $this->uri->segment(3) != 'logoutDevice' && $this->uri->segment(3) != 'index' && $this->uri->segment(2) != 'auth') {
            login_check_auth();
        }
        $this->load->library('mailer');
        $this->load->model('user/auth_model', 'auth_model');
    }

    //--------------------------------------------------------------
    public function index()
    {
        if ($this->session->has_userdata('is_user_login')) {
            redirect('user/screen');
        } else {
            redirect('user/auth/login');
        }
    }

    //--------------------------------------------------------------
    public function login()
    {
        if ($this->session->has_userdata('is_user_login')) {
            redirect('user/screen');
        } else if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('error', $data['errors']);
                redirect(base_url('user/auth/login'), 'refresh');
            } else {
                $data = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password'),
                );
                $result = $this->auth_model->login($data);
                // prd($result);
                if ($result) { //username and password matched
                    if ($result['is_verify'] == 0) {
                        $this->session->set_flashdata('error', 'Please verify your email address!');
                        redirect(base_url('user/auth/login'));
                        exit();
                    }
                    if ($result['is_active'] == 0) {
                        $this->session->set_flashdata('error', 'Account is disabled by Admin!');
                        redirect(base_url('user/auth/login'));
                        exit();
                    }
                    //check if login on another device
                    if ($result['session_id'] != "") {
                        $this->session->set_flashdata('notice', 'You have been logged out from other device.!');
                        // prd($this->session);
                    }
                    if ($result['is_admin'] == 0) {
                        $user_data = array(
                            'user_id' => $result['id'],
                            'company_id' => $result['company_id'],
                            'username' => $result['username'],
                            'is_user_login' => true,
                            'dte_activity' => time(),
                            'dte_login' => time(),
                            'flag' => 1,
                        );
                        $this->session->set_userdata($user_data);
                        $sess_id = session_id();
                        // prd($sess_id);
                        $data = array(
                            "session_id" => $sess_id,
                        );
                        $update_result = $this->auth_model->update_data($result['id'], $data);
                        // prd($this->session);
                        if (isset($this->session->userdata['notice'])) {
                            $this->rbac->set_access_in_session(); // set access in session
                            // prd($this->session);
                            redirect(base_url('user/screen/index'), 'refresh');
                        }
                        // prd($this->session);
                        else {
                            $this->rbac->set_access_in_session(); // set access in session
                            redirect(base_url('user/screen/index'), 'refresh');
                        }
                    }} else {
                    $this->session->set_flashdata('errors', 'Invalid Username or Password!');
                    redirect(base_url('user/auth/login'));
                }
            }
        } else {
            $data['title'] = 'Login';
            $data['navbar'] = false;
            $data['sidebar'] = false;
            $data['footer'] = false;
            $data['bg_cover'] = true;

            $this->load->view('admin/includes/_header', $data);
            $this->load->view('user/auth/login');
            $this->load->view('admin/includes/_footer', $data);
        }
    }

    public function checkLogin()
    {
        // prd($this->session->userdata['user_id']);
        $user_id = $this->session->userdata['user_id'];
        $sess_id = session_id();

        $result = $this->auth_model->select_field($user_id, $sess_id);
        // prd($result);
        echo json_encode([
            "status" => $result ? 1 : 0,
        ]);
    }

    //-----------------------------------------------------------------------
    public function logout()
    {
        // if($this->input->get("username")){
        //     $data=array(
        //     "username" => $this->input->get("username"),
        //     // "password" => password_hash($this->input->get('password'), PASSWORD_BCRYPT)
        //     );
        //     $result = $this->auth_model->select_user($data);
        //     // prd($result->id);
        //     if($result){
        //         $data = array(
        //             "session_id" => 0,
        //         );
        //         $this->auth_model->update_data($result->id, $data);
        //         $this->session->sess_destroy();
        //         redirect(base_url('user/auth'), 'refresh');
        //     }
        // }
        $user_id = $this->session->userdata['user_id'];
        $data = array(
            "session_id" => 0,
        );
        $result = $this->auth_model->update_data($user_id, $data);
        if ($result) {
            $this->session->sess_destroy();
            redirect(base_url('user/auth'), 'refresh');
        }
    }
    public function logoutDevice()
    {
        $this->session->sess_destroy();
        redirect(base_url("user/auth"));
    }
} // end class