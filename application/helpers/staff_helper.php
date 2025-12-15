<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @since  2.3.3
 * Get available staff permissions, modules can use the filter too to hook permissions
 * @param  array  $data additional data passed from view role.php and member.php
 * @return array
 */
function get_available_staff_permissions($data = [])
{
    $viewGlobalName = _l('permission_view') . '(' . _l('permission_global') . ')';

    $allPermissionsArray = [
        'view_own' => _l('permission_view_own'),
        'view'     => $viewGlobalName,
        'create'   => _l('permission_create'),
        'edit'     => _l('permission_edit'),
        'delete'   => _l('permission_delete'),
    ];

    $withoutViewOwnPermissionsArray = [
        'view'   => $viewGlobalName,
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    $withNotApplicableViewOwn = array_merge(['view_own' => ['not_applicable' => true, 'name' => _l('permission_view_own')]], $withoutViewOwnPermissionsArray);

    $corePermissions = [
		'ai_support' => [
            'name'         => _l('AI Support'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
        'bulk_pdf_exporter' => [
            'name'         => _l('bulk_pdf_exporter'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
		
        'contracts' => [
            'name'         => _l('contracts'),
            'capabilities' => array_merge($allPermissionsArray, [
                'view_all_templates' => _l('permission_view_all_templates'),
            ]),
        ],
        'credit_notes' => [
            'name'         => _l('credit_notes'),
            'capabilities' => $allPermissionsArray,
        ],
		'conversation' => [
            'name'         => _l('Conversation'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
        'customers' => [
            'name'         => _l('clients'),
            'capabilities' => $withNotApplicableViewOwn,
            'help'         => [
                'view_own' => _l('permission_customers_based_on_admins'),
            ],
        ],
		'direct_email' => [
            'name'         => _l('Direct Email'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
        'email_templates' => [
            'name'         => _l('email_templates'),
            'capabilities' => [
                'view' => $viewGlobalName,
                'edit' => _l('permission_edit'),
            ],
        ],
        'estimates' => [
            'name'         => _l('estimates'),
            'capabilities' => $allPermissionsArray,
        ],
        'expenses' => [
            'name'         => _l('expenses'),
            'capabilities' => $allPermissionsArray,
        ],
        'invoices' => [
            'name'         => _l('invoices'),
            'capabilities' => $allPermissionsArray,
        ],
        'items' => [
            'name'         => _l('items'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        /*'knowledge_base' => [
            'name'         => _l('knowledge_base'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],*/
        'payments' => [
            'name'         => _l('payments'),
            'capabilities' => $withNotApplicableViewOwn,
            'help'         => [
                'view_own' => _l('permission_payments_based_on_invoices'),
            ],
        ],
        /*'projects' => [
            'name'         => _l('projects'),
            'capabilities' => array_merge($withNotApplicableViewOwn, [ 'create_milestones' => _l('permission_create_timesheets'),
                'edit_milestones'                                                          => _l('permission_edit_milestones'), 'delete_milestones' => _l('permission_delete_milestones'), ]),
            'help' => [
                'view'     => _l('help_project_permissions'),
                'view_own' => _l('permission_projects_based_on_assignee'),
            ],
        ],*/
		
		'project' => [
            'name'         => _l('project'),
            'capabilities' => array_merge( [ 
			    'view_own' => _l('permission_view_own'),
			    'view'            => $viewGlobalName,
			    'project_dashboard' => _l('Dashboard'),
				'project_project' => _l('Project'),
                'project_collaboration'=> _l('Collaboration'), 
				'project_chat'=> _l('Chat'),
				'project_status' => _l('Status'),
				'project_group' => _l('Group'), ]),
        ],
		
		'hr_department' => [
            'name'         => _l('hr_department'),
            'capabilities' => array_merge( [ 
			    'view_own' => _l('permission_view_own'),
                /*'hrd_dashboard'=> _l('Dashboard'), 
				'hrd_attendance'=> _l('Attendance'),
				'hrd_leave' => _l('Leave Application'),*/
				'view_interviews' => _l('Interviews'),
				'view_setting' => _l('settings'),
				'view_reports' => _l('Reports'), ]),
        ],
		
        /*'proposals' => [
            'name'         => _l('proposals'),
            'capabilities' => array_merge($allPermissionsArray, [
                'view_all_templates' => _l('permission_view_all_templates'),
            ]),
        ],*/
        'reports' => [
            'name'         => _l('reports'),
            'capabilities' => [
                'view'            => $viewGlobalName,
                'view-timesheets' => _l('permission_view_timesheet_report'),
            ],
        ],
        'roles' => [
            'name'         => _l('roles'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        'settings' => [
            'name'         => _l('settings'),
            'capabilities' => [
                'view' => $viewGlobalName,
                'edit' => _l('permission_edit'),
            ],
        ],
        'staff' => [
            'name'         => _l('staff'),
            'capabilities' => $withoutViewOwnPermissionsArray,
        ],
        /*'subscriptions' => [
            'name'         => _l('subscriptions'),
            'capabilities' => $allPermissionsArray,
        ],
		*/
        'tasks' => [
            'name'         => _l('tasks'),
            'capabilities' => array_merge($withNotApplicableViewOwn, [
                'edit_timesheet'       => _l('permission_edit_timesheets'),
                'edit_own_timesheet'   => _l('permission_edit_own_timesheets'),
                'delete_timesheet'     => _l('permission_delete_timesheets'),
                'delete_own_timesheet' => _l('permission_delete_own_timesheets'),
            ]),
             'help' => [
                'view'     => _l('help_tasks_permissions'),
                'view_own' => _l('permission_tasks_based_on_assignee'),
            ],
        ],
		'tickets' => [
            'name'         => _l('tickets'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
		'webmail' => [
            'name'         => _l('Webmail'),
            'capabilities' => [
				'webmail' => _l('Webmail'),
				'webmail_setup' => _l('Webmail Setup'),
            ],
        ],
		'user_utility' => [
            'name'         => _l('Team Document'),
            'capabilities' => [
                'view' => $viewGlobalName,
            ],
        ],
        'checklist_templates' => [
            'name'         => _l('checklist_templates'),
            'capabilities' => [
                'create' => _l('permission_create'),
                'delete' => _l('permission_delete'),
            ],
        ],
        'estimate_request' => [
            'name'         => _l('estimate_request'),
            'capabilities' => $allPermissionsArray,
        ],
		'finance' => [
            'name'         => _l('Finance'),
            'capabilities' => $allPermissionsArray,
        ],
    ];

    $addLeadsPermission = true;
    if (isset($data['staff_id']) && $data['staff_id']) {
        $is_staff_member = is_staff_member($data['staff_id']);
        if (!$is_staff_member) {
            $addLeadsPermission = false;
        }
    }

    if ($addLeadsPermission) {
        $corePermissions['leads'] = [
            'name'         => _l('leads'),
            'capabilities' => [
                'view'   => $viewGlobalName,
                'delete' => _l('permission_delete'),
            ],
            'help' => [
                'view' => _l('help_leads_permission_view'),
            ],
        ];
    }

    return hooks()->apply_filters('staff_permissions', $corePermissions, $data);
}
/**
 * Get staff by ID or current logged in staff
 * @param  mixed $id staff id
 * @return mixed
 */
function get_staff($id = null)
{
    if (empty($id) && isset($GLOBALS['current_user'])) {
        return $GLOBALS['current_user'];
    }

    // Staff not logged in
    if (empty($id)) {
        return null;
    }

    if (!class_exists('staff_model', false)) {
        get_instance()->load->model('staff_model');
    }

    return get_instance()->staff_model->get($id);
}

/**
 * Return staff profile image url
 * @param  mixed $staff_id
 * @param  string $type
 * @return string
 */
function staff_profile_image_url($staff_id, $type = 'small')
{
    $url = base_url('assets/images/user-placeholder.jpg');

    if ((string) $staff_id === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
        $staff = $GLOBALS['current_user'];
    } else {
        $CI = & get_instance();
        $CI->db->select('profile_image')
        ->where('staffid', $staff_id);

        $staff = $CI->db->get(db_prefix() . 'staff')->row();
    }

    if ($staff) {
        if (!empty($staff->profile_image)) {
            $profileImagePath = 'uploads/staff_profile_images/' . $staff_id . '/' . $type . '_' . $staff->profile_image;
            if (file_exists($profileImagePath)) {
                $url = base_url($profileImagePath);
            }
        }
    }

    return $url;
}

/**
 * Staff profile image with href
 * @param  boolean $id        staff id
 * @param  array   $classes   image classes
 * @param  string  $type
 * @param  array   $img_attrs additional <img /> attributes
 * @return string
 */
function staff_profile_image($id, $classes = ['staff-profile-image'], $type = 'small', $img_attrs = [])
{
    $url = base_url('assets/images/user-placeholder.jpg');

    $id = trim($id);

    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . e($val) . '" ';
    }

    $blankImageFormatted = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    if ((string) $id === (string) get_staff_user_id() && isset($GLOBALS['current_user'])) {
        $result = $GLOBALS['current_user'];
    } else {
        $CI     = & get_instance();
        $result = $CI->app_object_cache->get('staff-profile-image-data-' . $id);

        if (!$result) {
            $CI->db->select('profile_image,firstname,lastname');
            $CI->db->where('staffid', $id);
            $result = $CI->db->get(db_prefix() . 'staff')->row();
            $CI->app_object_cache->add('staff-profile-image-data-' . $id, $result);
        }
    }

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && $result->profile_image !== null) {
        $profileImagePath = 'uploads/staff_profile_images/' . $id . '/' . $type . '_' . $result->profile_image;
        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . base_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';
    }

    return $profile_image;
}

/**
 * Get staff full name
 * @param  string $userid Optional
 * @return string Firstname and Lastname
 */
function get_staff_full_name($userid = '')
{
    $tmpStaffUserId = get_staff_user_id();
    if ($userid == '' || $userid == $tmpStaffUserId) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->firstname . ' ' . $GLOBALS['current_user']->lastname;
        }
        $userid = $tmpStaffUserId;
    }

    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-full-name-data-' . $userid);

    if (!$staff) {
        $CI->db->where('staffid', $userid);
        $staff = $CI->db->select('firstname,lastname')->from(db_prefix() . 'staff')->get()->row();
        $CI->app_object_cache->add('staff-full-name-data-' . $userid, $staff);
    }

    return $staff ? $staff->firstname . ' ' . $staff->lastname : '';
}

function get_staff_rolex($userid = '')
{ $tmpStaffUserId = get_staff_user_id();
    if ($userid == '' || $userid == $tmpStaffUserId) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->role;
        }
        $userid = $tmpStaffUserId;
    }

    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-roll-data-' . $userid);

    /**if (!$staff) {
        $CI->db->where('staffid', $userid);
        $staff = $CI->db->select('role')->from(db_prefix() . 'staff')->get()->row();
        $CI->app_object_cache->add('staff-roll-data-' . $userid, $staff);
    } */

    return $staff ? $staff->role : '';
}

function get_staff_company_id($userid = '')
{   


    $tmpStaffUserId = get_staff_user_id();
    if ($userid == '' || $userid == $tmpStaffUserId) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->company_id;
        }
        $userid = $tmpStaffUserId;
    }
    $CI = & get_instance();
    $staff = $CI->app_object_cache->get('staff-roll-data-' . $userid);
    return $staff ? $staff->company_id : '';
}
function get_branch_id($staffid='')
{   
        // Fetch company Name from company id
		// check company id is found or not
		if($staffid==""){
				$tmpStaffUserId = get_staff_user_id();
				if(isset($GLOBALS['current_user'])) {
				$branch_id=$GLOBALS['current_user']->branch;
				}
		 }
	
		
		if(isset($staffid)&&$staffid){
			$CI = & get_instance();
			$CI->db->where('staffid', $staffid);
			$com = $CI->db->select('branch')->from(db_prefix() . 'staff')->get()->row();
			if(isset($com)&&$com->branch){
			return $com->branch;
			}
		}
		
		return $branch_id;
}

function get_departments_id($staffid='')
{   
        // Fetch company Name from company id
		if(empty($staffid)){
		$staffid=get_staff_user_id();
		}
		
		if(isset($staffid)&&$staffid){
		$CI = & get_instance();
		$CI->db->where('staffid', $staffid);
		$com = $CI->db->select('departmentid')->from(db_prefix() . 'staff_departments')->get()->row();
		if(isset($com)&&$com->departmentid){
		return $com->departmentid;
		}
		}
		
		return 0;
}

function get_staff_branch_name($branchid='')
{   
        // Fetch company Name from company id
		if(empty($branchid)){
		$branchid=1;
		}
		
		if(isset($branchid)&&$branchid){
		$CI = & get_instance();
		$CI->db->where('id', $branchid);
		$com = $CI->db->select('branch_name')->from(db_prefix() . 'hrd_branch_manager')->get()->row();
		if(isset($com)&&$com->branch_name){
		return $com->branch_name;
		}
		}
		
		return 0;
}

function get_staff_designations_name($id='')
{   
        // Fetch company Name from company id
		if(empty($id)){
		return 0;
		}
		
		if(isset($id)&&$id){
		$CI = & get_instance();
		$CI->db->where('id', $id);
		$com = $CI->db->select('title')->from(db_prefix() . 'designations')->get()->row();
		if(isset($com)&&$com->title){
		return $com->title;
		}
		}
		
		return 0;
}

function get_staff_department_name($id='')
{   
        // Fetch company Name from company id
		if(empty($id)){
		return 0;
		}
		
		if(isset($id)&&$id){
		$CI = & get_instance();
		$CI->db->where('departmentid', $id);
		$CI->db->where('company_id', get_staff_company_id());
		$com = $CI->db->select('name')->from(db_prefix() . 'departments')->get()->row();
		if(isset($com)&&$com->name){
		return $com->name;
		}
		}
		
		return 0;
}

function get_staff_staff_type($staffid='')
{   
        // Fetch company Name from company id
		if(empty($staffid)){
		$staffid=get_staff_user_id();
		}
		
		if(isset($staffid)&&$staffid){
		$CI = & get_instance();
		$CI->db->where('id', $staffid);
		$com = $CI->db->select('title')->from(db_prefix() . 'hrd_staff_type')->get()->row();
		if(isset($com)&&$com->title){
		return $com->title;
		}
		}
		
		return 0;
}

function get_saturday_rule($tableid='')
{   
        
		
		if(isset($tableid)&&$tableid){
		$CI = & get_instance();
		$CI->db->where('id', $tableid);
		$com = $CI->db->select('title')->from(db_prefix() . 'hrd_saturday_rule')->get()->row();
		if(isset($com)&&$com->title){
		return $com->title;
		}
		}
		
		return 0;
}

function get_company_status($company_id)
{   
        // Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('active')->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->active){
		return $com->active;
		}
		}
		
		return 0;
}
//Get Approver ID by Spprover Type hr_approver,admin_approver,hr_manager_approver,reporting_approver
function get_approver_id($approver_type = 'hr_approver')
{ 
                $tmpStaffUserId = get_staff_user_id();
				if(isset($GLOBALS['current_user'])) {
				$approver=$GLOBALS['current_user']->approver;
				}
				// Decode JSON into an array
                $approverArr = json_decode($approver, true);

                // Return only HR Approver value
               return $approverArr[$approver_type] ?? get_staff_user_id();

}

function get_hrd_email()
{   
				if(isset($GLOBALS['current_user'])) {
				$approver=$GLOBALS['current_user']->approver;
				}
				// Decode JSON into an array
                $approverArr = json_decode($approver, true);

                // Return only HR Approver value
               return $approverArr['hr_approver'] ?? get_staff_user_id();
}

function get_admin_email()
{   

				if(isset($GLOBALS['current_user'])) {
				$approver=$GLOBALS['current_user']->approver;
				}
				 // Decode JSON into an array
                $approverArr = json_decode($approver, true);

                // Return only HR Approver value
               return $approverArr['admin_approver'] ?? get_staff_user_id();
}

function get_deal_form_type($company_id)
{   
        // Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('deal_form_type')->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->deal_form_type){
		return $com->deal_form_type;
		}
		}
		
		return 0;
}

function get_deals_stage_title($sid)
{   
        // Fetch company Name from company id
		if(isset($sid)&&$sid){
		$CI = & get_instance();
		$CI->db->where('id', $sid);
		$com = $CI->db->select('stage')->from(db_prefix() . 'deals_stage')->get()->row();
		if(isset($com)&&$com->stage){
		return $com->stage;
		}
		}
		
		return 0;
}

function exist_deal_process($deal_id,$deal_stage,$company_id)
{   
       
		$CI = & get_instance();
		$CI->db->where('deal_id', $deal_id);
		$CI->db->where('deal_stage', $deal_stage);
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('count(id) as cnt')->from(db_prefix() . 'deals_process_list')->get()->row();
		if(isset($com)&&$com->cnt==1){
		return true;
		}else{
		return false;
		}
	
		return false;
}

function get_staff_email($sid='')
{   
        // Fetch company Name from company id
		if(isset($sid)&&$sid){
		$staff_id=$sid;
		}else{
		$staff_id=get_staff_user_id();
		}
		
		$CI = & get_instance();
		$CI->db->where('staffid', $staff_id);
		$com = $CI->db->select('email')->from(db_prefix() . 'staff')->get()->row();
		if(isset($com)&&$com->email){
		return $com->email;
		}
		
		
		return 0;
}

function get_staff_company_name($company_id = '')
{   

        // check company id is found or not
		if($company_id==""){
				$tmpStaffUserId = get_staff_user_id();
				if(isset($GLOBALS['current_user'])) {
				$company_id=$GLOBALS['current_user']->company_id;
				}
		 }
		 
        // Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('companyname')->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->companyname){
		return $com->companyname;
		}else{
		return $company_id;
		}
		
		}

    return $company_id;
}

function get_staff_company_logo($company_id = '')
{  

if(isset($_SESSION['staff_company_id'])&&$_SESSION['staff_company_id']<>1){
$company_id=$_SESSION['staff_company_id'];
}	

        // check company id is found or not
		if($company_id==""){
				$tmpStaffUserId = get_staff_user_id();
				if(isset($GLOBALS['current_user'])) {
				$company_id=$GLOBALS['current_user']->company_id;
				}
		 }
	
        // Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('company_logo')->from(db_prefix() . 'company_master')->get()->row();
		//echo $this->db->last_query();exit;
		if(isset($com)&&$com->company_logo){
		return $com->company_logo;
		}else{
		return "68080b344bc9520c4a031d635b9545f0.png";
		}
		
		}

    return $company_id;
}

