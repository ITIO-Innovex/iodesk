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
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_employee_type(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Employee Type'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($employee_types) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Employee Type Title</th>
                                <th><?php echo _l('status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($employee_types as $employee_type) { ?>
                                <tr>
                                    <td>
                                        <a href="#"
                                            onclick="edit_employee_type(this,<?php echo e($employee_type['id']); ?>);return false;"
                                            data-title="<?php echo e($employee_type['title']); ?>"
                                        ><?php echo e($employee_type['title']); ?></a><br />
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="toggleEmployeeTypeStatus(<?php echo $employee_type['id']; ?>, <?php echo $employee_type['status']; ?>)" id="status-label-<?php echo $employee_type['id']; ?>">
                                        <?php if ($employee_type['status']) { ?>
                                            <span class="label label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-danger">Deactive</span>
                                        <?php } ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_employee_type(this,<?php echo e($employee_type['id']); ?>);return false;"
                                                data-title="<?php echo e($employee_type['title']); ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <a href="<?php echo admin_url('hrd/delete_employee_type/' . $employee_type['id']); ?>"
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
            <p class="no-margin">No employee types found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="employee_type" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('hrd/employeetype'), ['id' => 'employee-type-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Employee Type</span> <span class="add-title"><?php echo _l('Add New Employee Type'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Employee Type Title'); ?> 
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
    appValidateForm($("body").find('#employee-type-form'), {
        name: 'required'
    }, manage_employee_type);
    $('#employee_type').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#employee_type input[name="name"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

// Create new employee type
function new_employee_type() {
    $('#employee_type').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit employee type function which init the data to the modal
function edit_employee_type(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#employee_type input[name="name"]').val($(invoker).data('title'));
    $('#employee_type').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for employee type
function manage_employee_type(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}

function toggleEmployeeTypeStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_employee_type/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleEmployeeTypeStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>

