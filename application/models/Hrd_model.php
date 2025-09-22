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
}
