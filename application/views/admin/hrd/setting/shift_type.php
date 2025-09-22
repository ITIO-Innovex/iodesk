<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4"> 
          <a href="#" onclick="new_shift_type(); return false;" class="btn btn-primary"> 
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Shift Type'); ?> 
          </a> 
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($shift_types) && count($shift_types) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
              <thead>
                <th>Shift Type Title</th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($shift_types as $st) { ?>
                <tr>
                  <td>
                    <a href="#"
                       onclick="edit_shift_type(this,<?php echo e($st['id']); ?>);return false;"
                       data-title="<?php echo e($st['title']); ?>"
                    ><?php echo e($st['title']); ?></a><br />
                  </td>
                  <td>
                    <a href="javascript:void(0);" onclick="toggleShiftTypeStatus(<?php echo $st['id']; ?>, <?php echo (int)$st['status']; ?>)" id="status-label-<?php echo $st['id']; ?>">
                      <?php if (!empty($st['status'])) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_shift_type(this,<?php echo e($st['id']); ?>);return false;"
                         data-title="<?php echo e($st['title']); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_shift_type/' . $st['id']); ?>"
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
              <p class="no-margin">No shift types found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="shift_type" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('hrd/shifttype'), ['id' => 'shift-type-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Shift Type</span> <span class="add-title"><?php echo _l('Add New Shift Type'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Shift Type Title'); ?> 
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
    appValidateForm($("body").find('#shift-type-form'), {
        name: 'required'
    }, manage_shift_type);
    $('#shift_type').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#shift_type input[name="name"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

// Create new shift type
function new_shift_type() {
    $('#shift_type').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit shift type function which init the data to the modal
function edit_shift_type(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#shift_type input[name="name"]').val($(invoker).data('title'));
    $('#shift_type').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for shift type
function manage_shift_type(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function () {
        window.location.reload();
    });
    return false;
}

function toggleShiftTypeStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_shift_type/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleShiftTypeStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>
