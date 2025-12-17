<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .ctc-slip-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 24px;
    background: #fff;
    box-shadow: 0 6px 18px rgba(0,0,0,0.05);
  }
  .ctc-section-title {
    font-weight: 600;
    margin-bottom: 12px;
    text-transform: uppercase;
    color: #555;
    letter-spacing: .05em;
  }
  .ctc-table th {
    background: #f5f7fb;
    border-bottom: 1px solid #e7ebf3;
    color: #555;
    font-size: 13px;
    text-transform: uppercase;
  }
  .ctc-table td {
    vertical-align: middle !important;
  }
  .summary-pill {
    padding: 12px 18px;
    border-radius: 8px;
    background: #f5f7fb;
    text-align: center;
  }
  .summary-pill strong {
    display: block;
    font-size: 18px;
    color: #111827;
  }
  @media print {
    body * {
      visibility: hidden !important;
    }
    #wrapper, #wrapper * {
      visibility: visible !important;
    }
    #wrapper {
      position: absolute !important;
      left: 0;
      top: 0;
      width: 100%;
      margin: 0 !important;
    }
    #side-menu,
    .mobile-menu-toggle,
    .admin #side-menu {
      display: none !important;
    }
	#print-button {
      display: none !important;
    }
	a[href]:after {
        content: none !important;
    }
  }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
	  <div class="text-right tw-mb-2" id="print-button">
		<a href="javascript:window.print()" class="btn btn-default btn-sm" title="Print Salary Slip"><i class="fa-solid fa-print"></i></a>
        </div>
        <div class="ctc-slip-card">
          <div class="tw-flex tw-flex-col sm:tw-flex-row tw-justify-between tw-gap-4 tw-mb-6">
            <div>
              <h3 class="tw-m-0 tw-text-lg tw-font-semibold">Cost to Company (CTC)</h3>
              <p class="tw-mt-1 tw-text-sm tw-text-neutral-500">Generated on <?php echo _dt(date('Y-m-d H:i:s')); ?></p>
            </div>
            <div class="summary-pill">
              <span>Total Annual CTC</span>
              <strong><?php echo app_format_money(($structure['base_salary'] ?? 0) * 12, get_base_currency()); ?></strong>
            </div>
          </div>

<?php
$baseSalary = (float)($structure['base_salary'] ?? 0);
$items = $structure['items'] ?? [];
$componentMap = $components ?? [];

$resolvedAmounts = [];
$preparedItems = $items;
$maxIterations = count($preparedItems) + 2;

for ($pass = 0; $pass < $maxIterations; $pass++) {
    $changes = false;
    foreach ($preparedItems as &$item) {
        if (isset($item['_computed'])) {
            continue;
        }
        $calcType = $item['calc_type'] ?? 'fixed';
        if ($calcType === 'percent') {
            $ref = (int)($item['percent_of_component'] ?? 0);
            if ($ref && !isset($resolvedAmounts[$ref])) {
                continue;
            }
            $basis = $ref ? ($resolvedAmounts[$ref] ?? 0) : $baseSalary;
            $item['_computed'] = round($basis * ((float)$item['amount'] / 100), 2);
            $resolvedAmounts[$item['component_id']] = $item['_computed'];
            $changes = true;
        } else {
            $item['_computed'] = (float)$item['amount'];
            $resolvedAmounts[$item['component_id']] = $item['_computed'];
            $changes = true;
        }
    }
    unset($item);
    if (!$changes) {
        break;
    }
}

$earnings = [];
$deductions = [];

/*$earnings[] = [
    'label' => 'Base Salary',
    'calc' => 'Fixed',
    'reference' => '-',
    'amount' => $baseSalary,
];*/

