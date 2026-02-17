<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Support_maintenance_notice extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * List all maintenance notices
     */
    public function index()
    {
        if (!is_super()) {
            access_denied('Support Maintenance Notice');
        }

        // Fetch all notices
        $this->db->order_by('created_at', 'desc');
        $data['notices'] = $this->db->get(db_prefix() . 'support_maintenance_notice')->result_array();
        
        // Get notice types for dropdown
        $data['notice_types'] = ['Maintenance', 'Support', 'Upgrade', 'Alert', 'Info', 'Warning'];
        
        // Get display positions for dropdown
        $data['display_positions'] = ['Header', 'Footer', 'Popup', 'Full Page'];
        
        $data['title'] = 'Support Maintenance Notice';
        $this->load->view('admin/support_maintenance_notice/manage', $data);
    }

    /**
     * Add new notice
     */
    public function add()
    {
        if (!is_super()) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $title = trim($this->input->post('title'));
        $message = trim($this->input->post('message'));
        $notice_type = trim($this->input->post('notice_type'));
        $display_position = trim($this->input->post('display_position'));
        $start_datetime = $this->input->post('start_datetime');
        $end_datetime = $this->input->post('end_datetime');
        $background_color = trim($this->input->post('background_color')) ?: '#ffc107';
        $text_color = trim($this->input->post('text_color')) ?: '#000000';
        $is_active = $this->input->post('is_active') ? 1 : 0;

        // Validation
        if (empty($title)) {
            echo json_encode(['success' => false, 'message' => 'Title is required']);
            return;
        }

        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Message is required']);
            return;
        }

        $insert_data = [
            'title' => $title,
            'message' => $message,
            'notice_type' => $notice_type ?: 'Maintenance',
            'display_position' => $display_position ?: 'Footer',
            'start_datetime' => $start_datetime ?: null,
            'end_datetime' => $end_datetime ?: null,
            'background_color' => $background_color,
            'text_color' => $text_color,
            'is_active' => $is_active,
            'created_by' => get_staff_user_id(),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->insert(db_prefix() . 'support_maintenance_notice', $insert_data);

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Notice added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add notice']);
        }
    }

    /**
     * Update existing notice
     */
    public function update($id)
    {
        if (!is_super()) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $id = (int) $id;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }

        $title = trim($this->input->post('title'));
        $message = trim($this->input->post('message'));
        $notice_type = trim($this->input->post('notice_type'));
        $display_position = trim($this->input->post('display_position'));
        $start_datetime = $this->input->post('start_datetime');
        $end_datetime = $this->input->post('end_datetime');
        $background_color = trim($this->input->post('background_color')) ?: '#ffc107';
        $text_color = trim($this->input->post('text_color')) ?: '#000000';
        $is_active = $this->input->post('is_active') ? 1 : 0;

        // Validation
        if (empty($title)) {
            echo json_encode(['success' => false, 'message' => 'Title is required']);
            return;
        }

        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Message is required']);
            return;
        }

        $update_data = [
            'title' => $title,
            'message' => $message,
            'notice_type' => $notice_type ?: 'Maintenance',
            'display_position' => $display_position ?: 'Footer',
            'start_datetime' => $start_datetime ?: null,
            'end_datetime' => $end_datetime ?: null,
            'background_color' => $background_color,
            'text_color' => $text_color,
            'is_active' => $is_active,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'support_maintenance_notice', $update_data);

        echo json_encode(['success' => true, 'message' => 'Notice updated successfully']);
    }

    /**
     * Delete notice
     */
    public function delete($id)
    {
        if (!is_super()) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $id = (int) $id;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'support_maintenance_notice');

        if ($this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Notice deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete notice']);
        }
    }

    /**
     * Toggle active status
     */
    public function toggle_status($id)
    {
        if (!is_super()) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $id = (int) $id;
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ID']);
            return;
        }

        // Get current status
        $this->db->where('id', $id);
        $row = $this->db->get(db_prefix() . 'support_maintenance_notice')->row();

        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'Notice not found']);
            return;
        }

        $new_status = $row->is_active ? 0 : 1;

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'support_maintenance_notice', [
            'is_active' => $new_status,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode([
            'success' => true, 
            'message' => $new_status ? 'Notice activated' : 'Notice deactivated',
            'new_status' => $new_status
        ]);
    }
}
