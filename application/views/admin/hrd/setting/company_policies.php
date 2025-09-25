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
          <a href="#" onclick="new_policy(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Company Policy'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($policies) && count($policies) > 0) { ?>
            <?php 
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
                <th>Attachments</th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($policies as $p) { ?>
                <tr>
                  <td><?php echo isset($branchMap[$p['branch']]) ? e($branchMap[$p['branch']]) : '-'; ?></td>
                  <td>
                    <a href="#"
                       onclick="edit_policy(this,<?php echo e($p['id']); ?>);return false;"
                       data-branch="<?php echo e($p['branch']); ?>"
                       data-title="<?php echo e($p['title']); ?>"
                       data-details="<?php echo htmlspecialchars($p['details'], ENT_QUOTES, 'UTF-8'); ?>"
                    ><?php echo e($p['title']); ?></a>
                  </td>
                  <td><?php echo nl2br($p['details']); ?></td>
                  <td>
                    <?php if (!empty($p['attachments'])) { ?>
                      <div class="attachments-list">
                        <?php foreach ($p['attachments'] as $attachment) { ?>
                          <div class="attachment-item" style="margin-bottom: 5px;">
                            <a href="<?php echo base_url($attachment['file_path']); ?>" target="_blank" class="text-primary">
                              <i class="fa fa-file"></i> <?php echo e($attachment['original_name']); ?>
                            </a>
                            <a href="<?php echo admin_url('hrd/delete_policy_attachment/' . $attachment['id']); ?>" 
                               class="text-danger ml-2 _delete" title="Delete">
                              <i class="fa fa-trash"></i>
                            </a>
                          </div>
                        <?php } ?>
                      </div>
                    <?php } else { ?>
                      <span class="text-muted">No attachments</span>
                    <?php } ?>
                  </td>
                  <td>
                    <a href="javascript:void(0);" onclick="togglePolicyStatus(<?php echo $p['id']; ?>, <?php echo (int)$p['status']; ?>)" id="status-label-<?php echo $p['id']; ?>">
                      <?php if (!empty($p['status'])) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_policy(this,<?php echo e($p['id']); ?>);return false;"
                         data-branch="<?php echo e($p['branch']); ?>"
                         data-title="<?php echo e($p['title']); ?>"
                         data-details="<?php echo htmlspecialchars($p['details'], ENT_QUOTES, 'UTF-8'); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_company_policies/' . $p['id']); ?>"
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

<div class="modal fade" id="company_policies" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('hrd/companypolicies'), ['id' => 'company-policies-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit Company Policy</span>
          <span class="add-title"><?php echo _l('Add New Company Policy'); ?></span>
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
            <div class="form-group">
              <label for="attachments">Attachments</label>
              <input type="file" name="attachments[]" id="attachments" class="form-control" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
              <small class="text-muted">You can select multiple files. Supported formats: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF</small>
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
    appValidateForm($("body").find('#company-policies-form'), {
      branch: 'required',
      title: 'required',
      details: 'required'
    }, manage_policy);
    $('#company_policies').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#company_policies select[name="branch"]').val('');
      $('#company_policies input[name="title"]').val('');
      $('#company_policies textarea[name="details"]').val('');
      $('#company_policies input[name="attachments[]"]').val('');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  // Create new
  function new_policy() {
    $('#company_policies').modal('show');
    $('.edit-title').addClass('hide');
  }

  // Edit
  function edit_policy(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#company_policies select[name="branch"]').val($(invoker).data('branch'));
    $('#company_policies input[name="title"]').val($(invoker).data('title'));
    $('#company_policies textarea[name="details"]').jqteVal($(invoker).data('details'));
	$('#company_policies textarea[name="details"]').val($(invoker).data('details'));
    $('#company_policies').modal('show');
    $('.add-title').addClass('hide');
  }

  // Form handler
  function manage_policy(form) {
    var formData = new FormData(form);
    var url = form.action;
    
    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        window.location.reload();
      },
      error: function(xhr, status, error) {
        alert('Error uploading files: ' + error);
      }
    });
    return false;
  }

  function togglePolicyStatus(id, currentStatus) {
    $.post(admin_url + 'hrd/toggle_company_policies/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
      if (response.success) {
        var label = $('#status-label-' + id + ' span');
        if (response.new_status == 1) {
          label.removeClass('label-danger').addClass('label-success').text('Active');
        } else {
          label.removeClass('label-success').addClass('label-danger').text('Deactive');
        }
        $('#status-label-' + id).attr('onclick', 'togglePolicyStatus(' + id + ', ' + response.new_status + ')');
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
