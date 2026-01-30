<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_documentation extends App_Controller
{
    public function index()
    {
        $data['title'] = 'User Documentation';
        $this->load->view('user_documentation/index', $data);
    }
}
