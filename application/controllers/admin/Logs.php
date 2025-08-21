<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Logs extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
    }

    public function index()
    {
        if (!is_admin()) {
            access_denied('logs');
        }

        $data['title'] = 'System Logs';
        
        // Get all log files
        $log_path = APPPATH . 'logs/';
        $log_files = [];
        
        if (is_dir($log_path)) {
            $files = scandir($log_path);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'php' && strpos($file, 'log-') === 0) {
                    $log_files[] = [
                        'name' => $file,
                        'size' => filesize($log_path . $file),
                        'modified' => filemtime($log_path . $file)
                    ];
                }
            }
            // Sort by modified date, newest first
            usort($log_files, function($a, $b) {
                return $b['modified'] - $a['modified'];
            });
        }
        
        $data['log_files'] = $log_files;
        $data['selected_file'] = $this->input->get('file');
        $data['log_content'] = '';
        
        if ($data['selected_file'] && file_exists($log_path . $data['selected_file'])) {
            $content = file_get_contents($log_path . $data['selected_file']);
            // Remove PHP opening tag for display
            $content = str_replace('<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\'); ?>', '', $content);
            $data['log_content'] = $content;
        }
        
        $this->load->view('admin/logs/manage', $data);
    }

    public function download($filename = '')
    {
        if (!is_admin()) {
            access_denied('logs');
        }

        $log_path = APPPATH . 'logs/';
        $file_path = $log_path . $filename;
        
        if (file_exists($file_path) && strpos($filename, 'log-') === 0) {
            $this->load->helper('download');
            force_download($filename, file_get_contents($file_path));
        } else {
            show_404();
        }
    }

    public function clear($filename = '')
    {
        if (!is_admin()) {
            access_denied('logs');
        }

        $log_path = APPPATH . 'logs/';
        $file_path = $log_path . $filename;
        
        if (file_exists($file_path) && strpos($filename, 'log-') === 0) {
            file_put_contents($file_path, '<?php defined(\'BASEPATH\') OR exit(\'No direct script access allowed\'); ?>');
            set_alert('success', 'Log file cleared successfully');
        }
        
        redirect(admin_url('logs'));
    }
}
