<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Telegram extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leads_model');
        $this->load->model('telegram_model');
    }
    public function configuration()
    {
        // Fetch all Telegram configurations
        $data['configurationData'] = $this->telegram_model->getAllTelegramConfigurations();
        $adminCompanyId = get_staff_company_id();
        $data['departmentData'] = $this->leads_model->getAdminAllDepartments($adminCompanyId);
        $data['title'] = 'Telegram-Configuration';
        $this->load->view('admin/telegram/configuration', $data);
    }
   public function add_update_configuration()
    {
        if ($this->input->post()) {
            $data = $this->input->post();
            $id = isset($data['id']) && !empty($data['id']) ? $data['id'] : null;

            // Type-based logic
            if (isset($data['type']) && $data['type'] == 1) {
                $data['staff_ids'] = '';
            } elseif (isset($data['type']) && $data['type'] == 2) {
                $data['department_id'] = 0;
            } else {
                $data['department_id'] = 0;
                $data['staff_ids'] = '';
                set_alert('warning', 'Please select a type');
                redirect(admin_url('telegram/configuration'));
            }

            if (isset($data['department_id']) && $data['department_id'] == 0) {
                $data['department_id'] = '';
                set_alert('warning', 'Please select a department');
                redirect(admin_url('telegram/configuration'));
            }

            // Webhook setup (always fresh)
            $deleteUrl = "https://api.telegram.org/bot" . $data['telegram_token'] . "/deleteWebhook";
            $ch = curl_init($deleteUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $deleteResponse = curl_exec($ch);
            curl_close($ch);
            if ($deleteResponse === false) {
                set_alert('danger', 'Failed to delete existing webhook.');
                redirect(admin_url('telegram/configuration'));
            }

            $webhookUrl = base_url('import-telegram.php?bot=' . urlencode($data['telegram_name']));
            $setUrl = "https://api.telegram.org/bot" . $data['telegram_token'] . "/setWebhook?url=" . urlencode($webhookUrl);
            $ch = curl_init($setUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $setResponse = curl_exec($ch);
            curl_close($ch);
            if ($setResponse === false) {
                set_alert('danger', 'Failed to set webhook.');
                redirect(admin_url('telegram/configuration'));
            }

            $data['webhook'] = $webhookUrl;

            // Decide: Update or Insert
            if ($id) {
                // Update existing config
                $update = $this->telegram_model->updateTelegramConfiguration($id, $data);
                if ($update) {
                    set_alert('success', 'Configuration updated successfully.');
                } else {
                    set_alert('warning', 'Failed to update configuration.');
                }
            } else {
                // Check for duplicates by username or name
                $existingConfig = $this->telegram_model->getTelegramConfigurationByNameOrUsername($data['telegram_name'], $data['telegram_username']);
                if ($existingConfig) {
                    set_alert('warning', 'A configuration with this name or username already exists.');
                    redirect(admin_url('telegram/configuration'));
                }

                // Insert new config
                $add = $this->telegram_model->addTelegramConfiguration($data);
                if ($add) {
                    set_alert('success', 'Configuration added successfully.');
                } else {
                    set_alert('danger', 'Failed to add configuration.');
                }
            }

            redirect(admin_url('telegram/configuration'));
        }
    }
    public function delete_configuration($id)
    {
        // Check if the ID is valid
        if (is_numeric($id) && $id > 0) {
            // delete the webhook using the Telegram API
            $config = $this->telegram_model->getTelegramConfigurationById($id);
            if (!$config) {
                set_alert('danger', 'Configuration not found.');
                redirect(admin_url('telegram/configuration'));
            }
            $url = "https://api.telegram.org/bot" . $config['telegram_token'] . "/deleteWebhook";
            // Make the API call to delete the webhook
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            // Check if the response indicates success
            if ($response === false) {
                set_alert('danger', 'Failed to delete existing webhook.');
                redirect(admin_url('telegram/configuration'));
            }
            // Delete the configuration by ID
            $delete = $this->telegram_model->deleteTelegramConfiguration($id);
            if ($delete) {
                set_alert('success', 'Configuration deleted successfully.');
            } else {
                set_alert('danger', 'Failed to delete configuration.');
            }
        } else {
            set_alert('danger', 'Invalid configuration ID.');
        }
        redirect(admin_url('telegram/configuration'));
    }
}