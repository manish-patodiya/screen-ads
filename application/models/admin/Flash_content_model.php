<?php
class Flash_content_model extends CI_Model
{

    public function add_flashcontent($data)
    {
        $this->db->insert('flashcontent', $data);
        return true;
    }

    //---------------------------------------------------
    // get all flashcontent for server-side datatable processing (ajax based)
    public function get_all_flashcontent()
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select('*');
        // $this->db->join("language", "language.id = flashcontent.language_id", "left");
        if ($admin_role_id != 1) {
            $this->db->where("flashcontent.company_id=$company_id");
        }
        $this->db->where("flashcontent.deleted_at is NULL");
        return $this->db->get('flashcontent')->result_array();
    }

    //---------------------------------------------------
    // Get flashcontent detial by ID
    public function get_flashcontent_by_id($id)
    {
        $query = $this->db->get_where('flashcontent', array('id' => $id));
        //$this->db->last_query();
        return $result = $query->row_array();
    }

    // Get flashcontent detial
    // public function get_flashcontent()
    // {
    //     $result = $this->db->order_by("id", "desc")->get_where('flashcontent', ['is_active' => 1])->row();
    //     return $result;
    // }

    //---------------------------------------------------
    // Edit flashcontent Record
    public function edit_flashcontent($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('flashcontent', $data);
        return true;
    }

    //---------------------------------------------------
    // Change flashcontent status
    //-----------------------------------------------------
    public function change_status()
    {
        $this->db->set('is_active', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('flashcontent');
    }

    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('flashcontent');

    }

    public function get_all_property()
    {
        $this->db->select('*');
        return $this->db->get('property')->result_array();
    }

    public function status($id)
    {
        $this->db->select('*')
            ->set(['status' => '1'])
            ->where('id', $id)
            ->update('property');
        return true;
    }
//     public function labels()
    //     {
    // $this->db->select('*')
    // ->get('pro')
    //     }
    public function properties($id)
    {
        $this->db->select('flashcontent.content,flashcontent.property,flashcontent.position,flashcontent.media_file')
            ->where('id', $id);
        return $this->db->get('flashcontent')->row_array();
    }
}