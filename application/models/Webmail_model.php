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
		}elseif($search==1){
		
		$this->db->or_like($_SESSION['stype'], $_SESSION['skey']);
		$this->db->where('is_deleted', 0);
		}else{
		$this->db->where('is_deleted', 0);
		$this->db->where('folder', $folder);
		}
		$this->db->where('email', $mailer_email);
		//$this->db->group_by('id');
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
		}elseif($search==1){
		$this->db->or_like($_SESSION['stype'], $_SESSION['skey']);
		$this->db->where('is_deleted', 0);
		
		}else{
		$this->db->where('is_deleted', 0);
		$this->db->where('folder', $folder);
		}
		$this->db->where('email', $mailer_email);
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
		$mail = new PHPMailer(true);
		
		
	try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $mailer_smtp_host; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $mailer_username; // Replace with your email
    $mail->Password = $mailer_password; // Replace with your email password or app-specific password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $mailer_smtp_port;

    // Email settings
	$mail->isHTML(true); // Set email format to plain text
	$mail->CharSet = 'UTF-8';
	$mail->Encoding = 'base64';
	$mail->WordWrap = 50;               // set word wrap
	$mail->Priority = 1; 
	$mail->setFrom($senderEmail, $senderName);
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
	$mail->addBCC('onboarding@paycly.com');
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
		
	
		
		
		//print_r($_SESSION['webmail']);exit;
		
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
    $data['date'] = $message->getDate(); //->format('Y-m-d H:i:s')
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
  
	
    $to_list = $message->getTo(); // Returns array of Address objects
    $data['to_emails'] = $to_list[0]->mail ?? '';
   
	
	
   
    $cc_list = $message->getCc(); // Returns array of Address objects
    $data['cc_emails'] = $cc_list[0]->mail ?? '';
	
	
  
    // BCC
	$bcc_list = $message->getBcc(); // Returns array of Address objects
    $data['bcc_emails'] = $bcc_list[0]->mail ?? '';


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
	  
		public function downloadmailbbbbb($id)
		{
			if (!isset($id) || !$id) {
				return "Invalid Mailbox ID!";
			}
		
			$mailers = $this->webmail_model->get_imap_details($id);
			if (empty($mailers)) {
				return "Email SMTP Details Not Found !!";
			}
		
			$mailer_imap_host = trim($mailers[0]['mailer_imap_host']);
			$mailer_imap_port = trim($mailers[0]['mailer_imap_port']);
			$mailer_username  = trim($mailers[0]['mailer_username']);
			$mailer_password  = trim($mailers[0]['mailer_password']);
			$encryption       = trim($mailers[0]['encryption']);
			$data['email']    = $mailer_username;
		
			/*try {
				$cm = new ClientManager();
				$client = $cm->make([
					'host'          => $mailer_imap_host,
					'port'          => $mailer_imap_port,
					'encryption'    => $encryption,
					'validate_cert' => true,
					'username'      => $mailer_username,
					'password'      => $mailer_password,
					'protocol'      => 'imap',
					'timeout'       => 300 // longer timeout
				]);
		
				if ($client->connect()) {
					$folders = $client->getFolders();
					$cnt = 0;
		
					foreach ($folders as $folder) {
						try {
							$mailbox = $client->getFolder($folder->name);
							if ($mailbox === null) {
								continue; // skip invalid folder
							}
		
							$data['folder'] = $folder->name;
							$last_email_id  = $this->webmail_model->lastemailid($mailer_username, $folder->name);
							$last_email_id  = $last_email_id[0]['uniqid'] ?? 0;
							$pg = floor($last_email_id / 10) + 1;
		
							try {
								// Fetch messages safely
								$messages = $mailbox->query()
									->all()
									->limit(10, $pg)
									->get();
		
								if ($messages->count() == 0) {
									continue; // no new messages
								}
		
								// Filter only new UIDs
								$messages = $messages->filter(function ($message) use ($last_email_id) {
									return $message->getUid() > $last_email_id;
								});
		
								foreach ($messages as $message) {
									$data['subject']    = $message->getSubject();
									$data['date']       = $message->getDate();
									$data['body']       = $message->getHtmlBody() ?? $message->getTextBody() ?? '';
									$data['uniqid']     = $message->uid;
									$data['messageid']  = $message->getMessageId();
		
									$from               = $message->getFrom();
									$data['from_email'] = $from[0]->mail ?? '';
									$data['from_name']  = $from[0]->personal ?? '';
		
									$to_list            = $message->getTo();
									$data['to_emails']  = $to_list[0]->mail ?? '';
		
									$cc_list            = $message->getCc();
									$data['cc_emails']  = $cc_list[0]->mail ?? '';
		
									$bcc_list           = $message->getBcc();
									$data['bcc_emails'] = $bcc_list[0]->mail ?? '';
		
									// Handle attachments
									$attachments_paths  = [];
									$data['isattachments'] = 0;
									$uid = uniqid();
									$attachmentDir = 'attachments';
									$filePath = $attachmentDir . '/' . $uid;
		
									foreach ($message->getAttachments() as $attachment) {
										if (!file_exists($filePath)) {
											mkdir($filePath, 0777, true);
		
										}
										$fileName = $attachment->name;
										$attachment->save($filePath);
										$data['isattachments'] = 1;
										$attachments_paths[] = $filePath . "/" . $fileName;
									}
		
									$data['attachments'] = implode(',', $attachments_paths);
		
									// Force reconnect before insert
									$this->db->close();
									$this->db->initialize();
		
									try {
										$this->db->insert(db_prefix() . 'emails', $data);
										$cnt++;
									} catch (Exception $e) {
										log_message('error', 'DB Insert Failed: ' . $e->getMessage());
										continue;
									}
								}
							} catch (\Webklex\PHPIMAP\Exceptions\GetMessagesFailedException $e) {
								log_message('error', "IMAP error in folder {$folder->name}: " . $e->getMessage());
								continue;
							}
						} catch (Exception $e) {
							log_message('error', "Folder skipped: " . $e->getMessage());
							continue;
						}
					}
		
					$client->disconnect();
					return "Total Added: " . $cnt;
				}
			} catch (Exception $e) {
				return "Error: " . $e->getMessage();
			}*/
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
				'timeout'       => 300
			]);
		
			if (!$client->connect()) {
				throw new Exception("IMAP Connection failed");
			}
		
			$cnt = 0;
			$folders = $client->getFolders();
		
			foreach ($folders as $folder) {
				try {
					// Skip system folders
					if (in_array(strtolower($folder->name), ['spam', 'junk', 'trash'])) {
						continue;
					}
		
					$data['folder'] = $folder->name;
					$last_email_id  = $this->webmail_model->lastemailid($mailer_username, $folder->name);
					$last_email_id  = $last_email_id[0]['uniqid'] ?? 0;
		
					// Get only 10 recent messages after last UID
					$messages = $folder->messages()
						->since(now()->subDays(7)) // fetch recent only
						->limit(10)
						->get();
		
					if ($messages->count() == 0) continue;
		
					// Filter only new
					$messages = $messages->filter(function ($m) use ($last_email_id) {
						return $m->getUid() > $last_email_id;
					});
		
					foreach ($messages as $message) {
						$data = [
							'folder'       => $folder->name,
							'subject'      => $message->getSubject(),
							'date'         => $message->getDate(),
							'body'         => $message->getHtmlBody() ?? $message->getTextBody() ?? '',
							'uniqid'       => $message->uid,
							'messageid'    => $message->getMessageId(),
							'from_email'   => $message->getFrom()[0]->mail ?? '',
							'from_name'    => $message->getFrom()[0]->personal ?? '',
							'to_emails'    => $message->getTo()[0]->mail ?? '',
							'cc_emails'    => $message->getCc()[0]->mail ?? '',
							'bcc_emails'   => $message->getBcc()[0]->mail ?? '',
							'isattachments'=> 0,
							'attachments'  => ''
						];
		
						// Save attachments
						$attachments_paths = [];
						foreach ($message->getAttachments() as $attachment) {
							$uid = uniqid();
							$dir = FCPATH . 'uploads/email_attachments/' . $uid;
							if (!is_dir($dir)) mkdir($dir, 0777, true);
							$attachment->save($dir);
							$attachments_paths[] = $dir . '/' . $attachment->name;
							$data['isattachments'] = 1;
						}
		
						$data['attachments'] = implode(',', $attachments_paths);
		
						// Insert safely
						$this->db->insert(db_prefix() . 'emails', $data);
						$cnt++;
		
						unset($message); // free memory
					}
				} catch (\Webklex\PHPIMAP\Exceptions\ResponseException $e) {
					log_message('error', "IMAP Folder Error [{$folder->name}]: " . $e->getMessage());
					continue;
				} catch (Exception $e) {
					log_message('error', "Folder skipped [{$folder->name}]: " . $e->getMessage());
					continue;
				}
			}
		
			$client->disconnect();
			return "Total emails downloaded: $cnt";
		
		} catch (\Webklex\PHPIMAP\Exceptions\ResponseException $e) {
			log_message('error', 'IMAP Connection Error: ' . $e->getMessage());
			return "Error: IMAP connection failed";
		} catch (Exception $e) {
			log_message('error', 'General Exception: ' . $e->getMessage());
			return "Error: " . $e->getMessage();
		}
		
			
		}

		public function downloadmail_latest($id)
		{
		
		
			// ===============================
			// Validate Mailbox
			// ===============================
			if (!isset($id) || !$id) {
				return "Invalid Mailbox ID!";
			}
		
			// Increase execution time for email processing
			set_time_limit(300); // 5 minutes
			ini_set('max_execution_time', 300);
		
			$mailers = $this->get_imap_details($id);
			if (empty($mailers)) {
				return "Email IMAP details not found!";
			}
		
			$mailer_imap_host = trim($mailers[0]['mailer_imap_host']);
			$mailer_imap_port = trim($mailers[0]['mailer_imap_port']);
			$mailer_username  = trim($mailers[0]['mailer_username']);
			$mailer_password  = trim($mailers[0]['mailer_password']);
			$encryption       = trim($mailers[0]['encryption']);
			$data['email']    = $mailer_username;
		
			// ===============================
			// Connect to IMAP
			// ===============================
			try {
				$cm = new ClientManager();
				
				$client_config = [
			'host'          => $mailer_imap_host,
			'port'          => $mailer_imap_port,
			'encryption'    => $encryption,   // 'ssl' or 'tls'
			'validate_cert' => true,
			'username'      => $mailer_username,
			'password'      => $mailer_password,    // mask password for logs
			'protocol'      => 'imap',
			'timeout'       => 300
		];
		
		// Log the IMAP connection details
		log_message('error', 'IMAP Details: ' . json_encode($client_config, JSON_PRETTY_PRINT));
		
				$client = $cm->make([
					'host'          => $mailer_imap_host,
					'port'          => $mailer_imap_port,
					'encryption'    => $encryption,   // 'ssl' or 'tls'
					'validate_cert' => true,
					'username'      => $mailer_username,
					'password'      => $mailer_password,
					'protocol'      => 'imap',
					'timeout'       => 600
				]);
		
				try {
					if (!$client->connect()) {
						return "Unable to connect to IMAP server ($mailer_imap_host). Check host/port.";
					}
				} catch (ImapServerErrorException $e) {
					log_message('error', 'IMAP Login Failed: ' . $e->getMessage());
					return "Authentication failed - please check username/password.";
				} catch (ResponseException $e) {
					log_message('error', 'IMAP Response Error: ' . $e->getMessage());
					return "IMAP server error - please verify IMAP configuration.";
				}
		
				// ===============================
				// 3 Fetch Folders
				// ===============================
				$folders = $client->getFolders();
				$cnt = 0;
		
				foreach ($folders as $folder) {
					try {
						// Skip system folders
						if (in_array(strtolower($folder->name), ['spam', 'junk', 'trash', '[gmail]/spam'])) {
							continue;
						}
		
						$data['folder'] = $folder->name;
						$last_email_id  = $this->lastemailid($mailer_username, $folder->name);
						//print_r($last_email_id);
						$last_email_id  = $last_email_id[0]['uniqid'] ?? 0;
						
		
						// ===============================
						// Fetch Recent Messages
						// ===============================
						try {
							// Fetch a larger batch to ensure we get new messages beyond the first 10
							// We'll fetch up to 100 messages and filter to get the next 10 new ones
							$fetch_limit = 10; // Fetch more messages to find new ones
							$target_new_messages = 10; // We want to process 10 new messages
							
							// Fetch messages in larger batches to find new ones
							$messages = $folder->messages()->all()->limit($fetch_limit)->get();
							
						} catch (GetMessagesFailedException $e) {
							log_message('error', "IMAP message fetch error in {$folder->name}: " . $e->getMessage());
							continue;
						} catch (\Exception $e) {
							log_message('error', "Error fetching messages from {$folder->name}: " . $e->getMessage());
							continue;
						}
		
						if ($messages->count() == 0) {
							log_message('info', "No messages found in folder: {$folder->name}");
							continue;
						}
		
						// Store original count before filtering
						$original_count = $messages->count();
						
						
						// Log for debugging
						log_message('info', "Folder: {$folder->name}, Total messages fetched: {$original_count}, Last email UID: {$last_email_id}");
		
						// Filter only new messages (UIDs greater than last downloaded)
						$messages = $messages->filter(function ($m) use ($last_email_id) {
							$uid = $m->getUid();
							return $uid > $last_email_id;
						});
		
						// Log filtered count
						$filtered_count = $messages->count();
						log_message('info', "Folder: {$folder->name}, New messages after filtering: {$filtered_count}");
					   
						// If no new messages after filtering, skip this folder
						if ($filtered_count == 0) {
							if ($original_count > 0) {
								log_message('info', "Folder: {$folder->name}, All {$original_count} fetched messages have UID <= {$last_email_id}. No new messages to download.");
							}
							continue; // Skip to next folder if no new messages
						}
						
						// Limit processing to target number of new messages per batch
						$processed_count = 0;
						$message_index = 0;
						foreach ($messages as $message) {
							// Stop if we've processed the target number of new messages
							if ($processed_count >= $target_new_messages) {
								log_message('info', "Folder: {$folder->name}, Processed {$target_new_messages} new messages. Remaining will be processed in next batch.");
								break;
							}
							
							$message_index++;
							 
							// Check execution time periodically to avoid timeout
							if ($message_index % 10 == 0) {
								$elapsed = time() - (isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time());
								if ($elapsed > 100) { // If more than 100 seconds elapsed, stop processing
									log_message('error', "Stopping email processing in {$folder->name} to avoid timeout. Processed {$message_index} messages.");
									break;
								}
							}
							
							try { 
								$data = [
									'email'         => $mailer_username,
									'folder'        => $folder->name,
									'subject'       => $message->getSubject(),
									'date'          => $message->getDate(),
									'body'          => $message->getHtmlBody() ?? $message->getTextBody() ?? '',
									'uniqid'        => $message->uid,
									'messageid'     => $message->getMessageId(),
									'from_email'    => $message->getFrom()[0]->mail ?? '',
									'from_name'     => $message->getFrom()[0]->personal ?? '',
									'to_emails'     => $message->getTo()[0]->mail ?? '',
									'cc_emails'     => $message->getCc()[0]->mail ?? '',
									'bcc_emails'    => $message->getBcc()[0]->mail ?? '',
									'isattachments' => 0,
									'attachments'   => ''
								];
								
								
		
								// ===============================
								// Handle Attachments
								// ===============================
								$attachments_paths = [];
								try {
									foreach ($message->getAttachments() as $attachment) {
										$uid = uniqid();
										$dir = FCPATH . 'uploads/email_attachments/' . $uid;
										if (!is_dir($dir)) mkdir($dir, 0777, true);
										$attachment->save($dir);
										$attachments_paths[] = $dir . '/' . $attachment->name;
										$data['isattachments'] = 1;
									}
								} catch (\Exception $e) {
									log_message('error', "Error saving attachment for message UID {$data['uniqid']}: " . $e->getMessage());
									// Continue without attachment
								}
								$data['attachments'] = implode(',', $attachments_paths);
		
								// ===============================
								//Insert into Database
								// ===============================
								try {
								
								
									// Check if email already exists (by uniqid, email, and folder to avoid duplicates)
									$this->db->where('uniqid', $data['uniqid']);
									$this->db->where('email', $data['email']);
									$this->db->where('folder', $data['folder']);
									$existing = $this->db->get(db_prefix() . 'emails')->row();
									//echo $this->db->last_query();exit;
									
									if (!$existing) { //echo "SSSSSSSSSSSS";exit;
										$this->db->insert(db_prefix() . 'emails', $data);
										if ($this->db->insert_id()) {
											$cnt++;
											log_message('info', "Inserted email: {$data['subject']} (UID: {$data['uniqid']})");
										} else {
											log_message('error', "Failed to insert email: {$data['subject']} (UID: {$data['uniqid']})");
										}
									} else {
										log_message('info', "Email already exists, skipped: {$data['subject']} (UID: {$data['uniqid']})");
									}
									
									// Increment processed count after processing this message
									$processed_count++;
								} catch (Exception $e) {
									log_message('error', 'DB Insert Failed for UID ' . ($data['uniqid'] ?? 'unknown') . ': ' . $e->getMessage());
									// Still increment processed count even if insert failed
									$processed_count++;
									continue;
								}
							} catch (\Exception $e) {
								log_message('error', "Error processing message in {$folder->name}: " . $e->getMessage());
								// Still increment processed count even if processing failed
								$processed_count++;
								continue; // Skip this message and continue with next
							}
		
							unset($message); // free memory
						}
					} catch (ResponseException $e) {
						log_message('error', "IMAP Folder Error [{$folder->name}]: " . $e->getMessage());
						continue;
					} catch (Exception $e) {
						log_message('error', "Folder skipped [{$folder->name}]: " . $e->getMessage());
						continue;
					}
				}
		
				// ===============================
				//Clean Disconnect
				// ===============================
				$client->disconnect();
				
				// Log final result
				log_message('info', "Email download completed. Total emails downloaded: {$cnt}");
				
				if ($cnt == 0) {
					return "Total emails downloaded: 0. (No new emails found. All emails may have already been downloaded, or check logs for details.)";
				}
				
				return "Total emails downloaded: {$cnt}";
		
			} catch (ImapServerErrorException $e) {
				log_message('error', 'IMAP Server Error: ' . $e->getMessage());
				return " IMAP Authentication failed - check credentials.";
			} catch (ResponseException $e) {
				log_message('error', 'IMAP Response Exception: ' . $e->getMessage());
				return " IMAP server response invalid.";
			} catch (Exception $e) {
				log_message('error', 'General Exception: ' . $e->getMessage());
				return " Error: " . $e->getMessage();
			}
		}


	  
	  // function for get inbox mail list
        public function downloadmailbyfolder()
        {
		
		$mailer_imap_host=trim($_SESSION['webmail']['mailer_imap_host']);
        $mailer_imap_port=trim($_SESSION['webmail']['mailer_imap_port']);
        $mailer_username=trim($_SESSION['webmail']['mailer_username']);
		$data['email']=trim($_SESSION['webmail']['mailer_username']);
        $mailer_password=trim($_SESSION['webmail']['mailer_password']);
		$encryption=trim($_SESSION['webmail']['encryption']);
		$folder=trim($_SESSION['webmail']['folder']);
		
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
		//'authentication' => "oauth"            // Protocol (imap/pop3)
    ]);
	
