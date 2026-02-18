<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Daily Activity Report - List
		<a href="<?php echo admin_url('hrd/dar');?>" class="btn btn-primary pull-right" ><i class="fa-regular fa-plus"></i> Add New DAR</a>
		</h4>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($dars)) { ?>
              <table class="table dt-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <?php /*?><th>Description</th><?php */?>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dars as $dar) { ?>
                    <?php
                      $files = [];
                      if (!empty($dar['file'])) {
                        $files = array_filter(array_map('trim', explode(',', $dar['file'])));
                      }
                      $desc = htmlspecialchars($dar['descriptions'] ?? '', ENT_QUOTES, 'UTF-8');
                      $filesJson = htmlspecialchars(json_encode(array_values($files)), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                      <td><?php echo e(date('d-m-Y', strtotime($dar['addedon']))); ?></td>
                      <td>
                        <?php if ((int)$dar['status'] === 1) { ?>
                          <span class="label label-success">Submitted</span>
                        <?php } else { ?>
                          <span class="label label-default">Draft</span>
                        <?php } ?>
                      </td>
                      <?php /*?><td><?php echo e(wordwrap(strip_tags($dar['descriptions'] ?? ''), 60, "\n", true)); ?></td><?php */?>
                      <td>
                        <button type="button"
                                class="btn btn-info btn-xs dar-view"
                                data-id="<?php echo (int) $dar['id']; ?>"
                                data-status="<?php echo (int) $dar['status']; ?>"
                                data-description="<?php echo $desc; ?>"
                                data-files="<?php echo $filesJson; ?>">
                          View
                        </button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <div class="alert alert-info">No DAR records found.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dar_view_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="dar-update-form" enctype="multipart/form-data">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <input type="hidden" name="dar_id" id="dar-id" value="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">DAR Details</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Description <span class="text-danger" id="desc-required" style="display:none;">*</span></label>
            <div class="well well-sm" style="min-height:120px;">
              <textarea id="dar-view-description" name="description" class="form-control editor"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label>Existing Attachments</label>
            <div id="dar-view-files"></div>
          </div>
          <div class="form-group" id="new-attachments-group" style="display:none;">
            <label>Add New Attachments</label>
            <input type="file" name="dar_files[]" id="dar-new-files" class="form-control" multiple>
            <div id="dar-new-files-list" class="tw-mt-2"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="dar-update-btn" style="display:none;">
            <i class="fa-solid fa-save tw-mr-1"></i> Update DAR
          </button>
          <button type="button" class="btn btn-primary" id="dar-submit-btn" style="display:none;">
            <i class="fa-solid fa-paper-plane tw-mr-1"></i> Submit DAR
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
  $('.editor').jqte();
</script>
<script>
  $(function() {
    var currentDarId = 0;
    var currentDarStatus = 0;
    var currentFiles = [];
    var newFilesStore = [];
    
    // File input management
    var $fileInput = $('#dar-new-files');
    var $fileList = $('#dar-new-files-list');
    
    function renderNewFilesList() {
      $fileList.empty();
      if (newFilesStore.length === 0) return;
      var $ul = $('<ul class="list-unstyled mb-0"></ul>');
      newFilesStore.forEach(function(file, index) {
        var $li = $('<li class="tw-my-1"></li>');
        var $name = $('<span></span>').text(file.name + ' (' + Math.round(file.size / 1024) + ' KB)');
        var $btn = $('<button type="button" class="btn btn-xs btn-danger ml-2">Remove</button>');
        $btn.on('click', function() {
          newFilesStore.splice(index, 1);
          renderNewFilesList();
        });
        $li.append($name).append($btn);
        $ul.append($li);
      });
      $fileList.append($ul);
    }
    
    $fileInput.on('change', function() {
      var files = Array.from($fileInput[0].files);
      files.forEach(function(file) {
        newFilesStore.push(file);
      });
      renderNewFilesList();
      $fileInput.val('');
    });
    
    $('.dar-view').on('click', function() {
      var desc = $(this).data('description') || '';
      var filesData = $(this).data('files');
      var darId = $(this).data('id');
      var darStatus = $(this).data('status');
      var files = [];
      
      currentDarId = darId;
      currentDarStatus = darStatus;
      $('#dar-id').val(darId);
      
      if (Array.isArray(filesData)) {
        files = filesData;
      } else if (typeof filesData === 'string') {
        try { files = JSON.parse(filesData); } catch (e) { files = []; }
      } else if (filesData) {
        files = [filesData];
      }
      currentFiles = files;

      var decoded = $('<textarea/>').html(desc).text();
      if (decoded) {
        $('#dar-view-description').jqteVal(decoded);
      } else {
        $('#dar-view-description').jqteVal('');
      }
      
      // Check if draft - enable/disable editor
      var isDraft = (darStatus == 2 || darStatus == 0);
      if (isDraft) {
        $('.jqte').css('opacity', '1');
        $('.jqte_editor').attr('contenteditable', 'true');
      } else {
        $('.jqte').css('opacity', '0.8');
        $('.jqte_editor').attr('contenteditable', 'false');
      }

      // Render existing files with delete option for drafts
      if (!files.length) {
        $('#dar-view-files').html('<span class="text-muted">No attachments</span>');
      } else {
        var list = '<ul class="list-unstyled">';
        for (var i = 0; i < files.length; i++) {
          var f = files[i];
          var name = f.split('/').pop();
          list += '<li class="tw-my-1">';
          list += '<a href="<?php echo base_url(''); ?>' + f + '" target="_blank">' + $('<div>').text(name).html() + '</a>';
          if (isDraft) {
            list += ' <button type="button" class="btn btn-xs btn-danger delete-existing-file" data-file="' + f + '"><i class="fa fa-trash"></i></button>';
          }
          list += '</li>';
        }
        list += '</ul>';
        $('#dar-view-files').html(list);
      }
      
      // Show/hide buttons based on status
      if (isDraft) {
        $('#dar-submit-btn').show();
        $('#dar-update-btn').show();
        $('#new-attachments-group').show();
        $('#desc-required').show();
      } else {
        $('#dar-submit-btn').hide();
        $('#dar-update-btn').hide();
        $('#new-attachments-group').hide();
        $('#desc-required').hide();
      }
      
      $('#dar_view_modal').modal('show');
    });
    
    // Delete existing file
    $(document).on('click', '.delete-existing-file', function() {
      var file = $(this).data('file');
      if (!currentDarId || !file) return;
      if (!confirm('Delete this attachment?')) return;
      
      var $btn = $(this);
      $btn.prop('disabled', true);
      
      $.post('<?php echo admin_url('hrd/dar_delete_file'); ?>/' + currentDarId, {
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>',
        file: file
      }, function(resp) {
        if (resp && resp.success) {
          $btn.closest('li').remove();
          alert_float('success', 'File deleted');
        } else {
          $btn.prop('disabled', false);
          alert_float('warning', 'Failed to delete file');
        }
      }, 'json');
    });
    
    // Handle update button click
    $('#dar-update-btn').on('click', function() {
      if (!currentDarId) {
        alert_float('warning', 'Invalid DAR');
        return;
      }
      
      //var description = $('#dar-view-description').val();
	  var description = $('#dar-view-description').jqteVal();
	  //alert(description);
      if ($.trim(description).length < 5) {
        alert_float('warning', 'Description is required (minimum 5 characters)');
        return;
      }
      
      var $btn = $(this);
      $btn.html('<i class="fa-solid fa-spinner fa-spin-pulse"></i> Updating...').prop('disabled', true);
      
      var formData = new FormData();
      formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
      formData.append('dar_id', currentDarId);
      formData.append('description', description);
      
      // Add new files
      newFilesStore.forEach(function(file) {
        formData.append('dar_files[]', file);
      });
      
      $.ajax({
        url: '<?php echo admin_url('hrd/dar_update'); ?>',
        method: 'POST',
        data: formData,
		dataType: "json", 
        processData: false,
        contentType: false,
        success: function(resp) {
          $btn.html('<i class="fa-solid fa-save tw-mr-1"></i> Update DAR').prop('disabled', false);
          alert(resp);
		  console.log(resp);
          if (resp && (resp.success === true || resp.success === "true")) {
            alert_float('success', resp.message || 'DAR updated successfully');
            $('#dar_view_modal').modal('hide');
            location.reload();
          } else {  alert(resp.success);
            alert_float('warning', resp && resp.message ? resp.message : 'Failed to update DAR !!');
          }
        },
        error: function() {
          $btn.html('<i class="fa-solid fa-save tw-mr-1"></i> Update DAR').prop('disabled', false);
          alert_float('danger', 'Failed to update DAR. Please try again.');
        }
      });
    });
    
    // Handle submit button click
    $('#dar-submit-btn').on('click', function() {
      if (!currentDarId) {
        alert_float('warning', 'Invalid DAR');
        return;
      }
      
      var description = $('#dar-view-description').val();
      if ($.trim(description).length < 5) {
        alert_float('warning', 'Description is required (minimum 5 characters)');
        return;
      }
      
      if (!confirm('Are you sure you want to submit this DAR? Once submitted, it cannot be edited.')) {
        return;
      }
      
      var $btn = $(this);
      $btn.html('<i class="fa-solid fa-spinner fa-spin-pulse"></i> Submitting...').prop('disabled', true);
      
      var formData = new FormData();
      formData.append('<?php echo $this->security->get_csrf_token_name(); ?>', '<?php echo $this->security->get_csrf_hash(); ?>');
      formData.append('dar_id', currentDarId);
      formData.append('description', description);
      formData.append('submit_dar', '1');
      
      // Add new files
      newFilesStore.forEach(function(file) {
        formData.append('dar_files[]', file);
      });
      
      $.ajax({
        url: '<?php echo admin_url('hrd/dar_update'); ?>',
        method: 'POST',
        data: formData,
		dataType: "json", 
        processData: false,
        contentType: false,
        success: function(resp) {
          $btn.html('<i class="fa-solid fa-paper-plane tw-mr-1"></i> Submit DAR').prop('disabled', false);
          
          
          if (resp && (resp.success === true || resp.success === "true")) {
            alert_float('success', resp.message || 'DAR submitted successfully');
            $('#dar_view_modal').modal('hide');
            location.reload();
          } else {
            alert_float('warning', resp && resp.message ? resp.message : 'Failed to submit DAR');
          }
        },
        error: function() {
          $btn.html('<i class="fa-solid fa-paper-plane tw-mr-1"></i> Submit DAR').prop('disabled', false);
          alert_float('danger', 'Failed to submit DAR. Please try again.');
        }
      });
    });
    
    // Reset on modal close
    $('#dar_view_modal').on('hidden.bs.modal', function() {
      currentDarId = 0;
      currentDarStatus = 0;
      currentFiles = [];
      newFilesStore = [];
      $('#dar-id').val('');
      $('#dar-submit-btn').hide();
      $('#dar-update-btn').hide();
      $('#new-attachments-group').hide();
      $('#desc-required').hide();
      $fileList.empty();
    });
  });
</script>
</body></html>
