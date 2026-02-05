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
