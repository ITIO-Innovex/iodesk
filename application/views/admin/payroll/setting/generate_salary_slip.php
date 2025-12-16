<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .salary-slip-card {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 20px;
    background: #fff;
  }
  .salary-slip-table thead th {
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: .05em;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
  }
  .payroll-summary-pill {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 12px 18px;
    text-align: center;
    background: #f9fafb;
  }
  .payroll-summary-pill span {
    display: block;
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
  }
  .payroll-summary-pill strong {
    display: block;
    font-size: 18px;
    color: #111827;
    margin-top: 4px;
  }
  .breakdown-row {
    background: #f9fafb;
  }
  .breakdown-row table {
    margin-bottom: 0;
  }
  .no-staff-state {
    padding: 60px 20px;
    text-align: center;
    color: #6b7280;
  }
  .table .checkbox {
     padding-left: 20px;
}
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-start tw-justify-between tw-gap-4 tw-mb-4">
              <div>
                <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-800">Generate Salary Slip</h4>
                <p class="tw-mt-1 tw-text-sm tw-text-neutral-500">Select a payroll month, pick the employees to include, and generate their salary slips.</p>
              </div>
              <?php echo form_open(admin_url('payroll/setting/generate_salary_slip'), ['method' => 'GET', 'class' => 'tw-flex tw-items-center tw-gap-2', 'id' => 'salary-month-form']); ?>
                <div>
                  <label class="control-label tw-text-xs tw-text-neutral-500 tw-uppercase">Payroll Month</label>
                  <input type="month" name="month" value="<?php echo html_escape($selected_month); ?>" class="form-control" required />
                </div>
                <div class="tw-pt-3">
                  <button type="submit" class="btn btn-default">Apply</button>
                </div>
              <?php echo form_close(); ?>
            </div>

            <div class="tw-flex tw-flex-col lg:tw-flex-row tw-gap-3 tw-items-end tw-mb-3">
              <div class="tw-flex-1">
                <label class="control-label tw-text-xs tw-text-neutral-500 tw-uppercase">Search Staff</label>
                <input type="text" id="salary-staff-search" class="form-control" placeholder="Type name, employee code, email, branch or department" />
              </div>
              <div>
                <button type="button" class="btn btn-primary" id="run-payroll-btn" disabled>
                  <i class="fa-regular fa-paper-plane"></i> Generate Salary Slips
                </button>
              </div>
            </div>

            <?php if (empty($staff_rows)) { ?>
              <div class="no-staff-state">
                <i class="fa-regular fa-circle-question fa-2x"></i>
                <p class="tw-mt-3 tw-text-base">No staff members with payroll structures were found. Configure CTC for staff from the Payroll CTC page first.</p>
              </div>
            <?php } else { ?>
              <div class="table-responsive salary-slip-card">
                <table class="table table-bordered table-striped salary-slip-table" id="salary-slip-table">
                  <thead>
                    <tr>
                      <th style="width:45px;">
                        <div class="checkbox">
                          <input type="checkbox" id="select-all-staff" />
                          <label for="select-all-staff"></label>
                        </div>
                      </th>
                      <th>Staff</th>
                      <th>Org Info</th>
                      <th class="text-right">Base Salary</th>
                      <th class="text-right">Gross Earnings</th>
                      <th class="text-right">Deductions</th>
                      <th class="text-right">Net Pay</th>
                      <th style="width:120px;">Breakdown</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($staff_rows as $staff) {
                      $staffId   = (int)($staff['staffid'] ?? 0);
                      $fullName  = trim(($staff['firstname'] ?? '') . ' ' . ($staff['lastname'] ?? '')) ?: 'Unnamed Staff';
                      $employeeCode = $staff['employee_code'] ?? '';
                      $email     = $staff['email'] ?? '';
                      $branch    = $staff['branch_name'] ?? '-';
                      $department= $staff['department'] ?? '-';
                      $designation = $staff['designation'] ?? '-';
                      $baseSalary = (float)($staff['base_salary'] ?? 0);
                      $summary    = $staff['payroll_summary'] ?? [];
                      $gross      = (float)($summary['gross'] ?? 0);
                      $deductions = (float)($summary['deductions'] ?? 0);
                      $net        = (float)($summary['net'] ?? 0);
                      $earnings   = $summary['details']['earnings'] ?? [];
                      $deductionRows = $summary['details']['deductions'] ?? [];
                      $tokens = strtolower($fullName . ' ' . $employeeCode . ' ' . $email . ' ' . $branch . ' ' . $department . ' ' . $designation);
                    ?>
                      <tr class="data-row" data-filter-tokens="<?php echo html_escape($tokens); ?>">
                        <td>
                          <div class="checkbox">
                            <input type="checkbox" class="staff-payroll-checkbox" id="staff_<?php echo $staffId; ?>" value="<?php echo $staffId; ?>" />
                            <label for="staff_<?php echo $staffId; ?>"></label>
                          </div>
                        </td>
                        <td>
                          <strong><?php echo html_escape($fullName); ?></strong><br />
                          <small class="text-muted">Code: <?php echo html_escape($employeeCode ?: '-'); ?> | <?php echo html_escape($email ?: 'No email'); ?></small>
                        </td>
                        <td>
                          <div><strong>Branch:</strong> <?php echo html_escape($branch); ?></div>
                          <div><strong>Dept:</strong> <?php echo html_escape($department); ?></div>
                          <div><strong>Desig:</strong> <?php echo html_escape($designation); ?></div>
                        </td>
                        <td class="text-right"><?php echo app_format_money($baseSalary, get_base_currency()); ?></td>
                        <td class="text-right"><?php echo app_format_money($gross, get_base_currency()); ?></td>
                        <td class="text-right"><?php echo app_format_money($deductions, get_base_currency()); ?></td>
                        <td class="text-right"><strong><?php echo app_format_money($net, get_base_currency()); ?></strong></td>
                        <td>
                          <button type="button" class="btn btn-default btn-xs toggle-breakdown" data-target="#breakdown_<?php echo $staffId; ?>">
                            <i class="fa-regular fa-eye"></i> View
                          </button>
                        </td>
                      </tr>
                      <tr id="breakdown_<?php echo $staffId; ?>" class="breakdown-row" style="display:none;">
                        <td></td>
                        <td colspan="7">
                          <div class="row">
                            <div class="col-sm-6">
                              <h5 class="tw-mt-0">Earnings</h5>
                              <table class="table table-sm">
                                <thead>
                                  <tr>
                                    <th>Component</th>
                                    <th class="text-right">Amount</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php if (!empty($earnings)) { foreach ($earnings as $earning) { ?>
                                    <tr>
                                      <td>
                                        <strong><?php echo html_escape($earning['label'] ?? '-'); ?></strong><br />
                                        <small class="text-muted"><?php echo html_escape($earning['reference'] ?? ''); ?></small>
                                      </td>
                                      <td class="text-right"><?php echo app_format_money((float)($earning['amount'] ?? 0), get_base_currency()); ?></td>
                                    </tr>
                                  <?php } } else { ?>
                                    <tr><td colspan="2" class="text-muted text-center">No earnings configured</td></tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                            <div class="col-sm-6">
                              <h5 class="tw-mt-0">Deductions</h5>
                              <table class="table table-sm">
                                <thead>
                                  <tr>
                                    <th>Component</th>
                                    <th class="text-right">Amount</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php if (!empty($deductionRows)) { foreach ($deductionRows as $deduction) { ?>
                                    <tr>
                                      <td>
                                        <strong><?php echo html_escape($deduction['label'] ?? '-'); ?></strong><br />
                                        <small class="text-muted"><?php echo html_escape($deduction['reference'] ?? ''); ?></small>
                                      </td>
                                      <td class="text-right"><?php echo app_format_money((float)($deduction['amount'] ?? 0), get_base_currency()); ?></td>
                                    </tr>
                                  <?php } } else { ?>
                                    <tr><td colspan="2" class="text-muted text-center">No deductions configured</td></tr>
                                  <?php } ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
(function(){
  var $table = $('#salary-slip-table');
  var $search = $('#salary-staff-search');
  var $selectAll = $('#select-all-staff');
  var $checkboxes = $('.staff-payroll-checkbox');
  var $runBtn = $('#run-payroll-btn');
  var $monthInput = $('input[name="month"]');

  function updateButtonState() {
    var selectedCount = $('.staff-payroll-checkbox:checked').length;
    var month = ($monthInput.val() || '').trim();
    $runBtn.prop('disabled', selectedCount === 0 || !month);
    if (selectedCount > 0) {
      $runBtn.html('<i class="fa-regular fa-paper-plane"></i> Generate Salary Slips (' + selectedCount + ')');
    } else {
      $runBtn.html('<i class="fa-regular fa-paper-plane"></i> Generate Salary Slips');
    }
  }

  $selectAll.on('change', function(){
    var checked = $(this).is(':checked');
    $checkboxes.prop('checked', checked);
    updateButtonState();
  });

  $table.on('change', '.staff-payroll-checkbox', function(){
    if (!$(this).is(':checked')) {
      $selectAll.prop('checked', false);
    } else if ($('.staff-payroll-checkbox:not(:checked)').length === 0) {
      $selectAll.prop('checked', true);
    }
    updateButtonState();
  });

  $search.on('input', function(){
    var term = $(this).val().toLowerCase();
    $table.find('tbody tr.data-row').each(function(){
      var tokens = ($(this).data('filter-tokens') || '').toString();
      var match = !term || tokens.indexOf(term) !== -1;
      $(this).toggle(match);
      var target = $(this).next('.breakdown-row');
      if (target.length) {
        if (!match) { target.hide(); }
      }
    });
  });

  $table.on('click', '.toggle-breakdown', function(){
    var target = $($(this).data('target'));
    if (!target.length) { return; }
    target.slideToggle(150);
  });

  $runBtn.on('click', function(){
    var month = ($monthInput.val() || '').trim();
    if (!month) {
      alert_float('warning', 'Please select a payroll month first.');
      return;
    }

    var selected = [];
    $('.staff-payroll-checkbox:checked').each(function(){
      selected.push($(this).val());
    });

    if (!selected.length) {
      alert_float('warning', 'Select at least one staff to continue.');
      return;
    }

    var originalHtml = $runBtn.html();
    $runBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating...');

    $.ajax({
      url: admin_url + 'payroll/run_payroll',
      method: 'POST',
      data: { month: month, staff_ids: selected },
      dataType: 'json'
    }).done(function(resp){
      if (resp && resp.success) {
        alert_float('success', resp.message || 'Salary slips generated successfully.');
        setTimeout(function(){ window.location.reload(); }, 1200);
      } else {
        alert_float('danger', (resp && resp.message) ? resp.message : 'Unable to generate salary slips.');
      }
    }).fail(function(){
      alert_float('danger', 'Server error while generating salary slips.');
    }).always(function(){
      $runBtn.prop('disabled', false).html(originalHtml);
      updateButtonState();
    });
  });
})();
</script>
</body></html>
