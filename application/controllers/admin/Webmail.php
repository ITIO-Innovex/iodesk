<?php

//use app\services\imap\Imap;

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
		if (!is_client_logged_in() && !is_admin() && is_staff_logged_in()) {
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
	    $data['title'] = _l('Webmail Setup');
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
		//print_r($data);exit;
		$entry_id=get_staff_user_id();
        $this->webmail_model->reply($data, $entry_id);
        set_alert('success', _l('Email Sent Successfully', _l('Email Sent')));
        redirect(previous_url() ?: $_SERVER['HTTP_REFERER']);
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
                'message'    => "Flaged",
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

}
