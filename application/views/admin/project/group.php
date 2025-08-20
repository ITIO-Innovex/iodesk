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
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_group(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Project Group'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($groups) && count($groups) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th><?php echo _l('project_group_table_name'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($groups as $group) { ?>
                                <tr>
                                    <td>
                                        <span class="label label-inline" style="background:<?php echo $group['color']; ?>">
                                            <?php echo $group['name']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-default btn-icon" onclick="edit_group(this,<?php echo $group['id']; ?>); return false;" data-name="<?php echo $group['name']; ?>" data-color="<?php echo $group['color']; ?>" data-order="<?php echo $group['statusorder']; ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="<?php echo admin_url('project/delete_project_group/' . $group['id']); ?>" class="btn btn-danger btn-icon _delete">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('no_project_groups_found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="group" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('project/projectgroup'), ['id' => 'project-group-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title"><?php echo _l('edit_group'); ?></span> <span class="add-title"><?php echo _l('Add New Project Group'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Group Title'); ?> 
            <?php echo render_color_picker('color', _l('Group Color')); ?> 
            <?php /*?><?php echo render_input('statusorder', 'project_group_add_edit_order', total_rows(db_prefix() . 'project_group') + 1, 'number'); ?><?php */?></div>
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
    appValidateForm($("body").find('#project-group-form'), {
        name: 'required'
    }, manage_project_groups);
    $('#group').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#group input[name="name"]').val('');
        $('#group input[name="color"]').val('');
        $('#group input[name="statusorder"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        $('#group input[name="statusorder"]').val($('table tbody tr').length + 1);
    });
});

// Create project new group
function new_group() {
    $('#group').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit group function which init the data to the modal
function edit_group(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#group input[name="name"]').val($(invoker).data('name'));
    $('#group .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
    $('#group input[name="statusorder"]').val($(invoker).data('order'));
    $('#group').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for project group
function manage_project_groups(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
		showFlashMessage('Project group action done!', 'success');
    });
    return false;
}
</script>
<?php init_tail(); ?>
</body></html> 