<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Database_backups extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['download', 'file']);
        $this->load->library('zip');
        
        // Only allow admin access
        if (!is_super()) {
            access_denied('Database Backups');
        }
    }

    public function index()
    {
        $data['title'] = 'Database Backup';
        $data['backups'] = $this->get_backup_files();
        $this->load->view('admin/database_backups/manage', $data);
    }

    public function export_backup()
    {
        try {
            // Create backups directory if it doesn't exist
            $backup_dir = FCPATH . 'backups' . DIRECTORY_SEPARATOR;
            if (!is_dir($backup_dir)) {
                if (!mkdir($backup_dir, 0777, true)) {
                    throw new Exception('Failed to create backup directory');
                }
                // Create .htaccess to protect directory
                if (!file_exists($backup_dir . '.htaccess')) {
                    file_put_contents($backup_dir . '.htaccess', "Order Deny,Allow\nDeny from all");
                }
            }
            
            // Test write permissions by creating a temporary file
            $test_file = $backup_dir . 'test_write_' . time() . '.tmp';
            if (!@file_put_contents($test_file, 'test')) {
                throw new Exception('Backup directory is not writable. Please check folder permissions for: ' . $backup_dir);
            }
            @unlink($test_file);

            // Get database configuration
            $db_config = $this->db->database;
            $db_name = $this->db->database;
            $timestamp = date('Y-m-d_H-i-s');
            $filename = "backup_{$db_name}_{$timestamp}";
            $sql_file = $backup_dir . $filename . '.sql';
            $zip_file = $backup_dir . $filename . '.sql.zip';

            // Create MySQL dump command
            $host = $this->db->hostname;
            $username = $this->db->username;
            $password = $this->db->password;
            
            // Use mysqldump to create backup - escape password for Windows
            $escaped_password = escapeshellarg($password);
            $command = "mysqldump --host={$host} --user={$username} --password={$escaped_password} --single-transaction --routines --triggers {$db_name}";
            
            // Execute the command and capture output
            $output = [];
            $return_var = 0;
            exec($command, $output, $return_var);
            
            if ($return_var !== 0) {
                // Fallback: Try using CodeIgniter's database utilities
                $this->load->dbutil();
                $backup = $this->dbutil->backup();
                if (!$backup) {
                    throw new Exception('Failed to create database backup using both mysqldump and CI dbutil');
                }
                file_put_contents($sql_file, $backup);
            } else {
                // Write mysqldump output to file
                file_put_contents($sql_file, implode("\n", $output));
            }
            
            if (!file_exists($sql_file) || filesize($sql_file) == 0) {
                throw new Exception('Failed to create database backup - empty or missing file');
            }

            // Create ZIP file
            $zip = new ZipArchive();
            if ($zip->open($zip_file, ZipArchive::CREATE) !== TRUE) {
                throw new Exception('Failed to create ZIP file');
            }
            
            $zip->addFile($sql_file, $filename . '.sql');
            $zip->close();

            // Remove the SQL file (keep only ZIP)
            unlink($sql_file);

            if (!file_exists($zip_file)) {
                throw new Exception('Failed to create backup ZIP file');
            }

            // Force download
            $this->download_file($zip_file, $filename . '.sql.zip');

        } catch (Exception $e) {
            $this->session->set_flashdata('danger', 'Backup failed: ' . $e->getMessage());
            redirect(admin_url('database_backups'));
        }
    }

    public function download($filename)
    {
        if (empty($filename)) {
            show_404();
        }

        $backup_dir = FCPATH . 'backups/';
        $file_path = $backup_dir . $filename;

        if (!file_exists($file_path) || !is_file($file_path)) {
            show_404();
        }

        // Security check - ensure filename doesn't contain path traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            show_error('Invalid filename', 403);
        }

        $this->download_file($file_path, $filename);
    }

    public function delete()
    {
        if (!$this->input->post()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $filename = $this->input->post('filename');
        
        if (empty($filename)) {
            echo json_encode(['success' => false, 'message' => 'No filename provided']);
            return;
        }

        // Security check - ensure filename doesn't contain path traversal
        if (strpos($filename, '..') !== false || strpos($filename, '/') !== false || strpos($filename, '\\') !== false) {
            echo json_encode(['success' => false, 'message' => 'Invalid filename']);
            return;
        }

        $backup_dir = FCPATH . 'backups/';
        $file_path = $backup_dir . $filename;

        if (!file_exists($file_path)) {
            echo json_encode(['success' => false, 'message' => 'File not found']);
            return;
        }

        if (unlink($file_path)) {
            echo json_encode(['success' => true, 'message' => 'Backup deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete backup']);
        }
    }

    private function get_backup_files()
    {
        $backup_dir = FCPATH . 'backups/';
        $backups = [];

        if (!is_dir($backup_dir)) {
            return $backups;
        }

        $files = scandir($backup_dir);
        
        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === '.htaccess') {
                continue;
            }

            $file_path = $backup_dir . $file;
            
            if (is_file($file_path) && pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                $backups[] = [
                    'filename' => $file,
                    'size' => $this->format_file_size(filesize($file_path)),
                    'created_date' => date('Y-m-d H:i:s', filemtime($file_path))
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_date']) - strtotime($a['created_date']);
        });

        return $backups;
    }

    private function format_file_size($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    private function download_file($file_path, $download_name)
    {
        if (!file_exists($file_path)) {
            show_404();
        }

        // Set headers for file download
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $download_name . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));

        // Clear output buffer
        ob_clean();
        flush();

        // Read and output file
        readfile($file_path);
        exit;
    }
}
