<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <?php /*?><a href="#" onclick="new_leave(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Leave Application'); ?>
          </a><?php */?>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($leave_list)) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
              <thead>
                <th>#</th>
                <th>Employee</th>
                <th>From</th>
                <th>To</th>
                <th>Type</th>
                <th>For</th>
                <th>Status</th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($leave_list as $l) { $st=(int)($l['leave_status']??0); ?>
                <tr>
                  <td><?php echo (int)$l['leave_id']; ?></td>
                  <td><?php echo function_exists('get_staff_full_name') ? e(get_staff_full_name($l['staffid'])) : (int)$l['staffid']; ?></td>
                  <td><?php echo e($l['from_date']); ?></td>
                  <td><?php echo e($l['to_date']); ?></td>
                  <td><?php echo e($l['leave_type']); ?></td>
                  <td><?php echo ((int)$l['leave_for']===2)?'Half Day':'Full Day'; ?></td>
                  <td><?php $label='Pending';$class='warning'; if($st===1){$label='Approved';$class='success';}elseif($st===2){$label='Rejected';$class='danger';} ?><span class="label label-<?php echo $class; ?>"><?php echo $label; ?></span></td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#" onclick="view_leave(this);return false;" data-all='<?php echo json_encode($l, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-eye fa-lg"></i></a>
                      <a href="#" onclick="edit_leave(this);return false;" data-all='<?php echo json_encode($l, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>
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

<div class="modal fade" id="leave_application" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/leaveapplication'), ['id' => 'leave-application-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="edit-title">Edit Leave</span><span class="add-title"><?php echo _l('Add New Leave'); ?></span></h4>
      </div>
      <div class="modal-body">
        <div id="additional"></div>
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>From Date</label><input type="date" name="from_date" class="form-control" required></div></div>
          <div class="col-md-6"><div class="form-group"><label>To Date</label><input type="date" name="to_date" class="form-control" required></div></div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group"><label>Leave Type</label>
              <select name="leave_type" class="form-control" required>
                <option value="">-- Select Leave Type --</option>
                <?php if (!empty($leave_types)) { foreach ($leave_types as $t) { ?>
                  <option value="<?php echo e($t['title']); ?>"><?php echo e($t['title']); ?></option>
                <?php } } ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group"><label>Leave For</label>
              <select name="leave_for" class="form-control" required>
                <option value="1">Full Day</option>
                <option value="2">Half Day</option>
              </select>
            </div>
          </div>
        </div>
        <div class="form-group"><label>Reason</label><textarea name="leave_reson" class="form-control" rows="4" required></textarea></div>
        <div class="form-group"><label>Reply (optional)</label><textarea name="leave_reply" class="form-control" rows="3"></textarea></div>
        <div class="form-group"><label>Status</label>
          <select name="leave_status" class="form-control">
            <option value="0">Pending</option>
            <option value="1">Approved</option>
            <option value="2">Rejected</option>
          </select>
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

<div class="modal fade" id="leave_details" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Leave Details</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6"><strong>Employee:</strong> <span id="d-employee"></span></div>
          <div class="col-md-3"><strong>From:</strong> <span id="d-from"></span></div>
          <div class="col-md-3"><strong>To:</strong> <span id="d-to"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-4"><strong>Type:</strong> <span id="d-type"></span></div>
          <div class="col-md-4"><strong>For:</strong> <span id="d-for"></span></div>
          <div class="col-md-4"><strong>Status:</strong> <span id="d-status" class="label"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-12"><strong>Reason:</strong>
            <div id="d-reason" class="tw-whitespace-pre-line"></div>
          </div>
        </div>
        <div class="row mtop10">
          <div class="col-md-12"><strong>Reply:</strong>
            <div id="d-reply" class="tw-whitespace-pre-line"></div>
          </div>
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
    appValidateForm($("body").find('#leave-application-form'), {from_date:'required',to_date:'required',leave_type:'required',leave_for:'required',leave_reson:'required'}, manage_leave);
    $('#leave_application').on("hidden.bs.modal", function () { $('#additional').html(''); $('#leave_application input, #leave_application textarea').val(''); $('#leave_application select').val(''); $('.add-title').removeClass('hide'); $('.edit-title').removeClass('hide'); });
  });
  function new_leave(){ $('#leave_application').modal('show'); $('.edit-title').addClass('hide'); }
  function edit_leave(invoker){ var it=$(invoker).data('all'); $('#additional').append(hidden_input('leave_id', it.leave_id)); $('#leave_application input[name=from_date]').val(it.from_date); $('#leave_application input[name=to_date]').val(it.to_date); $('#leave_application select[name=leave_type]').val(it.leave_type); $('#leave_application select[name=leave_for]').val(it.leave_for); $('#leave_application textarea[name=leave_reson]').val(it.leave_reson); $('#leave_application textarea[name=leave_reply]').val(it.leave_reply||''); $('#leave_application select[name=leave_status]').val(it.leave_status||0); $('#leave_application').modal('show'); $('.add-title').addClass('hide'); }
  function view_leave(invoker){ var it=$(invoker).data('all'); $('#d-employee').text(it.staffid); $('#d-from').text(it.from_date); $('#d-to').text(it.to_date); $('#d-type').text(it.leave_type); $('#d-for').text((parseInt(it.leave_for,10)===2)?'Half Day':'Full Day'); var st=parseInt(it.leave_status||0,10); var label='Pending', cls='label-warning'; if(st===1){label='Approved';cls='label-success';} else if(st===2){label='Rejected';cls='label-danger';} $('#d-status').removeClass('label-warning label-success label-danger').addClass(cls).text(label); $('#d-reason').text(it.leave_reson||''); $('#d-reply').text(it.leave_reply||''); $('#leave_details').modal('show'); }
  function manage_leave(form){ var data=$(form).serialize(); $.post(form.action, data).done(function(){ window.location.reload(); }); return false; }
</script>
<?php init_tail(); ?>
</body></html>
