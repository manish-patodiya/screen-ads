<?php
class Company_model extends CI_Model
{

    public function add_company($data)
    {
        $data = $this->security->xss_clean($data);
        $this->db->insert('companies', $data);
        return $this->db->insert_id();
    }

    //---------------------------------------------------
    // get all company for server-side datatable processing (ajax based)
    public function get_all_company()
    {
        $this->db->select('*,admin.admin_id,admin.username');
        $this->db->join('admin', 'admin.admin_id=companies.admin_id');
        $this->db->where("deleted_at is NULL");
        return $this->db->get('companies')->result_array();
    }
    public function get_all_companys()
    {
        $this->db->select('*');
        return $this->db->get('companies')->result_array();
    }

    //---------------------------------------------------
    // Get company detial by ID
    public function get_company_by_id($id)
    {
        $query = $this->db->get_where('companies', array('id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Get company admin detial by company ID
    public function get_admin_by_company_id($id)
    {
        $this->db->select("admin.*,companies.is_active as active");
        $this->db->join("admin", "admin.admin_id = companies.admin_id", "left");
        $query = $this->db->get_where('companies', array('companies.id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Edit company Record
    public function edit_company($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('companies', $data);
        return true;
    }

    //---------------------------------------------------
    // Change company status
    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('companies');
    }
    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('companies');
        //$this->db->update();
    }

    public function get_company_detail_by_user_id($uid)
    {
        $this->db->select("admin.username,admin.firstname,admin.lastname, companies.*");
        $this->db->join("admin", "admin.admin_id = companies.admin_id", "left");
        $query = $this->db->get_where('companies', array('companies.admin_id' => $uid));
        return $result = $query->row();
    }
    public function get_notifications($uid)
    {
        $this->db->select("companies.admin_id,notifications.*,notifications.company_id as company")
            ->join("notifications", "notifications.company_id=companies.id");
        $query = $this->db->get_where('companies', array('companies.admin_id' => $uid));
        return $result = $query->result_array();
    }
}