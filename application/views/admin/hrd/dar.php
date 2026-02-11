<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($dar); ?>
<style>
  .jqte_tool.jqte_tool_1 .jqte_tool_label { height: 20px !important; }
  .jqte { margin: 10px 0 !important; }
  .jqte_editor { height: 300px !important; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div>
               <h4 class="tw-mt-0 tw-font-semibold tw-text-lg"> Daily Activity Report (DAR) 
			   <a href="<?php echo admin_url('hrd/dar_list');?>" id="upgrade_plan" class="btn btn-info btn-sm pull-right"><i class="fa-regular fa-eye"></i> View Send DAR</a>
			   </h4>
                <hr class="hr-panel-heading">
                </div>
            <?php echo form_open_multipart(admin_url('hrd/dar'), ['id' => 'dar-form']); ?>
              <input type="hidden" name="status" id="dar-status" value="2">
              <div class="form-group">
                <label for="dar-description">Activity Description</label>
                <textarea id="dar-description" name="description" class="form-control editor" rows="6" required><?php echo isset($dar['descriptions']) ? $dar['descriptions'] : ''; ?></textarea>
              </div>
              <div class="form-group">
			  <div class="row">
      <div class="col-md-6">
	  <label for="dar-files">Attach File (Optional)</label>
      <input type="file" name="dar_files[]" id="dar-files" class="form-control" multiple>
      <p class="text-muted mtop5">You can select multiple files.</p>
      <div id="dar-selected-files" class="mtop10"></div>
	  </div>
	   <div class="col-md-68">
	   <?php if (!empty($dar['file'])) { ?>
                  <?php $darFiles = array_filter(array_map('trim', explode(',', $dar['file']))); ?>
                  <?php if (!empty($darFiles)) { ?>
                    <div class="mtop10">
                      <strong>Existing Attachments:</strong>
                      <ul class="list-unstyled">
                        <?php foreach ($darFiles as $file) { ?>
                          <li class="tw-flex tw-items-center tw-gap-2">
                            <a href="<?php echo base_url($file); ?>" target="_blank"><?php echo e(basename($file)); ?></a>
                            <button type="button" class="btn btn-xs btn-danger dar-delete-file" data-id="<?php echo (int) ($dar['id'] ?? 0); ?>" data-file="<?php echo e(basename($file)); ?>">Delete</button>
                          </li>
                        <?php } ?>
                      </ul>
                    </div>
                  <?php } ?>
                <?php } ?>
	  </div>
	  </div>
			  
                
				
				
                
				
				
              </div>
              <div class="tw-flex tw-gap-2">
			  <?php if (!isset($dar['status']) || (int)$dar['status'] !== 1) { ?>
                <button type="button" class="btn btn-default" data-status="2" id="dar-save-later" onclick="return confirm('Data can be saved as draft. Once submitted, editing is disabled.')">Save as Draft & Submit Later</button>
                <button type="button" class="btn btn-primary" data-status="1" id="dar-save-submit" onclick="return confirm('Once submitted, you won?t be able to edit this data. Do you want to continue?')">Submit</button>
				<?php }else{?>
				<span class="btn btn-success">DAR Submitted </span>
				<?php }?>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
  $(function() {
    $('.editor').jqte();
    $('#dar-save-later, #dar-save-submit').on('click', function() {
      var status = $(this).data('status');
      $('#dar-status').val(status);
      $('#dar-form').submit();
    });

    var $fileInput = $('#dar-files');
    var $selectedList = $('#dar-selected-files');
    var selectedFiles = [];

    function renderSelectedFiles() {
      if (!selectedFiles.length) {
        $selectedList.html('');
        return;
      }
      var html = '<strong>Selected Files:</strong><ul class="list-unstyled">';
      for (var i = 0; i < selectedFiles.length; i++) {
        var f = selectedFiles[i];
        html += '<li class="tw-flex tw-items-center tw-gap-2">' +
          '<span>' + $('<div>').text(f.name).html() + '</span>' +
          '<button type="button" class="btn btn-xs btn-danger dar-remove-selected" data-index="' + i + '">Remove</button>' +
        '</li>';
      }
      html += '</ul>';
      $selectedList.html(html);
    }

    function syncInputFiles() {
      var dt = new DataTransfer();
      for (var i = 0; i < selectedFiles.length; i++) {
        dt.items.add(selectedFiles[i]);
      }
      $fileInput[0].files = dt.files;
    }

    $fileInput.on('change', function() {
      var files = Array.prototype.slice.call(this.files || []);
      if (!files.length) {
        selectedFiles = [];
        renderSelectedFiles();
        return;
      }
      selectedFiles = selectedFiles.concat(files);
      syncInputFiles();
      renderSelectedFiles();
    });

    $selectedList.on('click', '.dar-remove-selected', function() {
      var index = parseInt($(this).data('index'), 10);
      if (isNaN(index)) { return; }
      selectedFiles.splice(index, 1);
      syncInputFiles();
      renderSelectedFiles();
    });

    $('.dar-delete-file').on('click', function() {
      var $btn = $(this);
      var id = $btn.data('id');
      var file = $btn.data('file');
      if (!id || !file) { return; }
      if (!confirm('Delete this file?')) { return; }
      $.post('<?php echo admin_url('hrd/dar_delete_file'); ?>/' + id, { file: file }, function(resp) {
        if (resp && resp.success) {
          location.reload();
        } else {
          alert('Failed to delete file');
        }
      }, 'json');
    });
  });
</script>
</body></html>
