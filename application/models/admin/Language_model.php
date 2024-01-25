<?php
class Language_model extends CI_Model
{
    public function add_language($data)
    {
        $this->db->insert('language', $data);
        return true;
    }

    //---------------------------------------------------
    // get all department for server-side datatable processing (ajax based)
    public function get_all_language()
    {
        $this->db->select('*');
        return $this->db->get('language')->result_array();
    }

}