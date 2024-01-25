<?php
class Join_model extends CI_Model
{

    public function get_all_serverside_payments()
    {
        $this->db->select('
	    		payments.id,
				payments.invoice_no,
				payments.grand_total,
				payments.currency,
				payments.created_date,
				users.username as client_name,
				users.email as client_email,
				users.mobile_no as client_mobile_no
	    	');
        $this->db->join('users', 'users.id = payments.user_id', 'left');
        return $this->db->get('payments')->result_array();
    }

    public function get_user_payment_details()
    {
        $this->db->select('
	    			payments.id,
	    			payments.invoice_no,
	    			payments.payment_status,
					payments.grand_total,
					payments.currency,
					payments.due_date,
					payments.created_date,
					users.username as client_name,
					users.firstname,
					users.lastname,
					users.email as client_email,
					users.mobile_no as client_mobile_no,
					users.address as client_address,'
        );
        $this->db->from('payments');
        $this->db->join('users', 'users.id = payments.user_id ', 'left');
        $query = $this->db->get();
        return $query->result_array();
    }

}