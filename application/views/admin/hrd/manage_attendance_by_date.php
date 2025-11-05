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
                <div class="col-md-9">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-default">Search</button>
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
                  <th>Employee Name</th>
                  <th>Date</th>
                  <th>InTime</th>
                  <th>OutTime</th>
                  <th>First Half</th>
                  <th>Second Half</th>
                  <th>Portion</th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($staff)) { foreach ($staff as $s) { $sid=(int)$s['staffid']; $att = $attendance_map[$sid] ?? null; ?>
                  <tr>
                    <td><input type="checkbox" class="row-check" value="<?php echo (int)$sid; ?>"></td>
                    <td><?php echo e(($s['firstname']??'') . ' ' . ($s['lastname']??'')); ?></td>
                    <td><?php echo e($date); ?></td>
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
                        <option value="">-- First Half --</option>
                        <?php if (!empty($attendance_statuses)) { 
						foreach ($attendance_statuses as $st) { 
						$sel = isset($att['first_half']) && (string)$att['first_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td>
                      <select class="form-control sh-select" data-staffid="<?php echo (int)$sid; ?>">
                        <option value="">-- Second Half --</option>
                        <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { $sel = isset($att['second_half']) && (string)$att['second_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td><?php echo e($att['position'] ?? ''); ?></td>
                    <td>-</td>
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
<script>
(function(){
  var selAll = document.getElementById('select-all');
  if(selAll){ selAll.addEventListener('change', function(){
    var checked = this.checked;
    document.querySelectorAll('.row-check').forEach(function(cb){ cb.checked = checked; });
  });}

  var applyBtn = document.getElementById('apply-bulk');
  if(applyBtn){ applyBtn.addEventListener('click', function(){
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
  });}

  // Save single row In/Out time
  window.saveInOut = function(staffid, el){
    var $tr = $(el).closest('tr');
    var inTime = $tr.find('input.in-time[data-staffid="'+staffid+'"]').val();
    var outTime = $tr.find('input.out-time[data-staffid="'+staffid+'"]').val();
    $.post(admin_url + 'hrd/attendance_update_inout_by_date', {staffid: staffid, date: '<?php echo e($date); ?>', in_time: inTime, out_time: outTime}, function(resp){
      if(!(resp && resp.success)){ alert('Failed to save'); }
    }, 'json');
  }
})();
</script>
<?php init_tail(); ?>
</body></html>


