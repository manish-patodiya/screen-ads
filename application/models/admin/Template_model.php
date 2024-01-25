<?php
class Template_model extends CI_Model
{

    public function add_template($data)
    {
        // prd($data);
        $this->db->insert('templates', $data);
        return true;
    }

    //---------------------------------------------------
    // get all template for server-side datatable processing (ajax based)
    public function get_all_template()
    {
        $this->db->select('*');
        return $this->db->get('templates')->result_array();
    }

    //---------------------------------------------------
    // Get template detial by ID
    public function get_template_by_id($id)
    {
        $query = $this->db->get_where('templates', array('id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Edit template Record
    public function edit_template($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('templates', $data);
        return true;
    }

    //---------------------------------------------------
    // Change template status
    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('status', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('templates');
    }
    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->delete('templates');
        //$this->db->update();
    }
    public function get_default_tmplt()
    {
        $this->db->select('*');
        $this->db->where('company_id', '0');
        return $this->db->get('templates')->result_array();
    }
    public function get_template($id)
    {
        if ($id) {
            $this->db->select('*');
            $this->db->where('company_id', $id);
            return $this->db->get('templates')->result_array();
        }
    }

    //  Patient appointment templates functionality

    public function get_patient_appointment_templates()
    {
        $this->db->select('*');
        return $this->db->get('patient_appointment')->result_array();
    }

    public function get_appointment_table_by_id($id)
    {
        $this->db->select('*')
            ->where('id', $id);
        return $this->db->get('patient_appointment')->result_array();
    }
}