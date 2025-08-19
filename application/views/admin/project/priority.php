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
          <a href="#" onclick="new_priority(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Project Priority'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($priorities) && count($priorities) > 0) { ?>
            <table class="table dt-table" data-order-col="2" data-order-type="asc">
              <thead>
                <th><?php echo _l('Name'); ?></th>
                <th><?php echo _l('Color'); ?></th>
                <th><?php echo _l('Order'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($priorities as $p) { ?>
                <tr>
                  <td><?php echo e($p['name']); ?></td>
                  <td><span class="label label-inline" style="background:<?php echo e($p['color']); ?>;">&nbsp;&nbsp;&nbsp;</span> <?php echo e($p['color']); ?></td>
                  <td><?php echo (int)$p['statusorder']; ?></td>
                  <td>
                    <div class="btn-group">
                      <a href="#" class="btn btn-default btn-icon" onclick="edit_priority(this,<?php echo (int)$p['priorityid']; ?>); return false;" data-name="<?php echo e($p['name']); ?>" data-color="<?php echo e($p['color']); ?>" data-order="<?php echo (int)$p['statusorder']; ?>" data-status="<?php echo (int)$p['status']; ?>">
                        <i class="fa fa-pencil"></i>
                      </a>
                      <a href="<?php echo admin_url('project/delete_project_priority/' . (int)$p['priorityid']); ?>" class="btn btn-danger btn-icon _delete">
                        <i class="fa fa-remove"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('No project priorities found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="priority" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('project/projectpriority'), ['id' => 'project-priority-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo _l('edit'); ?></span>
          <span class="add-title"><?php echo _l('Add New Project Priority'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Name'); ?>
            <?php echo render_color_picker('color', _l('Color')); ?>
            <?php echo render_input('statusorder', 'Order', total_rows(db_prefix() . 'project_priority') + 1, 'number'); ?>
            <div class="form-group">
              <label for="status" class="control-label"><?php echo _l('Status'); ?></label>
              <select name="status" id="status" class="form-control">
                <option value="1"><?php echo _l('Active'); ?></option>
                <option value="0"><?php echo _l('Inactive'); ?></option>
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

<script>
window.addEventListener('load', function () {
  appValidateForm($("body").find('#project-priority-form'), {
    name: 'required'
  }, manage_project_priorities);
  $('#priority').on("hidden.bs.modal", function () {
    $('#additional').html('');
    $('#priority input[name="name"]').val('');
    $('#priority input[name="color"]').val('');
    $('#priority input[name="statusorder"]').val('');
    $('#priority select[name="status"]').val('1');
    $('.add-title').removeClass('hide');
    $('.edit-title').removeClass('hide');
    $('#priority input[name="statusorder"]').val($('table tbody tr').length + 1);
  });
});

function new_priority() {
  $('#priority').modal('show');
  $('.edit-title').addClass('hide');
}

function edit_priority(invoker, id) {
  $('#additional').append('<input type="hidden" name="priorityid" value="' + id + '">');
  $('#priority input[name="name"]').val($(invoker).data('name'));
  $('#priority .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
  $('#priority input[name="statusorder"]').val($(invoker).data('order'));
  $('#priority select[name="status"]').val($(invoker).data('status'));
  $('#priority').modal('show');
  $('.add-title').addClass('hide');
}

function manage_project_priorities(form) {
  var data = $(form).serialize();
  var url = form.action;
  $.post(url, data).done(function () {
    window.location.reload();
  });
  return false;
}
</script>
<?php init_tail(); ?>
</body></html>

