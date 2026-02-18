<?php

//use app\services\imap\Imap;
use app\services\imap\Imap;
use app\services\imap\ConnectionErrorException;
defined('BASEPATH') or exit('No direct script access allowed');

class Webmail extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('webmail_model');
        if (!is_admin()) {
            //access_denied('Access Webmail Setup');
        }
		
		$wheredata="";
		$data['staffid']="";
		$_SESSION['mail_limit']=30;
		$staffid=get_staff_user_id();
		if (!is_client_logged_in()  && is_staff_logged_in()) { //&& !is_admin()
		$wheredata=' staffid=' . $staffid;
		$data['staffid']=get_staff_user_id();
		$data['departmentid']  = $this->webmail_model->departmentid(get_staff_user_id(), $wheredata);
		
		//print_r($data['departmentid']);
		//
		if(isset($data['departmentid'][0]['departmentid'])&&$data['departmentid'][0]['departmentid']){
		$wheredata='('.$wheredata.' OR departmentid='.$data['departmentid'][0]['departmentid'].' OR FIND_IN_SET('.$staffid.', assignto))';
		}else{
		$wheredata=$wheredata.' OR FIND_IN_SET('.$staffid.', assignto)';
		}
		//echo $wheredata;exit;
		}else{
		$wheredata='staffid=0';
		}
		
		$wheredata=$wheredata.' AND mailer_status=1';
		//echo $wheredata;exit;
		$_SESSION['mailersdropdowns']   = $this->webmail_model->getemaillist('', $wheredata);
		
		
		if(isset($_GET['mt'])&&$_GET['mt']){
		$wheredata.=' AND id='.$_GET['mt'];
		}
		
		/// for display All / Last 2 Days
		if(isset($_GET['messageorder'])&&$_GET['messageorder']){
		$_SESSION['messageorder']=$_GET['messageorder'];
		}else{
		$_SESSION['messageorder']=1;
		}
		
		if((isset($_GET['mt'])&&$_GET['mt'] <> $_SESSION['webmail']['id']) || empty($_SESSION['webmail']) ){  
		//echo "New Session";
		//echo $wheredata;exit;
		$_SESSION['inbox-total-email']="";
		$_SESSION['outbox-total-email']="";
		$_SESSION['folderlist']="";
		$_SESSION['subfolderlist']="";
		$data['webmailsetup']= $this->webmail_model->webmailsetup('', $wheredata);
		//print_r($data['webmailsetup']);
		if(empty($data['webmailsetup'])){
		redirect(admin_url('webmail_setup'));
		}
		$_SESSION['webmail']=$data['webmailsetup'][0];
		$_SESSION['webmail']['login-staffid']=$data['staffid'];
		if(isset($data['departmentid'][0]['departmentid']) && $data['departmentid'][0]['departmentid']){
		$_SESSION['webmail']['login-departmentid']=$data['departmentid'][0]['departmentid'];
		}
		$_SESSION['webmail']['login-staffid']=$data['staffid'];
		
		if(isset($_GET['lead'])&&$_GET['lead']==1){
		redirect(admin_url('webmail/webmail_leads?stype=TEXT&skey='.$_GET['ekey']));
		}else{
		redirect(admin_url('webmail/inbox'));
		}
		
		}else{
		//echo "Old Session";
		}
        
    }
    /* Redirect Index to Inbox page */
    public function index()
    {
		redirect(admin_url('webmail/inbox'));
    }
	
	
	 //Display Inbox Listing 
	public function inbox()
	{
	    $mailer_email = $_SESSION['webmail']['mailer_email'] ?? "";
	    $data['title'] = $mailer_email.' - Webmail';
		$data['email_signature'] = get_staff_signature();
		//print_r($_SESSION['mailersdropdowns']);
		if(empty($_SESSION['mailersdropdowns'])){
		$data['errormessage']="Account not Assigned, Please add your webmail setup or contact web admin";
		$this->load->view('admin/webmail/inbox', $data);
		}else{
		
		$data['inboxemail']=$this->webmail_model->getinboxemail();
		
		////////////////////////////////////////////
		$this->load->view('admin/webmail/inbox', $data);
		}
	}
	
	 //Display Inbox Listing 
	public function webmail_leads()
	{
	    $data['title'] = _l('Webmail Setup');
		$data['email_signature'] = get_staff_signature();
		
		//print_r($_SESSION['mailersdropdowns']);
		if(empty($_SESSION['mailersdropdowns'])){
		$data['errormessage']="Account not Assigned, Please add your webmail setup or contact web admin";
		$this->load->view('admin/webmail/webmail_leads', $data);
		}else{
		
		$data['inboxemail']=$this->webmail_model->getleadsemail();
		////////////////////////////////////////////
		$this->load->view('admin/webmail/webmail_leads', $data);
		}
	}	
	
	 
	 public function reply()
	{
	    
		$data = $this->input->post();
		$entry_id=get_staff_user_id();
        $this->webmail_model->reply($data, $entry_id);
        set_alert('success', _l('Email Sent Successfully', _l('Email Sent')));
		log_message('error', 'Redirect : ' . previous_url() ?: $_SERVER['HTTP_REFERER']);
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
	} 

	public function schedule_send()
	{
		$data = $this->input->post();

		$scheduleAt = trim($data['schedule_at'] ?? '');
		if ($scheduleAt === '') {
			echo json_encode(['success' => false, 'message' => 'Scheduled time is required']);
			return;
		}

		$companyId = get_staff_company_id();
		$attachments = [];
		if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
			$uploadDir = FCPATH . 'uploads/email_queue/';
			if (!is_dir($uploadDir)) {
				@mkdir($uploadDir, 0755, true);
			}
			foreach ($_FILES['attachments']['name'] as $index => $name) {
				if ($name === '') {
					continue;
				}
				$tmpName = $_FILES['attachments']['tmp_name'][$index] ?? '';
				if ($tmpName === '' || !is_uploaded_file($tmpName)) {
					continue;
				}
				$ext = pathinfo($name, PATHINFO_EXTENSION);
				$storedName = uniqid('queue_', true) . ($ext ? '.' . $ext : '');
				$targetPath = $uploadDir . $storedName;
				if (move_uploaded_file($tmpName, $targetPath)) {
					$attachments[] = $storedName;
				}
			}
		}

		$smtpDetails = [
			'mailer_email'      => $_SESSION['webmail']['mailer_email'] ?? '',
			'mailer_username'   => $_SESSION['webmail']['mailer_username'] ?? '',
			'mailer_password'   => $_SESSION['webmail']['mailer_password'] ?? '',
			'mailer_smtp_host'  => $_SESSION['webmail']['mailer_smtp_host'] ?? '',
			'mailer_smtp_port'  => $_SESSION['webmail']['mailer_smtp_port'] ?? '',
			'encryption'        => $_SESSION['webmail']['encryption'] ?? '',
		];

		$insert = [
			'company_id'   => $companyId ?: null,
			'to_email'     => trim($data['recipientEmail'] ?? ''),
			'cc_emails'    => trim($data['recipientCC'] ?? ''),
			'bcc_emails'   => trim($data['recipientBCC'] ?? ''),
			'subject'      => trim($data['emailSubject'] ?? ''),
			'body'         => $data['emailBody'] ?? '',
			'attachments'  => $attachments ? json_encode($attachments) : null,
			'smtp_details' => json_encode($smtpDetails),
			'scheduled_at' => date('Y-m-d H:i:s', strtotime($scheduleAt)),
			'status'       => 'pending',
			'created_at'   => date('Y-m-d H:i:s'),
		];
		

		
		

		$this->db->insert(db_prefix() . 'email_queue', $insert);
		$last_insert_id = $this->db->insert_id();
		$attachments_json = $attachments ? json_encode($attachments) : null;
