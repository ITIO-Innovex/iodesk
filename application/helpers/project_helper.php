<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Get project by ID (for admin project page)
 * @param  mixed $id project id
 * @return object
 */
 
function AdminProjectTableStructure($name = '', $bulk_action = false)
{
    $table = '<table class="table customizable-table number-index-' . ($bulk_action ? '2' : '1') . ' dt-table-loading ' . ($name == '' ? 'tickets-table' : $name) . ' table-tickets" id="tickets" data-last-order-identifier="tickets" data-default-order="' . get_table_last_order('tickets') . '">';
    $table .= '<thead>';
    $table .= '<tr>';

    $table .= '<th class="' . ($bulk_action == true ? '' : 'not_visible') . '">';
    $table .= '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="tickets"><label></label></div>';
    $table .= '</th>';

    $table .= '<th class="toggleable" id="th-number">' . _l('TicketNo') . '</th>';
    $table .= '<th class="toggleable" id="th-subject">' . _l('ticket_dt_subject') . '</th>';
    $table .= '<th class="toggleable" id="th-tags">' . _l('tags') . '</th>';
    $table .= '<th class="toggleable" id="th-department">' . _l('ticket_dt_department') . '</th>';
    $services_th_attrs = '';
    if (get_option('services') == 0) {
        $services_th_attrs = ' class="not_visible"';
    }
    $table .= '<th' . $services_th_attrs . '>' . _l('ticket_dt_service') . '</th>';
    $table .= '<th class="toggleable" id="th-submitter">' . _l('ticket_dt_submitter') . '</th>';
    $table .= '<th class="toggleable" id="th-status">' . _l('ticket_dt_status') . '</th>';
    $table .= '<th class="toggleable" id="th-priority">' . _l('ticket_dt_priority') . '</th>';
    $table .= '<th class="toggleable" id="th-last-reply">' . _l('ticket_dt_last_reply') . '</th>';
    $table .= '<th class="toggleable ticket_created_column" id="th-created">' . _l('ticket_date_created') . '</th>';

    $custom_fields = get_table_custom_fields('tickets');

    foreach ($custom_fields as $field) {
        $table .= '<th>' . $field['name'] . '</th>';
    }

    $table .= '</tr>';
    $table .= '</thead>';
    $table .= '<tbody></tbody>';
    $table .= '</table>';

    $table .= '<script id="hidden-columns-table-tickets" type="text/json">';
    $table .= get_staff_meta(get_staff_user_id(), 'hidden-columns-table-tickets');
    $table .= '</script>';

    return $table;
}

function proj_status_translate($id = null)
{
    if ($id == '' || is_null($id)) {
        return '';
    }


        $CI = & get_instance();
		$CI->db->select('name,color');
        $CI->db->where('id', $id);
        $priority = $CI->db->get(db_prefix() . 'project_status')->row();

        return !$priority ? '' : $priority;
   

}

function get_project_status_title($id)
{   
        // Fetch company Name from company id
		if(isset($id)&&$id){
		$CI = & get_instance();
		$CI->db->where('id', $id);
		$com = $CI->db->select('name')->from(db_prefix() . 'project_status')->get()->row();
		if(isset($com)&&$com->name){
		return $com->name;
		}
		}
		
		return null;
}

function get_project_group($sid)
{   
        // Fetch company Name from company id
		if(isset($sid)&&$sid){
		$CI = & get_instance();
		$CI->db->where('id', $sid);
		$com = $CI->db->select('name')->from(db_prefix() . 'project_group')->get()->row();
		if(isset($com)&&$com->name){
		return $com->name;
		}
		}
		
		return null;
}

function get_project_access($sid)
{   
        $result="Private";
        if($sid==2){
		$result="Public";
		}
		
		return $result;
}

function get_project_priority($sid)
{   
        // Fetch company Name from company id
		if(isset($sid)&&$sid){
		$CI = & get_instance();
		$CI->db->where('priorityid ', $sid);
		$com = $CI->db->select('name')->from(db_prefix() . 'project_priority')->get()->row();
		if(isset($com)&&$com->name){
		return $com->name;
		}
		}
		
		return null;
}

function get_proj_priority_color($sid)
{   
        // Fetch company Name from company id
		if(isset($sid)&&$sid){
		$CI = & get_instance();
		$CI->db->where('priorityid ', $sid);
		$com = $CI->db->select('color')->from(db_prefix() . 'project_priority')->get()->row();
		if(isset($com)&&$com->color){
		return $com->color;
		}
		}
		
		return null;
}

function get_proj_statush($id)
{   
        // Fetch company Name from company id
		if(isset($id)&&$id){
		$CI = & get_instance();
		$CI->db->where('id', $id);
		$com = $CI->db->select('name')->from(db_prefix() . 'project_status')->get()->row();
		if(isset($com)&&$com->name){
		return $com->name;
		}
		}
		
		return null;
}

