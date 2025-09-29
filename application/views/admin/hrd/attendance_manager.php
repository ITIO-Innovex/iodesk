<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_attendance(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Attendance Entry'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <form method="get" action="" class="mbot15">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Staff</label>
                    <select name="staffid" class="form-control">
                      <option value="">-- All --</option>
                      <?php if (!empty($staff_list)) { foreach ($staff_list as $stf) { 
                        $sid = isset($stf['staffid']) ? (int)$stf['staffid'] : (isset($stf['id'])?(int)$stf['id']:0);
                        $sel = (isset($filters['staffid']) && (int)$filters['staffid']===$sid) ? 'selected="selected"' : '';
                        $name = function_exists('get_staff_full_name') ? get_staff_full_name($sid) : ((isset($stf['firstname'])?$stf['firstname']:'').' '.(isset($stf['lastname'])?$stf['lastname']:''));
                      ?>
                        <option value="<?php echo $sid; ?>" <?php echo $sel; ?>><?php echo e(trim($name)) . ' (#'.$sid.')'; ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Date From</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo isset($filters['date_from']) ? e($filters['date_from']) : '' ; ?>" />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Date To</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo isset($filters['date_to']) ? e($filters['date_to']) : '' ; ?>" />
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Shift</label>
                    <select name="shift_id" class="form-control">
                      <option value="">-- All --</option>
                      <?php if (!empty($shifts)) { foreach ($shifts as $s) { $sel = (isset($filters['shift_id']) && (int)$filters['shift_id']==(int)$s['shift_id'])?'selected="selected"':''; ?>
                        <option value="<?php echo (int)$s['shift_id']; ?>" <?php echo $sel; ?>><?php echo e($s['shift_name']); ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Portion</label>
                    <?php $portion = isset($filters['portion']) ? $filters['portion'] : ''; ?>
                    <select name="portion" class="form-control">
                      <option value="" <?php echo ($portion==='')?'selected="selected"':''; ?>>-- All --</option>
                      <option value="None" <?php echo ($portion==='None')?'selected="selected"':''; ?>>None</option>
                      <option value="Full" <?php echo ($portion==='Full')?'selected="selected"':''; ?>>Full</option>
                      <option value="First Half" <?php echo ($portion==='First Half')?'selected="selected"':''; ?>>First Half</option>
                      <option value="Second Half" <?php echo ($portion==='Second Half')?'selected="selected"':''; ?>>Second Half</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>First Half</label>
                    <?php $fh = isset($filters['first_half']) ? $filters['first_half'] : ''; ?>
                    <select name="first_half" class="form-control">
                      <option value="" <?php echo ($fh==='')?'selected="selected"':''; ?>>-- All --</option>
                      <option value="Absent" <?php echo ($fh==='Absent')?'selected="selected"':''; ?>>Absent</option>
                      <option value="Present" <?php echo ($fh==='Present')?'selected="selected"':''; ?>>Present</option>
                      <option value="HalfDay" <?php echo ($fh==='HalfDay')?'selected="selected"':''; ?>>HalfDay</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Second Half</label>
                    <?php $sh = isset($filters['second_half']) ? $filters['second_half'] : ''; ?>
                    <select name="second_half" class="form-control">
                      <option value="" <?php echo ($sh==='')?'selected="selected"':''; ?>>-- All --</option>
                      <option value="Absent" <?php echo ($sh==='Absent')?'selected="selected"':''; ?>>Absent</option>
                      <option value="Present" <?php echo ($sh==='Present')?'selected="selected"':''; ?>>Present</option>
                      <option value="HalfDay" <?php echo ($sh==='HalfDay')?'selected="selected"':''; ?>>HalfDay</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Late Mark</label>
                    <?php $lm = isset($filters['late_mark']) ? (string)$filters['late_mark'] : ''; ?>
                    <select name="late_mark" class="form-control">
                      <option value="" <?php echo ($lm==='')?'selected="selected"':''; ?>>-- All --</option>
                      <option value="1" <?php echo ($lm==='1')?'selected="selected"':''; ?>>Yes</option>
                      <option value="0" <?php echo ($lm==='0')?'selected="selected"':''; ?>>No</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-1">
                  <label>&nbsp;</label>
                  <div class="form-group">
                    <button type="submit" class="btn btn-default btn-block">Filter</button>
                  </div>
                </div>
                <div class="col-md-1">
                  <label>&nbsp;</label>
                  <div class="form-group">
                    <a href="<?php echo admin_url('hrd/attendance'); ?>" class="btn btn-default btn-block">Reset</a>
                  </div>
                </div>
              </div>
            </form>
            <div class="row mtop10">
              <div class="col-md-12">
                <div class="tw-flex tw-gap-2">
                  <button type="button" class="btn btn-default" onclick="bulkUpdateAttendanceStatus(0)">Mark Open</button>
                  <button type="button" class="btn btn-default" onclick="bulkUpdateAttendanceStatus(1)">Mark Fixed</button>
                </div>
              </div>
            </div>
            <?php if (!empty($attendance_list)) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="desc">
              <thead>
                <th><input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"></th>
                <th>#</th>
                <th>Employee</th>
                <th>Date</th>
                <th>Shift</th>
                <th>In</th>
                <th>Out</th>
                <th>Total Hrs</th>
                <th>Late</th>
                <th>Status</th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($attendance_list as $a) { ?>
                <tr>
                  <td><input type="checkbox" class="row-check" value="<?php echo (int)$a['attendance_id']; ?>"></td>
                  <td><?php echo (int)$a['attendance_id']; ?></td>
                  <td><?php echo function_exists('get_staff_full_name') ? e(get_staff_full_name($a['staffid'])) : (int)$a['staffid']; ?></td>
                  <td><?php echo e($a['entry_date']); ?></td>
                  <td><?php echo (int)$a['shift_id']; ?></td>
                  <td><?php echo e($a['in_time']); ?></td>
                  <td><?php echo e($a['out_time']); ?></td>
                  <td><?php echo e($a['total_hours']); ?></td>
                  <td><?php echo ((int)($a['late_mark']??0)===1)?'<span class="label label-danger">Yes</span>':'<span class="label label-success">No</span>'; ?></td>
                  <td><?php $st = (int)($a['status']??0); echo $st===1?'<span class="label label-success">Fixed</span>':'<span class="label label-warning">Open</span>'; ?></td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#" onclick="view_attendance(this);return false;" data-all='<?php echo json_encode($a, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-eye fa-lg"></i></a>
                      <a href="#" onclick="edit_attendance(this);return false;" data-all='<?php echo json_encode($a, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?><p class="no-margin">No records found.</p><?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="attendance_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/attendanceentry'), ['id' => 'attendance-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="edit-title">Edit Attendance</span><span class="add-title"><?php echo _l('Add Attendance'); ?></span></h4>
      </div>
      <div class="modal-body">
        <div id="additional"></div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Date</label><input type="date" name="entry_date" class="form-control" required></div></div>
          <div class="col-md-4"><div class="form-group"><label>Shift</label>
            <select name="shift_id" class="form-control" required>
              <option value="">-- Select Shift --</option>
              <?php if (!empty($shifts)) { foreach ($shifts as $s) { ?>
                <option value="<?php echo (int)$s['shift_id']; ?>"><?php echo e($s['shift_name']); ?></option>
              <?php } } ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group"><label>Late Mark</label><br><input type="checkbox" name="late_mark" value="1"></div></div>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>In Time</label><input type="datetime-local" name="in_time" class="form-control"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Out Time</label><input type="datetime-local" name="out_time" class="form-control"></div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>First Half</label>
            <select name="first_half" class="form-control">
              <option value="Absent">Absent</option>
              <option value="Present">Present</option>
              <option value="HalfDay">HalfDay</option>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group"><label>Second Half</label>
            <select name="second_half" class="form-control">
              <option value="Absent">Absent</option>
              <option value="Present">Present</option>
              <option value="HalfDay">HalfDay</option>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group"><label>Portion</label>
            <select name="portion" class="form-control">
              <option value="None">None</option>
              <option value="Full">Full</option>
              <option value="First Half">First Half</option>
              <option value="Second Half">Second Half</option>
            </select>
          </div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Total Hours</label><input type="text" name="total_hours" class="form-control" placeholder="e.g. 8.00"></div></div>
          <div class="col-md-8"><div class="form-group"><label>Remarks</label><input type="text" name="remarks" class="form-control" maxlength="255"></div></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="attendance_details" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Attendance Details</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4"><strong>Employee:</strong> <span id="d-emp"></span></div>
          <div class="col-md-4"><strong>Date:</strong> <span id="d-date"></span></div>
          <div class="col-md-4"><strong>Shift:</strong> <span id="d-shift"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-6"><strong>In:</strong> <span id="d-in"></span></div>
          <div class="col-md-6"><strong>Out:</strong> <span id="d-out"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-3"><strong>First Half:</strong> <span id="d-fh"></span></div>
          <div class="col-md-3"><strong>Second Half:</strong> <span id="d-sh"></span></div>
          <div class="col-md-3"><strong>Portion:</strong> <span id="d-portion"></span></div>
          <div class="col-md-3"><strong>Total Hrs:</strong> <span id="d-hrs"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-12"><strong>Remarks:</strong> <span id="d-remarks"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#attendance-form'), {
      entry_date: 'required',
      shift_id: 'required'
    }, manage_attendance);

    $('#attendance_modal').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#attendance_modal input, #attendance_modal textarea').val('');
      $('#attendance_modal select').val('');
      $('#attendance_modal input[name=late_mark]').prop('checked', false);
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  function new_attendance(){ $('#attendance_modal').modal('show'); $('.edit-title').addClass('hide'); }
  function edit_attendance(invoker){ var it=$(invoker).data('all'); $('#additional').append(hidden_input('attendance_id', it.attendance_id)); $('#attendance_modal input[name=entry_date]').val(it.entry_date); $('#attendance_modal select[name=shift_id]').val(it.shift_id); $('#attendance_modal input[name=in_time]').val(it.in_time ? it.in_time.replace(' ', 'T') : ''); $('#attendance_modal input[name=out_time]').val(it.out_time ? it.out_time.replace(' ', 'T') : ''); $('#attendance_modal select[name=first_half]').val(it.first_half||'Absent'); $('#attendance_modal select[name=second_half]').val(it.second_half||'Absent'); $('#attendance_modal select[name=portion]').val(it.portion||'None'); $('#attendance_modal input[name=total_hours]').val(it.total_hours||''); $('#attendance_modal input[name=late_mark]').prop('checked', parseInt(it.late_mark||0,10)===1); $('#attendance_modal input[name=remarks]').val(it.remarks||''); $('#attendance_modal').modal('show'); $('.add-title').addClass('hide'); }
  function view_attendance(invoker){ var it=$(invoker).data('all'); $('#d-emp').text(it.staffid); $('#d-date').text(it.entry_date); $('#d-shift').text(it.shift_id); $('#d-in').text(it.in_time||''); $('#d-out').text(it.out_time||''); $('#d-fh').text(it.first_half||''); $('#d-sh').text(it.second_half||''); $('#d-portion').text(it.portion||''); $('#d-hrs').text(it.total_hours||''); $('#d-remarks').text(it.remarks||''); $('#attendance_details').modal('show'); }
  function manage_attendance(form){ var data=$(form).serialize(); $.post(form.action, data).done(function(){ window.location.reload(); }); return false; }
  function toggleSelectAll(cb){ $('.row-check').prop('checked', cb.checked); }
  function bulkUpdateAttendanceStatus(status){
    var ids = $('.row-check:checked').map(function(){ return this.value; }).get();
    if(ids.length===0){ alert('Select at least one record'); return; }
    $.post(admin_url + 'hrd/bulk_update_attendance_status', {ids: ids, status: status}, function(resp){
      if(resp && resp.success){ window.location.reload(); } else { alert('Failed to update'); }
    }, 'json');
  }
</script>
<?php init_tail(); ?>
</body></html>
