<?php
class support_tickets_model extends CI_Model
{
    public function add_support_tickets($data)
    {
        $this->db->insert('support_tickets', $data);
        return true;
    }

    //---------------------------------------------------
    // get all department for server-side datatable processing (ajax based)
    public function get_all_support_tickets()
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        // prd($company_id);
        $this->db->select('*');
        if ($admin_role_id != 1 && $admin_role_id != 5) {
            $this->db->where("support_tickets.company_id=$company_id");
        }
        $this->db->where("deleted_at is NULL");
        // prd($this->db->get()->last_query());
        return $this->db->get('support_tickets')->result_array();
    }

    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('support_tickets');
    }

    public function getFields($fields, $where)
    {
        $this->db->select($fields);
        $this->db->where("deleted_at is NULL");
        if ($where) {
            $this->db->where($where);
        }
        return $this->db->get('support_tickets')->row_array();
    }

    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('support_tickets');
        return $this->db->affected_rows();
        //$this->db->update();
    }
}