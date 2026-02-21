<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Powerform extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function employee_details_form()
    {
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_joining_details');
        $this->db->order_by('created_at', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        
        $data['title'] = 'Employee Details Form';
        $this->load->view('admin/powerform/employee_details_form', $data);
    }

    public function get_employee_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_joining_details');
        $this->db->where('id', $id);
        $record = $this->db->get()->row_array();
        
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function update_employee_details()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $id = $this->input->post('id');
        
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }
        
        $this->db->where('id', $id);
        $exists = $this->db->get(db_prefix() . 'staff_joining_details')->row();
        
        if (!$exists) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }
        
        $updateData = [
            'name' => trim($this->input->post('name')),
            'contact_number' => trim($this->input->post('contact_number')),
            'emergency_contact_number' => trim($this->input->post('emergency_contact_number')),
            'email' => trim($this->input->post('email')),
            'pan_number' => trim($this->input->post('pan_number')),
            'aadhaar_number' => trim($this->input->post('aadhaar_number')),
            'date_of_birth' => $this->input->post('date_of_birth') ?: null,
            'assigned_designation' => trim($this->input->post('assigned_designation')),
            'department' => trim($this->input->post('department')),
            'date_of_joining' => $this->input->post('date_of_joining') ?: null,
            'current_address' => trim($this->input->post('current_address')),
            'permanent_address' => trim($this->input->post('permanent_address')),
            'ref1_name' => trim($this->input->post('ref1_name')),
            'ref1_relation' => trim($this->input->post('ref1_relation')),
            'ref1_contact' => trim($this->input->post('ref1_contact')),
            'ref2_name' => trim($this->input->post('ref2_name')),
            'ref2_relation' => trim($this->input->post('ref2_relation')),
            'ref2_contact' => trim($this->input->post('ref2_contact')),
            'ref3_name' => trim($this->input->post('ref3_name')),
            'ref3_relation' => trim($this->input->post('ref3_relation')),
            'ref3_contact' => trim($this->input->post('ref3_contact')),
            'ref4_name' => trim($this->input->post('ref4_name')),
            'ref4_relation' => trim($this->input->post('ref4_relation')),
            'ref4_contact' => trim($this->input->post('ref4_contact')),
            'ref5_name' => trim($this->input->post('ref5_name')),
            'ref5_relation' => trim($this->input->post('ref5_relation')),
            'ref5_contact' => trim($this->input->post('ref5_contact')),
            'ref6_name' => trim($this->input->post('ref6_name')),
            'ref6_relation' => trim($this->input->post('ref6_relation')),
            'ref6_contact' => trim($this->input->post('ref6_contact')),
            'ref7_name' => trim($this->input->post('ref7_name')),
            'ref7_relation' => trim($this->input->post('ref7_relation')),
            'ref7_contact' => trim($this->input->post('ref7_contact')),
            'ref8_name' => trim($this->input->post('ref8_name')),
            'ref8_relation' => trim($this->input->post('ref8_relation')),
            'ref8_contact' => trim($this->input->post('ref8_contact')),
            'status' => $this->input->post('status') ?: 'Draft',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        
        $this->db->where('id', $id);
        $success = $this->db->update(db_prefix() . 'staff_joining_details', $updateData);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update record']);
        }
    }

    public function delete_employee_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        
        $this->db->where('id', $id);
        $success = $this->db->delete(db_prefix() . 'staff_joining_details');
        
        if ($success && $this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    }

    public function download_employee_details_pdf($id)
    {
        $this->db->where('id', $id);
        $record = $this->db->get(db_prefix() . 'staff_joining_details')->row_array();

        if (!$record) {
            set_alert('danger', 'Record not found');
            redirect(admin_url('powerform/employee_details_form'));
        }

        $this->load->helper('pdf');

        try {
            $pdf = employee_details_pdf($record);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $name = isset($record['name']) ? slug_it($record['name']) : 'employee-details';
        $filename = 'employee-details-' . $name . '-' . $id;

        $type = 'D';
        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }
        if ($this->input->get('print')) {
            $type = 'I';
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output(mb_strtoupper($filename) . '.pdf', $type);
    }

    // Job Application Form
    public function job_application_form()
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_job_application_form');
        $this->db->order_by('created_at', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        $data['title'] = 'Job Application Form';
        $this->load->view('admin/powerform/job_application_form', $data);
    }

    public function get_job_application_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_job_application_form');
        $this->db->where('id', $id);
       
        $record = $this->db->get()->row_array();
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function update_job_application_details()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }
        $this->db->where('id', $id);
       
        $exists = $this->db->get(db_prefix() . 'staff_job_application_form')->row();
        if (!$exists) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }
        $updateData = [
            'full_name' => trim($this->input->post('full_name')),
            'sex' => trim($this->input->post('sex')),
            'applied_post' => trim($this->input->post('applied_post')),
            'mobile_no' => trim($this->input->post('mobile_no')),
            'alternative_no' => trim($this->input->post('alternative_no')),
            'address_with_pincode' => trim($this->input->post('address_with_pincode')),
            'email' => trim($this->input->post('email')),
            'marital_status' => trim($this->input->post('marital_status')),
            'edu1_degree' => trim($this->input->post('edu1_degree')),
            'edu1_university' => trim($this->input->post('edu1_university')),
            'edu1_major_subject' => trim($this->input->post('edu1_major_subject')),
            'edu1_year' => trim($this->input->post('edu1_year')),
            'edu2_degree' => trim($this->input->post('edu2_degree')),
            'edu2_university' => trim($this->input->post('edu2_university')),
            'edu2_major_subject' => trim($this->input->post('edu2_major_subject')),
            'edu2_year' => trim($this->input->post('edu2_year')),
            'edu3_degree' => trim($this->input->post('edu3_degree')),
            'edu3_university' => trim($this->input->post('edu3_university')),
            'edu3_major_subject' => trim($this->input->post('edu3_major_subject')),
            'edu3_year' => trim($this->input->post('edu3_year')),
            'edu4_degree' => trim($this->input->post('edu4_degree')),
            'edu4_university' => trim($this->input->post('edu4_university')),
            'edu4_major_subject' => trim($this->input->post('edu4_major_subject')),
            'edu4_year' => trim($this->input->post('edu4_year')),
            'edu5_degree' => trim($this->input->post('edu5_degree')),
            'edu5_university' => trim($this->input->post('edu5_university')),
            'edu5_major_subject' => trim($this->input->post('edu5_major_subject')),
            'edu5_year' => trim($this->input->post('edu5_year')),
            'fam1_name' => trim($this->input->post('fam1_name')),
            'fam1_age' => $this->input->post('fam1_age') ? (int) $this->input->post('fam1_age') : null,
            'fam1_relationship' => trim($this->input->post('fam1_relationship')),
            'fam1_occupation' => trim($this->input->post('fam1_occupation')),
            'fam2_name' => trim($this->input->post('fam2_name')),
            'fam2_age' => $this->input->post('fam2_age') ? (int) $this->input->post('fam2_age') : null,
            'fam2_relationship' => trim($this->input->post('fam2_relationship')),
            'fam2_occupation' => trim($this->input->post('fam2_occupation')),
            'fam3_name' => trim($this->input->post('fam3_name')),
            'fam3_age' => $this->input->post('fam3_age') ? (int) $this->input->post('fam3_age') : null,
            'fam3_relationship' => trim($this->input->post('fam3_relationship')),
            'fam3_occupation' => trim($this->input->post('fam3_occupation')),
            'fam4_name' => trim($this->input->post('fam4_name')),
            'fam4_age' => $this->input->post('fam4_age') ? (int) $this->input->post('fam4_age') : null,
            'fam4_relationship' => trim($this->input->post('fam4_relationship')),
            'fam4_occupation' => trim($this->input->post('fam4_occupation')),
            'fam5_name' => trim($this->input->post('fam5_name')),
            'fam5_age' => $this->input->post('fam5_age') ? (int) $this->input->post('fam5_age') : null,
            'fam5_relationship' => trim($this->input->post('fam5_relationship')),
            'fam5_occupation' => trim($this->input->post('fam5_occupation')),
            'fam6_name' => trim($this->input->post('fam6_name')),
            'fam6_age' => $this->input->post('fam6_age') ? (int) $this->input->post('fam6_age') : null,
            'fam6_relationship' => trim($this->input->post('fam6_relationship')),
            'fam6_occupation' => trim($this->input->post('fam6_occupation')),
            'job1_title' => trim($this->input->post('job1_title')),
            'job1_start_date' => $this->input->post('job1_start_date') ?: null,
            'job1_end_date' => $this->input->post('job1_end_date') ?: null,
            'job1_company' => trim($this->input->post('job1_company')),
            'job1_designation' => trim($this->input->post('job1_designation')),
            'job1_reason_for_leaving' => trim($this->input->post('job1_reason_for_leaving')),
            'job1_start_salary' => trim($this->input->post('job1_start_salary')),
            'job1_end_salary' => trim($this->input->post('job1_end_salary')),
            'job2_title' => trim($this->input->post('job2_title')),
            'job2_start_date' => $this->input->post('job2_start_date') ?: null,
            'job2_end_date' => $this->input->post('job2_end_date') ?: null,
            'job2_company' => trim($this->input->post('job2_company')),
            'job2_designation' => trim($this->input->post('job2_designation')),
            'job2_reason_for_leaving' => trim($this->input->post('job2_reason_for_leaving')),
            'job2_start_salary' => trim($this->input->post('job2_start_salary')),
            'job2_end_salary' => trim($this->input->post('job2_end_salary')),
            'has_linkedin' => $this->input->post('has_linkedin') ?: null,
            'linkedin_connections' => trim($this->input->post('linkedin_connections')),
            'major_skill_1' => trim($this->input->post('major_skill_1')),
            'major_skill_2' => trim($this->input->post('major_skill_2')),
            'coordinating_person_name' => trim($this->input->post('coordinating_person_name')),
            'interviewed_by_1' => trim($this->input->post('interviewed_by_1')),
            'interviewed_by_2' => trim($this->input->post('interviewed_by_2')),
            'doj_datetime' => $this->input->post('doj_datetime') ?: null,
            'offered_salary' => trim($this->input->post('offered_salary')),
            'offered_designation' => trim($this->input->post('offered_designation')),
            'interview_remarks_1' => trim($this->input->post('interview_remarks_1')),
            'interview_remarks_2' => trim($this->input->post('interview_remarks_2')),
            'status' => $this->input->post('status') ?: 'Draft',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->where('id', $id);
        
        $success = $this->db->update(db_prefix() . 'staff_job_application_form', $updateData);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update record']);
        }
    }

    public function delete_job_application_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->db->where('id', $id);
        
        $success = $this->db->delete(db_prefix() . 'staff_job_application_form');
        if ($success && $this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    }

    public function download_job_application_pdf($id)
    {
        $this->db->where('id', $id);
        $record = $this->db->get(db_prefix() . 'staff_job_application_form')->row_array();

        if (!$record) {
            set_alert('danger', 'Record not found');
            redirect(admin_url('powerform/job_application_form'));
        }

        $this->load->helper('pdf');

        try {
            $pdf = job_application_pdf($record);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $name = isset($record['full_name']) ? slug_it($record['full_name']) : 'job-application';
        $filename = 'job-application-' . $name . '-' . $id;

        $type = 'D';
        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }
        if ($this->input->get('print')) {
            $type = 'I';
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output(mb_strtoupper($filename) . '.pdf', $type);
    }

    // Joining Form (it_crm_staff_joining_form)
    public function joining_form()
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_joining_form');
        $this->db->order_by('created_at', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        $data['title'] = 'Joining Form';
        $this->load->view('admin/powerform/joining_form', $data);
    }

    public function get_joining_form_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_joining_form');
        $this->db->where('id', $id);
        
        $record = $this->db->get()->row_array();
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function update_joining_form_details()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }
        $this->db->where('id', $id);
        
        $exists = $this->db->get(db_prefix() . 'staff_joining_form')->row();
        if (!$exists) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }
        $updateData = [
            'name' => trim($this->input->post('name')),
            'father_husband_name' => trim($this->input->post('father_husband_name')),
            'contact_number' => trim($this->input->post('contact_number')),
            'emergency_contact_number' => trim($this->input->post('emergency_contact_number')),
            'email' => trim($this->input->post('email')),
            'pan_number' => trim($this->input->post('pan_number')),
            'aadhaar_number' => trim($this->input->post('aadhaar_number')),
            'date_of_birth' => $this->input->post('date_of_birth') ?: null,
            'assigned_designation' => trim($this->input->post('assigned_designation')),
            'department' => trim($this->input->post('department')),
            'date_of_joining' => $this->input->post('date_of_joining') ?: null,
            'current_address_line1' => trim($this->input->post('current_address_line1')),
            'current_address_line2' => trim($this->input->post('current_address_line2')),
            'current_address_line3' => trim($this->input->post('current_address_line3')),
            'permanent_address_line1' => trim($this->input->post('permanent_address_line1')),
            'permanent_address_line2' => trim($this->input->post('permanent_address_line2')),
            'permanent_address_line3' => trim($this->input->post('permanent_address_line3')),
            'status' => $this->input->post('status') ?: 'Draft',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->where('id', $id);
        
        $success = $this->db->update(db_prefix() . 'staff_joining_form', $updateData);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update record']);
        }
    }

    public function delete_joining_form_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->db->where('id', $id);
       
        $success = $this->db->delete(db_prefix() . 'staff_joining_form');
        if ($success && $this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    }

    public function download_joining_form_pdf($id)
    {
        $this->db->where('id', $id);
        $record = $this->db->get(db_prefix() . 'staff_joining_form')->row_array();

        if (!$record) {
            set_alert('danger', 'Record not found');
            redirect(admin_url('powerform/joining_form'));
        }

        $this->load->helper('pdf');

        try {
            $pdf = joining_form_pdf($record);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $name = isset($record['name']) ? slug_it($record['name']) : 'joining-form';
        $filename = 'joining-form-' . $name . '-' . $id;

        $type = 'D';
        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }
        if ($this->input->get('print')) {
            $type = 'I';
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output(mb_strtoupper($filename) . '.pdf', $type);
    }

    // KYC Form (it_crm_staff_kyc_details)
    public function kyc_form()
    {
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_kyc_details');
        $this->db->order_by('created_at', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        $data['title'] = 'KYC Form';
        $this->load->view('admin/powerform/kyc_form', $data);
    }

    public function get_kyc_form_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->db->select('*');
        $this->db->from(db_prefix() . 'staff_kyc_details');
        $this->db->where('id', $id);
        $record = $this->db->get()->row_array();
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function update_kyc_form_details()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }
        $this->db->where('id', $id);
        
        $exists = $this->db->get(db_prefix() . 'staff_kyc_details')->row();
        if (!$exists) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }
        $updateData = [
            'candidate_name' => trim($this->input->post('candidate_name')),
            'father_name' => trim($this->input->post('father_name')),
            'mother_name' => trim($this->input->post('mother_name')),
            'date_of_birth' => $this->input->post('date_of_birth') ?: null,
            'marital_status' => trim($this->input->post('marital_status')),
            'email' => trim($this->input->post('email')),
            'contact_number' => trim($this->input->post('contact_number')),
            'alternate_contact_number' => trim($this->input->post('alternate_contact_number')),
            'aadhaar_number' => trim($this->input->post('aadhaar_number')),
            'pan_number' => trim($this->input->post('pan_number')),
            'present_complete_address' => trim($this->input->post('present_complete_address')),
            'present_landmark' => trim($this->input->post('present_landmark')),
            'present_city' => trim($this->input->post('present_city')),
            'present_state' => trim($this->input->post('present_state')),
            'present_pin_code' => trim($this->input->post('present_pin_code')),
            'present_police_station' => trim($this->input->post('present_police_station')),
            'present_stay_from' => trim($this->input->post('present_stay_from')),
            'present_stay_to' => trim($this->input->post('present_stay_to')),
            'permanent_complete_address' => trim($this->input->post('permanent_complete_address')),
            'permanent_landmark' => trim($this->input->post('permanent_landmark')),
            'permanent_city' => trim($this->input->post('permanent_city')),
            'permanent_state' => trim($this->input->post('permanent_state')),
            'permanent_pin_code' => trim($this->input->post('permanent_pin_code')),
            'permanent_police_station' => trim($this->input->post('permanent_police_station')),
            'permanent_stay_from' => trim($this->input->post('permanent_stay_from')),
            'permanent_stay_to' => trim($this->input->post('permanent_stay_to')),
            'edu1_institute_name' => trim($this->input->post('edu1_institute_name')),
            'edu1_course_name' => trim($this->input->post('edu1_course_name')),
            'edu1_passing_year' => $this->input->post('edu1_passing_year') ?: null,
            'edu1_registration_number' => trim($this->input->post('edu1_registration_number')),
            'edu1_mode' => $this->input->post('edu1_mode') ?: null,
            'edu2_institute_name' => trim($this->input->post('edu2_institute_name')),
            'edu2_course_name' => trim($this->input->post('edu2_course_name')),
            'edu2_passing_year' => $this->input->post('edu2_passing_year') ?: null,
            'edu2_registration_number' => trim($this->input->post('edu2_registration_number')),
            'edu2_mode' => $this->input->post('edu2_mode') ?: null,
            'edu3_institute_name' => trim($this->input->post('edu3_institute_name')),
            'edu3_course_name' => trim($this->input->post('edu3_course_name')),
            'edu3_passing_year' => $this->input->post('edu3_passing_year') ?: null,
            'edu3_registration_number' => trim($this->input->post('edu3_registration_number')),
            'edu3_mode' => $this->input->post('edu3_mode') ?: null,
            'org1_name' => trim($this->input->post('org1_name')),
            'org1_address' => trim($this->input->post('org1_address')),
            'org1_designation' => trim($this->input->post('org1_designation')),
            'org1_employee_code' => trim($this->input->post('org1_employee_code')),
            'org1_date_of_joining' => $this->input->post('org1_date_of_joining') ?: null,
            'org1_last_working_day' => $this->input->post('org1_last_working_day') ?: null,
            'org1_salary_ctc' => trim($this->input->post('org1_salary_ctc')),
            'org1_reason_for_leaving' => trim($this->input->post('org1_reason_for_leaving')),
            'org1_reporting_manager_name' => trim($this->input->post('org1_reporting_manager_name')),
            'org1_reporting_manager_contact' => trim($this->input->post('org1_reporting_manager_contact')),
            'org1_reporting_manager_email' => trim($this->input->post('org1_reporting_manager_email')),
            'org1_hr1_name' => trim($this->input->post('org1_hr1_name')),
            'org1_hr1_contact' => trim($this->input->post('org1_hr1_contact')),
            'org1_hr1_email' => trim($this->input->post('org1_hr1_email')),
            'org1_hr2_name' => trim($this->input->post('org1_hr2_name')),
            'org1_hr2_contact' => trim($this->input->post('org1_hr2_contact')),
            'org1_hr2_email' => trim($this->input->post('org1_hr2_email')),
            'referee1_name' => trim($this->input->post('referee1_name')),
            'referee1_organization' => trim($this->input->post('referee1_organization')),
            'referee1_designation' => trim($this->input->post('referee1_designation')),
            'referee1_contact' => trim($this->input->post('referee1_contact')),
            'referee1_email' => trim($this->input->post('referee1_email')),
            'referee2_name' => trim($this->input->post('referee2_name')),
            'referee2_organization' => trim($this->input->post('referee2_organization')),
            'referee2_designation' => trim($this->input->post('referee2_designation')),
            'referee2_contact' => trim($this->input->post('referee2_contact')),
            'referee2_email' => trim($this->input->post('referee2_email')),
            'status' => $this->input->post('status') ?: 'Draft',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $success = $this->db->update(db_prefix() . 'staff_kyc_details', $updateData);
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update record']);
        }
    }

    public function delete_kyc_form_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $this->db->where('id', $id);
       
        $success = $this->db->delete(db_prefix() . 'staff_kyc_details');
        if ($success && $this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    }

    public function download_kyc_form_pdf($id)
    {
        $this->db->where('id', $id);
        $record = $this->db->get(db_prefix() . 'staff_kyc_details')->row_array();

        if (!$record) {
            set_alert('danger', 'Record not found');
            redirect(admin_url('powerform/kyc_form'));
        }

        $this->load->helper('pdf');

        try {
            $pdf = kyc_form_pdf($record);
        } catch (Exception $e) {
            $message = $e->getMessage();
            echo $message;
            if (strpos($message, 'Unable to get the size of the image') !== false) {
                show_pdf_unable_to_get_image_size_error();
            }
            die;
        }

        $name = isset($record['candidate_name']) ? slug_it($record['candidate_name']) : 'kyc-form';
        $filename = 'kyc-form-' . $name . '-' . $id;

        $type = 'D';
        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }
        if ($this->input->get('print')) {
            $type = 'I';
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        $pdf->Output(mb_strtoupper($filename) . '.pdf', $type);
    }
}
