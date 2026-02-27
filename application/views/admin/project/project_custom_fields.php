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
          <a href="#" onclick="new_project_custom_field(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Project Custom Field'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($custom_fields) && count($custom_fields) > 0) { ?>
              <table class="table dt-table" data-order-col="0" data-order-type="asc">
                <thead>
                  <th><?php echo _l('Project Group'); ?></th>
                  <th><?php echo _l('Field Title'); ?></th>
                  <th><?php echo _l('Status'); ?></th>
                  <th><?php echo _l('options'); ?></th>
                </thead>
                <tbody>
                  <?php foreach ($custom_fields as $cf) { ?>
                    <tr>
                      <td>
                        <?php echo html_escape($cf['group_name'] ?? ''); ?>
                      </td>
                      <td><?php echo html_escape($cf['field_title']); ?></td>
                      <td>
                        <?php if ((int) $cf['status'] === 1) { ?>
                          <span class="label label-success"><?php echo _l('Active'); ?></span>
                        <?php } else { ?>
                          <span class="label label-default"><?php echo _l('Inactive'); ?></span>
                        <?php } ?>
                      </td>
                      <td>
                        <div class="btn-group">
                          <a href="#" class="btn btn-default btn-icon"
                             onclick="edit_project_custom_field(this, <?php echo (int) $cf['id']; ?>); return false;"
                             data-group_id="<?php echo (int) $cf['group_id']; ?>"
                             data-field_title="<?php echo html_escape($cf['field_title']); ?>"
                             data-status="<?php echo (int) $cf['status']; ?>">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a href="<?php echo admin_url('project/delete_project_custom_field/' . (int) $cf['id']); ?>"
                             class="btn btn-danger btn-icon _delete">
                            <i class="fa fa-remove"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="no-margin"><?php echo _l('No project custom fields found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="project-custom-field" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('project/projectcustomfield'), ['id' => 'project-custom-field-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo _l('edit'); ?></span>
          <span class="add-title"><?php echo _l('Add New Project Custom Field'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_select('group_id', $groups, ['id', 'name'], 'Project Group'); ?>
            <?php echo render_input('field_title', 'Field Title'); ?>
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
    appValidateForm($("body").find('#project-custom-field-form'), {
      group_id: 'required',
      field_title: 'required'
    }, manage_project_custom_fields);

    $('#project-custom-field').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#project-custom-field select[name="group_id"]').val('').trigger('change');
      $('#project-custom-field input[name="field_title"]').val('');
      $('#project-custom-field select[name="status"]').val('1');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  function new_project_custom_field() {
    $('#project-custom-field').modal('show');
    $('.edit-title').addClass('hide');
  }

  function edit_project_custom_field(invoker, id) {
    $('#additional').append('<input type="hidden" name="id" value="' + id + '">');
    $('#project-custom-field select[name="group_id"]').val($(invoker).data('group_id')).trigger('change');
    $('#project-custom-field input[name="field_title"]').val($(invoker).data('field_title'));
    $('#project-custom-field select[name="status"]').val($(invoker).data('status'));
    $('#project-custom-field').modal('show');
    $('.add-title').addClass('hide');
  }

  function manage_project_custom_fields(form) {
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

