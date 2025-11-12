<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.maestro-table {
  width: 100%;
  overflow-x: auto;
  display: block;
}
.maestro-table table {
  min-width: 2000px;
}
.date-column {
  min-width: 200px;
  text-align: center;
}
.date-header {
  writing-mode: vertical-rl;
  text-orientation: mixed;
  padding: 10px 5px;
  font-size: 11px;
}
.date-cell {
  padding: 5px;
  border: 1px solid #ddd;
}
.date-cell .form-group {
  margin-bottom: 5px;
}
.date-cell select,
.date-cell input {
  width: 100%;
  font-size: 11px;
  padding: 3px;
}
.locked-cell {
  background-color: #f0f0f0 !important;
  opacity: 0.7;
}
.locked-cell select,
.locked-cell input {
  background-color: #e9ecef !important;
  cursor: not-allowed;
  color: #999;
}
.sticky-col {
  position: sticky;
  left: 0;
  background: white;
  z-index: 10;
  border-right: 2px solid #ddd;
}
</style>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2">
      <i class="fa-solid fa-calendar-days tw-mr-2"></i> Manage Attendance Maestro
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <!-- Search Form -->
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Month</label>
                    <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employee</label>
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
                <div class="col-md-4">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary" title="Search"><i class="fa fa-search"></i> Search</button>
                      <a href="<?php echo admin_url('hrd/manage_attendance_maestro'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-xmark"></i> Reset</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($staff_list)) { ?>
              <form id="maestro-form">
                <input type="hidden" name="month" value="<?php echo e($month); ?>">
                <div class="row" style="margin-bottom: 15px;">
                  <div class="col-md-12">
                    <div class="checkbox checkbox-primary">
                      <input type="checkbox" name="lock_data" id="lock_data" value="1">
                      <label for="lock_data">
                        <strong>Lock</strong> - Lock all attendance data while saving (sets status to Locked)
                      </label>
                    </div>
                  </div>
                </div>
                <div class="maestro-table">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="sticky-col" style="min-width: 150px;">Employee Name</th>
                        <th class="sticky-col" style="min-width: 120px; left: 150px;">Employee Code</th>
                        <?php foreach ($dates as $date) { 
                          $dayName = date('D', strtotime($date));
                          $dayNum = date('d', strtotime($date));
                        ?>
                          <th class="date-column">
                            <div class="date-header">
                              <?php echo $dayName . '<br>' . $dayNum; ?>
                            </div>
                          </th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($staff_list as $staff) { 
                        $staffid = (int)$staff['staffid'];
                      ?>
                        <tr>
                          <td class="sticky-col" style="font-weight: bold;"><?php echo e($staff['full_name']); ?></td>
                          <td class="sticky-col" style="left: 150px;"><?php echo e($staff['employee_code'] ?? ''); ?></td>
                          <?php foreach ($dates as $date) { 
                            $key = $staffid . '_' . $date;
                            $att = isset($attendance_map[$key]) ? $attendance_map[$key] : null;
                            $first_half = isset($att['first_half']) ? (string)$att['first_half'] : '';
                            $portion = isset($att['position']) ? $att['position'] : '';
                            $second_half = isset($att['second_half']) ? (string)$att['second_half'] : '';
                            $isLocked = isset($att['status']) && (int)$att['status'] === 1;
                            $disabledAttr = $isLocked ? 'disabled' : '';
                            $cellClass = $isLocked ? 'date-cell locked-cell' : 'date-cell';
                          ?>
                            <td class="<?php echo $cellClass; ?>">
                              <div class="form-group" style="margin-bottom: 5px;">
                                <label style="font-size: 10px; margin-bottom: 2px;">First Half <span class="text-danger">*</span></label>
                                <select name="staff_data[<?php echo $staffid; ?>][<?php echo $date; ?>][first_half]" class="form-control input-sm fh-required" <?php echo $disabledAttr; ?> <?php echo !$isLocked ? 'required' : ''; ?>>
                                  <option value="">--</option>
                                  <?php if (!empty($attendance_statuses)) { 
                                    foreach ($attendance_statuses as $st) { 
                                      $sel = $first_half === (string)$st['id'] ? 'selected' : '';
                                  ?>
                                    <option value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                                  <?php } } ?>
                                </select>
                              </div>
                              <div class="form-group" style="margin-bottom: 5px;">
                                <label style="font-size: 10px; margin-bottom: 2px;">Portion</label>
                                <input type="text" name="staff_data[<?php echo $staffid; ?>][<?php echo $date; ?>][portion]" 
                                       class="form-control input-sm" value="<?php echo e($portion !== '' ? $portion : '1.00'); ?>" 
                                       placeholder="1.00" style="font-size: 11px;" <?php echo $disabledAttr; ?>>
                              </div>
                              <div class="form-group" style="margin-bottom: 0;">
                                <label style="font-size: 10px; margin-bottom: 2px;">Second Half</label>
                                <select name="staff_data[<?php echo $staffid; ?>][<?php echo $date; ?>][second_half]" class="form-control input-sm" <?php echo $disabledAttr; ?>>
                                  <option value="">--</option>
                                  <?php if (!empty($attendance_statuses)) { 
                                    foreach ($attendance_statuses as $st) { 
                                      $sel = $second_half === (string)$st['id'] ? 'selected' : '';
                                  ?>
                                    <option value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                                  <?php } } ?>
                                </select>
                              </div>
                              <input type="hidden" name="staff_data[<?php echo $staffid; ?>][<?php echo $date; ?>][staffid]" value="<?php echo $staffid; ?>">
                              <input type="hidden" name="staff_data[<?php echo $staffid; ?>][<?php echo $date; ?>][date]" value="<?php echo e($date); ?>">
                              <input type="hidden" name="staff_data[<?php echo $staffid; ?>][<?php echo $date; ?>][is_locked]" value="<?php echo $isLocked ? '1' : '0'; ?>">
                            </td>
                          <?php } ?>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
                <div class="row" style="margin-top: 20px;">
                  <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary btn-lg">
                      <i class="fa fa-save"></i> Save Attendance
                    </button>
                  </div>
                </div>
              </form>
            <?php } else { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                No employees found. Please select a month and/or employee to view attendance data.
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
jQuery(document).ready(function($){
  $('#maestro-form').on('submit', function(e){
    e.preventDefault();
    
    var month = $('input[name="month"]').val();
    
    // Validate First Half is required (skip locked records)
    var missingFirstHalf = [];
    $('.fh-required:not(:disabled)').each(function(){
      var $select = $(this);
      var value = $select.val();
      if (!value || value === '') {
        var name = $select.attr('name');
        var matches = name.match(/staff_data\[(\d+)\]\[([^\]]+)\]/);
        if (matches) {
          var staffid = matches[1];
          var date = matches[2];
          // Find staff name from the row
          var $row = $select.closest('tr');
          var staffName = $row.find('td.sticky-col').first().text().trim();
          missingFirstHalf.push(staffName + ' - ' + date);
        }
      }
    });
    
    if (missingFirstHalf.length > 0) {
      alert('First Half is required for the following:\n\n' + missingFirstHalf.slice(0, 10).join('\n') + (missingFirstHalf.length > 10 ? '\n... and ' + (missingFirstHalf.length - 10) + ' more' : ''));
      return false;
    }
    
    // Collect all staff data
    var staffDataMap = {};
    
    // Process all inputs and selects with staff_data name pattern (skip disabled/locked)
    $('input[name^="staff_data"]:not(:disabled), select[name^="staff_data"]:not(:disabled)').each(function(){
      var name = $(this).attr('name');
      // Pattern: staff_data[staffid][date][field]
      var matches = name.match(/staff_data\[(\d+)\]\[([^\]]+)\]\[([^\]]+)\]/);
      if (matches) {
        var staffid = matches[1];
        var date = matches[2];
        var field = matches[3];
        
        // Check if this record is locked
        var $hiddenLock = $('input[name="staff_data[' + staffid + '][' + date + '][is_locked]"]');
        if ($hiddenLock.length && $hiddenLock.val() === '1') {
          return; // Skip locked records
        }
        
        var value = $(this).val() || '';
        
        // Create key for this staffid+date combination
        var key = staffid + '_' + date;
        if (!staffDataMap[key]) {
          staffDataMap[key] = {
            staffid: parseInt(staffid),
            date: date
          };
        }
        staffDataMap[key][field] = value;
      }
    });
    
    // Convert map to array
    var staffData = [];
    for (var key in staffDataMap) {
      if (staffDataMap.hasOwnProperty(key)) {
        staffData.push(staffDataMap[key]);
      }
    }
    
    if (staffData.length === 0) {
      alert('No data to save');
      return false;
    }
    
    // Show loading
    var $btn = $(this).find('button[type="submit"]');
    var originalText = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
    
    // Get lock checkbox value
    var lockData = $('#lock_data').is(':checked') ? 1 : 0;
    
    $.ajax({
      url: admin_url + 'hrd/save_attendance_maestro',
      method: 'POST',
      data: {
        month: month,
        staff_data: staffData,
        lock_data: lockData
      },
      dataType: 'json'
    }).done(function(resp){
      if(resp && resp.success) {
        alert(resp.message || 'Attendance saved successfully');
        window.location.reload();
      } else {
        alert('Failed to save: ' + (resp.message || 'Unknown error'));
        $btn.prop('disabled', false).html(originalText);
      }
    }).fail(function(){
      alert('Failed to save attendance. Please try again.');
      $btn.prop('disabled', false).html(originalText);
    });
    
    return false;
  });
});
</script>
<?php hooks()->do_action('app_admin_footer'); ?>

