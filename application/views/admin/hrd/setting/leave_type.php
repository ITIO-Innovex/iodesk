<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  </style>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa-solid fa-calendar-days menu-icon tw-mr-2"></i> Leave Type</h4>
    <div class="row">
      <div class="col-md-12">
        <?php /*?><div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_leave_type(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Leave Type'); ?> </a> </div><?php */?>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($leave_types) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Leave Type Title</th>
                                <th>Remark</th>
                                <th><?php echo _l('status'); ?></th>
                               <?php /*?> <th><?php echo _l('options'); ?></th><?php */?>
                            </thead>
                            <tbody>
                                <?php foreach ($leave_types as $leave_type) { ?>
                                <tr>
                                    <td>
                                        <a href="#"
                                            onclick="edit_leave_type(this,<?php echo e($leave_type['id']); ?>);return false;"
                                            data-title="<?php echo e($leave_type['title']); ?>"
                                            data-remark="<?php echo isset($leave_type['remark']) ? e($leave_type['remark']) : ''; ?>"
                                        ><?php echo e($leave_type['title']); ?></a><br />
                                    </td>
                                    <td><?php echo isset($leave_type['remark']) ? e($leave_type['remark']) : ''; ?></td>
                                    <td>
<a href="javascript:void(0);"  id="status-label-<?php echo $leave_type['id']; ?>">
<?php /*?>onclick="toggleLeaveTypeStatus(<?php echo $leave_type['id']; ?>, <?php echo $leave_type['status']; ?>)"<?php */?>
                                        <?php if ($leave_type['status']) { ?>
                                            <span class="label label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-danger">Deactive</span>
                                        <?php } ?>
                                        </a>
                                    </td>
                                    <?php /*?><td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_leave_type(this,<?php echo e($leave_type['id']); ?>);return false;"
                                                data-title="<?php echo e($leave_type['title']); ?>"
                                                data-remark="<?php echo isset($leave_type['remark']) ? e($leave_type['remark']) : ''; ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <a href="<?php echo admin_url('hrd/delete_leave_type/' . $leave_type['id']); ?>"
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
            <p class="no-margin">No leave types found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="leave_type" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('hrd/leavetype'), ['id' => 'leave-type-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Leave Type</span> <span class="add-title"><?php echo _l('Add New Leave Type'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Leave Type Title'); ?> 
            <div class="form-group">
                <label for="remark">Remark</label>
                <textarea name="remark" id="remark" class="form-control" rows="3"></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <!-- /.modal-content -->
    <?php echo form_close(); ?> </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#leave-type-form'), {
        name: 'required'
    }, manage_leave_type);
    $('#leave_type').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#leave_type input[name="name"]').val('');
        $('#leave_type textarea[name="remark"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

// Create new leave type
function new_leave_type() {
    $('#leave_type').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit leave type function which init the data to the modal
function edit_leave_type(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#leave_type input[name="name"]').val($(invoker).data('title'));
    $('#leave_type textarea[name="remark"]').val($(invoker).data('remark'));
    $('#leave_type').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for leave type
function manage_leave_type(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}

function toggleLeaveTypeStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_leave_type/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleLeaveTypeStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>

