<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function login($data)
    {
        $this->db->select('*');
        $this->db->from('users');
        // $this->db->join('companies', 'companies.admin_id=admin.admin_id', 'left');
        $this->db->where('users.username', $data['username']);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {
            return false;
        } else {
            //Compare the password attempt with the password we have stored.
            $result = $query->row_array();
            $validPassword = password_verify($data['password'], $result['password']);
            if ($validPassword) {
                return $result = $query->row_array();
            }
        }
    }

    //--------------------------------------------------------------------
    public function register($data)
    {
        $this->db->insert('admin', $data);
        return true;
    }

    //--------------------------------------------------------------------
    public function email_verification($code)
    {
        $this->db->select('email, token, is_active');
        $this->db->from('admin');
        $this->db->where('token', $code);
        $query = $this->db->get();
        $result = $query->result_array();
        $match = count($result);
        if ($match > 0) {
            $this->db->where('token', $code);
            $this->db->update('admin', array('is_verify' => 1, 'token' => ''));
            return true;
        } else {
            return false;
        }
    }

    //============ Check User Email ============
    public function check_user_mail($email)
    {
        $result = $this->db->get_where('admin', array('email' => $email));

        if ($result->num_rows() > 0) {
            $result = $result->row_array();
            return $result;
        } else {
            return false;
        }
    }

    //============ Update Reset Code Function ===================
    public function update_reset_code($reset_code, $user_id)
    {
        $data = array('password_reset_code' => $reset_code);
        $this->db->where('admin_id', $user_id);
        $this->db->update('admin', $data);
    }

    //============ Activation code for Password Reset Function ===================
    public function check_password_reset_code($code)
    {

        $result = $this->db->get_where('admin', array('password_reset_code' => $code));
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    //============ Reset Password ===================
    public function reset_password($id, $new_password)
    {
        $data = array(
            'password_reset_code' => '',
            'password' => $new_password,
        );
        $this->db->where('password_reset_code', $id);
        $this->db->update('admin', $data);
        return true;
    }

    //--------------------------------------------------------------------
    public function get_admin_detail()
    {
        $id = $this->session->userdata('admin_id');
        $query = $this->db->get_where('admin', array('admin_id' => $id));
        return $result = $query->row_array();
    }

    //--------------------------------------------------------------------
    public function update_admin($data)
    {
        $id = $this->session->userdata('admin_id');
        $this->db->where('admin_id', $id);
        $this->db->update('admin', $data);
        return true;
    }

    //--------------------------------------------------------------------
    public function change_pwd($data, $id)
    {
        $this->db->where('admin_id', $id);
        $this->db->update('admin', $data);
        return true;
    }

    public function update_data($user_id, $data)
    {
        // prd($data);
        // pr($data);
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        // prd($this->db->last_query());
        return true;
    }
    
    // public function update_session($user_id, $data)
    // {
    //     // prd($data);
    //     // pr($data);
    //     $this->db->where('id', $user_id);
    //     $this->db->update('users', $data);
    //     prd($this->db->last_query());
    //     return $this->db->get("users")->affected_rows();
    // }
    public function select_field($id,$fields)
    {
        $this->db->where('id',$id)
        ->where('session_id',$fields);
        return $this->db->get("users")->row();
    }

    public function select_user($data)
    {
    $this->db->where('username',$data['username']);
    // ->where('password',$data['password']);
    return $this->db->get("users")->row();
    }
}