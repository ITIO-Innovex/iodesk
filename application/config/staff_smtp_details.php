<?php
defined('BASEPATH') or exit('No direct script access allowed');
		
		$CI = & get_instance();
		$CI->db->where('staffid', $user_smtp_userid);
		$CI->db->like('email', '@itio.in', 'after'); // email ends with @itio.in
		$CI->db->order_by('priority', 'DESC');
		$CI->db->limit(1);
		$settings = $CI->db->select('*')->from(db_prefix() . 'webmail_setup')->get()->result_array();
		
		if (!empty($settings)) {
		$settings=$settings[0];
		//print_r($settings);exit;
if(isset($settings['encryption'])&&$settings['encryption']&&isset($settings['mailer_smtp_host'])&&$settings['mailer_smtp_host']&&isset($settings['mailer_smtp_port'])&&$settings['mailer_smtp_port']&&isset($settings['mailer_email'])&&$settings['mailer_email']&&isset($settings['mailer_username'])&&$settings['mailer_username']&&isset($settings['mailer_password'])&&$settings['mailer_password']){
		
			$config['smtp_host'] = trim($settings['mailer_smtp_host']);
			
			if ($settings['mailer_username'] == '') {
			$config['smtp_user'] = trim($settings['mailer_email']);
			} else {
			$config['smtp_user'] = trim($settings['mailer_username']);
			}
			
			$config['smtp_email'] = trim($settings['mailer_email']);
			$config['smtp_pass']    = trim($settings['mailer_password']);
			$config['smtp_port']    = trim($settings['mailer_smtp_port']);
			$config['smtp_crypto']  = trim($settings['encryption']);
			$_SESSION['staff_fromemai']=$settings['mailer_email'];
			$smtp_fetch_type="StaffSMTP";
		}else{
		$smtp_fetch_type_status=1;
		}
		
		}else{
		$smtp_fetch_type_status=1;
		}