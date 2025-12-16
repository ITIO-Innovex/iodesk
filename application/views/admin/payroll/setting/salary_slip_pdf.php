<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
<style>

table {
    width: 100%;
	border-collapse: collapse; /* Merges borders into a single border */
  	border-radius: 25px; /* Applies rounded corners to the table container */
}

th, td {
    border: 1px solid #ddd;
    padding: 10px;
    font-size: 11px;
    line-height: 40px;     /* 👈 THIS controls height */
    vertical-align: middle;
}

th {
    /*background-color: #f2f2f2;
    font-weight: bold;
    text-transform: uppercase;*/
}

.meta-table th,
.meta-table td {
    border: none;
    padding: 4px;
    line-height: 30px;
}

.totals-row td, td.totals-row {
    font-weight: bold;
    background-color: #f5f5f5;
}

tr {
    /*page-break-inside: avoid;*/
}

.text-right { text-align: right; }
.text-center { text-align: center; }

/* Image sizing handled via HTML attributes */
.logoadmin {
    height: 40px;
}

/* Remove printed link URLs */
a[href]:after {
    content: '';
}
.h2, h2 {
    font-size: 18px;
	color:#3c766c;
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
    <table id="emp">
      <tr>
        <td class="totals-row">Employee Name</td>
        <td><?php echo html_escape(trim(($slip['firstname'] ?? '') . ' ' . ($slip['lastname'] ?? '')) ?: 'N/A'); ?></td>
        <td class="totals-row">Employee Code</td>
        <td><?php echo html_escape($slip['employee_code'] ?? '-'); ?></td>
      </tr>
      <tr>
        <td class="totals-row">Department</td>
        <td><?php echo html_escape($slip['department'] ?? '-'); ?></td>
        <td class="totals-row">Designation</td>
        <td><?php echo html_escape($slip['designation'] ?? '-'); ?></td>
      </tr>
      <tr>
        <td class="totals-row">Branch</td>
        <td><?php echo html_escape($slip['branch_name'] ?? '-'); ?></td>
        <td class="totals-row">Joining Date</td>
        <td><?php echo $slip['joining_date'] ? date("d F Y",strtotime($slip['joining_date'])) : '-'; ?></td>
      </tr>
    </table>

    <h2>Earnings & Deductions</h2>
    <table>
      <thead>
        <tr class="totals-row">
          <td class="text-center">Earnings</td>
          <td class="text-right">Amount</td>
          <td class="text-center">Deductions</td>
          <td class="text-right">Amount</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="2">
            <table class="meta-table" style="width:100%;">
              <?php if (!empty($earnings)) { ?>
                <?php foreach ($earnings as $earning) { ?>
                  <tr>
                    <td><div style="padding:10px; background:#CCFFCC;"><?php echo html_escape($earning['label'] ?? '-'); ?></div></td>
                    <td class="text-right"><?php echo number_format(($earning['amount'] ?? 0), 2, '.', ''); ?></td>
                  </tr>
                <?php } ?>
              <?php } else { ?>
                <tr><td colspan="2" class="text-center">No earnings</td></tr>
              <?php } ?>
            </table>
          </td>
          
          <td colspan="2">
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
        <tr class="totals-row">
          <td colspan="4">Net Pay (in words): <?php echo amountInWords(number_format(($slip['net_amount'] ?? 0), 2, '.', '')); ?></td>
        </tr>
      </tfoot>
    </table>
  </div>
</body>
</html>
