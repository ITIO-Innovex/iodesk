<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa-solid fa-calendar-days menu-icon tw-mr-2 "></i>  Attendance Status</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <?php /*?><a href="#" onclick="new_attendance_status(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Attendance Status'); ?>
          </a><?php */?>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($attendance_statuses) && count($attendance_statuses) > 0) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="asc">
              <thead>
                <th>Title</th>
                <th>Remark</th>
                <th><?php echo _l('status'); ?></th>
                <?php /*?><th><?php echo _l('options'); ?></th><?php */?>
              </thead>
              <tbody>
                <?php foreach ($attendance_statuses as $st) { ?>
				<?php $hex = isset($st['color']) && $st['color'] !== '' ? $st['color'] : '#000000'; ?>
                <tr>
                  <td>
                    <span class="label label-inline" style="background:<?php echo e($hex); ?>"><?php echo e($st['title']); ?></span>
                  </td>
                  <td><?php echo e($st['remark']); ?></td>
                 
                  <td>
                    <a href="javascript:void(0);" id="status-label-<?php echo $st['id']; ?>">
<?php /*?>onclick="toggleAttendanceStatus(<?php echo $st['id']; ?>, <?php echo (int)$st['status']; ?>)"<?php */?>
                      <?php if (!empty($st['status'])) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <?php /*?><td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_attendance_status(this,<?php echo e($st['id']); ?>);return false;"
                         data-title="<?php echo e($st['title']); ?>"
                         data-remark="<?php echo e($st['remark']); ?>"
                         data-color="<?php echo e(isset($st['color']) ? $st['color'] : '#000000'); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_attendance_status/' . $st['id']); ?>"
                         class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                        <i class="fa-regular fa-trash-can fa-lg"></i>
                      </a>
                    </div>
                  </td><?php */?>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
              <p class="no-margin">No records found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="attendance_status" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/attendancestatus'), ['id' => 'attendance-status-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit Attendance Status</span>
          <span class="add-title"><?php echo _l('Add New Attendance Status'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Title'); ?>
            <?php echo render_input('remark', 'Remark'); ?>
            <div class="form-group">
              <label for="color">Color</label>
              <input type="color" name="color" id="color" class="form-control" value="#000000" />
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

<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#attendance-status-form'), {
      name: 'required'
    }, manage_attendance_status);
    $('#attendance_status').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#attendance_status input[name="name"]').val('');
      $('#attendance_status input[name="remark"]').val('');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  function new_attendance_status() {
    $('#attendance_status').modal('show');
    $('.edit-title').addClass('hide');
  }

  function edit_attendance_status(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#attendance_status input[name="name"]').val($(invoker).data('title'));
    $('#attendance_status input[name="remark"]').val($(invoker).data('remark'));
    var hex = $(invoker).data('color') || '#000000';
    $('#attendance_status input[name="color"]').val(hex);
    $('#attendance_status').modal('show');
    $('.add-title').addClass('hide');
  }

  function manage_attendance_status(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function () {
      window.location.reload();
    });
    return false;
  }

  function toggleAttendanceStatus(id, currentStatus) {
    $.post(admin_url + 'hrd/toggle_attendance_status/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
      if (response.success) {
        var label = $('#status-label-' + id + ' span');
        if (response.new_status == 1) {
          label.removeClass('label-danger').addClass('label-success').text('Active');
        } else {
          label.removeClass('label-success').addClass('label-danger').text('Deactive');
        }
        $('#status-label-' + id).attr('onclick', 'toggleAttendanceStatus(' + id + ', ' + response.new_status + ')');
      } else {
        alert('Failed to update status');
      }
    }, 'json');
  }
</script>
<?php init_tail(); ?>
</body></html>
