<?php
class Setting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //-----------------------------------------------------
    public function update_general_setting($data)
    {
        $this->db->where('id', 1);
        $this->db->update('general_settings', $data);
        return true;

    }

    //-----------------------------------------------------
    public function get_general_settings()
    {
        $this->db->where('id', 1);
        $query = $this->db->get('general_settings');
        return $query->row_array();
    }

    public function get_all_languages()
    {
        $this->db->where('status', 1);
        return $this->db->get('languages')->result_array();
    }

    /*--------------------------
    Email Template Settings
    --------------------------*/

    public function get_email_templates()
    {
        return $this->db->get('email_templates')->result_array();
    }

    public function update_email_template($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('email_templates', $data);
        return true;
    }

    public function get_email_template_content_by_id($id)
    {
        return $this->db->get_where('email_templates', array('id' => $id))->row_array();
    }

    public function get_email_template_variables_by_id($id)
    {
        return $this->db->get_where('email_template_variables', array('template_id' => $id))->result_array();
    }

}