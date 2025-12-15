<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Payroll extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payroll_model');
        $this->load->model('hrd_model');
    }

    public function setting($type = 'components')
    {
        if ($type === 'components') {
            $this->components();
            return;
        } elseif ($type === 'ctc') {
            $this->ctc();
            return;
        } elseif ($type === 'ctc-details') {
            $this->ctc_details();
            return;
        } elseif ($type === 'generate_salary_slip') {
            $this->generate_salary_slip();
            return;
        } elseif ($type === 'generated_salary_slip') {
            $this->generated_salary_slip();
            return;
        } elseif ($type === 'salary_slip') {
            $this->salary_slip();
            return;
        } elseif ($type === 'salary_slip_download') {
            $this->salary_slip_download();
            return;
        }

        show_404();
    }

    /**
     * Payroll components listing
     */
    public function components()
    {
        $this->assert_payroll_permission('Payroll Components');

        $companyId = $this->current_company_id();
        $where     = [];
        if ($companyId) {
            $where['company_id'] = $companyId;
        }

        $components = $this->payroll_model->get_components('', $where);
        $data['components'] = $components;
        $data['component_options'] = $components;
        $data['title']             = 'Payroll Components';

        $this->load->view('admin/payroll/setting/components', $data);
    }

    /**
     * Payroll CTC (Staff management mirror)
     */
    public function ctc()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Staff Management');
        }

        $companyId = $this->current_company_id();

        // Staff listing
        $this->db->select('s.staffid, s.employee_code, s.title, s.firstname, s.lastname, s.branch as branch, s.department_id, s.designation_id, s.staff_type, s.gender, s.date_of_birth as dob, s.email, s.phonenumber, s.joining_date AS joining_date, s.approver, s.employee_status, s.reporting_manager, d.name AS department, des.title AS designation, br.branch_name AS branch_name, st.title AS staff_type_name');
        $this->db->from(db_prefix() . 'staff s');
        $this->apply_company_scope('s', $companyId);
        $this->db->join(db_prefix() . 'departments d', 'd.departmentid = s.department_id', 'left');
        $this->db->join(db_prefix() . 'designations des', 'des.id = s.designation_id', 'left');
        $this->db->join(db_prefix() . 'hrd_branch_manager br', 'br.id = s.branch', 'left');
        $this->db->join(db_prefix() . 'hrd_staff_type st', 'st.id = s.staff_type', 'left');
        $this->db->order_by('s.firstname', 'asc');
        $data['staff_rows'] = $this->db->get()->result_array();

        // Branches
        $this->apply_company_scope(null, $companyId);
        $this->db->where('status', 1);
        $data['branches'] = $this->hrd_model->get_branch_manager();

        // Departments
        $this->db->reset_query();
        $this->apply_company_scope(null, $companyId);
        $data['departments'] = $this->db->get(db_prefix() . 'departments')->result_array();

        // Designations
        $this->load->model('staff_model');
        $data['designations'] = $this->staff_model->get_designation();

        // Staff types
        $this->db->reset_query();
        $this->apply_company_scope(null, $companyId);
        $this->db->where('status', 1);
        $data['staff_types'] = $this->hrd_model->get_staff_type();

        // Dept 9 employees (approvers)
        $this->db->reset_query();
        $this->apply_company_scope(null, $companyId);
        $this->db->where('active', 1);
        $this->db->where('department_id', 9);
        $this->db->select('staffid, email, firstname, lastname, CONCAT(firstname, " ", lastname) AS full_name');
        $this->db->order_by('firstname', 'asc');
        $data['dept9_employees'] = $this->db->get(db_prefix() . 'staff')->result_array();

        // All employees
        $this->db->reset_query();
        $this->apply_company_scope(null, $companyId);
        $this->db->where('active', 1);
        $this->db->select('staffid, email, firstname, lastname, CONCAT(firstname, " ", lastname) AS full_name');
        $this->db->order_by('firstname', 'asc');
        $data['all_employees'] = $this->db->get(db_prefix() . 'staff')->result_array();

        // Active payroll components for modal
        $componentWhere = [];
        if ($companyId) {
            $componentWhere['company_id'] = $companyId;
        }
        $componentWhere['is_active'] = 1;
        $data['payroll_components'] = $this->payroll_model->get_components('', $componentWhere);

        $data['title'] = 'Payroll CTC';
        $this->load->view('admin/payroll/setting/ctc', $data);
    }

    /**
     * Display CTC details slip
     */
    public function ctc_details()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Payroll CTC');
        }

        $staffId = (int) $this->input->get('staffid');
        if ($staffId <= 0) {
            show_error('Invalid staff selected');
        }

        $staff = $this->db->where('staffid', $staffId)->get(db_prefix() . 'staff')->row_array();
        if (!$staff) {
            show_404();
        }

        $structure = $this->payroll_model->get_staff_structure($staffId);
        $components = [];
        if ($structure && !empty($structure['items'])) {
            $componentIds = array_unique(array_filter(array_column($structure['items'], 'component_id')));
            if ($componentIds) {
                $components = $this->payroll_model->get_components('', ['id' => $componentIds]);
            }
        }

        $componentsById = [];
        foreach ($components as $component) {
            $componentsById[$component['id']] = $component;
        }

        $data = [
            'staff' => $staff,
            'structure' => $structure,
            'components' => $componentsById,
            'title' => 'Payroll CTC Details',
        ];

        $this->load->view('admin/payroll/setting/ctc_details', $data);
    }

    /**
     * Add/Edit payroll component
     */
    public function component()
    {
        $this->assert_payroll_permission('Payroll Components');

        $id        = $this->input->post('id');
        $companyId = $this->current_company_id();

        $percentOfComponent = $this->input->post('percent_of_component');
        $percentOfComponent = $percentOfComponent !== null && $percentOfComponent !== '' ? (int)$percentOfComponent : null;

        $data = [
            'company_id'           => $companyId,
            'code'                 => $this->input->post('code') ?: null,
            'title'                => $this->input->post('title'),
            'type'                 => $this->input->post('type'),
            'is_percentage'        => $this->input->post('is_percentage') ? 1 : 0,
            'percent_of_component' => $percentOfComponent,
            'is_active'            => $this->input->post('is_active') ? 1 : 0,
        ];

        if (!$data['is_percentage']) {
            $data['percent_of_component'] = null;
        }

        if ($id) {
            $updated = $this->payroll_model->update_component($data, $id, $companyId);
            if ($updated) {
                set_alert('success', 'Payroll component updated successfully');exit;
            } else {
                set_alert('warning', 'Unable to update payroll component');exit;
            }
        } else {
            $insertId = $this->payroll_model->add_component($data);
            if ($insertId) {
                set_alert('success', 'Payroll component added successfully');exit;
            } else {
                set_alert('warning', 'Unable to add payroll component');exit;
            }
        }

        redirect(admin_url('payroll/setting/components'));exit;
    }

    /**
     * Delete payroll component
     */
    public function delete_component($id)
    {
        $this->assert_payroll_permission('Payroll Components');

        $companyId = $this->current_company_id();
        if ($this->payroll_model->delete_component($id, $companyId)) {
            set_alert('success', 'Payroll component deleted successfully');
        } else {
            set_alert('warning', 'Unable to delete payroll component');
        }
        redirect(admin_url('payroll/setting/components'));
    }

    /**
     * Update staff data via Payroll CTC page (mirrors HRD staffentry)
     */
    public function staffentry()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Staff Management');
        }

        $staffid = (int)$this->input->post('staffid');
        if ($staffid <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid staff']);
            return;
        }

        $firstname = trim((string)$this->input->post('firstname'));
        $lastname  = trim((string)$this->input->post('lastname'));

        $hr_approver         = $this->input->post('hr_approver') !== '' ? (int)$this->input->post('hr_approver') : null;
        $admin_approver      = $this->input->post('admin_approver') !== '' ? (int)$this->input->post('admin_approver') : null;
        $hr_manager_approver = $this->input->post('hr_manager_approver') !== '' ? (int)$this->input->post('hr_manager_approver') : null;
        $reporting_approver  = $this->input->post('reporting_approver') !== '' ? (int)$this->input->post('reporting_approver') : null;

        $approver_data = [
            'hr_approver'         => $hr_approver,
            'admin_approver'      => $admin_approver,
            'hr_manager_approver' => $hr_manager_approver,
            'reporting_approver'  => $reporting_approver,
        ];

        $data = [
            'title'             => $this->input->post('title'),
            'firstname'         => $firstname,
            'lastname'          => $lastname,
            'employee_code'     => $this->input->post('employee_code'),
            'branch'            => ($this->input->post('branch') !== '') ? (int)$this->input->post('branch') : null,
            'department_id'     => ($this->input->post('department') !== '') ? (int)$this->input->post('department') : null,
            'designation_id'    => ($this->input->post('designation') !== '') ? (int)$this->input->post('designation') : null,
            'staff_type'        => ($this->input->post('staff_type') !== '') ? (int)$this->input->post('staff_type') : null,
            'reporting_manager' => ($this->input->post('reporting_manager') !== '') ? (int)$this->input->post('reporting_manager') : null,
            'phonenumber'       => $this->input->post('phonenumber'),
            'joining_date'      => $this->input->post('joining_date'),
            'date_of_birth'     => $this->input->post('dob'),
            'gender'            => $this->input->post('gender'),
            'approver'          => json_encode($approver_data),
            'employee_status'   => $this->input->post('employee_status') !== '' ? trim($this->input->post('employee_status')) : null,
        ];

        if ($this->input->post('employee_status') !== '') {
            if ($this->input->post('employee_status') === 'Active') {
                $data['active']      = 1;
                $data['is_not_staff'] = 0;
            } else {
                $data['active']      = 0;
                $data['is_not_staff'] = 1;
            }
        }

        $this->db->where('staffid', $staffid);
        $this->db->update(db_prefix() . 'staff', $data);

        if ($this->input->is_ajax_request()) {
            set_alert('success', 'Staff updated successfully');
            echo json_encode(['success' => true]);
            return;
        }

        set_alert('success', 'Staff updated successfully');
        redirect(admin_url('payroll/setting/ctc'));
    }

    /**
     * Fetch payroll structure for staff (AJAX)
     */
    public function get_structure($staffId)
    {
        if (!staff_can('view_own', 'hr_department')) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $staffId = (int) $staffId;
        if ($staffId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid staff']);
            return;
        }

        $structure = $this->payroll_model->get_staff_structure($staffId);
        if (!$structure) {
            $structure = [
                'base_salary' => 0,
                'items' => [],
            ];
        }

        echo json_encode(['success' => true, 'structure' => $structure]);
    }

    /**
     * Save payroll structure (AJAX)
     */
    public function save_structure()
    {
        if (!staff_can('view_own', 'hr_department')) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $staffId = (int) $this->input->post('staffid');
        if ($staffId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid staff']);
            return;
        }

        $baseSalary = (float) $this->input->post('base_salary');
        $itemsRaw = $this->input->post('items');
        $itemsDecoded = json_decode($itemsRaw, true);

        if (!is_array($itemsDecoded)) {
            echo json_encode(['success' => false, 'message' => 'Invalid structure payload']);
            return;
        }

        $normalizedItems = [];
        foreach ($itemsDecoded as $item) {
            $componentId = isset($item['component_id']) ? (int) $item['component_id'] : 0;
            if ($componentId <= 0) {
                continue;
            }

            $calcType = isset($item['calc_type']) && $item['calc_type'] === 'percent' ? 'percent' : 'fixed';
            $amount = isset($item['amount']) ? (float) $item['amount'] : 0;
            $percentOf = isset($item['percent_of_component']) && $item['percent_of_component'] !== ''
                ? (int) $item['percent_of_component']
                : null;

            if ($calcType === 'fixed' && $amount <= 0) {
                continue;
            }

            if ($calcType === 'percent') {
                if ($amount <= 0 || !$percentOf) {
                    continue;
                }
            } else {
                $percentOf = null;
            }

            $normalizedItems[] = [
                'component_id' => $componentId,
                'amount' => $amount,
                'calc_type' => $calcType,
                'percent_of_component' => $percentOf,
            ];
        }

        $saved = $this->payroll_model->save_staff_structure($staffId, $baseSalary, $normalizedItems);

        if ($saved) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unable to save structure']);
        }
    }

    /**
     * Generate salary slip page
     */
    public function generate_salary_slip()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Generate Salary Slip');
        }

        $selectedMonth = $this->input->get('month');
        if (!$selectedMonth || !preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = date('Y-m');
        }

        $companyId = $this->current_company_id();
        $componentRows = $this->payroll_model->get_components();
        $componentMap = [];
        foreach ($componentRows as $component) {
            $componentMap[$component['id']] = $component;
        }

        $staffRows = $this->payroll_model->get_staff_with_structures($companyId);
		//print_r($staffRows);
        foreach ($staffRows as &$staff) {
            $summary = $this->prepare_structure_summary($staff['base_salary'], $staff['structure_items'], $componentMap);
            $staff['payroll_summary'] = $summary;
        }
        unset($staff);

        $data = [
            'title'          => 'Generate Salary Slip',
            'selected_month' => $selectedMonth,
            'staff_rows'     => $staffRows,
        ];

        $this->load->view('admin/payroll/setting/generate_salary_slip', $data);
    }

    /**
     * Render generated salary slips listing with month filter
     */
    public function generated_salary_slip()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Generated Salary Slip');
        }

        $companyId = $this->current_company_id();
        $availableMonths = $this->payroll_model->get_payroll_run_months($companyId);

        $selectedMonth = $this->input->get('month');
        if ($selectedMonth && !preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            $selectedMonth = null;
        }

        if (!$selectedMonth && !empty($availableMonths)) {
            $selectedMonth = $availableMonths[0];
        }

        $slipRows = [];
        if ($selectedMonth) {
            $slipRows = $this->payroll_model->get_generated_slips($companyId, $selectedMonth);
        }

        $summaryTotals = [
            'staff_count' => count($slipRows),
            'gross'       => 0,
            'deductions'  => 0,
            'net'         => 0,
        ];
        foreach ($slipRows as $row) {
            $summaryTotals['gross'] += (float) ($row['gross_amount'] ?? 0);
            $summaryTotals['deductions'] += (float) ($row['deduction_amount'] ?? 0);
            $summaryTotals['net'] += (float) ($row['net_amount'] ?? 0);
        }

        $data = [
            'title'            => 'Generated Salary Slips',
            'available_months' => $availableMonths,
            'selected_month'   => $selectedMonth,
            'slip_rows'        => $slipRows,
            'summary_totals'   => $summaryTotals,
        ];

        $this->load->view('admin/payroll/setting/generated_salary_slip', $data);
    }

    /**
     * View individual salary slip for staff + month
     */
    public function salary_slip()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Salary Slip');
        }

        $staffId = (int) $this->input->get('id');
        $month   = $this->input->get('month');

        if ($staffId <= 0 || !$month || !preg_match('/^\d{4}-\d{2}$/', $month)) {
            show_error('Invalid salary slip request');
        }

        $companyId = $this->current_company_id();
        $slip = $this->payroll_model->get_staff_salary_slip($staffId, $month, $companyId);

        if (!$slip) {
            show_error('Salary slip not found for the selected month.');
        }

        $data = [
            'title'        => 'Salary Slip',
            'slip'         => $slip,
            'staffid'      => $staffId,
            'month'        => $month,
            'earnings'     => $slip['details']['earnings'] ?? [],
            'deductions'   => $slip['details']['deductions'] ?? [],
        ];

        $this->load->view('admin/payroll/setting/salary_slip', $data);
    }

    /**
     * Download salary slip as PDF
     */
    public function salary_slip_download()
    {
        if (!staff_can('view_own', 'hr_department')) {
            access_denied('Salary Slip Download');
        }

        $staffId = (int) $this->input->get('id');
        $month   = $this->input->get('month');

        if ($staffId <= 0 || !$month || !preg_match('/^\d{4}-\d{2}$/', $month)) {
            show_error('Invalid salary slip download request');
        }

        $companyId = $this->current_company_id();
        $slip = $this->payroll_model->get_staff_salary_slip($staffId, $month, $companyId);

        if (!$slip) {
            show_error('Salary slip not found for the selected month.');
        }

        $viewData = [
            'slip'       => $slip,
            'staffid'    => $staffId,
            'month'      => $month,
            'earnings'   => $slip['details']['earnings'] ?? [],
            'deductions' => $slip['details']['deductions'] ?? [],
        ];

        $this->load->helper('pdf');

        $pdf = app_pdf('salary_slip', APPPATH . 'libraries/pdf/Salary_slip_pdf.php', $viewData);
        $staffName = trim(($slip['firstname'] ?? '') . ' ' . ($slip['lastname'] ?? '')) ?: 'staff';
        $fileName = slug_it('Salary-Slip-' . $staffName . '-' . $month) . '.pdf';

        $pdf->Output($fileName, 'D');
    }

    /**
     * Run payroll (AJAX)
     */
    public function run_payroll()
    {
        if (!staff_can('view_own', 'hr_department')) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            return;
        }

        $selectedMonth = $this->input->post('month');
        if (!$selectedMonth || !preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
            echo json_encode(['success' => false, 'message' => 'Please select a valid month.']);
            return;
        }

        $staffIds = $this->input->post('staff_ids');
        if (empty($staffIds) || !is_array($staffIds)) {
            echo json_encode(['success' => false, 'message' => 'Select at least one staff.']);
            return;
        }

        $companyId = $this->current_company_id();
        $staffRows = $this->payroll_model->get_staff_with_structures($companyId, $staffIds);
        if (empty($staffRows)) {
            echo json_encode(['success' => false, 'message' => 'No eligible staff found.']);
            return;
        }

        $componentRows = $this->payroll_model->get_components();
        $componentMap = [];
        foreach ($componentRows as $component) {
            $componentMap[$component['id']] = $component;
        }

        $summaries = [];
        foreach ($staffRows as $staff) {
            $summary = $this->prepare_structure_summary($staff['base_salary'], $staff['structure_items'], $componentMap);
            $summary['staffid'] = $staff['staffid'];
            $summary['staff_name'] = trim(($staff['firstname'] ?? '') . ' ' . ($staff['lastname'] ?? ''));
            $summaries[] = $summary;
        }

        $runId = $this->payroll_model->create_payroll_run($selectedMonth, $companyId, get_staff_user_id(), $summaries);

        if (!$runId) {
            echo json_encode(['success' => false, 'message' => 'Failed to generate salary slips.']);
            return;
        }

        log_activity('Payroll run generated [RunID: ' . $runId . ', Month: ' . $selectedMonth . ']');
        echo json_encode(['success' => true, 'message' => 'Salary slips generated successfully.']);
    }

    /**
     * Toggle payroll component status (AJAX)
     */
    public function toggle_component($id)
    {
        if (!$this->can_access_payroll_setting()) {
            echo json_encode(['success' => false]);
            return;
        }

        $newStatus = $this->input->post('status') == 1 ? 1 : 0;

        $companyId = $this->current_company_id();
        $success   = $this->payroll_model->toggle_component_status($id, $newStatus, $companyId);

        echo json_encode(['success' => $success, 'new_status' => $newStatus]);
    }

    private function current_company_id()
    {
        if (!is_super()) {
            return get_staff_company_id();
        }

        if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
            return $_SESSION['super_view_company_id'];
        }

        return get_staff_company_id();
    }

    private function apply_company_scope($tableAlias = null, $companyId = null)
    {
        $companyId = $companyId ?: $this->current_company_id();
        if (!$companyId) {
            return;
        }

        if ($tableAlias) {
            $this->db->where($tableAlias . '.company_id', $companyId);
        } else {
            $this->db->where('company_id', $companyId);
        }
    }

    private function can_access_payroll_setting()
    {
        return staff_can('view_setting', 'hr_department');
    }

    private function assert_payroll_permission($context)
    {
        if (!$this->can_access_payroll_setting()) {
            access_denied($context);
        }
    }

    private function prepare_structure_summary($baseSalary, $items, $componentMap)
    {
        $baseSalary = (float) ($baseSalary ?: 0);
        $resolvedAmounts = [];
        $itemsCopy = [];
        foreach ($items as $item) {
            $itemsCopy[] = $item;
        }

        $iterations = count($itemsCopy) + 3;
        for ($i = 0; $i < $iterations; $i++) {
            $changes = false;
            foreach ($itemsCopy as &$row) {
                if (isset($row['_computed'])) {
                    continue;
                }

                $componentId = (int) ($row['component_id'] ?? 0);
                $calcType    = $row['calc_type'] ?? 'fixed';
                $rawAmount   = (float) ($row['amount'] ?? 0);

                if (!$componentId) {
                    $row['_computed'] = 0;
                    $changes = true;
                    continue;
                }

                if ($calcType === 'percent') {
                    $refComponent = (int) ($row['percent_of_component'] ?? 0);
                    if ($refComponent && !array_key_exists($refComponent, $resolvedAmounts)) {
                        continue;
                    }
                    $basis = $refComponent ? ($resolvedAmounts[$refComponent] ?? 0) : $baseSalary;
                    $row['_computed'] = round(($basis * $rawAmount) / 100, 2);
                } else {
                    $row['_computed'] = $rawAmount;
                }

                $resolvedAmounts[$componentId] = $row['_computed'];
                $changes = true;
            }
            unset($row);

            if (!$changes) {
                break;
            }
        }

        $earnings = [];
        $deductions = [];

        /*$earnings[] = [
            'label'     => 'Base Salary',
            'reference' => '-',
            'amount'    => $baseSalary,
        ];*/

        foreach ($itemsCopy as $item) {
            $componentId = (int) ($item['component_id'] ?? 0);
            $component   = $componentMap[$componentId] ?? [];
            $type        = $component['type'] ?? 'earning';
            $amount      = isset($item['_computed']) ? $item['_computed'] : (float) ($item['amount'] ?? 0);
            $calcType    = $item['calc_type'] ?? 'fixed';
            $reference   = $calcType === 'percent'
                ? number_format((float) ($item['amount'] ?? 0), 2) . '% of ' . ($componentMap[$item['percent_of_component']] ['title'] ?? 'Base Salary')
                : 'Fixed amount';

            $row = [
                'label'     => $component['title'] ?? $component['name'] ?? ('Component #' . $componentId),
                'reference' => $reference,
                'amount'    => $amount,
            ];

            if ($type === 'deduction') {
                $deductions[] = $row;
            } else {
                $earnings[] = $row;
            }
        }

        $totalEarnings   = array_sum(array_column($earnings, 'amount'));
        $totalDeductions = array_sum(array_column($deductions, 'amount'));
        $netPay          = $totalEarnings - $totalDeductions;

        return [
            'gross'      => $totalEarnings,
            'deductions' => $totalDeductions,
            'net'        => $netPay,
            'details'    => [
                'earnings'   => $earnings,
                'deductions' => $deductions,
            ],
        ];
    }
}
