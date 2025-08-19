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
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_status(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Project Status'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($statuses) && count($statuses) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th><?php echo _l('project_status_table_name'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($statuses as $status) { ?>
                                <tr>
                                    <td>
                                        <span class="label label-inline" style="background:<?php echo $status['color']; ?>">
                                            <?php echo $status['name']; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-default btn-icon" onclick="edit_status(this,<?php echo $status['id']; ?>); return false;" data-name="<?php echo $status['name']; ?>" data-color="<?php echo $status['color']; ?>" data-order="<?php echo $status['statusorder']; ?>">
                                                <i class="fa fa-pencil"></i>
                                            </a>
                                            <a href="<?php echo admin_url('project/delete_project_status/' . $status['id']); ?>" class="btn btn-danger btn-icon _delete">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('no_project_statuses_found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="status" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('project/projectstatus'), ['id' => 'project-status-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title"><?php echo _l('edit_status'); ?></span> <span class="add-title"><?php echo _l('Add New Project Status'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Status Title'); ?> 
            <?php echo render_color_picker('color', _l('Status Color')); ?> 
            <?php echo render_input('statusorder', 'project_status_add_edit_order', total_rows(db_prefix() . 'project_status') + 1, 'number'); ?></div>
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
    appValidateForm($("body").find('#project-status-form'), {
        name: 'required'
    }, manage_project_statuses);
    $('#status').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#status input[name="name"]').val('');
        $('#status input[name="color"]').val('');
        $('#status input[name="statusorder"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        $('#status input[name="statusorder"]').val($('table tbody tr').length + 1);
    });
});

// Create project new status
function new_status() {
    $('#status').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit status function which init the data to the modal
function edit_status(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#status input[name="name"]').val($(invoker).data('name'));
    $('#status .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
    $('#status input[name="statusorder"]').val($(invoker).data('order'));
    $('#status').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for project status
function manage_project_statuses(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}
</script>
<?php init_tail(); ?>
</body></html> 