// convert warnings to exceptions
set_error_handler(function($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) return;
    throw new ErrorException($message, 0, $severity, $file, $line);
});

$cnt=0;	
$connection=false;
             // Check IMAP connection
			try {
			if ($client->connect()) {
			$connection=true;
			} else {
			$data['msg']="IMAP Error :-".$e->getMessage();
			$data['cnt']=0;
			return $data;
			
			}
			} catch (\Exception $e) {
			$data['msg']="IMAP Error :-".$e->getMessage();
			$data['cnt']=0;
			return $data;
			}

	
	if ($connection) {
 
	$mailbox = $client->getFolder($folder);
	 if ($mailbox === null) {
      die("The ".$folder." folder could not be found.");exit;
      }
	  $data['folder']=$folder;


     
	  $total_Email=$mailbox->query()->all()->count();
	  $last_email_id=$this->webmail_model->lastemailid($mailer_username, $folder);
	  $last_uid=$last_email_id[0]['uniqid']?? 0;//exit;
	  
	  		log_message('error', 'Total Email: ' . $total_Email);
			log_message('error', 'Last Email: ' . $last_uid);

	  $messages = $mailbox->query()->limit(25)->getByUidGreater($last_uid);
	 
      /*$pg=floor($last_email_id / 10) +1;
	  $messages = $mailbox->query()
    ->all()->limit($limit = 10, $page = $pg)
    ->get() // fetch messages
    ->filter(function($message) use ($last_email_id) {
        return $message->getUid() > $last_email_id;
    });*/

   


//print_r($messages);exit;
foreach ($messages as $message) {

    $data['subject'] = $message->getSubject();
    $data['date'] = $message->getDate(); //->format('Y-m-d H:i:s')
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
  
	
    $to_list = $message->getTo(); // Returns array of Address objects
    $data['to_emails'] = $to_list[0]->mail ?? '';
   
	
	
   
    $cc_list = $message->getCc(); // Returns array of Address objects
    $data['cc_emails'] = $cc_list[0]->mail ?? '';
	
	
  
    // BCC
	$bcc_list = $message->getBcc(); // Returns array of Address objects
    $data['bcc_emails'] = $bcc_list[0]->mail ?? '';


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
		$this->db->insert(db_prefix() . 'emails', $data);
		//echo $this->db->last_query();exit;
 
}

    
$client->disconnect();	   
	    // Get the inbox folder
      

	        
			$data['msg']="Total Added :-".$cnt;
			$data['cnt']=1;
			return $data;exit;
	  
	  }
	
   
	
		} catch (Exception $e) {
		    $data['msg']="Error: " . $e->getMessage();
			$data['cnt']=0;
			return $data;exit;
			}
		return $data;exit;
	
       //echo "ERROR 103";exit;
        //return $this->db->get(db_prefix().'webmail_setup')->result_array();
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
	
	
    
    
}
