<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
      <i class="fa-solid fa-calendar-days menu-icon tw-mr-2"></i> 
      Attendance Request
     
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($requests) > 0) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
              <thead>
                <th>Date</th>
                <th>Requested In Time</th>
                <th>Requested Out Time</th>
                <th>Remarks</th>
                <th>Update Remark</th>
				<th>Name</th>
				<th>Employee Code</th>
                <th>Added On</th>
                <th>Updated On</th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($requests as $req) { ?>
                <tr>
                  <td><?php echo e($req['entry_date'] ?? ''); ?></td>
                  <td><?php echo e($req['in_time'] ?? '-'); ?></td>
                  <td><?php echo e($req['out_time'] ?? '-'); ?></td>
                  <td><?php echo e($req['remarks'] ?? '-'); ?></td>
                  <td><?php echo e($req['update_remark'] ?? '-'); ?></td>
                  <td><?php echo e($req['firstname'] ?? ''); ?></td>
				  <td><?php echo e($req['employee_code'] ?? ''); ?></td>
				  <td><?php echo e($req['addedon'] ?? ''); ?></td>
                  <td><?php echo e($req['updatedon'] ?? '-'); ?></td>
                  <td>
                    <?php 
                      $status = (int)($req['status'] ?? 1);
                      if ($status == 1) { // Only show action for pending requests
                    ?>
                    <a href="#"
                       onclick="edit_attendance_request(this,<?php echo e($req['id']); ?>);return false;"
                       data-id="<?php echo e($req['id']); ?>"
                       data-staffid="<?php echo e($req['staffid']); ?>"
                       data-entry_date="<?php echo e($req['entry_date']); ?>"
                       data-in_time="<?php echo e($req['in_time'] ?? ''); ?>"
                       data-out_time="<?php echo e($req['out_time'] ?? ''); ?>"
                       data-remarks="<?php echo e($req['remarks'] ?? ''); ?>"
                       class="btn btn-default btn-sm">Update</a>
                    <?php } else { ?>
                      <span class="label label-<?php echo $status == 2 ? 'success' : 'danger'; ?>">
                        <?php echo $status == 2 ? 'Approved' : 'Rejected'; ?>
                      </span>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                No pending attendance requests found for this staff.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Update Request Modal -->
<div class="modal fade" id="attendance_request_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/attendance_request_update'), ['id' => 'attendance-request-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Update Attendance Request</h4>
      </div>
      <div class="modal-body">
        <div id="additional"></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Date</label>
              <input type="date" id="req-entry-date" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Requested In Time</label>
              <input type="time" id="req-in-time" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Requested Out Time</label>
              <input type="time" id="req-out-time" class="form-control" readonly>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Request Remarks</label>
              <textarea id="req-remarks" class="form-control" rows="2" readonly></textarea>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Update Remark <span class="text-danger">*</span></label>
              <textarea name="update_remark" id="update-remark" class="form-control" rows="3" required></textarea>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label>Status <span class="text-danger">*</span></label>
              <select name="status" id="req-status" class="form-control" required>
                <option value="1">Pending</option>
                <option value="2">Approved</option>
                <option value="0">Rejected</option>
              </select>
            </div>
          </div>
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

<?php init_tail(); ?>
<script>
$(function(){
  appValidateForm($("#attendance-request-form"), {
    update_remark: 'required',
    status: 'required'
  }, manage_attendance_request);

  $('#attendance_request_modal').on("hidden.bs.modal", function () {
    $('#additional').html('');
    $('#req-entry-date').val('');
    $('#req-in-time').val('');
    $('#req-out-time').val('');
    $('#req-remarks').val('');
    $('#update-remark').val('');
    $('#req-status').val('1');
  });
});

function edit_attendance_request(invoker, id) {
  $('#additional').append(hidden_input('request_id', id));
  $('#req-entry-date').val($(invoker).data('entry_date'));
  $('#req-in-time').val($(invoker).data('in_time'));
  $('#req-out-time').val($(invoker).data('out_time'));
  $('#req-remarks').val($(invoker).data('remarks'));
  $('#req-status').val('1');
  $('#attendance_request_modal').modal('show');
}

function manage_attendance_request(form) {
  var data = $(form).serialize();
  var url = form.action;
  $.post(url, data, function(response) {
    if (response && response.success) {
      window.location.reload();
    } else {
      alert('Failed to update request: ' + (response.message || 'Unknown error'));
    }
  }, 'json');
  return false;
}
</script>
</body></html>

