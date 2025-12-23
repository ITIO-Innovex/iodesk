<?php
defined('BASEPATH') or exit('No direct script access allowed');
//use Webklex\PHPIMAP\ClientManager;
//use Webklex\PHPIMAP\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPPATH.'/vendor/vendor/autoload.php';

class Direct_email extends AdminController
{
    public function __construct()
    {
        parent::__construct();

    }

    public function index()
    {
        $data['title']          = 'Direct E-mail';
		$data['email_signature'] = get_staff_signature();
        $this->load->view('admin/directemail/email', $data);
    }
    public function sendMail(){
        $email_to=$_POST['email'];
		$email_subject=$_POST['subject'];
		$email_message="<html>".$_POST['message']."</html>";
		//$emailArray = explode(";",$email_to);
		//$bulkEmails = array_map(function($email) {
			//return ['Email' => $email];
		//}, $emailArray);
		//$response=$this->send_attchment_message1($bulkEmails,$email_subject,$email_message); 
		//log_message('error', 'sendMail' . $email_subject);
		$response=$this->send_attchment_message_by_smtp($email_to,$email_subject,$email_message); 
	   $pst['msg'] = "Your message has been successfully sent to " . $email_to;
	   $pst['response'] = $response;
	   echo json_encode($pst);
    }
    
	
	function send_attchment_message_by_smtp($bulkEmails, $email_subject, $email_message) {
    $subject = $email_subject;
    $body = $email_message;
	
	$directdata=get_direct_mail_smtp(get_staff_company_id());
	
	if(isset($directdata)&&$directdata){
	
	$config = json_decode($directdata, true);
	
    $mailer_smtp_host = trim($config['smtp_host']);
	$senderEmail= trim($config['smtp_email']);
	$mailer_username= trim($config['smtp_username']);
	$mailer_password= trim($config['smtp_password']);
	$mailer_smtp_port= trim($config['smtp_port']);
	
	}else{
	echo "Direct EMAIL SMTP Setting not configured";exit;
	}
	

	
	
    //$senderName       = get_staff_company_name() ? get_staff_company_name() : 'Mailert CRM';
	$senderName="Draft and Sign";//"NDA Esign";
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $mailer_smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailer_username;
        $mail->Password   = $mailer_password;
		$port_config = [
    					587 => PHPMailer::ENCRYPTION_STARTTLS,
    					465 => PHPMailer::ENCRYPTION_SMTPS,
    					25  => false
		];

		$mail->SMTPSecure = $port_config[$mailer_smtp_port] ?? false;
		$mail->Port       = $mailer_smtp_port;


        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($senderEmail, $senderName);
        //$mail->addAddress('vikashg@bigit.io,vikashg@itio.in');
		$bulkEmails=str_replace(";",",",$bulkEmails);
		$recipients = explode(",", $bulkEmails);
        // Add each email to PHPMailer
		foreach ($recipients as $email) {
			$email = trim($email); // remove spaces
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$mail->addAddress($email);
			}
		}
		//log_message('error', '!!!'.$email_subject);
        $mail->Subject = $subject;
        $mail->Body    = $body;
        //$mail->SMTPDebug = 2; 
        //$mail->Debugoutput = 'error_log';
        $mail->send();
        //log_message('error', 'Email Sent');
        //return true;
		$http_code=200;
		$response='Success';
		return 'HTTP Code: ' . $http_code . ' Response: ' . $response;
    } catch (Exception $e) {
        log_message('error', 'Email could not be sent. Error: ' . $mail->ErrorInfo .''.$mail->SMTPDebug);
        $http_code=400;
		$response='Failed';
		return 'HTTP Code: ' . $http_code . ' Response: ' . $response;
    }
}
}