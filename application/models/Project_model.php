<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = '')
    {
        if (is_numeric($id)) {
		
		$this->db->select('pm.*, pg.name as project_group_name, COUNT(pt.id) as total_tasks, COUNT(ps.id) as total_milestones, COUNT(pi.id) as total_issues');
        $this->db->from(db_prefix() . 'project_master pm');
        $this->db->join(db_prefix() . 'project_group pg', 'pg.id = pm.project_group', 'left');
		$this->db->join(db_prefix() . 'project_task pt', 'pt.project_id = pm.id', 'left');
		$this->db->join(db_prefix() . 'project_milestones ps', 'ps.project_id = pm.id', 'left');
		$this->db->join(db_prefix() . 'project_issues pi', 'pi.project_id = pm.id', 'left');
        $this->db->group_by('pm.id');
		$this->db->where('pm.id', $id);
        return $this->db->get()->result_array();
		
		//echo $this->db->last_query();exit;//
        }
        return null;
    }

    public function get_project_status()
    {
        return $this->db->get(db_prefix() . 'project_status')->result_array();
    }

    public function get_project_statuses()
    {
        $this->db->order_by('name', 'asc');
        return $this->db->get(db_prefix() . 'project_status')->result_array();
    }
	
	public function project_priority()
    {
	     $this->db->select('priorityid, name, color,');
        $this->db->order_by('name', 'asc');
        return $this->db->get(db_prefix() . 'project_priority')->result_array();
    }

    public function get_project_priorities()
    {
        $this->db->order_by('statusorder', 'asc');
        return $this->db->get(db_prefix() . 'project_priority')->result_array();
    }

    public function add_project_priority($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_project_priority_color', '#28B8DA');
        }

        if (!isset($data['statusorder']) || $data['statusorder'] === '') {
            $data['statusorder'] = total_rows(db_prefix() . 'project_priority') + 1;
        }

        $insert = [
            'name'        => $data['name'] ?? '',
            'color'       => $data['color'] ?? '#28B8DA',
            'status'      => isset($data['status']) ? (int)$data['status'] : 1,
            'statusorder' => (int)$data['statusorder'],
        ];

        $this->db->insert(db_prefix() . 'project_priority', $insert);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Project Priority Added [PriorityID: ' . $insert_id . ', Name: ' . $insert['name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_project_priority($data, $id)
    {
        $update = [];
        if (isset($data['name'])) $update['name'] = $data['name'];
        if (isset($data['color'])) $update['color'] = $data['color'];
        if (isset($data['status'])) $update['status'] = (int)$data['status'];
        if (isset($data['statusorder']) && $data['statusorder'] !== '') $update['statusorder'] = (int)$data['statusorder'];

        $this->db->where('priorityid', $id);
        $this->db->update(db_prefix() . 'project_priority', $update);
        if ($this->db->affected_rows() > 0) {
            log_activity('Project Priority Updated [PriorityID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete_project_priority($id)
    {
        $this->db->where('priorityid', $id);
        $this->db->delete(db_prefix() . 'project_priority');
        if ($this->db->affected_rows() > 0) {
            log_activity('Project Priority Deleted [PriorityID: ' . $id . ']');
            return true;
        }
        return false;
    }
	public function get_projectlist()
    {
	
	
	     $this->db->select('id,project_title,');
	    if(is_super()){
		if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		$this->db->where('company_id', $_SESSION['super_view_company_id']);	// Use condition
		}
		
		}elseif(is_admin()){
		$this->db->where('company_id', get_staff_company_id());
		}else{
		$this->db->where('owner', get_staff_user_id());
		}
        $this->db->order_by('project_title', 'asc');
       return $this->db->get(db_prefix() . 'project_master')->result_array();
	   //echo $this->db->last_query();exit;
	    //return 
    }
	
	public function get_project_list()
    {
        $this->db->select('pm.*, pg.name as project_group_name, COUNT(pt.id) as total_tasks, COUNT(ps.id) as total_milestones, COUNT(pi.id) as total_issues');
        $this->db->from(db_prefix() . 'project_master pm');
        $this->db->join(db_prefix() . 'project_group pg', 'pg.id = pm.project_group', 'left');
		$this->db->join(db_prefix() . 'project_task pt', 'pt.project_id = pm.id', 'left');
		$this->db->join(db_prefix() . 'project_milestones ps', 'ps.project_id = pm.id', 'left');
		$this->db->join(db_prefix() . 'project_issues pi', 'pi.project_id = pm.id', 'left');
        $this->db->group_by('pm.id');
        
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
		$this->db->where('pm.is_deleted', 0);
        $this->db->order_by('pm.id', 'desc');
		
		//echo $this->db->get_compiled_select(); 
		
        return $this->db->get()->result_array();
    }
	
		public function get_task_list($id)
    {
	  //echo $id;exit;
        $this->db->select('pt.*, pm.project_title as project_title');
        $this->db->from(db_prefix() . 'project_task pt');
        $this->db->join(db_prefix() . 'project_master pm', 'pt.project_id = pm.id', 'left');
		if(isset($id)&&$id){
		$this->db->where('pm.id', $id);
		}
        //echo $this->db->get_compiled_select(); exit;
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
		$this->db->where('pt.task_is_deleted', 0);
        $this->db->order_by('pt.id', 'desc');
        return $this->db->get()->result_array();
    }
	

    /*public function get_priority()
    {
        return $this->db->get(db_prefix() . 'project_priorities')->result_array();
    }

    public function get_services()
    {
        return $this->db->get(db_prefix() . 'project_services')->result_array();
    }*/

    public function get_project_groups()
    {
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('company_id', get_staff_company_id());
		}else{
		$this->db->where('company_id', get_staff_company_id());
		}
        $this->db->order_by('statusorder', 'asc');
        return $this->db->get(db_prefix() . 'project_group')->result_array();
    }

    public function get_projects_assignes_disctinct()
    {
        return $this->db->distinct()->select('assign')->where('assign IS NOT NULL')->get(db_prefix() . 'project_master')->result_array();
    }

    public function get_weekly_projects_opening_statistics()
    {
        $this->db->select('DATE(project_created) as date, COUNT(*) as total');
        $this->db->from(db_prefix() . 'project_master');
        $this->db->where('project_created >= DATE_SUB(NOW(), INTERVAL 7 DAY)');
        $this->db->group_by('DATE(project_created)');
        $this->db->order_by('date', 'ASC');
        
        $result = $this->db->get()->result_array();
        
        $data = [];
        foreach ($result as $row) {
            $data['labels'][] = _d($row['date']);
            $data['datasets'][0]['data'][] = $row['total'];
        }
        
        if (!isset($data['datasets'][0]['data'])) {
            $data['datasets'][0]['data'] = [];
        }
        
        return $data;
    }

    public function add($data, $staff_id = '')
    {
        if ($staff_id == '') {
            $staff_id = get_staff_user_id();
        }

        $data['project_created'] = date('Y-m-d H:i:s');
        $data['addedby'] = $staff_id;
        $data['company_id'] = get_staff_company_id();
		$data['ip']  = getClientIP();

        $this->db->insert(db_prefix() . 'project_master', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
		    
			$staffemail=get_staff_email($data['owner']);
			$mail_subject="Assign New Project # :".$insert_id." - ".$data['project_title'];
			$project_details=$mail_subject."<br><br>".$data['project_description'];
            $result1=send_mail_template('project_mail', $staffemail, $data['owner'], $insert_id, $project_details, $mail_subject);
			
			log_message('error', 'Result: ' . ($result1 ? 'Success' : 'Failed'));
			$CI =& get_instance();
			log_message('error', 'Debugger: ' . $CI->email->print_debugger());
			
			
			//echo "Result: " . ($result1 ? 'Success' : 'Failed') . "<br>\n";
//echo "Log: " . $this->email->print_debugger() . "<br>\n";exit;
			//echo "Result: " . ($result1 ? 'Success' : 'Failed') . "<br>\n";
//echo "Log: " . $CI->email->print_debugger() . "<br>\n";
			$project_type=1; //Project=1, Task=2, Issues=3, Milestone=4
			$this->log_project_activity($insert_id, $project_type, 'Added New Project');
            log_activity('New Project Added [ID: ' . $insert_id . ']');
			
            return $insert_id;
        }

        return false;
    } 
	
	
	
	public function addtask($data, $staff_id = '')
    {
        if ($staff_id == '') {
            $staff_id = get_staff_user_id();
        }

        $data['task_created'] = date('Y-m-d H:i:s');
		
        //$data['addedby'] = $staff_id;
        //$data['company_id'] = get_staff_company_id();
		$project_id = $data['project_id'];
        $this->db->insert(db_prefix() . 'project_task', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            $project_type=2; //Project=1, Task=2, Issues=3, Milestone=4
			$changes = [];
            foreach ($data as $field => $value) {
			    if($field=='owner'){
				$changes[] = ucwords(str_replace("_"," ",$field)) . ': ' . get_staff_full_name($value);
				}else{
                $changes[] = ucwords(str_replace("_"," ",$field)) . ': ' . $value;
				}
            }
			//===============================
			$task_owner=get_task_owner($insert_id);
			$emails=get_cc_mail_list($task_owner);
			$emailString = implode(',', $emails);	
			$mail_subject="Create New Task # :".$insert_id." - ".$data['task_name'];
            $data_desc=$mail_subject."<br><br>".implode(', ', $changes);
			$staffid=get_staff_user_id();
			$staffemail=get_staff_email($staffid);
			send_mail_template('project_mail', $staffemail, $staffid, $insert_id, $data_desc, $mail_subject,$emailString);
			//===============================
			$this->log_project_activity($project_id, $project_type, $mail_subject,'',$insert_id);
            log_activity('New Task Added [ID: ' . $insert_id . ']');
			
            return $insert_id;
        }

        return false;
    }

    public function update($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_master', $data);

        if ($this->db->affected_rows() > 0) {
		   
			$changes = [];
            foreach ($data as $field => $value) {
			    if($field=='owner'){
				$changes[] = ucwords(str_replace("_"," ",$field)) . ': ' . get_staff_full_name($value);
				}else{
                $changes[] = ucwords(str_replace("_"," ",$field)) . ': ' . $value;
				}
            }			
			$project_type=1; //Project=1, Task=2, Issues=3, Milestone=4
			//===========================
			$mail_subject="Updated Project # :".$id;
            $data_desc=$mail_subject."<br><br>".implode(', ', $changes);
			$staffid=get_staff_user_id();
			$staffemail=get_staff_email($staffid);
			send_mail_template('project_mail', $staffemail, $staffid, $id, $data_desc, $mail_subject);
			//===========================
			$this->log_project_activity($id, $project_type, $data_desc,'',$id);
            log_activity('Project Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    public function delete_project($id)
    {
	
	    
		
        $this->db->where('id', $id);
		$this->db->where('company_id', get_staff_company_id());
		
		if(!is_admin()){
		$this->db->where('owner', get_staff_user_id());
		}
		
        $this->db->update(db_prefix() . 'project_master', ['is_deleted' => 1]);
		
		
		//echo $this->db->last_query();exit;

        if ($this->db->affected_rows() > 0) {
		$this->db->where('project_id', $id);
		$this->db->update(db_prefix() . 'project_task', ['task_is_deleted' => 1]);
		$data_desc="Project Deleted # ".$id;
		$this->log_project_activity($id, 1, $data_desc,'',$id);
        return true;
        }

        return false;
    } 
	
	public function delete_task($id)
    {
		
        $this->db->where('id', $id);
		//$this->db->where('company_id', get_staff_company_id());
		
		if(!is_admin()){
		$this->db->where_in('task_owner', get_staff_user_id());
		}
		
        $this->db->update(db_prefix() . 'project_task', ['task_is_deleted' => 1]);
		
		
		//echo $this->db->last_query();exit;

        if ($this->db->affected_rows() > 0) {
		$data_desc="Task Deleted # ".$id;
		$this->log_project_activity($id, 1, $data_desc,'',$id);
        return true;
        }

        return false;
    }

    public function change_project_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_master', ['project_status' => $status]);
		
		if($status==10){
		$currdate=date("Y-m-d H:i:s");
        $this->db->update(db_prefix() . 'project_master', ['project_status' => $status, 'date_finished' => $currdate, 'progress' => 100 ]);
		}else{
		$this->db->update(db_prefix() . 'project_master', ['project_status' => $status, 'date_finished' => NULL ]);
		}
		

        if ($this->db->affected_rows() > 0) {
		
		if($status==10){
		$this->db->where('project_id', $id);
		$this->db->where('task_status <>', 10);
		$currdate=date("Y-m-d H:i:s");
        $this->db->update(db_prefix() . 'project_task', ['task_status' => 10, 'date_finished' => $currdate]);
		}
		
			$project_type=1; //Project=1, Task=2, Issues=3, Milestone=4
			//////////////////////////////EMAIL////////////////
			$mail_subject="Change Project Status - ".get_proj_statush($status)." - Project ID :".$id;
            $data_desc="Change Project Status - ".get_proj_statush($status);
			$staffid=get_staff_user_id();
			$staffemail=get_staff_email($staffid);
			send_mail_template('project_mail', $staffemail, $staffid, $id, $data_desc, $mail_subject);
			//////////////////////////////END EMAIL////////////
			$project_details="Change Project Status - ".get_proj_statush($status)." - Project ID :".$id;
			$this->log_project_activity($id, $project_type, $mail_subject,'',$id);
            log_activity('Project Status Changed [ID: ' . $id . ' Status: ' . $status . ']');
            return true;
        }

        return false;
    }

    public function change_project_priority($id, $priority)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_master', ['priority' => $priority]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Priority Changed [ID: ' . $id . ' Priority: ' . $priority . ']');
            return true;
        }

        return false;
    }

    public function change_project_service($id, $service)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_master', ['service' => $service]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Service Changed [ID: ' . $id . ' Service: ' . $service . ']');
            return true;
        }

        return false;
    }

    public function change_project_assign($id, $assign)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_master', ['assign' => $assign]);

        if ($this->db->affected_rows() > 0) {
            log_activity('Project Assign Changed [ID: ' . $id . ' Assign: ' . $assign . ']');
            return true;
        }

        return false;
    }

   /* public function get_predefined_replies()
    {
        return $this->db->get(db_prefix() . 'project_predefined_replies')->result_array();
    }

    public function get_predefined_reply($id)
    {
        $this->db->where('id', $id);
        return $this->db->get(db_prefix() . 'project_predefined_replies')->row_array();
    }

    public function add_predefined_reply($data)
    {
        $this->db->insert(db_prefix() . 'project_predefined_replies', $data);
        return $this->db->insert_id();
    }

    public function update_predefined_reply($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update(db_prefix() . 'project_predefined_replies', $data);
    }

    public function delete_predefined_reply($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete(db_prefix() . 'project_predefined_replies');
    }*/

    public function add_project_status($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_project_status_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows(db_prefix() . 'project_status') + 1;
        }
        $data['company_id'] = get_staff_company_id();
        $this->db->insert(db_prefix() . 'project_status', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Project Status Added [StatusID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_project_status($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_status', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Project Status Updated [StatusID: ' . $id . ', Name: ' . $data['name'] . ']');
            return true;
        }
        return false;
    }

    public function delete_project_status($id)
    {
        $this->db->where('id', $id);
        $this->db->where('company_id', get_staff_company_id());
        $this->db->delete(db_prefix() . 'project_status');
        if ($this->db->affected_rows() > 0) {
            log_activity('Project Status Deleted [StatusID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /*public function add_priority($data)
    {
        $this->db->insert(db_prefix() . 'project_priorities', $data);
        return $this->db->insert_id();
    }

    public function update_priority($data, $id)
    {
        $this->db->where('priorityid', $id);
        return $this->db->update(db_prefix() . 'project_priorities', $data);
    }

    public function delete_priority($id)
    {
        $this->db->where('priorityid', $id);
        return $this->db->delete(db_prefix() . 'project_priorities');
    }*/

   /* public function add_service($data)
    {
        $this->db->insert(db_prefix() . 'project_services', $data);
        return $this->db->insert_id();
    }

    public function update_service($data, $id)
    {
        $this->db->where('serviceid', $id);
        return $this->db->update(db_prefix() . 'project_services', $data);
    }

    public function delete_service($id)
    {
        $this->db->where('serviceid', $id);
        return $this->db->delete(db_prefix() . 'project_services');
    }

    public function get_service($id)
    {
        $this->db->where('serviceid', $id);
        return $this->db->get(db_prefix() . 'project_services')->row_array();
    }*/

    /*public function merge($primary_id, $status, $ids)
    {
        // Implementation for merging projects
        return true;
    }

    public function block_sender($sender)
    {
        // Implementation for blocking sender
        return true;
    }

    public function project_change_data($data)
    {
        // Implementation for project change data
        return true;
    }

    public function update_single_project_settings($data)
    {
        // Implementation for updating single project settings
        return true;
    }

    public function update_staff_replying($project_id, $staff_id)
    {
        // Implementation for updating staff replying
        return true;
    }

    public function get_staff_replying($project_id)
    {
        // Implementation for getting staff replying
        return null;
    }

    public function get_project_replies($project_id)
    {
        // Implementation for getting project replies
        return [];
    }

    public function get_project_attachments($project_id)
    {
        // Implementation for getting project attachments
        return [];
    }

    public function edit_message($data)
    {
        // Implementation for editing message
        return true;
    }

    public function delete_project_reply($project_id, $reply_id)
    {
        // Implementation for deleting project reply
        return true;
    }

    public function delete_attachment($id)
    {
        // Implementation for deleting attachment
        return true;
    }
*/


    public function add_project_group($data)
    {
        if (isset($data['color']) && $data['color'] == '') {
            $data['color'] = hooks()->apply_filters('default_project_group_color', '#757575');
        }

        if (!isset($data['statusorder'])) {
            $data['statusorder'] = total_rows(db_prefix() . 'project_group') + 1;
        }
        $data['company_id'] = get_staff_company_id();
        $this->db->insert(db_prefix() . 'project_group', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('New Project Group Added [GroupID: ' . $insert_id . ', Name: ' . $data['name'] . ']');
            return $insert_id;
        }
        return false;
    }

    public function update_project_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_group', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Project Group Updated [GroupID: ' . $id . ', Name: ' . $data['name'] . ']');
            return true;
        }
        return false;
    }

    public function delete_project_group($id)
    {
        $this->db->where('id', $id);
        $this->db->where('company_id', get_staff_company_id());
        $this->db->delete(db_prefix() . 'project_group');
        if ($this->db->affected_rows() > 0) {
            log_activity('Project Group Deleted [GroupID: ' . $id . ']');
            return true;
        }
        return false;
    }
	
	 public function get_proj_status($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);

            return $this->db->get(db_prefix() . 'project_status')->row();
        }
        $this->db->order_by('statusorder', 'asc');

        return $this->db->get(db_prefix() . 'project_status')->result_array();
    }
	
	 public function get_task_priority_clr($id = '')
    {
        if (is_numeric($id)) {
            $this->db->where('priorityid', $id);

            return $this->db->get(db_prefix() . 'project_priority')->row();
        }
        $this->db->order_by('statusorder', 'asc');

        return $this->db->get(db_prefix() . 'project_priority')->result_array();
    }
	
	
	/**
     * Add lead activity from staff
     * @param  mixed  $id          lead id
     * @param  string  $description activity description
	 * $project_type= //Project=1, Task=2, Issues=3, Milestone=4
     */
    public function log_project_activity($project_id, $project_type, $description, $integration = false, $additional_data = '')
    {
        $log = [
            'date'            => date('Y-m-d H:i:s'),
            'description'     => $description,
            'project_id'      => $project_id,
			'project_type'    => $project_type,
            'staffid'         => get_staff_user_id(),
			'company_id'      => get_staff_company_id(),
            'additional_data' => $additional_data,
            'full_name'       => get_staff_full_name(get_staff_user_id()),
        ];
        if ($integration == true) {
            $log['staffid']   = 0;
            $log['full_name'] = '[CRON]';
        }

        $this->db->insert(db_prefix() . 'project_activity_log', $log);

        return $this->db->insert_id();
    }

    public function change_task_status($id, $status)
    {
        $this->db->where('id', $id);
		if($status==10){
		$currdate=date("Y-m-d H:i:s");
        $this->db->update(db_prefix() . 'project_task', ['task_status' => $status, 'date_finished' => $currdate, 'task_progress' => 100 ]);
		}else{
		$this->db->update(db_prefix() . 'project_task', ['task_status' => $status, 'date_finished' => NULL ]);
		}
		
		if ($this->db->affected_rows() > 0) {
		    $project_type=2; //Project=1, Task=2, Issues=3, Milestone=4
			$project_details="Change Task Status : ".get_proj_statush($status)." Task ID :".$id;
			//////////////////////////////EMAIL////////////////
			$task_owner=get_task_owner($id);
			$emails=get_cc_mail_list($task_owner);
			$emailString = implode(',', $emails);	
			$mail_subject="Updated Task Status # :".$id." - ".get_proj_statush($status);
            $data_desc="Updated Task Status - ".get_proj_statush($status);
			$staffid=get_staff_user_id();
			$staffemail=get_staff_email($staffid);
			send_mail_template('project_mail', $staffemail, $staffid, $id, $data_desc, $mail_subject,$emailString);
			//////////////////////////////END EMAIL////////////
			$this->log_project_activity('', $project_type, $mail_subject,'',$id);
            log_activity('Change Task Status [ID: ' . $id . ']');
			return true;
        }
		return false;
    }

    public function update_task($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_task', $data);

        if ($this->db->affected_rows() > 0) {
		if($data['task_progress']==100 && $data['task_status']<>10){
		$this->db->where('id', $id);
		$currdate=date("Y-m-d H:i:s");
        $this->db->update(db_prefix() . 'project_task', ['task_status' => 10, 'date_finished' => $currdate]);
		}
		
            // Log the activity
            $project_type = 2; // Task
            $changes = [];
            foreach ($data as $field => $value) {
                $changes[] = ucwords(str_replace("_"," ",$field)) . ': ' . $value;
            }			
			$project_type=2; //Project=1, Task=2, Issues=3, Milestone=4
			
            
		
			//////////////////////////////////////////////
	        $task_owner=get_task_owner($id);
			$emails=get_cc_mail_list($task_owner);
			$emailString = implode(',', $emails);	
			$mail_subject="Updated Task # :".$id;
            $data_desc=$mail_subject."<br><br>".implode(', ', $changes);
			$staffid=get_staff_user_id();
			$staffemail=get_staff_email($staffid);
			send_mail_template('project_mail', $staffemail, $staffid, $id, $data_desc, $mail_subject,$emailString);
            /////////////////////////////////////////
            // Get project ID for logging
            $task = $this->get_task($id);
            $project_id = $task ? $task['project_id'] : '';
            
            $this->log_project_activity($project_id, $project_type, $data_desc, '', $id);
            log_activity('Task Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }
	
	public function change_task_priority($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'project_task', ['task_priority' => $status]);
		
		    $project_type=2; //Project=1, Task=2, Issues=3, Milestone=4
			//$status = get_proj_status($status);
			$project_details="Change Task Priority : ".get_project_priority($status)." Task ID :".$id;
			$this->log_project_activity('', $project_type, $project_details,'',$id);
            log_activity('Change Task Priority [ID: ' . $id . ']');
        return $this->db->affected_rows() > 0;
    }

    public function get_task($id = '')
    {
        if (is_numeric($id)) {
            $this->db->select('pt.*, pm.project_title as project_title');
            $this->db->from(db_prefix() . 'project_task pt');
            $this->db->join(db_prefix() . 'project_master pm', 'pt.project_id = pm.id', 'left');
            $this->db->where('pt.id', $id);
            return $this->db->get()->row_array();
        }
        return null;
    }

    public function add_task_comment(array $data)
    {
	    $project_id = $data['project_id'];
		$task_id = $data['task_id'];
        $this->db->insert(db_prefix() . 'project_comments', $data);
		$insert_id = $this->db->insert_id();
		if ($insert_id) {
            $project_type=2; //Project=1, Task=2, Issues=3, Milestone=4

            //////////////////////////////////////////////
	        $task_owner=get_task_owner($task_id);
			$emails=get_cc_mail_list($task_owner);
			$emailString = implode(',', $emails);	
			$mail_subject="Add New Comment # :".$insert_id;
            $data_desc=$mail_subject."<br><br>".$data['comments'];
			$staffid=get_staff_user_id();
			$staffemail=get_staff_email($staffid);
			send_mail_template('project_mail', $staffemail, $staffid, $insert_id, $data_desc, $mail_subject,$emailString);
            /////////////////////////////////////////
	
			$this->log_project_activity($project_id, $project_type, $data_desc,'',$task_id);
            log_activity('Add New Comment with [ID: ' . $insert_id . ']');
		}	
			
        return $this->db->insert_id();
    }

    public function get_task_comments(int $projectId, ?int $taskId = null)
    {
        $this->db->from(db_prefix() . 'project_comments');
        $this->db->where('project_id', $projectId);
        if (!empty($taskId)) {
            $this->db->where('task_id', $taskId);
        }
        $this->db->order_by('addedon', 'DESC');
        return $this->db->get()->result_array();
    }

    // Dashboard Methods
    public function get_dashboard_stats()
    {
        $stats = [];
        
        // Total projects
        $this->db->select('COUNT(*) as total');
		
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('company_id', get_staff_company_id());
		}else{
		$this->db->where('owner', get_staff_user_id());
		}
        $stats['total_projects'] = $this->db->get(db_prefix() . 'project_master')->row()->total;
       
        // Active projects
        $this->db->select('COUNT(*) as total');
        $this->db->where('project_status !=', 10); // Assuming 10 is completed
        if(is_super()){
            //$this->db->where('company_id', get_staff_company_id());
			
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('company_id', get_staff_company_id());
		}else{
		$this->db->where('owner', get_staff_user_id());
		}
        $stats['active_projects'] = $this->db->get(db_prefix() . 'project_master')->row()->total;
         
        // Total tasks
        $this->db->select('COUNT(*) as total');
        $this->db->from(db_prefix() . 'project_task pt');
        $this->db->join(db_prefix() . 'project_master pm', 'pt.project_id = pm.id');
       
		
		if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
		
        $stats['total_tasks'] = $this->db->get()->row()->total;
        //echo $this->db->last_query();exit;
        // Completed tasks
        $this->db->select('COUNT(*) as total');
        $this->db->from(db_prefix() . 'project_task pt');
        $this->db->join(db_prefix() . 'project_master pm', 'pt.project_id = pm.id');
        $this->db->where('pt.task_status', 10); // Assuming 10 is completed
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
        $stats['completed_tasks'] = $this->db->get()->row()->total;
        
        // Calculate completion percentage
        $stats['task_completion_rate'] = $stats['total_tasks'] > 0 ? round(($stats['completed_tasks'] / $stats['total_tasks']) * 100, 1) : 0;
        
        return $stats;
    }

    public function get_latest_projects($limit = 5)
    {
        $this->db->select('pm.*, pg.name as project_group_name, ps.name as status_name, ps.color as status_color');
        $this->db->from(db_prefix() . 'project_master pm');
        $this->db->join(db_prefix() . 'project_group pg', 'pg.id = pm.project_group', 'left');
        $this->db->join(db_prefix() . 'project_status ps', 'ps.id = pm.project_status', 'left');
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
        $this->db->order_by('pm.project_created', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_latest_tasks($limit = 5)
    {
        $this->db->select('pt.*, pm.project_title, ps.name as status_name, ps.color as status_color');
        $this->db->from(db_prefix() . 'project_task pt');
        $this->db->join(db_prefix() . 'project_master pm', 'pt.project_id = pm.id');
        $this->db->join(db_prefix() . 'project_status ps', 'ps.id = pt.task_status', 'left');
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
        $this->db->order_by('pt.task_created', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_project_status_chart_data()
    {
        $this->db->select('ps.name, ps.color, COUNT(pm.id) as count');
        $this->db->from(db_prefix() . 'project_status ps');
        $this->db->join(db_prefix() . 'project_master pm', 'pm.project_status = ps.id', 'left');
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
        $this->db->group_by('ps.id');
        $this->db->order_by('ps.statusorder');
        return $this->db->get()->result_array();
    }

    public function get_task_status_chart_data()
    {
        $this->db->select('ps.name, ps.color, COUNT(pt.id) as count');
        $this->db->from(db_prefix() . 'project_status ps');
        $this->db->join(db_prefix() . 'project_task pt', 'pt.task_status = ps.id', 'left');
        $this->db->join(db_prefix() . 'project_master pm', 'pt.project_id = pm.id');
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pm.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pm.company_id', get_staff_company_id());
		}else{
		$this->db->where('pm.owner', get_staff_user_id());
		}
        $this->db->group_by('ps.id');
        $this->db->order_by('ps.statusorder');
        return $this->db->get()->result_array();
    }

    public function get_monthly_projects_chart()
    {
        $this->db->select('DATE_FORMAT(project_created, "%Y-%m") as month, COUNT(*) as count');
        $this->db->from(db_prefix() . 'project_master');
        $this->db->where('project_created >= DATE_SUB(NOW(), INTERVAL 12 MONTH)');
       
	    if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('company_id', get_staff_company_id());
		}else{
		$this->db->where('owner', get_staff_user_id());
		}
		
        $this->db->group_by('month');
        $this->db->order_by('month', 'ASC');
        return $this->db->get()->result_array();
    }
	
	public function get_task_logs($task_id)
    {
	    $this->db->where('project_type', 2);
		$this->db->where('additional_data', $task_id);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'project_activity_log')->result_array();
    }
	
	public function get_project_logs($project_id)
    {
		$this->db->where('project_id', $project_id);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'project_activity_log')->result_array();
    }
	public function get_project_comments($project_id)
    {
		$this->db->where('project_id', $project_id);
        $this->db->order_by('id', 'desc');
        return $this->db->get(db_prefix() . 'project_comments')->result_array();
    }

    // Collaboration Methods
    public function get_activity_logs($limit = 50)
    {
        $this->db->select('pal.*, pm.project_title, pg.name as project_group_name');
        $this->db->from(db_prefix() . 'project_activity_log pal');
        $this->db->join(db_prefix() . 'project_master pm', 'pm.id = pal.project_id', 'left');
        $this->db->join(db_prefix() . 'project_group pg', 'pg.id = pm.project_group', 'left');
       
		if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pal.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pal.company_id', get_staff_company_id());
		}else{
		$this->db->where('pal.owner', get_staff_user_id());
		}
		
        $this->db->order_by('pal.date', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function get_calendar_events()
    {
        $this->db->select('pal.*, pm.project_title, pm.project_status');
        $this->db->from(db_prefix() . 'project_activity_log pal');
        $this->db->join(db_prefix() . 'project_master pm', 'pm.id = pal.project_id', 'left');
        if(is_super()){
			if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		    $this->db->where('pal.company_id', $_SESSION['super_view_company_id']);	// Use condition
		    }
		
		}elseif(is_admin()){
		$this->db->where('pal.company_id', get_staff_company_id());
		}else{
		$this->db->where('pal.owner', get_staff_user_id());
		}
        $this->db->order_by('pal.date', 'ASC');
        $logs = $this->db->get()->result_array();
        
        $events = [];
        foreach ($logs as $log) {
            $color = $this->get_event_color($log['project_type']);
            $events[] = [
                'id' => $log['id'],
                'title' => $log['description'],
                'start' => $log['date'],
                'end' => $log['date'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'project_title' => $log['project_title'] ?? 'N/A',
                    'staff_name' => $log['full_name'] ?? 'System',
                    'project_type' => $this->get_project_type_name($log['project_type']),
                    'additional_data' => $log['additional_data'] ?? ''
                ]
            ];
        }
        
        return $events;
    }

    private function get_event_color($project_type)
    {
        switch ($project_type) {
            case 1: return '#007bff'; // Project
            case 2: return '#28a745'; // Task
            case 3: return '#dc3545'; // Issues
            case 4: return '#ffc107'; // Milestone
            default: return '#6c757d';
        }
    }

    private function get_project_type_name($project_type)
    {
        switch ($project_type) {
            case 1: return 'Project';
            case 2: return 'Task';
            case 3: return 'Issue';
            case 4: return 'Milestone';
            default: return 'Activity';
        }
    }
}
