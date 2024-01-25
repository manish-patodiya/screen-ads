<?php defined('BASEPATH') or exit('No direct script access allowed');

class Doctors_model extends CI_Model
{
    public function get_doctors($filter = false)
    {
        $company_id = $this->session->company_id;
        $admin_role_id = $this->session->admin_role_id;
        $this->db->select("doctors.*, doctors.id as did,departments.*")
            ->join('departments', 'departments.id=doctors.department_id');
        if ($admin_role_id != 1) {
            $this->db->where("doctors.company_id=$company_id");
        }
        if($filter != "") {
            // prd($filter);
            $this->db->like("doctors.name", $filter);
        }
        $this->db->where("doctors.deleted_at is NULL");
        // prd($result);
        // $result = $this->db->last_query();
        return $this->db->get('doctors')->result_array();
        
    }

    public function change_status()
    {
        $this->db->set('status', $this->input->post('status'));
        $this->db->where('id', $this->input->post('id'));
        $this->db->update('doctors');
    }

    public function edit_details($data, $id, $qualifications, $affiliations, $availabilities)
    {
        $this->db->where('id', $id)->update('doctors', $data);

        $this->db->where('doctor_id', $id);
        $this->db->delete('doctors_qualifications');
        foreach ($qualifications as $k => $v) {
            $this->db->insert('doctors_qualifications', [
                'doctor_id' => $id,
                'qualification_id' => $v,
            ]);
        }

        $this->db->where('doctor_id', $id);
        $this->db->delete('doctors_affiliations');
        // prd($affiliations);
        foreach ($affiliations as $k => $v) {
            $this->db->insert('doctors_affiliations', [
                'doctor_id' => $id,
                'affiliation_id' => $v,
            ]);
        }

        // prd($this->db->get()->last_query());
        $this->db->where('doctor_id', $id);
        $this->db->delete('doctors_availabilities');
        foreach ($availabilities as $k => $v) {
            if ($v) {
                // $time = json_encode($v);
                // prd($time);
                $this->db->insert('doctors_availabilities', [
                    'doctor_id' => $id,
                    'availabilities_id' => $v,
                ]);
            }
        }
        return true;
    }

    public function get_doctors_with_info()
    {
        $this->db->select('doctors.*,departments.department_name')->join('departments', 'departments.id=doctors.department_id');
        if ($this->session->admin_role_id != 1) {
            $this->db->where('doctors.company_id', $this->session->company_id);
        }
        $res = $this->db->get('doctors')->result();
        foreach ($res as $k => $v) {
            $avarr = $this->doctor_availabilities($v->id);
            // prd($avarr);
            $availabilities = [];
            foreach ($avarr as $val) {
                $availabilities[] = $val['time'];
            }
            $res[$k]->availabilities = implode(',', $availabilities);

            $afarr = $this->_get_doctor_affiliations($v->id);
            $affiliations = [];
            foreach ($afarr as $val) {
                $affiliations[] = $val['title'];
            }
            $res[$k]->affiliations = implode(',', $affiliations);

            $qarr = $this->_get_doctor_qualifications($v->id);
            $qualifications = [];
            foreach ($qarr as $val) {
                $qualifications[] = $val['title'];
            }
            $res[$k]->qualifications = implode(',', $qualifications);
        }
        return $res;
    }

    public function get_doctor_by_id($id)
    {
        $this->db->select("*,doctors.id as did")
            ->join('departments', 'departments.id=doctors.department_id')
            ->where('doctors.id', $id);
        $dr_details = $this->db->get('doctors')->row_array();

        $dr_details['availabilities'] = $this->doctor_availabilities($id);
        $dr_details['affiliations'] = $this->_get_doctor_affiliations($id);
        $dr_details['qualifications'] = $this->_get_doctor_qualifications($id);
        //prd($dr_details);
        return $dr_details;
    }

    private function _get_doctor_affiliations($id)
    {
        $this->db->select("ma.id, ma.title")
            ->join('master_affiliations ma', 'ma.id=da.affiliation_id')
            ->where('da.doctor_id', $id);
        return $this->db->get('doctors_affiliations da')->result_array();
    }

