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
        $directSmtp = get_direct_mail_smtp(get_staff_company_id());
        $data['direct_smtp_raw'] = $directSmtp;
        $data['direct_smtp_config'] = $directSmtp ? json_decode($directSmtp, true) : [];
        $this->load->view('admin/directemail/email', $data);
    }

    public function save_direct_smtp()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        if (!$companyId) {
            echo json_encode(['success' => false, 'message' => 'Company not found.']);
            return;
        }

        $payload = [
            'smtp_encryption' => trim((string) $this->input->post('smtp_encryption')),
            'smtp_host'       => trim((string) $this->input->post('smtp_host')),
            'smtp_port'       => trim((string) $this->input->post('smtp_port')),
            'smtp_email'      => trim((string) $this->input->post('smtp_email')),
            'smtp_username'   => trim((string) $this->input->post('smtp_username')),
            'smtp_password'   => trim((string) $this->input->post('smtp_password')),
        ];

        if ($payload['smtp_host'] === '' || $payload['smtp_port'] === '' || $payload['smtp_email'] === '') {
            echo json_encode(['success' => false, 'message' => 'SMTP Host, Port, and Email are required.']);
            return;
        }

        $this->db->where('company_id', $companyId);
        $success = $this->db->update(db_prefix() . 'company_master', [
            'direct_mail_smtp' => json_encode($payload),
        ]);

        echo json_encode(['success' => (bool) $success, 'message' => $success ? 'SMTP details saved.' : 'Failed to save SMTP details.']);
    }

    public function save_nda_smtp()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        if (!$companyId) {
            echo json_encode(['success' => false, 'message' => 'Company not found.']);
            return;
        }

        $payload = [
            'smtp_encryption' => trim((string) $this->input->post('smtp_encryption')),
            'smtp_host'       => trim((string) $this->input->post('smtp_host')),
            'smtp_port'       => trim((string) $this->input->post('smtp_port')),
            'smtp_email'      => trim((string) $this->input->post('smtp_email')),
            'smtp_username'   => trim((string) $this->input->post('smtp_username')),
            'smtp_password'   => trim((string) $this->input->post('smtp_password')),
        ];

        if ($payload['smtp_host'] === '' || $payload['smtp_port'] === '' || $payload['smtp_email'] === '') {
            echo json_encode(['success' => false, 'message' => 'SMTP Host, Port, and Email are required.']);
            return;
        }

        $this->db->where('company_id', $companyId);
        $success = $this->db->update(db_prefix() . 'company_master', [
            'nda_smtp' => json_encode($payload),
        ]);

        echo json_encode(['success' => (bool) $success, 'message' => $success ? 'NDA SMTP details saved.' : 'Failed to save NDA SMTP details.']);
    }

    public function save_global_smtp()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        if (!$companyId) {
            echo json_encode(['success' => false, 'message' => 'Company not found.']);
            return;
        }

        $payload = [
            'smtp_encryption' => trim((string) $this->input->post('smtp_encryption')),
            'smtp_host'       => trim((string) $this->input->post('smtp_host')),
            'smtp_port'       => trim((string) $this->input->post('smtp_port')),
            'smtp_email'      => trim((string) $this->input->post('smtp_email')),
            'smtp_username'   => trim((string) $this->input->post('smtp_username')),
            'smtp_password'   => trim((string) $this->input->post('smtp_password')),
        ];

        if ($payload['smtp_host'] === '' || $payload['smtp_port'] === '' || $payload['smtp_email'] === '') {
            echo json_encode(['success' => false, 'message' => 'SMTP Host, Port, and Email are required.']);
            return;
        }

        $current = $this->db->select('settings')
            ->where('company_id', $companyId)
            ->get(db_prefix() . 'company_master')
            ->row_array();
        $existing = [];
        if (!empty($current['settings'])) {
            $existing = json_decode($current['settings'], true) ?: [];
        }
        $settings = array_merge($existing, $payload);

        $this->db->where('company_id', $companyId);
        $success = $this->db->update(db_prefix() . 'company_master', [
            'settings' => json_encode($settings),
        ]);

        echo json_encode(['success' => (bool) $success, 'message' => $success ? 'Global SMTP details saved.' : 'Failed to save SMTP details.']);
    }
    public function sendMail(){
        $this->output->set_content_type('application/json');
        $email_to = $this->input->post('email', true);
		$email_subject = $this->input->post('subject', true);
		$email_message = "<html>".$this->input->post('message', false)."</html>";
        if (empty($email_to) || empty($email_subject) || trim(strip_tags($email_message)) === '') {
            echo json_encode([
                'success' => false,
                'message' => 'Email, subject and message are required.',
            ]);
            return;
        }
        $attachments = [];
        if (!empty($_FILES['attachments']) && is_array($_FILES['attachments']['name'])) {
            $count = count($_FILES['attachments']['name']);
            for ($i = 0; $i < $count; $i++) {
                if (!isset($_FILES['attachments']['error'][$i])) {
                    continue;
                }
                if ($_FILES['attachments']['error'][$i] !== UPLOAD_ERR_OK) {
                    $errorCode = (int) $_FILES['attachments']['error'][$i];
                    if ($errorCode === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }
                    $errorMessage = 'Attachment upload failed.';
                    if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                        $errorMessage = 'Attachment is too large.';
                    } elseif ($errorCode === UPLOAD_ERR_PARTIAL) {
                        $errorMessage = 'Attachment was only partially uploaded.';
                    }
                    echo json_encode([
                        'success' => false,
                        'message' => $errorMessage,
                    ]);
                    return;
                }
                $tmpName = $_FILES['attachments']['tmp_name'][$i] ?? '';
                $origName = $_FILES['attachments']['name'][$i] ?? '';
                if ($tmpName && is_uploaded_file($tmpName)) {
                    $attachments[] = [
                        'path' => $tmpName,
                        'name' => $origName,
                    ];
                }
            }
        }
		//$emailArray = explode(";",$email_to);
		//$bulkEmails = array_map(function($email) {
			//return ['Email' => $email];
		//}, $emailArray);
		//$response=$this->send_attchment_message1($bulkEmails,$email_subject,$email_message); 
		//log_message('error', 'sendMail' . $email_subject);
		$response=$this->send_attchment_message_by_smtp($email_to,$email_subject,$email_message,$attachments);
        echo json_encode($response);
    }
    
	
	function send_attchment_message_by_smtp($bulkEmails, $email_subject, $email_message, $attachments = []) {
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
        return [
            'success' => false,
            'message' => 'Direct EMAIL SMTP Setting not configured',
        ];
	}
	

	
	
    $senderName       = get_staff_company_name() ? get_staff_company_name() : 'Mailert CRM';
	 //$senderName="Draft and Sign";//"NDA Esign";
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
        if (!empty($attachments)) {
            foreach ($attachments as $file) {
                if (!empty($file['path']) && is_file($file['path'])) {
                    $mail->addAttachment($file['path'], $file['name'] ?? '');
                }
            }
        }
        //$mail->SMTPDebug = 2; 
        //$mail->Debugoutput = 'error_log';
        $mail->send();
        return [
            'success' => true,
            'message' => 'Mail sent successfully.',
        ];
    } catch (Exception $e) {
        log_message('error', 'Email could not be sent. Error: ' . $mail->ErrorInfo .''.$mail->SMTPDebug);
        return [
            'success' => false,
            'message' => 'Failed to send mail: ' . $mail->ErrorInfo,
        ];
    }
}
}