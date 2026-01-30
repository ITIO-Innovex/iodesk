<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Help_center extends App_Controller
{
    public function index()
    {
        $data['title'] = 'Help Center';
        $data['support_email'] = get_option('support_email')??'';
		$data['support_phone'] = get_option('support_phone')??'';
		$data['terms_of_use_url'] = filter_var(get_option('terms_of_use_url'), FILTER_VALIDATE_URL) ?: '#';
        $data['privacy_policy_url'] = filter_var(get_option('privacy_policy_url'), FILTER_VALIDATE_URL) ?: '#';
        $this->load->view('help_center/index', $data);
    }
}
