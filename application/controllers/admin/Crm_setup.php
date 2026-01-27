<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crm_setup extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'CRM Setup';
        $company_id    = get_staff_company_id();
        $active_count  = 0;
        $source_count  = 0;
        $project_count = 0;
        $leave_count   = 0;
        $leave_type_count = 0;
        $branch_manager_count = 0;
        $employee_type_count = 0;
        $shift_manager_count = 0;
        $shift_type_count = 0;
        $deal_stage_count = 0;
        $task_status_count = 0;
        $lead_source_count = 0;
        $department_count  = 0;
        $designation_count = 0;
        $staff_type_count  = 0;
        $company_details   = [];
        $ai_details_count  = 0;

        if (!empty($company_id)) {
            $active_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_interview_process');
            $source_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_interview_source');
            $project_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'project_group');
            $leave_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_leave_rule');
            $leave_type_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_leave_type');
            $branch_manager_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_branch_manager');
            $employee_type_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_employee_type');
            $shift_manager_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_shift_manager');
            $shift_type_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_shift_type');
            $deal_stage_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'deals_stage');
            $task_status_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'task_status');
            $lead_source_count = (int) $this->db->count_all_results(db_prefix() . 'leads_sources');
            $department_count = (int) $this->db->where('company_id', $company_id)
                ->count_all_results(db_prefix() . 'departments');
            $designation_count = (int) $this->db->where('company_id', $company_id)
                ->where('is_active', 1)
                ->count_all_results(db_prefix() . 'designations');
            $staff_type_count = (int) $this->db->where('company_id', $company_id)
                ->where('status', 1)
                ->count_all_results(db_prefix() . 'hrd_staff_type');
            $company_details = (array) $this->db->select('companyname, website, company_logo, favicon, settings, nda_url, nda_smtp, direct_mail_smtp')
                ->where('company_id', $company_id)
                ->get(db_prefix() . 'company_master')
                ->row_array();
            $ai_details_count = (int) $this->db->where('company_id', $company_id)
                ->count_all_results(db_prefix() . 'ai_details');
        }

        $data['active_interview_process_count'] = $active_count;
        $data['active_interview_source_count']  = $source_count;
        $data['active_project_group_count']     = $project_count;
        $data['active_leave_rule_count']        = $leave_count;
        $data['active_leave_type_count']        = $leave_type_count;
        $data['active_branch_manager_count']    = $branch_manager_count;
        $data['active_employee_type_count']     = $employee_type_count;
        $data['active_shift_manager_count']     = $shift_manager_count;
        $data['active_shift_type_count']        = $shift_type_count;
        $data['active_deal_stage_count']        = $deal_stage_count;
        $data['active_task_status_count']       = $task_status_count;
        $data['active_lead_source_count']       = $lead_source_count;
        $data['active_department_count']        = $department_count;
        $data['active_designation_count']       = $designation_count;
        $data['active_staff_type_count']        = $staff_type_count;
        $data['company_details']                = $company_details;
        $data['active_ai_details_count']        = $ai_details_count;
        $this->load->view('admin/crm_setup/manage', $data);
    }
}
