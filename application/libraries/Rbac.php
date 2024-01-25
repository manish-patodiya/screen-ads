<?php
class RBAC
{
    private $module_access;
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->module_access = $this->ci->session->userdata('module_access');
        $this->ci->is_supper = $this->ci->session->userdata('is_supper');
    }

    //----------------------------------------------------------------
    public function set_access_in_session()
    {
        $this->ci->db->from('module_access');
        $this->ci->db->where('admin_role_id', $this->ci->session->userdata('admin_role_id'));
        $query = $this->ci->db->get();
        $data = array();
        foreach ($query->result_array() as $v) {
            $data[$v['module']][$v['operation']] = '';
        }

        $this->ci->session->set_userdata('module_access', $data);

    }

    //--------------------------------------------------------------
    public function check_module_access()
    {
        if ($this->ci->is_supper) {
            return 1;
        } elseif (!$this->check_module_permission($this->ci->uri->segment(2))) //sending controller name
        {
            $back_to = $_SERVER['REQUEST_URI'];
            $back_to = $this->ci->functions->encode($back_to);
            redirect('access_denied/index/' . $back_to);
        }
    }

    //--------------------------------------------------------------
    public function check_module_permission($module) // $module is controller name

    {
        $access = false;

        if ($this->ci->is_supper) {
            return true;
        } elseif (isset($this->ci->module_access[$module])) {
            foreach ($this->ci->module_access[$module] as $key => $value) {
                if ($key == 'access') {
                    $access = true;
                }
            }

            if ($access) {
                return 1;
            } else {
                return 0;
            }

        }
    }

    //--------------------------------------------------------------
    public function check_operation_access()
    {
        if ($this->ci->is_supper) {
            return 1;
        } elseif (!$this->check_operation_permission($this->ci->uri->segment(3))) {

            $back_to = $_SERVER['REQUEST_URI'];
            $back_to = $this->ci->functions->encode($back_to);
            redirect('access_denied/index/' . $back_to);
        }
    }

    //--------------------------------------------------------------
    public function Check_operation_permission($operation)
    {
        if (isset($this->ci->module_access[$this->ci->uri->segment(2)][$operation])) {
            return 1;
        } else {
            return 0;
        }

    }

    public function Check_subModule_permission($module, $operation)
    {
        if ($this->ci->is_supper || $operation == 'backup_export') {
            return 1;
        } elseif (isset($this->ci->module_access[$module][$operation])) {
            return 1;
        } else {
            return 0;
        }
    }

}