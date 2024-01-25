<?php
class Media_model extends CI_Model
{
    public function add_media($data)
    {
        $this->db->insert('media_master', $data);
        return true;
    }

    //---------------------------------------------------
    // get all department for server-side datatable processing (ajax based)
    public function get_all_media()
    {
        $this->db->select('*');
        return $this->db->get('media_master')->result_array();
    }

    public function getMediaTypeExtension($id)
    {
        $query = $this->db->get_where('media_master', array('id' => $id));
        // prd($this->db->last_query());
        return $result = $query->result();
    }
}