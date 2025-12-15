<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #111827;
    }
    .salary-slip-wrapper {
      padding: 20px;
    }
    h2 {
      margin: 0 0 10px;
      font-size: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 6px 8px;
      border: 1px solid #e5e7eb;
    }
    th {
      background: #f3f4f6;
      text-transform: uppercase;
      font-size: 11px;
    }
    .text-right {
      text-align: right;
    }
    .text-center {
      text-align: center;
    }
    .meta-table th, .meta-table td {
      border: none;
      padding: 2px 0;
    }
    .totals-row td {
      font-weight: bold;
    }
	.logoadmin {
    height: 40px !important;
    }
    a[href]:after {
    content: none !important;
    }
  </style>
</head>
<body>
  <div class="salary-slip-wrapper">
    <table class="meta-table">
      <tr>
        <td class="logoadmin"><div><?php get_company_logo(); ?></div></td>
		<td class="text-left"><strong><?php echo e(get_staff_company_name()); ?></strong></td>
        <td class="text-right">
          <strong>Salary Slip</strong><br>
          <?php echo html_escape(date('F Y', strtotime($month . '-01'))); ?>
        </td>
      </tr>
    </table>

    <h2>Employee Details</h2>
    <table>
      <tr>
        <th>Employee Name</th>
        <td><?php echo html_escape(trim(($slip['firstname'] ?? '') . ' ' . ($slip['lastname'] ?? '')) ?: 'N/A'); ?></td>
        <th>Employee Code</th>
        <td><?php echo html_escape($slip['employee_code'] ?? '-'); ?></td>
      </tr>
      <tr>
        <th>Department</th>
        <td><?php echo html_escape($slip['department'] ?? '-'); ?></td>
        <th>Designation</th>
        <td><?php echo html_escape($slip['designation'] ?? '-'); ?></td>
      </tr>
      <tr>
        <th>Branch</th>
        <td><?php echo html_escape($slip['branch_name'] ?? '-'); ?></td>
        <th>Joining Date</th>
        <td><?php echo $slip['joining_date'] ? _d($slip['joining_date']) : '-'; ?></td>
      </tr>
    </table>

    <h2>Earnings & Deductions</h2>
    <table>
      <thead>
        <tr>
          <th class="text-center">Earnings</th>
          <th class="text-right">Amount</th>
          <th class="text-center">Deductions</th>
          <th class="text-right">Amount</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <table class="meta-table" style="width:100%;">
              <?php if (!empty($earnings)) { ?>
                <?php foreach ($earnings as $earning) { ?>
                  <tr>
                    <td><?php echo html_escape($earning['label'] ?? '-'); ?></td>
                    <td class="text-right"><?php echo number_format(($earning['amount'] ?? 0), 2, '.', ''); ?></td>
                  </tr>
                <?php } ?>
              <?php } else { ?>
                <tr><td colspan="2" class="text-center">No earnings</td></tr>
              <?php } ?>
            </table>
          </td>
          <td></td>
          <td>
            <table class="meta-table" style="width:100%;">
              <?php if (!empty($deductions)) { ?>
                <?php foreach ($deductions as $deduction) { ?>
                  <tr>
                    <td><?php echo html_escape($deduction['label'] ?? '-'); ?></td>
                    <td class="text-right"><?php echo number_format(($deduction['amount'] ?? 0), 2, '.', ''); ?></td>
                  </tr>
                <?php } ?>
              <?php } else { ?>
                <tr><td colspan="2" class="text-center">No deductions</td></tr>
              <?php } ?>
            </table>
          </td>
          <td></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="totals-row">
          <td class="text-right">Gross Total</td>
          <td class="text-right"><?php echo number_format(($slip['gross_amount'] ?? 0), 2, '.', ''); ?></td>
          <td class="text-right">Total Deductions</td>
          <td class="text-right"><?php echo number_format(($slip['deduction_amount'] ?? 0), 2, '.', ''); ?></td>
        </tr>
        <tr class="totals-row">
          <td class="text-right" colspan="2">Net Pay</td>
          <td class="text-right" colspan="2"><?php echo number_format(($slip['net_amount'] ?? 0), 2, '.', ''); ?></td>
        </tr>
        <tr>
          <td colspan="4">Net Pay (in words): <?php echo amountInWords(number_format(($slip['net_amount'] ?? 0), 2, '.', '')); ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</body>
</html>
