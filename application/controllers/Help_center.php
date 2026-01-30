<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Help_center extends App_Controller
{
    public function index()
    {
        $data['title'] = 'Help Center';
        $data['support_email'] = 'Info@itio.in';
        $data['support_phone'] = get_option('invoice_company_phonenumber');
        $this->load->view('help_center/index', $data);
    }
}
