<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Customize extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('staff_model');
        $this->load->helper('customize');
    }

    /* View all customize settings */
    public function index()
    {
        if (staff_cant('view', 'settings')) {
            access_denied('settings');
        }
		
		if(is_super()){
		redirect(admin_url('settings'), 'refresh');
		}

        $company_id = get_staff_company_id();
        $company = $this->staff_model->getcompany($company_id);
        $this->load->model('settings_model');
        $staff_list = $this->settings_model->get_staff_list();

        // Load SMTP settings from company->settings
        $smtp_settings = [];
        if (!empty($company->settings)) {
            $smtp_settings = json_decode($company->settings, true) ?? [];
        }

        if ($this->input->post()) {
            if (staff_cant('edit', 'settings')) {
                access_denied('settings');
            }

            $data = $this->input->post();
            $data['companyname'] = $data['customize_company_name'] ?? '';
            $data['website'] = $data['customize_company_domain'] ?? '';

            

            // Collect SMTP fields
            $smtp_fields = [
                'smtp_encryption',
                'smtp_host',
                'smtp_port',
                'smtp_email',
                'smtp_username',
                'smtp_password',

            ];
            $smtp_settings_save = [];
            foreach ($smtp_fields as $field) {
                $smtp_settings_save[$field] = $data['settings'][$field] ?? '';
            }
            $data['settings'] = json_encode($smtp_settings_save);

           

            // Save lead assignment fields
            if (isset($data['automatically_assign_to_staff'])) {
                $data['automatically_assign_to_staff'] = $data['automatically_assign_to_staff'];
            }
            if (isset($data['lead_auto_assign_to_staff'])) {
                $data['lead_auto_assign_to_staff'] = $data['lead_auto_assign_to_staff'];
            }

            $upload_path = './uploads/company/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            // Handle company logo upload
            if (isset($_FILES['customize_company_logo']) && $_FILES['customize_company_logo']['error'] == 0) {
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'gif|jpg|jpeg|png|svg';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = 'company_logo_' . time();

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('customize_company_logo')) {
                    $upload_data = $this->upload->data();
                    $data['company_logo'] = $upload_data['file_name'];
                } else {
                    set_alert('danger', $this->upload->display_errors());
                }
            }

            // Handle favicon upload
            if (isset($_FILES['customize_favicon']) && $_FILES['customize_favicon']['error'] == 0) {
                $config['upload_path']   = $upload_path;
                $config['allowed_types'] = 'ico|png';
                $config['max_size']      = 1024; // 1MB
                $config['file_name']     = 'favicon_' . time();

                $this->load->library('upload', $config);
                if ($this->upload->do_upload('customize_favicon')) {
                    $upload_data = $this->upload->data();
                    $data['favicon'] = $upload_data['file_name'];
                } else {
                    set_alert('danger', $this->upload->display_errors());
                }
            }
            //print_r($data);exit;
            $this->staff_model->updatecompany($data, $company_id);
            set_alert('success', _l('settings_updated77'));
            redirect(admin_url('customize'));
        }

        $data = [
            'company' => $company,
            'smtp_settings' => $smtp_settings,
            'staff_list' => $staff_list,
        ];
        $data['title'] = _l('customize_settings');
        $this->load->view('admin/customize/all', $data);
    }

    public function delete_custom_option($name)
    {
        // Not used for company master
        redirect(admin_url('customize'));
    }

    public function clear_custom_cache()
    {
        // Not used for company master
        redirect(admin_url('customize'));
    }
} 