function get_staff_favicon($company_id = '')
{   

        // check company id is found or not
		if($company_id==""){
				$tmpStaffUserId = get_staff_user_id();
				if(isset($GLOBALS['current_user'])) {
				$company_id=$GLOBALS['current_user']->company_id;
				}
		 }
		 
        // Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('favicon')->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->favicon){
		return $com->favicon;
		}else{
		return $company_id;
		}
		
		}

    return $company_id;
}

function get_company_website($company_id = '')
{   

        // check company id is found or not
		if($company_id==""){
				$tmpStaffUserId = get_staff_user_id();
				if(isset($GLOBALS['current_user'])) {
				$company_id=$GLOBALS['current_user']->company_id;
				}
		 }
		 
        // Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('website')->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->website){
		return $com->website;
		}else{
		return $company_id;
		}
		
		}

    return $company_id;
}


function get_staff_fields($userid = '',$fieldname = '')
{ $tmpStaffUserId = get_staff_user_id();
    if ($userid == '' || $userid == $tmpStaffUserId) {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->$fieldname;
        }
        $userid = $tmpStaffUserId;
    }

    $CI = & get_instance();

    $staff = $CI->app_object_cache->get('staff-roll-data-' . $userid);

  

    return $staff ? $staff->$fieldname : '';
}

