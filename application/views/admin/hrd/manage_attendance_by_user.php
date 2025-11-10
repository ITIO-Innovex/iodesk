<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Select Staff (Multiple Selection)</label>
                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; background: #f9f9f9;">
                      <div class="row">
                        <div class="col-md-12" style="margin-bottom: 10px;">
                          <input type="checkbox" id="select-all-staff" title="Select All">
                          <label for="select-all-staff" style="font-weight: bold; margin-left: 5px;">Select All</label>
                        </div>
                      </div>
                      <div class="row">
                        <?php if (!empty($all_staff)) { 
                          $col_count = 0;
                          foreach ($all_staff as $st) { 
                            $staff_id = (int)$st['staffid'];
                            $is_selected = in_array($staff_id, $selected_staff_ids);
                        ?>
                          <div class="col-md-3" style="margin-bottom: 5px;">
                            <input type="checkbox" 
                                   name="staff_ids[]" 
                                   class="staff-checkbox" 
                                   value="<?php echo $staff_id; ?>" 
                                   id="staff_<?php echo $staff_id; ?>"
                                   <?php echo $is_selected ? 'checked' : ''; ?>>
                            <label for="staff_<?php echo $staff_id; ?>" style="margin-left: 5px; font-weight: normal;">
                              <?php echo e($st['full_name']); ?>
                              <?php if (!empty($st['employee_code'])) { ?>
                                <small>(<?php echo e($st['employee_code']); ?>)</small>
                              <?php } ?>
                            </label>
                          </div>
                        <?php 
                            $col_count++;
                            if ($col_count % 4 == 0) {
                              echo '</div><div class="row">';
                            }
                          } 
                        } else { ?>
                          <div class="col-md-12">
                            <p class="text-muted">No staff found.</p>
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary" title="Search"><i class="fa fa-search"></i> Search</button>
                    <a href="<?php echo admin_url('hrd/manage_attendance_by_user'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-xmark"></i> Reset</a>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($selected_staff)) { ?>
            <div class="row" style="margin-bottom:10px;">
              <div class="col-md-12">
                <h4>Selected Staff (<?php echo count($selected_staff); ?>)</h4>
              </div>
            </div>

            <table class="table dt-table" data-order-col="1" data-order-type="asc">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Employee Code</th>
                  <th>Employee Name</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php $index = 1; foreach ($selected_staff as $s) { 
                  $staff_id = (int)$s['staffid'];
                ?>
                  <tr>
                    <td><?php echo $index++; ?></td>
                    <td><?php echo e($s['employee_code'] ?? '-'); ?></td>
                    <td><?php echo e($s['full_name']); ?></td>
                    <td>
                      <div class="tw-flex tw-items-center tw-gap-2">
                        <a href="<?php echo admin_url('hrd/setting/employee_attendance?staffid=' . $staff_id); ?>" 
                           class="btn btn-default btn-sm" 
                           title="View Attendance"
                           target="_blank">
                          <i class="fa-regular fa-calendar-check"></i> View Attendance
                        </a>
                        <a href="<?php echo admin_url('hrd/leave_manager?staffid=' . $staff_id); ?>" 
                           class="btn btn-default btn-sm" 
                           title="View Leave Request"
                           target="_blank">
                          <i class="fa-regular fa-calendar-days"></i> View Leave Request
                        </a>
                        <a href="<?php echo admin_url('hrd/attendance_request?staffid=' . $staff_id); ?>" 
                           class="btn btn-default btn-sm" 
                           title="View Attendance Request"
                           target="_blank">
                          <i class="fa-regular fa-file-lines"></i> View Attendance Request
                        </a>
                      </div>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
              <div class="row">
                <div class="col-md-12">
                  <p class="text-muted text-center" style="padding: 20px;">
                    <?php if (empty($selected_staff_ids)) { ?>
                      Please select one or more staff members from the list above and click Search to view their details.
                    <?php } else { ?>
                      No staff found for the selected criteria.
                    <?php } ?>
                  </p>
                </div>
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
$(document).ready(function() {
  // Select All functionality
  $('#select-all-staff').on('change', function() {
    $('.staff-checkbox').prop('checked', $(this).prop('checked'));
  });

  // Update Select All checkbox state when individual checkboxes change
  $('.staff-checkbox').on('change', function() {
    var total = $('.staff-checkbox').length;
    var checked = $('.staff-checkbox:checked').length;
    $('#select-all-staff').prop('checked', total === checked);
  });

  // Initialize Select All state
  var total = $('.staff-checkbox').length;
  var checked = $('.staff-checkbox:checked').length;
  if (total > 0 && total === checked) {
    $('#select-all-staff').prop('checked', true);
  }
});
</script>
<?php init_tail(); ?>
