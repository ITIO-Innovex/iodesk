<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hrd extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hrd_model');
    }

    /* View Interview Status */
    public function setting($type = '')
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('HRD Settings');
        }

        if ($type == 'interview_status') {
            $this->interview_status();
        } elseif ($type == 'interview_process') {
            $this->interview_process();
        } elseif ($type == 'interview_source') {
            $this->interview_source();
        } elseif ($type == 'leave_type') {
            $this->leave_type();
        } elseif ($type == 'employee_type') {
            $this->employee_type();
        } elseif ($type == 'branch_manager') {
            $this->branch_manager();
        } elseif ($type == 'holiday_list') {
            $this->holiday_list();
        } elseif ($type == 'todays_thought') {
            $this->todays_thought();
        } elseif ($type == 'leave_rule') {
            $this->leave_rule();
        } elseif ($type == 'shift_type') {
            $this->shift_type();
        } elseif ($type == 'corporate_guidelines') {
            $this->corporate_guidelines();
        } elseif ($type == 'company_policies') {
            $this->company_policies();
        } elseif ($type == 'events_announcements') {
            $this->events_announcements();
        } elseif ($type == 'attendance_status') {
            $this->attendance_status();
        } elseif ($type == 'shift_manager') {
            $this->shift_manager();
        } else {
            // Default HRD settings page should look like /admin/reports
            $data['title'] = 'Settings';
            $this->load->view('admin/hrd/setting/setting_home', $data);
        }
    }
	
	 public function dashboard()
    {
        /*if(is_admin() || (is_staff_member() && staff_can('project_dashboard',  'project'))) { 
        }else{
		access_denied('Project Dashboard');
		}
        
        $this->load->model('project_model');
        $this->load->model('staff_model');
        
        // Get dashboard statistics
        $data['stats'] = $this->project_model->get_dashboard_stats();
        
        // Get latest projects and tasks
        $data['latest_projects'] = $this->project_model->get_latest_projects(5);
        $data['latest_tasks'] = $this->project_model->get_latest_tasks(5);
        
        // Get chart data
        $data['project_status_chart'] = $this->project_model->get_project_status_chart_data();
        $data['task_status_chart'] = $this->project_model->get_task_status_chart_data();
        $data['monthly_projects'] = $this->project_model->get_monthly_projects_chart();
        */
		
		// Get get events announcements
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
        $data['events_announcements'] = $this->hrd_model->get_events_announcements();
		
		
		// Get latest company policy
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
		$policy = $this->db->get(db_prefix() . 'hrd_company_policies')->row_array();
		
		if ($policy) {
			// Get all active attachments for that policy
			$this->db->where('policy_id', $policy['id']);
			$this->db->where('status', 1);
			$attachments = $this->db->get(db_prefix() . 'hrd_company_policy_attachments')->result_array();
		
			$policy['attachments'] = $attachments;
		}

        $data['company_policies'] = $policy;
        //$data['company_policies'] = $this->hrd_model->get_company_policies();
		
		
		// Get get todays thought
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
        $data['corporate_guidelines'] = $this->hrd_model->get_corporate_guidelines();
		
		// Get get leave rule
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
        $data['leave_rule'] = $this->hrd_model->get_leave_rule();
		
		
		
        // Get get todays thought
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
        $data['todays_thought'] = $this->hrd_model->get_todays_thought();
		
		// Get get holiday list
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->where('YEAR(holiday_date)', date('Y')); // filter by year
		$this->db->order_by('holiday_date', 'asc');
        $data['holiday_lists'] = $this->hrd_model->get_holiday_list();
        $data['title'] = 'My Desk - HR & Policy';
		
		// Get todays Attendance
		$data['attendance']                = $this->hrd_model->get_todays_attendance();
		
		// Get chart data
        $data['attendance_stats'] = $this->hrd_model->get_attendance_stats();
		
        $this->load->view('admin/hrd/dashboard', $data);
    }

    /* View Interview Status */
    public function interview_status()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Status');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['statuses'] = $this->hrd_model->get_interview_status();
        $data['title'] = 'Interview Status';
        $this->load->view('admin/hrd/setting/interview_status', $data);
    }

    // Add/Edit Interview Status
    public function interviewstatus()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Status');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_interview_status', $data);
            set_alert('success', 'Interview status updated successfully');exit;
        } else {
            // Default new interview status to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_interview_status', $data);
            set_alert('success', 'Interview status added successfully');exit;
        }
        redirect(admin_url('hrd/setting/interview_status'));
    }

    // Delete Interview Status
    public function delete_interview_status($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Status');
        }
        // Soft delete: set status to 0 (Deactive) instead of removing the record
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_interview_status', ['status' => 0]);
        set_alert('success', 'Interview status deactivated successfully');
        redirect(admin_url('hrd/setting/interview_status'));
    }

    // Toggle Interview Status Status (AJAX)
    public function toggle_interview_status($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_interview_status', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Add/Edit Events & Announcements
    public function eventsannouncements()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Events & Announcements');
        }

        $id = $this->input->post('id');
        $data = [
            'branch' => $this->input->post('branch'),
            'title' => $this->input->post('title'),
            'details' => $this->input->post('details'),
            'company_id' => get_staff_company_id(),
            'addedby' => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_events_announcements', $data);
            set_alert('success', 'Event/Announcement updated successfully');exit;
        } else {
            // Default new records to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_events_announcements', $data);
            set_alert('success', 'Event/Announcement added successfully');exit;
        }
        redirect(admin_url('hrd/setting/events_announcements'));
    }

    // Delete Events & Announcements (soft)
    public function delete_events_announcements($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Events & Announcements');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_events_announcements', ['status' => 0]);
        set_alert('success', 'Event/Announcement deactivated successfully');
        redirect(admin_url('hrd/setting/events_announcements'));
    }

    // Toggle Events & Announcements Status (AJAX)
    public function toggle_events_announcements($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_events_announcements', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    /* View Interview Process */
    public function interview_process()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Process');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['processes'] = $this->hrd_model->get_interview_process();
        $data['title'] = 'Interview Process';
        $this->load->view('admin/hrd/setting/interview_process', $data);
    }

    // Add/Edit Interview Process
    public function interviewprocess()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Process');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_interview_process', $data);
            set_alert('success', 'Interview process updated successfully');exit;
        } else {
            // Default new interview processes to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_interview_process', $data);
            set_alert('success', 'Interview process added successfully');exit;
        }
        redirect(admin_url('hrd/setting/interview_process'));
    }

    // Delete Interview Process
    public function delete_interview_process($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Process');
        }
        // Soft delete: set status to 0 (Deactive) instead of removing the record
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_interview_process', ['status' => 0]);
        set_alert('success', 'Interview process deactivated successfully');
        redirect(admin_url('hrd/setting/interview_process'));
    }

    // Toggle Interview Process Status (AJAX)
    public function toggle_interview_process($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_interview_process', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    /* View Interview Source */
    public function interview_source()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Source');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['sources'] = $this->hrd_model->get_interview_source();
        $data['title'] = 'Interview Source';
        $this->load->view('admin/hrd/setting/interview_source', $data);
    }

    // Add/Edit Interview Source
    public function interviewsource()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Source');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_interview_source', $data);
            set_alert('success', 'Interview source updated successfully');exit;
        } else {
            // Default new interview sources to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_interview_source', $data);
            set_alert('success', 'Interview source added successfully');exit;
        }
        redirect(admin_url('hrd/setting/interview_source'));
    }

    // Delete Interview Source (soft)
    public function delete_interview_source($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interview Source');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_interview_source', ['status' => 0]);
        set_alert('success', 'Interview source deactivated successfully');
        redirect(admin_url('hrd/setting/interview_source'));
    }

    // Toggle Interview Source Status (AJAX)
    public function toggle_interview_source($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_interview_source', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    /* View Leave Type */
    public function leave_type()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Type');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['leave_types'] = $this->hrd_model->get_leave_type();
        $data['title'] = 'Leave Type';
        $this->load->view('admin/hrd/setting/leave_type', $data);
    }

    // Add/Edit Leave Type
    public function leavetype()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Type');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'remark' => $this->input->post('remark'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_leave_type', $data);
            set_alert('success', 'Leave type updated successfully');exit;
        } else {
            // Default new leave types to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_leave_type', $data);
            set_alert('success', 'Leave type added successfully');exit;
        }
        redirect(admin_url('hrd/setting/leave_type'));
    }

    // Delete Leave Type
    public function delete_leave_type($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Type');
        }
        // Soft delete: set status to 0 (Deactive) instead of removing the record
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_leave_type', ['status' => 0]);
        set_alert('success', 'Leave type deactivated successfully');
        redirect(admin_url('hrd/setting/leave_type'));
    }

    // Toggle Leave Type Status (AJAX)
    public function toggle_leave_type($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_leave_type', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    /* View Employee Type */
    public function employee_type()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Employee Type');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['employee_types'] = $this->hrd_model->get_employee_type();
        $data['title'] = 'Employee Type';
        $this->load->view('admin/hrd/setting/employee_type', $data);
    }

    /* View Shift Type */
    public function shift_type()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Shift Type');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['shift_types'] = $this->hrd_model->get_shift_type();
        $data['title'] = 'Shift Type';
        $this->load->view('admin/hrd/setting/shift_type', $data);
    }

    /* View Shift Manager */
    public function shift_manager()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Shift Manager');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['shift_managers'] = $this->hrd_model->get_shift_manager();

        // Load shift types for dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('status', 1);
        $data['shift_types'] = $this->hrd_model->get_shift_type();

        $data['title'] = 'Shift Manager';
        $this->load->view('admin/hrd/setting/shift_manager', $data);
    }

    // Add/Edit Employee Type
    public function employeetype()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Employee Type');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'status' => $this->input->post('status') == '1' ? 1 : 0,
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_employee_type', $data);
            set_alert('success', 'Employee type updated successfully');exit;
        } else {
            // Default new employee types to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_employee_type', $data);
            set_alert('success', 'Employee type added successfully');exit;
        }
        redirect(admin_url('hrd/setting/employee_type'));
    }

    // Add/Edit Shift Type
    public function shifttype()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Shift Type');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_shift_type', $data);
            set_alert('success', 'Shift type updated successfully');exit;
        } else {
            // Default new shift types to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_shift_type', $data);
            set_alert('success', 'Shift type added successfully');exit;
        }
        redirect(admin_url('hrd/setting/shift_type'));
    }

    // Add/Edit Shift Manager
    public function shiftmanager()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Shift Manager');
        }

        $id = $this->input->post('shift_id');
        $data = [
            'shift_code' => $this->input->post('shift_code'),
            'shift_name' => $this->input->post('shift_name'),
            'shift_in' => $this->input->post('shift_in'),
            'shift_out' => $this->input->post('shift_out'),
            'grace_period' => $this->input->post('grace_period'),
            'shift_type' => $this->input->post('shift_type'),
            'tea_break_in_minut' => $this->input->post('tea_break_in_minut'),
            'lunch_break_in_minut' => $this->input->post('lunch_break_in_minut'),
            'dinner_break_in_minut' => $this->input->post('dinner_break_in_minut'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('shift_id', $id);
            $this->db->update('it_crm_hrd_shift_manager', $data);
            set_alert('success', 'Shift updated successfully');exit;
        } else {
            // Default new shifts to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_shift_manager', $data);
            set_alert('success', 'Shift added successfully');exit;
        }
        redirect(admin_url('hrd/setting/shift_manager'));
    }

    // Delete Shift Manager (soft)
    public function delete_shift_manager($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Shift Manager');
        }
        $this->db->where('shift_id', $id);
        $this->db->update('it_crm_hrd_shift_manager', ['status' => 0]);
        set_alert('success', 'Shift deactivated successfully');
        redirect(admin_url('hrd/setting/shift_manager'));
    }

    // Toggle Shift Manager Status (AJAX)
    public function toggle_shift_manager($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('shift_id', $id);
        $this->db->update('it_crm_hrd_shift_manager', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Delete Shift Type (soft)
    public function delete_shift_type($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Shift Type');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_shift_type', ['status' => 0]);
        set_alert('success', 'Shift type deactivated successfully');
        redirect(admin_url('hrd/setting/shift_type'));
    }

    // Toggle Shift Type Status (AJAX)
    public function toggle_shift_type($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_shift_type', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Delete Employee Type
    public function delete_employee_type($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Employee Type');
        }
        // Soft delete: set status to 0 (Deactive) instead of removing the record
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_employee_type', ['status' => 0]);
        set_alert('success', 'Employee type deactivated successfully');
        redirect(admin_url('hrd/setting/employee_type'));
    }

    // Toggle Employee Type Status (AJAX)
    public function toggle_employee_type($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_employee_type', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    /* View Branch Manager */
    public function branch_manager()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Branch Manager');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['branch_managers'] = $this->hrd_model->get_branch_manager();
        $data['title'] = 'Branch Manager';
        $this->load->view('admin/hrd/setting/branch_manager', $data);
    }

    // Add/Edit Branch Manager
    public function branchmanager()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Branch Manager');
        }

        $id = $this->input->post('id');
        $data = [
            'branch_name' => $this->input->post('branch_name'),
            'branch_address' => $this->input->post('branch_address'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_branch_manager', $data);
            set_alert('success', 'Branch manager updated successfully');exit;
        } else {
            // Default new branch managers to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_branch_manager', $data);
            set_alert('success', 'Branch manager added successfully');exit;
        }
        redirect(admin_url('hrd/setting/branch_manager'));
    }

    // Delete Branch Manager
    public function delete_branch_manager($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Branch Manager');
        }
        // Soft delete: set status to 0 (Deactive) instead of removing the record
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_branch_manager', ['status' => 0]);
        set_alert('success', 'Branch manager deactivated successfully');
        redirect(admin_url('hrd/setting/branch_manager'));
    }

    // Toggle Branch Manager Status (AJAX)
    public function toggle_branch_manager($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_branch_manager', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    /* View Holiday List */
    public function holiday_list()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Holiday List');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['holiday_lists'] = $this->hrd_model->get_holiday_list();
        $data['title'] = 'Holiday List';
        $this->load->view('admin/hrd/setting/holiday_list', $data);
    }

    /* View Today's Thought */
    public function todays_thought()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Today`s Thought');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['thoughts'] = $this->hrd_model->get_todays_thought();
        $data['title'] = "Today's Thought";
        $this->load->view('admin/hrd/setting/todays_thought', $data);
    }

    /* View Leave Rule */
    public function leave_rule()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Rule');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['rules'] = $this->hrd_model->get_leave_rule();
        $data['title'] = 'Leave Rule';
        $this->load->view('admin/hrd/setting/leave_rule', $data);
    }

    /* View Corporate Guidelines */
    public function corporate_guidelines()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Corporate Guidelines');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['guidelines'] = $this->hrd_model->get_corporate_guidelines();
        // Fetch active branches for dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('status', 1);
        $data['branches'] = $this->hrd_model->get_branch_manager();
        $data['title'] = 'Corporate Guidelines';
        $this->load->view('admin/hrd/setting/corporate_guidelines', $data);
    }

    /* View Company Policies */
    public function company_policies()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Company Policies');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['policies'] = $this->hrd_model->get_company_policies();

        // Load attachments for each policy
        foreach ($data['policies'] as &$policy) {
            $policy['attachments'] = $this->hrd_model->get_company_policy_attachments($policy['id']);
        }

        // Fetch active branches for dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('status', 1);
        $data['branches'] = $this->hrd_model->get_branch_manager();

        $data['title'] = 'Company Policies';
        $this->load->view('admin/hrd/setting/company_policies', $data);
    }

    /* View Events & Announcements */
    public function events_announcements()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Events & Announcements');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['events'] = $this->hrd_model->get_events_announcements();

        // Fetch active branches for dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('status', 1);
        $data['branches'] = $this->hrd_model->get_branch_manager();

        $data['title'] = 'Events & Announcements';
        $this->load->view('admin/hrd/setting/events_announcements', $data);
    }

    /* View Attendance Status */
    public function attendance_status()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Attendance Status');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        $data['attendance_statuses'] = $this->hrd_model->get_attendance_status();
        $data['title'] = 'Attendance Status';
        $this->load->view('admin/hrd/setting/attendance_status', $data);
    }
/* View Leave Application */
    public function leave_manager()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Application');
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }

        // Filters
        $staffid    = trim((string)$this->input->get('staffid'));
        $fdate      = trim((string)$this->input->get('from_date'));
        $tdate      = trim((string)$this->input->get('to_date'));
        $ltype      = trim((string)$this->input->get('leave_type'));
        $lstatus    = trim((string)$this->input->get('leave_status'));

        if ($staffid !== '') { $this->db->where('staffid', (int)$staffid); }
        if ($ltype !== '') { $this->db->where('leave_type', $ltype); }
        if ($lstatus !== '') { $this->db->where('leave_status', (int)$lstatus); }
        if ($fdate !== '') { $this->db->where('from_date >=', $fdate); }
        if ($tdate !== '') { $this->db->where('to_date <=', $tdate); }

        $data['leave_list'] = $this->hrd_model->get_leave_application();
		
        // Load active staff for filter dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('active', 1);
        $data['staff_list'] = $this->db->get(db_prefix() . 'staff')->result_array();
        $data['filters'] = [
            'staffid' => $staffid,
            'from_date' => $fdate,
            'to_date' => $tdate,
            'leave_type' => $ltype,
            'leave_status' => $lstatus,
        ];

        // Load active leave types for dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('status', 1);
        $data['leave_types'] = $this->hrd_model->get_leave_type();

        $data['title'] = 'Leave Application Manager';
        $this->load->view('admin/hrd/leave_manager', $data);
    }
	
	
    /* View Leave Application */
    public function leave_application()
    {
        if (!staff_can('view_own',  'hr_department')) {
            access_denied('Leave Application');
        }

        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
		} elseif (is_admin()) {
		$this->db->where('company_id', get_staff_company_id());   
        } else {
         $this->db->where('staffid', get_staff_user_id());   
        }

        $data['leave_list'] = $this->hrd_model->get_leave_application();

        // Load active leave types for dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('status', 1);
        $data['leave_types'] = $this->hrd_model->get_leave_type();

        // Load active staff for filter dropdown
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        }
        $this->db->where('active', 1);
        $data['staff_list'] = $this->db->get(db_prefix() . 'staff')->result_array();

        // Get get leave rule
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1);
        $data['leave_rule'] = $this->hrd_model->get_leave_rule();
		
        $data['title'] = 'Leave Application';
        $this->load->view('admin/hrd/leave_application', $data);
    }

    // Add/Edit Leave Application
    public function leaveapplication()
    {
        if (!staff_can('view_own',  'hr_department')) {
            access_denied('Leave Application');
        }

        $leave_id = $this->input->post('leave_id');
        $data = [
            'staffid'      => ($this->input->post('staffid')) ? (int)$this->input->post('staffid') : get_staff_user_id(),
            'company_id'   => get_staff_company_id(),
            'from_date'    => $this->input->post('from_date'),
            'to_date'      => $this->input->post('to_date'),
            'leave_type'   => $this->input->post('leave_type'),
            'leave_for'    => $this->input->post('leave_for') !== null ? (int)$this->input->post('leave_for') : 1,
            'leave_reson'  => $this->input->post('leave_reson'),
        ];

        // Optional reply and status updates (e.g., by manager)
        if ($this->input->post('leave_reply') !== null) {
            $data['leave_reply'] = $this->input->post('leave_reply');
			$data['approved_by'] = get_staff_user_id();
        }
        if ($this->input->post('leave_status') !== null) {
            $data['leave_status'] = (int)$this->input->post('leave_status');
        }

        if ($leave_id) {
            $this->db->where('leave_id', $leave_id);
            $this->db->update('it_crm_hrd_leave_master', $data);
            set_alert('success', 'Leave application updated successfully');
            exit;
        } else {
            // Default new leave status to Pending (0)
            if (!isset($data['leave_status'])) {
                $data['leave_status'] = 0;
            }
            $this->db->insert('it_crm_hrd_leave_master', $data);
            set_alert('success', 'Leave application submitted successfully');
            exit;
        }
        redirect(admin_url('hrd/leave_application'));
    }

    /* View Attendance */
    public function attendance()
    {
        if (!staff_can('view_own',  'hr_department')) {
            access_denied('Attendance');
        }

        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } elseif (is_admin()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            $this->db->where('staffid', get_staff_user_id());
        }

        // Filters
        $staffid   = trim((string)$this->input->get('staffid'));
        $date_from = trim((string)$this->input->get('date_from'));
        $date_to   = trim((string)$this->input->get('date_to'));
        $month_year = trim((string)$this->input->get('month_year'));

        // If month_year (YYYY-MM) provided, derive date_from/to
        if ($month_year !== '') {
            // Expected format YYYY-MM from <input type="month">
            $parts = explode('-', $month_year);
            if (count($parts) === 2) {
                $y = (int)$parts[0];
                $m = (int)$parts[1];
                if ($y > 1900 && $m >= 1 && $m <= 12) {
                    $start = sprintf('%04d-%02d-01', $y, $m);
                    $end   = date('Y-m-t', strtotime($start));
                    $date_from = $date_from === '' ? $start : $date_from;
                    $date_to   = $date_to === '' ? $end   : $date_to;
                }
            }
        }
        // Default to current month when nothing provided
        if ($month_year === '' && $date_from === '' && $date_to === '') {
            $month_year = date('Y-m');
            $start = date('Y-m-01');
            $end   = date('Y-m-t');
            $date_from = $start;
            $date_to   = $end;
        }
        $shift_id  = trim((string)$this->input->get('shift_id'));
        $position   = trim((string)$this->input->get('position'));
        $late_mark = trim((string)$this->input->get('late_mark'));
        $fh        = trim((string)$this->input->get('first_half'));
        $sh        = trim((string)$this->input->get('second_half'));

        if ($staffid !== '') { $this->db->where('staffid', (int)$staffid); }
        if ($shift_id !== '') { $this->db->where('shift_id', (int)$shift_id); }
        if ($position !== '') { $this->db->where('position', $position); }
        if ($fh !== '') { $this->db->where('first_half', $fh); }
        if ($sh !== '') { $this->db->where('second_half', $sh); }
        if ($late_mark !== '') { $this->db->where('late_mark', (int)$late_mark); }
        if ($date_from !== '') { $this->db->where('entry_date >=', $date_from); }
        if ($date_to !== '') { $this->db->where('entry_date <=', $date_to); }

        $data['attendance_list'] = $this->hrd_model->get_attendance();
        $data['filters'] = [
            'staffid' => $staffid,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'shift_id' => $shift_id,
            'position' => $position,
            'late_mark' => $late_mark,
            'first_half' => $fh,
            'second_half' => $sh,
            'month_year' => $month_year,
        ];

        // Prepare calendar context when month_year provided (or when date_from indicates a single month)
        $calendarYear = null; $calendarMonth = null;
        if ($month_year !== '') {
            $parts = explode('-', $month_year);
            if (count($parts) === 2) { $calendarYear = (int)$parts[0]; $calendarMonth = (int)$parts[1]; }
        } elseif ($date_from !== '' && $date_to !== '') {
            $yf = (int)date('Y', strtotime($date_from));
            $mf = (int)date('n', strtotime($date_from));
            $yt = (int)date('Y', strtotime($date_to));
            $mt = (int)date('n', strtotime($date_to));
            if ($yf === $yt && $mf === $mt) { $calendarYear = $yf; $calendarMonth = $mf; }
        }

        $data['calendar'] = null;
        if ($calendarYear && $calendarMonth) {
            $firstDay = sprintf('%04d-%02d-01', $calendarYear, $calendarMonth);
            $lastDay  = date('Y-m-t', strtotime($firstDay));
            // Build day slots
            $daysInMonth = (int)date('t', strtotime($firstDay));
            $startWeekday = (int)date('N', strtotime($firstDay)); // 1=Mon..7=Sun
            $calendar = [
                'year' => $calendarYear,
                'month' => $calendarMonth,
                'days' => [],
                'start_weekday' => $startWeekday,
                'days_in_month' => $daysInMonth,
            ];
            // Group attendance by date
            $byDate = [];
            foreach ((array)$data['attendance_list'] as $row) {
                $d = isset($row['entry_date']) ? $row['entry_date'] : (isset($row['attendance_date']) ? $row['attendance_date'] : null);
                if ($d) { $byDate[$d][] = $row; }
            }
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $date = sprintf('%04d-%02d-%02d', $calendarYear, $calendarMonth, $d);
                $calendar['days'][] = [
                    'date' => $date,
                    'items' => isset($byDate[$date]) ? $byDate[$date] : [],
                ];
            }
            $data['calendar'] = $calendar;
        }

        // Load active shifts for dropdown
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('status', 1);
        $data['shifts'] = $this->hrd_model->get_shift_manager();

        // Load active staff for filter dropdown
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('active', 1);
        $data['staff_list'] = $this->db->get(db_prefix() . 'staff')->result_array();

        $data['title'] = 'Attendance';
        $this->load->view('admin/hrd/attendance', $data);
    }
	
	/* View Attendance */
    public function attendance_manager()
    {
        if (!staff_can('view_own',  'hr_department')) {
            access_denied('Attendance');
        }

        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } elseif (is_admin()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            $this->db->where('staffid', get_staff_user_id());
        }

        // Filters
        $staffid   = trim((string)$this->input->get('staffid'));
        $date_from = trim((string)$this->input->get('date_from'));
        $date_to   = trim((string)$this->input->get('date_to'));
        $shift_id  = trim((string)$this->input->get('shift_id'));
        $position   = trim((string)$this->input->get('position'));
        $late_mark = trim((string)$this->input->get('late_mark'));
        $fh        = trim((string)$this->input->get('first_half'));
        $sh        = trim((string)$this->input->get('second_half'));

        if ($staffid !== '') { $this->db->where('staffid', (int)$staffid); }
        if ($shift_id !== '') { $this->db->where('shift_id', (int)$shift_id); }
        if ($position !== '') { $this->db->where('position', $position); }
        if ($fh !== '') { $this->db->where('first_half', $fh); }
        if ($sh !== '') { $this->db->where('second_half', $sh); }
        if ($late_mark !== '') { $this->db->where('late_mark', (int)$late_mark); }
        if ($date_from !== '') { $this->db->where('attendance_date >=', $date_from); }
        if ($date_to !== '') { $this->db->where('attendance_date <=', $date_to); }

        $data['attendance_list'] = $this->hrd_model->get_attendance();
        $data['filters'] = [
            'staffid' => $staffid,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'shift_id' => $shift_id,
            'position' => $position,
            'late_mark' => $late_mark,
            'first_half' => $fh,
            'second_half' => $sh,
        ];

        // Load active shifts for dropdown
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('status', 1);
        $data['shifts'] = $this->hrd_model->get_shift_manager();

        // Load active staff for filter dropdown
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('active', 1);
        $data['staff_list'] = $this->db->get(db_prefix() . 'staff')->result_array();

        // Load active attendance statuses for first/second half dropdowns
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('status', 1);
        $data['attendance_statuses'] = $this->hrd_model->get_attendance_status();

        $data['title'] = 'Attendance Manager';
        $this->load->view('admin/hrd/attendance_manager', $data);
    }

    // Add/Edit Attendance Entry
    public function attendanceentry()
    {
        if (!staff_can('view_own',  'hr_department')) {
            access_denied('Attendance');
        }
        
        $attendance_id = $this->input->post('attendance_id');
        $in_time  = $this->input->post('in_time');
        $out_time = $this->input->post('out_time');
        $total_hours = $this->input->post('total_hours');

        // Compute total_hours if not provided and both datetimes present
        if ($in_time && $out_time) {
			$start = strtotime($in_time);
			$end   = strtotime($out_time);
	
			if ($start && $end && $end >= $start) {
			$diff_seconds = $end - $start;          // difference in seconds
			$total_hours = gmdate('H:i:s', $diff_seconds); // convert to HH:MM:SS
			} else {
			$total_hours = '00:00:00';
			}
		}
	

        $data = [
            'shift_id'        => (int)$this->input->post('shift_id'),
            'entry_date'      => $this->input->post('entry_date'),
            'in_time'         => $in_time ?: null,
            'out_time'        => $out_time ?: null,
            'first_half'      => $this->input->post('first_half') ?: 'Absent',
            'second_half'     => $this->input->post('second_half') ?: 'Absent',
            'position'         => $this->input->post('position') ?: 'None',
            'total_hours'     => $total_hours ?: '0.00',
            'late_mark'       => $this->input->post('late_mark') ? 1 : 0,
            'remarks'         => $this->input->post('remarks'),
        ];
		
		// Add staffid and company_id only if new record (no attendance_id)
        if (!$attendance_id) {
        $data['staffid']    = ($this->input->post('staffid')) ? (int)$this->input->post('staffid') : get_staff_user_id();
        $data['company_id'] = get_staff_company_id();
        }
		
		
		

        if ($attendance_id) {
		   ///log_message('error', 'API call failed: ' . json_encode($data));
		//set_alert('success', 'Attendance updated successfully');
        //exit;
            $this->db->where('attendance_id', $attendance_id);
            $this->db->update('it_crm_hrd_attendance', $data);
            set_alert('success', 'Attendance updated successfully');
            exit;
        } else {
            $this->db->insert('it_crm_hrd_attendance', $data);
            set_alert('success', 'Attendance added successfully');
            exit;
        }
        redirect(admin_url('hrd/attendance'));
    }

    // Bulk update attendance status (0=open,1=fixed)
    public function bulk_update_attendance_status()
    {
        if (!staff_can('view_own',  'hr_department')) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $ids = $this->input->post('ids');
        $status = (int)$this->input->post('status');

        if (!is_array($ids) || empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'No items selected']);
            return;
        }

        // Scope by company for safety
        if (is_super()) {
            $company_id = isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id'] ? $_SESSION['super_view_company_id'] : get_staff_company_id();
        } else {
            $company_id = get_staff_company_id();
        }

        $this->db->where_in('attendance_id', $ids);
        $this->db->where('company_id', $company_id);
        $this->db->update('it_crm_hrd_attendance', ['status' => $status]);

        echo json_encode(['success' => true]);
    }

    /* View Interviews */
    public function interviews()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interviews');
        }

        // Company scope
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }

        // Filters (multi-field)
        $full_name     = trim((string)$this->input->get('full_name'));
        $phone         = trim((string)$this->input->get('phone_number'));
        $email         = trim((string)$this->input->get('email_id'));
        $qualification = trim((string)$this->input->get('qualification'));
        $designation   = trim((string)$this->input->get('designation'));
        $experience    = trim((string)$this->input->get('total_experience'));
        $salary        = trim((string)$this->input->get('current_salary'));
        $notice_from   = trim((string)$this->input->get('notice_from'));
        $notice_to     = trim((string)$this->input->get('notice_to'));
        $location      = trim((string)$this->input->get('location'));
        $city          = trim((string)$this->input->get('city'));
        $process_status        = trim((string)$this->input->get('process_status'));
        $added_from    = trim((string)$this->input->get('added_from'));
        $added_to      = trim((string)$this->input->get('added_to'));

        if ($full_name !== '') { $this->db->like('full_name', $full_name); }
        if ($phone !== '') { $this->db->like('phone_number', $phone); }
        if ($email !== '') { $this->db->like('email_id', $email); }
        if ($qualification !== '') { $this->db->like('qualification', $qualification); }
        if ($designation !== '') { $this->db->like('designation', $designation); }
        if ($experience !== '') { $this->db->like('total_experience', $experience); }
        if ($salary !== '') { $this->db->like('current_salary', $salary); }
        if ($notice_from !== '') { $this->db->where('notice_period_in_days >=', (int)$notice_from); }
        if ($notice_to !== '') { $this->db->where('notice_period_in_days <=', (int)$notice_to); }
        if ($location !== '') { $this->db->like('location', $location); }
        if ($city !== '') { $this->db->like('city', $city); }
        if ($process_status !== '') { $this->db->where('process_status', (int)$process_status); }
        if ($added_from !== '') { $this->db->where('addedon >=', $added_from . ' 00:00:00'); }
        if ($added_to !== '') { $this->db->where('addedon <=', $added_to . ' 23:59:59'); }

        $data['interviews'] = $this->hrd_model->get_interviews();
        $data['filters'] = [
            'full_name' => $full_name,
            'phone_number' => $phone,
            'email_id' => $email,
            'qualification' => $qualification,
            'designation' => $designation,
            'total_experience' => $experience,
            'current_salary' => $salary,
            'notice_from' => $notice_from,
            'notice_to' => $notice_to,
            'location' => $location,
            'city' => $city,
            'process_status' => $process_status,
            'added_from' => $added_from,
            'added_to' => $added_to,
        ];

        // Load interview sources for dropdown
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('status', 1);
        $data['interview_sources'] = $this->hrd_model->get_interview_source();

        // Load interview processes for dropdown
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            } else {
                $this->db->where('company_id', get_staff_company_id());
            }
        } else {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->where('status', 1);
        $data['interview_processes'] = $this->hrd_model->get_interview_process();

        $data['title'] = 'Interviews';
        $this->load->view('admin/hrd/interviews', $data);
    }

    // Add/Edit Interview
    public function interviewentry()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Interviews');
        }

        $id = $this->input->post('id');

        $data = [
            'company_id'            => get_staff_company_id(),
            'full_name'             => $this->input->post('full_name'),
            'phone_number'          => $this->input->post('phone_number'),
            'email_id'              => $this->input->post('email_id'),
            'qualification'         => $this->input->post('qualification'),
            'designation'           => $this->input->post('designation'),
            'total_experience'      => $this->input->post('total_experience'),
            'current_salary'        => $this->input->post('current_salary'),
            'notice_period_in_days' => ($this->input->post('notice_period_in_days')!=='') ? (int)$this->input->post('notice_period_in_days') : null,
            'location'              => $this->input->post('location'),
            'city'                  => $this->input->post('city'),
            'source'                => ($this->input->post('source')!=='') ? (int)$this->input->post('source') : null,
            'process_status'        => ($this->input->post('process_status')!=='') ? (int)$this->input->post('process_status') : null,
            'comments'              => $this->input->post('comments'),
            'addedby'               => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_interviews_master', $data);
            set_alert('success', 'Interview updated successfully');
            exit;
        } else {
            $this->db->insert('it_crm_hrd_interviews_master', $data);
            set_alert('success', 'Interview added successfully');
            exit;
        }
        redirect(admin_url('hrd/interviews'));
    }
    // Add/Edit Holiday List
    public function holidaylist()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Holiday List');
        }

        $id = $this->input->post('id');
        $data = [
            'holiday_title' => $this->input->post('holiday_title'),
            'holiday_remark' => $this->input->post('holiday_remark'),
            'holiday_date' => $this->input->post('holiday_date'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_holiday_list', $data);
            set_alert('success', 'Holiday updated successfully');exit;
        } else {
            // Default new holidays to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_holiday_list', $data);
            set_alert('success', 'Holiday added successfully');exit;
        }
        redirect(admin_url('hrd/setting/holiday_list'));
    }

    // Add/Edit Today's Thought
    public function todaysthought()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied("Today's Thought");
        }

        $id = $this->input->post('id');
        $data = [
            'details' => $this->input->post('details'),
            'company_id' => get_staff_company_id(),
            'addedby' => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_todays_thought', $data);
            set_alert('success', "Today's thought updated successfully");exit;
			exit;
        } else {
            // Default new thought to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_todays_thought', $data);
            set_alert('success', "Today's thought added successfully");exit;
			exit;
        }
        redirect(admin_url('hrd/setting/todays_thought'));
    }

    // Add/Edit Attendance Status
    public function attendancestatus()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Attendance Status');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('name'),
            'remark' => $this->input->post('remark'),
			'color' => $this->input->post('color'),
            'company_id' => get_staff_company_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_attendance_status', $data);
            set_alert('success', 'Attendance status updated successfully');exit;
        } else {
            // Default new attendance status to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_attendance_status', $data);
            set_alert('success', 'Attendance status added successfully');exit;
        }
        redirect(admin_url('hrd/setting/attendance_status'));
    }

    // Delete Attendance Status (soft)
    public function delete_attendance_status($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Attendance Status');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_attendance_status', ['status' => 0]);
        set_alert('success', 'Attendance status deactivated successfully');
        redirect(admin_url('hrd/setting/attendance_status'));
    }

    // Toggle Attendance Status (AJAX)
    public function toggle_attendance_status($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_attendance_status', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Add/Edit Leave Rule
    public function leaverule()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Rule');
        }

        $id = $this->input->post('id');
        $data = [
            'title' => $this->input->post('title'),
            'details' => $this->input->post('details'),
            'company_id' => get_staff_company_id(),
            'addedby' => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_leave_rule', $data);
            set_alert('success', 'Leave rule updated successfully');exit;
        } else {
            // Default new rule to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_leave_rule', $data);
            set_alert('success', 'Leave rule added successfully');exit;
        }
        redirect(admin_url('hrd/setting/leave_rule'));
    }

    // Add/Edit Corporate Guidelines
    public function corporateguidelines()
    {
	
	//log_message('error', 'hhhhhhhhhhhhhhhh');
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Corporate Guidelines');
        }

        $id = $this->input->post('id');
        $data = [
            'branch' => $this->input->post('branch'),
            'title' => $this->input->post('title'),
            'details' => $this->input->post('details'),
            'company_id' => get_staff_company_id(),
            'addedby' => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_corporate_guidelines', $data);
            set_alert('success', 'Corporate guideline updated successfully');exit;
        } else {
            // Default new guidelines to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_corporate_guidelines', $data);
            set_alert('success', 'Corporate guideline added successfully');exit;
        }
        redirect(admin_url('hrd/setting/corporate_guidelines'));
    }

    // Add/Edit Company Policies
    public function companypolicies()
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Company Policies');
        }

        $id = $this->input->post('id');
        $data = [
            'branch' => $this->input->post('branch'),
            'title' => $this->input->post('title'),
            'details' => $this->input->post('details'),
            'company_id' => get_staff_company_id(),
            'addedby' => get_staff_user_id(),
        ];

        if ($id) {
            $this->db->where('id', $id);
            $this->db->update('it_crm_hrd_company_policies', $data);
            $policy_id = $id;
            set_alert('success', 'Company policy updated successfully');
        } else {
            // Default new policies to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_company_policies', $data);
            $policy_id = $this->db->insert_id();
            set_alert('success', 'Company policy added successfully');
        }

        // Handle file uploads
        if (!empty($_FILES['attachments']['name'][0])) {
            $this->handle_policy_attachments($policy_id);
        }

        exit;
    }

    // Handle policy attachments upload
    private function handle_policy_attachments($policy_id)
    {
        $upload_path = FCPATH . 'uploads/company_policies/';
        
        // Create directory if it doesn't exist
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $files = $_FILES['attachments'];
        $file_count = count($files['name']);

        for ($i = 0; $i < $file_count; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $file_name = $files['name'][$i];
                $file_tmp = $files['tmp_name'][$i];
                $file_size = $files['size'][$i];
                $file_type = $files['type'][$i];
                
                // Generate unique filename
                $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
                $unique_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $file_path = $upload_path . $unique_filename;
                
                // Move uploaded file
                if (move_uploaded_file($file_tmp, $file_path)) {
                    $attachment_data = [
                        'policy_id' => $policy_id,
                        'company_id' => get_staff_company_id(),
                        'file_name' => $unique_filename,
                        'original_name' => $file_name,
                        'file_path' => 'uploads/company_policies/' . $unique_filename,
                        'file_size' => $file_size,
                        'file_type' => $file_type,
                        'uploaded_by' => get_staff_user_id(),
                        'status' => 1
                    ];
                    
                    $this->hrd_model->add_company_policy_attachment($attachment_data);
                }
            }
        }
    }

    // Delete policy attachment
    public function delete_policy_attachment($attachment_id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Company Policies');
        }

        if ($this->hrd_model->delete_company_policy_attachment($attachment_id)) {
            set_alert('success', 'Attachment deleted successfully');
        } else {
            set_alert('danger', 'Failed to delete attachment');
        }
        
        redirect(admin_url('hrd/setting/company_policies'));
    }

    // Delete Company Policy (soft)
    public function delete_company_policies($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Company Policies');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_company_policies', ['status' => 0]);
        set_alert('success', 'Company policy deactivated successfully');
        redirect(admin_url('hrd/setting/company_policies'));
    }

    // Toggle Company Policy Status (AJAX)
    public function toggle_company_policies($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_company_policies', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Delete Corporate Guidelines (soft)
    public function delete_corporate_guidelines($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Corporate Guidelines');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_corporate_guidelines', ['status' => 0]);
        set_alert('success', 'Corporate guideline deactivated successfully');
        redirect(admin_url('hrd/setting/corporate_guidelines'));
    }

    // Toggle Corporate Guidelines Status (AJAX)
    public function toggle_corporate_guidelines($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_corporate_guidelines', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Delete Leave Rule (soft)
    public function delete_leave_rule($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Leave Rule');
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_leave_rule', ['status' => 0]);
        set_alert('success', 'Leave rule deactivated successfully');
        redirect(admin_url('hrd/setting/leave_rule'));
    }

    // Toggle Leave Rule Status (AJAX)
    public function toggle_leave_rule($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_leave_rule', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Delete Today's Thought (soft)
    public function delete_todays_thought($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied("Today's Thought");
        }
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_todays_thought', ['status' => 0]);
        set_alert('success', "Today's thought deactivated successfully");
        redirect(admin_url('hrd/setting/todays_thought'));
    }

    // Toggle Today's Thought Status (AJAX)
    public function toggle_todays_thought($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_todays_thought', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }

    // Delete Holiday List
    public function delete_holiday_list($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            access_denied('Holiday List');
        }
        // Soft delete: set status to 0 (Deactive) instead of removing the record
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_holiday_list', ['status' => 0]);
        set_alert('success', 'Holiday deactivated successfully');
        redirect(admin_url('hrd/setting/holiday_list'));
    }

    // Toggle Holiday List Status (AJAX)
    public function toggle_holiday_list($id)
    {
        if (!staff_can('view_setting',  'hr_department')) {
            echo json_encode(['success' => false]);
            return;
        }
        $new_status = $this->input->post('status') == 1 ? 1 : 0;
        $this->db->where('id', $id);
        $this->db->update('it_crm_hrd_holiday_list', ['status' => $new_status]);
        echo json_encode(['success' => true, 'new_status' => $new_status]);
    }
	
	
	public function add_attendance() {
        
        $mode = $this->input->get('mode');
        if (!$mode) {
            echo json_encode(['success' => false]);
            return;
        }
        $this->load->model('hrd_model');
        $data = $this->hrd_model->addattendance($mode);
        echo json_encode(['success' => $data ? true : false, 'data' => $data]);
    }
}
