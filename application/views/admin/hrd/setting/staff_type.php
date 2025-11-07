<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
</style>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  Staff Type <i class="fa-solid fa-circle-info" title="For count Saturday on attendance" style=" color:khaki;"></i></span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span></h4>
    <div class="row">
      <div class="col-md-12">
        <?php /*?><div class="tw-mb-2 sm:tw-mb-4"> 
          <a href="#" onclick="new_staff_type(); return false;" class="btn btn-primary"> 
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Staff Type'); ?> 
          </a> 
        </div><?php */?>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($staff_types) && count($staff_types) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
              <thead>
                <th>Staff Type Title</th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($staff_types as $st) { ?>
                <tr>
                  <td>
                    <a href="#"
                       onclick="edit_staff_type(this,<?php echo e($st['id']); ?>);return false;"
                       data-title="<?php echo e($st['title']); ?>"
                    ><?php echo e($st['title']); ?></a><br />
                  </td>
                  <td>
                    <a href="javascript:void(0);" onclick="toggleStaffTypeStatus(<?php echo $st['id']; ?>, <?php echo (int)$st['status']; ?>)" id="status-label-<?php echo $st['id']; ?>">
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
                         onclick="edit_staff_type(this,<?php echo e($st['id']); ?>);return false;"
                         data-title="<?php echo e($st['title']); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <?php /*?><a href="<?php echo admin_url('hrd/delete_staff_type/' . $st['id']); ?>"
                         class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                        <i class="fa-regular fa-trash-can fa-lg"></i>
                      </a><?php */?>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
              <p class="no-margin">No staff types found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="staff_type" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('hrd/stafftype'), ['id' => 'staff-type-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Staff Type</span> <span class="add-title"><?php echo _l('Add New Staff Type'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Staff Type Title'); ?> 
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
    appValidateForm($("body").find('#staff-type-form'), {
        name: 'required'
    }, manage_staff_type);
    $('#staff_type').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#staff_type input[name="name"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

// Create new staff type
function new_staff_type() {
    $('#staff_type').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit staff type function which init the data to the modal
function edit_staff_type(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#staff_type input[name="name"]').val($(invoker).data('title'));
    $('#staff_type').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for staff type
function manage_staff_type(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function () {
        window.location.reload();
    });
    return false;
}

function toggleStaffTypeStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_staff_type/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleStaffTypeStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>

