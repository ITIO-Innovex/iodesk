<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-justify-between tw-items-center">
          <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
             <i class="fa-brands fa-wpforms tw-mr-2"></i> DAR Form Custom Fields
          </h4>
          <a href="#" onclick="openDarFieldModal(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> Add New Field
          </a>
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-full">

            <div class="row tw-mb-4">
              <div class="col-md-4">
                <?php
                $deptOptions = [];
                if (!empty($departments)) {
                    foreach ($departments as $d) {
                        $deptOptions[$d['departmentid']] = $d['name'];
                    }
                }
                echo render_select(
                    'filter_department_id',
                    $departments,
                    ['departmentid', 'name'],
                    'Department',
                    $filter_department_id,
                    ['id' => 'filter_department_id', 'class' => 'selectpicker', 'data-none-selected-text' => 'All Departments']
                );
				
                ?>
              </div>
              <div class="col-md-2 tw-flex tw-items-end">
                <button type="button" class="btn btn-default" onclick="applyDepartmentFilter();" style="margin-top:22px;">Search</button>
              </div>
            </div>

            <?php if (isset($dar_custom_fields) && !empty($dar_custom_fields)) { ?>
              <table class="table dt-table" data-order-col="0" data-order-type="asc">
                <thead>
                  <tr>
                    <th>Department</th>
                    <th>Field Title</th>
                    <th>Required</th>
                    <th>Status</th>
                    <th><?php echo _l('options'); ?></th>
                  </tr>
                </thead>
                <tbody <?php if ((int)$filter_department_id > 0) { ?>id="dar-sortable" data-department-id="<?php echo (int)$filter_department_id; ?>"<?php } ?>>
                  <?php foreach ($dar_custom_fields as $f) { ?>
                    <tr class="dar-form-row"
                        data-id="<?php echo (int)$f['id']; ?>"
                        data-department_id="<?php echo (int)$f['department_id']; ?>"
                        data-field_title="<?php echo htmlspecialchars($f['field_title'], ENT_QUOTES, 'UTF-8'); ?>"
                        data-status="<?php echo (int)$f['status']; ?>"
                        data-required="<?php echo (int)($f['required'] ?? 0); ?>">
                      <td>
                        <?php
                        $deptId = (int) ($f['department_id'] ?? 0);
                        echo html_escape($department_index[$deptId] ?? '');
                        ?>
                      </td>
                      <td><?php echo html_escape($f['field_title']); ?></td>
                      <td>
                        <?php if ((int)($f['required'] ?? 0) === 1) { ?>
                          <span class="label label-info">Yes</span>
                        <?php } else { ?>
                          <span class="label label-default">No</span>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ((int) $f['status'] === 1) { ?>
                          <span class="label label-success">Active</span>
                        <?php }else { ?>
                          <span class="label label-default">Inactive</span>
                        <?php } ?>
						
                      </td>
                      <td>
                        <div class="btn-group">
                          <a href="#" class="btn btn-default btn-icon"
                             onclick="editDarField(this, <?php echo (int) $f['id']; ?>); return false;"
                             data-department_id="<?php echo (int) $f['department_id']; ?>"
                             data-field_title="<?php echo html_escape($f['field_title']); ?>"
                             data-status="<?php echo (int) $f['status']; ?>"
                             data-required="<?php echo (int)($f['required'] ?? 0); ?>">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a href="<?php echo admin_url('hrd/delete_dar_form_field/' . (int) $f['id']); ?>"
                             class="btn btn-danger btn-icon _delete">
                            <i class="fa fa-remove"></i>
                          </a>
                        </div>
<?php if(isset($filter_department_id)&&$filter_department_id){ ?>
<i class="fa fa-arrows tw-my-2" title="Drag and drop the fields below to arrange your form as needed."></i>
<?php }else{ ?>
<i class="fa fa-arrows tw-my-2"
   title="Drag & drop works only after search by department"
   onclick="alert('Drag & drop works only after search by department')">
</i>
<?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="no-margin">No DAR custom fields found.</p>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dar-field-modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/dar_form_save'), ['id' => 'dar-field-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <span class="edit-title"><?php echo _l('edit'); ?></span>
          <span class="add-title">Add DAR Field</span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="dar-additional"></div>
            <?php echo render_select('department_id', $departments, ['departmentid', 'name'], 'Department', '', ['id' => 'dar_department_id']); ?>
            <?php echo render_input('field_title', 'Field Title', '', 'text', ['id' => 'dar_field_title']); ?>
            <div class="form-group">
              <label for="dar_status" class="control-label">Status</label>
              <select name="status" id="dar_status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="required" id="dar_required" value="1" checked>
                Required
              </label>
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

<?php init_tail(); ?>
<script>
  window.addEventListener('load', function () {
    appValidateForm($('#dar-field-form'), {
      department_id: 'required',
      field_title: 'required'
    });

    $('#dar-field-modal').on('hidden.bs.modal', function () {
      $('#dar-additional').html('');
      var $dept = $('#department_id').length ? $('#department_id') : $('#dar_department_id');
      $dept.val('').trigger('change');
      if ($dept.hasClass('selectpicker') && typeof $dept.selectpicker === 'function') {
        $dept.selectpicker('refresh');
      }
      $('#dar-field-form').find('input[name="field_title"]').val('');
      $('#dar_status').val('1');
      $('#dar_required').prop('checked', true);
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  function openDarFieldModal() {
    $('#dar-field-modal').modal('show');
    $('.edit-title').addClass('hide');
  }

  function editDarField(invoker, id) {
    var $row = $(invoker).closest('tr.dar-form-row');
    if (!$row.length) {
      $row = $(invoker).closest('tr');
    }
    var departmentId = $row.attr('data-department_id') || $(invoker).attr('data-department_id') || '';
    var fieldTitle = ($row.attr('data-field_title') || $(invoker).attr('data-field_title') || '');
    var status = String($row.attr('data-status') || $(invoker).attr('data-status') || '1');
    var required = String($row.attr('data-required') || $(invoker).attr('data-required') || '0');

    $('#dar-additional').html('<input type="hidden" name="id" value="' + id + '">');
    $('#dar-field-form').find('input[name="field_title"]').val(fieldTitle);
    $('#dar_status').val(status);
    $('#dar_required').prop('checked', required === '1');

    var $deptSelect = $('#department_id');
    if (!$deptSelect.length) {
      $deptSelect = $('#dar_department_id');
    }
    $deptSelect.val(departmentId);
    if ($deptSelect.hasClass('selectpicker') && typeof $deptSelect.selectpicker === 'function') {
      $deptSelect.selectpicker('refresh');
    }
    $deptSelect.trigger('change');

    $('.add-title').addClass('hide');
    $('.edit-title').removeClass('hide');
    $('#dar-field-modal').modal('show');
  }

  function applyDepartmentFilter() {
    var deptId = $('#filter_department_id').val() || '';
    var url = '<?php echo admin_url('hrd/setting/dar_form'); ?>';
    if (deptId) {
      url += '?department_id=' + encodeURIComponent(deptId);
    }
    window.location.href = url;
  }

  $(function () {
    var $sortable = $('#dar-sortable');
    if ($sortable.length) {
      $sortable.sortable({
        helper: function (e, ui) {
          ui.children().each(function () {
            $(this).width($(this).width());
          });
          return ui;
        },
        update: function () {
          var order = [];
          $sortable.find('tr').each(function () {
            var id = $(this).data('id');
            if (id) {
              order.push(id);
            }
          });

          if (order.length === 0) {
            return;
          }

          $.post(admin_url + 'hrd/dar_form_reorder', {
            department_id: $sortable.data('department-id'),
            order: order
          }, function (response) {
            // Optionally show a toast if needed
            try {
              var res = typeof response === 'string' ? JSON.parse(response) : response;
              if (!res.success) {
                alert_float('danger', res.message || 'Failed to save order');
              }
            } catch (e) {
              // ignore parse errors
            }
          });
        }
      }).disableSelection();
    }
  });
</script>

</body></html>

