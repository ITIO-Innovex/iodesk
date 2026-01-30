<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Project_model $project_model
 */
class Project extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (get_option('access_projects_to_none_staff_members') == 0 && !is_staff_member()) {
            //redirect(admin_url());
        }
		$this->load->model('project_model');
    }

    public function index($status = '', $userid = '')
    {
        if (!is_admin() && !staff_can('project_project', 'project')) {
            access_denied('Project');
        }
        
        // Load required models
        $this->load->model('staff_model');
        $this->load->model('project_model');
        
        // Get data for the view
        $data['listdata'] = $this->project_model->get_project_list();
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        $data['project_groups'] = $this->project_model->get_project_groups();
        $data['project_statuses'] = $this->project_model->get_project_statuses();
        $data['title']    = 'Project List';
        $this->load->view('admin/project/list', $data);
    } 
	
	    public function tasks($id = '' , $sid = '')
        {
			 if (!is_admin() && !staff_can('project_project', 'project')) {
             access_denied('Project');
             }
		
        
        // Load required models
        $this->load->model('staff_model');
        $this->load->model('project_model');
        
        // Get data for the view
        $data['listdata'] = $this->project_model->get_task_list($id);
		//print_r($data['listdata']);
		$data['project_id'] = $id;
		
		//print_r($data['listdata']);exit;
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
		$data['project_list'] = $this->project_model->get_projectlist();
		$data['project_statuses'] = $this->project_model->get_project_statuses();
		$data['project_priority'] = $this->project_model->project_priority();
		
        //$data['project_groups'] = $this->project_model->get_project_groups();
        $data['title']    = 'Task List';
        $this->load->view('admin/project/task', $data);
    }
	
	
	
    // Project statuses
    /* Get all project statuses */
    public function project_status()
    {
        if (!is_admin()) {
            //access_denied('Project Statuses');
        }
        $data['statuses'] = $this->project_model->get_project_statuses();
        $data['title']    = 'Project Statuses';
        $this->load->view('admin/project/status', $data);
    }

    /* Add new or edit existing status */
    public function projectstatus()
    {
        if (!is_admin()) {
            //access_denied('Project Statuses');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $data['company_id']  = get_staff_company_id();
                $id = $this->project_model->add_project_status($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('Project Status')));
                        redirect(admin_url('project/project_status'));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->project_model->update_project_status($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('Project Status')));
                    redirect(admin_url('project/project_status'));
                } else {
                    set_alert('danger', 'No any Update found');
                    redirect(admin_url('project/project_status'));
                }
            }
        }
    }

    /* Delete project status from database */
    public function delete_project_status($id)
    {
        if (!is_admin()) {
            //access_denied('Project Statuses');
        }
        if (!$id) {
            redirect(admin_url('project/project_status'));
        }
        $response = $this->project_model->delete_project_status($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('Project Status')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('Project Status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('Project Status')));
        }
        redirect(admin_url('project/project_status'));
    }

	// Project priorities
	/* Get all project priorities */
	public function project_priority()
	{
		if (!is_admin()) {
			//access_denied('Project Priorities');
		}
		$data['priorities'] = $this->project_model->get_project_priorities();
		$data['title']    = 'Project Priorities';
		$this->load->view('admin/project/priority', $data);
	}

	/* Add new or edit existing priority */
	public function projectpriority()
	{
		if (!is_admin()) {
			//access_denied('Project Priorities');
		}
		if ($this->input->post()) {
			$data = $this->input->post();
			if (!$this->input->post('priorityid')) {
				$inline = isset($data['inline']);
				if (isset($data['inline'])) {
					unset($data['inline']);
				}
				$id = $this->project_model->add_project_priority($data);
				if (!$inline) {
					if ($id) {
						set_alert('success', _l('added_successfully', _l('Project Priority')));
						redirect(admin_url('project/project_priority'));
					}
				} else {
					echo json_encode(['success' => $id ? true : false, 'id' => $id]);
				}
			} else {
				$id = $data['priorityid'];
				unset($data['priorityid']);
				$success = $this->project_model->update_project_priority($data, $id);
				if ($success) {
					set_alert('success', _l('updated_successfully', _l('Project Priority')));
					redirect(admin_url('project/project_priority'));
				} else {
					set_alert('danger', 'No any Update found');
					redirect(admin_url('project/project_priority'));
				}
			}
		}
	}

	/* Delete project priority from database */
	public function delete_project_priority($id)
	{
		if (!is_admin()) {
			//access_denied('Project Priorities');
		}
		if (!$id) {
			redirect(admin_url('project/project_priority'));
		}
		$response = $this->project_model->delete_project_priority($id);
		if ($response == true) {
			set_alert('success', _l('deleted', _l('Project Priority')));
		} else {
			set_alert('warning', _l('problem_deleting', _l('Project Priority')));
		}
		redirect(admin_url('project/project_priority'));
	}

    // Project groups
    /* Get all project groups */
    public function project_group()
    {
        if (!is_admin()) {
            //access_denied('Project Groups');
        }
        $data['groups'] = $this->project_model->get_project_groups();
        $data['title']  = 'Project Groups';
        $this->load->view('admin/project/group', $data);
    }

    /* Add new or edit existing group */
    public function projectgroup()
    {
        if (!is_admin()) {
            //access_denied('Project Groups');
        }
        if ($this->input->post()) {
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $inline = isset($data['inline']);
                if (isset($data['inline'])) {
                    unset($data['inline']);
                }
                $data['company_id']  = get_staff_company_id();
                $id = $this->project_model->add_project_group($data);
                if (!$inline) {
                    if ($id) {
                        set_alert('success', _l('added_successfully', _l('Project Group')));
                        redirect(admin_url('project/project_group'));
                    }
                } else {
                    echo json_encode(['success' => $id ? true : false, 'id' => $id]);
                }
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->project_model->update_project_group($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('Project Group')));
                    redirect(admin_url('project/project_group'));
                } else {
                    set_alert('danger', 'No any Update found');
                    redirect(admin_url('project/project_group'));
                }
            }
        }
    }

    /* Delete project group from database */
    public function delete_project_group($id)
    {
        if (!is_admin()) {
            //access_denied('Project Groups');
        }
        if (!$id) {
            redirect(admin_url('project/project_group'));
        }
        $response = $this->project_model->delete_project_group($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('Project Group')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('Project Group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('Project Group')));
        }
        redirect(admin_url('project/project_group'));
    }
	
	// Project Dashboard
    /* Get project Dashboard Data */
    public function dashboard()
    {
         if (!is_admin() && !staff_can('project_project', 'project')) {
            access_denied('Project');
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
        
        // Get staff members for filters
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        
        $data['title'] = 'HRD Dashboard';
        $this->load->view('admin/project/dashboard', $data);
    }
	
		// Project Collaboration
    /* Get project Collaboration Data */
    public function collaboration()
    {
       
		if (!is_admin() && !staff_can('project_collaboration', 'project')) {
            access_denied('Project Collaboration');
        }
        
        $this->load->model('project_model');
        $this->load->model('staff_model');
        
        // Get activity logs for feed
        $data['activity_logs'] = $this->project_model->get_activity_logs();
        
        // Get activity logs for calendar (formatted as events)
        $data['calendar_events'] = $this->project_model->get_calendar_events();
        
        // Get staff members for filtering
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        
        // Get project statuses for reference
        $data['project_statuses'] = $this->project_model->get_project_statuses();
        
        $data['title'] = 'Project Collaboration';
        $this->load->view('admin/project/collaboration', $data);
    }
    
    // Debug method to test activity log data
    public function test_activity_logs()
    {
        if (!is_admin()) {
            //access_denied('Project Collaboration');
        }
        
        $this->load->model('project_model');
        
        // Test raw query
        $raw_logs = $this->db->get(db_prefix() . 'project_activity_log')->result_array();
        echo "<h3>Raw Activity Logs (" . count($raw_logs) . " records):</h3>";
        echo "<pre>" . print_r($raw_logs, true) . "</pre>";
        
        // Test model method
        $model_logs = $this->project_model->get_activity_logs(10);
        echo "<h3>Model Activity Logs (" . count($model_logs) . " records):</h3>";
        echo "<pre>" . print_r($model_logs, true) . "</pre>";
        
        // Test calendar events
        $calendar_events = $this->project_model->get_calendar_events();
        echo "<h3>Calendar Events (" . count($calendar_events) . " events):</h3>";
        echo "<pre>" . print_r($calendar_events, true) . "</pre>";
    }
	
	// Add new project
    public function addproject()
    {
        if (!is_admin()) {
            //access_denied('Projects');
        }
        
        if ($this->input->post()) {
            $data = $this->input->post();
            
            // Debug: Log the received data
           // log_message('debug', 'Project form data: ' . print_r($data, true));
            
            // Prepare data for database insertion
            $insert_data = array();
            
            // Map form fields to database fields
            if (!empty($data['project_title'])) $insert_data['project_title'] = $data['project_title'];
            if (!empty($data['project_description'])) $insert_data['project_description'] = $data['project_description'];
            if (!empty($data['owner'])) $insert_data['owner'] = $data['owner'];
            if (!empty($data['start_date'])) $insert_data['start_date'] = $data['start_date'];
            if (!empty($data['deadline'])) $insert_data['deadline'] = $data['deadline'];
            if (!empty($data['project_group'])) $insert_data['project_group'] = $data['project_group'];
            //if (!empty($data['tags'])) $insert_data['tags'] = $data['tags'];
			
			
			if(isset($data['tags'])&&$data['tags']){
		//$tagsJson = $_POST['tags']; 
        // Convert JSON to PHP array
        $tagsArray = json_decode($data['tags'], true);
        // Extract only the "value" from each tag
        $tagsOnly = array_column($tagsArray, 'value');
        // Convert to comma-separated string
        $insert_data['tags'] = implode(',', $tagsOnly);
		}else{
		$insert_data['tags']=""; // insert old tags
		}
		
		
            $insert_data['make_this_a_strict_project'] = isset($data['make_this_a_strict_project']) ? 1 : 0;
            $insert_data['project_access'] = isset($data['project_access']) ? $data['project_access'] : 1;
			
			
            
            // Set default values
            $insert_data['company_id'] = get_staff_company_id();
            $insert_data['project_created'] = date('Y-m-d H:i:s');
            $insert_data['addedby'] = get_staff_user_id();
            $insert_data['project_status'] = 1; // Default status
            $insert_data['progress'] = 0; // Default progress
            
            // Debug: Log the final data
            //log_message('debug', 'Final insert data: ' . print_r($insert_data, true));
            
            // Add the project
            try {
                $id = $this->project_model->add($insert_data);
                
                if ($id) {
                    log_message('debug', 'Project added successfully with ID: ' . $id);
                    echo json_encode(['success' => true, 'id' => $id, 'message' => _l('added_successfully', _l('Project'))]);
                } else {
                    log_message('error', 'Failed to add project. Database error: ' . $this->db->last_query());
                    echo json_encode(['success' => false, 'message' => _l('problem_adding', _l('Project'))]);
                }
            } catch (Exception $e) {
                log_message('error', 'Exception while adding project: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            }
        } else {
            show_404();
        }
    }
	
	/* Delete Project */
    public function delete_project($id)
    {
        if (!$id) {
            redirect(admin_url('project'));
        }
        
        $response = $this->project_model->delete_project($id);
		
		if ($response) {
        set_alert('success', _l('deleted', _l('Project')));
		}else{
		set_alert('danger', _l('problem_deleting', _l('Project')));
		}
        redirect(admin_url('project'));
    }	
	
	/* Delete Task  */
    public function delete_task($id)
    {
        if (!$id) {
            redirect(admin_url('project/tasks'));
        }
        
        $response = $this->project_model->delete_task($id);
		
		if ($response) {
        set_alert('success', _l('deleted', _l('Task')));
		}else{
		set_alert('danger', _l('problem_deleting', _l('Task')));
		}
        redirect(admin_url('project/tasks'));
    }
	
	// Add new task
    public function addtask($pid='')
    {
        if (!is_admin()) {
            //access_denied('Projects');
        }
        
        if ($this->input->post()) {
            $data = $this->input->post();
            
            // Debug: Log the received data
            log_message('debug', 'Project form data: ' . print_r($data, true));
			 //print_r($data);exit;
            
            // Prepare data for database insertion
            $insert_data = array();
            //exit;
            // Map form fields to database fields
            if (!empty($data['task_name'])) $insert_data['task_name'] = $data['task_name'];
            if (!empty($data['task_description'])) $insert_data['task_description'] = $data['task_description'];
            if (!empty($data['task_owner'])) {
                // Accept multiple owners from multiselect; store as comma-separated string
                if (is_array($data['task_owner'])) {
                    $insert_data['task_owner'] = implode(',', array_filter($data['task_owner']));
                } else {
                    $insert_data['task_owner'] = $data['task_owner'];
                }
            }
            if (!empty($data['task_start_date'])) $insert_data['task_start_date'] = $data['task_start_date'];
            if (!empty($data['task_end_date'])) $insert_data['task_end_date'] = $data['task_end_date'];
            if (!empty($data['task_priority'])) $insert_data['task_priority'] = $data['task_priority'];
            //if (!empty($data['tags'])) $insert_data['task_tags'] = $data['tags'];
			if (!empty($data['project_id'])) $insert_data['project_id'] = $data['project_id'];
			
			if (!empty($data['task_reminder'])) $insert_data['task_reminder'] = $data['task_reminder'];
			if (!empty($data['reminder_daily_time'])) $insert_data['reminder_daily_time'] = $data['reminder_daily_time'];
			if (!empty($data['reminder_on_date'])) $insert_data['reminder_on_date'] = $data['reminder_on_date'];
			if (!empty($data['reminder_on_time'])) $insert_data['reminder_on_time'] = $data['reminder_on_time'];
			
		if(isset($data['tags'])&&$data['tags']){
		//$tagsJson = $_POST['tags']; 
        // Convert JSON to PHP array
        $tagsArray = json_decode($data['tags'], true);
        // Extract only the "value" from each tag
        $tagsOnly = array_column($tagsArray, 'value');
        // Convert to comma-separated string
        $insert_data['task_tags'] = implode(',', $tagsOnly);
		}else{
		$insert_data['task_tags']=""; // insert old tags
		}
		
            
            // Set default values
            $insert_data['task_created'] = date('Y-m-d H:i:s');
            $insert_data['task_status'] = 1; // Default status
			$insert_data['task_addedby'] = get_staff_user_id();
            
            // Debug: Log the final data
            log_message('debug', 'Final insert data: ' . print_r($insert_data, true));
            
            // Add the task
            try {
                $id = $this->project_model->addtask($insert_data);
                
                if ($id) {
                    log_message('debug', 'Task added successfully with ID: ' . $id);
                    
                    // Handle file uploads using existing helper function
                    $uploaded_files = [];
                    if (!empty($_FILES['task_attachments']['name'][0])) {
                        $uploaded_files = handle_project_attachments_array($id, 'task_attachments');
                        
                        // Add attachments to database if files were uploaded
                        if ($uploaded_files && is_array($uploaded_files)) {
                            foreach ($uploaded_files as $file) {
                                $this->db->insert(db_prefix() . 'project_documents', [
                                    'project_id' => $data['project_id'],
									'task_id' => $id,
                                    'project_type' => 'task',
                                    'file_name' => $file['file_name'],
                                    'filetype' => $file['filetype'],
                                    'staffid' => get_staff_user_id(),
                                    'dateadded' => date('Y-m-d H:i:s'),
                                    'attachment_key' => app_generate_hash()
                                ]);
                            }
                        }
                    }
                    
                    $message = _l('added_successfully', _l('Task'));
                    if (!empty($uploaded_files)) {
                        $message .= ' with ' . count($uploaded_files) . ' attachment(s)';
                    }
                    
                    echo json_encode(['success' => true, 'id' => $id, 'message' => $message]);
                } else {
                    log_message('error', 'Failed to add Task. Database error: ' . $this->db->last_query());
                    echo json_encode(['success' => false, 'message' => _l('problem_adding', _l('Task'))]);
                }
            } catch (Exception $e) {
                log_message('error', 'Exception while adding Task: ' . $e->getMessage());
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            }
        } else {
            show_404();
        }
    }
	
	public function update_status() {
        if (!$this->input->is_ajax_request() || !$this->input->post('project_id') || !$this->input->post('status')) {
            echo json_encode(['success' => false]);
            return;
        }
        $project_id = $this->input->post('project_id');
        $status_id = $this->input->post('status');
        $this->load->model('project_model');
        $success = $this->project_model->change_project_status($project_id, $status_id);
        $color = null;
        if ($success) {
            $status = $this->project_model->get_proj_status($status_id);
            $color = $status ? $status->color : null;
        }
        echo json_encode(['success' => $success, 'color' => $color]);
    }
	
	public function update_owner() {
        if (!$this->input->is_ajax_request() || !$this->input->post('project_id') || !$this->input->post('owner')) {
            echo json_encode(['success' => false]);
            return;
        }
        $project_id = $this->input->post('project_id');
        $owner_id = $this->input->post('owner');
        $this->load->model('project_model');
        $success = $this->project_model->update(['owner' => $owner_id], $project_id);
        echo json_encode(['success' => $success]);
    }
	
	public function get_project() {
        $project_id = $this->input->get('project_id');
        if (!$project_id) {
            echo json_encode(['success' => false]);
            return;
        }
        $this->load->model('project_model');
        $data = $this->project_model->get($project_id);
        echo json_encode(['success' => $data ? true : false, 'data' => $data]);
    }
	
	public function updateproject() {
        if (!$this->input->post('project_id')) {
            echo json_encode(['success' => false, 'message' => 'No project ID provided.']);
            return;
        }
		
        $project_id = $this->input->post('project_id');
		$path = $this->input->post('path');
        $tagsJson = $this->input->post('edit_tags');
		
		if(isset($tagsJson)&&$tagsJson){
		//$tagsJson = $_POST['tags']; 
        // Convert JSON to PHP array
        $tagsArray = json_decode($tagsJson, true);
        // Extract only the "value" from each tag
        $tagsOnly = array_column($tagsArray, 'value');
        // Convert to comma-separated string
        $tags = implode(',', $tagsOnly);
		}else{
		$tags=""; // insert old tags
		}
		
       //log_message('error', 'Update Project - Received tags: ' . $tags); // Debug log
		
		
        $data = [
            'project_title' => $this->input->post('project_title'),
            'owner' => $this->input->post('owner'),
            'project_group' => $this->input->post('project_group'),
            'start_date' => $this->input->post('start_date'),
            'deadline' => $this->input->post('deadline'),
            'project_description' => $this->input->post('project_description'),
            'tags' => $tags,
            'make_this_a_strict_project' => $this->input->post('make_this_a_strict_project') ? 1 : 0,
            'project_access' => $this->input->post('project_access') ? $this->input->post('project_access') : 1,
        ];
		
		//log_message('error', 'Update Project - Received tags: ' . print_r($data, true)); // Debug log
        $this->load->model('project_model');
        $success = $this->project_model->update($data, $project_id);
		if($path==9){
		set_alert('success', _l('updated_successfully', _l('Project')));
        redirect(admin_url('project/view/'.$project_id));
		}
        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Project updated successfully.' : 'Failed to update project.'
        ]);
    }
	
	public function view($id = '') {
        if (!$id) {
            access_denied('Project View');
        }
        $this->load->model('project_model');
        $this->load->model('staff_model');
        $project = $this->project_model->get($id);
        if (!$project) {
            show_404();
        }
        $staff_members = $this->staff_model->get('', ['active' => 1]);
        $project_groups = $this->project_model->get_project_groups();
        $project_statuses = $this->project_model->get_project_statuses();
		$datalogs = $this->project_model->get_project_logs($id);
		$datacomments = $this->project_model->get_project_comments($id);
        // TODO: Load comments and activity stream if needed
        $data = [
            'project' => $project[0],
            'staff_members' => $staff_members,
            'project_groups' => $project_groups,
            'project_statuses' => $project_statuses,
			'datalogs' => $datalogs,
			'datacomments' => $datacomments,
            'title' => 'Project Details',
        ];
        $this->load->view('admin/project/view', $data);
    }
	
	public function update_task_status() {
        if (!$this->input->is_ajax_request() || !$this->input->post('task_id') || !$this->input->post('status')) {
            echo json_encode(['success' => false]);
            return;
        }
        $task_id = $this->input->post('task_id');
        $status_id = $this->input->post('status');
        $this->load->model('project_model');
        $success = $this->project_model->change_task_status($task_id, $status_id);
        $color = null;
        if ($success) {
            $status = $this->project_model->get_proj_status($status_id);
            $color = $status ? $status->color : null;
        }
        echo json_encode(['success' => $success, 'color' => $color]);
    }

	public function update_task_info() {
        if (!$this->input->is_ajax_request() || !$this->input->post('task_id')) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $task_id = $this->input->post('task_id');
		
		 $tagsJson = $this->input->post('task_tags');
		    // Decode into PHP array
			if(isset($tagsJson)&&$tagsJson){
    $tagsArray = json_decode($tagsJson, true);

    // Extract only "value"
    $tagsOnly = array_column($tagsArray, 'value');

    // Convert to comma-separated string
    $tagsString = implode(',', $tagsOnly);
	}else{
	$tagsString="";
	}	
	
	$task_reminder=$this->input->post('task_reminder');
	$reminder_daily_time=$this->input->post('reminder_daily_time');
	$reminder_on_date=$this->input->post('reminder_on_date');
	$reminder_on_time=$this->input->post('reminder_on_time');
	
	if(isset($task_reminder)&&$task_reminder=='none'){
	$reminder_daily_time='';
	$reminder_on_date='';
	$reminder_on_time='';
	}
		
        $data = [
            'task_owner' => $this->input->post('task_owner'),
            'task_start_date' => $this->input->post('task_start_date'),
            'task_end_date' => $this->input->post('task_end_date'),
            'task_status' => $this->input->post('task_status'),
            'task_priority' => $this->input->post('task_priority'),
			'task_name' => $this->input->post('task_name'),
			'task_progress' => $this->input->post('task_progress'),
			'task_tags' => $tagsString,
            'task_reminder' => $task_reminder,
            'reminder_daily_time' => $reminder_daily_time,
            'reminder_on_date' => $reminder_on_date,
            'reminder_on_time' => $reminder_on_time
        ];
		
		

        // Remove empty values
        $data = array_filter($data, function($value) {
            return $value !== '' && $value !== null;
        });

        if (empty($data)) {
            echo json_encode(['success' => false, 'message' => 'No data to update']);
            return;
        }

        $this->load->model('project_model');
        
        // Update the task
        $success = $this->project_model->update_task($data, $task_id);
        
        if ($success) {
            // Get updated status color for response
            $status_color = null;
            if (isset($data['task_status'])) {
                $status = $this->project_model->get_proj_status($data['task_status']);
                $status_color = $status ? $status->color : null;
            }
            
            echo json_encode([
                'success' => true, 
                'message' => 'Task updated successfully',
                'data' => ['status_color' => $status_color]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update task']);
        }
    }
	
	public function update_task_priority() {
        if (!$this->input->is_ajax_request() || !$this->input->post('task_id') || !$this->input->post('status')) {
            echo json_encode(['success' => false]);
            return;
        }
        $task_id = $this->input->post('task_id');
        $status_id = $this->input->post('status');
        $this->load->model('project_model');
        $success = $this->project_model->change_task_priority($task_id, $status_id);
        $color = null;
        if ($success) {
            $status = $this->project_model->get_task_priority_clr($status_id);
            $color = $status ? $status->color : null;
			//$color = null;
        }
        echo json_encode(['success' => $success, 'color' => $color]);
    }
	
	public function tasks_details($task_id = '')
    {
        if (!$task_id) {
            access_denied('Task View');
        }
        $this->load->model('project_model');
        $this->load->model('staff_model');
        $task = $this->project_model->get_task($task_id);
        if (!$task) {
            show_404();
        }
        $staff_members = $this->staff_model->get('', ['active' => 1]);
        $project_statuses = $this->project_model->get_project_statuses();
		$project_priority = $this->project_model->project_priority();
		//print_r($data['project_priority']);exit;
		$datalogs = $this->project_model->get_task_logs($task_id);
		
		//print_r($datalogs);
        $data = [
            'task' => $task,
            'staff_members' => $staff_members,
            'project_statuses' => $project_statuses,
			'project_priority' => $project_priority,
			'datalogs' => $datalogs,
            'title' => 'Task Details',
        ];
        $this->load->view('admin/project/task_details', $data);
    }
    
    public function addcomments()
    {
        if (!is_admin()) {
            //access_denied('Add Comment');
        }
        if (!$this->input->post()) {
            show_404();
        }
        $projectId = (int) $this->input->post('project_id');
        $taskId    = (int) $this->input->post('task_id');
        $comments  = trim($this->input->post('comments'));
        if (!$projectId || $comments === '') {
            echo json_encode(['success' => false, 'message' => 'Missing required data']);
            return;
        }
        $data = [
            'project_id' => $projectId,
            'task_id'    => $taskId ?: null,
            'comments'   => $comments,
            'addedon'    => date('Y-m-d H:i:s'),
            'addedby'    => get_staff_user_id(),
        ];
        $this->load->model('project_model');
        $id = $this->project_model->add_task_comment($data);
        if ($id) {
            echo json_encode(['success' => true, 'message' => 'Comment added']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
        }
    }

    public function getcomments()
    {
        if (!is_admin()) {
            //access_denied('Get Comments');
        }
        $taskId    = (int) $this->input->get('task_id');
        $projectId = (int) $this->input->get('project_id');
        if (!$projectId) {
            echo 'No comments found';
            return;
        }
        $this->load->model('project_model');
        $comments = $this->project_model->get_task_comments($projectId, $taskId ?: null);
        if (!$comments) {
            echo '<div class="text-muted">No comments yet.</div>';
            return;
        }
        // Render simple HTML list
       // echo '<ul class="list-unstyled">';
        foreach ($comments as $c) {
            $author = get_staff_full_name($c['addedby']);
            $date   = _dt($c['addedon']);
           
echo '<div>';

echo '<div class="media-body"><h5 class="media-heading tw-font-semibold tw-mb-0"><div class="btn-group pull-right mleft5"></div>';
echo staff_profile_image($c['addedby'], ['staff-profile-image-small',]);
echo '<span class="tw-px-2">'.$author.'</span></h5><div class="tw-text-sm text-danger" style="padding-left: 40px;">'.$date.'</div>';
echo '<div class="tw-my-2" style="padding-left: 40px;">'. $c['comments'].'</div></div></div>';

        }
    }
	
	
}
