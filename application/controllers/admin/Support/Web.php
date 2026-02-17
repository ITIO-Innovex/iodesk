<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Web extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new support ticket page
     */
    public function add()
    {
        $data['title'] = 'New Support Ticket';
        $data['priorities'] = ['Low', 'Medium', 'High', 'Urgent'];
        $this->load->view('admin/support/web/add', $data);
    }

    /**
     * Submit support ticket
     */
    public function submit()
    {
        $this->output->set_content_type('application/json');

        $subject = trim($this->input->post('subject', true));
        $description = $this->input->post('description', false);
        $priority = trim($this->input->post('priority', true));

        // Validation
        if (empty($subject)) {
            echo json_encode([
                'success' => false,
                'message' => 'Subject is required.',
            ]);
            return;
        }

        if (empty($description) || strlen(strip_tags($description)) < 10) {
            echo json_encode([
                'success' => false,
                'message' => 'Description is required (minimum 10 characters).',
            ]);
            return;
        }

        // Validate priority
        $valid_priorities = ['Low', 'Medium', 'High', 'Urgent'];
        if (!in_array($priority, $valid_priorities)) {
            $priority = 'Medium';
        }

        // Handle file attachments
        $attachment_paths = [];
        if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
            $upload_dir = 'uploads/support_tickets/' . date('Y/m/');
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $count = count($_FILES['attachments']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!isset($_FILES['attachments']['error'][$i])) {
                    continue;
                }
                if ($_FILES['attachments']['error'][$i] !== UPLOAD_ERR_OK) {
                    $errorCode = (int) $_FILES['attachments']['error'][$i];
                    if ($errorCode === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    $errorMessage = 'Attachment upload failed.';
                    if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                        $errorMessage = 'Attachment is too large.';
                    } elseif ($errorCode === UPLOAD_ERR_PARTIAL) {
                        $errorMessage = 'Attachment was only partially uploaded.';
                    }
                    echo json_encode([
                        'success' => false,
                        'message' => $errorMessage,
                    ]);
                    return;
                }

                $tmpName = $_FILES['attachments']['tmp_name'][$i] ?? '';
                $origName = $_FILES['attachments']['name'][$i] ?? '';
                if ($tmpName && is_uploaded_file($tmpName)) {
                    $ext = pathinfo($origName, PATHINFO_EXTENSION);
                    $newName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $origName);
                    $destPath = $upload_dir . $newName;
                    if (move_uploaded_file($tmpName, $destPath)) {
                        $attachment_paths[] = $destPath;
                    }
                }
            }
        }

        // Company and staff scope
        $company_id = get_staff_company_id();
        $staffid = get_staff_user_id();

        // Insert ticket
        $insert_data = [
            'company_id' => $company_id,
            'staffid' => $staffid,
            'subject' => $subject,
            'description' => $description,
            'priority' => $priority,
            'status' => 'Open',
            'ticket_for' => 'Web',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Save attachments as comma-separated paths in the same table
        if (!empty($attachment_paths)) {
            $insert_data['attachments'] = implode(',', $attachment_paths);
        }

        $this->db->insert(db_prefix() . 'support_tickets', $insert_data);
        $ticket_id = $this->db->insert_id();
		
		// For Send Email
		        
		        //$msgdata['recipientCC']=$customer;
		        $msgdata['emailSubject']=$subject;
		        $msgdata['emailBody']=$description;
				// Load webmail model
                $this->load->model('webmail_model');
				
				if(is_super()){
				$msgdata['recipientEmail']=get_staff_email($ticket->staffid) ?? "nomail@itio.in";
				}else{
				$msgdata['recipientEmail']=get_option('support_email') ?? "support@itio.in";
				}
                $this->webmail_model->compose_email_super($msgdata);
				

        if ($ticket_id) {
            echo json_encode([
                'success' => true,
                'message' => 'Support ticket #' . $ticket_id . ' created successfully.',
                'ticket_id' => $ticket_id,
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create support ticket.',
            ]);
        }
    }

    /**
     * List support tickets
     */
    public function index()
    {
        $company_id = get_staff_company_id();
        $staffid = get_staff_user_id();

        // Fetch tickets for this staff
		if(is_super()){
		$this->db->where('ticket_for', 'Web');
		}elseif(is_admin()){
		$this->db->where('company_id', $company_id);
		}else{
		$this->db->where('company_id', $company_id);
        $this->db->where('staffid', $staffid);
		}
        
        
        $this->db->order_by('created_at', 'desc');
        $data['tickets'] = $this->db->get(db_prefix() . 'support_tickets')->result_array();

        $data['title'] = 'My Support Tickets';
        $this->load->view('admin/support/web/list', $data);
    }

    /**
     * View single ticket
     */
    public function view($id)
    {
	
	//echo "#######";exit;
        $id = (int) $id;
        $company_id = get_staff_company_id();
        $staffid = get_staff_user_id();
		
		
		$this->db->where('id', $id);
		
		if(is_super()){
		$this->db->where('ticket_for', 'Web');
		}elseif(is_admin()){
		$this->db->where('company_id', $company_id);
		}else{
		$this->db->where('company_id', $company_id);
        $this->db->where('staffid', $staffid);
		}
		

        
        
        
        $ticket = $this->db->get(db_prefix() . 'support_tickets')->row_array();

        if (!$ticket) {
            show_404();
            return;
        }

        // Parse attachments from comma-separated string
        $attachments = [];
        if (!empty($ticket['attachments'])) {
            $paths = explode(',', $ticket['attachments']);
            foreach ($paths as $path) {
                $path = trim($path);
                if ($path) {
                    $attachments[] = ['file_path' => $path];
                }
            }
        }
        $data['attachments'] = $attachments;

        // Fetch replies for this ticket
        $this->db->select('r.*, s.firstname, s.lastname');
        $this->db->from(db_prefix() . 'support_ticket_replies r');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = r.staffid', 'left');
        $this->db->where('r.ticket_id', $id);
        $this->db->order_by('r.created_at', 'asc');
        $data['replies'] = $this->db->get()->result_array();

        $data['ticket'] = $ticket;
        $data['title'] = 'Ticket #' . $id;
        $this->load->view('admin/support/web/view', $data);
    }

    /**
     * Submit reply to a ticket
     */
    public function reply()
    {
        $this->output->set_content_type('application/json');

        $ticket_id = (int) $this->input->post('ticket_id');
        $message = $this->input->post('message', false);

        // Validation
        if ($ticket_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid ticket ID.']);
            return;
        }

        if (empty($message) || strlen(strip_tags($message)) < 5) {
            echo json_encode(['success' => false, 'message' => 'Message is required (minimum 5 characters).']);
            return;
        }

        // Verify ticket belongs to current user
        $company_id = get_staff_company_id();
        $staffid = get_staff_user_id();

        $this->db->where('id', $ticket_id);
		if(is_super()){
		$this->db->where('ticket_for', 'Web');
		}elseif(is_admin()){
		$this->db->where('company_id', $company_id);
		}else{
		$this->db->where('company_id', $company_id);
        $this->db->where('staffid', $staffid);
		}
		
        $ticket = $this->db->get(db_prefix() . 'support_tickets')->row();
		
		        
		
		//log_message('error', 'Display data - '.print_r($ticket , true));

        if (!$ticket) {
            echo json_encode(['success' => false, 'message' => 'Ticket not found.']);
            return;
        }

        // Handle file attachments
        $attachment_paths = [];
        if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
            $upload_dir = 'uploads/support_tickets/replies/' . date('Y/m/');
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $count = count($_FILES['attachments']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!isset($_FILES['attachments']['error'][$i])) {
                    continue;
                }
                if ($_FILES['attachments']['error'][$i] !== UPLOAD_ERR_OK) {
                    $errorCode = (int) $_FILES['attachments']['error'][$i];
                    if ($errorCode === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    continue; // Skip failed uploads for replies
                }

                $tmpName = $_FILES['attachments']['tmp_name'][$i] ?? '';
                $origName = $_FILES['attachments']['name'][$i] ?? '';
                if ($tmpName && is_uploaded_file($tmpName)) {
                    $newName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $origName);
                    $destPath = $upload_dir . $newName;
                    if (move_uploaded_file($tmpName, $destPath)) {
                        $attachment_paths[] = $destPath;
                    }
                }
            }
        }

        // Insert reply
        $insert_data = [
            'ticket_id' => $ticket_id,
            'staffid' => $staffid,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if (!empty($attachment_paths)) {
            $insert_data['attachments'] = implode(',', $attachment_paths);
        }

        $this->db->insert(db_prefix() . 'support_ticket_replies', $insert_data);
        $reply_id = $this->db->insert_id();

        if ($reply_id) {
            // Update ticket's updated_at timestamp
            $this->db->where('id', $ticket_id);
            $this->db->update(db_prefix() . 'support_tickets', ['updated_at' => date('Y-m-d H:i:s')]);
			
			// For Send Email
		        
		        //$msgdata['recipientCC']=$customer;
		        $msgdata['emailSubject']="RE : ".$ticket->subject;
		        $msgdata['emailBody']=$message;
				// Load webmail model
                $this->load->model('webmail_model');

				if(is_super()){
				$msgdata['recipientEmail']=get_staff_email($ticket->staffid) ?? "nomail@itio.in";
				}else{
				$msgdata['recipientEmail']=get_option('support_email') ?? "support@itio.in";
				}
                $this->webmail_model->compose_email_super($msgdata);
            echo json_encode([
                'success' => true,
                'message' => 'Reply added successfully.',
                'reply_id' => $reply_id,
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add reply.']);
        }
    }
}
