<?php defined('BASEPATH') or exit('No direct script access allowed');
class Templates extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('is_user_login')) {
            auth_check();
            $this->rbac->check_module_access();
        }
        $this->load->model('admin/template_model', 'template_model');
        $this->load->model('admin/doctors_model', 'doctors_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $company_id = $this->session->userdata['company_id'];
        // prd($company_id);
        $data = [
            "templates" => $this->template_model->get_template($company_id),
            "patient_appointments" => $this->template_model->get_patient_appointment_templates(),
        ];
        // prd($data);
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/templates/list', $data);
        $this->load->view('admin/includes/_footer');
        $this->load->view('admin/sub_views/delete_modal');
    }
    public function datatable_json()
    {
        $records['data'] = $this->template_model->get_all_template();
        // prd($records['data']);
        $data = array();
        $i = 0;
        foreach ($records['data'] as $row) {
            $status = ($row['status'] == 1) ? 'checked' : '';
            $data[] = array(
                ++$i,
                $row['title'],
                '<input class="tgl_checkbox tgl-ios"
				data-id="' . $row['id'] . '"
				id="cb_' . $row['id'] . '"
				type="checkbox"
				' . $status . '><label for="cb_' . $row['id'] . '"></label>',
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('admin/templates/view/' . $row['id']) . '"> <i class="fa fa-eye"></i></a> <a title="Edit" class="update btn btn-sm btn-warning" href="' . base_url('admin/templates/edit/' . $row['id']) . '"> <i class="fa fa-pencil-square-o"></i></a>
                 <a title="Delete" class="delete btn btn-sm btn-danger sup_delete"  uid="' . $row['id'] . '" href="#"> <i class="fa fa-trash-o"></i></a>',
            );
        }

        $records['data'] = $data;
        echo json_encode($records);
    }
    public function get_template()
    {
        $id = $this->input->post('tid');
        // prd($id);
        $res = $this->template_model->get_template_by_id($id);
        // prd($res);
        $doc_cards = [];
        $doctors_list = $this->doctors_model->get_doctors_with_info();
        // prd($doctors_list);
        foreach ($doctors_list as $k => $doc) {
            // prd($k);
            $doc_cards[] = str_replace(
                ['{department}', '{name}', '{qualification}', '{affiliation}', '{image}', '{morning_time}', '{evening_time}'],
                [$doc->department_name, $doc->name, $doc->qualifications, $doc->affiliations, $doc->image == "" ? base_url("/uploads/no_image.jpg") : $doc->image, $doc->availabilities],
                $res['content']);
        }
        if (!empty($res)) {
            echo json_encode([
                'status' => 1,
                "msg" => "Fetch Successfully!",
                'data' => $doc_cards,
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                "msg" => "No Data Found",
                'data' => [],
            ]);
        }
    }
    public function get_appointment_table()
    {
        $id = $this->input->post('tid');
        // prd($id);
        $res = $this->template_model->get_appointment_table_by_id($id);
        foreach ($res as $k => $v) {
            $appointment_table[] = $v["content"];
        }
        if (!empty($res)) {
            echo json_encode([
                'status' => 1,
                "msg" => "Fetch Successfully!",
                'data' => $appointment_table,
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                "msg" => "No Data Found",
                'data' => [],
            ]);
        }
    }
    public function edit($id = '0')
    {
        if ($this->input->post('content')) {
            $data = array(
                // "title" => uc($this->input->post('title')),
                "content" => $this->input->post('content'),
                "is_master" => "1",
            );
            $id = $this->input->post('id');
            // prd($id);
            // $data = $this->security->xss_clean($data);
            $result = $this->template_model->edit_template($data, $id);
            // prd($result);
            if ($result) {
                // prd("hello");
                // Activity Log
                // $this->activity_model->add_log(2);
                echo json_encode([
                    "status" => 1,
                ]);
                $this->session->set_flashdata('success', 'Template has been updated successfully!');
                // redirect(base_url('admin/templates'));
            }
            // }
        } else {
            $data['template'] = $this->template_model->get_template_by_id($id);
            // prd($data);
            $this->load->view('admin/includes/_header');
            $this->load->view('admin/templates/edit_template', $data);
            $this->load->view('admin/includes/_footer');
        }}
    public function change_status()
    {
        $this->template_model->change_status();
    }
    public function delete_template()
    {
        $id = $this->input->post('id');
        $department = $this->template_model->deleteRow($id);
        echo json_encode([
            'status' => 1,
            'msg' => 'Template was deleted successfully',
        ]);
    }
    public function view($content = '')
    {
        $data = array(
            "content" => $this->input->post('content'),
        );
        // prd($data);
        // prd($data);
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/templates/preview', $data);
        $this->load->view('admin/includes/_footer');
    }
}