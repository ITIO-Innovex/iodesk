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
            $data['firstname'] = $data['customize_firstname'] ?? '';
            $data['lastname'] = $data['customize_lastname'] ?? '';
            $data['email'] = $data['customize_email'] ?? '';

            // Email notification fields
            $data['email_notification'] = trim($data['email_notification'] ?? '');
            $data['email_hrd'] = trim($data['email_hrd'] ?? '');
            $data['email_dar'] = trim($data['email_dar'] ?? '');
            $data['email_cc'] = trim($data['email_cc'] ?? '');

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
                $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf|svg';
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
            set_alert('success', _l('settings_updated'));
            redirect(admin_url('customize'));
        }

        $data = [
            'company' => $company,
            'smtp_settings' => $smtp_settings,
            'staff_list' => $staff_list,
        ];
// Get company deal form type
$data['deal_form_type'] = $this->db->where('company_id', $company_id)->get('it_crm_company_master')->row()->deal_form_type;
         
        $data['title'] = _l('customize_settings');
		
        $this->load->view('admin/customize/all', $data);
    }

    public function smtp_setting()
    {
        if (staff_cant('view', 'settings')) {
            access_denied('settings');
        }

        $company_id = get_staff_company_id();

        $company = $this->db->where('company_id', $company_id)
            ->get('it_crm_company_master')
            ->row();

        $smtp_fields = [
            'smtp_encryption',
            'smtp_host',
            'smtp_port',
            'smtp_email',
            'smtp_username',
            'smtp_password',
        ];

        $decode_smtp = function ($json) use ($smtp_fields) {
            $decoded = [];
            if (!empty($json)) {
                $decoded = json_decode($json, true);
                if (!is_array($decoded)) {
                    $decoded = [];
                }
            }
            $prepared = [];
            foreach ($smtp_fields as $field) {
                $prepared[$field] = $decoded[$field] ?? '';
            }
            return $prepared;
        };

        $nda_smtp    = $decode_smtp($company->nda_smtp ?? '');
        $direct_smtp = $decode_smtp($company->direct_mail_smtp ?? '');

        if ($this->input->post()) {
            if (staff_cant('edit', 'settings')) {
                access_denied('settings');
            }

            $nda_input    = $this->input->post('nda') ?? [];
            $direct_input = $this->input->post('direct') ?? [];

            $prepare_smtp = function ($input) use ($smtp_fields) {
                $prepared = [];
                foreach ($smtp_fields as $field) {
                    $prepared[$field] = $input[$field] ?? '';
                }
                return $prepared;
            };

            $update = [
                'nda_smtp'         => json_encode($prepare_smtp($nda_input)),
                'direct_mail_smtp' => json_encode($prepare_smtp($direct_input)),
            ];

            $this->db->where('company_id', $company_id)
                ->update('it_crm_company_master', $update);

            if ($this->db->affected_rows() >= 0) {
                set_alert('success', _l('settings_updated'));
            } else {
                set_alert('danger', _l('problem_updating', 'SMTP Settings'));
            }

            redirect(admin_url('customize/smtp_setting'));
        }

        $data = [
            'title'       => _l('smtp_settings', 'SMTP Settings'),
            'smtp_fields' => [
                'smtp_encryption' => 'SMTP Encryption',
                'smtp_host'       => 'SMTP Host',
                'smtp_port'       => 'SMTP Port',
                'smtp_email'      => 'SMTP Email',
                'smtp_username'   => 'SMTP Username',
                'smtp_password'   => 'SMTP Password',
            ],
            'nda_smtp'    => $nda_smtp,
            'direct_smtp' => $direct_smtp,
        ];

        $this->load->view('admin/customize/smtp_setting', $data);
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

    // AJAX endpoint for deal stages in customize tab
    public function get_deal_stages()
    {
        $this->load->model('leads_model');
        $stages = $this->leads_model->get_deal_stage();
        echo json_encode([
            'success' => true,
            'stages' => $stages
        ]);
        exit;
    }

    // AJAX endpoint for drag & drop customized deal stages
    public function get_deal_stages_customized()
    {
        $this->load->model('leads_model');
        $company_id = get_staff_company_id();
        $this->load->database();
        // Get all stages
        $stages = $this->leads_model->get_deal_stage();
        // Get custom order/checks for this company
        $custom = $this->db->where('company_id', $company_id)->get('it_crm_deals_stage_custom')->result_array();
        $checkedMap = [];
        $orderMap = [];
        foreach ($custom as $row) {
            $checkedMap[$row['deal_stage_id']] = $row['checked'];
            $orderMap[$row['deal_stage_id']] = $row['order'];
        }
        // If custom order exists, sort all stages accordingly
        if (count($orderMap) > 0) {
            usort($stages, function($a, $b) use ($orderMap) {
                $oa = isset($orderMap[$a['id']]) ? $orderMap[$a['id']] : 9999;
                $ob = isset($orderMap[$b['id']]) ? $orderMap[$b['id']] : 9999;
                return $oa - $ob;
            });
        }
        echo json_encode([
            'success' => true,
            'stages' => $stages,
            'checkedMap' => $checkedMap
        ]);
        exit;
    }

    public function save_deal_stages_customized()
    {
        $company_id = get_staff_company_id();
        $order = $this->input->post('order');
        $checked = $this->input->post('checked');
        $customized_default = $this->input->post('customized_default');
        if (!is_array($order) || !is_array($checked)) {
            echo json_encode(['success' => false]);
            exit;
        }
        $this->load->database();
        // Remove old records for this company
        // Insert new records
        foreach ($order as $i => $deal_stage_id) {
            $exists = $this->db->where([
                'deal_stage_id' => $deal_stage_id,
                'company_id' => $company_id
            ])->get('it_crm_deals_stage_custom')->row();
            if ($exists) {
                $this->db->where([
                    'deal_stage_id' => $deal_stage_id,
                    'company_id' => $company_id
                ])->update('it_crm_deals_stage_custom', [
                    'checked' => isset($checked[$deal_stage_id]) ? $checked[$deal_stage_id] : 0,
                    'order' => $i
                ]);
            } else {
                $this->db->insert('it_crm_deals_stage_custom', [
                    'deal_stage_id' => $deal_stage_id,
                    'order' => $i,
                    'company_id' => $company_id,
                    'checked' => isset($checked[$deal_stage_id]) ? $checked[$deal_stage_id] : 0
                ]);
            }
        }
		//$sss=$this->db->last_query();
        // Update it_crm_company_master.deal_form_type
        //$this->db->where('company_id', $company_id)
           // ->update('it_crm_company_master', ['deal_form_type' => ($customized_default ? 1 : 0)]);
        echo json_encode(['success' => true]);
        exit;
    }

    // AJAX: Get form layout for a deal stage and company
    public function get_form_layout()
    {
        $deal_stage_id = $this->input->get('deal_stage_id');
        $company_id = get_staff_company_id();
        $row = $this->db->where(['deal_stage_id' => $deal_stage_id, 'company_id' => $company_id])
            ->get('it_crm_deals_stage_custom')->row();
        $layout = $row && $row->form_layout ? json_decode($row->form_layout, true) : [];
        echo json_encode(['success' => true, 'layout' => $layout]);
        exit;
    }
    // AJAX: Save form layout for a deal stage and company
    public function save_form_layout()
    {
        $deal_stage_id = $this->input->post('deal_stage_id');
        $company_id = get_staff_company_id();
        $layout = $this->input->post('layout');
        // If record exists, update; else insert
        $exists = $this->db->where(['deal_stage_id' => $deal_stage_id, 'company_id' => $company_id])
            ->get('it_crm_deals_stage_custom')->row();
        if ($exists) {
            $this->db->where(['deal_stage_id' => $deal_stage_id, 'company_id' => $company_id])
                ->update('it_crm_deals_stage_custom', ['form_layout' => $layout]);
        } else {
            $this->db->insert('it_crm_deals_stage_custom', [
                'deal_stage_id' => $deal_stage_id,
                'company_id' => $company_id,
                'form_layout' => $layout,
                'order' => 9999,
                'checked' => 1
            ]);
        }
        echo json_encode(['success' => true]);
        exit;
    }

    // AJAX: Get form layout status for multiple stages
    public function get_form_layout_status()
    {
        $ids = $this->input->post('ids');
        $company_id = get_staff_company_id();
        $statusMap = [];
        if (is_array($ids)) {
            $rows = $this->db->where('company_id', $company_id)
                ->where_in('deal_stage_id', $ids)
                ->get('it_crm_deals_stage_custom')->result();
            foreach ($rows as $row) {
                $statusMap[$row->deal_stage_id] = !empty($row->form_layout);
            }
        }
        echo json_encode(['statusMap' => $statusMap]);
        exit;
    }

    // AJAX: Get current company's deal_form_type
    public function get_company_deal_form_type()
    {
        $company_id = get_staff_company_id();
        $row = $this->db->where('company_id', $company_id)
            ->get('it_crm_company_master')->row();
        $deal_form_type = $row && isset($row->deal_form_type) ? (int)$row->deal_form_type : 0;
        echo json_encode(['deal_form_type' => $deal_form_type]);
        exit;
    }
} 