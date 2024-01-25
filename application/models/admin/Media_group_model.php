<?php
class Media_group_model extends CI_Model
{

    public function add_media_group($data)
    {
        $this->db->insert('media_group_master', $data);
        return $this->db->insert_id();
    }

    public function add_media_content($data)
    {
        $this->db->insert('media_group_contents', $data);
        return $this->db->insert_id();
    }

    //---------------------------------------------------
    // get all media for server-side datatable processing (ajax based)
    public function get_all_media_group()
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('media_group_master.*,media_master.media_type as media_type');
        $this->db->join("media_master", "media_master.id = media_group_master.media_type", "left");
        if ($admin_role_id != 1) {
            $this->db->where("media_group_master.company_id=$company_id");
        }
        $this->db->where("media_group_master.deleted_at is NULL");
        return $this->db->get('media_group_master')->result_array();
    }

    //---------------------------------------------------
    // Get media detial by ID
    public function get_media_group_by_id($id)
    {
        $this->db->select('*');
        // ->join('media_master', 'media_master.id=media_group_master.media_type');
        $this->db->where('media_group_master.id', $id);
        $this->db->where("media_group_master.deleted_at is NULL");
        $result = $this->db->get("media_group_master")->row_array();

        $content = $this->db->get_where('media_group_contents', ['media_group_id' => $id])->result_array();
        $media_list = [];
        foreach ($content as $k => $v) {
            $media_list[] = $v['media_id'];
        }
        $result['media_group_list'] = implode(',', $media_list);
        return $result;
    }

    //---------------------------------------------------
    // Get media group list detial by ID on view modal
    public function get_media_group_list_by_id($id)
    {
        $this->db->select('*, media_master.id as uid,media_master.media_type')
            ->join('media_master', 'media_master.id=media_group_master.media_type');
        $this->db->where('media_group_master.id', $id);
        $this->db->where("media_group_master.deleted_at is NULL");
        $result = $this->db->get("media_group_master")->row_array();

        $content = $this->db->get_where('media_group_contents', ['media_group_id' => $id])->result_array();
        $media_list = [];
        foreach ($content as $k => $v) {
            $media_list[] = $v['media_id'];
        }
        $result['media_group_list'] = implode(',', $media_list);
        return $result;
    }
    // Edit media Record
    public function edit_media_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('media_group_master', $data);
        return true;
    }

    //---------------------------------------------------
    // Change media status
    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('media_group_master');
    }

    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('media_group_master');
        //$this->db->update();
    }

    public function get_media_by_media_type_id($id)
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('*');
        if ($admin_role_id != 1) {
            $this->db->where("company_id=$company_id");
        }
        $this->db->where("deleted_at is NULL");
        $this->db->where("is_active", 1);
        $this->db->where("media_type", $id);
        $media_grp = $this->db->get('media_group_master')->result();
        return $media_grp;
    }

    public function get_media_contents($id)
    {
        $result = $this->db->select('*')->where('media_group_id', $id)->join('media', 'mgc.media_id = media.id', 'left')->get('media_group_contents mgc')->result();
        return $result;
    }

    public function media_group($id)
    {
        $this->db->select('media_group_master.id,media_group_master.media_type,media_group_master.group_name,media_group_master.remarks')
            ->where('id', $id);

        return $this->db->get('media_group_master')->row_array();
    }
}