if (!empty($attachments_json)) {
    $attachments_array = json_decode($attachments_json, true);
    $attachments_string = implode(',', $attachments_array);
} else {
    $attachments_string = '';
}
		
		$insert_email = [
		    'subject'      => trim($data['emailSubject'] ?? ''),
			'from_email'   => $_SESSION['webmail']['mailer_email'],
			'from_name'    => $_SESSION['webmail']['mailer_email'],
			'to_emails'    => trim($data['recipientEmail'] ?? ''),
			'cc_emails'    => trim($data['recipientCC'] ?? ''),
			'bcc_emails'   => trim($data['recipientBCC'] ?? ''),
			'body'         => $data['emailBody'] ?? '',
			'attachments'  => $attachments_string,
			'folder'       => 'Outbox',
			'email'   	   => $_SESSION['webmail']['mailer_email'],
			'date' 		   => date('Y-m-d H:i:s', strtotime($scheduleAt)),
			'uniqid' 	   => $last_insert_id,
		];
		
		$this->db->insert(db_prefix() . 'emails', $insert_email);
		if ($this->db->affected_rows() > 0) {
			echo json_encode(['success' => true, 'message' => 'Email scheduled successfully']);
			return;
		}

		echo json_encode(['success' => false, 'message' => 'Failed to schedule email']);
	}

	public function add_contact()
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$data = [
			'staffid' => get_staff_user_id(),
			'first_name' => trim((string) $this->input->post('first_name')),
			'last_name' => trim((string) $this->input->post('last_name')),
			'email_id' => trim((string) $this->input->post('email_id')),
			'company_name' => trim((string) $this->input->post('company_name')),
			'phonenumber' => trim((string) $this->input->post('phonenumber')),
		];

		if ($data['email_id'] !== '' && !filter_var($data['email_id'], FILTER_VALIDATE_EMAIL)) {
			echo json_encode(['success' => false, 'message' => 'Invalid email']);
			return;
		}

		if ($data['email_id'] === '') {
			echo json_encode(['success' => false, 'message' => 'Email is required']);
			return;
		}
		if ($data['first_name'] === '') {
			echo json_encode(['success' => false, 'message' => 'First name is required']);
			return;
		}

		$exists = $this->db->where('staffid', $data['staffid'])
			->where('email_id', $data['email_id'])
			->get('it_crm_email_contact')
			->row_array();
		if (!empty($exists)) {
			echo json_encode(['success' => false, 'message' => 'Already added']);
			return;
		}

		$this->db->insert('it_crm_email_contact', $data);
		echo json_encode(['success' => true, 'message' => 'Contact saved successfully']);
	}

	public function update_contact($id = 0)
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$id = (int) $id;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid contact']);
			return;
		}

		$data = [
			'first_name' => trim((string) $this->input->post('first_name')),
			'last_name' => trim((string) $this->input->post('last_name')),
			'email_id' => trim((string) $this->input->post('email_id')),
			'company_name' => trim((string) $this->input->post('company_name')),
			'phonenumber' => trim((string) $this->input->post('phonenumber')),
		];

		if ($data['email_id'] === '' || !filter_var($data['email_id'], FILTER_VALIDATE_EMAIL)) {
			echo json_encode(['success' => false, 'message' => 'Invalid email']);
			return;
		}
		if ($data['first_name'] === '') {
			echo json_encode(['success' => false, 'message' => 'First name is required']);
			return;
		}

		$exists = $this->db->where('staffid', get_staff_user_id())
			->where('email_id', $data['email_id'])
			->where('id !=', $id)
			->get('it_crm_email_contact')
			->row_array();
		if (!empty($exists)) {
			echo json_encode(['success' => false, 'message' => 'Already added']);
			return;
		}

		$this->db->where('id', $id);
		$this->db->where('staffid', get_staff_user_id());
		$this->db->update('it_crm_email_contact', $data);
		echo json_encode(['success' => true, 'message' => 'Contact updated successfully']);
	}

	public function delete_contact($id = 0)
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}
		$id = (int) $id;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid contact']);
			return;
		}
		$this->db->where('id', $id);
		$this->db->where('staffid', get_staff_user_id());
		$this->db->delete('it_crm_email_contact');
		echo json_encode(['success' => true, 'message' => 'Contact deleted successfully']);
	}

	public function scrub_email()
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$email = trim((string) $this->input->post('email'));
		$action = trim((string) $this->input->post('action'));
		if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(['success' => false, 'message' => 'Invalid email']);
			return;
		}

		if ($action === 'archive') {
			$this->db->where('from_email', $email);
			$this->db->update('it_crm_emails', ['folder' => 'Archive']);
			echo json_encode(['success' => true, 'message' => 'Archived']);
			return;
		}

		if ($action === 'delete') {
			$this->db->where('from_email', $email);
			$this->db->update('it_crm_emails', ['is_deleted' => 1]);
			echo json_encode(['success' => true, 'message' => 'Deleted']);
			return;
		}

		echo json_encode(['success' => false, 'message' => 'Invalid action']);
	}

	public function add_appointment()
	{
	
	date_default_timezone_set('Asia/Kolkata');
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$consultations = (int) $this->input->post('consultations');
		$dateTime = trim((string) $this->input->post('date_time'));
		$consultant = trim((string) $this->input->post('consultant'));
		$customer = trim((string) $this->input->post('customer'));
		$notes = $this->input->post('notes', false);
		$notification = (int) $this->input->post('notification');

		if (!in_array($consultations, [15, 30, 45, 60], true)) {
			echo json_encode(['success' => false, 'message' => 'Invalid consultation time']);
			return;
		}
		if ($dateTime === '') {
			echo json_encode(['success' => false, 'message' => 'Date and time is required']);
			return;
		}
		if ($consultant === '' || $customer === '') {
			echo json_encode(['success' => false, 'message' => 'Consultant and customer are required']);
			return;
		}

		$data = [
			'staffid' => get_staff_user_id(),
			'company_id' => get_staff_company_id(),
			'consultations' => $consultations,
			'date_time' => date('Y-m-d H:i:s', strtotime($dateTime)),
			'consultant' => $consultant,
			'customer' => $customer,
			'notes' => $notes,
			'notification' => $notification ? 1 : 0,
			'status' => 1,
		];

		
		
		

		
		
		$this->db->insert('it_crm_email_appoinment', $data);
		$last_id = $this->db->insert_id();
		if($notification==1){
		
$mailbody='<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Invoice</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:20px;">
  <tr>
    <td align="center">

      <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
        <tr>
          <td style="padding:24px; color:#111827; font-size:14px; line-height:22px;">

            <p style="margin:0 0 16px;">Hi there, <strong>'.formatEmailName($customer).'! </strong></p>
<p>You`ve scheduled an appointment with <strong>'.formatEmailName($consultant).'</strong> for <strong>'.$consultations.' Mins meetings <br> on '.date('d M Y \a\t h:i A', strtotime($dateTime)).' (Asia/Kolkata GMT +)</strong></p>
<p style="margin:0 0 20px; text-align:left;">Something amiss? You can always reschedule or cancel your appointment.</p>
<p style="margin:0 0 16px;">Appoinment Number is : <strong># 000'.$last_id.'</strong></p>

<p style="margin:0;">
See you soon,<br>
<strong>'.formatEmailName($consultant).'<br>
'.get_staff_company_name().'</strong><br>
</p>

          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

</body>
</html>';

		
		$msgdata['redirect']="inbox.php";
		$msgdata['recipientEmail']=$customer;
		//$msgdata['recipientCC']=$customer;
		$msgdata['emailSubject']=get_staff_company_name()." : Appointment scheduled. # 000".$last_id;
		$msgdata['emailBody']=$mailbody;
		$this->webmail_model->compose_email($msgdata);
		}
		
		echo json_encode(['success' => true, 'message' => 'Appointment saved successfully']);
	}

	public function appoinment()
	{
		if (!is_staff_logged_in()) {
			access_denied('Appointments');
		}

		$companyId = get_staff_company_id();
		$staffId = get_staff_user_id();
		$now = date('Y-m-d H:i:s');

		$this->db->where('company_id', $companyId);
		if (!is_admin()) {
			$this->db->where('staffid', $staffId);
		}
		$this->db->where('date_time >=', $now);
		$this->db->order_by('date_time', 'asc');
		$data['upcoming'] = $this->db->get('it_crm_email_appoinment')->result_array();

		$this->db->where('company_id', $companyId);
		if (!is_admin()) {
			$this->db->where('staffid', $staffId);
		}
		$this->db->where('date_time <', $now);
		$this->db->order_by('date_time', 'desc');
		$data['past'] = $this->db->get('it_crm_email_appoinment')->result_array();

		$this->db->where('staffid', $staffId);
		$data['contacts'] = $this->db->get('it_crm_email_contact')->result_array();

		$data['title'] = 'Appointments';
		$this->load->view('admin/webmail/appoinment', $data);
	}

	public function update_appointment_status($id = 0)
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}
		$id = (int) $id;
		$status = (int) $this->input->post('status');
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid appointment']);
			return;
		}
		if (!in_array($status, [0, 1, 2], true)) {
			echo json_encode(['success' => false, 'message' => 'Invalid status']);
			return;
		}

		$this->db->where('id', $id);
		if (!is_admin()) {
			$this->db->where('staffid', get_staff_user_id());
		}
		$this->db->update('it_crm_email_appoinment', ['status' => $status]);
		echo json_encode(['success' => true, 'message' => 'Status updated']);
	}

	/**
	 * Update appointment
	 */
	public function update_appointment($id = 0)
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$id = (int) $id;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid appointment']);
			return;
		}

		$consultations = (int) $this->input->post('consultations');
		$date_time = trim($this->input->post('date_time'));
		$consultant = trim($this->input->post('consultant'));
		$customer = trim($this->input->post('customer'));
		$notes = trim($this->input->post('notes'));
		$notification = $this->input->post('notification') ? 1 : 0;

		if (!$consultations || !in_array($consultations, [15, 30, 45, 60], true)) {
			echo json_encode(['success' => false, 'message' => 'Invalid consultation time']);
			return;
		}
		if (empty($date_time)) {
			echo json_encode(['success' => false, 'message' => 'Date and time are required']);
			return;
		}
		if (empty($customer)) {
			echo json_encode(['success' => false, 'message' => 'Customer is required']);
			return;
		}

		$this->db->where('id', $id);
		if (!is_admin()) {
			$this->db->where('staffid', get_staff_user_id());
		}
		$this->db->update('it_crm_email_appoinment', [
			'consultations' => $consultations,
			'date_time' => $date_time,
			'consultant' => $consultant,
			'customer' => $customer,
			'notes' => $notes,
			'notification' => $notification,
		]);

		echo json_encode(['success' => true, 'message' => 'Appointment updated successfully']);
	}

	/**
	 * Delete appointment
	 */
	public function delete_appointment($id = 0)
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$id = (int) $id;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid appointment']);
			return;
		}

		$this->db->where('id', $id);
		if (!is_admin()) {
			$this->db->where('staffid', get_staff_user_id());
		}
		$this->db->delete('it_crm_email_appoinment');

		if ($this->db->affected_rows() > 0) {
			echo json_encode(['success' => true, 'message' => 'Appointment deleted successfully']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Failed to delete appointment']);
		}
	}

	public function contacts()
	{
		if (!is_staff_logged_in()) {
			access_denied('Contacts');
		}

		$staffId = get_staff_user_id();
		$this->db->where('staffid', $staffId);
		$this->db->order_by('addedon', 'desc');
		$data['contacts'] = $this->db->get('it_crm_email_contact')->result_array();
		$data['title'] = 'Email Contacts';
		$this->load->view('admin/webmail/contacts', $data);
	}

	/**
	 * Update scheduled email page
	 */
	public function update_schedule($id = 0)
	{
		if (!is_staff_logged_in()) {
			access_denied('Scheduled Emails');
		}

		$id = (int) $id;
		if (!$id) {
			redirect(admin_url('webmail/inbox?fd=Outbox'));
		}

		$companyId = get_staff_company_id();
		$this->db->where('id', $id);
		$this->db->where('company_id', $companyId);
		$this->db->where('status', 'pending');
		$schedule = $this->db->get(db_prefix() . 'email_queue')->row_array();

		if (!$schedule) {
			set_alert('warning', 'Scheduled email not found or already sent');
			redirect(admin_url('webmail/inbox?fd=Outbox'));
		}

		$data['schedule'] = $schedule;
		$data['title'] = 'Update Scheduled Email';
		$data['email_signature'] = get_staff_signature();
		$this->load->view('admin/webmail/update_schedule', $data);
	}

	/**
	 * Process update scheduled email
	 */
	public function process_update_schedule()
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$id = (int) $this->input->post('schedule_id');
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid schedule ID']);
			return;
		}

		$companyId = get_staff_company_id();
		$this->db->where('id', $id);
		$this->db->where('company_id', $companyId);
		$this->db->where('status', 'pending');
		$existing = $this->db->get(db_prefix() . 'email_queue')->row_array();

		if (!$existing) {
			echo json_encode(['success' => false, 'message' => 'Scheduled email not found or already sent']);
			return;
		}

		$toEmail = trim($this->input->post('recipientEmail'));
		$ccEmails = trim($this->input->post('recipientCC'));
		$bccEmails = trim($this->input->post('recipientBCC'));
		$subject = trim($this->input->post('emailSubject'));
		$body = $this->input->post('emailBody');
		$scheduleAt = trim($this->input->post('schedule_at'));

		if (empty($toEmail)) {
			echo json_encode(['success' => false, 'message' => 'Recipient email is required']);
			return;
		}
		if (empty($subject)) {
			echo json_encode(['success' => false, 'message' => 'Subject is required']);
			return;
		}
		if (empty($scheduleAt)) {
			echo json_encode(['success' => false, 'message' => 'Scheduled time is required']);
			return;
		}

		$existingAttachments = [];
		if (!empty($existing['attachments'])) {
			$existingAttachments = json_decode($existing['attachments'], true) ?: [];
		}

		$deletedAttachments = $this->input->post('deleted_attachments');
		if (!empty($deletedAttachments)) {
			$deletedArr = json_decode($deletedAttachments, true) ?: [];
			foreach ($deletedArr as $delFile) {
				$key = array_search($delFile, $existingAttachments);
				if ($key !== false) {
					$filePath = FCPATH . 'uploads/email_queue/' . $delFile;
					if (file_exists($filePath)) {
						@unlink($filePath);
					}
					unset($existingAttachments[$key]);
				}
			}
			$existingAttachments = array_values($existingAttachments);
		}

		$newAttachments = [];
		if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
			$uploadDir = FCPATH . 'uploads/email_queue/';
			if (!is_dir($uploadDir)) {
				@mkdir($uploadDir, 0755, true);
			}
			foreach ($_FILES['attachments']['name'] as $index => $name) {
				if ($name === '') {
					continue;
				}
				$tmpName = $_FILES['attachments']['tmp_name'][$index] ?? '';
				if ($tmpName === '' || !is_uploaded_file($tmpName)) {
					continue;
				}
				$ext = pathinfo($name, PATHINFO_EXTENSION);
				$storedName = uniqid('queue_', true) . ($ext ? '.' . $ext : '');
				$targetPath = $uploadDir . $storedName;
				if (move_uploaded_file($tmpName, $targetPath)) {
					$newAttachments[] = $storedName;
				}
			}
		}

		$allAttachments = array_merge($existingAttachments, $newAttachments);

		$updateData = [
			'to_email' => $toEmail,
			'cc_emails' => $ccEmails,
			'bcc_emails' => $bccEmails,
			'subject' => $subject,
			'body' => $body,
			'attachments' => $allAttachments ? json_encode($allAttachments) : null,
			'scheduled_at' => date('Y-m-d H:i:s', strtotime($scheduleAt)),
		];

		$this->db->where('id', $id);
		$this->db->update(db_prefix() . 'email_queue', $updateData);

		$this->db->where('uniqid', $id);
		$this->db->where('folder', 'Outbox');
		$attachmentsString = $allAttachments ? implode(',', $allAttachments) : '';
		$this->db->update(db_prefix() . 'emails', [
			'to_emails' => $toEmail,
			'cc_emails' => $ccEmails,
			'bcc_emails' => $bccEmails,
			'subject' => $subject,
			'body' => $body,
			'attachments' => $attachmentsString,
			'date' => date('Y-m-d H:i:s', strtotime($scheduleAt)),
		]);
		
		//log_message('error', 'Last Query - '.$this->db->last_query() );

		echo json_encode(['success' => true, 'message' => 'Scheduled email updated successfully']);
	}

	/**
	 * Delete scheduled email
	 */
	public function delete_schedule($id = 0)
	{
		if (!is_staff_logged_in()) {
			echo json_encode(['success' => false, 'message' => 'Unauthorized']);
			return;
		}

		$id = (int) $id;
		if (!$id) {
			echo json_encode(['success' => false, 'message' => 'Invalid schedule ID']);
			return;
		}

		$companyId = get_staff_company_id();
		$this->db->where('id', $id);
		$this->db->where('company_id', $companyId);
		$this->db->where('status', 'pending');
		$existing = $this->db->get(db_prefix() . 'email_queue')->row_array();

		if (!$existing) {
			echo json_encode(['success' => false, 'message' => 'Scheduled email not found or already sent']);
			return;
		}

		if (!empty($existing['attachments'])) {
			$attachments = json_decode($existing['attachments'], true) ?: [];
			foreach ($attachments as $file) {
				$filePath = FCPATH . 'uploads/email_queue/' . $file;
				if (file_exists($filePath)) {
					@unlink($filePath);
				}
			}
		}

		$this->db->where('id', $id);
		$this->db->delete(db_prefix() . 'email_queue');

		$this->db->where('uniqid', $id);
		$this->db->where('folder', 'Outbox');
		$this->db->delete(db_prefix() . 'emails');

		if ($this->db->affected_rows() >= 0) {
			echo json_encode(['success' => true, 'message' => 'Scheduled email deleted successfully']);
		} else {
			echo json_encode(['success' => false, 'message' => 'Failed to delete scheduled email']);
		}
	}
	

	
	 //Display Inbox Listing 
	public function compose()
	{
	    $data['title'] = _l('New Email');
		$data['email_signature'] = get_staff_signature();
		//print_r($_SESSION['mailersdropdowns']);
		if(empty($_SESSION['mailersdropdowns'])){
		$data['errormessage']="Account not Assigned, Please add your webmail setup or contact web admin";
		$this->load->view('admin/webmail/compose', $data);
		}else{
		////////////////////////////////////////////
		$this->load->view('admin/webmail/compose', $data);
		}
	} 
	
	//Download Email From Cron
	public function download_email_from_cron_test($id)
	{
	
	    $data['title'] = _l('Download Email From Cron');
		$data['message']=$this->webmail_model->downloadmail($id);
		$this->load->view('admin/webmail/download_email_from_cron', $data);
	}
	
	public function make_isflag()
    {
	
	    $data = $this->input->post();
		$fid=$data['fid'];
		$mid=$data['mid'];
		
		$data['msg']=$this->webmail_model->make_isflag($mid,$fid);
		
		if(isset($data['msg'])&&$data['msg']==1){
		//echo $data['ai_content']['error'];
		//$data['content_description']=$data['ai_content']['error'];
		echo json_encode([
                'alert_type' => "success",
                'message'    => "Un Flagged",
            ]);
		
		}else{
		
		
		echo json_encode([
                'alert_type' => 'danger',
                'message'    => "UnFlaged",
            ]);
		}
		
    }
	
	public function make_isdelete()
    {
	
	    $data = $this->input->post();
		$fid=$data['fid'];
		$mid=$data['mid'];
		
		$data['msg']=$this->webmail_model->make_isdelete($mid,$fid);
		
		if(isset($data['msg'])&&$data['msg']==1){
		//echo $data['ai_content']['error'];
		//$data['content_description']=$data['ai_content']['error'];
		echo json_encode([
                'alert_type' => "success",
                'message'    => "Action Completed",
            ]);
		
		}else{
		
		
		echo json_encode([
                'alert_type' => 'danger',
                'message'    => "Action Failed",
            ]);
		}
		
    }
	
	public function make_isread()
    {
	
	    $data = $this->input->post();
		$fid=$data['fid'];
		$mid=$data['mid'];
		
		$data['msg']=$this->webmail_model->make_isread($mid,$fid);
		if(isset($data['msg'])&&$data['msg']==1){
		echo json_encode([
                'alert_type' => "success",
                'message'    => "Read",
            ]);
		
		}else{
		
		
		echo json_encode([
                'alert_type' => 'danger',
                'message'    => "Not Read",
            ]);
		}
		
    }
	public function refresh_email()
    {
		$data=$this->webmail_model->downloadmailbyfolder();
		if(isset($data['cnt'])&&$data['cnt']==1){
		echo json_encode([
                'alert_type' => "success",
                'message'    => $data['msg'],
            ]);
		
		}else{
		
		
		echo json_encode([
                'alert_type' => 'danger',
                'message'    => $data['msg'],
            ]);
		}
		
    }
	
	// function for get inbox mail list
       public function getfolderlist()
        {
		if(isset($_SESSION['webmail'])&&$_SESSION['webmail']){
		
		//print_r($_SESSION['webmail']);
		
		app_check_imap_open_function();

       
        $mailer_imap_host = trim($_SESSION['webmail']['mailer_imap_host'] ?? '');
        $mailer_imap_port = trim($_SESSION['webmail']['mailer_imap_port'] ?? '');
        $mailer_email     = trim($_SESSION['webmail']['mailer_email'] ?? '');
		$mailer_username  = trim($_SESSION['webmail']['mailer_username'] ?? '');
        $mailer_password  = trim($_SESSION['webmail']['mailer_password'] ?? '');
        $encryption       = trim($_SESSION['webmail']['encryption'] ?? '');
		
		 $imap = new Imap(
           $mailer_username ? $mailer_username : $mailer_email,
           $mailer_password,
           $mailer_imap_host,
           $encryption
        );
		
		try {
            $mailbox=$imap->getSelectableFolders();
			
			//print_r($mailbox);
			
			foreach ($mailbox as $box) {
			
			$folder=htmlspecialchars($box);
     
			
		$this->db->where('email', $mailer_email);
        $this->db->where('folder', $folder);
        $result=$this->db->select('email')->from(db_prefix() . 'emails')->get()->row(); 
		
		if(isset($result)&&$result->email){
		//echo "Duplicate - ".$result->email;
		}else{
		
		$data['uniqid']=0;
		$data['email']=$mailer_email;
		$data['folder']=$folder;
		$this->db->insert(db_prefix() . 'emails', $data);
		//echo $this->db->last_query();
		}
		  
	   }
			
		
			
        } catch (ConnectionErrorException $e) {
           set_alert('warning', _l('problem_deleting', _l('Folder Setup Failed')));
		   redirect(admin_url('webmail/inbox'));
        }
		set_alert('success', _l('added_successfully', _l('Folder Setup')));
		redirect(admin_url('webmail/inbox'));
		}else{
		set_alert('warning', _l('problem_deleting', _l('Folder Setup Failed')));
		redirect(admin_url('webmail/inbox'));
		}
		
		}

}
