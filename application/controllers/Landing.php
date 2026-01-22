<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'HRM - Features & Pricing';
        $this->load->model('services_subscriptions_model');
        $plans = $this->services_subscriptions_model->get();
        $data['pricing_plans'] = array_values(array_filter($plans, function ($plan) {
            return isset($plan['status']) ? $plan['status'] === 'active' : true;
        }));
        $this->load->view('landing/index', $data);
    }
}
