<?php
class Invoice_model extends CI_Model
{

    //---------------------------------------------------
    // Get Customer detial by ID
    public function customer_detail($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $result = $query->row_array();
    }

    //---------------------------------------------------
    // Insert New Invoice
    public function add_invoice($data)
    {
        return $this->db->insert('payments', $data);
    }

    //---------------------------------------------------
    // Insert New Invoice
    public function add_company($data)
    {
        $this->db->insert('companies', $data);
        return $this->db->insert_id();
    }

    //---------------------------------------------------
    // Get Add Invoices
    public function get_all_invoices()
    {
        $this->db->select('
					payments.id,
	    			payments.invoice_no,
	    			users.username as client_name,
	    			payments.payment_status,
					payments.grand_total,
					payments.currency,
					payments.due_date,
					'
        );
        $this->db->from('payments');
        $this->db->join('users', 'users.id = payments.user_id ', 'Left');
        $query = $this->db->get();
        return $query->result_array();
    }

    //---------------------------------------------------
    // Get Customers List for Invoice
    public function get_customer_list()
    {
        $this->db->select('id, UPPER(CONCAT(firstname, ' . ' ,lastname)) as username');
        $this->db->from('users');
        return $this->db->get()->result_array();
    }

    //---------------------------------------------------
    // Get Invoice Detil by ID
    public function get_invoice_by_id($id)
    {

        $this->db->select('
					payments.id,
					payments.user_id as client_id,
	    			payments.invoice_no,
	    			payments.items_detail,
	    			payments.payment_status,
	    			payments.sub_total,
	    			payments.total_tax,
	    			payments.discount,
					payments.grand_total,
					payments.currency,
					payments.client_note,
					payments.termsncondition,
					payments.due_date,
					payments.created_date,
					users.username as client_name,
					users.firstname,
					users.lastname,
					users.email as client_email,
					users.mobile_no as client_mobile_no,
					users.address as client_address,
					companies.id as company_id,
					companies.name as company_name,
					companies.email as company_email,
					companies.address1 as company_address1,
					companies.address2 as company_address2,
					companies.mobile_no as company_mobile_no,
					'
        );
        $this->db->from('payments');
        $this->db->join('users', 'users.id = payments.user_id ', 'Left');
        $this->db->join('companies', 'companies.id = payments.company_id ', 'Left');
        $this->db->where('payments.id', $id);
        $query = $this->db->get();
        return $query->row_array();

    }

    //---------------------------------------------------
    // Get Invoice Detil by ID
    public function update_invoice($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('payments', $data);
    }

    //---------------------------------------------------
    // Update Customer Detail in invoice
    public function update_company($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('companies', $data);
        return $id; // return the updated id
    }

}