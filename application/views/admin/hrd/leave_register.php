<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2">
      <i class="fa-solid fa-calendar-check tw-mr-2"></i> Leave Register
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <!-- Search Form -->
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <?php if (is_admin() || is_department_admin()) { ?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employee</label>
                    <select name="staffid" class="form-control">
                      <option value="">-- Select Employee --</option>
                      <?php if (!empty($all_staff)) { foreach ($all_staff as $st) { 
                        $selected = (isset($staffid) && (int)$staffid === (int)$st['staffid']) ? 'selected' : '';
                      ?>
                        <option value="<?php echo (int)$st['staffid']; ?>" <?php echo $selected; ?>><?php echo e($st['full_name']); ?> (<?php echo e($st['employee_code'] ?? ''); ?>)</option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <?php } else { ?>
                  <input type="hidden" name="staffid" value="<?php echo get_staff_user_id(); ?>">
                <?php } ?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Year</label>
                    <input type="number" name="year" class="form-control" min="2000" max="2099" value="<?php echo e($year); ?>" placeholder="YYYY" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary" title="Search"><i class="fa fa-search"></i> Search</button>
                      <a href="<?php echo admin_url('hrd/leave_register'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-xmark"></i> Reset</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($leave_register)) { 
              $first_reg = $leave_register[0];
              // Calculate totals
              $total_days_sum = 0;
              $total_present_sum = 0;
              $total_adsent_sum = 0;
              $pl_count_sum = 0;
              $leave_earned_sum = 0;
              $leave_balance_sum = 0;
              foreach ($leave_register as $reg) {
                $total_days_sum += $reg['total_days'];
                $total_present_sum += $reg['total_present'];
                $total_adsent_sum += isset($reg['total_adsent']) ? $reg['total_adsent'] : 0;
                $pl_count_sum += $reg['pl_count'];
                $leave_earned_sum += $reg['leave_earned'];
                $leave_balance_sum += $reg['leave_balance'];
              }
            ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                Leave Register for <strong><?php echo e($first_reg['staff_name']); ?></strong>
                <?php if (!empty($first_reg['employee_code'])) { ?>
                  (Code: <?php echo e($first_reg['employee_code']); ?>)
                <?php } ?>
                - Year: <strong><?php echo e($year); ?></strong>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped dt-table" data-order-col="7" data-order-type="asc">
                  <thead>
                    <tr style="background-color: #f9f9f9;">
                      <th style="width: 15%;">Month Year</th>
                      <th style="width: 12%;">Total Days</th>
                      <th style="width: 12%;">Total Present</th>
					  <th style="width: 12%;">Total Absent</th>
                      <th style="width: 12%;">PL</th>
                      <th style="width: 12%;">Leave Earned</th>
                      <th style="width: 15%;">Leave Balance</th>
					  <th style="display:none;" >&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    // Display all 12 months
                    if (count($leave_register) > 0) {
                      foreach ($leave_register as $reg) { 
                    ?>
                      <tr>
                        <td><strong><?php echo e($reg['month_display']); ?></strong></td>
                        <td style="text-align: center;">
                          <span class="badge badge-info" style="font-size: 14px; padding: 6px 10px;">
                            <?php echo (int)$reg['total_days']; ?>
                          </span>
                        </td>
                        <td style="text-align: center;">
                          <span class="badge badge-success" style="font-size: 14px; padding: 6px 10px;">
                            <?php echo (int)$reg['total_present']; ?>
                          </span>
                        </td>
						<td style="text-align: center;">
                          <span class="badge badge-danger" style="font-size: 14px; padding: 6px 10px;">
                            <?php echo (int)($reg['total_adsent'] ?? 0); ?>
                          </span>
                        </td>
                        <td style="text-align: center;">
                          <span class="badge badge-warning" style="font-size: 14px; padding: 6px 10px;">
                            <?php echo (int)$reg['pl_count']; ?>
                          </span>
                        </td>
                        <td style="text-align: center;">
                          <span class="badge badge-primary" style="font-size: 14px; padding: 6px 10px;">
                            <?php echo (int)$reg['leave_earned']; ?>
                          </span>
                        </td>
                        <td style="text-align: center;">
                          <span class="badge <?php echo $reg['leave_balance'] >= 0 ? 'badge-success' : 'badge-danger'; ?>" style="font-size: 14px; padding: 6px 10px;">
                            <?php echo number_format($reg['leave_balance'], 2); ?>
                          </span>
                        </td>
						<td style="display:none;" >0</td>
                         
                        
                      </tr>
                    <?php 
                      } // end foreach
                    } // end if
                    ?>
                  </tbody>
                  <tfoot>
                    <tr style="background-color: #f9f9f9; font-weight: bold;">
                      <td style="text-align: right;"><strong>Year Total:</strong></td>
                      <td style="text-align: center;">
                        <span class="badge badge-info" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo (int)$total_days_sum; ?>
                        </span>
                      </td>
                      <td style="text-align: center;">
                        <span class="badge badge-success" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo (int)$total_present_sum; ?>
                        </span>
                      </td>
					  <td style="text-align: center;">
                        <span class="badge badge-danger" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo (int)$total_adsent_sum; ?>
                        </span>
                      </td>
                      <td style="text-align: center;">
                        <span class="badge badge-warning" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo (int)$pl_count_sum; ?>
                        </span>
                      </td>
                      <td style="text-align: center;">
                        <span class="badge badge-primary" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo (int)$leave_earned_sum; ?>
                        </span>
                      </td>
                      <td style="text-align: center;">
                        <span class="badge <?php echo $leave_balance_sum >= 0 ? 'badge-success' : 'badge-danger'; ?>" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo number_format($leave_balance_sum, 2); ?>
                        </span>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <!-- Summary Cards -->
              <div class="row" style="margin-top: 20px;">
                <div class="col-md-3">
                  <div class="panel panel-info">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #5bc0de; font-size: 32px;">
                        <?php echo (int)$total_days_sum; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Days (Year)</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-success">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #5cb85c; font-size: 32px;">
                        <?php echo (int)$total_present_sum; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Present (Year)</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-warning">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #f0ad4e; font-size: 32px;">
                        <?php echo (int)$pl_count_sum; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">PL (Year)</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel <?php echo $leave_balance_sum >= 0 ? 'panel-success' : 'panel-danger'; ?>">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: <?php echo $leave_balance_sum >= 0 ? '#5cb85c' : '#d9534f'; ?>; font-size: 32px;">
                        <?php echo number_format($leave_balance_sum, 2); ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Leave Balance (Year)</p>
                    </div>
                  </div>
                </div>
              </div>
            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                <?php if ($staffid <= 0) { ?>
                  Please select an employee to view leave register.
                <?php } else { ?>
                  No attendance data found for the selected year.
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<?php hooks()->do_action('app_admin_footer'); ?>
<script>

</script>

