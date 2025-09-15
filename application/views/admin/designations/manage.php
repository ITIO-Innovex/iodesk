<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
              <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Designations</h4>
              <a href="#" class="btn btn-primary" onclick="open_designation_modal(); return false;">
                <i class="fa-regular fa-plus tw-mr-1"></i> Add New Designation
              </a>
            </div>
            <?php render_datatable([
              'ID',
              'Department',
              'Designation',
              'Active',
              'Company',
              _l('options'),
            ], 'designations'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="designation_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('designation/manage'), ['id' => 'designation-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="designation_modal_label">Add Designation</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="designation_id" value="">
        <div class="form-group">
          <label for="department_id" class="control-label">Department</label>
          <select class="form-control selectpicker" data-live-search="true" name="department_id" id="department_id" data-none-selected-text="Select department">
          </select>
        </div>
        <div class="form-group">
          <label for="title" class="control-label">Designation</label>
          <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Senior Developer">
        </div>
        <div class="checkbox checkbox-primary">
          <input type="checkbox" id="is_active" name="is_active" checked>
          <label for="is_active">Active</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  function reload_departments(selected) {
    $.getJSON('<?php echo admin_url('designations/departments'); ?>', function(list) {
      var $sel = $('#department_id');
      $sel.empty();
      $sel.append('<option value="">Select department</option>');
      list.forEach(function(it){
        var opt = $('<option>').attr('value', it.id).text(it.name);
        if (selected && parseInt(selected) === parseInt(it.id)) opt.attr('selected', 'selected');
        $sel.append(opt);
      });
      $sel.selectpicker('refresh');
    });
  }
  function open_designation_modal() {
    $('#designation_modal_label').text('Add Designation');
    $('#designation_id').val('');
    $('#title').val('');
    $('#is_active').prop('checked', true);
    reload_departments();
    $('#designation_modal').modal('show');
  }
  function edit_designation(el, id) {
    $('#designation_modal_label').text('Edit Designation');
    $('#designation_id').val(id);
    $('#title').val($(el).data('title'));
    $('#is_active').prop('checked', $(el).data('active') == 1);
    reload_departments($(el).data('department-id'));
    $('#designation_modal').modal('show');
  }

  $(function(){
    // Columns: 0 ID, 1 Department, 2 Designation, 3 Active, 4 Company, 5 Options
    // Make Options column non-sortable/non-searchable; default order by ID desc
    initDataTable('.table-designations', window.location.href, [5], [5], undefined, [0, 'desc']);

    $('#designation-form').on('submit', function(e){
      e.preventDefault();
      var $f = $(this);
      $.post($f.attr('action'), $f.serialize()).done(function(resp){
        var r = {};
        try { r = JSON.parse(resp); } catch(e) {}
        if (r.success) {
          alert_float('success', r.message || 'Saved');
          $('#designation_modal').modal('hide');
          $('.table-designations').DataTable().ajax.reload();
        } else {
          alert_float('warning', r.message || 'Validation failed');
        }
      }).fail(function(){
        alert_float('danger', 'Request failed');
      })
    });
  });
</script>
</body>
</html>
