<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crm_setup extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['title'] = 'CRM Setup';
        $this->load->view('admin/crm_setup/manage', $data);
    }
}