function get_company_fields($company_id = '',$fieldname = '')
{ 
		// Fetch company Name from company id
		if(isset($company_id)&&$company_id){
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select($fieldname)->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->$fieldname){
		return $com->$fieldname;
		}else{
		return $company_id;
		}
	}
		return $company_id;
}

/**
 * Get staff default language
 * @param  mixed $staffid
 * @return mixed
 */
function get_staff_default_language($staffid = '')
{
    if (!is_numeric($staffid)) {
        // checking for current user if is admin
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->default_language;
        }

        $staffid = get_staff_user_id();
    }
    $CI = & get_instance();
    $CI->db->select('default_language');
    $CI->db->from(db_prefix() . 'staff');
    $CI->db->where('staffid', $staffid);
    $staff = $CI->db->get()->row();
    if ($staff) {
        return $staff->default_language;
    }

    return '';
}

/**
 * Get staff default language
 * @param  mixed $staffid
 * @return mixed
 */
function get_staff_signature($staffid = '')
{
    if (!is_numeric($staffid)) {
        // checking for current user if is admin
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->email_signature;
        }

        $staffid = get_staff_user_id();
    }
    $CI = & get_instance();
    $CI->db->select('email_signature');
    $CI->db->from(db_prefix() . 'staff');
    $CI->db->where('staffid', $staffid);
    $staff = $CI->db->get()->row();
    if ($staff) {
        return $staff->email_signature;
    }

    return '';
}

