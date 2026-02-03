<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends ClientsController
{
    public function index()
    {
        show_404();
    }

    public function download_email_from_cron($id)
    {
        try {
            // Increase execution time limit for email download
            set_time_limit(300); // 5 minutes
            ini_set('max_execution_time', 300);
            
            $data['title'] = _l('Download Email From Cron');
            
            // Validate ID
            if (empty($id) || !is_numeric($id)) {
                $data['message'] = '<div class="alert alert-danger">Invalid Mailbox ID provided!</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
           
            // Load webmail model
            $this->load->model('webmail_model');
			
            
            // Check if model was loaded successfully
            if (!isset($this->webmail_model) || !is_object($this->webmail_model)) {
                $data['message'] = '<div class="alert alert-danger">Error: Failed to load webmail model! Model object not found.</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Check if method exists
            if (!method_exists($this->webmail_model, 'downloadmail')) {
                $data['message'] = '<div class="alert alert-danger">Error: downloadmail method not found in webmail_model!</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Log that we're about to call the method
            log_message('error', 'Cronjob: About to call downloadmail with ID: ' . $id);
            
            // Call downloadmail method
            try {
                $result = $this->webmail_model->downloadmail($id);
                log_message('error', 'Cronjob: downloadmail returned: ' . (is_array($result) ? json_encode($result) : $result));
            } catch (\Exception $e) {
                log_message('error', 'Cronjob: Exception in downloadmail: ' . $e->getMessage());
                $result = 'Error calling downloadmail: ' . $e->getMessage();
            } catch (\Error $e) {
                log_message('error', 'Cronjob: Fatal Error in downloadmail: ' . $e->getMessage());
                $result = 'Fatal error calling downloadmail: ' . $e->getMessage();
            }
            
            // Check if result is null or empty (method might not have been called)
            if (!isset($result)) {
                $data['message'] = '<div class="alert alert-danger">Error: downloadmail method returned no result. Check logs for details.</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Check if result is an array (error case from some methods)
            if (is_array($result)) {
                if (isset($result['msg'])) {
                    $data['message'] = '<div class="alert alert-danger">' . htmlspecialchars($result['msg']) . '</div>';
                } else {
                    $data['message'] = '<div class="alert alert-info">Total emails downloaded: ' . (isset($result['cnt']) ? $result['cnt'] : 0) . '</div>';
                }
            } else {
                // Result is a string
                if (stripos($result, 'error') !== false || stripos($result, 'failed') !== false || stripos($result, 'invalid') !== false) {
                    $data['message'] = '<div class="alert alert-danger">' . htmlspecialchars($result) . '</div>';
                } else {
                    $data['message'] = '<div class="alert alert-success">' . htmlspecialchars($result) . '</div>';
                }
            }
            
            $this->load->view('cronjob/download_email_from_cron', $data);
        } catch (\Exception $e) {
            $data['title'] = _l('Download Email From Cron');
            $error_msg = $e->getMessage();
            if (stripos($error_msg, 'Maximum execution time') !== false) {
                $error_msg = 'Process timed out. The email download was taking too long. Try running it again - it will continue from where it left off.';
            }
            $data['message'] = '<div class="alert alert-danger">Error: ' . htmlspecialchars($error_msg) . '</div>';
            $this->load->view('cronjob/download_email_from_cron', $data);
        } catch (\Error $e) {
            $data['title'] = _l('Download Email From Cron');
            $error_msg = $e->getMessage();
            $data['message'] = '<div class="alert alert-danger">Fatal Error: ' . htmlspecialchars($error_msg) . '</div>';
            $this->load->view('cronjob/download_email_from_cron', $data);
        }
    }
	
	// Send Reminder Email
	public function send_renewal_reminder()
	{
	
	
	$supercompanyname=get_option('companyname') ?? "Support CRM";
	$supersupportemail=get_option('support_email') ?? "support@itio.in";
	$supernotificationemail = get_option('notification_email') ?: 'vikashg@itio.in';
	
	
	
	
	$this->db->select('sus.*, s.plan_name, s.price, s.currency, s.billing_cycle, s.no_of_staff, s.duration, s.features, s.tax');
	$this->db->from(db_prefix() . 'services_user_subscriptions sus');
	$this->db->join(db_prefix() . 'services_subscriptions s', 's.id = sus.subscription_id', 'left');
	$this->db->where('sus.status', 'active');
	$this->db->where_in('DATEDIFF(sus.end_date, CURDATE())', [7, 3, 1, 0], false);
	$this->db->order_by('sus.id', 'asc');
	$plans = $this->db->get()->result_array(); // ALL records
	//print_r($plans);
	
		foreach ($plans as $index => $plan) {
			//if ($index === 0) {
				// display only first record
				$company_id= $plan['company_id'];
				if(isset($company_id)&&$company_id){
				$this->db->where('company_id', $company_id);
			    $rs = $this->db->select('companyname,firstname,lastname,email')->from(db_prefix() . 'company_master')->get()->row();              //print_r($rs);
				$name=$rs->firstname.' '.$rs->lastname;	
                $email=$rs->email;
                $companyname=$rs->companyname;
				}
				
				
				$subscription_id = $plan['subscription_id'];
				$staff_limit= $plan['staff_limit'];
				$pending_staff_limit= $plan['pending_staff_limit'];
				$start_date= $plan['start_date'];
				$end_date = $plan['end_date'];
				$status = $plan['status'];
				$plan_name = $plan['plan_name'];
				$price = $plan['price'];
				$currency = $plan['currency'];
				$billing_cycle = $plan['billing_cycle'];
				$no_of_staff = $plan['no_of_staff'];
				$duration = $plan['duration'];
				$features = $plan['features'];
				$tax = $plan['tax'];

	
$name=$name ?? 'Subscriber';	
$email=$email ?? 'vikashg@itio.in';
$companyname=$companyname ?? 'CRM';		
$mailSub="CRM Subscription Renewal Reminder - ".$plan_name;
$mailbody='<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Subscription Renewal Reminder</title>
</head>
<body style="margin:0; padding:0; background-color:#f3f4f6; font-family:Arial, Helvetica, sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f3f4f6; padding:20px;">
  <tr>
    <td align="center">

      <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
        <tr>
          <td style="padding:24px; color:#111827; font-size:14px; line-height:22px;">

<p style="margin:0 0 16px;">Dear <strong>'.$name.'</strong>,</p>
<p>Hi, <br><br> This is a friendly reminder that your Growth Plan subscription is nearing its expiry.</p>

<p style="margin:0 0 20px; text-align:left;">
<br><strong>Subscription Details</strong>
<br>Plan Name: '.$plan_name.'
<br>Billing Cycle: '.$billing_cycle.'
<br>Price: '.$currency.' '.$price.' (excluding applicable taxes)
<br>Staff Limit: '.$no_of_staff.' 
<br>Features: '.$features.'
<br>Start Date: '.$start_date.'
<br>End Date: '.$end_date.'
<br>Status: '.$status.'
<br></p>

<p style="margin:0 0 20px;">
To avoid any interruption in services, we recommend renewing your subscription before the expiry date.<br />
<br />
If you have any questions regarding your plan, renewal process, or would like to upgrade your subscription, please feel free to contact our support team.<br />
<br />
Thank you for choosing our services. We look forward to continuing to support your business.<br />

</p>

<p style="margin:0;">
Warm regards,<br>
'.$supercompanyname.'<br>
'.$supersupportemail.'<br>
Support Team
</p>

          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

</body>
</html>';
$this->load->model('services_subscriptions_model');
// Save for History /////////
$insert['company_id']=$company_id;
$insert['mail_to']=$email;
$insert['renewal_date']=$end_date;
$this->db->insert('it_crm_services_subscriptions_reminder_email', $insert);
// ENDSave for History //
// Send Email By Function//
$result = $this->services_subscriptions_model->send_renewal_email($email, $mailSub, $mailbody, $company_id );			
		}
	
	}

}