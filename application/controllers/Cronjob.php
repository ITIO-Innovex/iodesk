<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cronjob extends ClientsController
{
    public function index()
    {
        show_404();
    }

    public function download_email_from_cron($id)
    {
        try {
            // Increase execution time limit for email download
            set_time_limit(300); // 5 minutes
            ini_set('max_execution_time', 300);
            
            $data['title'] = _l('Download Email From Cron');
            
            // Validate ID
            if (empty($id) || !is_numeric($id)) {
                $data['message'] = '<div class="alert alert-danger">Invalid Mailbox ID provided!</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
           
            // Load webmail model
            $this->load->model('webmail_model');
			
            
            // Check if model was loaded successfully
            if (!isset($this->webmail_model) || !is_object($this->webmail_model)) {
                $data['message'] = '<div class="alert alert-danger">Error: Failed to load webmail model! Model object not found.</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Check if method exists
            if (!method_exists($this->webmail_model, 'downloadmail')) {
                $data['message'] = '<div class="alert alert-danger">Error: downloadmail method not found in webmail_model!</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Log that we're about to call the method
            log_message('error', 'Cronjob: About to call downloadmail with ID: ' . $id);
            
            // Call downloadmail method
            try {
                $result = $this->webmail_model->downloadmail($id);
                log_message('error', 'Cronjob: downloadmail returned: ' . (is_array($result) ? json_encode($result) : $result));
            } catch (\Exception $e) {
                log_message('error', 'Cronjob: Exception in downloadmail: ' . $e->getMessage());
                $result = 'Error calling downloadmail: ' . $e->getMessage();
            } catch (\Error $e) {
                log_message('error', 'Cronjob: Fatal Error in downloadmail: ' . $e->getMessage());
                $result = 'Fatal error calling downloadmail: ' . $e->getMessage();
            }
            
            // Check if result is null or empty (method might not have been called)
            if (!isset($result)) {
                $data['message'] = '<div class="alert alert-danger">Error: downloadmail method returned no result. Check logs for details.</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Check if result is an array (error case from some methods)
            if (is_array($result)) {
                if (isset($result['msg'])) {
                    $data['message'] = '<div class="alert alert-danger">' . htmlspecialchars($result['msg']) . '</div>';
                } else {
                    $data['message'] = '<div class="alert alert-info">Total emails downloaded: ' . (isset($result['cnt']) ? $result['cnt'] : 0) . '</div>';
                }
            } else {
                // Result is a string
                if (stripos($result, 'error') !== false || stripos($result, 'failed') !== false || stripos($result, 'invalid') !== false) {
                    $data['message'] = '<div class="alert alert-danger">' . htmlspecialchars($result) . '</div>';
                } else {
                    $data['message'] = '<div class="alert alert-success">' . htmlspecialchars($result) . '</div>';
                }
            }
            
            $this->load->view('cronjob/download_email_from_cron', $data);
        } catch (\Exception $e) {
            $data['title'] = _l('Download Email From Cron');
            $error_msg = $e->getMessage();
            if (stripos($error_msg, 'Maximum execution time') !== false) {
                $error_msg = 'Process timed out. The email download was taking too long. Try running it again - it will continue from where it left off.';
            }
            $data['message'] = '<div class="alert alert-danger">Error: ' . htmlspecialchars($error_msg) . '</div>';
            $this->load->view('cronjob/download_email_from_cron', $data);
        } catch (\Error $e) {
            $data['title'] = _l('Download Email From Cron');
            $error_msg = $e->getMessage();
            $data['message'] = '<div class="alert alert-danger">Fatal Error: ' . htmlspecialchars($error_msg) . '</div>';
            $this->load->view('cronjob/download_email_from_cron', $data);
        }
    }

}