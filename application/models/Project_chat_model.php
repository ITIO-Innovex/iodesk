<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project_chat_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Get all conversations with project and participant info
    public function get_conversations()
    {
        $this->db->select('pc.*, p.project_title as project_name, s.firstname, s.lastname');
        $this->db->from(db_prefix() . 'project_conversations pc');
        $this->db->join(db_prefix() . 'project_master p', 'p.id = pc.project_id', 'left');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = pc.created_by', 'left');
        $this->db->where('p.company_id', get_staff_company_id());
        $this->db->where('p.is_deleted', 0);
        $this->db->order_by('pc.last_activity', 'DESC');
        $conversations = $this->db->get()->result_array();

        // Get participants for each conversation
        foreach ($conversations as &$conversation) {
            $conversation['participants'] = $this->get_conversation_participants($conversation['id']);
        }

        return $conversations;
    }

    // Get single conversation
    public function get_conversation($conversation_id)
    {
        $this->db->select('pc.*, p.project_title as project_name');
        $this->db->from(db_prefix() . 'project_conversations pc');
        $this->db->join(db_prefix() . 'project_master p', 'p.id = pc.project_id', 'left');
        $this->db->where('pc.id', $conversation_id);
        $this->db->where('p.company_id', get_staff_company_id());
        $this->db->where('p.is_deleted', 0);
        return $this->db->get()->row_array();
    }

    // Create new conversation
    public function create_conversation($data)
    {
        $this->db->insert(db_prefix() . 'project_conversations', $data);
        return $this->db->insert_id();
    }

    // Add participants to conversation
    public function add_participants($conversation_id, $participants)
    {
        foreach ($participants as $staff_id) {
            $participant_data = [
                'conversation_id' => $conversation_id,
                'staff_id' => $staff_id,
                'joined_at' => date('Y-m-d H:i:s')
            ];
            $this->db->insert(db_prefix() . 'conversation_participants', $participant_data);
        }
        return true;
    }

    // Get conversation participants
    public function get_conversation_participants($conversation_id)
    {
        $this->db->select('cp.*, s.firstname, s.lastname, s.email');
        $this->db->from(db_prefix() . 'conversation_participants cp');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = cp.staff_id');
        $this->db->where('cp.conversation_id', $conversation_id);
        return $this->db->get()->result_array();
    }

    // Get messages for conversation
    public function get_messages($conversation_id, $limit = 50)
    {
        $this->db->select('cm.*, s.firstname, s.lastname');
        $this->db->from(db_prefix() . 'chat_messages cm');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = cm.sender_id');
        $this->db->where('cm.conversation_id', $conversation_id);
        $this->db->order_by('cm.sent_at', 'ASC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    // Get new messages after specific message ID
    public function get_new_messages($conversation_id, $last_message_id)
    {
        $this->db->select('cm.*, s.firstname, s.lastname');
        $this->db->from(db_prefix() . 'chat_messages cm');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = cm.sender_id');
        $this->db->where('cm.conversation_id', $conversation_id);
        $this->db->where('cm.id >', $last_message_id);
        $this->db->order_by('cm.sent_at', 'ASC');
        return $this->db->get()->result_array();
    }

    // Send message
    public function send_message($data)
    {
        $this->db->insert(db_prefix() . 'chat_messages', $data);
        return $this->db->insert_id();
    }

    // Get single message with sender info
    public function get_message($message_id)
    {
        $this->db->select('cm.*, s.firstname, s.lastname');
        $this->db->from(db_prefix() . 'chat_messages cm');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = cm.sender_id');
        $this->db->where('cm.id', $message_id);
        return $this->db->get()->row_array();
    }

    // Update conversation last activity
    public function update_conversation_activity($conversation_id)
    {
        $this->db->where('id', $conversation_id);
        $this->db->update(db_prefix() . 'project_conversations', [
            'last_activity' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $this->db->affected_rows() > 0;
    }

    // Update conversation
    public function update_conversation($conversation_id, $data)
    {
        // Debug logging
        log_message('debug', 'Update Conversation ID: ' . $conversation_id);
        log_message('debug', 'Update Data: ' . print_r($data, true));
        
        // Check if conversation exists first
        $this->db->where('id', $conversation_id);
        $existing = $this->db->get(db_prefix() . 'project_conversations')->row_array();
        log_message('debug', 'Existing conversation: ' . print_r($existing, true));
        
        if (!$existing) {
            log_message('error', 'Conversation ID ' . $conversation_id . ' not found');
            return false;
        }
        
        $this->db->where('id', $conversation_id);
        $this->db->update(db_prefix() . 'project_conversations', $data);
        
        // Debug query and result
        log_message('debug', 'Update Query: ' . $this->db->last_query());
        log_message('debug', 'Affected Rows: ' . $this->db->affected_rows());
        log_message('debug', 'DB Error: ' . print_r($this->db->error(), true));
        
        return $this->db->affected_rows() > 0;
    }

    // Remove participants from conversation
    public function remove_participants($conversation_id, $participants = null)
    {
        $this->db->where('conversation_id', $conversation_id);
        if ($participants) {
            $this->db->where_in('staff_id', $participants);
        }
        $this->db->delete(db_prefix() . 'conversation_participants');
        return $this->db->affected_rows() > 0;
    }

    // Delete conversation and related data
    public function delete_conversation($conversation_id)
    {
        // Delete messages
        $this->db->where('conversation_id', $conversation_id);
        $this->db->delete(db_prefix() . 'chat_messages');

        // Delete participants
        $this->db->where('conversation_id', $conversation_id);
        $this->db->delete(db_prefix() . 'conversation_participants');

        // Delete conversation
        $this->db->where('id', $conversation_id);
        $this->db->delete(db_prefix() . 'project_conversations');

        return $this->db->affected_rows() > 0;
    }

    // Get all projects for dropdown
    public function get_projects()
    {
        $this->db->select('id, project_title as name');
        $this->db->from(db_prefix() . 'project_master');
        $this->db->where('company_id', get_staff_company_id());
		$this->db->where('is_deleted', 0); // Active projects only
        $this->db->order_by('project_title', 'ASC');
        return $this->db->get()->result_array();
    }

    // Get all staff members for participant selection
    public function get_staff_members()
    {
        $this->db->select('staffid as id, firstname, lastname, email');
        $this->db->from(db_prefix() . 'staff');
        $this->db->where('active', 1);
        $this->db->order_by('firstname', 'ASC');
        return $this->db->get()->result_array();
    }

    // Mark message as read
    public function mark_message_read($message_id, $staff_id)
    {
        $read_data = [
            'message_id' => $message_id,
            'staff_id' => $staff_id,
            'read_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->replace(db_prefix() . 'message_read_status', $read_data);
        return $this->db->affected_rows() > 0;
    }

    // Get unread message count for user
    public function get_unread_count($staff_id, $conversation_id = null)
    {
        $this->db->select('COUNT(*) as unread_count');
        $this->db->from(db_prefix() . 'chat_messages cm');
        $this->db->join(db_prefix() . 'conversation_participants cp', 'cp.conversation_id = cm.conversation_id');
        $this->db->join(db_prefix() . 'message_read_status mrs', 'mrs.message_id = cm.id AND mrs.staff_id = ' . $staff_id, 'left');
        $this->db->where('cp.staff_id', $staff_id);
        $this->db->where('cm.sender_id !=', $staff_id);
        $this->db->where('mrs.id IS NULL');
        
        if ($conversation_id) {
            $this->db->where('cm.conversation_id', $conversation_id);
        }
        
        $result = $this->db->get()->row_array();
        return $result['unread_count'];
    }
}
