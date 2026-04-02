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
        $data['is_admin_user'] = is_admin();

        //if (is_admin()) {
            $this->db->select('r.*, f.name as form_name, s.firstname, s.lastname, s.email as requester_email, ts.firstname as target_firstname, ts.lastname as target_lastname');
            $this->db->from('it_crm_web_form_share_requests r');
            $this->db->join(db_prefix() . 'web_forms f', 'f.id = r.form_id', 'left');
            $this->db->join(db_prefix() . 'staff s', 's.staffid = r.requested_by_staffid', 'left');
            $this->db->join(db_prefix() . 'staff ts', 'ts.staffid = r.target_staffid', 'left');
            $this->db->where('r.company_id', $companyId);
            $this->db->where('r.status', 'pending');
			$this->db->where('r.target_staffid', get_staff_user_id());
            $this->db->order_by('r.id', 'desc');
            $data['pending_share_requests'] = $this->db->get()->result_array();
        //} else {
            //$data['pending_share_requests'] = [];
        //}

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
        $staffId = get_staff_user_id();
        if (!is_array($assignTo)) {
            $assignTo = [];
        }
        $assignTo = array_values(array_filter(array_map('intval', $assignTo)));

        // Admin: keep current behavior (direct assign).
        if (is_admin()) {
            $this->db->where('id', $formId);
            $this->db->where('company_id', $companyId);
            $this->db->where('is_deleted', 0);
            $this->db->update(db_prefix() . 'web_forms', [
                'assign_to'  => json_encode($assignTo),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    		
    		if (!empty($assignTo)) {
                foreach ($assignTo as $assignid) {
    		        $notification_data = [
                        'description'     => 'assign_web_form',
                        'touserid'        => $assignid,
                        'link'            => 'web_form',
    					'fromuserid'      => 0,
    					'additional_data' => serialize([$formId,]),
                    ];
                    if (add_notification($notification_data)) {
                        pusher_trigger_notification([$assignid]);
                    }
    				log_activity(_l('assign_web_form') . ' -  [ Form ID: ' . $formId . ']');
                }
            }

            echo json_encode(['success' => true, 'message' => 'Form shared successfully.']);
            exit;
        }

        // Non-admin: email-based request to admin approval.
        $shareEmail = trim((string) $this->input->post('share_email'));
        if ($shareEmail === '' || !filter_var($shareEmail, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Please enter a valid email id.']);
            exit;
        }

        $target = $this->db->select('staffid, email, active')
            ->where('company_id', $companyId)
            ->where('email', $shareEmail)
            ->limit(1)
            ->get(db_prefix() . 'staff')
            ->row_array();

        if (!$target || (int) ($target['active'] ?? 0) !== 1) {
            echo json_encode(['success' => false, 'message' => 'Email id not found in staff table.']);
            exit;
        }

        $existsPending = $this->db->where('company_id', $companyId)
            ->where('form_id', $formId)
            ->where('requested_by_staffid', $staffId)
            ->where('target_staffid', (int) $target['staffid'])
            ->where('status', 'pending')
            ->count_all_results('it_crm_web_form_share_requests');

        if ($existsPending > 0) {
            echo json_encode(['success' => false, 'message' => 'Approval request already pending for this email.']);
            exit;
        }

        $this->db->insert('it_crm_web_form_share_requests', [
            'company_id'            => $companyId,
            'form_id'               => $formId,
            'requested_by_staffid'  => $staffId,
            'target_staffid'        => (int) $target['staffid'],
            'target_email'          => $shareEmail,
            'status'                => 'pending',
            'created_at'            => date('Y-m-d H:i:s'),
            'updated_at'            => date('Y-m-d H:i:s'),
        ]);
		/////////////Add Notification and Logs ////////////
		        $log_msg="share_web_form";
		        $notification_data = [
                    'description'     => $log_msg,
                    'touserid'        => $target['staffid'],
                    'link'            => 'web_form',
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$target['staffid']]);
                }
				log_activity(_l($log_msg).' -  [ Form ID: ' . $formId . ']');
		
		/////////////End Notification and Logs ////////////

        echo json_encode(['success' => true, 'message' => 'Request sent for admin approval.']);
        exit;
    }

    public function process_share_request()
    {
        if (!is_staff_logged_in()) { ///  || !is_admin() for admin
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit;
        }

        $companyId = get_staff_company_id();
        $requestId = (int) $this->input->post('request_id');
        $decision  = trim((string) $this->input->post('decision'));
        if (!in_array($decision, ['approved', 'rejected'], true)) {
            echo json_encode(['success' => false, 'message' => 'Invalid decision']);
            exit;
        }

        $request = $this->db->where('id', $requestId)
            ->where('company_id', $companyId)
            ->where('status', 'pending')
            ->get('it_crm_web_form_share_requests')
            ->row_array();

        if (!$request) {
            echo json_encode(['success' => false, 'message' => 'Request not found']);
            exit;
        }

        if ($decision === 'approved') {
            $this->db->select('assign_to');
            $form = $this->db->where('id', (int) $request['form_id'])
                ->where('company_id', $companyId)
                ->where('is_deleted', 0)
                ->get(db_prefix() . 'web_forms')
                ->row_array();
            if ($form) {
                $assignTo = json_decode((string) ($form['assign_to'] ?? '[]'), true);
                if (!is_array($assignTo)) {
                    $assignTo = [];
                }
                $assignTo[] = (int) $request['target_staffid'];
                $assignTo = array_values(array_unique(array_filter(array_map('intval', $assignTo))));

                $this->db->where('id', (int) $request['form_id']);
                $this->db->where('company_id', $companyId);
                $this->db->update(db_prefix() . 'web_forms', [
                    'assign_to'  => json_encode($assignTo),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->db->where('id', $requestId);
        $this->db->where('company_id', $companyId);
        $this->db->update('it_crm_web_form_share_requests', [
            'status'               => $decision,
            'approved_by_staffid'  => get_staff_user_id(),
            'approved_at'          => date('Y-m-d H:i:s'),
            'updated_at'           => date('Y-m-d H:i:s'),
        ]);

        echo json_encode(['success' => true, 'message' => 'Request ' . $decision . '.']);
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
		$formnamex    = $this->input->post('name') ?? '-';
        $log_msg="add_web_form";
        $formData = [
            'company_id' => $companyId,
			'staffid' 	 => $staffId,
            'name'       => trim((string) $this->input->post('name')),
            'description'=> trim((string) $this->input->post('description')),
            'is_active'  => (int) $this->input->post('is_active'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($formId > 0) {
		   $log_msg="update_web_form";
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
			$log_msg="update_web_form";
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
        /////////////////////Notification & log//////////////
		         $notification_data = [
                    'description'     => $log_msg,
                    'touserid'        => $staffId,
                    'link'            => 'web_form',
					'additional_data' => serialize([$formnamex,]),
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification([$staffId]);
                }
				log_activity(_l($log_msg).' -  [ Form Name: ' . $formnamex . ']');
		////////////////////////////////////////////////
        set_alert('success', 'Form saved successfully');
        //redirect(admin_url('web_form/create/' . $formId));
		//redirect(admin_url('web_form'));
		redirect(admin_url('web_form/manage/' . $formId));
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
        $id        = (int) $id;
        $reason    = trim((string) ($this->input->post('reason_for_delete') ?? ''));

        if ($id <= 0) {
            show_404();
        }

        $update = ['is_deleted' => 1];
        if ($reason !== '') {
            $update['reason_for_delete'] = $reason;
        }

        $this->db->where('id', $id);
        $this->db->where('company_id', $companyId);
        $this->db->update(db_prefix() . 'web_forms', $update);

        // If AJAX, just return ok
        if ($this->input->is_ajax_request()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            return;
        }

        set_alert('success', 'Form deleted successfully');
        redirect(admin_url('web_form'));
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
		
		/// Set Email From List
		/*$_SESSION['mailersdropdowns']="";*/
		if(empty($_SESSION['mailersdropdowns'])){
		$staffid=get_staff_user_id();
		$deptid=get_departments_id();
		$this->load->model('webmail_model');
		$wheredata=' mailer_status=1';
		$wheredata .=' AND ( staffid='.$staffid;
		$wheredata .=' OR departmentid='.$deptid;
		$wheredata .=' OR FIND_IN_SET('.$staffid.', assignto))';
		$_SESSION['mailersdropdowns']   = $this->webmail_model->getemaillist('', $wheredata);
		$data['webmailsetup']= $this->webmail_model->webmailsetup('', $wheredata);
			if(isset($data['webmailsetup'])&&$data['webmailsetup']){
			$_SESSION['webmail']=$data['webmailsetup'][0];
			}
		}
       
        $this->load->view('admin/web_form/manage', $data);
    }
	
	
	/**
     * Download csv Format
     * URL: /admin/web_form/download_csv_format/{form_id}
     */
    public function download_csv_format($formId)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }
        $companyId = get_staff_company_id();
        $formId    = (int) $formId;

        // Load form
		$this->db->select('name');
        $this->db->where('id', $formId);
        $this->db->where('company_id', $companyId);
        $this->db->where('is_deleted', 0);
        $form = $this->db->get(db_prefix() . 'web_forms')->row_array();
        if (!$form) {
            show_404();
        }
//generate file name
$formname=$form['name'];
// remove special characters
$filename = preg_replace('/[^A-Za-z0-9\-]/', '_', $formname);
// convert to lowercase
$filename = strtolower($filename);
// remove multiple underscores
$filename = preg_replace('/_+/', '_', $filename);
// final filename
$filename .= '_'.date("YmdHis").'.csv';

        // Load fields
        $this->db->where('form_id', $formId);
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sort_order', 'asc');
        $fields = $this->db->get(db_prefix() . 'web_form_fields')->result_array();
        $headerCols = [];
		  foreach ($fields as $f) {
			  if ($f['type'] === 'file') { continue; } // files not supported in CSV
			  $headerCols[] = $f['name'];
		  }
if (ob_get_length()) ob_end_clean();
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Pragma: no-cache');
header('Expires: 0');
$output = fopen('php://output', 'w');
fputcsv($output, $headerCols);
fclose($output);
exit;   
}

      /**
     * Save table column setting
     */

    public function update_table_field($formId){
	$fields = $this->input->post('fields');
    $_SESSION['selected_fields'][$formId] = !empty($fields) ? $fields : [];
	set_alert('success', 'Table column field updated successfully');
	redirect(admin_url('web_form/manage/' . $formId));
	}
    /**
     * Save entry (add/edit) for a form
     */
    public function save_entry($formId, $entryId = null)
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }
        print_r($this->input->post());
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
            echo $name = $field['name'];
            $type = $field['type'];
log_message('error', 'f Type - '.$type );


            // File fields handled via $_FILES below
            if ($type === 'file') {
                // Keep current files unless user uploads new ones
                $data[$name] = isset($existingData[$name]) ? $existingData[$name] : [];
                continue;
            }
            if ($type === 'editor') {
			$val  = $this->input->post($name, false);
			}else{
            $val  = $this->input->post($name);
			}

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

    /**
     * Send plain email from web form entry modal (Send New Email — no template).
     * POST (AJAX): to_email, cc_email, bcc_email, final_subject, final_body
     */
    public function send_row_email()
    {
        if (!is_staff_logged_in()) {
            access_denied('Web Form');
        }

        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $fromEmail = trim((string) ($this->input->post('reply_from') ?? ''));
        $toEmail  = trim((string) ($this->input->post('to_email') ?? ''));
        $ccEmail  = trim((string) ($this->input->post('cc_email') ?? ''));
        $bccEmail = trim((string) ($this->input->post('bcc_email') ?? ''));
        $subject  = trim((string) ($this->input->post('final_subject') ?? ''));
        $body     = $this->input->post('final_body', false);

        header('Content-Type: application/json');

        if ($toEmail === '') {
            echo json_encode(['success' => false, 'message' => 'To is required']);
            return;
        }
        if ($subject === '' || $body === '') {
            echo json_encode(['success' => false, 'message' => 'Subject and body are required']);
            return;
        }

        $this->load->model('webmail_model');
        $ok = $this->webmail_model->compose_email_super([
            'recipientEmail' 		=> $toEmail,
            'recipientCC'    		=> $ccEmail,
            'recipientBCC'   		=> $bccEmail,
            'emailSubject'   		=> $subject,
			'recipientFromEmail'    => $fromEmail,
            'emailBody'      		=> $body,
        ]);

        echo json_encode([
            'success' => (bool) $ok,
            'message' => $ok ? 'Email sent successfully' : 'Failed to send email',
        ]);
    }
	
	//Set Display in Menu
    public function display_in_menu($id)
    {
        if (!$id) {
            redirect(admin_url('web_form'));
        }        
            //log_message('error', 'USER ID - '.$id );
			
			$this->db->set('display_in_menu', 'IF(display_in_menu = 0, 1, 0)', false);
            $this->db->where('id', $id);
            $response = $this->db->update('it_crm_web_forms');
            if ($response == true) {
                set_alert('success', _l('menu display status updated', _l('menu display status priority')));
            } else {
                set_alert('warning', _l('problem_updating', _l('menu display status priority')));
            }
       
        
        redirect(admin_url('web_form'));
    }
	
	//Set Display in Menu
    public function create_powerform($id)
    {
        if (!$id) {
            redirect(admin_url('web_form'));
        }        
            //log_message('error', 'USER ID - '.$id );
			
			$powerform_id = bin2hex(random_bytes(16));
            $this->db->where('id', $id);
            $response = $this->db->update('it_crm_web_forms' , ['powerform_id'  => $powerform_id],);
            if ($response == true) {
                set_alert('success', _l('Powerform Generated', _l('Powerform Generated')));
            } else {
                set_alert('warning', _l('problem_updating', _l('Powerform Generated')));
            }
       
        
        redirect(admin_url('web_form'));
    }
	
	//Set Display in Publically
    public function display_staff($id)
    {
        if (!$id || !is_admin()) {
            access_denied('Web Form');
        }        
            //log_message('error', 'USER ID - '.$id );
			
			$this->db->set('form_type', 'IF(form_type = 0, 1, 0)', false);
            $this->db->where('id', $id);
            $response = $this->db->update('it_crm_web_forms');
            if ($response == true) {
                set_alert('success', _l('Menu display status updated', _l('Menu display status priority')));
            } else {
                set_alert('warning', _l('problem_updating', _l('Menu display status priority')));
            }
       
        
        redirect(admin_url('web_form'));
    }
}

