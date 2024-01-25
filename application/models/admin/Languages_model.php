<?php
class Languages_model extends CI_Model
{

    public function add_language($data)
    {
        return $this->db->insert('languages', $data);
    }

    public function get_all_languages()
    {
        $query = $this->db->get('languages');
        return $result = $query->result_array();
    }

    public function edit_language($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('languages', $data);
        return true;

    }

    public function get_language_by_id($id)
    {
        $query = $this->db->get_where('languages', array('id' => $id));
        return $result = $query->row_array();
    }

    public function delete_language($id)
    {
        $this->db->delete('languages', array('id' => $id));
        return true;
    }

    public function set_default_language($id)
    {
        $language = $this->get_language_by_id($id);
        $this->db->update('general_settings', array('default_language' => $language['directory_name'])); // setting in General settings table

        $this->db->update('languages', array('is_default' => 0)); // setting all previous to 0

        $this->db->where('id', $id);
        $this->db->update('languages', array('is_default' => 1));
        return true;

    }

}