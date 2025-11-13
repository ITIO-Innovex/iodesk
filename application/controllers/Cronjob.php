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
            $this->load->model('webmail_model');
            
            // Validate ID
            if (empty($id) || !is_numeric($id)) {
                $data['message'] = '<div class="alert alert-danger">Invalid Mailbox ID provided!</div>';
                $this->load->view('cronjob/download_email_from_cron', $data);
                return;
            }
            
            // Call downloadmail method
            $result = $this->webmail_model->downloadmail($id);
            
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
        }
    }

}