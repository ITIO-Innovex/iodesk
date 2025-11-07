<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Hrd_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get interview status
     * @param  mixed $id Optional - status id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_interview_status($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_interview_status')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_interview_status')->result_array();
    }

    /**
     * Add new interview status
     * @param array $data interview status data
     */
    public function add_interview_status($data)
    {
        $this->db->insert(db_prefix() . 'hrd_interview_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Interview Status Added [StatusID: ' . $insert_id . ', Title: ' . $data['title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update interview status
     * @param  array $data interview status data
     * @param  mixed $id   status id
     * @return boolean
     */
    public function update_interview_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_interview_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Interview Status Updated [StatusID: ' . $id . ', Title: ' . $data['title'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete interview status from database
     * @param  mixed $id status id
     * @return boolean
     */
    public function delete_interview_status($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'hrd_interview_status');
        if ($this->db->affected_rows() > 0) {
            log_activity('Interview Status Deleted [StatusID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Get interview process
     * @param  mixed $id Optional - process id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_interview_process($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_interview_process')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_interview_process')->result_array();
    }

    /**
     * Add new interview process
     * @param array $data interview process data
     */
    public function add_interview_process($data)
    {
        $this->db->insert(db_prefix() . 'hrd_interview_process', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Interview Process Added [ProcessID: ' . $insert_id . ', Title: ' . $data['title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update interview process
     * @param  array $data interview process data
     * @param  mixed $id   process id
     * @return boolean
     */
    public function update_interview_process($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_interview_process', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Interview Process Updated [ProcessID: ' . $id . ', Title: ' . $data['title'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete interview process from database
     * @param  mixed $id process id
     * @return boolean
     */
    public function delete_interview_process($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'hrd_interview_process');
        if ($this->db->affected_rows() > 0) {
            log_activity('Interview Process Deleted [ProcessID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Get leave type
     * @param  mixed $id Optional - leave type id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_leave_type($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_leave_type')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_leave_type')->result_array();
    }

    /**
     * Get saturday rule(s)
     * @param  mixed $id Optional - saturday rule id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_saturday_rule($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_saturday_rule')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_saturday_rule')->result_array();
    }

    /**
     * Add new leave type
     * @param array $data leave type data
     */
    public function add_leave_type($data)
    {
        $this->db->insert(db_prefix() . 'hrd_leave_type', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Leave Type Added [LeaveTypeID: ' . $insert_id . ', Title: ' . $data['title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update leave type
     * @param  array $data leave type data
     * @param  mixed $id   leave type id
     * @return boolean
     */
    public function update_leave_type($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_leave_type', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Leave Type Updated [LeaveTypeID: ' . $id . ', Title: ' . $data['title'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete leave type from database
     * @param  mixed $id leave type id
     * @return boolean
     */
    public function delete_leave_type($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'hrd_leave_type');
        if ($this->db->affected_rows() > 0) {
            log_activity('Leave Type Deleted [LeaveTypeID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Get employee type
     * @param  mixed $id Optional - employee type id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_employee_type($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_employee_type')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_employee_type')->result_array();
    }

    /**
     * Add new employee type
     * @param array $data employee type data
     */
    public function add_employee_type($data)
    {
        $this->db->insert(db_prefix() . 'hrd_employee_type', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Employee Type Added [EmployeeTypeID: ' . $insert_id . ', Title: ' . $data['title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update employee type
     * @param  array $data employee type data
     * @param  mixed $id   employee type id
     * @return boolean
     */
    public function update_employee_type($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_employee_type', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Employee Type Updated [EmployeeTypeID: ' . $id . ', Title: ' . $data['title'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete employee type from database
     * @param  mixed $id employee type id
     * @return boolean
     */
    public function delete_employee_type($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'hrd_employee_type');
        if ($this->db->affected_rows() > 0) {
            log_activity('Employee Type Deleted [EmployeeTypeID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Get branch manager
     * @param  mixed $id Optional - branch manager id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_branch_manager($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_branch_manager')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_branch_manager')->result_array();
    }

    /**
     * Add new branch manager
     * @param array $data branch manager data
     */
    public function add_branch_manager($data)
    {
        $this->db->insert(db_prefix() . 'hrd_branch_manager', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Branch Manager Added [BranchManagerID: ' . $insert_id . ', Branch Name: ' . $data['branch_name'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update branch manager
     * @param  array $data branch manager data
     * @param  mixed $id   branch manager id
     * @return boolean
     */
    public function update_branch_manager($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_branch_manager', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Branch Manager Updated [BranchManagerID: ' . $id . ', Branch Name: ' . $data['branch_name'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete branch manager from database
     * @param  mixed $id branch manager id
     * @return boolean
     */
    public function delete_branch_manager($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'hrd_branch_manager');
        if ($this->db->affected_rows() > 0) {
            log_activity('Branch Manager Deleted [BranchManagerID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Get holiday list
     * @param  mixed $id Optional - holiday list id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_holiday_list($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_holiday_list')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_holiday_list')->result_array();
    }

    /**
     * Add new holiday list
     * @param array $data holiday list data
     */
    public function add_holiday_list($data)
    {
        $this->db->insert(db_prefix() . 'hrd_holiday_list', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Holiday Added [HolidayID: ' . $insert_id . ', Title: ' . $data['holiday_title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update holiday list
     * @param  array $data holiday list data
     * @param  mixed $id   holiday list id
     * @return boolean
     */
    public function update_holiday_list($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_holiday_list', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Holiday Updated [HolidayID: ' . $id . ', Title: ' . $data['holiday_title'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Delete holiday list from database
     * @param  mixed $id holiday list id
     * @return boolean
     */
    public function delete_holiday_list($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'hrd_holiday_list');
        if ($this->db->affected_rows() > 0) {
            log_activity('Holiday Deleted [HolidayID: ' . $id . ']');
            return true;
        }
        return false;
    }
    /**
     * Get today's thought
     * @param  mixed $id Optional - thought id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_todays_thought($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_todays_thought')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_todays_thought')->result_array();
    }

    /**
     * Get leave rule(s)
     * @param  mixed $id Optional - rule id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_leave_rule($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_leave_rule')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_leave_rule')->result_array();
    }

    /**
     * Get shift type(s)
     * @param  mixed $id Optional - shift type id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_shift_type($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_shift_type')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_shift_type')->result_array();
    }

    /**
     * Get staff type(s)
     * @param  mixed $id Optional - staff type id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_staff_type($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_staff_type')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_staff_type')->result_array();
    }

    /**
     * Get corporate guideline(s)
     * @param  mixed $id Optional - guideline id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_corporate_guidelines($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_corporate_guidelines')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_corporate_guidelines')->result_array();
    }

    /**
     * Get company policy(ies)
     * @param  mixed $id Optional - policy id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_company_policies($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_company_policies')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_company_policies')->result_array();
    }

    /**
     * Get events & announcements
     * @param  mixed $id Optional - record id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_events_announcements($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_events_announcements')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_events_announcements')->result_array();
    }

    /**
     * Get attendance status
     * @param  mixed $id Optional - status id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_attendance_status($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_attendance_status')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_attendance_status')->result_array();
    }

    /**
     * Get shift manager records
     * @param mixed $id Optional - shift_id
     * @param array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_shift_manager($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('shift_id', $id);
            return $this->db->get(db_prefix() . 'hrd_shift_manager')->row();
        }

        $this->db->where($where);
        $this->db->order_by('shift_id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_shift_manager')->result_array();
    }
	
	public function get_shift_details($id = '', $where = [])
    {
            $this->db->select('s.*, b.*');
			$this->db->from(db_prefix() . 'hrd_shift_manager s');
			$this->db->join(db_prefix() . 'hrd_branch_manager b', 's.shift_id = b.shift');
			$this->db->where('b.shift', get_branch_id());
			$query = $this->db->get();
			return $query->result_array();
        
    }

    /**
     * Get leave application(s)
     * @param mixed $id Optional - leave_id
     * @param array $where Optional filters
     * @return mixed object if id passed else array
     */
    public function get_leave_application($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('leave_id', $id);
            return $this->db->get(db_prefix() . 'hrd_leave_master')->row();
        }

        $this->db->where($where);
        $this->db->order_by('leave_id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_leave_master')->result_array();
    }

    /**
     * Get attendance records
     * @param mixed $id Optional - attendance_id
     * @param array $where Optional filters
     * @return mixed object if id passed else array
     */
    public function get_attendance($id = '', $staffid='', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('attendance_id', $id);
            return $this->db->get(db_prefix() . 'hrd_attendance')->row();
			//echo $this->db->last_query();exit;
        }
		
		if (is_numeric($staffid)) {
            $this->db->where($where);
            $this->db->where('staffid', $staffid);
            return $this->db->get(db_prefix() . 'hrd_attendance')->result_array();
			//echo $this->db->last_query();exit;
        }

        $this->db->where($where);
        $this->db->order_by('attendance_id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_attendance')->result_array();
    }

    /**
     * Get interviews records
     * @param mixed $id Optional - id
     * @param array $where Optional filters
     * @return mixed object if id passed else array
     */
    public function get_interviews($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_interviews_master')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_interviews_master')->result_array();
    }

    /**
     * Get interview source
     * @param  mixed $id Optional - source id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_interview_source($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->db->where($where);
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'hrd_interview_source')->row();
        }

        $this->db->where($where);
        $this->db->order_by('id', 'asc');
        return $this->db->get(db_prefix() . 'hrd_interview_source')->result_array();
    }

    /**
     * Get company policy attachments
     * @param  mixed $policy_id Optional - policy id
     * @param  array $where Optional - where conditions
     * @return mixed object if id passed else array
     */
    public function get_company_policy_attachments($policy_id = '', $where = [])
    {
        if (is_numeric($policy_id)) {
            $this->db->where($where);
            $this->db->where('policy_id', $policy_id);
            $this->db->where('status', 1);
            return $this->db->get(db_prefix() . 'hrd_company_policy_attachments')->result_array();
        }

        $this->db->where($where);
        $this->db->where('status', 1);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'hrd_company_policy_attachments')->result_array();
    }

    /**
     * Add company policy attachment
     * @param array $data attachment data
     */
    public function add_company_policy_attachment($data)
    {
        $this->db->insert(db_prefix() . 'hrd_company_policy_attachments', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Company Policy Attachment Added [AttachmentID: ' . $insert_id . ', File: ' . $data['original_name'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Delete company policy attachment
     * @param  mixed $id attachment id
     * @return boolean
     */
    public function delete_company_policy_attachment($id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'hrd_company_policy_attachments', ['status' => 0]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Company Policy Attachment Deleted [AttachmentID: ' . $id . ']');
            return true;
        }
        return false;
    }
	
	public function addattendance($data)
    {
        
        $ip = $_SERVER['REMOTE_ADDR'];
		$staffid = get_staff_user_id();
		$company_id = get_staff_company_id();
		$mode = $data;
		
		/*$late_time = "09:30:00"; // Allowed time
		$in = new DateTime(date("H:i:s"));
		$limit = new DateTime($late_time);
		$late_mark=0;
		if ($in > $limit) { $late_mark=1;}*/
		
$in_time = new DateTime(date("H:i:s")); // or from DB
$start_time = new DateTime('09:30:00');
$end_time   = new DateTime('13:00:00');

if ($in_time >= $start_time && $in_time <= $end_time) {
    $diff = $start_time->diff($in_time);
    $difference = $diff->format('%H:%I:%S');
} else {
    $difference = "00:00:00";
}
		
		
		
		if(isset($mode)&&$mode=='In'){
		
        $insert = [
            'staffid'        	=> $staffid,
            'company_id'       	=> $company_id,
            'shift_id'     		=> isset($data['status']) ? (int)$data['status'] : 1,
			'mode'     			=> $mode,
			'ip'     			=> $ip,
			'first_half'     	=> 4,
			'position'     		=> 1,
            'in_time' 			=> date("H:i:s"),
			'entry_date' 		=> date("Y-m-d"),
			'late_mark' 		=> $difference,
        ];

        $this->db->insert(db_prefix() . 'hrd_attendance', $insert);
        $insert_id = $this->db->insert_id();
		}else{
		
		$attendance = $this->hrd_model->get_todays_attendance();
		$attendance_id  = $attendance[0]['attendance_id'];
		$in_time  = $attendance[0]['in_time'];
		
		$time1 = new DateTime($in_time);
        $time2 = new DateTime(date("H:i:s"));
        $interval = $time1->diff($time2);
		// Format interval as H:i:s
        $totalHours = $interval->format('%H:%I:%S');

//echo $interval->format('%H hours %I minutes %S seconds');
		
		$update = [
			'out_time' 			=> date("H:i:s"),
			'total_hours' 			=> $totalHours,
        ];
		
		
		
		
		$this->db->where('attendance_id', $attendance_id);
		$this->db->update(db_prefix() . 'hrd_attendance', $update);
		
		$insert_id=$attendance_id;
		
		}
		
		if(isset($insert_id)){
		
		 $logs = [
		    'attendance_id' 	=> $insert_id,
			'punch_type'  		=> $mode,
			'ip'     			=> $ip,
			'punch_time' 		=> date("H:i:s"),
        ];
		$this->db->insert(db_prefix() . 'hrd_attendance_logs', $logs);
		return $insert_id = $this->db->insert_id();
		}
         //return $this->db->get()->result_array();
    }
	
	
	 public function get_todays_attendance()
    {
      
	    $staffid = get_staff_user_id();
		$company_id = get_staff_company_id();
		$this->db->select('in_time,out_time,attendance_id');
        $this->db->where('entry_date', date('Y-m-d'));
		$this->db->where('staffid', $staffid);
		$this->db->where('company_id', $company_id);
        return $this->db->get(db_prefix() . 'hrd_attendance')->result_array();
    }
	
	public function get_attendance_stats(){
			$staffid = get_staff_user_id();
			
			$month = date("m");
		$year  = date("Y");
		
		$sql = "
		  SUM(CASE WHEN (first_half = 1 || first_half = 2 || first_half = 3 || first_half = 7) THEN 1 ELSE 0 END) AS fullday,
		  SUM(CASE WHEN (second_half = 8 || second_half = 4)  THEN 1 ELSE 0 END) AS half,
		  SUM(CASE WHEN (first_half = 8 || first_half = 4) THEN 1 ELSE 0 END) AS absent
		";
		
		$this->db->select($sql, false); // false -> do NOT escape the expression
		$this->db->where('staffid', $staffid);
		$this->db->where("MONTH(entry_date) = {$month}", null, false);
		$this->db->where("YEAR(entry_date) = {$year}",  null, false);
		$this->db->from(db_prefix() . 'hrd_attendance');
		//echo $compiled = $this->db->get_compiled_select();exit;
		//log_message('debug', 'Attendance SQL: ' . $compiled);
		$result = $this->db->get()->row_array(); // single-row aggregate
		return $result;
	}
	
	  public function get_status_counter($month_year="")
    { 
	
	    if($month_year==""){
		$month_year=date("Y-m");
		}
      
	    $staffid = get_staff_user_id();
		$company_id = get_staff_company_id();
		$this->db->select('first_half, second_half, COUNT(*) AS total_count');
		$this->db->where('staffid', get_staff_user_id());
		$this->db->like('entry_date', $month_year, 'after'); // matches e.g. 2025-11-01, 2025-11-15
		$this->db->group_by(['first_half', 'second_half']);
        return $this->db->get(db_prefix() . 'hrd_attendance')->result_array();
    }
	
}
