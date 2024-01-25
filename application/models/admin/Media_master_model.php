<?php
class Media_master_model extends CI_Model
{

    public function add_media($data)
    {
        $this->db->insert('media', $data);
        return true;
    }

    //---------------------------------------------------
    // get all media for server-side datatable processing (ajax based)
    public function get_all_media($filters = false)
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('media.*,media_master.id as mid, media_master.media_type,media_master.extension');
        $this->db->join("media_master", "media_master.id = media.media_type_id");
        if ($filters) {
            if (isset($filters["media_id"]) && $filters["media_id"]) {
                $this->db->where("media_type_id", $filters["media_id"]);
            }}
        $this->db->where("media.deleted_at is NULL");
        if ($admin_role_id != 1) {
            $this->db->where("media.company_id=$company_id");
        }
        // prd($this->db->last_query());
        // prd($result);
        return $this->db->get('media')->result_array();
    }
    public function get_all_media_content($media_content)
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('*');
        $this->db->where('media_type_id', $media_content);
        if ($admin_role_id != 1) {
            $this->db->where("media.company_id=$company_id");
        }
        $this->db->where('deleted_at is NULL');
        return $this->db->get('media')->result_array();
    }
    //---------------------------------------------------
    // Get media detial by ID
    public function get_media_by_id($id)
    {
        $this->db->select('media_master.extension,media.*');
        $this->db->join('media_master', 'media.media_type_id=media_master.id');
        $query = $this->db->get_where('media', array('media.id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Edit media Record
    public function edit_media($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('media', $data);
        return true;
    }

    //---------------------------------------------------
    // Change media status
    //-----------------------------------------------------
    public function change_status()
    {
        // prd($this->input->post('id'));
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('media');
    }

    public function getMediaByType($id)
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('*');
        $this->db->where('media_type_id', $id);
        if ($admin_role_id != 1) {
            $this->db->where("media.company_id=$company_id");
        }
        $this->db->where('deleted_at is NULL');
        return $this->db->get('media')->result();
    }

    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('media');
        //$this->db->update();
    }
}
