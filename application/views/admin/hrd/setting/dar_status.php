<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_dar_status(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> New DAR Status
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($dar_statuses) > 0) { ?>
              <table class="table dt-table" data-order-col="1" data-order-type="asc">
                <thead>
                  <th>Title</th>
                  <th><?php echo _l('status'); ?></th>
                  <th><?php echo _l('options'); ?></th>
                </thead>
                <tbody>
                  <?php foreach ($dar_statuses as $status) { ?>
                    <tr>
                      <td>
                        <a href="#"
                           onclick="edit_dar_status(this,<?php echo e($status['id']); ?>);return false;"
                           data-title="<?php echo e($status['title']); ?>">
                          <?php echo e($status['title']); ?>
                        </a>
                      </td>
                      <td>
                        <a href="javascript:void(0);" onclick="toggleDarStatus(<?php echo (int) $status['id']; ?>, <?php echo (int) $status['status']; ?>)" id="status-label-<?php echo (int) $status['id']; ?>">
                          <?php if ((int) $status['status'] === 1) { ?>
                            <span class="label label-success">Active</span>
                          <?php } else { ?>
                            <span class="label label-danger">Deactive</span>
                          <?php } ?>
                        </a>
                      </td>
                      <td>
                        <div class="tw-flex tw-items-center tw-space-x-3">
                          <a href="#"
                             onclick="edit_dar_status(this,<?php echo e($status['id']); ?>);return false;"
                             data-title="<?php echo e($status['title']); ?>"
                             class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                          </a>
                          <a href="<?php echo admin_url('hrd/delete_dar_status/' . (int) $status['id']); ?>"
                             class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                            <i class="fa-regular fa-trash-can fa-lg"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="no-margin">No DAR statuses found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dar-status-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/darstatus'), ['id' => 'dar-status-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit DAR Status</span>
          <span class="add-title">Add New DAR Status</span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Title'); ?>
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
  appValidateForm($("body").find('#dar-status-form'), {
    name: 'required'
  }, manage_dar_status);

  $('#dar-status-modal').on('hidden.bs.modal', function () {
    $('#additional').html('');
    $('#dar-status-form input[name="name"]').val('');
    $('.add-title').removeClass('hide');
    $('.edit-title').removeClass('hide');
  });
});

function new_dar_status() {
  $('#dar-status-modal').modal('show');
  $('.edit-title').addClass('hide');
}

function edit_dar_status(invoker, id) {
  $('#additional').append(hidden_input('id', id));
  $('#dar-status-form input[name="name"]').val($(invoker).data('title'));
  $('#dar-status-modal').modal('show');
  $('.add-title').addClass('hide');
}

function manage_dar_status(form) {
  $.post(form.action, $(form).serialize()).done(function () {
    window.location.reload();
  });
  return false;
}

function toggleDarStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_dar_status/' + id, {status: currentStatus == 1 ? 0 : 1}, function (response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleDarStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body>
</html>