function get_staff_recent_search_history($staff_id = null)
{
    $recentSearches = get_staff_meta($staff_id ? $staff_id : get_staff_user_id(), 'recent_searches');

    if ($recentSearches == '') {
        $recentSearches = [];
    } else {
        $recentSearches = json_decode($recentSearches);
    }

    return $recentSearches;
}

function update_staff_recent_search_history($history, $staff_id = null)
{
    $totalRecentSearches = hooks()->apply_filters('total_recent_searches', 5);
    $history             = array_reverse($history);
    $history             = array_unique($history);
    $history             = array_splice($history, 0, $totalRecentSearches);

    update_staff_meta($staff_id ? $staff_id : get_staff_user_id(), 'recent_searches', json_encode($history));

    return $history;
}


/**
 * Check if user is staff member
 * In the staff profile there is option to check IS NOT STAFF MEMBER eq like contractor
 * Some features are disabled when user is not staff member
 * @param  string  $staff_id staff id
 * @return boolean
 */
function is_staff_member($staff_id = '')
{
    $CI = & get_instance();
    if ($staff_id == '') {
        if (isset($GLOBALS['current_user'])) {
            return $GLOBALS['current_user']->is_not_staff === '0';
        }
        $staff_id = get_staff_user_id();
    }

    $CI->db->where('staffid', $staff_id)
    ->where('is_not_staff', 0);

    return $CI->db->count_all_results(db_prefix() . 'staff') > 0 ? true : false;
}