foreach ($preparedItems as $item) {
    $componentId = $item['component_id'];
    $component = $componentMap[$componentId] ?? [];
    $label = $component['title'] ?? $component['name'] ?? ('Component #' . $componentId);
    $calcType = $item['calc_type'] ?? 'fixed';
    $calcDesc = $calcType === 'percent'
        ? number_format((float)$item['amount'], 2) . '% of ' . ($componentMap[$item['percent_of_component']] ['title'] ?? 'Base Salary')
        : 'Fixed amount';
    $actualAmount = isset($item['_computed']) ? $item['_computed'] : (float)$item['amount'];

    $row = [
        'label' => $label,
        'calc' => ucfirst($calcType),
        'reference' => $calcDesc,
        'amount' => $actualAmount,
    ];

    if (($component['type'] ?? 'earning') === 'deduction') {
        $deductions[] = $row;
    } else {
        $earnings[] = $row;
    }
}

$totalEarnings = array_sum(array_column($earnings, 'amount'));
$totalDeductions = array_sum(array_column($deductions, 'amount'));
$netPay = $totalEarnings - $totalDeductions;
?>

          <div class="tw-grid sm:tw-grid-cols-2 tw-gap-6">
            <div>
              <div class="ctc-section-title">Employee Information</div>
              <div class="table-responsive">
                <table class="table table-sm">
                  <tbody>
                    <tr><td>Name</td><td><?php echo e(($staff['firstname'] ?? '') . ' ' . ($staff['lastname'] ?? '')); ?></td></tr>
                    <tr><td>Employee Code</td><td><?php echo e($staff['employee_code'] ?? '-'); ?></td></tr>
                    <tr><td>Email</td><td><?php echo e($staff['email'] ?? '-'); ?></td></tr>
                    <tr><td>Joining Date</td><td><?php echo _d($staff['joining_date'] ?? ''); ?></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div>
              <div class="ctc-section-title">Company Information</div>
              <div class="table-responsive">
                <table class="table table-sm">
                  <tbody>
                    <tr><td>Branch</td><td><?php echo e(get_staff_branch_name($staff['branch']) ?? '-'); ?></td></tr>
                    <tr><td>Department</td><td><?php echo e(get_staff_department_name($staff['department_id']) ?? '-'); ?></td></tr>
                    <tr><td>Designation</td><td><?php echo e(get_staff_designations_name($staff['designation_id']) ?? '-'); ?></td></tr>
                    <tr><td>Reporting Manager</td><td><?php echo e(get_staff_full_name($staff['reporting_manager']) ?? '-'); ?></td></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="tw-grid sm:tw-grid-cols-2 tw-gap-6 tw-mt-6">
            <div>
              <div class="ctc-section-title">Earnings</div>
              <div class="table-responsive">
                <table class="table table-bordered ctc-table">
                  <thead>
                    <tr>
                      <th>Component</th>
                      <th class="text-right">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($earnings as $earning) { ?>
                      <tr>
                        <td><?php echo e($earning['label']); ?></td>
                        <td class="text-right"><?php echo $earning['amount']; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th class="text-right">Total Earnings</th>
                      <th class="text-right"><?php echo number_format(($totalEarnings ?? 0), 2, '.', ''); ?></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <div>
              <div class="ctc-section-title">Deductions</div>
              <div class="table-responsive">
                <table class="table table-bordered ctc-table">
                  <thead>
                    <tr>
                      <th>Component</th>
                      <th class="text-right">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($deductions)) { foreach ($deductions as $deduction) { ?>
                      <tr>
                        <td><?php echo e($deduction['label']); ?></td>
                        <td class="text-right"><?php echo $deduction['amount']; ?></td>
                      </tr>
                    <?php } } else { ?>
                      <tr>
                        <td colspan="2" class="text-center text-muted">No deductions configured</td>
                      </tr>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th  class="text-right">Total Deductions</th>
                      <th class="text-right"><?php echo number_format(($totalDeductions ?? 0), 2, '.', ''); ?></th>
                    </tr>
					<tr>
                      <th  class="text-right">Net CTC</th>
                      <th class="text-right"><?php echo number_format(($netPay ?? 0), 2, '.', ''); ?></th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>

          <p class="tw-mt-6 tw-text-xs tw-text-neutral-500">* This is a system generated slip and does not require signature!!.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>
