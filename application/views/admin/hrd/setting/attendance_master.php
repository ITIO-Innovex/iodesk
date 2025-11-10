<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
      <i class="fa-solid fa-calendar-check menu-icon tw-mr-2"></i> Attendance Master
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Attendance by Month</label>
                    <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>By Employee</label>
                    <select name="staff_id" class="form-control">
                      <option value="">-- All Employees --</option>
                      <?php if (!empty($all_staff)) { foreach ($all_staff as $st) { 
                        $selected = (isset($staff_filter) && (int)$staff_filter === (int)$st['staffid']) ? 'selected' : '';
                      ?>
                        <option value="<?php echo (int)$st['staffid']; ?>" <?php echo $selected; ?>><?php echo e($st['full_name']); ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>By Branch</label>
                    <select name="branch_id" class="form-control">
                      <option value="">-- All Branches --</option>
                      <?php if (!empty($all_branches)) { foreach ($all_branches as $br) { 
                        $selected = (isset($branch_filter) && (int)$branch_filter === (int)$br['id']) ? 'selected' : '';
                      ?>
                        <option value="<?php echo (int)$br['id']; ?>" <?php echo $selected; ?>><?php echo e($br['branch_name']); ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary" title="Search">
                        <i class="fa fa-search"></i> Search
                      </button>
                      <a href="<?php echo admin_url('hrd/setting/attendance_master'); ?>" class="btn btn-default" title="Reset">
                        <i class="fa-solid fa-xmark"></i> Reset
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($attendance_list)) { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                Showing <strong><?php echo count($attendance_list); ?></strong> attendance record(s) for 
                <strong><?php echo date('F Y', strtotime($month . '-01')); ?></strong>
                <?php if ($staff_filter > 0) { 
                  $staff_name = '';
                  foreach ($all_staff as $st) {
                    if ((int)$st['staffid'] === $staff_filter) {
                      $staff_name = $st['full_name'];
                      break;
                    }
                  }
                  if ($staff_name) {
                    echo ' - Employee: <strong>' . e($staff_name) . '</strong>';
                  }
                } ?>
                <?php if ($branch_filter > 0) { 
                  $branch_name = '';
                  foreach ($all_branches as $br) {
                    if ((int)$br['id'] === $branch_filter) {
                      $branch_name = $br['branch_name'];
                      break;
                    }
                  }
                  if ($branch_name) {
                    echo ' - Branch: <strong>' . e($branch_name) . '</strong>';
                  }
                } ?>
              </div>

              <table class="table dt-table table-bordered table-striped" data-order-col="1" data-order-type="desc">
                <thead>
                  <tr style="background-color: #f9f9f9;">
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Day</th>
                    <th>InTime</th>
                    <th>OutTime</th>
                    <th>First Half</th>
                    <th>Second Half</th>
                    <th>Portion</th>
                    <th>Tot. Hrs.</th>
                    <th>LateMark</th>
                    <th>Branch</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($attendance_list as $att) { 
                    $staffName = trim(($att['firstname'] ?? '') . ' ' . ($att['lastname'] ?? ''));
                    $entryDate = $att['entry_date'] ?? '';
                    $dayName = $entryDate ? date('l', strtotime($entryDate)) : '';
                    
                    // Format times
                    $inTime = isset($att['in_time']) && $att['in_time'] ? date('H:i:s', strtotime($att['in_time'])) : '-';
                    $outTime = isset($att['out_time']) && $att['out_time'] ? date('H:i:s', strtotime($att['out_time'])) : '-';
                    
                    // Get status titles
                    $firstHalfId = isset($att['first_half']) ? (int)$att['first_half'] : 0;
                    $secondHalfId = isset($att['second_half']) ? (int)$att['second_half'] : 0;
                    $firstHalfTitle = isset($status_map[$firstHalfId]) ? $status_map[$firstHalfId]['title'] : ($firstHalfId > 0 ? 'Status ' . $firstHalfId : '-');
                    $secondHalfTitle = isset($status_map[$secondHalfId]) ? $status_map[$secondHalfId]['title'] : ($secondHalfId > 0 ? 'Status ' . $secondHalfId : '-');
                    $firstHalfColor = isset($status_map[$firstHalfId]) ? $status_map[$firstHalfId]['color'] : '#000';
                    $secondHalfColor = isset($status_map[$secondHalfId]) ? $status_map[$secondHalfId]['color'] : '#000';
                    
                    $portion = isset($att['position']) ? number_format((float)$att['position'], 2) : '0.00';
                    $totalHours = $att['total_hours'] ?? '-';
                    $lateMark = isset($att['late_mark']) && (int)$att['late_mark'] === 1 ? '<span class="label label-danger">Yes</span>' : '<span class="label label-success">No</span>';
                    $branchName = $att['branch_name'] ?? '-';
                  ?>
                    <tr>
                      <td>
                        <strong><?php echo e($staffName); ?></strong>
                        <?php if (isset($att['employee_code']) && $att['employee_code']) { ?>
                          <br><small class="text-muted">Code: <?php echo e($att['employee_code']); ?></small>
                        <?php } ?>
                      </td>
                      <td><?php echo e($entryDate); ?></td>
                      <td><?php echo e($dayName); ?></td>
                      <td><?php echo e($inTime); ?></td>
                      <td><?php echo e($outTime); ?></td>
                      <td>
                        <?php if ($firstHalfId > 0) { ?>
                          <span style="background-color: <?php echo e($firstHalfColor); ?>; color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 11px;">
                            <?php echo e($firstHalfTitle); ?>
                          </span>
                        <?php } else { ?>
                          -
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($secondHalfId > 0) { ?>
                          <span style="background-color: <?php echo e($secondHalfColor); ?>; color: #fff; padding: 3px 8px; border-radius: 3px; font-size: 11px;">
                            <?php echo e($secondHalfTitle); ?>
                          </span>
                        <?php } else { ?>
                          -
                        <?php } ?>
                      </td>
                      <td><?php echo e($portion); ?></td>
                      <td><?php echo e($totalHours); ?></td>
                      <td><?php echo $lateMark; ?></td>
                      <td><?php echo e($branchName); ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="7" style="text-align: right;">Total Records:</td>
                    <td colspan="4"><?php echo count($attendance_list); ?></td>
                  </tr>
                </tfoot>
              </table>
            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                No attendance records found for the selected filters.
                <?php if ($month) { ?>
                  <br>Month: <strong><?php echo date('F Y', strtotime($month . '-01')); ?></strong>
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
</body></html>