// Function to get the name of a staff role based on the provided role ID.
function get_staff_role_name($roleid = NULL)
{
	// Get the CodeIgniter instance to access database and other libraries
	$CI = & get_instance();

	// Add a WHERE condition to filter roles based on the given roleid
	$CI->db->where('roleid', $roleid);

	// Execute the query to get the row from the 'roles' table where roleid matches
	$roles_detail = $CI->db->get(db_prefix() . 'roles')->row();

	// Check if the query returned a result
	if ($roles_detail)
	{
		// If a role is found, retrieve the role name
		$role_name = $roles_detail->name;

		// Return the role name
		return $role_name;
	}

	// If no matching role is found, return nothing (NULL)
	return;
}

/**
 * Get attendance status title by status ID
 * @param int|string $statusId
 * @return string
 */
function get_attendance_status_title($statusId)
{
	$numericId = (int) $statusId;
	if ($numericId <= 0) {
		return '';
	}

	$CI = & get_instance();
	$row = $CI->db
    ->select('title, color, remark')
		->from(db_prefix() . 'hrd_attendance_status')
		->where('id', $numericId)
		->get()
		->row();

	return $row ? "<span title='".(string) $row->remark."' style='color:".(string) $row->color.";font-weight: 900;'>".(string) $row->title ."</span>" : "";
}

