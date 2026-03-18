<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Add Web Forms</h4>
          <a href="<?php echo admin_url('web_form/create'); ?>" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> Add Web Form
          </a>
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($forms)) { ?>
              <table class="table dt-table" data-order-col="4" data-order-type="desc">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
					<th>Created At</th>
					<th>Share to</th>
                    <th><?php echo _l('options'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($forms as $form) { ?>
                    <tr>
                      <td><?php echo (int)$form['id']; ?></td>
                      <td><?php echo e($form['name']); ?></td>
                      <td><?php echo e($form['description']); ?></td>
                      <td>
                        <?php if (!empty($form['is_active'])) { ?>
                          <span class="label label-success">Active</span>
                        <?php } else { ?>
                          <span class="label label-default">Inactive</span>
                        <?php } ?>
                      </td>
                      
					  <td><?php echo e($form['created_at']); ?></td>
					  <td><?php //echo e($form['assign_to'] ?? ''); ?>
<?php
$list = $form['assign_to'];
if(isset($list)&&$list){
$list = json_decode($list, true);  // convert to array
	foreach ($list as $oid) {
		$oid = trim($oid);
                                if ($oid === '') { continue; }
                                echo '<a href="' . admin_url('profile/' . $oid) . '">'
                                  . staff_profile_image($oid, ['tw-h-7 tw-w-7 tw-inline-block tw-rounded-full tw-ring-2 tw-ring-white'], 'small', ['data-toggle' => 'tooltip', 'data-title' => e(get_staff_full_name($oid))])
                                  . '</a>';
	}
}
?>
</td>
                      <td>
                        <div class="tw-flex tw-items-center tw-space-x-2">
                          <a href="<?php echo admin_url('web_form/manage/' . (int)$form['id']); ?>" class="btn btn-default btn-xs" title="Manage Data">
                            <i class="fa-solid fa-database"></i>
                          </a>
                          <a href="<?php echo admin_url('web_form/create/' . (int)$form['id']); ?>" class="btn btn-default btn-xs" title="Edit Form">
                            <i class="fa-regular fa-pen-to-square"></i>
                          </a>
                          <a href="<?php echo admin_url('web_form/delete/' . (int)$form['id']); ?>" class="btn btn-danger btn-xs _delete" title="Delete (soft)">
                            <i class="fa-regular fa-trash-can"></i>
                          </a>
                          <button type="button"
                                  class="btn btn-default btn-xs share_form"
                                  title="Share Form"
                                  data-form-id="<?php echo (int)$form['id']; ?>"
                                  data-assign-to="<?php echo e($form['assign_to'] ?? ''); ?>">
                            <i class="fa-solid fa-share-nodes"></i>
                          </button>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="text-muted">No web forms found. Click "Add Web Form" to create one.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

<div class="modal fade" id="shareFormModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document" style="max-width:600px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Share Form</h4>
      </div>
      <div class="modal-body">
        <?php echo form_open(admin_url('web_form/save_assign_to'), ['id' => 'share-form']); ?>
          <input type="hidden" name="form_id" id="share_form_id" value="">

        <div class="form-group">
            <label>Assign To (Staff)</label>
            <div class="tw-mb-2">
              <button type="button" class="btn btn-default btn-xs" id="share_select_all">Select all</button>
              <button type="button" class="btn btn-default btn-xs" id="share_clear_all">Clear</button>
            </div>
            <select name="assign_to[]" id="assign_to" class="form-control selectpicker" multiple data-live-search="true" data-actions-box="true" title="Select staff">
              <?php if (!empty($staff_members)) { ?>
              <?php foreach ($staff_members as $s) { ?>
              <option value="<?php echo (int)$s['staffid']; ?>"> <?php echo e(trim($s['firstname'] . ' ' . $s['lastname'])); ?><?php echo !empty($s['email']) ? ' - ' . e($s['email']) : ''; ?> </option>
              <?php } ?>
              <?php } ?>
            </select>
        </div>

          <div class="tw-text-right">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<script>
(function() {
  function parseAssignTo(raw) {
    if (!raw) return [];
    try {
      var arr = JSON.parse(raw);
      if (Array.isArray(arr)) return arr.map(function(x){ return x + ''; });
    } catch (e) {}
    return [];
  }

  $('body').on('click', '.share_form', function() {
    var formId = $(this).data('form-id');
    var rawAssign = $(this).attr('data-assign-to') || '';
    $('#share_form_id').val(formId);

    var selected = parseAssignTo(rawAssign);
    $('#assign_to').selectpicker('val', selected);
    $('#assign_to').selectpicker('refresh');

    $('#shareFormModal').appendTo('body').modal('show');
  });

  $('#share_select_all').on('click', function() {
    var allVals = [];
    $('#assign_to option').each(function() { allVals.push($(this).val()); });
    $('#assign_to').selectpicker('val', allVals);
    $('#assign_to').selectpicker('refresh');
  });
  $('#share_clear_all').on('click', function() {
    $('#assign_to').selectpicker('val', []);
    $('#assign_to').selectpicker('refresh');
  });

  $('#share-form').on('submit', function(e) {
    e.preventDefault();
    var $form = $(this);
    $.post($form.attr('action'), $form.serialize())
      .done(function(resp) {
        try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
        if (resp && resp.success) {
          $('#shareFormModal').modal('hide');
          location.reload();
        } else {
          alert_float('danger', 'Failed to update assign staff');
        }
      })
      .fail(function() {
        alert_float('danger', 'Failed to update assign staff');
      });
  });
})();
</script>
</body>
</html>

