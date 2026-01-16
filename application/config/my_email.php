<?php
defined('BASEPATH') or exit('No direct script access allowed');

$company_id=$_SESSION['staff_company_id'];

if(isset($company_id)&&$company_id){
		
		$CI = & get_instance();
		$CI->db->where('company_id', $company_id);
		$com = $CI->db->select('settings')->from(db_prefix() . 'company_master')->get()->row();
		if(isset($com)&&$com->settings){
		$smtpdata=$com->settings;
		}
		if(isset($smtpdata)&&$smtpdata){
		// Decode JSON to associative array
        $settings = json_decode($smtpdata, true);
		//print_r($settings);
		}
		
if(isset($settings['smtp_encryption'])&&$settings['smtp_encryption']&&isset($settings['smtp_host'])&&$settings['smtp_host']&&isset($settings['smtp_port'])&&$settings['smtp_port']&&isset($settings['smtp_email'])&&$settings['smtp_email']&&isset($settings['smtp_username'])&&$settings['smtp_username']&&isset($settings['smtp_password'])&&$settings['smtp_password']){
		
			$config['smtp_host'] = trim($settings['smtp_host']);
			if ($settings['smtp_username'] == '') {
			$config['smtp_user'] = trim($settings['smtp_email']);
			} else {
			$config['smtp_user'] = trim($settings['smtp_username']);
			}
			$config['smtp_email'] = trim($settings['smtp_email']);
			$config['smtp_pass']    = trim($settings['smtp_password']);
			$config['smtp_port']    = trim($settings['smtp_port']);
			$config['smtp_crypto']  = trim($settings['smtp_encryption']);
			$_SESSION['staff_fromemai']=$settings['smtp_email'];
			$smtp_fetch_type="CompanySMTP";
		}
}