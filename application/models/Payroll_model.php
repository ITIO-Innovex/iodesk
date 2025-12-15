<?php defined('BASEPATH') or exit('No direct script access allowed');

class Payroll_model extends App_Model
{
    /** @var string */
    protected $table;

    /** @var string */
    protected $structureTable;

    /** @var string */
    protected $structureItemsTable;

    /** @var string */
    protected $runsTable;

    /** @var string */
    protected $runItemsTable;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix() . 'payroll_component';
        $this->structureTable = db_prefix() . 'payroll_structure_master';
        $this->structureItemsTable = db_prefix() . 'payroll_structure_items';
        $this->runsTable = db_prefix() . 'payroll_runs';
        $this->runItemsTable = db_prefix() . 'payroll_items';
    }

    /**
     * Get payroll component(s)
     * @param  mixed $id Optional component id
     * @param  array $where Optional where conditions
     * @return mixed object when id passed else array
     */
    public function get_components($id = '', $where = [])
    {
        if (is_numeric($id)) {
            $this->apply_where_filters($where);
            $this->db->where('id', $id);
            return $this->db->get($this->table)->row();
        }

        $this->apply_where_filters($where);
        $this->db->order_by('title', 'asc');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Insert payroll component
     */
    public function add_component($data)
    {
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            log_activity('Payroll Component Added [ID: ' . $insert_id . ', Title: ' . $data['title'] . ']');
            return $insert_id;
        }
        return false;
    }

    /**
     * Update payroll component
     */
    public function update_component($data, $id, $companyId = null)
    {
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->update($this->table, $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Payroll Component Updated [ID: ' . $id . ', Title: ' . $data['title'] . ']');
            return true;
        }
        return false;
    }

    /**
     * Soft delete payroll component (set inactive)
     */
    public function delete_component($id, $companyId = null)
    {
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->update($this->table, ['is_active' => 0]);
        if ($this->db->affected_rows() > 0) {
            log_activity('Payroll Component Deactivated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    /**
     * Toggle payroll component active status
     */
    public function toggle_component_status($id, $status, $companyId = null)
    {
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->update($this->table, ['is_active' => $status]);
        return $this->db->affected_rows() > 0;
    }

    private function apply_where_filters($where)
    {
        if (empty($where)) {
            return;
        }

        foreach ($where as $column => $value) {
            if (is_array($value)) {
                if (!empty($value)) {
                    $this->db->where_in($column, $value);
                }
            } else {
                $this->db->where($column, $value);
            }
        }
    }

    /**
     * Get payroll structure master + items for staff
     */
    public function get_staff_structure($staffId)
    {
        $structure = $this->db->where('staffid', $staffId)->get($this->structureTable)->row_array();
        if (!$structure) {
            return null;
        }

        $items = $this->db->where('structure_id', $structure['id'])
            ->get($this->structureItemsTable)
            ->result_array();
        $structure['items'] = $items;

        return $structure;
    }

    /**
     * Save/update payroll structure for staff (master + items)
     */
    public function save_staff_structure($staffId, $baseSalary, $items)
    {
        $this->db->trans_start();

        $structure = $this->db->where('staffid', $staffId)->get($this->structureTable)->row_array();

        if ($structure) {
            $structureId = (int) $structure['id'];
            $this->db->where('id', $structureId)->update($this->structureTable, [
                'base_salary' => $baseSalary,
            ]);
            $this->db->where('structure_id', $structureId)->delete($this->structureItemsTable);
        } else {
            $this->db->insert($this->structureTable, [
                'staffid' => $staffId,
                'base_salary' => $baseSalary,
            ]);
            $structureId = (int) $this->db->insert_id();
        }

        foreach ($items as $item) {
            $this->db->insert($this->structureItemsTable, [
                'structure_id' => $structureId,
                'component_id' => $item['component_id'],
                'amount' => $item['amount'],
                'calc_type' => $item['calc_type'],
                'percent_of_component' => $item['percent_of_component'],
            ]);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            log_activity('Failed to update payroll structure [StaffID: ' . $staffId . ']');
            return false;
        }

        log_activity('Payroll structure updated [StaffID: ' . $staffId . ']');
        return true;
    }

    /**
     * Fetch staff records that have payroll structures + items
     *
     * @param int|null $companyId
     * @param array|null $staffIds
     * @return array
     */
    public function get_staff_with_structures($companyId = null, $staffIds = null)
    {
        $this->db->select([
            's.staffid',
            's.firstname',
            's.lastname',
            's.employee_code',
            's.email',
            's.department_id',
            's.designation_id',
            's.branch',
            's.active',
            'd.name AS department',
            'des.title AS designation',
            'br.branch_name AS branch_name',
            'sm.id AS structure_id',
            'sm.base_salary',
        ]);
        $this->db->from(db_prefix() . 'staff s');
        $this->db->join($this->structureTable . ' sm', 'sm.staffid = s.staffid', 'inner');
        $this->db->join(db_prefix() . 'departments d', 'd.departmentid = s.department_id', 'left');
        $this->db->join(db_prefix() . 'designations des', 'des.id = s.designation_id', 'left');
        $this->db->join(db_prefix() . 'hrd_branch_manager br', 'br.id = s.branch', 'left');

        if ($companyId) {
            $this->db->where('s.company_id', $companyId);
        }

        if (!empty($staffIds) && is_array($staffIds)) {
            $staffIds = array_filter(array_map('intval', $staffIds));
            if (!empty($staffIds)) {
                $this->db->where_in('s.staffid', $staffIds);
            }
        }

        $this->db->where('sm.base_salary >', 0);
        $this->db->where('s.active', 1);
        $this->db->order_by('s.firstname', 'asc');

        $rows = $this->db->get()->result_array();
        if (empty($rows)) {
            return [];
        }

        $structureIds = array_unique(array_filter(array_column($rows, 'structure_id')));
        $itemsByStructure = [];
        if (!empty($structureIds)) {
            $items = $this->db
                ->where_in('structure_id', $structureIds)
                ->get($this->structureItemsTable)
                ->result_array();

            foreach ($items as $item) {
                $sid = (int)($item['structure_id'] ?? 0);
                if (!$sid) {
                    continue;
                }
                if (!isset($itemsByStructure[$sid])) {
                    $itemsByStructure[$sid] = [];
                }
                $itemsByStructure[$sid][] = $item;
            }
        }

        foreach ($rows as &$row) {
            $structureId = (int)($row['structure_id'] ?? 0);
            $row['base_salary'] = (float)($row['base_salary'] ?? 0);
            $row['structure_items'] = $itemsByStructure[$structureId] ?? [];
        }
        unset($row);

        return $rows;
    }

    /**
     * Persist payroll run summary + line items
     *
     * @param string $month
     * @param int|null $companyId
     * @param int $generatedBy
     * @param array $summaries
     * @return int|false
     */
    public function create_payroll_run($month, $companyId, $generatedBy, $summaries)
    {
        if (!$month || empty($summaries)) {
            return false;
        }

        $totalGross = 0;
        $totalDeductions = 0;
        $totalNet = 0;

        foreach ($summaries as $summary) {
            $totalGross += (float)($summary['gross'] ?? 0);
            $totalDeductions += (float)($summary['deductions'] ?? 0);
            $totalNet += (float)($summary['net'] ?? 0);
        }

        $runData = [
            'company_id'       => $companyId,
            'payroll_month'    => $month
        ];
        log_message('error', 'Display data - ' . print_r($runData, true));
        $this->db->trans_start();
        $this->db->insert($this->runsTable, $runData);
        $runId = (int)$this->db->insert_id();

        if ($runId <= 0) {
            $this->db->trans_complete();
            return false;
        }

        foreach ($summaries as $summary) {
            $itemData = [
                'run_id'           => $runId,
                'staffid'          => (int)($summary['staffid'] ?? 0),
                'gross_amount'     => (float)($summary['gross'] ?? 0),
                'deduction_amount' => (float)($summary['deductions'] ?? 0),
                'net_amount'       => (float)($summary['net'] ?? 0),
                'details'   => json_encode($summary['details'] ?? []),
            ];
            $this->db->insert($this->runItemsTable, $itemData);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }

        return $runId;
    }

    /**
     * Fetch distinct payroll months that have generated salary slips
     *
     * @param int|null $companyId
     * @return array
     */
    public function get_payroll_run_months($companyId = null)
    {
        $this->db->select('payroll_month');
        $this->db->from($this->runsTable);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('payroll_month IS NOT NULL');
        $this->db->group_by('payroll_month');
        $this->db->order_by('payroll_month', 'desc');

        return array_column($this->db->get()->result_array(), 'payroll_month');
    }

    /**
     * Fetch generated salary slips for a given month.
     *
     * @param int|null $companyId
     * @param string|null $month
     * @return array
     */
    public function get_generated_slips($companyId = null, $month = null)
    {
        $this->db->select([
            'pi.id',
            'pi.run_id',
            'pi.staffid',
            'pi.gross_amount',
            'pi.deduction_amount',
            'pi.net_amount',
            'pi.details',
            'pr.payroll_month',
            'pr.company_id',
            'pr.created_at',
            's.firstname',
            's.lastname',
            's.employee_code',
            's.email',
            's.department_id',
            's.designation_id',
            's.branch',
            'd.name AS department',
            'des.title AS designation',
            'br.branch_name AS branch_name',
        ]);
        $this->db->from($this->runItemsTable . ' pi');
        $this->db->join($this->runsTable . ' pr', 'pr.id = pi.run_id', 'inner');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = pi.staffid', 'left');
        $this->db->join(db_prefix() . 'departments d', 'd.departmentid = s.department_id', 'left');
        $this->db->join(db_prefix() . 'designations des', 'des.id = s.designation_id', 'left');
        $this->db->join(db_prefix() . 'hrd_branch_manager br', 'br.id = s.branch', 'left');

        if ($companyId) {
            $this->db->where('pr.company_id', $companyId);
        }

        if ($month) {
            $this->db->where('pr.payroll_month', $month);
        }

        $this->db->order_by('s.firstname', 'asc');

        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row['details'] = json_decode($row['details'] ?? '[]', true) ?: [];
        }
        unset($row);

        return $rows;
    }

    /**
     * Fetch a single salary slip for staff & month
     */
    public function get_staff_salary_slip($staffId, $month, $companyId = null)
    {
        if (!$staffId || !$month) {
            return null;
        }

        $this->db->select([
            'pi.*',
            'pr.payroll_month',
            'pr.company_id',
            'pr.created_at AS run_created_at',
            's.firstname',
            's.lastname',
            's.employee_code',
            's.email',
            's.phonenumber',
            's.date_of_birth',
            's.joining_date',
            's.branch',
            's.department_id',
            's.designation_id',
            'd.name AS department',
            'des.title AS designation',
            'br.branch_name AS branch_name',
        ]);
        $this->db->from($this->runItemsTable . ' pi');
        $this->db->join($this->runsTable . ' pr', 'pr.id = pi.run_id', 'inner');
        $this->db->join(db_prefix() . 'staff s', 's.staffid = pi.staffid', 'left');
        $this->db->join(db_prefix() . 'departments d', 'd.departmentid = s.department_id', 'left');
        $this->db->join(db_prefix() . 'designations des', 'des.id = s.designation_id', 'left');
        $this->db->join(db_prefix() . 'hrd_branch_manager br', 'br.id = s.branch', 'left');

        $this->db->where('pi.staffid', $staffId);
        $this->db->where('pr.payroll_month', $month);
        if ($companyId) {
            $this->db->where('pr.company_id', $companyId);
        }

        $row = $this->db->get()->row_array();
        if (!$row) {
            return null;
        }

        $row['details'] = json_decode($row['details'] ?? '[]', true) ?: [];
        return $row;
    }
}
