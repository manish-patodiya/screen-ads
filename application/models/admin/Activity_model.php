<?php defined('BASEPATH') or exit('No direct script access allowed');

class Activity_model extends CI_Model
{

    public function get_activity_log()
    {
        $this->db->select('
			activity_log.id,
			activity_log.activity_id,
			activity_log.user_id,
			activity_log.admin_id,
			activity_log.created_at,
			activity_status.description,
			users.id as uid,
			users.username,
			admin.admin_id,
			admin.username as adminname
		');
        $this->db->join('activity_status', 'activity_status.id=activity_log.activity_id');
        $this->db->join('users', 'users.id=activity_log.user_id', 'left');
        $this->db->join('admin', 'admin.admin_id=activity_log.admin_id', 'left');
        $this->db->order_by('activity_log.id', 'desc');
        return $this->db->get('activity_log')->result_array();
    }

    //--------------------------------------------------------------------
    public function add_log($activity)
    {
        $data = array(
            'activity_id' => $activity,
            'user_id' => ($this->session->userdata('user_id') != '') ? $this->session->userdata('user_id') : 0,
            'admin_id' => ($this->session->userdata('admin_id') != '') ? $this->session->userdata('admin_id') : 0,
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('activity_log', $data);
        return true;
    }

}