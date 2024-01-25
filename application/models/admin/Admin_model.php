<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    public function get_user_detail()
    {
        $id = $this->session->userdata('admin_id');
        $query = $this->db->get_where('admin', array('admin_id' => $id));
        return $result = $query->row_array();
    }
    //--------------------------------------------------------------------
    public function update_user($data)
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
    //-----------------------------------------------------
    public function get_admin_roles()
    {
        $this->db->from('admin_roles');
        $this->db->where('admin_role_status', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    //-----------------------------------------------------
    public function get_admin_by_id($id)
    {
        $this->db->from('admin');
        $this->db->join('admin_roles', 'admin_roles.admin_role_id=admin.admin_role_id');
        $this->db->where('admin_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    //-----------------------------------------------------
    public function get_all()
    {
        $this->db->from('admin');
        $this->db->join('admin_roles', 'admin_roles.admin_role_id=admin.admin_role_id');
        if ($this->session->userdata('filter_type') != '') {
            $this->db->where('admin.admin_role_id', $this->session->userdata('filter_type'));
        }

        if ($this->session->userdata('filter_status') != '') {
            $this->db->where('admin.is_active', $this->session->userdata('filter_status'));
        }

        $filterData = $this->session->userdata('filter_keyword');
        if ($filterData) {
            $this->db->like('admin_roles.admin_role_title', $filterData);
            $this->db->or_like('admin.firstname', $filterData);
            $this->db->or_like('admin.lastname', $filterData);
            $this->db->or_like('admin.email', $filterData);
            $this->db->or_like('admin.mobile_no', $filterData);
            $this->db->or_like('admin.username', $filterData);
        }

        $this->db->where('admin.is_supper !=', 1);
        $this->db->order_by('admin.admin_id', 'desc');
        $query = $this->db->get();
        return $query->num_rows() > 0 ? $query->result_array() : array();
    }

    //-----------------------------------------------------
    public function add_admin($data)
    {
        $this->db->insert('admin', $data);
        return true;
    }

    //---------------------------------------------------
    // Edit Admin Record
    public function edit_admin($data, $id)
    {
        $this->db->where('admin_id', $id);
        $this->db->update('admin', $data);
        return true;
    }

    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('admin_id', $this->input->post('id'));
        $this->db->update('admin');
    }

    //-----------------------------------------------------
    public function delete($id)
    {
        $this->db->where('admin_id', $id);
        $this->db->delete('admin');
    }

    public function check_username_existance($admin_id, $username)
    {
        $this->db->select('*');
        $this->db->from('admin');
        $this->db->where('username', $username);
        if ($admin_id) {
            $this->db->where_not_in("admin_id", [$admin_id]);
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

}