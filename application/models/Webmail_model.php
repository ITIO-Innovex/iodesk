<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Webklex\PHPIMAP\ClientManager;
//use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Exceptions\ImapServerErrorException;
use Webklex\PHPIMAP\Exceptions\ResponseException;
use Webklex\PHPIMAP\Exceptions\GetMessagesFailedException;
use Webklex\PHPIMAP\Client;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require_once APPPATH.'/vendor/vendor/autoload.php';

class Webmail_model extends App_Model
{
    

    public function __construct()
    {
        parent::__construct();
    }

 
	  
	// function for get inbox mail list
     public function getinboxemail()
     {
	 
	 //$_SESSION['webmail']="";
	 $mailer_email=$_SESSION['webmail']['mailer_email'];
	 
	 /// For change folder
	    if((isset($_GET['fd'])&&$_GET['fd'])){
		$_SESSION['webmail']['folder']=$_GET['fd'];
		$_SESSION['inbox-total-email']="";
		$_SESSION['outbox-total-email']="";
		$_SESSION['stype']="";
		$_SESSION['skey']="";
		redirect(admin_url('webmail/inbox'));
		}elseif($_SESSION['webmail']['folder']==""){
		$_SESSION['webmail']['folder']="INBOX";
		redirect(admin_url('webmail/inbox'));
		}
		$folder=$_SESSION['webmail']['folder'];
		
		$page=$_GET["page"]?? 1;//exit;
		if($page==1){
		$page=0;
		}else{
		$page=($page-1) * $_SESSION['mail_limit'];
		}
	
	    if(isset($mailer_email)&&$mailer_email&&isset($folder)&&$folder){
	  
	    $this->db->select('folder,');
        $this->db->where('email', $mailer_email);
        $this->db->order_by('id', 'asc');
		$this->db->group_by('folder');
		//$this->db->limit(1);
        $_SESSION['folderlist']=$this->db->get(db_prefix() . 'emails')->result_array();
		//echo $this->db->last_query();exit;


        ///////////////////////////Search Query//////////////
		$search=0;
		if(isset($_GET['stype'])&&!empty($_GET['stype'])&&isset($_GET['skey'])&&!empty($_GET['skey'])){
		$_SESSION['stype']=trim($_GET['stype']);
		$_SESSION['skey']=trim($_GET['skey']);
		$search=1;
		$_SESSION['webmail']['folder']="Search";
		$_SESSION['inbox-total-email']="";
		$_SESSION['outbox-total-email']="";
		}elseif(isset($_SESSION['stype'])&&!empty($_SESSION['stype'])&&isset($_SESSION['skey'])&&!empty($_SESSION['skey'])){
		$search=1;
		}
		///////////////////////////END Search Query//////////////
		
		
		///////////////////////////Count Total Email BY Folder//////////////
		$this->db->select('COUNT(`id`) AS `total_email`');
		
		
        
		
		if($folder=="Deleted"){
		$this->db->where('is_deleted', 1);
		}elseif($folder=="Flagged"){
		$this->db->where('isfalg', 1);
		}elseif($search==1){
		
		$this->db->or_like($_SESSION['stype'], $_SESSION['skey']);
		$this->db->where('is_deleted', 0);
		}else{
		$this->db->where('is_deleted', 0);
		$this->db->where('folder', $folder);
		}
		$this->db->where('email', $mailer_email);
		//$this->db->group_by('id');
		$this->db->where('uniqid >=', 1);
        $counter=$this->db->get(db_prefix() . 'emails')->result_array(); //return
		$_SESSION['inbox-total-email']=$counter[0]['total_email'];
		//echo $this->db->last_query();exit;
		//print_r($counter);exit;
		///////////////////////////END Count Total Email BY Folder//////////////
		 
		
		///////////////////////////Fetch Email//////////////
		
		
		$this->db->select('*,');
		
		
        
		
        $this->db->order_by('uniqid', 'desc');
		//$this->db->group_by('uniqid');
		if($folder=="Deleted"){
		$this->db->where('is_deleted', 1);
		}elseif($folder=="Flagged"){
		$this->db->where('isfalg', 1);
		}elseif($search==1){
		$this->db->or_like($_SESSION['stype'], $_SESSION['skey']);
		$this->db->where('is_deleted', 0);
		
		}else{
		$this->db->where('is_deleted', 0);
		$this->db->where('folder', $folder);
		}
		$this->db->where('email', $mailer_email);
		$this->db->where('uniqid >=', 1);
		$this->db->limit($_SESSION['mail_limit'],$page);
        $mails=$this->db->get(db_prefix() . 'emails')->result_array(); //return
		//echo $this->db->last_query();//exit;
		 return  $mails;exit;
		///////////////////////////END Fetch Email//////////////
		
	  
	  }
	  
	   	
	
	
		
      }
	  
