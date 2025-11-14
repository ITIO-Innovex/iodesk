<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.locked-row {
  background-color: #f0f0f0 !important;
  opacity: 0.7;
}
.locked-row td {
  color: #999;
}
.locked-row input,
.locked-row select {
  background-color: #e9ecef !important;
  cursor: not-allowed;
}
</style>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  Attendance By Date </span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>" />
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Branch</label>
                    <select name="branch_id" class="form-control">
                      <option value="">-- All Branches --</option>
                      <?php if (!empty($branches)) { foreach ($branches as $branch) { 
                        $selected = (isset($branch_filter) && (int)$branch_filter === (int)$branch['id']) ? 'selected' : '';
                      ?>
                        <option value="<?php echo (int)$branch['id']; ?>" <?php echo $selected; ?>><?php echo e($branch['branch_name']); ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Staff</label>
                    <select name="staff_id" class="form-control">
                      <option value="">-- All Staff --</option>
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
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-default" title="Search"><i class="fa fa-search"></i></button>
                      <a href="<?php echo admin_url('hrd/manage_attendance_by_date'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-xmark" ></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <div class="row" style="margin-bottom:10px;">
              <div class="col-md-12">
                <div class="tw-flex tw-items-center tw-gap-2">
                  <?php /*?><select id="bulk-first-half" class="form-control" style="width:auto; display:inline-block;">
                    <option value="">-- First Half --</option>
                    <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { ?>
                      <option value="<?php echo (int)$st['id']; ?>"><?php echo e($st['title']); ?></option>
                    <?php } } ?>
                  </select>
                  <select id="bulk-second-half" class="form-control" style="width:auto; display:inline-block;">
                    <option value="">-- Second Half --</option>
                    <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { ?>
                      <option value="<?php echo (int)$st['id']; ?>"><?php echo e($st['title']); ?></option>
                    <?php } } ?>
                  </select><?php */?>
                  <button type="button" class="btn btn-primary" id="apply-bulk">Apply to Selected & Locked In</button>
                  <?php /*?><button type="button" class="btn btn-success" id="lock-in-btn">Locked In</button><?php */?>
				  
				  <?php if(is_department_admin() || is_admin()){?>
                  <button type="button" class="btn btn-warning" id="lock-out-btn">Locked Out</button>
				  <?php } ?>
                </div>
              </div>
            </div>

            <table class="table dt-table" data-order-col="1" data-order-type="asc">
              <thead>
                <tr>
                  <th><input type="checkbox" id="select-all"></th>
                  <th>Employee</th>
                  <th>Date</th>
				  <th>Day</th>
                  <th>InTime</th>
                  <th>OutTime</th>
                  <th><select id="bulk-first-half" class="form-control" style="width:auto; display:inline-block;">
                    <option value="">First Half</option>
                    <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { ?>
                      <option value="<?php echo (int)$st['id']; ?>"><?php echo e($st['title']); ?></option>
                    <?php } } ?>
                  </select></th>
                  <th>Second Half</th>
                  <th>Portion</th>
				  <th>Tot. Hrs.</th>
                  <th>LateMark</th>
                  <?php /*?><th><?php echo _l('options'); ?></th><?php */?>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($staff)) { foreach ($staff as $s) { $sid=(int)$s['staffid']; $att = $attendance_map[$sid] ?? null; 
                  $isLocked = isset($att['status']) && (int)$att['status'] === 1;
                  $rowClass = $isLocked ? 'locked-row' : '';
                  $attendanceId = isset($att['attendance_id']) ? (int)$att['attendance_id'] : 0;
                ?>
                  <tr class="<?php echo $rowClass; ?>" data-staffid="<?php echo (int)$sid; ?>" data-attendance-id="<?php echo $attendanceId; ?>" data-locked="<?php echo $isLocked ? '1' : '0'; ?>">
                    <td>
                      <input type="checkbox" class="row-check" value="<?php echo (int)$sid; ?>" <?php echo $isLocked ? 'disabled' : ''; ?>>
                    </td>
                    <td><?php echo e(($s['firstname']??'') . ' ' . ($s['lastname']??'')); ?></td>
                    <td><?php echo e($date); ?></td>
					<td><?php echo date('D', strtotime(e($date))); ?></td>
                    <td>
                      <div class="input-group input-group-sm" style="max-width:200px;">
                        <input type="time" class="form-control in-time" data-staffid="<?php echo (int)$sid; ?>" value="<?php echo e(isset($att['in_time'])?date('H:i', strtotime($att['in_time'])):''); ?>" <?php echo $isLocked ? 'disabled' : ''; ?>>
                       
                      </div>
                    </td>
                    <td>
                      <div class="input-group input-group-sm" style="max-width:200px;">
                        <input type="time" class="form-control out-time" data-staffid="<?php echo (int)$sid; ?>" value="<?php echo e(isset($att['out_time'])?date('H:i', strtotime($att['out_time'])):''); ?>" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        
                      </div>
                    </td>
                    <td>
                      <select class="form-control fh-select" data-staffid="<?php echo (int)$sid; ?>" required title="First Half is required for checked data" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        <option value="">First Half *</option>
                        <?php if (!empty($attendance_statuses)) { 
						foreach ($attendance_statuses as $st) { 
						$sel = isset($att['first_half']) && (string)$att['first_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td>
                      <select class="form-control sh-select" data-staffid="<?php echo (int)$sid; ?>" <?php echo $isLocked ? 'disabled' : ''; ?>>
                        <option value="">Second Half</option>
                        <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { $sel = isset($att['second_half']) && (string)$att['second_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td><?php echo e($att['position'] ?? ''); ?></td>
					<td><?php echo e($att['total_hours'] ?? ''); ?></td>
					<td><?php echo e($att['late_mark'] ?? ''); ?></td>
                    <?php /*?><td>
                      <a class="btn btn-default btn-sm open-update" 
                         data-staffid="<?php echo (int)$sid; ?>"
                         data-date="<?php echo e($date); ?>"
                         data-in_time="<?php echo isset($att['in_time']) ? date('H:i', strtotime($att['in_time'])) : '';?>"
                         data-out_time="<?php echo isset($att['out_time']) ? date('H:i', strtotime($att['out_time'])) : '';?>"
                         data-first_half="<?php echo isset($att['first_half']) ? (string)$att['first_half'] : '';?>"
                         data-second_half="<?php echo isset($att['second_half']) ? (string)$att['second_half'] : '';?>"
                         data-staff_name="<?php echo e(($s['firstname']??'') . ' ' . ($s['lastname']??''));?>">
                        <i class="fa-solid fa-pen-to-square" title="Edit"></i>
                      </a>
                    </td><?php */?>
                  </tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Update Attendance Modal -->
<div class="modal fade" id="update_attendance_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Attendance</h4>
      </div>
      <div class="modal-body">
        <form id="update-attendance-form">
          <input type="hidden" id="modal-staffid" name="staffid" value="">
          <input type="hidden" id="modal-date" name="date" value="">
          
          <div class="form-group">
            <label>Employee Name</label>
            <input type="text" id="modal-staff-name" class="form-control" readonly>
          </div>
          
          <div class="form-group">
            <label>Date</label>
            <input type="date" id="modal-date-display" class="form-control" readonly>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>In Time</label>
                <input type="time" id="modal-in-time" name="in_time" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Out Time</label>
                <input type="time" id="modal-out-time" name="out_time" class="form-control">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>First Half <span class="text-danger">*</span></label>
                <select id="modal-first-half" name="first_half" class="form-control" required>
                  <option value="">-- Select First Half --</option>
                  <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { ?>
                    <option value="<?php echo (int)$st['id']; ?>"><?php echo e($st['title']); ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Second Half</label>
                <select id="modal-second-half" name="second_half" class="form-control">
                  <option value="">-- Select Second Half --</option>
                  <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { ?>
                    <option value="<?php echo (int)$st['id']; ?>"><?php echo e($st['title']); ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-update-attendance">Save Changes</button>
      </div>
    </div>
  </div>
</div>


<?php init_tail(); ?>
<script>
jQuery(document).ready(function($){
  try {
    // Open update modal
    $(document).on('click', '.open-update', function(e){
      e.preventDefault();
      e.stopPropagation();
      var data = $(this).data();
      if (typeof openUpdateModal === 'function') {
        openUpdateModal(data.staffid, data.date, data);
      }
      return false;
    });
    
    // Select all checkbox (skip locked rows)
    var selAll = document.getElementById('select-all');
    if(selAll){ 
      selAll.addEventListener('change', function(){
        var checked = this.checked;
        var checkboxes = document.querySelectorAll('.row-check:not(:disabled)');
        for(var i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = checked;
        }
      });
    }

  // Bulk apply button
  var applyBtn = document.getElementById('apply-bulk');
  if(applyBtn){ 
    applyBtn.addEventListener('click', function(){
      var checkedRows = document.querySelectorAll('.row-check:checked');
      if(checkedRows.length === 0){ alert('Select at least one staff'); return; }
      
      var date = '<?php echo e($date); ?>';
      var staffData = [];
      var missingFirstHalf = [];
      
      for(var i = 0; i < checkedRows.length; i++) {
        var checkbox = checkedRows[i];
        var staffid = checkbox.value;
        var $row = $(checkbox).closest('tr');
        var inTime = $row.find('input.in-time[data-staffid="'+staffid+'"]').val();
        var outTime = $row.find('input.out-time[data-staffid="'+staffid+'"]').val();
        var fh = $row.find('select.fh-select[data-staffid="'+staffid+'"]').val();
        var sh = $row.find('select.sh-select[data-staffid="'+staffid+'"]').val();
        var staffName = $row.find('td').eq(1).text().trim();
        
        // Validate First Half is required for checked data
        if (!fh || fh === '') {
          missingFirstHalf.push(staffName || 'Staff ID: ' + staffid);
        }
        
        staffData.push({
          staffid: staffid,
          in_time: inTime || '',
          out_time: outTime || '',
          first_half: fh || '',
          second_half: sh || ''
        });
      }
      
      // Show error if any checked row is missing First Half
      if (missingFirstHalf.length > 0) {
        alert('First Half is required for the following staff:\n\n' + missingFirstHalf.join('\n'));
        return;
      }
      
      $.post(admin_url + 'hrd/attendance_bulk_update_by_date', {date: date, staff_data: staffData}, function(resp){
        if(resp && resp.success){ 
          alert('Attendance updated successfully');
          window.location.reload(); 
        } else { 
          alert('Failed to update: ' + (resp.message || 'Unknown error')); 
        }
      }, 'json');
    });
  }

  // Lock In button - Set status to 1 (locked)
  var lockInBtn = document.getElementById('lock-in-btn');
  if(lockInBtn){
    lockInBtn.addEventListener('click', function(){
      var checkedRows = document.querySelectorAll('.row-check:checked:not(:disabled)');
      if(checkedRows.length === 0){ 
        alert('Select at least one staff to lock'); 
        return; 
      }
      
      if(!confirm('Are you sure you want to lock ' + checkedRows.length + ' selected attendance record(s)?')) {
        return;
      }
      
      var date = '<?php echo e($date); ?>';
      var attendanceIds = [];
      
      for(var i = 0; i < checkedRows.length; i++) {
        var checkbox = checkedRows[i];
        var staffid = checkbox.value;
        var $row = $(checkbox).closest('tr');
        var attendanceId = $row.data('attendance-id');
        if(attendanceId > 0) {
          attendanceIds.push(attendanceId);
        } else {
          // If no attendance record exists, we need to create one first
          // For now, we'll skip these and show a message
          var staffName = $row.find('td').eq(1).text().trim();
          console.warn('No attendance record found for ' + staffName);
        }
      }
      
      if(attendanceIds.length === 0) {
        alert('No attendance records found to lock. Please save attendance data first.');
        return;
      }
      
      $.post(admin_url + 'hrd/attendance_lock_by_date', {
        date: date,
        attendance_ids: attendanceIds,
        status: 1
      }, function(resp){
        if(resp && resp.success){ 
          alert('Attendance locked successfully');
          window.location.reload(); 
        } else { 
          alert('Failed to lock: ' + (resp.message || 'Unknown error')); 
        }
      }, 'json');
    });
  }

  // Lock Out button - Set status to 0 (unlocked) for current month
  var lockOutBtn = document.getElementById('lock-out-btn');
  if(lockOutBtn){
    lockOutBtn.addEventListener('click', function(){
      var date = '<?php echo e($date); ?>';
      var currentDate = new Date(date);
      var year = currentDate.getFullYear();
      var month = currentDate.getMonth() + 1; // JavaScript months are 0-indexed
      var monthYear = year + '-' + (month < 10 ? '0' + month : month);
      
      if(!confirm('Are you sure you want to unlock all attendance records for the current month (' + monthYear + ')?')) {
        return;
      }
      
      $.post(admin_url + 'hrd/attendance_lock_by_date', {
        date: date,
        month_year: monthYear,
        unlock_month: true,
        status: 0
      }, function(resp){
        if(resp && resp.success){ 
          alert('All attendance records for ' + monthYear + ' unlocked successfully');
          window.location.reload(); 
        } else { 
          alert('Failed to unlock: ' + (resp.message || 'Unknown error')); 
        }
      }, 'json');
    });
  }

  // Bulk First Half selection - apply to all fh-select dropdowns
  var bulkFirstHalf = document.getElementById('bulk-first-half');
  if(bulkFirstHalf){
    bulkFirstHalf.addEventListener('change', function(){
      var selectedValue = this.value;
      if(selectedValue === '') return; // Don't do anything if empty
      
      // Find all fh-select dropdowns that are not disabled
      var fhSelects = document.querySelectorAll('.fh-select:not(:disabled)');
      for(var i = 0; i < fhSelects.length; i++) {
        fhSelects[i].value = selectedValue;
        // Trigger change event in case there are other listeners
        if(typeof jQuery !== 'undefined') {
          $(fhSelects[i]).trigger('change');
        }
      }
    });
  }

  // Save single row In/Out time
  window.saveInOut = function(staffid, el){
    var $tr = $(el).closest('tr');
    var inTime = $tr.find('input.in-time[data-staffid="'+staffid+'"]').val();
    var outTime = $tr.find('input.out-time[data-staffid="'+staffid+'"]').val();
    $.post(admin_url + 'hrd/attendance_update_inout_by_date', {staffid: staffid, date: '<?php echo e($date); ?>', in_time: inTime, out_time: outTime}, function(resp){
      if(!(resp && resp.success)){ alert('Failed to save'); }
    }, 'json');
  };
  
 

  // Open update modal
  window.openUpdateModal = function(staffid, date, data){
    if(typeof jQuery === 'undefined' || !$('#update_attendance_modal').length){
      alert('Modal not ready. Please refresh the page.');
      return;
    }
    
    $('#modal-staffid').val(staffid);
    $('#modal-date').val(date);
    $('#modal-date-display').val(date);
    $('#modal-staff-name').val(data.staff_name || '');
    $('#modal-in-time').val(data.in_time || '');
    $('#modal-out-time').val(data.out_time || '');
    $('#modal-first-half').val(data.first_half || '');
    $('#modal-second-half').val(data.second_half || '');
    
    $('#update_attendance_modal').modal('show');
  };

  // Save update attendance
  $(document).on('click', '#save-update-attendance', function(){
    var staffid = $('#modal-staffid').val();
    var date = $('#modal-date').val();
    var inTime = $('#modal-in-time').val();
    var outTime = $('#modal-out-time').val();
    var firstHalf = $('#modal-first-half').val();
    var secondHalf = $('#modal-second-half').val();

    if(!staffid || !date){
      alert('Missing required data');
      return;
    }

    // Validate First Half is required
    if (!firstHalf || firstHalf === '') {
      alert('First Half is required');
      return;
    }

    var postData = {
      staffid: staffid,
      date: date,
      in_time: inTime || '',
      out_time: outTime || '',
      first_half: firstHalf || '',
      second_half: secondHalf || ''
    };

    $.post(admin_url + 'hrd/attendance_bulk_update_by_date', {
      date: date,
      staff_data: [postData]
    }, function(resp){
      if(resp && resp.success){
        alert('Attendance updated successfully');
        $('#update_attendance_modal').modal('hide');
        window.location.reload();
      } else {
        alert('Failed to update: ' + (resp.message || 'Unknown error'));
      }
    }, 'json');
  });
  } catch(e) {
    console.error('Error in attendance page scripts:', e);
  }
});
</script>
