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
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>" />
                  </div>
                </div>
                <div class="col-md-4">
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
                <div class="col-md-5">
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
                  <button type="button" class="btn btn-primary" id="apply-bulk">Apply to Selected</button>
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
                  <th>First Half</th>
                  <th>Second Half</th>
                  <th>Portion</th>
				  <th>Tot. Hrs.</th>
                  <th>LateMark</th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($staff)) { foreach ($staff as $s) { $sid=(int)$s['staffid']; $att = $attendance_map[$sid] ?? null; ?>
                  <tr>
                    <td><input type="checkbox" class="row-check" value="<?php echo (int)$sid; ?>"></td>
                    <td><?php echo e(($s['firstname']??'') . ' ' . ($s['lastname']??'')); ?></td>
                    <td><?php echo e($date); ?></td>
					<td><?php echo date('D', strtotime(e($date))); ?></td>
                    <td>
                      <div class="input-group input-group-sm" style="max-width:200px;">
                        <input type="time" class="form-control in-time" data-staffid="<?php echo (int)$sid; ?>" value="<?php echo e(isset($att['in_time'])?date('H:i', strtotime($att['in_time'])):''); ?>">
                       
                      </div>
                    </td>
                    <td>
                      <div class="input-group input-group-sm" style="max-width:200px;">
                        <input type="time" class="form-control out-time" data-staffid="<?php echo (int)$sid; ?>" value="<?php echo e(isset($att['out_time'])?date('H:i', strtotime($att['out_time'])):''); ?>">
                        
                      </div>
                    </td>
                    <td>
                      <select class="form-control fh-select" data-staffid="<?php echo (int)$sid; ?>">
                        <option value="">First Half</option>
                        <?php if (!empty($attendance_statuses)) { 
						foreach ($attendance_statuses as $st) { 
						$sel = isset($att['first_half']) && (string)$att['first_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td>
                      <select class="form-control sh-select" data-staffid="<?php echo (int)$sid; ?>">
                        <option value="">Second Half</option>
                        <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { $sel = isset($att['second_half']) && (string)$att['second_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td><?php echo e($att['position'] ?? ''); ?></td>
					<td><?php echo e($att['total_hours'] ?? ''); ?></td>
					<td><?php echo e($att['late_mark'] ?? ''); ?></td>
                    <td>
   <a class="btn btn-default btn-sm open-update" 
   data-staffid="<?php echo (int)$sid; ?>"
   data-date="<?php echo e($date); ?>"
   data-in_time="<?php echo isset($att['in_time']) ? date('H:i', strtotime($att['in_time'])) : '';?>"
   data-out_time="<?php echo isset($att['out_time']) ? date('H:i', strtotime($att['out_time'])) : '';?>"
   data-first_half="<?php echo isset($att['first_half']) ? (string)$att['first_half'] : '';?>"
   data-second_half="<?php isset($att['second_half']) ? (string)$att['second_half'] : '';?>"
   data-staff_name="<?php echo  e(($s['firstname']??'') . ' ' . ($s['lastname']??''));?>"><i class="fa-solid fa-pen-to-square" title="Edit"></i></a>
                    </td>
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
                <label>First Half</label>
                <select id="modal-first-half" name="first_half" class="form-control">
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
 $(document).on('click', '.open-update', function(){
  
  let data = $(this).data();
  openUpdateModal(data.staffid, data.date, data);
  return false;
  });
</script>
<script>
$(document).ready(function(){
  // Select all checkbox
  var selAll = document.getElementById('select-all');
  if(selAll){ 
    selAll.addEventListener('change', function(){
      var checked = this.checked;
      document.querySelectorAll('.row-check').forEach(function(cb){ cb.checked = checked; });
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
      
      checkedRows.forEach(function(checkbox){
        var staffid = checkbox.value;
        var $row = $(checkbox).closest('tr');
        var inTime = $row.find('input.in-time[data-staffid="'+staffid+'"]').val();
        var outTime = $row.find('input.out-time[data-staffid="'+staffid+'"]').val();
        var fh = $row.find('select.fh-select[data-staffid="'+staffid+'"]').val();
        var sh = $row.find('select.sh-select[data-staffid="'+staffid+'"]').val();
        
        staffData.push({
          staffid: staffid,
          in_time: inTime || '',
          out_time: outTime || '',
          first_half: fh || '',
          second_half: sh || ''
        });
      });
      
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
});
</script>
</body></html>


