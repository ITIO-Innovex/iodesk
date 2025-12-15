<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <?php $component_lookup = [];
    if (!empty($component_options)) {
        foreach ($component_options as $opt) {
            $component_lookup[$opt['id']] = $opt['title'];
        }
    }
    ?>
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_component(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i>
            <?php echo _l('New Payroll Component'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($components)) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="asc">
              <thead>
                <tr>
                  <th><?php echo _l('Component Code'); ?></th>
                  <th><?php echo _l('Component Title'); ?></th>
                  <th><?php echo _l('Type'); ?></th>
                  <th><?php echo _l('Is %'); ?></th>
                  <th><?php echo _l('Percent Of'); ?></th>
                  <th><?php echo _l('status'); ?></th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($components as $component) { ?>
                <tr>
                  <td><?php echo html_escape($component['code']); ?></td>
                  <td>
                    <a href="#" onclick="edit_component(this, <?php echo (int)$component['id']; ?>);return false;"
                      data-id="<?php echo (int)$component['id']; ?>"
                      data-code="<?php echo html_escape($component['code']); ?>"
                      data-title="<?php echo html_escape($component['title']); ?>"
                      data-type="<?php echo html_escape($component['type']); ?>"
                      data-percentage="<?php echo (int)$component['is_percentage']; ?>"
                      data-percent="<?php echo (int)$component['percent_of_component']; ?>"
                      data-active="<?php echo (int)$component['is_active']; ?>">
                      <?php echo html_escape($component['title']); ?>
                    </a>
                  </td>
                  <td class="text-capitalize"><?php echo html_escape($component['type']); ?></td>
                  <td>
                    <?php if ((int)$component['is_percentage'] === 1) { ?>
                      <span class="label label-info">Yes</span>
                    <?php } else { ?>
                      <span class="label label-default">No</span>
                    <?php } ?>
                  </td>
                  <td>
                    <?php
                      $percentId = (int)($component['percent_of_component'] ?? 0);
                      echo isset($component_lookup[$percentId]) ? html_escape($component_lookup[$percentId]) : '-';
                    ?>
                  </td>
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
                      <a href="#" onclick="edit_component(this, <?php echo (int)$component['id']; ?>); return false;"
                        data-id="<?php echo (int)$component['id']; ?>"
                        data-code="<?php echo html_escape($component['code']); ?>"
                        data-title="<?php echo html_escape($component['title']); ?>"
                        data-type="<?php echo html_escape($component['type']); ?>"
                        data-percentage="<?php echo (int)$component['is_percentage']; ?>"
                        data-percent="<?php echo (int)$component['percent_of_component']; ?>"
                        data-active="<?php echo (int)$component['is_active']; ?>"
                        class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('payroll/delete_component/' . (int)$component['id']); ?>"
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
    <?php echo form_open(admin_url('payroll/component'), ['id' => 'component-form']); ?>
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
            <?php echo render_input('code', 'Component Code'); ?>
            <?php echo render_input('title', 'Component Title'); ?>
            <div class="form-group">
              <label for="type" class="control-label">Type</label>
              <select name="type" id="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="earning">Earning</option>
                <option value="deduction">Deduction</option>
              </select>
            </div>
            <div class="checkbox checkbox-primary">
              <input type="checkbox" name="is_percentage" id="is_percentage" value="1">
              <label for="is_percentage">Is Percentage Component</label>
            </div>
            <div class="form-group hide" id="percent-of-wrapper">
              <label for="percent_of_component" class="control-label">Percent Of Component</label>
              <select name="percent_of_component" id="percent_of_component" class="form-control">
                <option value="">Select Component</option>
                <?php if (!empty($component_options)) { foreach ($component_options as $option) { ?>
                  <option value="<?php echo (int)$option['id']; ?>"><?php echo html_escape($option['title']); ?></option>
                <?php }} ?>
              </select>
              <small class="text-muted">Select component on which percentage should be calculated.</small>
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
(function() {
  function togglePercentWrapper() {
    if ($('#is_percentage').is(':checked')) {
      $('#percent-of-wrapper').removeClass('hide');
    } else {
      $('#percent-of-wrapper').addClass('hide');
      $('#percent_of_component').val('');
    }
  }

  window.addEventListener('load', function () {
    appValidateForm($('#component-form'), {
      title: 'required',
      type: 'required'
    }, manage_component);

    $('#component-modal').on('hidden.bs.modal', function () {
      $('#additional').html('');
      $('#component-form')[0].reset();
      $('#percent_of_component option').prop('disabled', false);
      $('#percent-of-wrapper').addClass('hide');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });

    $('#is_percentage').on('change', togglePercentWrapper);
  });

  window.new_component = function () {
    $('#component-modal').modal('show');
    $('.edit-title').addClass('hide');
    $('#is_active').prop('checked', true);
    $('#is_percentage').prop('checked', false);
    togglePercentWrapper();
  };

  window.edit_component = function (invoker, id) {
    $('#additional').html(hidden_input('id', id));
    $('#component-form input[name="code"]').val($(invoker).data('code'));
    $('#component-form input[name="title"]').val($(invoker).data('title'));
    $('#type').val($(invoker).data('type'));

    var isPercentage = $(invoker).data('percentage') == 1;
    $('#is_percentage').prop('checked', isPercentage);
    togglePercentWrapper();

    var percentId = $(invoker).data('percent');
    if (isPercentage && percentId) {
      $('#percent_of_component').val(percentId);
    } else {
      $('#percent_of_component').val('');
    }

    var active = $(invoker).data('active') == 1;
    $('#is_active').prop('checked', active);

    $('#component-modal').modal('show');
    $('.add-title').addClass('hide');
  };

  window.manage_component = function (form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function () {
      window.location.reload();
    });
    return false;
  };

  window.togglePayrollComponent = function (id, currentStatus) {
    $.post(admin_url + 'payroll/toggle_component/' + id, { status: currentStatus == 1 ? 0 : 1 }, function (response) {
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
  };
})();
</script>
<?php init_tail(); ?>
</body></html>
