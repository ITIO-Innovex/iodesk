<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Web_form extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }
    }

    /**
     * Web form listing page.
     * URL: /admin/web_form
     */
    public function index()
    {
        $companyId = get_staff_company_id();
        $staffId = get_staff_user_id();
		
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
		$this->db->group_start();
		
		$this->db->where('staffid', $staffId);
		$this->db->or_like('assign_to', '"' . $staffId . '"'); // for JSON string
		$this->db->or_like('assign_to', $staffId); // fallback
		$this->db->group_end();

        $this->db->order_by('id', 'desc');
        $data['forms'] = $this->db->get(db_prefix() . 'web_forms')->result_array();
        //echo $this->db->last_query();exit;
        // Staff list for share modal
        $this->db->select('staffid, firstname, lastname, email');
        $this->db->where('company_id', $companyId);
        $this->db->where('active', 1);
        $this->db->order_by('firstname', 'asc');
        $data['staff_members'] = $this->db->get(db_prefix() . 'staff')->result_array();

        $data['title'] = 'Web Forms';
        $this->load->view('admin/web_form/index', $data);
    }

    public function save_assign_to()
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId    = (int) $this->input->post('form_id');
        $assignTo  = $this->input->post('assign_to');

        if (!is_array($assignTo)) {
            $assignTo = [];
        }
        $assignTo = array_values(array_filter(array_map('intval', $assignTo)));

        $this->db->where('id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $this->db->update(db_prefix() . 'web_forms', [
            'assign_to'  => json_encode($assignTo),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Form builder: list forms + add/edit form definition
     * URL: /admin/web_form/create
     */
    public function create($id = null)
    {
        $companyId = get_staff_company_id();

        // Load existing forms for this company (not deleted)
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $data['forms'] = $this->db->get(db_prefix() . 'web_forms')->result_array();

        // If a form has entries, we will lock field names + hide delete controls in builder
        $data['has_entries'] = false;

        // If editing, load form + fields
        $data['edit_form']   = null;
        $data['edit_fields'] = [];
        if ($id) {
            $this->db->where('id', (int) $id);
            $this->db->where('company_id', $companyId);
            $this->db->where('is_deleted', 0);
            $data['edit_form'] = $this->db->get(db_prefix() . 'web_forms')->row_array();

            if ($data['edit_form']) {
                $this->db->where('form_id', (int) $id);
                $this->db->where('is_deleted', 0);
                $this->db->order_by('sort_order', 'asc');
                $data['edit_fields'] = $this->db->get(db_prefix() . 'web_form_fields')->result_array();

                $this->db->where('form_id', (int) $id);
                $this->db->where('company_id', $companyId);
                $this->db->where('is_deleted', 0);
                $data['has_entries'] = $this->db->count_all_results(db_prefix() . 'web_form_entries') > 0;
            }
        }

        $data['title'] = 'Dynamic Web Forms';
        $this->load->view('admin/web_form/create', $data);
    }

    /**
     * Save form definition (add/edit)
     * Expects:
     *  - form_id (optional)
     *  - name, description
     *  - fields[0..n][label,name,type,required,options]
     */
    public function save()
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Form Name', 'required|trim');

        if ($this->form_validation->run() === false) {
            set_alert('danger', validation_errors());
            redirect(admin_url('web_form/create'));
        }

        $companyId = get_staff_company_id();
		$staffId   = get_staff_user_id();
        $formId    = (int) $this->input->post('form_id');

        $formData = [
            'company_id' => $companyId,
			'staffid' 	 => $staffId,
            'name'       => trim((string) $this->input->post('name')),
            'description'=> trim((string) $this->input->post('description')),
            'is_active'  => (int) $this->input->post('is_active'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($formId > 0) {
            // Update existing
            $this->db->where('id', $formId);
            $this->db->where('company_id', $companyId);
            $this->db->update(db_prefix() . 'web_forms', $formData);
        } else {
            // Insert new
            $formData['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert(db_prefix() . 'web_forms', $formData);
            $formId = (int) $this->db->insert_id();
        }

        // Save fields
        $fields = $this->input->post('fields');
        if (!is_array($fields)) {
            $fields = [];
        }

        // If entries already exist for this form, lock existing field names and don't delete fields.
        $hasEntries = false;
        if ($formId > 0) {
            $this->db->where('form_id', $formId);
            $this->db->where('company_id', $companyId);
            $this->db->where('is_deleted', 0);
            $hasEntries = $this->db->count_all_results(db_prefix() . 'web_form_entries') > 0;
        }

        if (!$hasEntries) {
            // No entries: Soft-delete old fields and recreate (simpler)
            $this->db->where('form_id', $formId);
            $this->db->update(db_prefix() . 'web_form_fields', ['is_deleted' => 1]);
        }

        $sort = 1;
        $usedNames = [];
        foreach ($fields as $field) {
            $label    = trim((string) ($field['label'] ?? ''));
            $name     = trim((string) ($field['name'] ?? ''));
            $fieldId  = isset($field['field_id']) ? (int) $field['field_id'] : 0;
            $type     = trim((string) ($field['type'] ?? ''));
            $required = isset($field['required']) ? 1 : 0;
            $options  = trim((string) ($field['options'] ?? ''));

            if ($label === '' || $name === '' || $type === '') {
                continue;
            }

            // If entries exist and this is an existing field, keep the original field name
            if ($hasEntries && $fieldId > 0) {
                $this->db->where('id', $fieldId);
                $this->db->where('form_id', $formId);
                $this->db->where('is_deleted', 0);
                $existing = $this->db->get(db_prefix() . 'web_form_fields')->row_array();
                if ($existing && !empty($existing['name'])) {
                    $name = $existing['name'];
                }
            }

            // Normalize field name to a safe key (client_name)
            $name = strtolower($name);
            $name = preg_replace('/[^a-z0-9]+/', '_', $name);
            $name = trim($name, '_');
            if ($name === '' || preg_match('/^[0-9]/', $name)) {
                $name = 'field_' . $name;
                $name = trim($name, '_');
            }
            if ($name === '') {
                $name = 'field';
            }

            // Ensure unique within form definition
            $base = $name;
            $i = 2;
            while (in_array($name, $usedNames, true)) {
                $name = $base . '_' . $i++;
            }
            $usedNames[] = $name;

            $optionsJson = null;
            if (in_array($type, ['select', 'radio', 'checkbox'], true) && $options !== '') {
                // options separated by newline or comma
                $parts = preg_split('/[\r\n,]+/', $options);
                $parts = array_filter(array_map('trim', $parts));
                $optionsJson = json_encode(array_values($parts));
            }

            if ($hasEntries && $fieldId > 0) {
                // Update existing field (do not change name when entries exist)
                $this->db->where('id', $fieldId);
                $this->db->where('form_id', $formId);
                $this->db->update(db_prefix() . 'web_form_fields', [
                    'label'        => $label,
                    'type'         => $type,
                    'is_required'  => $required,
                    'options_json' => $optionsJson,
                    'sort_order'   => $sort++,
                    'is_deleted'   => 0,
                ]);
            } else {
                // Insert new field
                $this->db->insert(db_prefix() . 'web_form_fields', [
                    'form_id'      => $formId,
                    'label'        => $label,
                    'name'         => $name,
                    'type'         => $type,
                    'is_required'  => $required,
                    'options_json' => $optionsJson,
                    'sort_order'   => $sort++,
                    'is_deleted'   => 0,
                ]);
            }
        }

        set_alert('success', 'Form saved successfully');
        //redirect(admin_url('web_form/create/' . $formId));
		redirect(admin_url('web_form'));
    }

    /**
     * Soft delete form
     */
    public function delete($id)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();

        $this->db->where('id', (int) $id);
        $this->db->where('company_id', $companyId);
        $this->db->update(db_prefix() . 'web_forms', ['is_deleted' => 1]);

        set_alert('success', 'Form deleted successfully');
        redirect(admin_url('web_form/create'));
    }

    /**
     * Manage entries for specific form (add/edit/delete data)
     * URL: /admin/web_form/manage/{form_id}
     */
    public function manage($formId)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId    = (int) $formId;

        // Load form
        $this->db->where('id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $form = $this->db->get(db_prefix() . 'web_forms')->row_array();
        if (!$form) {
            show_404();
        }

        // Load fields
        $this->db->where('form_id', $formId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $fields = $this->db->get(db_prefix() . 'web_form_fields')->result_array();

        // Load entries
        $this->db->where('form_id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('id', 'desc');
        $entries = $this->db->get(db_prefix() . 'web_form_entries')->result_array();

        $data = [
            'form'    => $form,
            'fields'  => $fields,
            'entries' => $entries,
            'title'   => 'Manage Form: ' . $form['name'],
        ];

        $this->load->view('admin/web_form/manage', $data);
    }

    /**
     * Save entry (add/edit) for a form
     */
    public function save_entry($formId, $entryId = null)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId    = (int) $formId;
        $entryId   = (int) $entryId;
        if ($entryId === 0) {
            $entryId = (int) $this->input->post('entry_id');
        }

        // Load fields
        $this->db->where('form_id', $formId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $fields = $this->db->get(db_prefix() . 'web_form_fields')->result_array();

        // Existing data (for edit, to preserve uploaded files if user doesn't re-upload)
        $existingData = [];
        if ($entryId > 0) {
            $this->db->where('id', $entryId);
            $this->db->where('form_id', $formId);
            $this->db->where('company_id', $companyId);
            $this->db->where('is_deleted', 0);
            $existing = $this->db->get(db_prefix() . 'web_form_entries')->row_array();
            if ($existing && !empty($existing['data_json'])) {
                $decoded = json_decode($existing['data_json'], true);
                if (is_array($decoded)) {
                    $existingData = $decoded;
                }
            }
        }

        $data = [];
        foreach ($fields as $field) {
            $name = $field['name'];
            $type = $field['type'];

            // File fields handled via $_FILES below
            if ($type === 'file') {
                // Keep current files unless user uploads new ones
                $data[$name] = isset($existingData[$name]) ? $existingData[$name] : [];
                continue;
            }

            $val  = $this->input->post($name);

            // Normalize checkbox/radio/select values
            if (is_array($val)) {
                $val = implode(', ', array_map('trim', $val));
            }

            $data[$name] = $val;
        }

        $row = [
            'form_id'    => $formId,
            'company_id' => $companyId,
            'is_deleted' => 0,
        ];

        if ($entryId > 0) {
            $this->db->where('id', $entryId);
            $this->db->where('form_id', $formId);
            $this->db->where('company_id', $companyId);
            $this->db->update(db_prefix() . 'web_form_entries', $row);
            set_alert('success', 'Entry updated successfully');
        } else {
            $row['created_at'] = date('Y-m-d H:i:s');
            $row['created_by'] = get_staff_user_id();
            // Insert first to get entry ID for file folder
            $row['data_json'] = json_encode($data);
            $this->db->insert(db_prefix() . 'web_form_entries', $row);
            $entryId = (int) $this->db->insert_id();
            set_alert('success', 'Entry added successfully');
        }

        // Handle multi file uploads
        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'web_forms' . DIRECTORY_SEPARATOR . (int)$companyId . DIRECTORY_SEPARATOR . (int)$formId . DIRECTORY_SEPARATOR . (int)$entryId . DIRECTORY_SEPARATOR;
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $uploadedAny = false;
        foreach ($fields as $field) {
            if ($field['type'] !== 'file') {
                continue;
            }
            $fname = $field['name'];
            if (!isset($_FILES[$fname])) {
                continue;
            }

            $files = $_FILES[$fname];
            // Normalize to arrays
            $names = is_array($files['name']) ? $files['name'] : [$files['name']];
            $tmp   = is_array($files['tmp_name']) ? $files['tmp_name'] : [$files['tmp_name']];
            $err   = is_array($files['error']) ? $files['error'] : [$files['error']];

            $savedPaths = is_array($data[$fname] ?? null) ? $data[$fname] : [];

            for ($i = 0; $i < count($names); $i++) {
                if (!isset($err[$i]) || $err[$i] !== UPLOAD_ERR_OK) {
                    continue;
                }
                $original = (string) $names[$i];
                $ext = pathinfo($original, PATHINFO_EXTENSION);
                $base = pathinfo($original, PATHINFO_FILENAME);
                $base = preg_replace('/[^a-zA-Z0-9\-_]+/', '_', $base);
                $base = trim($base, '_');
                if ($base === '') {
                    $base = 'file';
                }
                $newName = $base . '_' . time() . '_' . $i . ($ext ? '.' . $ext : '');
                $dest = $uploadDir . $newName;
                if (@move_uploaded_file($tmp[$i], $dest)) {
                    $uploadedAny = true;
                    $relative = 'uploads/web_forms/' . (int)$companyId . '/' . (int)$formId . '/' . (int)$entryId . '/' . $newName;
                    $savedPaths[] = $relative;
                }
            }

            $data[$fname] = $savedPaths;
        }

        // Update entry data_json (for edit, and after uploads)
        $this->db->where('id', $entryId);
        $this->db->where('form_id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->update(db_prefix() . 'web_form_entries', [
            'data_json' => json_encode($data),
        ]);

        redirect(admin_url('web_form/manage/' . $formId));
    }

    /**
     * Soft delete entry
     */
    public function delete_entry($formId, $entryId)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();

        $this->db->where('id', (int) $entryId);
        $this->db->where('form_id', (int) $formId);
        $this->db->where('company_id', $companyId);
        $this->db->update(db_prefix() . 'web_form_entries', ['is_deleted' => 1]);

        set_alert('success', 'Entry deleted successfully');
        redirect(admin_url('web_form/manage/' . (int)$formId));
    }

    /**
     * Bulk upload entries via CSV
     * URL: /admin/web_form/upload_csv/{form_id}
     */
    public function upload_csv($formId)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId    = (int) $formId;

        // Load form
        $this->db->where('id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $form = $this->db->get(db_prefix() . 'web_forms')->row_array();
        if (!$form) {
            show_404();
        }

        if (empty($_FILES['csv_file']['name'])) {
            set_alert('danger', 'Please select a CSV file to upload.');
            redirect(admin_url('web_form/manage/' . $formId));
        }

        // Load fields (exclude file fields from CSV import)
        $this->db->where('form_id', $formId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $fields = $this->db->get(db_prefix() . 'web_form_fields')->result_array();

        $fieldMap = [];
        foreach ($fields as $f) {
            // Skip file fields for CSV import
            if ($f['type'] === 'file') {
                continue;
            }
            $fieldMap[$f['name']] = $f;
        }

        $tmpPath = $_FILES['csv_file']['tmp_name'];
        if (!is_readable($tmpPath)) {
            set_alert('danger', 'Unable to read uploaded CSV file.');
            redirect(admin_url('web_form/manage/' . $formId));
        }

        $handle = fopen($tmpPath, 'r');
        if ($handle === false) {
            set_alert('danger', 'Unable to open uploaded CSV file.');
            redirect(admin_url('web_form/manage/' . $formId));
        }

        $rowIndex   = 0;
        $inserted   = 0;
        $skipped    = 0;
        $header     = [];
        $colToField = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            // Trim values
            $row = array_map(function ($v) {
                return trim((string) $v);
            }, $row);

            if ($rowIndex === 0) {
                // Header row
                $header = $row;
                foreach ($header as $idx => $col) {
                    if (isset($fieldMap[$col])) {
                        $colToField[$idx] = $col;
                    }
                }
                $rowIndex++;
                continue;
            }

            if (empty($colToField)) {
                break;
            }

            $entryData = [];
            $allEmpty  = true;

            foreach ($colToField as $idx => $fieldName) {
                $value = isset($row[$idx]) ? $row[$idx] : '';
                if ($value !== '') {
                    $allEmpty = false;
                }
                $entryData[$fieldName] = $value;
            }

            // Skip completely empty rows
            if ($allEmpty) {
                $skipped++;
                $rowIndex++;
                continue;
            }

            // Basic required check
            $missingRequired = false;
            foreach ($fieldMap as $fname => $f) {
                if (!empty($f['is_required'])) {
                    if (!isset($entryData[$fname]) || $entryData[$fname] === '') {
                        $missingRequired = true;
                        break;
                    }
                }
            }
            if ($missingRequired) {
                $skipped++;
                $rowIndex++;
                continue;
            }

            // Insert entry
            $rowDb = [
                'form_id'    => $formId,
                'company_id' => $companyId,
                'data_json'  => json_encode($entryData),
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => get_staff_user_id(),
                'is_deleted' => 0,
            ];
            $this->db->insert(db_prefix() . 'web_form_entries', $rowDb);
            $inserted++;
            $rowIndex++;
        }

        fclose($handle);

        set_alert('success', 'CSV upload completed. Inserted: ' . $inserted . ', Skipped: ' . $skipped . '.');
        redirect(admin_url('web_form/manage/' . $formId));
    }

    public function download_entry_excel($formId, $entryId)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId  = (int) $formId;
        $entryId = (int) $entryId;

        $this->db->where('id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $form = $this->db->get(db_prefix() . 'web_forms')->row_array();
        if (!$form) { show_404(); }

        $this->db->where('form_id', $formId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $fields = $this->db->get(db_prefix() . 'web_form_fields')->result_array();

        $this->db->where('id', $entryId);
        $this->db->where('form_id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $entry = $this->db->get(db_prefix() . 'web_form_entries')->row_array();
        if (!$entry) { show_404(); }
        $entryData = json_decode($entry['data_json'], true) ?: [];

        $filename = slug_it($form['name'] . '-entry-' . $entryId) . '.xls';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo '<table border="1" cellpadding="6" cellspacing="0">';
/*        echo '<tr><th colspan="2" style="font-size:14px;">' . html_escape($form['name']) . ' - Entry #' . (int)$entryId . '</th></tr>';
*/        echo '<tr><td><b>Created At</b></td><td>' . html_escape($entry['created_at']) . '</td></tr></table>';
		
		//print_r($fields);exit;
		echo '<table border="1" cellpadding="6" cellspacing="0"><tr>';
        foreach ($fields as $f) {
            $label = $f['label'] ?: $f['name'];
            $name  = $f['name'];
            $val   = $entryData[$name] ?? '';

            if (is_array($val)) {
                $links = [];
                foreach ($val as $p) {
                    if (!$p) { continue; }
                    $links[] = site_url($p);
                }
                $val = implode("\n", $links);
            }
            echo '<th><b>' . html_escape($label) . '</b></th>';
        }
        echo '</tr><tr>';
		foreach ($fields as $f) {
            $label = $f['label'] ?: $f['name'];
            $name  = $f['name'];
            $val   = $entryData[$name] ?? '';

            if (is_array($val)) {
                $links = [];
                foreach ($val as $p) {
                    if (!$p) { continue; }
                    $links[] = site_url($p);
                }
                $val = implode("\n", $links);
            }
            echo '<td>' . nl2br(html_entity_decode((string)$val)) . '</td>';
        }
		
		
        exit;
    }
//<td>' . nl2br(html_entity_decode((string)$val)) . '</td>
    public function download_entry_pdf($formId, $entryId)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId  = (int) $formId;
        $entryId = (int) $entryId;

        $this->db->where('id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $form = $this->db->get(db_prefix() . 'web_forms')->row_array();
        if (!$form) { show_404(); }

        $this->db->where('form_id', $formId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $fields = $this->db->get(db_prefix() . 'web_form_fields')->result_array();

        $this->db->where('id', $entryId);
        $this->db->where('form_id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $entry = $this->db->get(db_prefix() . 'web_form_entries')->row_array();
        if (!$entry) { show_404(); }
        $entryData = json_decode($entry['data_json'], true) ?: [];

        $this->load->helper('pdf');
        $pdfData = [
            'form'       => $form,
            'fields'     => $fields,
            'entry'      => $entry,
            'entry_data' => $entryData,
        ];

        $pdf = web_form_entry_pdf($pdfData);
        $fileName = mb_strtoupper(slug_it($form['name'] . '-entry-' . $entryId)) . '.pdf';

        if (ob_get_length()) {
            ob_end_clean();
        }
        $pdf->Output($fileName, 'D');
    }
    /**
     * Delete a single uploaded file from an entry (AJAX)
     * POST: form_id, entry_id, field_name, file_path
     */
    public function delete_entry_file()
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        $companyId = get_staff_company_id();
        $formId    = (int) $this->input->post('form_id');
        $entryId   = (int) $this->input->post('entry_id');
        $fieldName = trim((string) $this->input->post('field_name'));
        $filePath  = trim((string) $this->input->post('file_path'));

        if ($formId <= 0 || $entryId <= 0 || $fieldName === '' || $filePath === '') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        // Load entry and verify ownership
        $this->db->where('id', $entryId);
        $this->db->where('form_id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $entry = $this->db->get(db_prefix() . 'web_form_entries')->row_array();
        if (!$entry) {
            echo json_encode(['success' => false, 'message' => 'Entry not found']);
            exit;
        }

        $data = json_decode($entry['data_json'], true);
        if (!is_array($data)) {
            $data = [];
        }

        if (!isset($data[$fieldName]) || !is_array($data[$fieldName])) {
            echo json_encode(['success' => false, 'message' => 'No files found']);
            exit;
        }

        // Security: allow only within our upload directory pattern
        $expectedPrefix = 'uploads/web_forms/' . (int)$companyId . '/' . (int)$formId . '/' . (int)$entryId . '/';
        if (strpos($filePath, $expectedPrefix) !== 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid file path']);
            exit;
        }

        // Remove from json
        $data[$fieldName] = array_values(array_filter($data[$fieldName], function ($p) use ($filePath) {
            return (string) $p !== (string) $filePath;
        }));

        // Delete physical file
        $abs = FCPATH . ltrim(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $filePath), DIRECTORY_SEPARATOR);
        if (is_file($abs)) {
            @unlink($abs);
        }

        // Save back
        $this->db->where('id', $entryId);
        $this->db->where('form_id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->update(db_prefix() . 'web_form_entries', [
            'data_json' => json_encode($data),
        ]);

        echo json_encode(['success' => true, 'data' => $data[$fieldName]]);
        exit;
    }
}