function get_attendance_status($statusId)
{
	$numericId = (int) $statusId;
	if ($numericId <= 0) {
		return '';
	}

	$CI = & get_instance();
	$row = $CI->db
    ->select('title, color, remark')
		->from(db_prefix() . 'hrd_attendance_status')
		->where('id', $numericId)
		->get()
		->row();

	return $row ? $row : "";
}

function getAttendanceStatus($staffId, $shiftID, $date, $staffType = null, $attendance_id=null) {
//echo $staffId;
//echo $inTime;
//echo $outTime;
//echo $date;
//echo $staffType;
//echo $shiftID;
//echo $attendance_status;
//echo $attendance_id;
    if (!class_exists('hrd_model', false)) {
        get_instance()->load->model('hrd_model');
    }
       // Get shift details	
		$data['shift_details'] = get_instance()->hrd_model->get_shift_details($shiftID);
		if(isset($attendance_id)&&$attendance_id){
		$data['attendance_list'] = get_instance()->hrd_model->get_attendance($attendance_id);
		//print_r($data['attendance_list']);
		$date=$data['attendance_list']->entry_date;
		$inTime=$data['attendance_list']->in_time;
		$outTime=$data['attendance_list']->out_time;
		$attendance_status=$data['attendance_list']->first_half;
		$attendance_substatus=$data['attendance_list']->second_half;
		}else{
		$attendance_status=0;
		$attendance_substatus=0;
		}
		
        
        $status = 4;
		$substatus = 0;
        $position = 0;
        $remarks = "";

        // Validate required staffId
        if (empty($staffId)) {
            return [
                'status' => 'Invalid',
				'substatus' => 0,
                'position' => 0.00,
                'remarks' => 'Staff ID missing'
            ];
        }

        // Validate shift details
        if (empty($data['shift_details']) || !isset($data['shift_details'][0])) {
            return [
                'status' => 'Invalid',
				'substatus' => 0,
                'position' => 0.00,
                'remarks' => 'Shift details not found'
            ];
        }

        // Default date
        if (empty($date)) $date = date('Y-m-d');
        $dayName = date('l', strtotime($date));

        // Convert times to DateTime
       // $inTimeObj = !empty($inTime) ? new DateTime($inTime) : null;
        //$outTimeObj = !empty($outTime) ? new DateTime($outTime) : null;
		$inTimeObj = (!empty($inTime) && $inTime != '-') ? new DateTime($inTime) : null;
        $outTimeObj = (!empty($outTime) && $outTime != '-') ? new DateTime($outTime) : null;



        // Default working hours
        $officeIn = $data['shift_details'][0]['shift_in'];
        $officeOut = $data['shift_details'][0]['shift_out'];
        $firstHalfIn = $data['shift_details'][0]['first_half_start'];
        $firstHalfOut = $data['shift_details'][0]['first_half_end'];
        $secondHalfIn = $data['shift_details'][0]['second_half_start'];
        $secondHalfOut = $data['shift_details'][0]['second_half_end'];
		$saturday_rule = $data['shift_details'][0]['saturday_rule'];
		$saturday_work_end = $data['shift_details'][0]['saturday_work_end'];
		
		//echo $attendance_status;
		//echo $attendance_substatus;
		if (isset($attendance_status)&& $attendance_status==8 && $attendance_substatus==0 ) {
            return [
                'status' => $attendance_status,
				'substatus' => 0,
                'position' => 0,
                'remarks' => 'Absent Without Pay'
            ];
        }
		
		if (isset($attendance_status)&& $attendance_status==1 && $attendance_substatus==8 ) {
            return [
                'status' => $attendance_status,
				'substatus' => 8,
                'position' => 0.5,
                'remarks' => 'Absent Without Pay'
            ];
        }
		
		
		if (isset($attendance_status)&& ($attendance_status==1 || $attendance_status==2 || $attendance_status==3|| $attendance_status==7)) {
            return [
                'status' => $attendance_status,
				'substatus' => 0,
                'position' => 1.00,
                'remarks' => 'Present'
            ];
        }
		
		
		

        // ==============================================================
        // 1. Check if date is in holidays
        // ==============================================================

        if (isHoliday($date)) {
            return [
                'status' => 7,
				'substatus' => 0,
                'position' => 1.00,
                'remarks' => 'Holiday'
            ];
        }

        // ==============================================================
        //  2. Check if Sunday
        // ==============================================================

        if ($dayName == 'Sunday') {
            return [
                'status' => 2,
				'substatus' => 0,
                'position' => 1.00,
                'remarks' => 'Sunday (Weekly Off)'
            ];
        }

        // ==============================================================
        //  3. Check if Saturday
        // ==============================================================

        if ($dayName == 'Saturday') {
            $saturdayRule = $saturday_rule;
            $weekOfMonth = ceil(date('j', strtotime($date)) / 7);
			
			
			// Working Saturday
            $saturdayOutTime = new DateTime($saturday_work_end);
			//echo $inTimeObj->format('H:i:s');  // prints time only//
            $officeInObj = new DateTime($officeIn);
            if ($inTimeObj && $outTimeObj && $inTimeObj <= $officeInObj && $outTimeObj >= $saturdayOutTime) {
                return ['status' => 1,'substatus' => 0, 'position' => 1.00, 'remarks' => 'Saturday Present'];
            }
			//echo $staffType;
			if (in_array($staffType, [2, 3, 4]) && in_array($weekOfMonth, [1, 3])) { // HRD, INTERN, Notice Period EMP
                return ['status' => 4, 'substatus' => 0, 'position' => 0.00, 'remarks' => 'Working for HRD'];
            }

            if ($saturdayRule == 1) { //All Saturday Off
                return ['status' => 2, 'substatus' => 0, 'position' => 1.00, 'remarks' => 'Saturday Off (All Off)'];
            }

            if ($saturdayRule == 2 && $weekOfMonth != 5) { //Only 5th Saturday Working
                return ['status' => 2, 'substatus' => 0, 'position' => 1.00, 'remarks' => 'Saturday Off (Only 5th Working)'];
            }

            if ($saturdayRule == 4 && in_array($weekOfMonth, [2, 4])) { //2nd And 4th Saturday off
                return ['status' => 2, 'substatus' => 0, 'position' => 1.00, 'remarks' => 'Saturday Off (2nd/4th)'];
            }

            if ($saturdayRule == 5 && in_array($weekOfMonth, [1, 3])) { //1st And 3rd Saturday off
                return ['status' => 2, 'substatus' => 0, 'position' => 1.00, 'remarks' => 'Saturday Off (1st/3rd)'];
            }

            

            
        }

        // ==============================================================
        //  4. If OutTime missing - Absent (only for working days)
        // ==============================================================
		
		 if (empty($inTime) || $inTime == '-') {
            return [
                'status' => 4,
				'substatus' => 0,
                'position' => 0,
                'remarks' => 'No In Time'
            ];
        }

        if (empty($outTime) || $outTime == '-') {
            return [
                'status' => 4,
				'substatus' => 0,
                'position' => 0,
                'remarks' => 'No Out Time'
            ];
        }

        // ==============================================================
        //  5. Regular Attendance Logic
        // ==============================================================

        if ($inTimeObj && $outTimeObj) {
            // Convert time strings to DateTime objects for comparison
            $officeInObj = new DateTime($officeIn);
            $officeOutObj = new DateTime($officeOut);
            $firstHalfInObj = new DateTime($firstHalfIn);
            $firstHalfOutObj = new DateTime($firstHalfOut);
            $secondHalfInObj = new DateTime($secondHalfIn);
            $secondHalfOutObj = new DateTime($secondHalfOut);

            $lateMark = ($inTimeObj > $officeInObj) ? 'Late Mark' : '';

            // Check first half
            $firstHalf = ($inTimeObj <= $firstHalfInObj && $outTimeObj >= $firstHalfOutObj) ? 1 : 0;

            // Check second half
            $secondHalf = ($inTimeObj <= $secondHalfInObj && $outTimeObj >= $secondHalfOutObj) ? 1 : 0;

            // Full day present
			//echo $inTimeObj->format('H:i:s');  // prints time only
			//echo $officeInObj->format('H:i:s');  // prints time only
			//echo $outTimeObj->format('H:i:s');  // prints time only
			//echo $officeOutObj->format('H:i:s');  // prints time only
			//echo "@@@";
            if ($inTimeObj <= $officeInObj && $outTimeObj >= $officeOutObj) {
                $status = 1;
				$substatus = 0;
                $position = 1.00;
                $remarks = $lateMark ?: 'On Time';
            }
            // Half day
            elseif ($firstHalf || $secondHalf) {
			    $lateMark="";
				if($firstHalf){ 
				$lateMark="First Half";
				$status = 1;
				$substatus = 8;
				}else{ 
				$lateMark="Second Half";
				$status = 8;
				$substatus = 1;
				}
                
                $position = 0.50;
                $remarks = $lateMark ?: '';
            } else {
                $status = 4;
				$substatus = 0;
                $position = 0;
                $remarks = 'Insufficient Hours';
            }
        } else {
            // If inTime is missing but outTime exists, or vice versa
            $status = 4;
			$substatus = 0;
            $position = 0;
            $remarks = 'Incomplete Attendance';
        }

        return [
            'status' => $status,
			'substatus' => $substatus,
            'position' => $position,
            'remarks' => $remarks
        ];
    }

  

    /**
     *  Holiday Check Function
     */
    function isHoliday($date) {
        // You can replace this with a DB query
        // Example: SELECT * FROM holidays WHERE date = '$date'
       $CI = & get_instance();
	    // Fetch all active holidays (status = 1)
       $query = $CI->db->select('holiday_date')
                    ->from('it_crm_hrd_holiday_list')
                    ->where('status', 1)
                    ->get();

        // Create an array of holiday dates
        $holidays = array_column($query->result_array(), 'holiday_date');
	

        return in_array($date, $holidays);
    }
	
