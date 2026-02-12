<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Important_document_public extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function edit($token)
    {
        $token = trim((string) $token);
        if ($token === '') {
            show_404();
        }

        $this->ensure_share_columns();
        $doc = $this->db->get_where(db_prefix() . 'important_documents', [
            'share_token' => $token,
            'share_enabled' => 1,
        ])->row_array();
        if (!$doc || empty($doc['document_path'])) {
            show_404();
        }

        $ext = strtolower(pathinfo($doc['document_path'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xls', 'xlsx'], true)) {
            show_404();
        }

        $data = [];
        $data['title'] = 'Shared Excel';
        $data['document'] = $doc;
        $this->load->view('important_document/public_edit_excel', $data);
    }

    public function save($token)
    {
        $token = trim((string) $token);
        if ($token === '') {
            echo json_encode(['success' => false, 'message' => 'Invalid token']);
            return;
        }

        $this->ensure_share_columns();
        $doc = $this->db->get_where(db_prefix() . 'important_documents', [
            'share_token' => $token,
            'share_enabled' => 1,
        ])->row_array();
        if (!$doc || empty($doc['document_path'])) {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
            return;
        }

        $ext = strtolower(pathinfo($doc['document_path'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['xls', 'xlsx'], true)) {
            echo json_encode(['success' => false, 'message' => 'Only Excel files can be edited']);
            return;
        }

        $base64 = $this->input->post('file_base64');
        if (!$base64) {
            echo json_encode(['success' => false, 'message' => 'Missing file data']);
            return;
        }

        $decoded = base64_decode($base64);
        if ($decoded === false) {
            echo json_encode(['success' => false, 'message' => 'Invalid file data']);
            return;
        }

        $filePath = FCPATH . $doc['document_path'];
        if (file_put_contents($filePath, $decoded) === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to save file']);
            return;
        }

        $this->db->where('id', (int) $doc['id'])
            ->update(db_prefix() . 'important_documents', [
                'updatedon' => date('Y-m-d H:i:s'),
            ]);

        echo json_encode(['success' => true, 'message' => 'File saved']);
    }

    private function ensure_share_columns()
    {
        $table = 'important_documents';
        $prefixedTable = $this->db->dbprefix($table);
        $tables = $this->db->list_tables();
        if (!in_array($prefixedTable, $tables)) {
            return;
        }
        $this->load->dbforge();
        if (!$this->db->field_exists('share_token', $table)) {
            $this->dbforge->add_column($table, [
                'share_token' => [
                    'type' => 'VARCHAR',
                    'constraint' => 64,
                    'null' => true,
                ],
            ]);
        }
        if (!$this->db->field_exists('share_enabled', $table)) {
            $this->dbforge->add_column($table, [
                'share_enabled' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                ],
            ]);
        }
    }
}
