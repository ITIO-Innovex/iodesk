<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_chat extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('project_chat_model');
        $this->load->library('form_validation');
        if (!is_admin() && !staff_can('project_chat', 'project')) {
            access_denied('Project Chat');
        }
    }

    // Main chat listing page
    public function index()
    {
        $data['title'] = 'Project Chat';
        $data['conversations'] = $this->project_chat_model->get_conversations();
        $this->load->view('admin/project_chat/manage', $data);
    }

    // Create new conversation
    public function new_conversation()
    {
	
	echo $this->input->post('project_id');
        if ($this->input->post()) {
            $this->form_validation->set_rules('project_id', 'Project', 'required');
            $this->form_validation->set_rules('title', 'Conversation Title', 'required');
            $this->form_validation->set_rules('participants[]', 'Participants', 'required');

            if ($this->form_validation->run() == TRUE) {
                $conversation_data = [
                    'project_id' => $this->input->post('project_id'),
                    'title' => $this->input->post('title'),
                    'created_by' => get_staff_user_id(),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $conversation_id = $this->project_chat_model->create_conversation($conversation_data);
                
                if ($conversation_id) {
                    // Add participants
                    $participants = $this->input->post('participants');
                    $this->project_chat_model->add_participants($conversation_id, $participants);
                    
                    set_alert('success', 'Conversation created successfully');
                    redirect(admin_url('project_chat/chatbox/' . $conversation_id));
                } else {
                    set_alert('danger', 'Failed to create conversation');
                }
            } else {
                set_alert('danger', validation_errors());
            }
        }

        $data['title'] = 'New Conversation';
        $data['projects'] = $this->project_chat_model->get_projects();
        //$data['staff'] = $this->project_chat_model->get_staff_members();
		//print_r($data['staff']);exit;
		
		$project_id = 26;

$sql = "
SELECT 
GROUP_CONCAT(DISTINCT val ORDER BY val) AS all_ids
FROM (
    
    SELECT a.owner AS val
    FROM it_crm_project_master a
    WHERE a.id = ?
    
    UNION ALL
    
    SELECT a.addedby
    FROM it_crm_project_master a
    WHERE a.id = ?
    
    UNION ALL
    
    SELECT TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(b.task_owner, ',', numbers.n), ',', -1)) AS val
    FROM it_crm_project_task b
    JOIN (
        SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
    ) numbers
    ON CHAR_LENGTH(b.task_owner) 
       - CHAR_LENGTH(REPLACE(b.task_owner, ',', '')) >= numbers.n - 1
    WHERE b.project_id = ?
    
    UNION ALL
    
    SELECT b.task_addedby
    FROM it_crm_project_task b
    WHERE b.project_id = ?

) AS combined
";

$query = $this->db->query($sql, array(
    $project_id,
    $project_id,
    $project_id,
    $project_id
));

$result = $query->row();
$all_ids = $result->all_ids;