function amountInWords($number)
{
    $number = round($number, 2);
    $no = floor($number);
    $decimal = round(($number - $no) * 100);

    $words = [
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen',
        17 => 'Seventeen', 18 => 'Eighteen', 19 => 'Nineteen',
        20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty',
        50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
        80 => 'Eighty', 90 => 'Ninety'
    ];

    $digits = ['', 'Thousand', 'Lakh', 'Crore'];

    function twoDigits($n, $words)
    {
        if ($n < 21) return $words[$n];
        return $words[floor($n / 10) * 10] . ($n % 10 ? ' ' . $words[$n % 10] : '');
    }

    $str = [];

    if ($no >= 10000000) {
        $str[] = twoDigits(floor($no / 10000000), $words) . ' Crore';
        $no %= 10000000;
    }
    if ($no >= 100000) {
        $str[] = twoDigits(floor($no / 100000), $words) . ' Lakh';
        $no %= 100000;
    }
    if ($no >= 1000) {
        $str[] = twoDigits(floor($no / 1000), $words) . ' Thousand';
        $no %= 1000;
    }
    if ($no >= 100) {
        $str[] = $words[floor($no / 100)] . ' Hundred';
        $no %= 100;
    }
    if ($no > 0) {
        $str[] = twoDigits($no, $words);
    }

    $rupees = implode(' ', $str);

    $paise = '';
    if ($decimal > 0) {
        $paise = ' and ' . twoDigits($decimal, $words) . ' Paise';
    }

    return trim($rupees) . ' Rupees' . $paise . ' Only';
}

