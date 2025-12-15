<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_component(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Payroll Component'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($components) > 0) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="asc">
              <thead>
                <th><?php echo _l('Component Name'); ?></th>
                <th><?php echo _l('Type'); ?></th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($components as $component) { ?>
                <tr>
                  <td>
                    <a href="#"
                       onclick="edit_component(this,<?php echo (int)$component['id']; ?>);return false;"
                       data-name="<?php echo e($component['name']); ?>"
                       data-type="<?php echo e($component['type']); ?>"
                       data-active="<?php echo (int)$component['is_active']; ?>">
                      <?php echo e($component['name']); ?>
                    </a>
                  </td>
                  <td class="text-capitalize"><?php echo e($component['type']); ?></td>
                  <td>
                    <a href="javascript:void(0);" onclick="togglePayrollComponent(<?php echo (int)$component['id']; ?>, <?php echo (int)$component['is_active']; ?>)" id="component-status-<?php echo (int)$component['id']; ?>">
                      <?php if ((int)$component['is_active'] === 1) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_component(this,<?php echo (int)$component['id']; ?>);return false;"
                         data-name="<?php echo e($component['name']); ?>"
                         data-type="<?php echo e($component['type']); ?>"
                         data-active="<?php echo (int)$component['is_active']; ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_payroll_component/' . (int)$component['id']); ?>"
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
              <p class="no-margin">No payroll components found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="component-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/payrollcomponent'), ['id' => 'component-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit Payroll Component</span>
          <span class="add-title"><?php echo _l('Add New Payroll Component'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Component Name'); ?>
            <div class="form-group">
              <label for="type" class="control-label">Type</label>
              <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="earning">Earning</option>
                <option value="deduction">Deduction</option>
              </select>
            </div>
            <div class="checkbox checkbox-primary">
              <input type="checkbox" name="is_active" id="is_active" value="1" checked>
              <label for="is_active">Active</label>
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
  appValidateForm($('#component-form'), {
    name: 'required',
    type: 'required'
  }, manage_component);

  $('#component-modal').on('hidden.bs.modal', function () {
    $('#additional').html('');
    $('#component-form')[0].reset();
    $('#type').val('');
    $('.add-title').removeClass('hide');
    $('.edit-title').removeClass('hide');
  });
});

function new_component() {
  $('#component-modal').modal('show');
  $('.edit-title').addClass('hide');
  $('#is_active').prop('checked', true);
}

function edit_component(invoker, id) {
  $('#additional').html(hidden_input('id', id));
  $('#component-form input[name="name"]').val($(invoker).data('name'));
  $('#type').val($(invoker).data('type'));
  $('#is_active').prop('checked', $(invoker).data('active') == 1);
  $('#component-modal').modal('show');
  $('.add-title').addClass('hide');
}

function manage_component(form) {
  var data = $(form).serialize();
  var url = form.action;
  $.post(url, data).done(function () {
    window.location.reload();
  });
  return false;
}

function togglePayrollComponent(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_payroll_component/' + id, {status: currentStatus == 1 ? 0 : 1}, function (response) {
    if (response.success) {
      var label = $('#component-status-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#component-status-' + id).attr('onclick', 'togglePayrollComponent(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>