    private function _get_doctor_qualifications($id)
    {
        $this->db->select("ma.id, ma.title")
            ->join('master_qualifications ma', 'ma.id=da.qualification_id')
            ->where('da.doctor_id', $id);
        return $this->db->get('doctors_qualifications da')->result_array();
    }

    private function _get_doctor_availabilities($id)
    {
        // prd($id);
        $this->db->select("*")
            ->where('doctor_id', $id);
        return $this->db->get('doctors_availabilities')->result_array();
    }

    private function doctor_availabilities($id)
    {
        $this->db->select("*,master_availabilities.*")
            ->join('master_availabilities', 'master_availabilities.id=doctors_availabilities.availabilities_id')
            ->where('doctors_availabilities.doctor_id', $id);
        return $this->db->get('doctors_availabilities')->result_array();
    }
    public function get_fields($data, $table)
    {
        // $company_id = $this->session->company_id;
        // $admin_role_id = $this->session->admin_role_id;
        // if ($admin_role_id != 1) {
        //     $this->db->where("company_id=$company_id");
        // }
        $this->db->select($data);
        return $this->db->get($table)->result_array();
    }

    public function add_doctor($data, $table)
    {
        $data = $this->security->xss_clean($data);
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }
    public function update_doctor($data, $table, $id)
    {
        // prd($data);
        if ($table = "doctors_qualifications" || $table = "doctors_affiliations" || $table = "doctors_availabilities") {
            $this->db->where('doctor_id', $id)
                ->delete($table);
            $this->db->insert($table, $data);
            return $this->db->affected_rows();
        }
        $this->db->set($data)
            ->update($table)
            ->where('id', $id);
        return $this->db->affected_rows();
    }

    public function add($data, $qualifications, $affiliations, $availabilities)
    {
        // prd([$data, $qulaifications, $affiliations, $availabilities]);
        $this->db->trans_start();
        // $aff_id = array();
        // foreach ($arr as $k) {
        //     $aff_id[] = $this->add_doctor(['title' => $k], 'master_affiliations');
        // }
        $dr_id = $this->add_doctor($data, "doctors");

        foreach ($qualifications as $k => $v) {
            // prd($qualifications);
            $this->add_doctor([
                'doctor_id' => $dr_id,
                'qualification_id' => $v,
            ], 'doctors_qualifications');
        }

        foreach ($affiliations as $k => $v) {
            $this->add_doctor([
                'doctor_id' => $dr_id,
                'affiliation_id' => $v,
            ], 'doctors_affiliations');
        }

        foreach ($availabilities as $k => $v) {

            $this->add_doctor([
                'doctor_id' => $dr_id,
                'availabilities_id' => $v,
            ], 'doctors_availabilities');
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function update($data, $qulaifications, $affiliations, $availabilities, $id)
    {
        // prd([$data, $qulaifications, $affiliations, $availabilities]);
        $this->db->trans_start();
        $this->update_doctor($data, "doctors", $id);

        foreach ($qulaifications as $k => $v) {
            $this->update_doctor([
                'doctor_id' => $id,
                'qualification_id' => $v,
            ], 'doctors_qualifications', $id);
        }

        foreach ($affiliations as $k => $v) {
            $this->update_doctor([
                'doctor_id' => $id,
                'affiliation_id' => $v,
            ], 'doctors_affiliations', $id);
        }

        foreach ($availabilities as $k => $v) {
            $this->update_doctor([
                'doctor_id' => $dr_id,
                'time' => $v,
            ], 'doctors_availabilities', $id);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function deleteRow($id)
    {
        $this->db->select('*');
        $this->db->set(["deleted_at" => date('Y-m-d')]);
        $this->db->where('id', $id);
        $this->db->update('doctors');

    }
    public function master_tables($field, $title, $table)
    {
        $id;
        $this->db->select('*')
            ->where($field, $title);
        $data = $this->db->get($table)->row_array();
        if ($data == "") {
            $this->db->insert($table, [$field => $title]);
            $id = $this->db->insert_id();
        }
        return $id;
    }
}