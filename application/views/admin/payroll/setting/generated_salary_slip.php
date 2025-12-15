<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .generated-slip-card {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    background: #fff;
    padding: 20px;
  }
  .generated-slip-card .table thead th {
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: .06em;
    background: #f9fafb;
  }
  .summary-chip {
    border-radius: 10px;
    background: linear-gradient(135deg, #f1f5f9, #fff);
    padding: 18px 22px;
  }
  .summary-chip span {
    display: block;
    font-size: 12px;
    color: #6b7280;
    text-transform: uppercase;
  }
  .summary-chip strong {
    display: block;
    font-size: 22px;
    color: #111827;
  }
  .no-months-state {
    padding: 60px 20px;
    text-align: center;
    color: #6b7280;
  }
  .slip-breakdown-row {
    background: #f9fafb;
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
                <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-800">Generated Salary Slips</h4>
                <p class="tw-mt-1 tw-text-sm tw-text-neutral-500">Review previously generated payroll runs and drill into earning/deduction breakdowns.</p>
              </div>
              <?php echo form_open(admin_url('payroll/setting/generated_salary_slip'), ['method' => 'GET', 'class' => 'tw-flex tw-items-end tw-gap-3']); ?>
                <div>
                  <label class="control-label tw-text-xs tw-text-neutral-500 tw-uppercase">Payroll Month</label>
                  <select name="month" class="form-control" <?php echo empty($available_months) ? 'disabled' : ''; ?>>
                    <?php if (!empty($available_months)) { ?>
                      <?php foreach ($available_months as $monthOption) { ?>
                        <option value="<?php echo html_escape($monthOption); ?>" <?php echo ($monthOption === $selected_month) ? 'selected' : ''; ?>>
                          <?php echo html_escape(date('F Y', strtotime($monthOption . '-01'))); ?>
                        </option>
                      <?php } ?>
                    <?php } else { ?>
                      <option value="">No payroll months</option>
                    <?php } ?>
                  </select>
                </div>
                <div>
                  <button type="submit" class="btn btn-default" <?php echo empty($available_months) ? 'disabled' : ''; ?>>
                    <i class="fa-solid fa-magnifying-glass"></i> Search
                  </button>
                </div>
              <?php echo form_close(); ?>
            </div>

            <?php if (empty($available_months)) { ?>
              <div class="no-months-state">
                <i class="fa-regular fa-calendar-xmark fa-2x"></i>
                <p class="tw-mt-3 tw-text-base">No payroll runs are available yet. Generate salary slips first to review them here.</p>
              </div>
            <?php } else { ?>
              <div class="tw-grid tw-grid-cols-1 md:tw-grid-cols-4 tw-gap-3 tw-mb-4">
			  <div class="row">
                <div class="summary-chip col-sm-3 mail-bg">
                  <span>Selected Month</span>
                  <strong><?php echo $selected_month ? html_escape(date('F Y', strtotime($selected_month . '-01'))) : '—'; ?></strong>
                </div>
                <div class="summary-chip col-sm-3 mail-bg">
                  <span>Staff Included</span>
                  <strong><?php echo (int)($summary_totals['staff_count'] ?? 0); ?></strong>
                </div>
                <div class="summary-chip col-sm-3 mail-bg">
                  <span>Total Gross</span>
                  <strong><?php echo app_format_money((float)($summary_totals['gross'] ?? 0), get_base_currency()); ?></strong>
                </div>
                <div class="summary-chip col-sm-3 mail-bg">
                  <span>Net Pay</span>
                  <strong><?php echo app_format_money((float)($summary_totals['net'] ?? 0), get_base_currency()); ?></strong>
                </div>
			  </div>
              </div>

              <div class="tw-flex tw-flex-col lg:tw-flex-row tw-items-end tw-gap-3 tw-mb-3">
                <div class="tw-flex-1">
                  <label class="control-label tw-text-xs tw-text-neutral-500 tw-uppercase">Search Staff</label>
                  <input type="text" class="form-control" id="generated-slip-search" placeholder="Type staff name, code, email, branch, or department" />
                </div>
                <div>
                  <span class="tw-text-xs tw-text-neutral-400 tw-uppercase">Showing <?php echo count($slip_rows); ?> records</span>
                </div>
              </div>

              <?php if (empty($slip_rows)) { ?>
                <div class="no-months-state">
                  <i class="fa-regular fa-circle-info fa-2x"></i>
                  <p class="tw-mt-3 tw-text-base">No salary slips were generated for this month.</p>
                </div>
              <?php } else { ?>
                <div class="table-responsive generated-slip-card">
                  <table class="table table-striped table-bordered" id="generated-slip-table">
                    <thead>
                      <tr>
                        <th>Staff</th>
                        <th>Org Info</th>
                        <th class="text-right">Gross</th>
                        <th class="text-right">Deductions</th>
                        <th class="text-right">Net Pay</th>
                        <th style="width:120px;">Breakdown</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($slip_rows as $row) {
                        $staffId = (int) ($row['staffid'] ?? 0);
                        $fullName = trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '')) ?: 'Unnamed Staff';
                        $department = $row['department'] ?? '-';
                        $designation = $row['designation'] ?? '-';
                        $branch = $row['branch_name'] ?? '-';
                        $employeeCode = $row['employee_code'] ?? '-';
                        $email = $row['email'] ?? '-';
                        $gross = (float) ($row['gross_amount'] ?? 0);
                        $deductions = (float) ($row['deduction_amount'] ?? 0);
                        $net = (float) ($row['net_amount'] ?? 0);
                        $details = $row['details'] ?? ['earnings' => [], 'deductions' => []];
                        $earnings = $details['earnings'] ?? [];
                        $deductionRows = $details['deductions'] ?? [];
                        $filterTokens = strtolower($fullName . ' ' . $employeeCode . ' ' . $email . ' ' . $department . ' ' . $designation . ' ' . $branch);
                      ?>
                        <tr class="data-row" data-filter-tokens="<?php echo html_escape($filterTokens); ?>">
                          <td>
                            <strong><?php echo html_escape($fullName); ?></strong><br>
                            <small class="text-muted">Code: <?php echo html_escape($employeeCode); ?> | <?php echo html_escape($email); ?></small>
                          </td>
                          <td>
                            <div><strong>Branch:</strong> <?php echo html_escape($branch); ?></div>
                            <div><strong>Dept:</strong> <?php echo html_escape($department); ?></div>
                            <div><strong>Desig:</strong> <?php echo html_escape($designation); ?></div>
                          </td>
                          <td class="text-right"><?php echo app_format_money($gross, get_base_currency()); ?></td>
                          <td class="text-right"><?php echo app_format_money($deductions, get_base_currency()); ?></td>
                          <td class="text-right"><strong><?php echo app_format_money($net, get_base_currency()); ?></strong></td>
                          <td>
                            <button type="button" class="btn btn-default btn-xs toggle-slip-breakdown" data-target="#slip_breakdown_<?php echo $staffId; ?>" title="View Details"><i class="fa-regular fa-eye"></i></button>
							<a  class="btn btn-danger btn-xs" href="<?php echo admin_url('payroll/setting/salary_slip');?>?id=<?php echo $staffId; ?>&month=<?php echo $selected_month; ?>" target="_blank" title="View Salary Slip"><i class="fa-solid fa-file-lines"></i></a>
                            
                          </td>
                        </tr>
                        <tr id="slip_breakdown_<?php echo $staffId; ?>" class="slip-breakdown-row" style="display:none;">
                          <td colspan="6">
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
                                          <strong><?php echo html_escape($earning['label'] ?? '-'); ?></strong><br>
                                          <small class="text-muted"><?php echo html_escape($earning['reference'] ?? ''); ?></small>
                                        </td>
                                        <td class="text-right"><?php echo app_format_money((float)($earning['amount'] ?? 0), get_base_currency()); ?></td>
                                      </tr>
                                    <?php } } else { ?>
                                      <tr><td colspan="2" class="text-muted text-center">No earnings recorded</td></tr>
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
                                          <strong><?php echo html_escape($deduction['label'] ?? '-'); ?></strong><br>
                                          <small class="text-muted"><?php echo html_escape($deduction['reference'] ?? ''); ?></small>
                                        </td>
                                        <td class="text-right"><?php echo app_format_money((float)($deduction['amount'] ?? 0), get_base_currency()); ?></td>
                                      </tr>
                                    <?php } } else { ?>
                                      <tr><td colspan="2" class="text-muted text-center">No deductions recorded</td></tr>
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
  var $search = $('#generated-slip-search');
  var $table = $('#generated-slip-table');

  $search.on('input', function(){
    var term = ($search.val() || '').toLowerCase();
    $table.find('tbody tr.data-row').each(function(){
      var tokens = ($(this).data('filter-tokens') || '').toString();
      var match = !term || tokens.indexOf(term) !== -1;
      $(this).toggle(match);
      var $next = $(this).next('.slip-breakdown-row');
      if (!match && $next.length) {
        $next.hide();
      }
    });
  });

  $table.on('click', '.toggle-slip-breakdown', function(){
    var $target = $($(this).data('target'));
    if (!$target.length) return;
    $target.slideToggle(150);
  });
})();
</script>
</body>
</html>
