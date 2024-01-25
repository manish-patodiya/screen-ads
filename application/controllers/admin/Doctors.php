<?php defined('BASEPATH') or exit('No direct script access allowed');
class Doctors extends MY_Controller
{

    public function __construct()
    {

        parent::__construct();
        auth_check(); // check login auth
        $this->rbac->check_module_access();

        $this->load->model('admin/department_model', 'department_model');
        $this->load->model('admin/language_model', 'language_model');
        $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->model('admin/doctors_model', 'doctors_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/doctors/doctors_list');
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
        $this->load->view('admin/sub_views/doctors_view');

    }
    public function view($id)
    {
        $data = $this->doctors_model->get_doctor_by_id($id);
        // prd($data);
        if ($data) {
            echo json_encode([
                "status" => 1,
                "info" => $data,
            ]);
        }
    }
    public function doctor_cards()
    {
        $filter = $this->input->get('value');
        if ($filter != "") {
            $records = $this->doctors_model->get_doctors($filter);
        } else {
            $records = $this->doctors_model->get_doctors();
        }
        // prd($records);
        if ($records) {
            echo json_encode([
                "status" => 1,
                "data" => $records,
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => "No data available",
            ]);
        }
    }
    public function add($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Docto Name', 'trim|required');
            $this->form_validation->set_rules('department_id', 'Speciality', 'trim|required');
            $this->form_validation->set_rules('qualification_id[]', 'Qualification', 'trim|required');
            $this->form_validation->set_rules('affiliation_id[]', 'Affiliation', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim');

            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/doctors/add'), 'refresh');
            } else {
                $doctors_image = '';
                if (!empty($_FILES['doctors_image']['name'])) {
                    $ext = pathinfo($_FILES['doctors_image']['name'], PATHINFO_EXTENSION);
                    $profileconfig['upload_path'] = './uploads/doctors_image';
                    $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg';
                    $profileconfig['max_size'] = '2000000';
                    $profileconfig['remove_spaces'] = false;
                    $profileconfig['overwrite'] = false;
                    $profileconfig['max_width'] = '';
                    $profileconfig['max_height'] = '';
                    $new_name = "doctors_image" . time();
                    $profileconfig['file_name'] = $new_name;
                    $this->upload->initialize($profileconfig);
                    if (!$this->upload->do_upload('doctors_image')) {
                        $data = array(
                            'errors' => $this->upload->display_errors(),
                        );
                        $this->session->set_flashdata('errors', $data['errors']);
                        redirect(base_url('admin/doctors/add/' . $id), 'refresh');
                    }
                    $file = $this->upload->data();
                    $doctors_image = base_url('uploads/doctors_image/' . $file['file_name']);
                    $file_path = $file['full_path'];
                    $this->load->library('S3upload');
                    $folderName = "doctors_image";
                    $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);
                    if ($s3Upload) {
                        $bucket = 'iddms';
                        $actual_file_path = $folderName . '/' . $file["file_name"];
                        $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                        //$fullFilePath = $monthPath.'/'.$file_name;
                        // $pushString = $this->AppointmentModel->storePdfString($visit_id, $fullFilePath);
                        // $data['pdfdata'][0]->prescriptionPdf = $fullFilePath;
                    }
                    // prd($fullFilePath);
                    $doctors_image = $fullFilePath;
                    if ($fullFilePath) {
                        unlink($file['file_path']);
                    }
                    // $doctors_image = $fullFilePath;
                    // if ($fullFilePath) {
                    //     unlink('C:\xampp7.4\htdocs\saas-digital\uploads\doctors_image\\' . $file['file_name']);
                    // }
                }
                $data = [
                    'name' => strtoupper($this->input->post('username')),
                    'department_id' => $this->input->post('department_id'),
                    'company_id' => $this->session->company_id,
                    'updated_at' => date('Y-m-d : h:m:s'),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'image' => $doctors_image,
                ];
                // $days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
                // foreach ($days as $d) {
                //     $availabilities = [$this->input->post($d) == "" ? "" : $d => $this->input->post($d),
                //     ];
                // }
                // $availabilities = [];

                // prd($availabilities);
                $quali_id = array();
                $qualifications = $this->input->post('qualification_id');
                foreach ($qualifications as $q => $b) {
                    if (!is_numeric($b)) {
                        $quali_id[] = $this->doctors_model->master_tables('title', $b, 'master_qualifications');
                        // array_push($arr, $v);
                    } else {
                        $quali_id[] = $b;
                    }
                }
                $qualifications = $quali_id;

                $aff_id = array();
                $affiliations = $this->input->post('affiliation_id');
                foreach ($affiliations as $k => $v) {
                    if (!is_numeric($v)) {
                        $aff_id[] = $this->doctors_model->master_tables('title', $v, 'master_affiliations');
                        // array_push($arr, $v);
                    } else {
                        $aff_id[] = $v;
                    }
                }
                $affiliations = $aff_id;

                $avail_id = array();
                $availabilities = $this->input->post('day');
                // prd($availabilities);
                foreach ($availabilities as $k => $v) {
                    if (!is_numeric($v)) {
                        $avail_id[] = $this->doctors_model->master_tables('time', $v, 'master_availabilities');
                    } else {
                        $avail_id[] = $v;
                    }
                }
                $availabilities = $avail_id;
                if ($this->doctors_model->add($data, $qualifications, $affiliations, $availabilities)) {
                    // Activity Log
                    $this->activity_model->add_log(1);

                    $this->session->set_flashdata('success', 'Doctor has been added successfully!');
                    redirect(base_url('admin/doctors'));
                }

            }
        } else {
            $data['departments'] = $this->department_model->get_all_department();
            $data['affiliations'] = $this->doctors_model->get_fields('*', 'master_affiliations');
            $data['qualifications'] = $this->doctors_model->get_fields('*', 'master_qualifications');
            $data['availabilities'] = $this->doctors_model->get_fields('*', 'master_availabilities');
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/doctors/doctors_add', $data);
            $this->load->view('admin/includes/_footer');
        }

    }

    public function edit($id = 0)
    {
        // prd($id);
        $this->rbac->check_operation_access(); // check opration permission

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', 'Name', 'trim|required');
            $this->form_validation->set_rules('department_id', 'Speciality', 'trim|required');
            $this->form_validation->set_rules('qualification_id[]', 'Qualification', 'trim|required');
            $this->form_validation->set_rules('affiliation_id[]', 'Affiliation', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'errors' => validation_errors(),
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('admin/doctors/edit/' . $id), 'refresh');
            } else {
                $doctors_image = '';
                if (!empty($_FILES['doctors_image']['name'])) {
                    $ext = pathinfo($_FILES['doctors_image']['name'], PATHINFO_EXTENSION);
                    $profileconfig['upload_path'] = './uploads/doctors_image';
                    $profileconfig['allowed_types'] = 'gif|jpg|png|jpeg';
                    $profileconfig['max_size'] = '2000000';
                    $profileconfig['remove_spaces'] = false;
                    $profileconfig['overwrite'] = false;
                    $profileconfig['max_width'] = '';
                    $profileconfig['max_height'] = '';
                    $new_name = "doctors_image" . time();
                    $profileconfig['file_name'] = $new_name;
                    $this->upload->initialize($profileconfig);
                    if (!$this->upload->do_upload('doctors_image')) {
                        $data = array(
                            'errors' => $this->upload->display_errors(),
                        );
                        $this->session->set_flashdata('errors', $data['errors']);
                        redirect(base_url('admin/doctors/add/' . $id), 'refresh');
                    }
                    $file = $this->upload->data();
                    $doctors_image = base_url('uploads/doctors_image/' . $file['file_name']);
                    //$doctors_image = $file['file_name'];
                    //  prd($doctors_image);
                    $file_path = $file['full_path'];
                    $this->load->library('S3upload');
                    $folderName = "doctors_image";
                    $s3Upload = $this->s3upload->upload($file_path, $file["file_name"], $folderName);
                    if ($s3Upload) {
                        $bucket = 'iddms';
                        $actual_file_path = $folderName . '/' . $file["file_name"];
                        $fullFilePath = 'https://' . $bucket . '.s3.amazonaws.com/' . $actual_file_path;
                        //$fullFilePath = $monthPath.'/'.$file_name;
                        // $pushString = $this->AppointmentModel->storePdfString($visit_id, $fullFilePath);
                        // $data['pdfdata'][0]->prescriptionPdf = $fullFilePath;
                    }
                    // prd($fullFilePath);
                    $doctors_image = $fullFilePath;
                    if ($fullFilePath) {
                        unlink($file['file_path']);
                    }
                }
                $data = [
                    'name' => uc($this->input->post('name')),
                    'department_id' => $this->input->post('department_id'),
                    'company_id' => $this->session->company_id,
                    'updated_at' => date('Y-m-d : h:m:s'),
                ];
                // $availabilities = [
                //     "0" => $this->input->post('Sunday'),
                //     "1" => $this->input->post('Monday'),
                //     "2" => $this->input->post('Tuesday'),
                //     "3" => $this->input->post('Wednesday'),
                //     "4" => $this->input->post('Thursday'),
                //     "5" => $this->input->post('Friday'),
                //     "6" => $this->input->post('Saturday'),
                // ];
                // prd($availabilities);
                $quali_id = array();
                $qualifications = $this->input->post('qualification_id');
                foreach ($qualifications as $q => $b) {
                    if (!is_numeric($b)) {
                        $quali_id[] = $this->doctors_model->master_tables('title', $b, 'master_qualifications');
                        // array_push($arr, $v);
                    } else {
                        $quali_id[] = $b;
                    }
                }
                $qualifications = $quali_id;

                $aff_id = array();
                $affiliations = $this->input->post('affiliation_id');
                foreach ($affiliations as $k => $v) {
                    if (!is_numeric($v)) {
                        $aff_id[] = $this->doctors_model->master_tables('title', $v, 'master_affiliations');
                        // array_push($arr, $v);
                    } else {
                        $aff_id[] = $v;
                    }
                }
                $affiliations = $aff_id;

                $avail_id = array();
                $availabilities = $this->input->post('day');
                foreach ($availabilities as $k => $v) {
                    if (!is_numeric($v)) {
                        $avail_id[] = $this->doctors_model->master_tables('time', $v, 'master_availabilities');
                    } else {
                        $avail_id[] = $v;
                    }
                }
                $availabilities = $avail_id;
                // prd($availabilities);
                if (!empty($_FILES['doctors_image']['name'])) {
                    $data = array_merge($data, ['image' => $doctors_image]);
                }
                // prd($id);

                if ($this->doctors_model->edit_details($data, $id, $qualifications, $affiliations, $availabilities)) {
                    // Activity Log
                    $this->session->set_flashdata('success', 'Doctor has been updated successfully!');
                    redirect(base_url('admin/doctors'));
                }
            }
        } else {
            $data['doctor'] = $this->doctors_model->get_doctor_by_id($id);
            $data['departments'] = $this->doctors_model->get_fields('*', 'departments');
            $data['availabilities'] = $this->doctors_model->get_fields('*', 'master_availabilities');
            $data['affiliations'] = $this->doctors_model->get_fields('*', 'master_affiliations');
            $data['qualifications'] = $this->doctors_model->get_fields('*', 'master_qualifications');
            // prd($data);

            $this->load->view('admin/includes/_header');
            $this->load->view('admin/doctors/doctors_edit', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    public function delete_doctor()
    {
        $id = $this->input->Post('id');
        $doctor = $this->doctors_model->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Doctor was deleted successfully',
        ]);

    }

    public function delete($id = 0)
    {
        $this->rbac->check_operation_access(); // check opration permission

        $this->db->delete('doctors', array('id' => $id));

        // Activity Log
        $this->activity_model->add_log(3);

        $this->session->set_flashdata('success', 'Doctor has been deleted successfully!');
        redirect(base_url('admin/doctors'));
    }
    public function change_status()
    {
        $this->doctors_model->change_status();
    }

    public function get_doctor_by_id()
    {
        $doc_id = $this->input->post('did');
        $res = $this->doctors_model->get_doctor_by_id($doc_id);
        if (!empty($res)) {
            echo json_encode([
                "status" => 1,
                'msg' => 'Fetch Succesfully',
                'data' => $res,
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                'msg' => 'No Data Found',
                'data' => [],
            ]);
        }
    }
}