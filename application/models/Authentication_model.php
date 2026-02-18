<?php
use PHPMailer\PHPMailer\PHPMailer; // Added on 22122025 for NDA Esign Email
use PHPMailer\PHPMailer\Exception;  // Added on 22122025 for NDA Esign Email
use PHPMailer\PHPMailer\SMTP;
header('Content-Type: text/html; charset=utf-8');
defined('BASEPATH') or exit('No direct script access allowed');

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

class Authentication_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_autologin');
        $this->autologin();
    }

    /**
     * @param  string Email address for login
     * @param  string User Password
     * @param  boolean Set cookies for user if remember me is checked
     * @param  boolean Is Staff Or Client
     * @return boolean if not redirect url found, if found redirect to the url
     */
    public function login($email, $password, $remember, $staff)
    {
        if ((!empty($email)) and (!empty($password))) {
            $table = db_prefix() . 'contacts';
            $_id   = 'id';
            if ($staff == true) {
                $table = db_prefix() . 'staff';
                $_id   = 'staffid';
            }
            $this->db->where('email', $email);
            $user = $this->db->get($table)->row();
			//echo $user -> active;
			//echo $company_id=$user -> company_id;
			//echo get_company_status($company_id);
			
			
            if ($user) {
                // Email is okey lets check the password now
                if (!$user->password || !app_hasher()->CheckPassword($password, $user->password)) {
                    hooks()->do_action('failed_login_attempt', [
                        'user'            => $user,
                        'is_staff_member' => $staff,
                    ]);

                    log_activity('Failed Login Attempt [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                    // Password failed, return
                    return false;
                }
            } else {
                hooks()->do_action('non_existent_user_login_attempt', [
                    'email'           => $email,
                    'is_staff_member' => $staff,
                ]);

                log_activity('Non Existing User Tried to Login [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return false;
            }

            if ($user->active == 0 || get_company_status($user -> company_id) == 0) {
                hooks()->do_action('inactive_user_login_attempt', [
                    'user'            => $user,
                    'is_staff_member' => $staff,
                ]);
                log_activity('Inactive User Tried to Login [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return [
                    'memberinactive' => true,
                ];
            }

            $twoFactorAuth = false;
            if ($staff == true) {
                $twoFactorAuth = $user->two_factor_auth_enabled == 0 ? false : true;

                if (!$twoFactorAuth) {
                    hooks()->do_action('before_staff_login', [
                        'email'  => $email,
                        'userid' => $user->$_id,
                    ]);
					
					// Store Subscription//////////
					
					$tables = db_prefix() . 'services_user_subscriptions';
					$this->db->where('company_id', $user->company_id);
					$this->db->where('status', 'active');
                    $subscriptions_data = $this->db->get($tables)->row();
					
					// if subscription Expired Only Admin Login
					$admin_check = $user->admin ?? 0;
					$expireddate = $subscriptions_data->end_date ?? '';
					
					if ( $admin_check != 1 && !empty($expireddate) && strtotime($expireddate) < strtotime(date('Y-m-d'))) {
					return ['memberinactive' => true, ];
					}
					
					// if subscription Expired Only Admin Login
					
					$subscription_id = !empty($subscriptions_data->subscription_id) ? $subscriptions_data->subscription_id : '';
					$start_date      = !empty($subscriptions_data->start_date) ? $subscriptions_data->start_date : '';
					$end_date        = !empty($subscriptions_data->end_date) ? $subscriptions_data->end_date : '';
					$status          = !empty($subscriptions_data->status) ? $subscriptions_data->status : '';
					$staff_limit     = !empty($subscriptions_data->staff_limit) ? $subscriptions_data->staff_limit : '';
					$created_at      = !empty($subscriptions_data->created_at) ? $subscriptions_data->created_at : '';
					
					//echo "XXXXXXXXX";exit;
                    $subscription_data = [
                    'cms_subscription_id'   => $subscription_id,
                    'cms_subscription_start_date'  => $start_date,
                    'cms_subscription_end_date' => $end_date,
					'cms_subscription_status' => $status,
					'cms_subscription_staff_limit' => $staff_limit,
					'cms_subscription_created_at' => $created_at,
                   ];
				   $this->session->set_userdata($subscription_data);
				   //print_r($subscription_data);exit;
				
                    $user_data = [
                        'staff_user_id'   => $user->$_id,
                        'staff_logged_in' => true,
						'staff_company_id' => $user->company_id,
						'company_form_type' => get_deal_form_type($user->company_id),
                    ];
                } else {
                    $user_data                = [];
                    $user_data['tfa_staffid'] = $user->staffid;
                    if ($remember) {
                        $user_data['tfa_remember'] = true;
                    }
                }
            } else {
                hooks()->do_action('before_client_login', [
                    'email'           => $email,
                    'userid'          => $user->userid,
                    'contact_user_id' => $user->$_id,
                ]);
				
				

                $user_data = [
                    'client_user_id'   => $user->userid,
                    'contact_user_id'  => $user->$_id,
                    'client_logged_in' => true,
                ];
            }
			
            $this->session->set_userdata($user_data);

            if (!$twoFactorAuth) {
                if ($remember) {
                    $this->create_autologin($user->$_id, $staff);
                }

                $this->update_login_info($user->$_id, $staff);
            } else {
                return ['two_factor_auth' => true, 'user' => $user];
            }

            return true;
        }

        return false;
    }

    /**
     * @param  boolean If Client or Staff
     * @return none
     */
    public function logout($staff = true)
    {
        $this->delete_autologin($staff);

        if (is_client_logged_in()) {
            hooks()->do_action('before_contact_logout', get_client_user_id());

            $this->session->unset_userdata('client_user_id');
            $this->session->unset_userdata('client_logged_in');
        } else {
            hooks()->do_action('before_staff_logout', get_staff_user_id());

            $this->session->unset_userdata('staff_user_id');
            $this->session->unset_userdata('staff_logged_in');
        }

        $this->session->sess_destroy();
    }

    /**
     * @param  integer ID to create autologin
     * @param  boolean Is Client or Staff
     * @return boolean
     */
    private function create_autologin($user_id, $staff)
    {
        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        $this->user_autologin->delete($user_id, $key, $staff);
        if ($this->user_autologin->set($user_id, md5($key), $staff)) {
            set_cookie([
                'name'  => 'autologin',
                'value' => serialize([
                    'user_id' => $user_id,
                    'key'     => $key,
                ]),
                'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
            ]);

            return true;
        }

        return false;
    }

    /**
     * @param  boolean Is Client or Staff
     * @return none
     */
    private function delete_autologin($staff)
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie('autologin', true)) {
            $data = unserialize($cookie);
            $this->user_autologin->delete($data['user_id'], md5($data['key']), $staff);
            delete_cookie('autologin', 'aal');
        }
    }

    /**
     * @return boolean
     * Check if autologin found
     */
    public function autologin()
    {
        if (!is_logged_in()) {
            $this->load->helper('cookie');
            if ($cookie = get_cookie('autologin', true)) {
                $data = unserialize($cookie);
                if (isset($data['key']) and isset($data['user_id'])) {
                    if (!is_null($user = $this->user_autologin->get($data['user_id'], md5($data['key'])))) {
                        // Login user
                        if ($user->staff == 1) {
                            $user_data = [
                                'staff_user_id'   => $user->id,
                                'staff_logged_in' => true,
                            ];
                        } else {
                            // Get the customer id
                            $this->db->select('userid');
                            $this->db->where('id', $user->id);
                            $contact = $this->db->get(db_prefix() . 'contacts')->row();

                            $user_data = [
                                'client_user_id'   => $contact->userid,
                                'contact_user_id'  => $user->id,
                                'client_logged_in' => true,
                            ];
                        }
                        $this->session->set_userdata($user_data);
                        // Renew users cookie to prevent it from expiring
                        set_cookie([
                            'name'   => 'autologin',
                            'value'  => $cookie,
                            'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
                        ]);
                        $this->update_login_info($user->id, $user->staff);

                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * @param  integer ID
     * @param  boolean Is Client or Staff
     * @return none
     * Update login info on autologin
     */
    private function update_login_info($user_id, $staff)
    {
        $table = db_prefix() . 'contacts';
        $_id   = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id   = 'staffid';
        }
        $this->db->set('last_ip', $this->input->ip_address());
        $this->db->set('last_login', date('Y-m-d H:i:s'));
        $this->db->where($_id, $user_id);
        $this->db->update($table);

        log_activity('User Successfully Logged In [User Id: ' . $user_id . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
    }

    /**
     * Send set password email for contacts
     * @param string $email
     */
    public function set_password_email($email)
    {
        $this->db->where('email', $email);
        $user = $this->db->get(db_prefix() . 'contacts')->row();

        if ($user) {
            if ($user->active == 0) {
                return [
                    'memberinactive' => true,
                ];
            }

            $new_pass_key = app_generate_hash();
            $this->db->where('id', $user->id);
            $this->db->update(db_prefix() . 'contacts', [
                'new_pass_key'           => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s'),
            ]);
            if ($this->db->affected_rows() > 0) {
                $data['new_pass_key'] = $new_pass_key;
                $data['userid']       = $user->id;
                $data['email']        = $email;

                $sent = send_mail_template('customer_contact_set_password', $user, $data);

                if ($sent) {
                    hooks()->do_action('set_password_email_sent', ['is_staff_member' => false, 'user' => $user]);

                    return true;
                }

                return false;
            }

            return false;
        }

        return false;
    }

    /**
     * @param  string Email from the user
     * @param  Is Client or Staff
     * @return boolean
     * Generate new password key for the user to reset the password.
     */
    public function forgot_password($email, $staff = false)
    {
        $table = db_prefix() . 'contacts';
        $_id   = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id   = 'staffid';
        }
        $this->db->where('email', $email);
        $user = $this->db->get($table)->row();

        if ($user) {
            if ($user->active == 0) {
                log_activity('Inactive User Tried Password Reset [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                return [
                    'memberinactive' => true,
                ];
            }

            $new_pass_key = app_generate_hash();
            $this->db->where($_id, $user->$_id);
            $this->db->update($table, [
                'new_pass_key'           => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s'),
            ]);

            if ($this->db->affected_rows() > 0) {
                $data['new_pass_key'] = $new_pass_key;
                $data['staff']        = $staff;
                $data['userid']       = $user->$_id;
                $merge_fields         = [];

                if ($staff == false) {
                    $sent = send_mail_template('customer_contact_forgot_password', $user->email, $user->userid, $user->$_id, $data);
                } else {
                    $sent = send_mail_template('staff_forgot_password', $user->email, $user->$_id, $data);
                }

                if ($sent) {
                    log_activity('Password Reset Email sent [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

                    hooks()->do_action('forgot_password_email_sent', ['is_staff_member' => $staff, 'user' => $user]);

                    return true;
                }

                return false;
            }

            return false;
        }
        log_activity('Non Existing User Tried Password Reset [Email: ' . $email . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');

        return false;
    }

    /**
     * Update user password from forgot password feature or set password
     * @param boolean $staff        is staff or contact
     * @param mixed $userid
     * @param string $new_pass_key the password generate key
     * @param string $password     new password
     */
    public function set_password($staff, $userid, $new_pass_key, $password)
    {
        if (!$this->can_set_password($staff, $userid, $new_pass_key)) {
            return [
                'expired' => true,
            ];
        }

        $password = app_hash_password($password);
        $table    = db_prefix() . 'contacts';
        $_id      = 'id';

        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id   = 'staffid';
        }

        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $this->db->update($table, [
            'password' => $password,
        ]);
        
        if ($this->db->affected_rows() > 0) {
            log_activity('User Set Password [User ID: ' . $userid . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
            $this->db->set('new_pass_key', null);
            $this->db->set('new_pass_key_requested', null);
            $this->db->set('last_password_change', date('Y-m-d H:i:s'));
            $this->db->where($_id, $userid);
            $this->db->where('new_pass_key', $new_pass_key);
            $this->db->update($table);

            return true;
        }

        return null;
    }

    /**
     * @param  boolean Is Client or Staff
     * @param  integer ID
     * @param  string
     * @param  string
     * @return boolean
     * User reset password after successful validation of the key
     */
    public function reset_password($staff, $userid, $new_pass_key, $password)
    {
        if (!$this->can_reset_password($staff, $userid, $new_pass_key)) {
            return [
                'expired' => true,
            ];
        }
        $password = app_hash_password($password);
        $table    = db_prefix() . 'contacts';
        $_id      = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id   = 'staffid';
        }

        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $this->db->update($table, [
            'password' => $password,
        ]);
        if ($this->db->affected_rows() > 0) {
            log_activity('User Reseted Password [User ID: ' . $userid . ', Is Staff Member: ' . ($staff == true ? 'Yes' : 'No') . ', IP: ' . $this->input->ip_address() . ']');
            $this->db->set('new_pass_key', null);
            $this->db->set('new_pass_key_requested', null);
            $this->db->set('last_password_change', date('Y-m-d H:i:s'));
            $this->db->where($_id, $userid);
            $this->db->where('new_pass_key', $new_pass_key);
            $this->db->update($table);
            $this->db->where($_id, $userid);
            $user = $this->db->get($table)->row();

            if ($staff == false) {
                $sent = send_mail_template('customer_contact_password_resetted', $user->email, $user->userid, $user->$_id);
            } else {
                $sent = send_mail_template('staff_password_resetted', $user->email, $user->$_id);
            }
            
            if ($sent) {
                return true;
            }
        }

        return null;
    }

    /**
     * @param  integer Is Client or Staff
     * @param  integer ID
     * @param  string Password reset key
     * @return boolean
     * Check if the key is not expired or not exists in database
     */
    public function can_reset_password($staff, $userid, $new_pass_key)
    {
        $table = db_prefix() . 'contacts';
        $_id   = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id   = 'staffid';
        }

        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $user = $this->db->get($table)->row();

        if ($user) {
            $timestamp_now_minus_1_hour = time() - (60 * 60);
            $new_pass_key_requested     = strtotime($user->new_pass_key_requested);
            if ($timestamp_now_minus_1_hour > $new_pass_key_requested) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * @param  integer Is Client or Staff
     * @param  integer ID
     * @param  string Password reset key
     * @return boolean
     * Check if the key is not expired or not exists in database
     */
    public function can_set_password($staff, $userid, $new_pass_key)
    {
        $table = db_prefix() . 'contacts';
        $_id   = 'id';
        if ($staff == true) {
            $table = db_prefix() . 'staff';
            $_id   = 'staffid';
        }
        $this->db->where($_id, $userid);
        $this->db->where('new_pass_key', $new_pass_key);
        $user = $this->db->get($table)->row();
        if ($user) {
            $timestamp_now_minus_48_hour = time() - (3600 * 48);
            $new_pass_key_requested      = strtotime($user->new_pass_key_requested);
            if ($timestamp_now_minus_48_hour > $new_pass_key_requested) {
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get user from database by 2 factor authentication code
     * @param  string $code authentication code to search for
     * @return object
     */
    public function get_user_by_two_factor_auth_code($code)
    {
        $this->db->where('two_factor_auth_code', $code);

        return $this->db->get(db_prefix() . 'staff')->row();
    }

    /**
     * Login user via two factor authentication
     * @param  object $user user object
     * @return boolean
     */
    public function two_factor_auth_login($user)
    {
        hooks()->do_action('before_staff_login', [
            'email'  => $user->email,
            'userid' => $user->staffid,
        ]);

        $this->session->set_userdata(
            [
                'staff_user_id'   => $user->staffid,
                'staff_logged_in' => true,
            ]
        );

        $remember = null;
        if ($this->session->has_userdata('tfa_remember')) {
            $remember = true;
            $this->session->unset_userdata('tfa_remember');
        }

        if ($remember) {
            $this->create_autologin($user->staffid, true);
        }

        $this->update_login_info($user->staffid, true);

        return true;
    }

    /**
     * Check if 2 factor authentication code sent to email is valid for usage
     * @param  string  $code auth code
     * @param  string  $email email of staff login in
     * @return boolean
     */
    public function is_two_factor_code_valid($code, $email)
    {
        $this->db->select('two_factor_auth_code_requested');
        $this->db->where('two_factor_auth_code', $code);
        $this->db->where('email', $email);
        $user = $this->db->get(db_prefix() . 'staff')->row();

        // Code not exists because no user is found
        if (!$user) {
            return false;
        }

        $timestamp_minus_1_hour = time() - (60 * 60);
        $new_code_key_requested = strtotime($user->two_factor_auth_code_requested);
        // The code is older then 1 hour and its not valid
        if ($timestamp_minus_1_hour > $new_code_key_requested) {
            return false;
        }
        // Code is valid
        return true;
    }

    /**
     * Clears 2 factor authentication code in database
     * @param  mixed $id
     * @return boolean
     */
    public function clear_two_factor_auth_code($id)
    {
        $this->db->where('staffid', $id);
        $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_code' => null,
        ]);

        return true;
    }

    /**
     * Set 2 factor authentication code for staff member
     * @param mixed $id staff id
     */
    public function set_two_factor_auth_code($id)
    {
        $code = generate_two_factor_auth_key();
        $code .= $id;

        $this->db->where('staffid', $id);
        $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_code'           => $code,
            'two_factor_auth_code_requested' => date('Y-m-d H:i:s'),
        ]);

        return $code;
    }

    public function get_qr($System_name)
    {
        $staff    = get_staff(get_staff_user_id());
        $g        = new GoogleAuthenticator();
        $secret   = $g->generateSecret();
        $username = urlencode($staff->email);
        $url      = \Sonata\GoogleAuthenticator\GoogleQrUrl::generate($username, $secret, $System_name);

        return ['qrURL' => $url, 'secret' => $secret];
    }

    public function set_google_two_factor($secret)
    {
        $id     = get_staff_user_id();
        $secret = $this->encrypt($secret);

        $this->db->where('staffid', $id);
        $success = $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_enabled' => 2,
            'google_auth_secret'      => $secret,
        ]);

        if ($success) {
            return true;
        }

        return false;
    }

    public function is_google_two_factor_code_valid($code, $secret = null)
    {
        $g = new GoogleAuthenticator();

        if (!is_null($secret)) {
            return $g->checkCode($secret, $code);
        }

        $staffid = $this->session->userdata('tfa_staffid');

        $this->db->select('google_auth_secret')
            ->where('staffid', $staffid);

        if ($staff = $this->db->get('staff')->row()) {
            return $g->checkCode(
                $this->decrypt($staff->google_auth_secret),
                $code
            );
        }

        return false;
    }

    public function encrypt($string)
    {
        $this->load->library('encryption');

        return $this->encryption->encrypt($string);
    }

    public function decrypt($string)
    {
        $this->load->library('encryption');

        return $this->encryption->decrypt($string);
    }
	
	
	function send_activation_mail($data){
	
	//print_r($data);exit;
	//log_message('error', 'Display data - ' . print_r($data, true));
	
	$companyname=get_option('companyname');
	$support_email=get_option('support_email');
	$website=get_option('main_domain');
	$msg="";
	
	
	
	//===============================
	$mailer_username    = trim($_SESSION['STAFFSMTP']['smtp_user']);
	$mailer_password    = trim(base64_decode($_SESSION['STAFFSMTP']['smtp_pass']));
	$mailer_smtp_host   = trim($_SESSION['STAFFSMTP']['smtp_host']);
	$mailer_smtp_port	= trim($_SESSION['STAFFSMTP']['smtp_port']);
	$mailer_smtp_crypto	= trim($_SESSION['STAFFSMTP']['smtp_crypto']);
	//==================================================
	
    $recipientEmail 	= $data['email'];
	$recipientName 		= $data['firstname'].' '.$data['lastname'];
	$mailSub			= "Activate Your Account - ".$companyname;
	$senderEmail 		= $mailer_username;
	$senderName			= e(get_staff_full_name());
	
	//exit;
$activation_link=site_url('authentication/acticate_account?salt='.base64_encode($data['company_id']));

$mailbody='<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body>

<table width="100%" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center">

      <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
        <tr>
          <td style="padding:24px; color:#111827; font-size:14px; line-height:22px;">

<p style="margin:0 0 16px;">Dear <strong>'.$recipientName.'</strong>,</p>
<p style="margin:0 0 16px;">Thank you for registering with <strong>'.$companyname.'</strong>.</p>
<p style="margin:0 0 16px;">To complete your registration, please verify your email address by clicking the button below:</p>
<p style="margin:0 0 16px;"><a href="'.$activation_link.'" target="_blank" style="background-color: #007bff; color: #ffffff; padding: 12px 24px; text-decoration: none; display: inline-block;">Activate Account</a></p>
<p style="margin:0 0 16px;">If the button does not work, copy and paste the following link into your browser:</p>
<p style="margin:0 0 16px;"><a href="'.$activation_link.'" target="_blank" >'.$activation_link.'</a></p>
<p style="margin:0 0 16px;"><em><strong>This activation link is valid for a limited time.</strong></em></p>
<p style="margin:0 0 16px;"><em><strong>If you did not create this account, please ignore this email.</strong></em></p>
<p style="margin:0 0 16px;"><em><strong>If you need any assistance, feel free to contact our support team.</strong></em></p>
<p style="margin:0 0 16px;"><em><strong>Best regards,</strong></em><br><em><strong>'.$companyname.' Team</strong></em><br><em><strong>'.$support_email.'</strong></em><br><em><strong>'.$website.'</strong></em></p>


          </td>
        </tr>
      </table>

    </td>
  </tr>
</table>

</body>
</html>';

//log_message('error', 'senderName - ' .$mailbody );
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
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
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
	$mail->addAddress($recipientEmail, $recipientName);
	// Add hardcoded BCC
	//$mail->addBCC('vikashg@itio.in');
	$mail->Subject = $mailSub;
	$mail->Body = $mailbody;
    $sent=$mail->send();
	//echo "==============!!";exit;
	 if (!$sent) {
	 log_message('error', $mailSub.'Mail Not Sent');
	 log_activity($mailSub.' not sent successfully -  [ Name: ' . $recipientName . ']');
	//log_activity('NDA sent successfully -  [ Name: ' . $name . ']');
    //exit;
	 
	 }else{
	//log_message('error', 'Mail Not Sent 66');
	log_activity($mailSub.' sent successfully -  [ Name: ' . $recipientName . ']');
    //exit;
	 
	 }
    
	
    
	} catch (Exception $e) {
		log_message('error', $mailSub.'Mail Not Sent');
	}
	 
	 /////////////////////////////////
	
	}
}
