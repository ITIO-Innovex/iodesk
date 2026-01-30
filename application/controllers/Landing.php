<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'HRM - Features & Pricing';
		$data['support_email'] = get_option('support_email')??'';
		$data['support_phone'] = get_option('support_phone')??'';
		$data['terms_of_use_url'] = filter_var(get_option('terms_of_use_url'), FILTER_VALIDATE_URL) ?: '#';
        $data['privacy_policy_url'] = filter_var(get_option('privacy_policy_url'), FILTER_VALIDATE_URL) ?: '#';
		
        $this->load->model('services_subscriptions_model');
        $plans = $this->services_subscriptions_model->get();
        $data['pricing_plans'] = array_values(array_filter($plans, function ($plan) {
            //return isset($plan['status']) ? $plan['status'] === 'active' : true;
			return
            (!isset($plan['status']) || $plan['status'] === 'active') &&
            (!isset($plan['id']) || $plan['id'] != 4);
        }));
        $this->load->view('landing/index', $data);
    }
}
