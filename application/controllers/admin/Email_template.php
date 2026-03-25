<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Email_template extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!is_staff_logged_in()) {
            access_denied('Email Templates');
        }
    }

    public function index()
    {
        $companyId = get_staff_company_id();
		$staffId = get_staff_user_id();

        $this->db->from(db_prefix() . 'email_template');
        $this->db->where('status', 1);

        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('staffid', $staffId);
        $this->db->order_by('id', 'desc');
        $data['templates'] = $this->db->get()->result_array();
        $data['title']     = 'Email Templates';
		
		/// Set Email From List
		/*$_SESSION['mailersdropdowns']="";*/
		if(empty($_SESSION['mailersdropdowns'])){
		$staffid=get_staff_user_id();
		$deptid=get_departments_id();
		$this->load->model('webmail_model');
		$wheredata=' mailer_status=1';
		$wheredata .=' AND ( staffid='.$staffid;
		$wheredata .=' OR departmentid='.$deptid;
		$wheredata .=' OR FIND_IN_SET('.$staffid.', assignto))';
		$_SESSION['mailersdropdowns']   = $this->webmail_model->getemaillist('', $wheredata);
		$data['webmailsetup']= $this->webmail_model->webmailsetup('', $wheredata);
			if(isset($data['webmailsetup'])&&$data['webmailsetup']){
			$_SESSION['webmail']=$data['webmailsetup'][0];
			}
		}

        $this->load->view('admin/email_template/manage', $data);
    }

    public function save()
    {
        $id      = (int) $this->input->post('id');
        $subject = trim((string) $this->input->post('subject'));
        $body    = $this->input->post('body', false);

        if ($subject === '' || $body === '') {
            set_alert('warning', 'Subject and Description are required');
            redirect(admin_url('email_template'));
        }

        $companyId = get_staff_company_id();
        $staffId   = get_staff_user_id();

        $data = [
            'company_id' => $companyId ?: null,
            'staffid'    => $staffId,
            'subject'    => $subject,
            'body'       => $body,
            'status'     => 1,
        ];

        $table = db_prefix() . 'email_template';

        if ($id > 0) {
            $this->db->where('id', $id);
            if (!(function_exists('is_super') && is_super())) {
                $this->db->where('company_id', $companyId);
            }
            $this->db->update($table, $data);
			/////////////////////Notification & log//////////////
		         $notification_data = [
                    'description'     => 'update_email_template',
                    'touserid'        => $staffId,
                    'link'            => 'email_template',
					'additional_data' => serialize([$subject,]),
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$staffId]);
                }
				//echo "Email sent successfully!";
	      log_activity('Email Template updated successfully -  [ Subject: ' . $subject . ']');
		////////////////////////////////////////////////
            set_alert('success', 'Template updated successfully');
            redirect(admin_url('email_template'));
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($table, $data);
		/////////////////////Notification & log//////////////
		         $notification_data = [
                    'description'     => 'add_email_template',
                    'touserid'        => $staffId,
                    'link'            => 'email_template',
					'additional_data' => serialize([$subject,]),
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$staffId]);
                }
				log_activity('Email Template added successfully -  [ Subject: ' . $subject . ']');
		////////////////////////////////////////////////
        set_alert('success', 'Template added successfully');
        redirect(admin_url('email_template'));
    }

    public function delete($id = 0)
    {
        $id = (int) $id;
        if (!$id) {
            redirect(admin_url('email_template'));
        }

        $companyId = get_staff_company_id();
        $staffId   = get_staff_user_id();
        $this->db->where('id', $id);
        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->update(db_prefix() . 'email_template', ['status' => 0]);
        /////////////////////Notification & log//////////////
		         $notification_data = [
                    'description'     => 'delete_email_template',
                    'touserid'        => $staffId,
                    'link'            => 'email_template',
					'additional_data' => serialize([$id,]),
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$staffId]);
                }
				log_activity('Email Template deleted successfully -  [ ID: ' . $id . ']');
		////////////////////////////////////////////////
        set_alert('success', 'Template deleted successfully');
        redirect(admin_url('email_template'));
    }

    public function send()
    {
        $ajax = $this->input->is_ajax_request();

        
        $templateId = (int) $this->input->post('template_id');
		$fromEmail   = trim((string) ($this->input->post('reply_from') ?? ''));
        $toEmail    = trim((string) ($this->input->post('to_email') ?? ''));
        $ccEmail    = trim((string) ($this->input->post('cc_email') ?? ''));
        $bccEmail   = trim((string) ($this->input->post('bcc_email') ?? ''));
		$fromEmail   = trim((string) ($this->input->post('reply_from') ?? ''));
        $subject    = trim((string) $this->input->post('final_subject'));
        $body       = $this->input->post('final_body', false);

        if ($templateId <= 0) {
            if ($ajax) {
                $this->email_send_ajax_response(false, 'Invalid template.');
                return;
            }
            set_alert('danger', 'Invalid template.');
            redirect(admin_url('email_template'));
        }

        if ($toEmail === '') {
            if ($ajax) {
                $this->email_send_ajax_response(false, 'To Email is required');
                return;
            }
            set_alert('warning', 'To Email is required');
            redirect(admin_url('email_template'));
        }

        if ($subject === '' || $body === '') {
            if ($ajax) {
                $this->email_send_ajax_response(false, 'Subject and Description are required');
                return;
            }
            set_alert('warning', 'Subject and Description are required');
            redirect(admin_url('email_template'));
        }

        // Prevent sending if placeholders still exist
        if (preg_match('/\{\{\s*[^}]+\s*\}\}/', $subject . ' ' . $body)) {
            if ($ajax) {
                $this->email_send_ajax_response(false, 'Please fill all template variables before sending');
                return;
            }
            set_alert('warning', 'Please fill all template variables before sending');
            redirect(admin_url('email_template'));
        }

        $companyId = get_staff_company_id();

        $this->db->where('id', $templateId);
        $this->db->where('status', 1);
        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }
        $tpl = $this->db->get(db_prefix() . 'email_template')->row_array();

        if (empty($tpl)) {
            if ($ajax) {
                $this->email_send_ajax_response(false, 'Template not found.');
                return;
            }
            set_alert('danger', 'Template not found.');
            redirect(admin_url('email_template'));
        }

        $this->load->model('webmail_model');
        $ok = $this->webmail_model->compose_email_super([
            'recipientEmail' => $toEmail,
            'recipientCC'    => $ccEmail,
            'recipientBCC'   => $bccEmail,
            'emailSubject'   => $subject,
			'recipientFromEmail'    => $fromEmail,
            'emailBody'      => $body,
        ]);

        if ($ajax) {
            $this->email_send_ajax_response(
                (bool) $ok,
                $ok ? 'Email sent successfully' : 'Failed to send email'
            );
            return;
        }

        if ($ok) {
            set_alert('success', 'Email sent successfully');
        } else {
            set_alert('danger', 'Failed to send email');
        }

        redirect(admin_url('email_template'));
    }

    /**
     * JSON list of templates (id + subject) for dropdowns — same scope as index().
     */
    public function templates_json()
    {
        $companyId = get_staff_company_id();
        $staffId   = get_staff_user_id();

        $this->db->select('id, subject');
        $this->db->from(db_prefix() . 'email_template');
        $this->db->where('status', 1);
        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('staffid', $staffId);
        $this->db->order_by('id', 'desc');
        $rows = $this->db->get()->result_array();

        header('Content-Type: application/json');
        echo json_encode([
            'success'    => true,
            'templates'  => $rows,
        ]);
    }

    /**
     * Single template JSON (for preview / placeholder resolution in UI).
     */
    public function template_json($id = 0)
    {
        $id = (int) $id;
        if ($id <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid id']);
            return;
        }

        $companyId = get_staff_company_id();
        $staffId   = get_staff_user_id();

        $this->db->where('id', $id);
        $this->db->where('status', 1);
        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('staffid', $staffId);
        $tpl = $this->db->get(db_prefix() . 'email_template')->row_array();

        header('Content-Type: application/json');
        if (empty($tpl)) {
            echo json_encode(['success' => false, 'message' => 'Template not found']);
            return;
        }

        echo json_encode([
            'success'  => true,
            'template' => [
                'id'      => (int) $tpl['id'],
                'subject' => $tpl['subject'] ?? '',
                'body'    => $tpl['body'] ?? '',
            ],
        ]);
    }

    /**
     * @param bool   $success
     * @param string $message
     */
    private function email_send_ajax_response($success, $message)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
        ]);
    }
}

