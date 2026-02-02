<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crm_setup_guide extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('ai_content_generator_model');
        $this->load->library('form_validation');
        if (!is_admin()) {
            access_denied('Access CRM Setup Guide');
        }

       

        $data['user_id']  = get_staff_user_id();
        $data['added_by'] = get_staff_full_name($data['user_id']);
        
    }

    public function index()
    {
        $data['title'] = _l('CRM Setup Guide');
        $this->load->view('admin/crm_setup_guide', $data);
    }
}
