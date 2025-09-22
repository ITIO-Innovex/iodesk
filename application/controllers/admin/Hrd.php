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
            set_alert('success', 'Company policy updated successfully');exit;
        } else {
            // Default new policies to Active (status=1)
            $data['status'] = 1;
            $this->db->insert('it_crm_hrd_company_policies', $data);
            set_alert('success', 'Company policy added successfully');exit;
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
}
