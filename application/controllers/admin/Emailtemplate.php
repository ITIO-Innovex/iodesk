<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Emailtemplate extends AdminController
{
    public function __construct()
    {
        parent::__construct();
       
    }



    /**
     * URL: /admin/emailtemplate
     * List internal templates.
     */
public function index()
{
$companyId = get_staff_company_id();
$this->db->from('it_crm_email_template_internal t');
$this->db->where('t.status', 1);
$this->db->where_in('t.company_id', [1, $companyId]);
$this->db->where("t.id = (
    SELECT t2.id
    FROM it_crm_email_template_internal t2
    WHERE t2.template_title = t.template_title
    AND t2.status = 1
    AND t2.company_id IN (1, $companyId)
    ORDER BY (t2.company_id = $companyId) DESC, t2.id DESC
    LIMIT 1
)", null, false);

$this->db->order_by('t.id', 'desc');

$data['templates'] = $this->db->get()->result_array();

        $data['title']     = 'Internal Email Templates';

        $this->load->view('admin/emailtemplate/index', $data);
    }

    /**
     * URL: /admin/emailtemplate/manage or /admin/emailtemplate/manage/{id}
     * Add/Edit form.
     */
    public function manage($id = null)
    {
        $companyId = get_staff_company_id();
        $staffId   = get_staff_user_id();

        if ($this->input->method() === 'post') {
            $id            = (int) $this->input->post('id');
            $templateTitle = trim((string) $this->input->post('template_title', true));
            $subject       = trim((string) $this->input->post('subject', true));
            $emailBody     = $this->input->post('email_body', false);

            if ($subject === '' || trim((string) $emailBody) === '') {
                set_alert('danger', 'Template Title, Subject and Email Body are required.');
                redirect(admin_url('emailtemplate/manage' . ($id ? '/' . $id : '')));
            }

            $data = [
                'company_id'      => $companyId ?: null,
                'staffid'         => $staffId,
				'subject'         => $subject,
                'email_body'      => $emailBody,
                'status'          => 1,
            ];
			

            if ($id > 0) {
                $this->db->where('id', $id);
                $this->db->where('company_id', $companyId);
                $this->db->update('it_crm_email_template_internal', $data);
                set_alert('success', 'Template updated successfully.');
            } else {
			
			if (!is_super()) {
                set_alert('danger', 'Only Super Admin add new Template');
                redirect(admin_url('emailtemplate'));
            }
			
                $data['created_at'] = date('Y-m-d H:i:s');
				$data['template_title'] = $templateTitle;
                $this->db->insert('it_crm_email_template_internal', $data);
                set_alert('success', 'Template added successfully.');
            }

            redirect(admin_url('emailtemplate'));
        }

        $template = null;
        $id = $id !== null ? (int) $id : 0;
        if ($id > 0) {
            $template = $this->db->where('id', $id)
                ->where('company_id', $companyId)
                ->get('it_crm_email_template_internal')
                ->row_array();
            if (!$template) {
                set_alert('warning', 'Template not found.');
                redirect(admin_url('emailtemplate'));
            }
        }

        $data['template'] = $template;
        $data['title']    = $template ? 'Edit Template' : 'Add Template';
        $this->load->view('admin/emailtemplate/manage', $data);
    }

    /**
     * URL: /admin/emailtemplate/create_manage/{id}
     * Copy an existing template into a new record.
     *
     * - GET: loads template_title/subject/email_body from given id into form.
     * - POST: always inserts a new row, then redirects to /manage/{new_id}
     */
    public function create_manage($id = 0)
    {
        $companyId = get_staff_company_id();
        $staffId   = get_staff_user_id();
        $sourceId  = (int) $id;
		
		if ($sourceId > 0) {
            $template = $this->db->where('id', $sourceId)
                ->get('it_crm_email_template_internal')
                ->row_array();
        }
		
		

        if (isset($template)&&$template) {
            $templateTitle = $template['template_title'];
            $subject       = $template['subject'];
            $emailBody     = $template['email_body'];

            if ($templateTitle === '' || $subject === '' || trim((string) $emailBody) === '') {
                set_alert('danger', 'Template Title, Subject and Email Body are required.');
                redirect(admin_url('emailtemplate/create_manage/' . $sourceId));
            }

            $data = [
                'company_id'     => $companyId ?: null,
                'staffid'        => $staffId,
                'template_title' => $templateTitle,
                'subject'        => $subject,
                'email_body'     => $emailBody,
                'status'         => 1,
                'created_at'     => date('Y-m-d H:i:s'),
            ];
			
			

            $this->db->insert('it_crm_email_template_internal', $data);
            $newId = (int) $this->db->insert_id();

            if ($newId > 0) {
                set_alert('success', 'Template copied successfully.');
                redirect(admin_url('emailtemplate/manage/' . $newId));
            }

            set_alert('danger', 'Failed to create template.');
            redirect(admin_url('emailtemplate'));
        }

    }

    /**
     * Soft delete: set status=0
     * URL: /admin/emailtemplate/delete/{id}
     */
    public function delete($id = 0)
    {
        $companyId = get_staff_company_id();
        $id = (int) $id;
        if ($id <= 0) {
            redirect(admin_url('emailtemplate'));
        }

        $this->db->where('id', $id);
        $this->db->where('company_id', $companyId);
        $this->db->update('it_crm_email_template_internal', ['status' => 0]);

        set_alert('success', 'Template deleted successfully.');
        redirect(admin_url('emailtemplate'));
    }
}

