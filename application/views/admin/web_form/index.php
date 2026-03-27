<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($pending_share_requests); 
$target_staffid=$pending_share_requests[0]['target_staffid'] ?? '';

?>
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
		  <?php //if (!empty($is_admin_user) && !empty($pending_share_requests)) { ?>
            <?php if (!empty($pending_share_requests) && ($target_staffid == get_staff_user_id()) ) { ?>
              <h5 class="tw-mt-0 tw-font-semibold">Pending Share Approval Requests</h5>
              <div class="table-responsive tw-mb-4">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Form</th>
                      <th>Requested By</th>
                      <th>Target Staff</th>
                      <th>Requested At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php echo $pending_share_requests[0]['target_staffid']; foreach ($pending_share_requests as $rq) { ?>
                      <tr>
                        <td><?php echo e($rq['form_name'] ?? '-'); ?></td>
                        <td><?php echo e(trim(($rq['firstname'] ?? '') . ' ' . ($rq['lastname'] ?? ''))); ?><?php if (!empty($rq['requester_email'])) { ?> (<?php echo e($rq['requester_email']); ?>)<?php } ?></td>
                        <td><?php echo e(trim(($rq['target_firstname'] ?? '') . ' ' . ($rq['target_lastname'] ?? ''))); ?><?php if (!empty($rq['target_email'])) { ?> (<?php echo e($rq['target_email']); ?>)<?php } ?></td>
                        <td><?php echo e($rq['created_at'] ?? ''); ?></td>
                        <td>
                          <button type="button" class="btn btn-success btn-xs process-share-request" data-request-id="<?php echo (int) $rq['id']; ?>" data-decision="approved">Approve</button>
                          <button type="button" class="btn btn-danger btn-xs process-share-request" data-request-id="<?php echo (int) $rq['id']; ?>" data-decision="rejected">Reject</button>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } ?>

            <?php if (!empty($forms)) { 
			
			?>
              <table class="table dt-table" data-order-col="4" data-order-type="desc">
                <thead>
                  <tr>
                    <?php /*?><th>ID</th><?php */?>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
					<th>Created At</th>
					<th>Share to</th>
                    <th><?php echo _l('options'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($forms as $form) { $formid=(int)$form['id']; ?>
                    <tr>
                      <?php /*?><td><?php echo (int)$form['id']; ?></td><?php */?>
                      <td><a href="<?php echo admin_url('web_form/manage/' . (int)$form['id']); ?>" title="Manage Data"><?php echo e($form['name']); ?></a> (<?php echo count_total_web_form($formid);?>)</td>
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
                          <a href="#" class="btn btn-danger btn-xs webform-delete-trigger" title="Delete (soft)" data-form-id="<?php echo (int)$form['id']; ?>" data-form-name="<?php echo e($form['name']); ?>">
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
            <?php if (!empty($is_admin_user)) { ?>
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
            <?php } else { ?>
              <label>Staff Email</label>
              <input type="email" name="share_email" id="share_email" class="form-control" placeholder="Enter staff email for approval request" required>
              <p class="text-muted tw-mt-2 tw-text-xs">For non-admin users, sharing requires admin approval.</p>
            <?php } ?>
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

<!-- Delete form modal (reason required) -->
<div class="modal fade" id="deleteFormModal" tabindex="-1" role="dialog" aria-labelledby="deleteFormModalLabel">
  <div class="modal-dialog" role="document" style="max-width:600px;">
    <div class="modal-content">
      <?php echo form_open('', ['id' => 'delete-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="deleteFormModalLabel">Delete Web Form</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="delete_form_id" value="">
        <p class="text-muted" id="delete_form_help"></p>
        <div class="form-group">
          <label>Reason for Delete <span class="text-danger">*</span></label>
          <textarea class="form-control" name="reason_for_delete" id="reason_for_delete" rows="3" required placeholder="Enter reason..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-danger" id="confirmDeleteBtn"><?php echo _l('delete'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<script>
(function() {
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

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

    <?php if (!empty($is_admin_user)) { ?>
      var selected = parseAssignTo(rawAssign);
      $('#assign_to').selectpicker('val', selected);
      $('#assign_to').selectpicker('refresh');
    <?php } else { ?>
      $('#share_email').val('');
    <?php } ?>

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
          if (resp.message) {
            alert_float('success', resp.message);
          }
          location.reload();
        } else {
          alert_float('danger', (resp && resp.message) ? resp.message : 'Failed to update assign staff');
        }
      })
      .fail(function() {
        alert_float('danger', 'Failed to update assign staff');
      });
  });

  $('body').on('click', '.process-share-request', function() {
    var requestId = parseInt($(this).data('request-id'), 10) || 0;
    var decision = ($(this).data('decision') || '').toString();
    if (!requestId || (decision !== 'approved' && decision !== 'rejected')) {
      return;
    }
    var reqData = { request_id: requestId, decision: decision };
    reqData[csrfName] = csrfHash;
    $.post(admin_url + 'web_form/process_share_request', reqData)
      .done(function(resp) {
        try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Updated');
          location.reload();
        } else {
          alert_float('danger', (resp && resp.message) ? resp.message : 'Failed to process request');
        }
      })
      .fail(function() {
        alert_float('danger', 'Failed to process request');
      });
  });

  // Delete modal
  $('body').on('click', '.webform-delete-trigger', function(e) {
    e.preventDefault();
    var formId = $(this).data('form-id');
    var formName = $(this).data('form-name') || '';
    $('#delete_form_id').val(formId);
    $('#reason_for_delete').val('');
    $('#delete_form_help').text('You are deleting: ' + formName);
    $('#deleteFormModal').appendTo('body').modal('show');
    setTimeout(function() { $('#reason_for_delete').focus(); }, 200);
  });

  $('#delete-form').on('submit', function(e) {
    e.preventDefault();
    var formId = parseInt($('#delete_form_id').val(), 10) || 0;
    var reason = $.trim($('#reason_for_delete').val());
    if (!formId) {
      alert_float('danger', 'Invalid form');
      return false;
    }
    if (!reason) {
      alert_float('warning', 'Reason for Delete is required');
      $('#reason_for_delete').focus();
      return false;
    }
    var $btn = $('#confirmDeleteBtn');
    $btn.prop('disabled', true);
    $.post(admin_url + 'web_form/delete/' + formId, {
      reason_for_delete: reason,
      <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    }).done(function() {
      $('#deleteFormModal').modal('hide');
	  alert_float('success', 'Failed to delete form');
      location.reload();
    }).fail(function() {
      alert_float('danger', 'Failed to delete form');
    }).always(function() {
      $btn.prop('disabled', false);
    });
  });
})();
</script>
</body>
</html>

