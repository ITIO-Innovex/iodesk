<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  .jqte_tool.jqte_tool_1 .jqte_tool_label { height: 20px !important; }
  .jqte { margin: 20px 0 !important; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_guideline(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Corporate Guideline'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($guidelines) && count($guidelines) > 0) { ?>
            <?php 
              // Build branch map id => name for quick lookup
              $branchMap = [];
              if (isset($branches)) {
                foreach ($branches as $b) { $branchMap[$b['id']] = $b['branch_name']; }
              }
            ?>
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
              <thead>
                <th>Branch</th>
                <th>Title</th>
                <th>Details</th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($guidelines as $g) { ?>
                <tr>
                  <td><?php echo isset($branchMap[$g['branch']]) ? e($branchMap[$g['branch']]) : '-'; ?></td>
                  <td>
                    <?php echo e($g['title']); ?>
                  </td>
                  <td><?php echo nl2br($g['details']); ?></td>
                  <td>
                    <a href="javascript:void(0);" onclick="toggleGuidelineStatus(<?php echo $g['id']; ?>, <?php echo (int)$g['status']; ?>)" id="status-label-<?php echo $g['id']; ?>">
                      <?php if (!empty($g['status'])) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_guideline(this,<?php echo e($g['id']); ?>);return false;"
                         data-branch="<?php echo e($g['branch']); ?>"
                         data-title="<?php echo e($g['title']); ?>"
                         data-details="<?php echo e($g['details']); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_corporate_guidelines/' . $g['id']); ?>"
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
              <p class="no-margin">No records found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="corporate_guidelines" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/corporateguidelines'), ['id' => 'corporate-guidelines-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit Corporate Guideline</span>
          <span class="add-title"><?php echo _l('Add New Corporate Guideline'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <div class="form-group">
              <label for="branch">Branch</label>
              <select name="branch" id="branch" class="form-control" required>
                <option value="">-- Select Branch --</option>
                <?php if (isset($branches)) { foreach ($branches as $b) { ?>
                  <option value="<?php echo e($b['id']); ?>"><?php echo e($b['branch_name']); ?></option>
                <?php } } ?>
              </select>
            </div>
            <?php echo render_input('title', 'Title'); ?>
            <div class="form-group">
              <label for="details">Details</label>
              <textarea name="details" id="details" class="form-control editor" rows="5" required></textarea>
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
    appValidateForm($("body").find('#corporate-guidelines-form'), {
      branch: 'required',
      title: 'required',
      details: 'required'
    }, manage_guideline);
    $('#corporate_guidelines').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#corporate_guidelines select[name="branch"]').val('');
      $('#corporate_guidelines input[name="title"]').val('');
      $('#corporate_guidelines textarea[name="details"]').val('');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  // Create new
  function new_guideline() {
    $('#corporate_guidelines').modal('show');
    $('.edit-title').addClass('hide');
  }

  // Edit
  function edit_guideline(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#corporate_guidelines select[name="branch"]').val($(invoker).data('branch'));
    $('#corporate_guidelines input[name="title"]').val($(invoker).data('title'));
    $('#corporate_guidelines textarea[name="details"]').jqteVal($(invoker).data('details'));
	$('#corporate_guidelines textarea[name="details"]').val($(invoker).data('details'));
    $('#corporate_guidelines').modal('show');
    $('.add-title').addClass('hide');
  }

  // Form handler
  function manage_guideline(form) {
    var data = $(form).serialize();
	//alert(data);
    var url = form.action;
	//alert(url);
    $.post(url, data).done(function () {
      window.location.reload();
    });
    return false;
  }

  function toggleGuidelineStatus(id, currentStatus) {
    $.post(admin_url + 'hrd/toggle_corporate_guidelines/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
      if (response.success) {
        var label = $('#status-label-' + id + ' span');
        if (response.new_status == 1) {
          label.removeClass('label-danger').addClass('label-success').text('Active');
        } else {
          label.removeClass('label-success').addClass('label-danger').text('Deactive');
        }
        $('#status-label-' + id).attr('onclick', 'toggleGuidelineStatus(' + id + ', ' + response.new_status + ')');
      } else {
        alert('Failed to update status');
      }
    }, 'json');
  }
</script>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
  $('.editor').jqte();
</script>
</body></html>
