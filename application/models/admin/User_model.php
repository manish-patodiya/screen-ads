<?php
class User_model extends CI_Model
{

    public function add_user($data)
    {

        $this->db->insert('users', $data);
        return true;
    }

    //---------------------------------------------------
    // get all users for server-side datatable processing (ajax based)
    public function get_all_users()
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('*');
        //  $this->db->join("companies", "users.company_id = companies.license");
        $this->db->where('is_admin', 0);
        if ($admin_role_id != 1) {
            $this->db->where("users.company_id=$company_id");
        }
        $this->db->order_by('username', 'asc');
        return $this->db->get('users')->result_array();
    }

    //---------------------------------------------------
    // Get user detial by ID
    public function get_user_by_id($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Edit user Record
    public function edit_user($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('users', $data);
        return true;
    }

    //---------------------------------------------------
    // Change user status
    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('users');
    }

    public function check_username_existance($user_id, $username)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        if ($user_id) {
            $this->db->where_not_in("id", [$user_id]);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        $match = count($result);
        if ($match > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_user_count()
    {
        return $this->db->where(['company_id' => $this->session->company_id])->from("users")->count_all_results();
    }

}