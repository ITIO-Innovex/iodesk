<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
              <i class="fa-solid fa-chart-line menu-icon tw-mr-2"></i> Employee Count Analysis
            </h4>

            <!-- Search Form -->
            <form method="get" action="" class="mbot15" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px;">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Select Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary" title="Search">
                        <i class="fa fa-search"></i> Search
                      </button>
                      <a href="<?php echo admin_url('hrd/setting/employee_count_analysis'); ?>" class="btn btn-default" title="Reset">
                        <i class="fa-solid fa-xmark"></i> Reset
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="well" style="margin: 0; padding: 10px; text-align: center;">
                      <strong>Selected Date:</strong><br>
                      <span style="font-size: 16px; color: #337ab7;"><?php echo e($date_display); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($company_stats)) { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                Showing employee count analysis for <strong><?php echo e($date_display); ?></strong>. 
                Companies are sorted by attendance percentage (highest first).
              </div>

              <table class="table table-bordered table-striped dt-table" data-order-col="5" data-order-type="desc">
                <thead>
                  <tr style="background-color: #f9f9f9;">
                    <th style="width: 5%;">#</th>
                    <th style="width: 30%;">Company Name</th>
                    <th style="width: 15%;">Total Employee</th>
                    <th style="width: 15%;">Total Present</th>
                    <th style="width: 15%;">Total Absent</th>
                    <th style="width: 20%;">Attendance Percentage</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $index = 1;
                  $grand_total_employees = 0;
                  $grand_total_present = 0;
                  $grand_total_absent = 0;
                  
                  foreach ($company_stats as $stat) {
                    $grand_total_employees += (int)$stat['total_employees'];
                    $grand_total_present += (int)$stat['total_present'];
                    $grand_total_absent += (int)$stat['total_absent'];
                    
                    $percentage = (float)$stat['attendance_percentage'];
                    // Determine badge color based on percentage
                    if ($percentage >= 90) {
                      $badge_class = 'success';
                      $progress_class = 'progress-bar-success';
                    } elseif ($percentage >= 70) {
                      $badge_class = 'info';
                      $progress_class = 'progress-bar-info';
                    } elseif ($percentage >= 50) {
                      $badge_class = 'warning';
                      $progress_class = 'progress-bar-warning';
                    } else {
                      $badge_class = 'danger';
                      $progress_class = 'progress-bar-danger';
                    }
                  ?>
                    <tr>
                      <td><?php echo $index++; ?></td>
                      <td>
                        <strong><?php echo e($stat['company_name']); ?></strong>
                        <br><small class="text-muted">ID: <?php echo (int)$stat['company_id']; ?></small>
                      </td>
                      <td>
                        <span class="badge badge-default" style="font-size: 14px; padding: 6px 10px;">
                          <?php echo (int)$stat['total_employees']; ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge badge-success" style="font-size: 14px; padding: 6px 10px;">
                          <?php echo (int)$stat['total_present']; ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge badge-danger" style="font-size: 14px; padding: 6px 10px;">
                          <?php echo (int)$stat['total_absent']; ?>
                        </span>
                      </td>
                      <td>
                        
                        <span class="label label-<?php echo $badge_class; ?>">
                          <?php echo number_format($percentage, 2); ?>%
                        </span>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="2" style="text-align: right;">Grand Total:</td>
                    <td>
                      <span class="badge badge-primary" style="font-size: 16px; padding: 8px 12px;">
                        <?php echo $grand_total_employees; ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-success" style="font-size: 16px; padding: 8px 12px;">
                        <?php echo $grand_total_present; ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-danger" style="font-size: 16px; padding: 8px 12px;">
                        <?php echo $grand_total_absent; ?>
                      </span>
                    </td>
                    <td>
                      <?php 
                      $grand_percentage = $grand_total_employees > 0 
                        ? round(($grand_total_present / $grand_total_employees) * 100, 2) 
                        : 0;
                      $grand_badge_class = $grand_percentage >= 90 ? 'success' : 
                                          ($grand_percentage >= 70 ? 'info' : 
                                          ($grand_percentage >= 50 ? 'warning' : 'danger'));
                      ?>
                      <span class="label label-<?php echo $grand_badge_class; ?>" style="font-size: 14px;">
                        <?php echo number_format($grand_percentage, 2); ?>%
                      </span>
                    </td>
                  </tr>
                </tfoot>
              </table>

              <!-- Summary Cards -->
              <div class="row" style="margin-top: 20px;">
                <div class="col-md-3">
                  <div class="panel panel-primary">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #337ab7; font-size: 32px;">
                        <?php echo count($company_stats); ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Companies</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-info">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #5bc0de; font-size: 32px;">
                        <?php echo $grand_total_employees; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Employees</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-success">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #5cb85c; font-size: 32px;">
                        <?php echo $grand_total_present; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Present</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-danger">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #d9534f; font-size: 32px;">
                        <?php echo $grand_total_absent; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Absent</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Overall Attendance Percentage -->
              

            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                No company data found for the selected date: <strong><?php echo e($date_display); ?></strong>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

