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

        $this->db->from(db_prefix() . 'email_template');
        $this->db->where('status', 1);

        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }

        $this->db->order_by('id', 'desc');
        $data['templates'] = $this->db->get()->result_array();
        $data['title']     = 'Email Templates';

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
            set_alert('success', 'Template updated successfully');
            redirect(admin_url('email_template'));
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert($table, $data);
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

        $this->db->where('id', $id);
        if (!(function_exists('is_super') && is_super())) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->update(db_prefix() . 'email_template', ['status' => 0]);

        set_alert('success', 'Template deleted successfully');
        redirect(admin_url('email_template'));
    }

    public function send()
    {
        $templateId = (int) $this->input->post('template_id');
        $toEmail    = trim((string) $this->input->post('to_email'));
        $ccEmail    = trim((string) $this->input->post('cc_email'));
        $subject    = trim((string) $this->input->post('final_subject'));
        $body       = $this->input->post('final_body', false);

        if ($templateId <= 0) {
            set_alert('danger', 'Invalid template.');
            redirect(admin_url('email_template'));
        }

        if ($toEmail === '') {
            set_alert('warning', 'To Email is required');
            redirect(admin_url('email_template'));
        }

        if ($subject === '' || $body === '') {
            set_alert('warning', 'Subject and Description are required');
            redirect(admin_url('email_template'));
        }

        // Prevent sending if placeholders still exist
        if (preg_match('/\{\{\s*[^}]+\s*\}\}/', $subject . ' ' . $body)) {
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
            set_alert('danger', 'Template not found.');
            redirect(admin_url('email_template'));
        }

        $this->load->model('webmail_model');
        $ok = $this->webmail_model->compose_email([
            'recipientEmail' => $toEmail,
            'recipientCC'    => $ccEmail,
            'recipientBCC'   => '',
            'emailSubject'   => $subject,
            'emailBody'      => $body,
            // Try company SMTP first if configured
            'company_email'  => 1,
        ]);

        if ($ok) {
            set_alert('success', 'Email sent successfully');
        } else {
            set_alert('danger', 'Failed to send email');
        }

        redirect(admin_url('email_template'));
    }
}

