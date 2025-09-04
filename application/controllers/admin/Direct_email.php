<?php
defined('BASEPATH') or exit('No direct script access allowed');
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Client;
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
		$emailArray = explode(";",$email_to);
		$bulkEmails = array_map(function($email) {
			return ['Email' => $email];
		}, $emailArray);
		//$response=$this->send_attchment_message1($bulkEmails,$email_subject,$email_message); 
		//log_message('error', 'sendMail' . $email_subject);
		$response=$this->send_attchment_message_by_smpt($bulkEmails,$email_subject,$email_message); 
	   $pst['msg'] = "Your message has been successfully sent to " . $email_to;
	   $pst['response'] = $response;
	   echo json_encode($pst);
    }
    function send_attchment_message1($bulkEmails,$email_subject,$email_message){
        $email_from='PAYCLY <info@paycly.com>';
        $email_reply='PAYCLY <info@paycly.com>';
        // TechWizard Logic
        //$apiKey = '66D2CD590806CC7B4D826B621729CCDE154FB77DB40D9C50B3ABD1A00E56B1DE5FCB29FD311552A0FCE88D9D1378764F';
		$apiKey = '2BEC2EC03749B5A74BBDC1C95365EB21BFFEAFF64ED98FE02BD67740D05A1686D5BAD64D530E82374E324CE437C8FF68'; //vikash
        $url = 'https://api.elasticemail.com/v4/emails';
        $postData = [
            'Recipients' => $bulkEmails,
            'Content' => [
                'Body' => [
                    [
                        'ContentType' => 'HTML',
                        'Content' => $email_message,
                        'Charset' => 'utf-8'
                    ]
                ],
                'From' => $email_from,
                'ReplyTo' => $email_reply,
                'Subject' => $email_subject
            ],
            'Options' => [
                'TrackOpens' => true,
                'TrackClicks' => true
            ]
        ];
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'X-ElasticEmail-ApiKey: ' . $apiKey
            ]
        ]);
        $response = curl_exec($ch);
        $curl_error = curl_error($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($response === false) {
            return 'Curl error: ' . $curl_error;
        }
        return 'HTTP Code: ' . $http_code . ' Response: ' . $response;
        // TechWizard logic End
    }
	
	function send_attchment_message_by_smpt($bulkEmails, $email_subject, $email_message) {
    $subject = $email_subject;
    $body = $email_message;

    $mailer_smtp_host = "smtppro.zoho.in";//smtp-relay.brevo.com
    $mailer_smtp_port = 465; // use 465 if you want SSL 587
    $mailer_username  = "mailers@itio.in";//7a1e20002@smtp-brevo.com
    $mailer_password  = "India@992@@";//Y2FnXGDyZvRBbzAr
    $senderEmail 	  = "mailers@itio.in";
    $senderName       = "CRM";
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $mailer_smtp_host;
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailer_username;
        $mail->Password   = $mailer_password;
        //Match security with port
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $mailer_smtp_port;
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->setFrom($senderEmail, $senderName);
        $mail->addAddress('vikashg@bigit.io');
        //foreach ($bulkEmails as $email) {
            //$mail->addAddress($email);
        //}
		//log_message('error', '!!!'.$email_subject);
        $mail->Subject = $subject;
        $mail->Body    = $body;
		//log_message('error', 'subject'.$subject);
		//log_message('error', 'Body'.$body);
        $mail->SMTPDebug = 2; 
        $mail->Debugoutput = 'error_log';
        $mail->send();
        log_message('error', 'Email Sent');
        return true;
    } catch (Exception $e) {
        log_message('error', 'Email could not be sent. Error: ' . $mail->ErrorInfo);
        return false;
    }
}
}