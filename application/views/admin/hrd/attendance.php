<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style media="print">
/* Show only the calendar when printing */
body * { visibility: hidden !important; }
#calendar-section, #calendar-section * { visibility: visible !important; }
/* Let it flow normally for full multi-page print */
#calendar-section { width: 100%; }
/* Ensure full table prints, not clipped by responsive wrapper */
#calendar-section .table-responsive { overflow: visible !important; }
/* Improve pagination */
#calendar-section table { page-break-inside: auto; }
#calendar-section tr    { page-break-inside: avoid; page-break-after: auto; }
#calendar-section thead { display: table-header-group; }
#calendar-section tfoot { display: table-footer-group; }
/* Better spacing/colors in print */
@page { size: auto; margin: 12mm; }
html, body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
<style>
@media print {
  body * {
    visibility: hidden;
  }
  #calendar-section, #calendar-section * {
    visibility: visible;
  }
  #calendar-section {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
</style>
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
                    <label>Month - Year</label>
                    <?php $my = isset($filters['month_year']) ? $filters['month_year'] : ''; ?>
                    <input type="month" name="month_year" class="form-control" value="<?php echo e($my); ?>" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-default">Search</button>
                      <a href="<?php echo admin_url('hrd/attendance'); ?>" class="btn btn-default">Reset</a>
                      <button type="button" class="btn btn-success" onclick="printDiv('calendar-section')"><i class="fa-solid fa-print"></i> Print</button>
                      <?php /*?><button type="button" class="btn btn-danger" onclick="window.print()"><i class="fa-regular fa-file-pdf"></i> Download PDF</button><?php */?>
                    </div>
                  </div>
                </div>
              </div>
            </form>

<script>
function printDiv(divId) { 
  var divContents = document.getElementById(divId).innerHTML;
  var printWindow = window.open('', '', 'height=600,width=800');
  printWindow.document.write('<html><head><title>Print</title>');
  printWindow.document.write('</head><body>');
  printWindow.document.write(divContents);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}
</script>
            <?php if (!empty($calendar)) { $cal = $calendar; ?>
            <div id="calendar-section" class="row" style="margin-bottom:15px;">
              <div class="col-md-12">
                <h4 style="margin-top:0;"><?php echo e(get_staff_full_name()); ?> : <?php echo date('F Y', strtotime(sprintf('%04d-%02d-01', (int)$cal['year'], (int)$cal['month']))); ?></h4>
                <div class="table-responsive">
                  <table class="table table-bordered" style="background:#fff;">
                    <thead>
                      <tr>
                        <th style="width:150px;">Date</th>
                        <th>Day</th>
                        <th>Shift</th>
                        <th>InTime</th>
                        <th>OutTime	</th>
                        <th>First Half</th>
						<th>Second Half</th>
                        <th>Portion</th>
						<th>Tot. Hrs.</th>
                        <th>LateMark</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($cal['days'] as $cell) { 
					  $bgClass="bg-light";
					  if(date('l', strtotime($cell['date']))=='Sunday'){
					  $bgClass="bg-danger";
					  }elseif(date('l', strtotime($cell['date']))=='Saturday'){
					  $bgClass="bg-warning";
					  }
					  ?>
                        <tr class="<?php echo $bgClass;?>">
                          <td><strong><?php echo date('D, d M Y', strtotime($cell['date'])); ?></strong></td>
                          <td ><?php echo date('l', strtotime($cell['date'])); ?></td>
                          <?php
                            if (!empty($cell['items'])) {
                              $shifts = [];$ins=[];$outs=[];$totals=[];$lates=[];$portions=[];$statuses=[];
                              foreach ($cell['items'] as $it) {
                                $shifts[] = (string)(int)($it['shift_id']??'');
                                $ins[] = e($it['in_time']??'');
                                $outs[] = e($it['out_time']??'');
                                $totals[] = e($it['total_hours']??'');
                                $lates[] = ((int)($it['late_mark']??0)===1)?'<span class="label label-danger">Yes</span>':'<span class="label label-success">No</span>';
                                $portions[] = isset($it['position'])?'<span class="label label-default">'.e($it['position']).'</span>':'';
                                $st = (int)($it['status']??0);
                                $statuses[] = $st===1?'<span class="label label-success">Fixed</span>':'<span class="label label-warning">Open</span>';
                              }
                              echo '<td>'.implode('<br>', array_map('e',$shifts)).'</td>';
                              echo '<td>'.implode('<br>', $ins).'</td>';
                              echo '<td>'.implode('<br>', $outs).'</td>';
                              echo '<td>'.implode('<br>', $statuses).'</td>';
							  echo '<td>'.implode('<br>', $statuses).'</td>';
                              echo '<td>'.implode('<br>', $portions).'</td>';
							  echo '<td>'.implode('<br>', $totals).'</td>';
                              echo '<td>'.implode('<br>', $lates).'</td>';
                            } else {
                              echo '<td></td><td></td><td></td><td></td><td><span class="label label-success">No</span></td><td></td><td></td><td>-</td>';
                            }
                          ?>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php } ?>
            
            
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php /*?>
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
          <div class="col-md-4"><div class="form-group"><label>Date</label><input type="date" name="attendance_date" class="form-control" required></div></div>
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
</div><?php */?>

<script>
  <?php /*?>window.addEventListener('load', function () {
    appValidateForm($("body").find('#attendance-form'), {
      attendance_date: 'required',
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
  }<?php */?>
  /* Print handled by CSS to show only calendar */
</script>
<?php init_tail(); ?>

</body></html>
