<?php
class Department_model extends CI_Model
{

    public function add_department($data)
    {
        $this->db->insert('departments', $data);
        return true;
    }

    //---------------------------------------------------
    // get all department for server-side datatable processing (ajax based)
    public function get_all_department()
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('departments.*');
        // $this->db->join("language", "language.id = departments.language_id", "left");
        if ($admin_role_id != 1) {
            $this->db->where("departments.company_id=$company_id");
        }
        $this->db->where("deleted_at is NULL");
        return $this->db->get('departments')->result_array();
    }

    //---------------------------------------------------
    // Get department detial by ID
    public function get_department_by_id($id)
    {
        $query = $this->db->get_where('departments', array('id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Edit department Record
    public function edit_department($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('departments', $data);
        return true;
    }

    //---------------------------------------------------
    // Change department status
    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('departments');
    }
    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('departments');
        //$this->db->update();
    }
}