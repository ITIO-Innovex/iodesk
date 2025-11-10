<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
              <i class="fa-solid fa-users menu-icon tw-mr-2"></i> Shift Wise Employee Count
              <small class="text-muted">(Date: <?php echo date('d M Y', strtotime($today)); ?>)</small>
            </h4>

            <?php if (!empty($staff_by_branch)) { ?>
              
              <!-- Branch Summary Count Boxes -->
              <div class="row" style="margin-bottom: 20px;">
                <?php 
                $branch_index = 0;
                foreach ($staff_by_branch as $branch_id => $branch_data) {
                  $branch_total_staff = count($branch_data['staff']);
                  $branch_present = 0;
                  $branch_absent = 0;
                  $branch_total_attendance = 0;
                  
                  foreach ($branch_data['staff'] as $s) {
                    $branch_total_attendance += (int)$s['attendance_count'];
                    if ($s['attendance_count'] > 0) {
                      $branch_present++;
                    } else {
                      $branch_absent++;
                    }
                  }
                  
                  // Start new row every 4 branches
                  if ($branch_index > 0 && $branch_index % 4 == 0) {
                    echo '</div><div class="row" style="margin-bottom: 20px;">';
                  }
                  
                  $branch_index++;
                ?>
                  <div class="col-md-3" style="margin-bottom: 15px;">
                    <div class="panel panel-default" style="border-left: 4px solid #337ab7;">
                      <div class="panel-body" style="padding: 15px;">
                        <h4 style="margin: 0 0 10px 0; color: #337ab7; font-size: 16px;">
                          <i class="fa-solid fa-building tw-mr-2"></i>
                          <?php echo e($branch_data['branch_name']); ?>
                        </h4>
                        <div class="row">
                          <div class="col-xs-6" style="padding: 5px;">
                            <div style="text-align: center;">
                              <h3 style="margin: 0; color: #337ab7; font-size: 24px;"><?php echo $branch_total_staff; ?></h3>
                              <small style="color: #666;">Total</small>
                            </div>
                          </div>
                          <div class="col-xs-6" style="padding: 5px;">
                            <div style="text-align: center;">
                              <h3 style="margin: 0; color: #5cb85c; font-size: 24px;"><?php echo $branch_present; ?></h3>
                              <small style="color: #666;">Present</small>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                          <div class="col-xs-6" style="padding: 5px;">
                            <div style="text-align: center;">
                              <h3 style="margin: 0; color: #f0ad4e; font-size: 24px;"><?php echo $branch_absent; ?></h3>
                              <small style="color: #666;">Absent</small>
                            </div>
                          </div>
                          <div class="col-xs-6" style="padding: 5px;">
                            <div style="text-align: center;">
                              <h3 style="margin: 0; color: #d9534f; font-size: 24px;"><?php echo $branch_total_attendance; ?></h3>
                              <small style="color: #666;">Records</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php 
                } 
                ?>
              </div>

              <hr style="margin: 20px 0; border-top: 2px solid #eee;">

              <?php foreach ($staff_by_branch as $branch_id => $branch_data) { ?>
                <div class="panel panel-default" style="margin-top: 20px;">
                  <div class="panel-heading" style="background-color: #f5f5f5; padding: 10px 15px;">
                    <h4 class="panel-title" style="margin: 0;">
                      <i class="fa-solid fa-building tw-mr-2"></i>
                      <strong><?php echo e($branch_data['branch_name']); ?></strong>
                      <span class="badge" style="margin-left: 10px;">
                        <?php echo count($branch_data['staff']); ?> Employee(s)
                      </span>
                    </h4>
                  </div>
                  <div class="panel-body" style="padding: 0;">
                    <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                      <thead>
                        <tr style="background-color: #f9f9f9;">
                          <th style="width: 5%;">#</th>
                          <th style="width: 25%;">Staff Name</th>
                          <th style="width: 15%;">Shift Code</th>
                          <th style="width: 20%;">Shift Time</th>
                          <th style="width: 15%;">Today's Count</th>
                          <th style="width: 20%;">Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $index = 1;
                        foreach ($branch_data['staff'] as $staff) { 
                          $has_attendance = $staff['attendance_count'] > 0;
                        ?>
                          <tr>
                            <td><?php echo $index++; ?></td>
                            <td>
                              <strong><?php echo e($staff['full_name']); ?></strong>
                              <?php if (function_exists('get_staff_full_name')) { ?>
                                <br><small class="text-muted">ID: <?php echo (int)$staff['staffid']; ?></small>
                              <?php } ?>
                            </td>
                            <td>
                              <?php if (!empty($staff['shift_code'])) { ?>
                                <span class="label label-info"><?php echo e($staff['shift_code']); ?></span>
                                <?php if (!empty($staff['shift_name'])) { ?>
                                  <br><small class="text-muted"><?php echo e($staff['shift_name']); ?></small>
                                <?php } ?>
                              <?php } else { ?>
                                <span class="text-muted">-</span>
                              <?php } ?>
                            </td>
                            <td>
                              <?php if ($staff['shift_time'] !== '-') { ?>
                                <span class="text-primary"><?php echo e($staff['shift_time']); ?></span>
                              <?php } else { ?>
                                <span class="text-muted">-</span>
                              <?php } ?>
                            </td>
                            <td>
                              <span class="badge <?php echo $has_attendance ? 'badge-success' : 'badge-default'; ?>" style="font-size: 14px; padding: 6px 10px;">
                                <?php echo (int)$staff['attendance_count']; ?>
                              </span>
                            </td>
                            <td>
                              <?php if ($has_attendance) { ?>
                                <span class="label label-success">
                                  <i class="fa-solid fa-check-circle"></i> Present
                                </span>
                              <?php } else { ?>
                                <span class="label label-warning">
                                  <i class="fa-solid fa-times-circle"></i> No Attendance
                                </span>
                              <?php } ?>
                            </td>
                          </tr>
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <tr style="background-color: #f9f9f9; font-weight: bold;">
                          <td colspan="4" style="text-align: right;">Branch Total:</td>
                          <td>
                            <span class="badge badge-primary" style="font-size: 14px; padding: 6px 10px;">
                              <?php 
                              $branch_total = 0;
                              foreach ($branch_data['staff'] as $s) {
                                $branch_total += (int)$s['attendance_count'];
                              }
                              echo $branch_total;
                              ?>
                            </span>
                          </td>
                          <td>
                            <?php 
                            $present_count = 0;
                            foreach ($branch_data['staff'] as $s) {
                              if ($s['attendance_count'] > 0) $present_count++;
                            }
                            $total_staff = count($branch_data['staff']);
                            ?>
                            <span class="label label-info">
                              <?php echo $present_count; ?> / <?php echo $total_staff; ?> Present
                            </span>
                          </td>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                </div>
              <?php } ?>

              <!-- Overall Summary -->
              <div class="panel panel-primary" style="margin-top: 20px;">
                <div class="panel-heading">
                  <h4 class="panel-title" style="margin: 0;">
                    <i class="fa-solid fa-chart-bar tw-mr-2"></i>
                    <strong>Overall Summary</strong>
                  </h4>
                </div>
                <div class="panel-body">
                  <?php 
                  $overall_total_staff = 0;
                  $overall_total_attendance = 0;
                  $overall_present = 0;
                  
                  foreach ($staff_by_branch as $branch_data) {
                    $overall_total_staff += count($branch_data['staff']);
                    foreach ($branch_data['staff'] as $s) {
                      $overall_total_attendance += (int)$s['attendance_count'];
                      if ($s['attendance_count'] > 0) $overall_present++;
                    }
                  }
                  ?>
                  <div class="row">
                    <div class="col-md-3">
                      <div class="well text-center">
                        <h3 style="margin: 0; color: #337ab7;"><?php echo $overall_total_staff; ?></h3>
                        <p style="margin: 5px 0 0 0;">Total Employees</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="well text-center">
                        <h3 style="margin: 0; color: #5cb85c;"><?php echo $overall_present; ?></h3>
                        <p style="margin: 5px 0 0 0;">Present Today</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="well text-center">
                        <h3 style="margin: 0; color: #f0ad4e;"><?php echo $overall_total_staff - $overall_present; ?></h3>
                        <p style="margin: 5px 0 0 0;">Absent Today</p>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="well text-center">
                        <h3 style="margin: 0; color: #d9534f;"><?php echo $overall_total_attendance; ?></h3>
                        <p style="margin: 5px 0 0 0;">Total Attendance Records</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <?php } else { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> No staff found for the selected criteria.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