	 // function for get inbox mail list
     public function getleadsemail()
     {
	
	    if(isset($_GET['skey'])&&trim($_GET['skey'])<>""){
		$skey=trim($_GET['skey']);
		$qrs='(`from_email` LIKE "%' . $skey . '%" OR `to_emails` LIKE "%' . $skey . '%")';
		
		///////////////////////////Count Total Email BY Folder//////////////
		$this->db->select('*');
		$this->db->where($qrs);
		$this->db->where('is_deleted', 0);
		$this->db->order_by('date', 'DESC');
        $mails=$this->db->get(db_prefix() . 'emails')->result_array(); //return
		$_SESSION['inbox-total-email']=count($mails);
		$this->db->last_query();
		return $mails;
		exit;
		///////////////////////////END Count Total Email BY Folder//////////////
	    }else{
		return 0;
		}
	  
		
      }
	
	   
	
	public function getemaillist($id = '', $where = [])
    {
        $this->db->select('MAX(`id`) AS id,mailer_email,');
        $this->db->where($where);
		
		          if (!is_super()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
		          if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		          $this->db->where('company_id', $_SESSION['super_view_company_id']);
	              }
		          }

        $this->db->order_by('id', 'asc');
		$this->db->group_by('mailer_email');
		//$this->db->limit(1);
          return $this->db->get(db_prefix() . 'webmail_setup')->result_array();
		 //echo $this->db->last_query();exit;
    }
	
	 public function webmailsetup($id = '', $where = [])
    {
        $this->db->select('*,');
        $this->db->where($where);
		          if (!is_super()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
		          if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		          $this->db->where('company_id', $_SESSION['super_view_company_id']);
	              }
		
		}
        $this->db->order_by('id', 'asc');
		$this->db->limit(1);
        return $this->db->get(db_prefix() . 'webmail_setup')->result_array();
		//return 
		//echo $this->db->last_query();
    }
	
	 public function departmentid($id = '', $where = [])
    {
	
	
        $this->db->select('departmentid,');
        $this->db->where($where);
        //$this->db->order_by('id', 'asc');
		$this->db->limit(1);
        return $this->db->get(db_prefix() . 'staff_departments')->result_array();
		//return 
		//echo $this->db->last_query();
    }

     public function reply($data, $id = '' )
    {
	
	//print_r($data);
	//print_r($_SESSION['webmail']);exit;
	
		$recipientEmail=isset($_POST['recipientEmail']) ? $_POST['recipientEmail'] : "";
		$messageid=isset($_POST['messageid']) ? $_POST['messageid'] : "";
		if(preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $recipientEmail, $matches)){
		$recipientEmail = $matches[0] ?? 'Email not found';
		}
		
		$recipientCC=isset($_POST['recipientCC']) ? $_POST['recipientCC'] : "";
		$recipientBCC=isset($_POST['recipientBCC']) ? $_POST['recipientBCC'] : "";
		
		// Form Post Data
		//echo $recipientEmail;
		$subject=$_POST['emailSubject'];
		$body=$_POST['emailBody'];
		$redirect=$_POST['redirect'];
		
		
		//exit;
		// SMTP Details from session
		$mailer_smtp_host=$_SESSION['webmail']['mailer_smtp_host'];
        $mailer_smtp_port=$_SESSION['webmail']['mailer_smtp_port'];
        $mailer_username=$_SESSION['webmail']['mailer_username'];
        $mailer_password=$_SESSION['webmail']['mailer_password'];
		$senderEmail=$_SESSION['webmail']["mailer_email"];
		$senderName=$_SESSION['webmail']["mailer_name"];
		$encryption=$_SESSION['webmail']["encryption"];
		$mail = new PHPMailer(true);
		
		
	try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $mailer_smtp_host; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $mailer_username; // Replace with your email
    $mail->Password = $mailer_password; // Replace with your email password or app-specific password
    
	
	
	if($encryption=="tls"){
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	}else{
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	}
	
    $mail->Port = $mailer_smtp_port;

    // Email settings
	$mail->isHTML(true); // Set email format to plain text
	$mail->CharSet = 'UTF-8';
	$mail->Encoding = 'base64';
	$mail->WordWrap = 50;               // set word wrap
	//$mail->Priority = 1; 
	$senderName = trim($senderName);
    $senderName = strip_tags($senderName);
    $senderName = preg_replace('/[^\p{L}\p{N}\s\.\-_]/u', '', $senderName);
	//$mail->setFrom($senderEmail, $senderName);
	$mail->setFrom($senderEmail, $senderName, false);
	$mail->addAddress($recipientEmail);
	if (isset($recipientCC) && $recipientCC != "") {
	
	      // Add CC addresses from comma-separated string
        $ccEmails = explode(',', trim($recipientCC));
        foreach ($ccEmails as $ccEmail) {
            $ccEmail = trim($ccEmail);
            if (filter_var($ccEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->addCC($ccEmail);
            }
        }
		
	}
	
	if (isset($recipientBCC) && $recipientBCC != "") {
	
	       // Add CC addresses from comma-separated string
        $bccEmails = explode(',', trim($recipientBCC));
        foreach ($bccEmails as $bccEmail) {
            $bccEmail = trim($bccEmail);
            if (filter_var($bccEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->addBCC($bccEmail);
            }
        }
		
		
	}
	
	if (isset($messageid) && $messageid != "") {
		
	$mail->addCustomHeader('In-Reply-To', $messageid);
    $mail->addCustomHeader('References', $messageid);
	}
	// Add hardcoded BCC
	//$mail->addBCC('onboarding@paycly.com');
	$mail->Subject = $subject;
	$mail->Body = $body;
	
	 $files = $_FILES['attachments'];
	// Handle Multiple File Attachments
        if (!empty($files['name'][0])) {
            for ($i = 0; $i < count($files['name']); $i++) {
                $fileTmpPath = $files['tmp_name'][$i];
                $fileName = $files['name'][$i];
                $fileType = $files['type'][$i];
                $fileError = $files['error'][$i];
                
                if ($fileError === 0) {
                    $mail->addAttachment($fileTmpPath, $fileName);
                }
            }
        }


    $mail->send();
	$lid=$this->leads_model->get_lead_id_by_email($recipientEmail);
	if(isset($lid)&&$lid > 0){
	//For Lead Activity
    $this->leads_model->log_lead_activity($lid, 'Sent Email to '.$recipientEmail.' with subject - '.$subject);
	
	}
    //echo "Email sent successfully!";
	log_activity('Email Reply With Subject Line -  [ Subject: ' . $subject . ']');
    return true;
	} catch (Exception $e) {
		//echo "Email could not be sent. Error: {$mail->ErrorInfo}";
		return false;
	}
	
	
	
	
	
	}
	
	
	public function send_email_via_smtp($email)
    {
	
	
	//print_r($email);
	if(isset($email['smtp_details'])&&$email['smtp_details']){
	$smtp_details = json_decode($email['smtp_details'], true);
	$mailer_email=$smtp_details['mailer_email'];
	$mailer_username=$smtp_details['mailer_username'];
	$mailer_password=$smtp_details['mailer_password'];
	$mailer_smtp_host=$smtp_details['mailer_smtp_host'];
	$mailer_smtp_port=$smtp_details['mailer_smtp_port'];
	$encryption=$smtp_details['encryption'];
	}
	
	
	//print_r($smtp_details);
	//exit;
	
	
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = $mailer_smtp_host;
    $mail->SMTPAuth   = true;
    $mail->Username   = $mailer_username;
    $mail->Password   = $mailer_password;
    $mail->SMTPSecure = $encryption;
    $mail->Port       = $mailer_smtp_port;

    $mail->setFrom($mailer_email, $mailer_username);
    $mail->addAddress($email['to_email']);
	
	if (isset($email['cc_emails']) && $email['cc_emails'] != "") {
	     // Add CC addresses from comma-separated string
        $ccEmails = explode(',', trim($email['cc_emails']));
        foreach ($ccEmails as $ccEmail) {
            $ccEmail = trim($ccEmail);
            if (filter_var($ccEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->addCC($ccEmail);
            }
        }
	}
	
	if (isset($email['bcc_emails']) && $email['bcc_emails'] != "") {
	
	       // Add CC addresses from comma-separated string
        $bccEmails = explode(',', trim($email['bcc_emails']));
        foreach ($bccEmails as $bccEmail) {
            $bccEmail = trim($bccEmail);
            if (filter_var($bccEmail, FILTER_VALIDATE_EMAIL)) {
                $mail->addBCC($bccEmail);
            }
        }
		
		
	}

    $mail->isHTML(true);
    $mail->Subject = $email['subject'];
    $mail->Body    = $email['body'];
	
	
    if (!empty($email['attachments'])) {
    $attachments = json_decode($email['attachments'], true);
    if (!empty($attachments)) {
        foreach ($attachments as $file) {
		$file=$uploadDir = FCPATH . 'uploads/email_queue/'.$file;
            $mail->addAttachment($file);
        }
    }
	}

    $mail->send();
}
   
   
       // function for get inbox mail list
        public function downloadmailXXXXXX($id)
        { 
		if(isset($id)&&$id){
		$mailers=$this->webmail_model->get_imap_details($id);
		}
		
		if(empty($mailers)){ 
		$downloadMessages="Email SMTP Details Not Found !!";
		return $downloadMessages;
		exit;
		}
		
		$mailer_imap_host=trim($mailers[0]['mailer_imap_host']);
        $mailer_imap_port=trim($mailers[0]['mailer_imap_port']);
        $mailer_username=trim($mailers[0]['mailer_username']);
		$data['email']=trim($mailer_username);
        $mailer_password=trim($mailers[0]['mailer_password']);
		$encryption=trim($mailers[0]['encryption']);
		
		 $cm = new ClientManager();
		 $client = $cm->make([
			'host'          => $mailer_imap_host,
			'port'          => $mailer_imap_port,
			'encryption'    => $encryption,
			'validate_cert' => true,
			'username'      => $mailer_username,
			'password'      => $mailer_password,
			'protocol'      => 'imap', 
			'timeout'       => 300            
		 ]);
		
		if (!$client->connect()) {
			return "IMAP connection failed";
		}
		
		$folders = $client->getFolders();
		$cnt=0;
		foreach ($folders as $folder) { 
		  $folder=$folder->name;
		  $data['folder'] = $folder; // for submit to db
		  $mailbox = $client->getFolder($folder);
		  if ($mailbox === null) {
			continue;
		  }
		  
		  $last_email_id=$this->webmail_model->lastemailid($mailer_username, $folder);
		  $last_email_id = $last_email_id[0]['uniqid'] ?? 0;
		  
		  try {
			$messages = $mailbox->query()->limit(10)->getByUidGreater($last_email_id);
		  } catch (\Exception $e) {
			continue;
		  }

		  foreach ($messages as $message) {
			$data['subject'] = $message->getSubject();
			    $dateAttribute = $message->getDate();
				$carbonDate = $dateAttribute->first(); // Carbon\Carbon
				$carbonDate->setTimezone('Asia/Kolkata');
				$data['date'] = $carbonDate->format('Y-m-d H:i:s');
				$timezoneOffset = $carbonDate->format('P');
				$data['timezone']      = $timezoneOffset;
			$data['body'] = $message->getHtmlBody() ?? '';
			if($data['body']==""){$data['body'] = $message->getTextBody() ?? ''; }
			$data['uniqid'] = $message->uid;
			$data['messageid'] = $message->getMessageId();
			
			$from = $message->getFrom();
			$data['from_email'] = $from[0]->mail ?? '';
			$data['from_name']  = $from[0]->personal ?? '';
			//////////////To LISt //////////////
                $to_list            = $message->getTo();
                $data['to_emails']  = $to_list[0]->mail ?? '';
				if(isset($to_list[1]->mail)&&$to_list[1]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[1]->mail;
				}
				if(isset($to_list[2]->mail)&&$to_list[2]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[2]->mail;
				}
				//////////////CC LISt //////////////
                $cc_list            = $message->getCc();
                $data['cc_emails']  = $cc_list[0]->mail ?? '';
				if(isset($cc_list[1]->mail)&&$cc_list[1]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[1]->mail;
				}
				if(isset($cc_list[2]->mail)&&$cc_list[2]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[2]->mail;
				}
				//////////////BCC LISt //////////////
                $bcc_list           = $message->getBcc();
                $data['bcc_emails'] = $bcc_list[0]->mail ?? '';
				if(isset($bcc_list[1]->mail)&&$bcc_list[1]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[1]->mail;
				}
				if(isset($bcc_list[2]->mail)&&$bcc_list[2]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[2]->mail;
				}

			$attachments_paths = [];
			$data['isattachments']=0;
			$uid=uniqid();
			$attachmentDir = 'attachments';
			$filePath = $attachmentDir . '/' . $uid;
			foreach ($message->getAttachments() as $attachment) {
				$attachments = $message->getAttachments();
				foreach ($attachments as $attachment) {
					if (!file_exists($filePath)) {
						mkdir($filePath, 0777, true);
					}	
					$fileName = $attachment->name;
					$attachment->save($filePath);
					$data['isattachments']=1;
					$attachments_paths[] = $filePath."/".$fileName;
				}
				$data['attachments'] = implode(',', $attachments_paths);
			}
			$cnt++;
			$data['isfalg']=0;
			$data['status']=1;
			$data['is_deleted']=0;
			$this->db->insert(db_prefix() . 'emails', $data);
		  }
		}
		
		$client->disconnect();	   
		$sortedMessages="Total Added :- ".$cnt;
		return $sortedMessages;
		
		////////////////////End Received EMAIL ///////
		
		}
		
		// function for get inbox mail list
        public function downloadmail($id)
        { 
		if(isset($id)&&$id){
		$mailers=$this->webmail_model->get_imap_details($id);
		}
		
		if(empty($mailers)){ 
		$downloadMessages="Email SMTP Details Not Found !!";
		return $downloadMessages;
		exit;
		}
		
		$mailer_imap_host=trim($mailers[0]['mailer_imap_host']);
        $mailer_imap_port=trim($mailers[0]['mailer_imap_port']);
        $mailer_username=trim($mailers[0]['mailer_username']);
		$data['email']=trim($mailers[0]['mailer_username']);
        $mailer_password=trim($mailers[0]['mailer_password']);
		$encryption=trim($mailers[0]['encryption']);
		
		
		
		
		
		try {
		 
		 $cm = new ClientManager();

    // Define the IMAP connection settings
    $client = $cm->make([
        'host'          => $mailer_imap_host,
        'port'          => $mailer_imap_port,
        'encryption'    => $encryption,
        'validate_cert' => true,
        'username'      => $mailer_username,
        'password'      => $mailer_password,
        'protocol'      => 'imap', 
		'timeout'       => 300            
    ]);
	
	
	if ($client->connect()) {
	
	
	
$folders = $client->getFolders();


$cnt=0;
foreach ($folders as $folder) { 

      $folder=$folder->name;
	  $data['folder'] = $folder; // for submit to db
      $mailbox = $client->getFolder($folder);
	 // print_r($mailbox);
      if ($mailbox === null) {
      die("The ".$folder." folder could not be found.");
      }
	  
	 
     
	  $last_email_id=$this->webmail_model->lastemailid($mailer_username, $folder);
	  $last_email_id = $last_email_id[0]['uniqid'] ?? 0;
	  
	  try {
	  $messages = $mailbox->query()->limit(10)->getByUidGreater($last_email_id);
      } catch (\Exception $e) {
      // Skip this message and continue with next one
      continue;
      }
	// Insert Email into DB
	foreach ($messages as $message) {

    $data['subject'] = $message->getSubject();
    $dateAttribute = $message->getDate();
                //$data['date']      = $dateAttribute;
				// Set Time Zone
				$carbonDate = $dateAttribute->first(); // Carbon\Carbon
				$carbonDate->setTimezone('Asia/Kolkata');
				$data['date'] = $carbonDate->format('Y-m-d H:i:s');
				$timezoneOffset = $carbonDate->format('P');
				$data['timezone']      = $timezoneOffset;
    $data['body'] = $message->getHtmlBody() ?? '';
	if($data['body']==""){$data['body'] = $message->getTextBody() ?? ''; }
	$data['uniqid'] = $message->uid;
	$data['messageid'] = $message->getMessageId();
	
	
	 // From
    $from = $message->getFrom(); // Returns array of Address objects
    $data['from_email'] = $from[0]->mail ?? '';
    $data['from_name']  = $from[0]->personal ?? '';
	//print_r($from);
	//echo "<br><br>";
	// To
  
	
    //////////////To LISt //////////////
                $to_list            = $message->getTo();
                $data['to_emails']  = $to_list[0]->mail ?? '';
				if(isset($to_list[1]->mail)&&$to_list[1]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[1]->mail;
				}
				if(isset($to_list[2]->mail)&&$to_list[2]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[2]->mail;
				}
				//////////////CC LISt //////////////
                $cc_list            = $message->getCc();
                $data['cc_emails']  = $cc_list[0]->mail ?? '';
				if(isset($cc_list[1]->mail)&&$cc_list[1]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[1]->mail;
				}
				if(isset($cc_list[2]->mail)&&$cc_list[2]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[2]->mail;
				}
				//////////////BCC LISt //////////////
                $bcc_list           = $message->getBcc();
                $data['bcc_emails'] = $bcc_list[0]->mail ?? '';
				if(isset($bcc_list[1]->mail)&&$bcc_list[1]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[1]->mail;
				}
				if(isset($bcc_list[2]->mail)&&$bcc_list[2]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[2]->mail;
				}


    // Handle attachments
    $attachments_paths = [];
    $data['isattachments']=0;
	$uid=uniqid();
	$attachmentDir = 'attachments';
	$filePath = $attachmentDir . '/' . $uid;
    foreach ($message->getAttachments() as $attachment) {
    $attachments = $message->getAttachments();
		
		// Create directory if it doesn't exist
					
		foreach ($attachments as $attachment) {
		
		if (!file_exists($filePath)) {
		mkdir($filePath, 0777, true);
		}	
				
		$fileName = $attachment->name;
		// Save the attachment
		$attachment->save($filePath);
		$data['isattachments']=1;
		$attachments_paths[] = $filePath."/".$fileName;
		}
		$data['attachments'] = implode(',', $attachments_paths);//exit;
 }
 $cnt++;
        $data['isfalg']=0;
		$data['status']=1;
		$data['is_deleted']=0;
       // $this->db->reconnect();
		$this->db->insert(db_prefix() . 'emails', $data);
		//echo $this->db->last_query();exit;
 
}

}
    
$client->disconnect();	   
	    // Get the inbox folder
      

	        $sortedMessages="Total Added :- ".$cnt;
			return $sortedMessages;
	  
	  }
	
   
	
		} catch (Exception $e) {
		//echo "ERROR 102";exit;
			echo "Error: " . $e->getMessage()."FFFFFF";exit;
			}
		exit;
	
       //echo "ERROR 103";exit;
        //return $this->db->get(db_prefix().'webmail_setup')->result_array();
      }
	  

	  
	  // function for get inbox mail list
       public function downloadmailbyfolder()
        {
        $result = ['msg' => 'Unable to download emails.', 'cnt' => 0];
        $mailer_imap_host = trim($_SESSION['webmail']['mailer_imap_host'] ?? '');
        $mailer_imap_port = trim($_SESSION['webmail']['mailer_imap_port'] ?? '');
        $mailer_username  = trim($_SESSION['webmail']['mailer_username'] ?? '');
        $mailer_password  = trim($_SESSION['webmail']['mailer_password'] ?? '');
        $encryption       = trim($_SESSION['webmail']['encryption'] ?? '');
        $folder           = trim($_SESSION['webmail']['folder'] ?? '');
        $data['email']    = $mailer_username;

        if ($mailer_username === '' || $mailer_password === '' || $folder === '') {
            $result['msg'] = 'Missing webmail session details.';
            return $result;
        }

        $bufferLevel = ob_get_level();
        ob_start();
        $handlerSet = false;
        $prevDisplayErrors = ini_get('display_errors');
        ini_set('display_errors', '0');
		ini_set('memory_limit', '1024M');

        try {
            $cm = new ClientManager();
            $client = $cm->make([
                'host'          => $mailer_imap_host,
                'port'          => $mailer_imap_port,
                'encryption'    => $encryption,
                'validate_cert' => true,
                'username'      => $mailer_username,
                'password'      => $mailer_password,
                'protocol'      => 'imap',
                'timeout'       => 300,
            ]);

            set_error_handler(function ($severity, $message, $file, $line) {
                if (!(error_reporting() & $severity)) {
                    return;
                }
                throw new ErrorException($message, 0, $severity, $file, $line);
            });
            $handlerSet = true;

            $cnt = 0;
            try {
                if (!$client->connect()) {
                    $result['msg'] = 'IMAP connection failed.';
                    return $result;
                }
            } catch (\Exception $e) {
                $result['msg'] = 'IMAP Error :-' . $e->getMessage();
                return $result;
            }

            $mailbox = $client->getFolder($folder);
            if ($mailbox === null) {
                $result['msg'] = 'Folder "' . $folder . '" not found on server.';
                $client->disconnect();
                return $result;
            }
            $data['folder'] = $folder;

            $total_Email   = $mailbox->query()->all()->count();
            $last_email_id = $this->webmail_model->lastemailid($mailer_username, $folder);
            $last_uid      = $last_email_id[0]['uniqid'] ?? 0;

            //log_message('error', 'Total Email: ' . $total_Email);
            //log_message('error', 'Last Email: ' . $last_uid);

            try {
                $messages = $mailbox->query()
                    ->limit(10)
                    ->getByUidGreater($last_uid);
            } catch (\Exception $e) {
                log_message('error', 'IMAP Error: ' . $e->getMessage());
                $messages = [];
            }

            foreach ($messages as $message) {
                $data['subject']   = $message->getSubject();
				$dateAttribute = $message->getDate();
                //$data['date']      = $dateAttribute;
				// Set Time Zone
				$carbonDate = $dateAttribute->first(); // Carbon\Carbon
				$carbonDate->setTimezone('Asia/Kolkata');
				$data['date'] = $carbonDate->format('Y-m-d H:i:s');
				$timezoneOffset = $carbonDate->format('P');
				$data['timezone']      = $timezoneOffset;
								
                $data['body']      = $message->getHtmlBody() ?? '';
                if ($data['body'] === '') {
                    $data['body'] = $message->getTextBody() ?? '';
                }
                $data['uniqid']     = $message->uid;
                $data['messageid']  = $message->getMessageId();
                $from               = $message->getFrom();
                $data['from_email'] = $from[0]->mail ?? '';
                $data['from_name']  = $from[0]->personal ?? '';
				
				//////////////To LISt //////////////
                $to_list            = $message->getTo();
                $data['to_emails']  = $to_list[0]->mail ?? '';
				if(isset($to_list[1]->mail)&&$to_list[1]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[1]->mail;
				}
				if(isset($to_list[2]->mail)&&$to_list[2]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[2]->mail;
				}
				//////////////CC LISt //////////////
                $cc_list            = $message->getCc();
                $data['cc_emails']  = $cc_list[0]->mail ?? '';
				if(isset($cc_list[1]->mail)&&$cc_list[1]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[1]->mail;
				}
				if(isset($cc_list[2]->mail)&&$cc_list[2]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[2]->mail;
				}
				//////////////BCC LISt //////////////
                $bcc_list           = $message->getBcc();
                $data['bcc_emails'] = $bcc_list[0]->mail ?? '';
				if(isset($bcc_list[1]->mail)&&$bcc_list[1]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[1]->mail;
				}
				if(isset($bcc_list[2]->mail)&&$bcc_list[2]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[2]->mail;
				}
				
                $attachments_paths   = [];
                $data['isattachments'] = 0;
                $uid                = uniqid();
                $attachmentDir      = 'attachments';
                $filePath           = $attachmentDir . '/' . $uid;

                $attachments = $message->getAttachments();
                foreach ($attachments as $attachment) {
                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777, true);
                    }
                    $fileName = $attachment->name;
                    $attachment->save($filePath);
                    $data['isattachments'] = 1;
                    $attachments_paths[]   = $filePath . "/" . $fileName;
                }
                $data['attachments'] = implode(',', $attachments_paths);

                $cnt++;
                $this->db->insert(db_prefix() . 'emails', $data);
            }

            $client->disconnect();
            $result['msg'] = 'Total Added :-' . $cnt;
            $result['cnt'] = 1;
        } catch (ImapServerErrorException $e) {
            log_message('error', 'IMAP Server Error: ' . $e->getMessage());
            $result['msg'] = ' IMAP Authentication failed - check credentials.';
        } catch (ResponseException $e) {
            log_message('error', 'IMAP Response Exception: ' . $e->getMessage());
            $result['msg'] = ' IMAP server response invalid.';
        } catch (Exception $e) {
            log_message('error', 'General Exception: ' . $e->getMessage());
            $result['msg'] = ' Error: ' . $e->getMessage();
        } finally {
            if ($handlerSet) {
                restore_error_handler();
            }
            while (ob_get_level() > $bufferLevel) {
                @ob_end_clean();
            }
            if ($prevDisplayErrors !== false) {
                ini_set('display_errors', $prevDisplayErrors);
            }
        }

        return $result;
      }
	  


    public function lastemailid($email, $folder)
    {
        $this->db->select('uniqid,');
		$this->db->where('email', $email);
        $this->db->where('folder', $folder);
        $this->db->limit(1);
		$this->db->order_by('uniqid', 'DESC');
        $result=$this->db->get(db_prefix() . 'emails')->result_array(); //return 
		//log_message('error', 'Last Email ID - ' . print_r($result, true));
		return $result;
		
    }
	
	 public function get_imap_details($id)
    {
        $this->db->select('mailer_username,mailer_password,mailer_imap_host,mailer_imap_port,encryption,');
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get(db_prefix() . 'webmail_setup')->result_array(); //return 
		//echo $this->db->last_query();exit;
    }
   
    public function make_isflag($mid,$fid)
    {
	    $data['isfalg']=$fid;
        $this->db->where('id', $mid);
		$this->db->update(db_prefix() . 'emails', $data);
        if ($this->db->affected_rows() > 0) {
		return 1;
		}else{
		return 0;
		}
        
		
    }
	
	 public function make_isdelete($mid,$fid)
    {
	    if($fid==2){
		$data['folder']='INBOX';
		}else{
	    $data['is_deleted']=$fid;
		}
        $this->db->where('id', $mid);
		$this->db->update(db_prefix() . 'emails', $data);
        if ($this->db->affected_rows() > 0) {
		return 1;
		}else{
		return 0;
		}
        
		
    }
	
	 public function make_isread($mid,$fid)
    {
	    $data['status']=$fid;
        $this->db->where('id', $mid);
		$this->db->update(db_prefix() . 'emails', $data);
        if ($this->db->affected_rows() > 0) {
		return 1;
		}else{
		return 0;
		}
        
		
    }
	
	
	// function for get inbox mail list
        public function downloadmailalluser()
        { 
		$this->db->select('mailer_name,mailer_email,mailer_username,mailer_password,mailer_imap_host,mailer_imap_port,encryption,');
        $this->db->where('mailer_status', 1);
		/* NOT NULL conditions */
		$this->db->where('mailer_name IS NOT NULL', null, false);
		$this->db->where('mailer_email IS NOT NULL', null, false);
		$this->db->where('mailer_username IS NOT NULL', null, false);
		$this->db->where('mailer_password IS NOT NULL', null, false);
		$this->db->where('mailer_imap_host IS NOT NULL', null, false);
		$this->db->where('mailer_imap_port IS NOT NULL', null, false);
		$this->db->where('encryption IS NOT NULL', null, false);
		
		/* NOT EMPTY conditions */
		$this->db->where('mailer_name !=', '');
		$this->db->where('mailer_email !=', '');
		$this->db->where('mailer_username !=', '');
		$this->db->where('mailer_password !=', '');
		$this->db->where('mailer_imap_host !=', '');
		$this->db->where('mailer_imap_port !=', '');
		$this->db->where('encryption !=', '');
		$this->db->order_by('id', 'asc');
		$this->db->group_by('mailer_email');
        $mailers=$this->db->get(db_prefix() . 'webmail_setup')->result_array();
		
		if(empty($mailers)){ 
			return "Email SMTP Details Not Found !!";
		}
		
		
		foreach ($mailers as $mailer) {
			$mailer_imap_host = trim($mailer['mailer_imap_host']);
			$mailer_imap_port = trim($mailer['mailer_imap_port']);
			$data['email']     = trim($mailer['mailer_email']);
			$mailer_username   = trim($mailer['mailer_username']);
			$mailer_password   = trim($mailer['mailer_password']);
			$encryption        = trim($mailer['encryption']);
			
			$cm = new ClientManager();

				try {
				
					$client = $cm->make([
						'host'          => $mailer_imap_host,
						'port'          => $mailer_imap_port,
						'encryption'    => $encryption, // ssl / tls / null
						'validate_cert' => true,
						'username'      => $mailer_username,
						'password'      => $mailer_password,
						'protocol'      => 'imap',
						'timeout'       => 300
					]);
				
					$client->connect();
				
					$folders = $client->getFolders();
		$cnt=0;
		foreach ($folders as $folder) { 
		  $folder=$folder->name;
		  $data['folder'] = $folder; // for submit to db
		  $mailbox = $client->getFolder($folder);
		  if ($mailbox === null) {
			continue;
		  }
		  
		  $last_email_id=$this->webmail_model->lastemailid($mailer_username, $folder);
		  $last_email_id = $last_email_id[0]['uniqid'] ?? 0;
		  
		  try {
			$messages = $mailbox->query()->limit(5)->getByUidGreater($last_email_id);
		  } catch (\Exception $e) {
			continue;
		  }

		  foreach ($messages as $message) {
			$data['subject'] = $message->getSubject();
			    $dateAttribute = $message->getDate();
				$carbonDate = $dateAttribute->first(); // Carbon\Carbon
				$carbonDate->setTimezone('Asia/Kolkata');
				$data['date'] = $carbonDate->format('Y-m-d H:i:s');
				$timezoneOffset = $carbonDate->format('P');
				$data['timezone']      = $timezoneOffset;
				
			$data['body'] = $message->getHtmlBody() ?? '';
			if($data['body']==""){$data['body'] = $message->getTextBody() ?? ''; }
			$data['uniqid'] = $message->uid;
			$data['messageid'] = $message->getMessageId();
			
			$from = $message->getFrom();
			$data['from_email'] = $from[0]->mail ?? '';
			$data['from_name']  = $from[0]->personal ?? '';
			
			    //////////////To LISt //////////////
                $to_list            = $message->getTo();
                $data['to_emails']  = $to_list[0]->mail ?? '';
				if(isset($to_list[1]->mail)&&$to_list[1]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[1]->mail;
				}
				if(isset($to_list[2]->mail)&&$to_list[2]->mail){
				$data['to_emails']=$data['to_emails'].', '.$to_list[2]->mail;
				}
				//////////////CC LISt //////////////
                $cc_list            = $message->getCc();
                $data['cc_emails']  = $cc_list[0]->mail ?? '';
				if(isset($cc_list[1]->mail)&&$cc_list[1]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[1]->mail;
				}
				if(isset($cc_list[2]->mail)&&$cc_list[2]->mail){
				$data['cc_emails']=$data['cc_emails'].', '.$cc_list[2]->mail;
				}
				//////////////BCC LISt //////////////
                $bcc_list           = $message->getBcc();
                $data['bcc_emails'] = $bcc_list[0]->mail ?? '';
				if(isset($bcc_list[1]->mail)&&$bcc_list[1]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[1]->mail;
				}
				if(isset($bcc_list[2]->mail)&&$bcc_list[2]->mail){
				$data['bcc_emails']=$data['bcc_emails'].', '.$bcc_list[2]->mail;
				}

			$attachments_paths = [];
			$data['isattachments']=0;
			$uid=uniqid();
			$attachmentDir = 'attachments';
			$filePath = $attachmentDir . '/' . $uid;
			foreach ($message->getAttachments() as $attachment) {
				$attachments = $message->getAttachments();
				foreach ($attachments as $attachment) {
					if (!file_exists($filePath)) {
						mkdir($filePath, 0777, true);
					}	
					$fileName = $attachment->name;
					$attachment->save($filePath);
					$data['isattachments']=1;
					$attachments_paths[] = $filePath."/".$fileName;
				}
				$data['attachments'] = implode(',', $attachments_paths);
			}
			$cnt++;
			$data['isfalg']=0;
			$data['status']=1;
			$data['is_deleted']=0;
			$this->db->insert(db_prefix() . 'emails', $data);
		  }
		}
		
		$client->disconnect();
				
				} catch (\Throwable $e) {
				
					
					continue;
				}
		}
		
		echo  "Email Downloaded";exit;
        }

}
