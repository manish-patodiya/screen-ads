<?php
class Admin_roles_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    //-----------------------------------------------------
    public function get_role_by_id($id)
    {
        $this->db->from('admin_roles');
        $this->db->where('admin_role_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    //-----------------------------------------------------
    public function get_all()
    {
        $this->db->from('admin_roles');
        $query = $this->db->get();
        return $query->result_array();
    }

    //-----------------------------------------------------
    public function insert()
    {
        $this->db->set('admin_role_title', $this->input->post('admin_role_title'));
        $this->db->set('admin_role_status', $this->input->post('admin_role_status'));
        $this->db->set('admin_role_created_on', date('Y-m-d h:i:sa'));
        $this->db->insert('admin_roles');
    }

    //-----------------------------------------------------
    public function update()
    {
        $this->db->set('admin_role_title', $this->input->post('admin_role_title'));
        $this->db->set('admin_role_status', $this->input->post('admin_role_status'));
        $this->db->set('admin_role_modified_on', date('Y-m-d h:i:sa'));
        $this->db->where('admin_role_id', $this->input->post('admin_role_id'));
        $this->db->update('admin_roles');
    }

    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('admin_role_status', $this->input->post('status'));
        $this->db->where('admin_role_id', $this->input->post('id'));
        $this->db->update('admin_roles');
    }

    //-----------------------------------------------------
    public function delete($id)
    {
        $this->db->where('admin_role_id', $id);
        $this->db->delete('admin_roles');
    }

    //-----------------------------------------------------
    public function get_modules()
    {
        $this->db->from('module');
        $this->db->order_by('sort_order', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    //-----------------------------------------------------
    public function set_access()
    {
        if ($this->input->post('status') == 1) {
            $this->db->set('admin_role_id', $this->input->post('admin_role_id'));
            $this->db->set('module', $this->input->post('module'));
            $this->db->set('operation', $this->input->post('operation'));
            $this->db->insert('module_access');
        } else {
            $this->db->where('admin_role_id', $this->input->post('admin_role_id'));
            $this->db->where('module', $this->input->post('module'));
            $this->db->where('operation', $this->input->post('operation'));
            $this->db->delete('module_access');
        }
    }
    //-----------------------------------------------------
    public function get_access($admin_role_id)
    {
        $this->db->from('module_access');
        $this->db->where('admin_role_id', $admin_role_id);
        $query = $this->db->get();
        $data = array();
        foreach ($query->result_array() as $v) {
            $data[] = $v['module'] . '/' . $v['operation'];
        }
        return $data;
    }

    /* SIDE MENU & SUB MENU */

    //-----------------------------------------------------
    public function get_all_module()
    {
        $this->db->select('*');
        $this->db->order_by('sort_order', 'asc');
        $query = $this->db->get('module');
        return $query->result_array();
    }

    //-----------------------------------------------------
    public function add_module($data)
    {
        $this->db->insert('module', $data);
        return true;
    }

    //---------------------------------------------------
    // Edit Module
    public function edit_module($data, $id)
    {
        $this->db->where('module_id', $id);
        $this->db->update('module', $data);
        return true;
    }

    //-----------------------------------------------------
    public function delete_module($id)
    {
        $this->db->where('module_id', $id);
        $this->db->delete('module');
    }

    //-----------------------------------------------------
    public function get_module_by_id($id)
    {
        $this->db->from('module');
        $this->db->where('module_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /*------------------------------
    Sub Module / Sub Menu
    ------------------------------*/

    //-----------------------------------------------------
    public function add_sub_module($data)
    {
        $this->db->insert('sub_module', $data);
        return $this->db->insert_id();
    }

    //-----------------------------------------------------
    public function get_sub_module_by_id($id)
    {
        $this->db->from('sub_module');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    //-----------------------------------------------------
    public function get_sub_module_by_module($id)
    {
        $this->db->select('*');
        $this->db->where('parent', $id);
        $this->db->order_by('sort_order', 'asc');
        $query = $this->db->get('sub_module');
        return $query->result_array();
    }

    //----------------------------------------------------
    public function edit_sub_module($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('sub_module', $data);
        return true;
    }

    //-----------------------------------------------------
    public function delete_sub_module($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('sub_module');
        return true;
    }

}