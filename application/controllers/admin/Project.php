<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Project extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        //$this->load->helper('project');
    }

   
 /* Dashboard */
    public function index()
    {
        if (staff_cant('project_dashboard', 'project')) {
            access_denied('Project Dashboard');
        }

        $data['title'] = _l('Project Dashboard');
        $this->load->view('admin/project/home', $data);
    } 
	
	/* Projects */
    public function list()
    {
        if (staff_cant('project_project', 'project')) {
            access_denied('Project List');
        }

        $data['title'] = _l('Project List');
        $this->load->view('admin/project/list', $data);
    }
	
	/* Projects */
    public function collaboration()
    {
        if (staff_cant('project_collaboration', 'project')) {
            access_denied('Collaboration');
        }

        $data['title'] = _l('Collaboration');
        $this->load->view('admin/project/collaboration', $data);
    }
  /* setting */
    public function setting()
    {
        if (staff_cant('project_setting', 'project')) {
            access_denied('Setting');
        }

        $data['title'] = _l('Setting');
        $this->load->view('admin/project/setting', $data);
    }
} 