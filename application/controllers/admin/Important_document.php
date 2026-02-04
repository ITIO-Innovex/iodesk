<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Important_document extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!(is_admin() || staff_can('view', 'hr_department') || staff_can('view_own', 'hr_department'))) {
            access_denied('Important Documents');
        }

        $this->ensure_soft_delete_column();
        $companyId = get_staff_company_id();
        $this->db->select('d.*, CONCAT(s.firstname, " ", s.lastname) as staff_name');
        $this->db->from(db_prefix() . 'important_documents as d');
        $this->db->join(db_prefix() . 'staff as s', 's.staffid = d.staff', 'left');
        $this->db->where('d.company_id', $companyId);
        if ($this->db->field_exists('is_deleted', db_prefix() . 'important_documents')) {
            $this->db->where('d.is_deleted', 0);
        }
        $this->db->order_by('d.addedon', 'desc');
        $documents = $this->db->get()->result_array();

        $data = [];
        $data['title'] = 'Important Documents';
        $data['documents'] = $documents;
        $this->load->view('admin/important_document/manage', $data);
    }

    public function save()
    {
        if (!(is_admin() || staff_can('view', 'hr_department') || staff_can('view_own', 'hr_department'))) {
            set_alert('warning', 'Access denied');
            redirect(admin_url('important_document'));
        }

        $this->ensure_soft_delete_column();
        $companyId = get_staff_company_id();
        $staffId = get_staff_user_id();
        $id = (int) $this->input->post('id');
        $title = trim((string) $this->input->post('document_title'));
        $remarks = trim((string) $this->input->post('remarks'));

        $uploadDir = FCPATH . 'uploads/important_documents/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        if ($id > 0) {
            $existing = $this->db->get_where(db_prefix() . 'important_documents', [
                'id' => $id,
                'company_id' => $companyId,
            ])->row_array();
            if (!$existing) {
                set_alert('warning', 'Record not found');
                redirect(admin_url('important_document'));
            }
            if (isset($existing['is_deleted']) && (int) $existing['is_deleted'] === 1) {
                set_alert('warning', 'Cannot edit a deleted document');
                redirect(admin_url('important_document'));
            }

            $update = [
                'document_title' => $title !== '' ? $title : null,
                'remarks' => $remarks,
                'updatedon' => date('Y-m-d H:i:s'),
            ];

            if (!empty($_FILES['document']['name'][0])) {
                $name = $_FILES['document']['name'][0];
                $tmp_name = $_FILES['document']['tmp_name'][0];
                if (!empty($name) && is_uploaded_file($tmp_name)) {
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $safeName = 'doc_' . $staffId . '_' . time() . '_' . mt_rand(1000, 9999) . ($ext ? ('.' . $ext) : '');
                    $dest = $uploadDir . $safeName;
                    if (@move_uploaded_file($tmp_name, $dest)) {
                        $update['document_path'] = 'uploads/important_documents/' . $safeName;
                        if (!empty($existing['document_path'])) {
                            $oldFile = FCPATH . $existing['document_path'];
                            if (is_file($oldFile)) {
                                @unlink($oldFile);
                            }
                        }
                    }
                }
            }

            $this->db->where('id', $id)->where('company_id', $companyId)->update(db_prefix() . 'important_documents', $update);
            set_alert('success', 'Document updated');
            redirect(admin_url('important_document'));
        }

        $savedAny = false;
        if (isset($_FILES['document'])) {
            $files = $_FILES['document'];
            $isMultiple = is_array($files['name']);
            $total = $isMultiple ? count($files['name']) : ($files['name'] ? 1 : 0);
            for ($i = 0; $i < $total; $i++) {
                $name = $isMultiple ? $files['name'][$i] : $files['name'];
                $tmp_name = $isMultiple ? $files['tmp_name'][$i] : $files['tmp_name'];
                if (empty($name) || !is_uploaded_file($tmp_name)) {
                    continue;
                }
                $ext = pathinfo($name, PATHINFO_EXTENSION);
                $safeName = 'doc_' . $staffId . '_' . time() . '_' . mt_rand(1000, 9999) . ($ext ? ('.' . $ext) : '');
                $dest = $uploadDir . $safeName;
                if (@move_uploaded_file($tmp_name, $dest)) {
                    $relPath = 'uploads/important_documents/' . $safeName;
                    $titleForThis = $title !== '' ? $title : pathinfo($name, PATHINFO_FILENAME);
                    $insert = [
                        'staff' => $staffId,
                        'company_id' => $companyId,
                        'document_title' => $titleForThis !== '' ? $titleForThis : null,
                        'document_path' => $relPath,
                        'remarks' => $remarks,
                        'addedon' => date('Y-m-d H:i:s'),
                    ];
                    $this->db->insert(db_prefix() . 'important_documents', $insert);
                    $savedAny = true;
                }
            }
        }

        if ($savedAny) {
            set_alert('success', 'Document(s) added');
        } else {
            set_alert('warning', 'No document uploaded');
        }
        redirect(admin_url('important_document'));
    }

    public function delete($id)
    {
        if (!(is_admin() || staff_can('view', 'hr_department') || staff_can('view_own', 'hr_department'))) {
            access_denied('Important Documents');
        }

        $this->ensure_soft_delete_column();
        $companyId = get_staff_company_id();
        $doc = $this->db->get_where(db_prefix() . 'important_documents', [
            'id' => (int) $id,
            'company_id' => $companyId,
        ])->row_array();
        if (!$doc) {
            set_alert('warning', 'Record not found');
            redirect(admin_url('important_document'));
        }

        $this->db->where('id', (int) $id)->where('company_id', $companyId)->update(db_prefix() . 'important_documents', [
            'is_deleted' => 1,
            'updatedon' => date('Y-m-d H:i:s'),
        ]);
        set_alert('success', 'Document deleted');
        redirect(admin_url('important_document'));
    }

    private function ensure_soft_delete_column()
    {
        $table = 'important_documents';
        $prefixedTable = $this->db->dbprefix($table);
        $tables = $this->db->list_tables();
        if (!in_array($prefixedTable, $tables)) {
            return;
        }
        if ($this->db->field_exists('is_deleted', $table)) {
            return;
        }
        $this->load->dbforge();
        $fields = [
            'is_deleted' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
        ];
        $this->dbforge->add_column($table, $fields);
    }
}
