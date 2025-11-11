<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
              <i class="fa-solid fa-clock menu-icon tw-mr-2"></i> List of Employee Early Going
            </h4>

            <!-- Search Form -->
            <form method="get" action="" class="mbot15" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Search by Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e($date ?? date('Y-m-d')); ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>OR Search by Month</label>
                    <input type="month" name="month" class="form-control" value="<?php echo e($month ?? ''); ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary" title="Search">
                        <i class="fa fa-search"></i> Search
                      </button>
                      <a href="<?php echo admin_url('hrd/setting/list_of_employee_early_going'); ?>" class="btn btn-default" title="Reset">
                        <i class="fa-solid fa-xmark"></i> Reset
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="well" style="margin: 0; padding: 10px; text-align: center;">
                      <strong>Selected:</strong><br>
                      <span style="font-size: 16px; color: #337ab7;"><?php echo e($month_display); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($top_10_early)) { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                Showing employees with <strong>out_time before 6:30 PM (18:30)</strong> 
                <?php if ($use_date_filter) { ?>
                  for the selected date.
                <?php } else { ?>
                  for the selected month.
                <?php } ?>
              </div>

              <table class="table table-bordered table-striped dt-table" data-order-col="<?php echo $use_date_filter ? '4' : '3'; ?>" data-order-type="desc">
                <thead>
                  <tr style="background-color: #f9f9f9;">
                    <th style="width: 5%;">Rank</th>
                    <th style="width: 25%;">Staff Name</th>
                    <th style="width: 15%;">Employee Code</th>
                    <?php if ($use_date_filter) { ?>
                      <th style="width: 15%;">Date</th>
                      <th style="width: 15%;">Out Time</th>
                    <?php } ?>
                    <th style="width: 15%;">Early Going Count</th>
                    <th style="width: 15%;">Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $rank = 1;
                  foreach ($top_10_early as $employee) { 
                    $early_count = (int)$employee['early_count'];
                    // Determine badge color based on count
                    if ($early_count >= 10) {
                      $badge_class = 'danger';
                      $status_text = 'Critical';
                    } elseif ($early_count >= 5) {
                      $badge_class = 'warning';
                      $status_text = 'High';
                    } else {
                      $badge_class = 'info';
                      $status_text = 'Moderate';
                    }
                    
                    // If date filter, show each record separately
                    if ($use_date_filter && !empty($employee['records'])) {
                      foreach ($employee['records'] as $record) {
                  ?>
                    <tr>
                      <td>
                        <span class="badge badge-<?php echo $rank <= 3 ? 'danger' : 'default'; ?>" style="font-size: 14px; padding: 6px 10px;">
                          #<?php echo $rank++; ?>
                        </span>
                      </td>
                      <td>
                        <strong><?php echo e($employee['full_name']); ?></strong>
                        <br><small class="text-muted">ID: <?php echo (int)$employee['staffid']; ?></small>
                      </td>
                      <td>
                        <?php if (!empty($employee['employee_code'])) { ?>
                          <span class="label label-default"><?php echo e($employee['employee_code']); ?></span>
                        <?php } else { ?>
                          <span class="text-muted">-</span>
                        <?php } ?>
                      </td>
                      <td><?php echo !empty($record['entry_date']) ? date('d M Y', strtotime($record['entry_date'])) : '-'; ?></td>
                      <td>
                        <span class="label label-danger" style="font-size: 13px;">
                          <?php echo !empty($record['out_time']) ? date('H:i:s', strtotime($record['out_time'])) : '-'; ?>
                        </span>
                      </td>
                      <td>
                        <span class="badge badge-<?php echo $badge_class; ?>" style="font-size: 14px; padding: 6px 10px;">
                          1 time
                        </span>
                      </td>
                      <td>
                        <span class="label label-<?php echo $badge_class; ?>">
                          <?php echo $status_text; ?>
                        </span>
                      </td>
                    </tr>
                  <?php 
                      }
                    } else {
                      // Month view - show summary
                  ?>
                    <tr>
                      <td>
                        <span class="badge badge-<?php echo $rank <= 3 ? 'danger' : 'default'; ?>" style="font-size: 14px; padding: 6px 10px;">
                          #<?php echo $rank++; ?>
                        </span>
                      </td>
                      <td>
                        <strong><?php echo e($employee['full_name']); ?></strong>
                        <br><small class="text-muted">ID: <?php echo (int)$employee['staffid']; ?></small>
                      </td>
                      <td>
                        <?php if (!empty($employee['employee_code'])) { ?>
                          <span class="label label-default"><?php echo e($employee['employee_code']); ?></span>
                        <?php } else { ?>
                          <span class="text-muted">-</span>
                        <?php } ?>
                      </td>
                      <td>
                        <span class="badge badge-<?php echo $badge_class; ?>" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo $early_count; ?> <?php echo $early_count == 1 ? 'time' : 'times'; ?>
                        </span>
                      </td>
                      <td>
                        <span class="label label-<?php echo $badge_class; ?>">
                          <?php echo $status_text; ?>
                        </span>
                      </td>
                    </tr>
                  <?php 
                    }
                  } 
                  ?>
                </tbody>
                <tfoot>
                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="<?php echo $use_date_filter ? '3' : '3'; ?>" style="text-align: right;">Total Early Going:</td>
                    <?php if ($use_date_filter) { ?>
                      <td colspan="2"></td>
                    <?php } ?>
                    <td>
                      <span class="badge badge-primary" style="font-size: 16px; padding: 8px 12px;">
                        <?php 
                        $total_early = 0;
                        foreach ($top_10_early as $e) {
                          $total_early += (int)$e['early_count'];
                        }
                        echo $total_early;
                        ?>
                      </span>
                    </td>
                    <td>
                      <span class="label label-info">
                        <?php echo count($top_10_early); ?> Employees
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
                        <?php echo count($top_10_early); ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Employees</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-danger">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #d9534f; font-size: 32px;">
                        <?php echo $total_early; ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Total Early Going</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-warning">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #f0ad4e; font-size: 32px;">
                        <?php 
                        $avg_early = count($top_10_early) > 0 ? round($total_early / count($top_10_early), 2) : 0;
                        echo $avg_early;
                        ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Average Early Going</p>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="panel panel-info">
                    <div class="panel-body text-center">
                      <h3 style="margin: 0; color: #5bc0de; font-size: 32px;">
                        <?php 
                        $max_early = 0;
                        foreach ($top_10_early as $e) {
                          if ((int)$e['early_count'] > $max_early) {
                            $max_early = (int)$e['early_count'];
                          }
                        }
                        echo $max_early;
                        ?>
                      </h3>
                      <p style="margin: 5px 0 0 0; color: #666;">Maximum Early Going</p>
                    </div>
                  </div>
                </div>
              </div>

            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                No employees found with early going (out_time before 6:30 PM) for the selected month: <strong><?php echo e($month_display); ?></strong>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