//echo $all_ids;//exit;

		$data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        $this->load->view('admin/project_chat/new_conversation', $data);
    }

    // Chatbox view for specific conversation
    public function chatbox($conversation_id = '')
    {
        if (empty($conversation_id)) {
            redirect(admin_url('project_chat'));
        }

        $conversation = $this->project_chat_model->get_conversation($conversation_id);
        if (!$conversation) {
            show_404();
        }

        $data['title'] = 'Chat: ' . $conversation['title'];
        $data['conversation'] = $conversation;
        $data['participants'] = $this->project_chat_model->get_conversation_participants($conversation_id);
        $data['messages'] = $this->project_chat_model->get_messages($conversation_id);
        $this->load->view('admin/project_chat/chatbox', $data);
    }

    // Send message via AJAX
    public function send_message()
    {
        if (!$this->input->post()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $conversation_id = $this->input->post('conversation_id');
        $message = $this->input->post('message');

        if (empty($conversation_id) || empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        $message_data = [
            'conversation_id' => $conversation_id,
            'sender_id' => get_staff_user_id(),
            'message' => $message,
            'sent_at' => date('Y-m-d H:i:s')
        ];

        $message_id = $this->project_chat_model->send_message($message_data);

        if ($message_id) {
            // Update conversation last activity
            $this->project_chat_model->update_conversation_activity($conversation_id);
            
            // Get message with sender info for response
            $new_message = $this->project_chat_model->get_message($message_id);
            
            echo json_encode([
                'success' => true,
                'message' => $new_message,
                'html' => $this->load->view('admin/project_chat/message_item', ['message' => $new_message], true)
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send message']);
        }
    }

    // Get new messages via AJAX (for polling)
    public function get_messages($conversation_id, $last_message_id = 0)
    {
        $messages = $this->project_chat_model->get_new_messages($conversation_id, $last_message_id);
        
        $html = '';
        foreach ($messages as $message) {
            $html .= $this->load->view('admin/project_chat/message_item', ['message' => $message], true);
        }

        echo json_encode([
            'success' => true,
            'messages' => $messages,
            'html' => $html,
            'last_message_id' => !empty($messages) ? end($messages)['id'] : $last_message_id
        ]);
    }

    // Edit conversation
    public function edit_conversation($conversation_id = null)
    {
        if (!is_admin()) {
            access_denied('project_chat');
        }

        if (!$conversation_id) {
            redirect(admin_url('project_chat'));
        }

        $data['conversation'] = $this->project_chat_model->get_conversation($conversation_id);
        if (!$data['conversation']) {
            set_alert('danger', 'Conversation not found');
            redirect(admin_url('project_chat'));
        }

		
$leadOriginal= $this->db->select('project_id')->where('id', $conversation_id)->get(db_prefix() .'project_conversations')->row();
$data['chat_project_id'] = $leadOriginal->project_id;

///////////get staff list////////////


////////////////////////////////////


        if ($this->input->post()) {
            $post_data = $this->input->post();
            
            // Skip manual CSRF validation - CodeIgniter handles this automatically
            // if CSRF protection is enabled in config

            // Validation
            $this->form_validation->set_rules('title', 'Conversation Title', 'required|max_length[255]');
            $this->form_validation->set_rules('project_id', 'Project', 'required|numeric');
            $this->form_validation->set_rules('participants[]', 'Participants', 'required');

            if ($this->form_validation->run() == TRUE) {
                // Update conversation
                $conversation_data = [
                    'title' => $this->input->post('title'),
                    'project_id' => $this->input->post('project_id'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];


                $update_result = $this->project_chat_model->update_conversation($conversation_id, $conversation_data);

                if ($update_result) {
                    // Update participants - remove all and add new ones
                    $this->project_chat_model->remove_participants($conversation_id);
                    $participants = $this->input->post('participants');
                    $this->project_chat_model->add_participants($conversation_id, $participants);

                    set_alert('success', 'Conversation updated successfully');
                    redirect(admin_url('project_chat'));
                } else {
                    set_alert('danger', 'Failed to update conversation.');
                }
            } else {
                set_alert('danger', validation_errors());
            }
        }

        $data['projects'] = $this->project_chat_model->get_projects();
        //$data['staff_members'] = $this->project_chat_model->get_staff_members();
		
		///////////get staff list////////////
		$project_id=$data['chat_project_id'];
         if(empty($project_id)){
            echo json_encode([
                'status' => false,
                'message' => 'Project ID is required'
            ]);
            return;
        }

        // Get project + task related staff
        $this->db->select('a.owner, a.addedby, b.task_owner, b.task_addedby');
        $this->db->from('it_crm_project_master a');
        $this->db->join('it_crm_project_task b', 'a.id = b.project_id', 'left');
        $this->db->where('a.id', $project_id);

        $query = $this->db->get();
        $result = $query->result();

        $ids = [];

        foreach ($result as $row) {

            $ids[] = $row->owner;
            $ids[] = $row->addedby;
            $ids[] = $row->task_addedby;

            if (!empty($row->task_owner)) {
                $ids = array_merge($ids, explode(',', $row->task_owner));
            }
        }

        $ids = array_filter($ids);
        $ids = array_unique($ids);
		$final_ids = implode(',', $ids);
		log_message('error', 'Project ID: ' . print_r($final_ids , true));
		//$final_ids = implode(',', $ids);

$id_array = explode(',', $final_ids);
$this->db->select('firstname, lastname, staffid');
$this->db->where('active', 1);
$this->db->where_in('staffid', $id_array);
$data['staff_members'] = $this->db->get(db_prefix().'staff')->result_array();
//print_r($data['staff_members']);
        //$data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        ////////////////////////////////////

		
        $data['current_participants'] = $this->project_chat_model->get_conversation_participants($conversation_id);
        $data['title'] = 'Edit Conversation';

        $this->load->view('admin/project_chat/edit_conversation', $data);
    }

    // Delete conversation
    public function delete_conversation($conversation_id)
    {
        if (!is_admin()) {
            access_denied('project_chat');
        }

        $result = $this->project_chat_model->delete_conversation($conversation_id);
        
        if ($result) {
            set_alert('success', 'Conversation deleted successfully');
        } else {
            set_alert('danger', 'Failed to delete conversation');
        }

        redirect(admin_url('project_chat'));
    }

    // Get staff members for AJAX
    public function get_staff_ajax()
    {
        $staff = $this->project_chat_model->get_staff_members();
        echo json_encode($staff);
    }
	
	public function group_staff(){
	
	
	 $project_id = $this->input->post('project_id');
	 log_message('error', 'Project ID: ' . $project_id);

        if(empty($project_id)){
            echo json_encode([
                'status' => false,
                'message' => 'Project ID is required'
            ]);
            return;
        }

        // Get project + task related staff
        $this->db->select('a.owner, a.addedby, b.task_owner, b.task_addedby');
        $this->db->from('it_crm_project_master a');
        $this->db->join('it_crm_project_task b', 'a.id = b.project_id', 'left');
        $this->db->where('a.id', $project_id);

        $query = $this->db->get();
        $result = $query->result();

        $ids = [];

        foreach ($result as $row) {

            $ids[] = $row->owner;
            $ids[] = $row->addedby;
            $ids[] = $row->task_addedby;

            if (!empty($row->task_owner)) {
                $ids = array_merge($ids, explode(',', $row->task_owner));
            }
        }

        $ids = array_filter($ids);
        $ids = array_unique($ids);

        $final_ids = $ids;
		log_message('error', 'Project ID: ' . print_r($final_ids , true));
		//$final_ids = implode(',', $ids);
		
		foreach ($ids as $id) {

        $staff_list[] = [
            'id'   => $id,
            'name' => get_staff_full_name($id)
        ];
        }
	//log_message('error', 'Project ID: ' . print_r($staff_list , true));

        echo json_encode([
            'status' => true,
            'staff_ids' => $staff_list
        ]);
    }
	
	
}