function get_project_title($pid)
{   
        // Fetch company Name from company id
		if(isset($pid)&&$pid){
		$CI = & get_instance();
		$CI->db->where('id', $pid);
		$com = $CI->db->select('project_title')->from(db_prefix() . 'project_master')->get()->row();
		if(isset($com)&&$com->project_title){
		return ucfirst($com->project_title);
		}
		}
		
		return null;
}

function get_task_name($tid)
{   
        // Fetch company Name from company id
		if(isset($tid)&&$tid){
		$CI = & get_instance();
		$CI->db->where('id', $tid);
		$com = $CI->db->select('task_name')->from(db_prefix() . 'project_task')->get()->row();
		if(isset($com)&&$com->task_name){
		return ucfirst($com->task_name);
		}
		}
		
		return null;
}

function get_task_owner($tid)
{   
        // Fetch company Name from company id
		if(isset($tid)&&$tid){
		$CI = & get_instance();
		$CI->db->where('id', $tid);
		$com = $CI->db->select('task_owner')->from(db_prefix() . 'project_task')->get()->row();
		if(isset($com)&&$com->task_owner){
		return ucfirst($com->task_owner);
		}
		}
		
		return null;
}

	function get_cc_mail_list($mail_id)
    {
					
			
			// Convert to array
			$idArray = explode(',', $mail_id);
			
			// Loop and get emails
			$emails = [];
			foreach ($idArray as $id) {
			    if($id<>get_staff_user_id()){
				$email = get_staff_email((int)trim($id));
				if (!empty($email)) {
					$emails[] = $email;
				}
				}
			}
			return $emails;

    }

function getDateDifference($end_date, $status) {
    // Convert to DateTime objects
    $today = new DateTime(); // current date
    $endDate = new DateTime($end_date);

    // Calculate difference
    $diff = $today->diff($endDate);

    // If end date is in the past, make days negative
    $days = (int)$diff->format('%a');
    if ($endDate < $today) {
       // $days = -$days;
    }else{
	 return null;
	}
	
	if($status <> 7 && $status <> 10){
	return '<span class="text-danger" title="Project Delay">('.$days.' Days)</span>';
	}

    return null;
}
function getDaysBetweenDates($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    return $start->diff($end)->days;
}

function get_task_percentage($tid)
{   
        // Fetch company Name from company id
		if(isset($tid)&&$tid){
		$CI = & get_instance();
		$CI->db->where('id', $tid);
		$com = $CI->db->select('task_status,task_progress')->from(db_prefix() . 'project_task')->get()->row();
		if(isset($com)&&$com->task_status==10){
		return 100;
		}else{
		return $com->task_progress;
		}
		}
		
		return 0;
}

function get_project_percentage($pid)
{   
        // Fetch company Name from company id
		if(isset($pid)&&$pid){
		$CI = & get_instance();
		$CI->db->where('id', $pid);
		$com = $CI->db->select('project_status')->from(db_prefix() . 'project_master')->get()->row();
		
		if(isset($com)&&$com->project_status==10){
		return 100;
		}else{
		$CI->db->where('project_id', $pid);
		$CI->db->where('task_status', 10);
		$rs = $CI->db->select('count(id) as cnt1')->from(db_prefix() . 'project_task')->get()->row();
		//echo $CI->db->last_query();exit;
		//print_r($rs);
		$CI->db->where('project_id', $pid);
		$CI->db->not_like('task_status', 10);
		$rss = $CI->db->select('count(id) as cnt2, sum(task_progress) as sum')->from(db_prefix() . 'project_task')->get()->row();
		//print_r($rss);
		//echo $CI->db->last_query();exit;
		//print_r($rss);exit;
		$final_percentage= ((($rs->cnt1 * 100) + (($rss->cnt2 * 100) + $rss->sum) / 10));
		
		return $final_percentage;
		}
		}
		
		return 0;
}

function getProjectCompletion($projectId) {
    $CI =& get_instance();
    
    // Fetch all tasks for project
    $CI->db->select('task_status, task_progress');
    $CI->db->from(db_prefix() . 'project_task');
    $CI->db->where('project_id', $projectId);
    $query = $CI->db->get();
    $tasks = $query->result_array();

    $totalProgress = 0;
    $taskCount = 0;

    foreach ($tasks as $row) {
        $progress = ($row['task_status'] == 10) ? 100 : (int)$row['task_progress'];
        $totalProgress += $progress;
        $taskCount++;
    }

    if ($taskCount == 0) {
        return 0; // No tasks - 0%
    }

    return round($totalProgress / $taskCount, 2);
}

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Shared internet
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Behind a proxy
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Default remote address
        return $_SERVER['REMOTE_ADDR'];
    }
}
