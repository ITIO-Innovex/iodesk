<?php
use PHPMailer\PHPMailer\PHPMailer; // Added on 22122025 for NDA Esign Email
use PHPMailer\PHPMailer\Exception;  // Added on 22122025 for NDA Esign Email
use PHPMailer\PHPMailer\SMTP;
header('Content-Type: text/html; charset=utf-8');

defined('BASEPATH') or exit('No direct script access allowed');

class Services_subscriptions_model extends App_Model
{
    private $table = 'it_crm_services_subscriptions';

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }

    public function add($data)
    {
        $insert = $this->normalize_payload($data);
        if (!$insert) {
            return false;
        }

        $insert['created_at'] = date('Y-m-d H:i:s');
        $insert['updated_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->table, $insert);
        $id = $this->db->insert_id();
        if ($id) {
            log_activity('Service Subscription Added [Plan: ' . $insert['plan_name'] . ', ID: ' . $id . ']');
        }
        return $id;
    }

    public function update($id, $data)
    {
        $update = $this->normalize_payload($data, false);
        if (!$update) {
            return false;
        }

        $update['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        $this->db->update($this->table, $update);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Subscription Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Subscription Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    private function normalize_payload($data, $requireAll = true)
    {
        $plan_name = trim($data['plan_name'] ?? '');
        $price = isset($data['price']) ? (float) $data['price'] : null;
        $currency = trim($data['currency'] ?? 'INR');
        $billing_cycle = $data['billing_cycle'] ?? '';
        if ($billing_cycle === 'per_month') {
            $billing_cycle = 'pro_data';
        }
        $duration = isset($data['duration']) ? (int) $data['duration'] : 0;
        $no_of_staff = isset($data['no_of_staff']) && $data['no_of_staff'] !== ''
            ? (int) $data['no_of_staff']
            : null;
        $tax = isset($data['tax']) ? (float) $data['tax'] : 0.00;
        $features = $data['features'] ?? null;
        $status = $data['status'] ?? 'active';

        $allowed_cycles = ['monthly', 'yearly', 'pro_data'];
        $allowed_status = ['active', 'inactive'];

        if ($requireAll) {
            if ($plan_name === '' || $price === null || $currency === '' || $billing_cycle === '' || $duration <= 0) {
                return false;
            }
        }

        if ($billing_cycle !== '' && !in_array($billing_cycle, $allowed_cycles, true)) {
            return false;
        }

        if (!in_array($status, $allowed_status, true)) {
            $status = 'active';
        }

        return [
            'plan_name'     => $plan_name,
            'price'         => $price ?? 0,
            'currency'      => $currency,
            'billing_cycle' => $billing_cycle !== '' ? $billing_cycle : 'monthly',
            'duration'      => $duration,
            'no_of_staff'   => $no_of_staff,
            'tax'           => $tax,
            'features'      => $features,
            'status'        => $status,
        ];
    }
	
	public function log_service_activity($subscription_id, $service_type, $description)
    {
        $log = [
            'date'            => date('Y-m-d H:i:s'),
            'description'     => $description,
			'staffid'         => get_staff_user_id(),
			'company_id'      => get_staff_company_id(),
            'subscription_id' => $subscription_id,
			'service_type'    => $service_type
        ];
        

        $this->db->insert(db_prefix() . 'service_log', $log);

        return $this->db->insert_id();
    }	
	
	
	
	public function get_staff_expansion($extrastaffcount)
    {
       
		$this->db->select('amount');
		$this->db->from('it_crm_services_subscriptions_invoices');
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('invoice_type', 'Staff Expansion');   // empty string
		$this->db->where('staff_added', $extrastaffcount);
		$query = $this->db->get();
		$result = $query->row(); // single row
		
		return $amount = $result ? $result->amount : 0;

        
    }
	
	// Toggle Deal Stage Status (AJAX)
    public function send_invoice_email($invoice_no)
    {
	
	//$invoice_no = "1000820260130154434";

$this->db->select('
    i.amount,
    i.currency,
    i.tax,
    i.total_amount,
    i.payment_status,
    i.payment_method,
    i.payment_id,
    i.created_at,
    c.companyname,
    c.firstname,
    c.lastname,
    c.email
');

$this->db->from('it_crm_services_subscriptions_invoices i');
$this->db->join(
    'it_crm_company_master c',
    'c.company_id = i.company_id',
    'left'
);

$this->db->where('i.invoice_no', $invoice_no);

$query = $this->db->get();
$result = $query->result_array(); // or result()

if(isset($result)&&$result){
$result=$result[0];
$amount=$result['amount'] ?? '';
$currency=$result['currency'] ?? '';
$tax=$result['tax'] ?? '';
$total_amount=$result['total_amount'] ?? '';
$payment_status=$result['payment_status'] ?? '';
$payment_method=$result['payment_method'] ?? '';
$payment_id=$result['payment_id'] ?? '';
$created_at=$result['created_at'] ?? '';
$companyname=$result['companyname'] ?? '';
$firstname=$result['firstname'] ?? '';
$lastname=$result['lastname'] ?? '';
$email=$result['email'] ?? '';
}

     //print_r($result);exit;
	//print_r($_SESSION['SUPERSMTP']);
	$name  = $firstname." ".$lastname;
    $recipientEmail = $email ?? 'vikashg@itio.in';
	$supercompanyname=get_option('companyname') ?? "Support CRM";
	$supersupportemail=get_option('support_email') ?? "support@itio.in";
	$supernotificationemail = get_option('notification_email') ?: 'vikashg@itio.in';

	
	
	if(isset($_SESSION['SUPERSMTP'])&&$_SESSION['SUPERSMTP']){	
    $mailer_smtp_host = trim($_SESSION['SUPERSMTP']['smtp_host']);
	$senderEmail= trim($_SESSION['SUPERSMTP']['smtp_user']);
	$mailer_username= trim($_SESSION['SUPERSMTP']['smtp_user']);
	$mailer_password= base64_decode(trim($_SESSION['SUPERSMTP']['smtp_pass']));
	$mailer_smtp_port= trim($_SESSION['SUPERSMTP']['smtp_port']);
	$senderName="Payment Status";//"NDA Esign";
	}else{
	//echo "SUPER SMTP Setting not configured";exit;
	}
	


	 
	 ////////////Sent EMAIL////////////
	 
//$recipientEmail="vikashg@itio.in";	 
//$name="Vimalesh";
$mailSub="Payment Confirmation # Invoice No - ".$invoice_no;
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

            <p style="margin:0 0 16px;">Dear <strong>'.$name.'</strong>,</p>
<p>We hope you are doing well. This email is to confirm the payment status for the following invoice:</p>

            <p style="margin:0 0 20px; text-align:left;">
<strong>Customer Details</strong>
<br>Name: '.$firstname.' '.$lastname.'
<br>Company Name: '.$companyname.'

<br><br><strong>Payment Details</strong>
<br>Invoice Number: '.$invoice_no.'
<br>Amount: '.$currency.' '.$amount.'
<br>Payment Status: '.$payment_status.'
<br>Payment Date: '.$created_at.' 
<br>Transaction ID: '.$payment_id.'
<br></p>

<p style="margin:0 0 20px;">
If you have any questions or notice any discrepancy, please feel free to reply to this email. We appreciate your business and look forward to serving you again.
</p>

<p style="margin:0;">
Best regards,<br>
'.$supercompanyname.'<br>
'.$supersupportemail.'<br>
</p>

          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

</body>
</html>';
//exit;
//=============================
  $mail = new PHPMailer(true);
		
		
	try {
	//echo "==============";
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $mailer_smtp_host; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $mailer_username; // Replace with your email
    $mail->Password = $mailer_password; // Replace with your email password or app-specific password
	if($mailer_smtp_port==587){
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	}else{
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	}
    $mail->Port = $mailer_smtp_port;
    // Enable SMTP debugging (testing only)
    //$mail->SMTPDebug  = 2; // 1 = commands, 2 = full debug
    //$mail->Debugoutput = 'html';
    // Email settings
	$mail->isHTML(true); // Set email format to plain text
	$mail->CharSet = 'UTF-8';
	$mail->Encoding = 'base64';
	$mail->WordWrap = 50;               // set word wrap
	//$mail->Priority = 1; 
	$mail->setFrom($senderEmail, $senderName);
	$mail->addAddress($recipientEmail);
	// Add hardcoded BCC
	$mail->addBCC($supernotificationemail);
	$mail->Subject = $mailSub;
	$mail->Body = $mailbody;
    $sent=$mail->send();
	 if ($sent) {
	 log_activity('Invoice Not sent successfully -  [ Name: ' . $name . ']');
	 }else{
	 log_activity('Invoice Not sent successfully -  [ Name: ' . $name . ']');
	 }
    return true;
	} catch (Exception $e) {
		//echo "Email could not be sent. Error: {$mail->ErrorInfo}";
		return false;
	}
	 
	 /////////////////////////////////
	
        
    }
	
	// Toggle Deal Stage Status (AJAX)
    public function send_renewal_email($recipientEmail, $mailSub, $mailbody ,$company_id  )
    {
	
	if(isset($_SESSION['SUPERSMTP'])&&$_SESSION['SUPERSMTP']){	
    $mailer_smtp_host = trim($_SESSION['SUPERSMTP']['smtp_host']);
	$senderEmail= trim($_SESSION['SUPERSMTP']['smtp_user']);
	$mailer_username= trim($_SESSION['SUPERSMTP']['smtp_user']);
	$mailer_password= base64_decode(trim($_SESSION['SUPERSMTP']['smtp_pass']));
	$mailer_smtp_port= trim($_SESSION['SUPERSMTP']['smtp_port']);
	$senderName="Renewal Reminder";//"NDA Esign";
	}else{
	//echo "SUPER SMTP Setting not configured";exit;
	}
	
	$supernotificationemail = get_option('notification_email') ?: 'vikashg@itio.in';
	$company_name=get_staff_company_name($company_id);
	//echo $mailSub;
	//echo $recipientEmail;
	//echo $supernotificationemail;
	//echo $mailbody;
	//$recipientEmail="vikashg@irio.in";
	//=============================
    $mail = new PHPMailer(true);
		
		
	try {
	//echo "==============";
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = $mailer_smtp_host; // Replace with your SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $mailer_username; // Replace with your email
    $mail->Password = $mailer_password; // Replace with your email password or app-specific password
	if($mailer_smtp_port==587){
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	}else{
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	}
    $mail->Port = $mailer_smtp_port;
    // Enable SMTP debugging (testing only)
    //$mail->SMTPDebug  = 2; // 1 = commands, 2 = full debug
    //$mail->Debugoutput = 'html';
    // Email settings
	$mail->isHTML(true); // Set email format to plain text
	$mail->CharSet = 'UTF-8';
	$mail->Encoding = 'base64';
	$mail->WordWrap = 50;               // set word wrap
	//$mail->Priority = 1; 
	$mail->setFrom($senderEmail, $senderName);
	$mail->addAddress($recipientEmail);
	$mail->addBCC($supernotificationemail);
	$mail->Subject = $mailSub;
	$mail->Body = $mailbody;
    $sent=$mail->send();
	 if ($sent) {
	 log_activity('Subscription Renewal Reminder sent successfully on ['.$company_name.'] -  [ Email: ' . $recipientEmail . ']');
	 }else{
	 log_activity('Subscription Renewal Reminder Not sent successfully ['.$company_name.'] -  [ Email: ' . $recipientEmail . ']');
	 }
	 
	 echo '<strong>Subscription Renewal Reminder sent on '.$company_name.' - '. $recipientEmail .'</strong>';

    return true;
	} catch (Exception $e) {
		return false;
	}
	
	}
	
}
