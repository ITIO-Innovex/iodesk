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
                    <label>Month</label>
                    <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>" />
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label>Staff</label>
                    <select name="staff_id" class="form-control">
                      <option value="">-- Select Staff --</option>
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
                      <button type="submit" class="btn btn-default" title="Search"><i class="fa fa-search"></i></button>
                      <a href="<?php echo admin_url('hrd/manage_attendance_master'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-xmark" ></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($staff_filter)) { ?>
            <div class="row" style="margin-bottom:10px;">
              <div class="col-md-12">
                <div class="tw-flex tw-items-center tw-gap-2">
                  <button type="button" class="btn btn-primary" id="apply-bulk-month">Apply to Selected</button>
                </div>
              </div>
            </div>

            <table class="table dt-table" data-order-col="0" data-order-type="asc">
              <thead>
                <tr>
                  <th><input type="checkbox" id="select-all"></th>
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
                <?php if (!empty($dates)) { foreach ($dates as $dt) { $att = $attendance_map[$dt] ?? null; ?>
                  <tr>
                    <td><input type="checkbox" class="row-check" value="<?php echo e($dt); ?>"></td>
                    <td><?php echo e($dt); ?></td>
                    <td><?php echo date('D', strtotime(e($dt))); ?></td>
                    <td>
                      <div class="input-group input-group-sm" style="max-width:200px;">
                        <input type="time" class="form-control in-time" data-date="<?php echo e($dt); ?>" value="<?php echo e(isset($att['in_time'])?date('H:i', strtotime($att['in_time'])):''); ?>">
                      </div>
                    </td>
                    <td>
                      <div class="input-group input-group-sm" style="max-width:200px;">
                        <input type="time" class="form-control out-time" data-date="<?php echo e($dt); ?>" value="<?php echo e(isset($att['out_time'])?date('H:i', strtotime($att['out_time'])):''); ?>">
                      </div>
                    </td>
                    <td>
                      <select class="form-control fh-select" data-date="<?php echo e($dt); ?>">
                        <option value="">First Half</option>
                        <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { 
                        $sel = isset($att['first_half']) && (string)$att['first_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td>
                      <select class="form-control sh-select" data-date="<?php echo e($dt); ?>">
                        <option value="">Second Half</option>
                        <?php if (!empty($attendance_statuses)) { foreach ($attendance_statuses as $st) { 
                        $sel = isset($att['second_half']) && (string)$att['second_half']===(string)$st['id'] ? 'selected' : ''; ?>
                          <option style=" background:<?php echo $st['color']; ?>" value="<?php echo (int)$st['id']; ?>" <?php echo $sel; ?>><?php echo e($st['title']); ?></option>
                        <?php } } ?>
                      </select>
                    </td>
                    <td><?php echo e($att['position'] ?? ''); ?></td>
                    <td><?php echo e($att['total_hours'] ?? ''); ?></td>
                    <td><?php echo e($att['late_mark'] ?? ''); ?></td>
                    <td>
                      <a class="btn btn-default btn-sm open-update" 
                         data-staffid="<?php echo (int)$staff_filter; ?>"
                         data-date="<?php echo e($dt); ?>"
                         data-in_time="<?php echo isset($att['in_time']) ? date('H:i', strtotime($att['in_time'])) : '';?>"
                         data-out_time="<?php echo isset($att['out_time']) ? date('H:i', strtotime($att['out_time'])) : '';?>"
                         data-first_half="<?php echo isset($att['first_half']) ? (string)$att['first_half'] : '';?>"
                         data-second_half="<?php echo isset($att['second_half']) ? (string)$att['second_half'] : '';?>"
                         data-staff_name="">
                         <i class="fa-solid fa-pen-to-square" title="Edit"></i></a>
                    </td>
                  </tr>
                <?php } } ?>
              </tbody>
            </table>
            <?php } else { ?>
              <div class="alert alert-info">Select a staff to view and manage attendance for the selected month.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
// Select all rows
$('#select-all').on('change', function(){
  $('.row-check').prop('checked', $(this).prop('checked'));
});

// Bulk apply for month (re-uses existing by-date endpoint by sending per-row payloads)
$('#apply-bulk-month').on('click', function(){
  var staffId = "<?php echo (int)$staff_filter; ?>";
  if (!staffId) { alert('Select a staff first.'); return; }

  var selectedDates = $('.row-check:checked').map(function(){ return $(this).val(); }).get();
  if (selectedDates.length === 0) { alert('Select at least one row.'); return; }

  var payloads = [];
  selectedDates.forEach(function(dt){
    var inTime   = $('.in-time[data-date="'+dt+'"]').val() || '';
    var outTime  = $('.out-time[data-date="'+dt+'"]').val() || '';
    var firstH   = $('.fh-select[data-date="'+dt+'"]').val() || '';
    var secondH  = $('.sh-select[data-date="'+dt+'"]').val() || '';
    payloads.push({staffid: staffId, date: dt, in_time: inTime, out_time: outTime, first_half: firstH, second_half: secondH});
  });

  // Build by-date compatible payload
  var staffData = {};
  payloads.forEach(function(p){
    staffData[p.staffid] = {in_time: p.in_time, out_time: p.out_time, first_half: p.first_half, second_half: p.second_half};
    // Send per-date; invoke endpoint once per date to stay compatible
    $.post(admin_url + 'hrd/attendance_bulk_update_by_date', {date: p.date, staff_data: { [p.staffid]: staffData[p.staffid] }}, function(resp){
      // noop
    });
  });

  alert('Bulk update triggered for selected dates.');
  setTimeout(function(){ location.reload(); }, 800);
});
</script>
<?php hooks()->do_action('app_admin_footer'); ?>

