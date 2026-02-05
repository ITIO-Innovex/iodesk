<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.radio input[type=radio] {opacity: 1;}
.checkbox input[type=checkbox]{opacity: 1;}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
			<div id="loader-project"><i class="fa-solid fa-spinner fa-spin fa-5x text-warning"></i></div>
                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
                                    <a href="<?php echo admin_url('user_utility'); ?>" class="btn btn-default pull-right">
                                        <i class="fa fa-arrow-left"></i> <?php echo _l('Back'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('user_utility/edit/' . $form->id); ?>" class="btn btn-info pull-right" style="margin-right: 10px;">
                                        <i class="fa fa-edit"></i> Edit Form
                                    </a>
<select class="form-control pull-right document-status-select" data-doc-id="<?php echo $form->id;?>" name="doc_status" style="width:100px;margin-right: 10px;color:#FFFFFF; <?php if($form->status == 2){ ?> background:#FF0000;  <?php  }else{?> background:#00CC33; <?php } ?>"  title="Document Status">
<option value="2" <?php if($form->status == 2){ ?>  selected="selected" <?php  } ?>>Process</option>
<option value="1" <?php if($form->status == 1){ ?>  selected="selected" <?php  } ?>>Completed</option>
</select>
                                </h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>
                        
                        <?php if (!empty($form->form_data)) { ?>
                        <div class="alert alert-info">
                            <strong>Note:</strong> This form already has saved data. Submitting will overwrite the existing data.
                        </div>
                        <?php } ?>
                        
                        <div class="row">
                            <div class="col-md-8">
							 <?php echo form_open_multipart(current_url(), array('id' => 'dynamic-form')); ?>
<input type="hidden" name="falsedata" value="1" />
                                <?php foreach ($form->form_fields as $field) { 
                                    $field_name = $field['name'];
                                    $field_type = $field['type'];
                                    $field_required = isset($field['required']) && $field['required'];
                                    $field_value = isset($form->form_data[$field_name]) ? $form->form_data[$field_name] : '';
                                    $field_options = isset($field['options']) ? explode(',', $field['options']) : [];
                                ?>

                                <div class="form-group">
                                    <label for="<?php echo $field_name; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $field_name)); ?>
                                        <?php if ($field_required) { ?>
                                            <span class="text-danger">*</span>
                                        <?php } ?>
                                    </label>
                                    
                                    <?php if ($field_type === 'text') { ?>
                                        <input type="text" 
                                               name="<?php echo $field_name; ?>" 
                                               id="<?php echo $field_name; ?>"
                                               class="form-control" 
                                               value="<?php echo htmlspecialchars($field_value); ?>"
                                               <?php echo $field_required ? 'required' : ''; ?>>
                                               
                                    <?php } elseif ($field_type === 'textarea') { ?>
                                        <textarea name="<?php echo $field_name; ?>" 
                                                  id="<?php echo $field_name; ?>"
                                                  class="form-control" 
                                                  rows="4"
                                                  <?php echo $field_required ? 'required' : ''; ?>><?php echo htmlspecialchars($field_value); ?></textarea>
												  
												  <?php } elseif ($field_type === 'editor') { ?>
                                        <textarea name="<?php echo $field_name; ?>" 
                                                  id="<?php echo $field_name; ?>"
                                                  class="form-control editor" 
                                                  rows="4"
                                                  <?php echo $field_required ? 'required' : ''; ?>><?php echo htmlspecialchars($field_value); ?></textarea>
                                                  
                                    <?php } elseif ($field_type === 'listbox') { ?>
                                        <select name="<?php echo $field_name; ?>" 
                                                id="<?php echo $field_name; ?>"
                                                class="form-control" 
                                                <?php echo $field_required ? 'required' : ''; ?>>
                                            <option value="">Select an option</option>
                                            <?php foreach ($field_options as $option) { 
                                                $option = trim($option);
                                            ?>
                                                <option value="<?php echo $option; ?>" 
                                                        <?php echo $field_value === $option ? 'selected' : ''; ?>>
                                                    <?php echo $option; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                        
                                    <?php } elseif ($field_type === 'radio') { ?>
                                        <?php foreach ($field_options as $option) { 
                                            $option = trim($option);
                                        ?>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" 
                                                       name="<?php echo $field_name; ?>" 
                                                       value="<?php echo $option; ?>"
                                                       <?php echo $field_value === $option ? 'checked' : ''; ?>
                                                       <?php echo $field_required ? 'required' : ''; ?>>
                                                <?php echo $option; ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                        
                                    <?php } elseif ($field_type === 'checkbox') { ?>
                                        <?php 
                                        $selected_values = is_array($field_value) ? $field_value : [];
                                        foreach ($field_options as $option) { 
                                            $option = trim($option);
                                        ?>
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" 
                                                       name="<?php echo $field_name; ?>[]" 
                                                       value="<?php echo $option; ?>"
                                                       <?php echo in_array($option, $selected_values) ? 'checked' : ''; ?>>
                                                <?php echo $option; ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                        
                                    <?php } elseif ($field_type === 'datetime') { ?>
                                        <input type="datetime-local" 
                                               name="<?php echo $field_name; ?>" 
                                               id="<?php echo $field_name; ?>"
                                               class="form-control" 
                                               value="<?php echo $field_value ? date('Y-m-d\TH:i', strtotime($field_value)) : ''; ?>"
                                               <?php echo $field_required ? 'required' : ''; ?>>
                                               
                                    <?php } elseif ($field_type === 'file') { ?>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="tw-flex tw-items-center tw-gap-2">
                                                    <input type="file"
                                                           name="<?php echo $field_name; ?>[]"
                                                           id="<?php echo $field_name; ?>"
                                                           class="form-control user-utility-file-input"
                                                           data-field="<?php echo $field_name; ?>"
                                                           multiple>
                                                    <button type="button" class="btn btn-default add-more-files" data-target="<?php echo $field_name; ?>">Add another</button>
                                                </div>
                                                <div class="selected-files mtop10" data-field="<?php echo $field_name; ?>"></div>
                                                <?php //echo $field_required ? 'required' : ''; ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if ($field_value) { ?>
                                                    <label class="control-label">Current Saved Data</label>
                                                    <div class="text-muted">
                                                        <?php if (is_array($field_value)) { ?>
                                                            <?php foreach ($field_value as $file) { ?>
                                                                <div class="tw-flex tw-items-center tw-justify-between tw-border tw-border-solid tw-border-neutral-200 tw-rounded tw-px-3 tw-py-2 tw-mb-2">
                                                                    <a href="<?php echo base_url('uploads/user_utility/' . $file); ?>" target="_blank">
                                                                        <i class="fa-regular fa-file-lines tw-mr-1"></i><?php echo $file; ?>
                                                                    </a>
                                                                    <!-- <button type="button" class="btn btn-danger btn-xs remove-existing-file" data-field="<?php echo $field_name; ?>" data-file="<?php echo $file; ?>">Delete !!</button> -->
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <div class="tw-flex tw-items-center tw-justify-between tw-border tw-border-solid tw-border-neutral-200 tw-rounded tw-px-3 tw-py-2 tw-mb-2">
                                                                <a href="<?php echo base_url('uploads/user_utility/' . $field_value); ?>" target="_blank">
                                                                    <i class="fa-regular fa-file-lines tw-mr-1"></i><?php echo $field_value; ?>
                                                                </a>
                                                                <button type="button" class="btn btn-danger btn-xs remove-existing-file" data-field="<?php echo $field_name; ?>" data-file="<?php echo $field_value; ?>">Delete ##</button>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="deleted-files" data-field="<?php echo $field_name; ?>"></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                
                                <?php } ?>
                               <?php if (is_admin() || $form->created_by==get_staff_user_id()) { ?>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Save Form Data
                                    </button>
                                </div>
								<?php } ?>
<?php echo form_close(); ?>

<div class="tw-p-2 mail-bg tw-rounded">
<div class="form-group tw-p-2">							
<a class="btn btn-danger tw-p-2 pull-right" id="toggleCommentBtn">Add Comment / Reply</a>
</div>
<div class="clearfix"></div>
<!-- Comment Form (hidden by default) -->
<div id="commentForm" class=" margin-top-50" style="display: none; margin-top: 10px;">

  <?php echo form_open(admin_url('user_utility/addcomment'), ['id' => 'addcomment-form']); ?>
  <input type="hidden" name="tid" value="<?php echo $form->id; ?>" />
   <div class="form-group">
    <textarea name="comment" placeholder="Write your comment..." class="form-control editor" required></textarea>
	</div>
	 <div class="form-group">
    <button class="btn btn-success" type="submit"><i class='fa-solid fa-comment'></i> Submit</button>
	</div>
  <?php echo form_close(); ?>
</div>
</div>


<div class="tw-p-2 mail-bg tw-rounded">
<div class="form-group">							
<h4 >Comments (<?php echo count($commentlist);?>)</h4>
</div>
<div class="clearfix"></div>
<!-- Comment Form (hidden by default) -->
<?php if (!empty($commentlist)) { ?>
    <div class="">
        <?php foreach ($commentlist as $c) { ?>
		<div class="tw-p-2 tw-my-2 modal-content">
		<h4 class="panel-title">Added on <?php echo $c->date_created ?> By : <?php echo get_staff_full_name($c->created_by); ?></h4>
            <div class="tw-my-2">
                <strong>Comment:</strong><?php echo  $c->comment ?><br>
            </div>
			</div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p>No comments found.</p>
<?php } ?>

</div>


                            </div>
                            
                            <div class="col-md-4">
                                <?php if (!empty($form->form_data)) { ?>
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Current Saved Data</h4>
                                    </div>
                                    <div class="panel-body">
                                        <?php foreach ($form->form_data as $key => $value) { ?>
                                        <div class="form-group" style="word-break: break-word !important;">
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</strong><br>
                                            <?php 
                                            if (is_array($value)) {
											
                                                echo implode(', ', $value);
												
                                            } elseif (!empty($value) && strpos((string)$value, '.') !== false && file_exists('./uploads/user_utility/' . $value)) {
                                                echo '<a href="' . base_url('uploads/user_utility/' . $value) . '" target="_blank">' . $value . '</a>';
                                            } else {
                                                echo $value;
                                            }
                                            ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
								
								<?php if (!empty($form->share_with) && $form->share_with !== null) { ?>
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Share With</h4>
                                    </div>
                                    <div class="panel-body">
                                        
										<?php
										if (isset($form)&&$form->share_with) {
                                        $numbers = explode(',', $form->share_with);
										foreach ($numbers as $num) {
                                        //echo $num . "<br>";
										echo "<p><strong><i class='fa-solid fa-comment'></i> ".get_staff_full_name($num) . "</strong><br>"; 
                                        }
                                        
                                        }
										?>

                                    </div>
                                </div>
                                <?php } ?>
                                
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">Form Information</h4>
                                    </div>
                                    <div class="panel-body">
                                        <p><strong>Form ID:</strong> <?php echo $form->id; ?></p>
                                        <p><strong>Created:</strong> <?php echo _dt($form->date_created); ?></p>
                                        <?php if ($form->date_updated) { ?>
                                        <p><strong>Last Updated:</strong> <?php echo _dt($form->date_updated); ?></p>
                                        <?php } ?>
                                        <p><strong>Fields Count:</strong> <?php echo count($form->form_fields); ?></p>
                                    </div>
                                </div>
								
								
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script> $('.editor').jqte(); </script>

<script>
$(document).ready(function() {
    // Form validation
    $('#dynamic-form').submit(function(e) {
        var isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('has-error');
            } else {
                $(this).removeClass('has-error');
            }
        });
        
        // Check required radio buttons
        $('input[type="radio"][required]').each(function() {
            var name = $(this).attr('name');
            if (!$('input[name="' + name + '"]:checked').length) {
                isValid = false;
                $(this).closest('.form-group').addClass('has-error');
            } else {
                $(this).closest('.form-group').removeClass('has-error');
            }
        });
        
        if (!isValid) {
            alert('Please fill in all required fields');
            e.preventDefault();
            return false;
        }
    });
    
    // Remove error styling on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('has-error');
        $(this).closest('.form-group').removeClass('has-error');
    });
});
</script>
<script>
  window.userUtilityFileStore = window.userUtilityFileStore || {};

  function uuRenderSelectedFiles(field) {
    var list = $('.selected-files[data-field="' + field + '"]');
    list.empty();
    var files = window.userUtilityFileStore[field] || [];
    files.forEach(function(file, idx) {
      var row = $('<div class="tw-flex tw-items-center tw-justify-between tw-border tw-border-solid tw-border-neutral-200 tw-rounded tw-px-3 tw-py-2 tw-mb-2"></div>');
      var name = $('<span class="tw-text-sm tw-text-neutral-700"></span>').text(file.name);
      var removeBtn = $('<button type="button" class="btn btn-danger btn-xs">Delete</button>');
      removeBtn.attr('data-field', field).attr('data-index', idx);
      row.append(name).append(removeBtn);
      list.append(row);
    });
  }

  function uuRebuildInput(field) {
    var input = document.getElementById(field);
    if (!input) {
      return;
    }
    var dt = new DataTransfer();
    (window.userUtilityFileStore[field] || []).forEach(function(file){
      dt.items.add(file);
    });
    input.files = dt.files;
  }

  function uuAddFiles(field, files) {
    if (!window.userUtilityFileStore[field]) {
      window.userUtilityFileStore[field] = [];
    }
    for (var i = 0; i < files.length; i++) {
      var f = files[i];
      var exists = window.userUtilityFileStore[field].some(function(existing){
        return existing.name === f.name && existing.size === f.size && existing.lastModified === f.lastModified;
      });
      if (!exists) {
        window.userUtilityFileStore[field].push(f);
      }
    }
    uuRebuildInput(field);
    uuRenderSelectedFiles(field);
  }

  $('.add-more-files').on('click', function(){
    var target = $(this).data('target');
    $('#' + target).click();
  });

  $('body').on('change', '.user-utility-file-input', function(){
    var field = $(this).data('field');
    var files = this.files;
    if (files && files.length > 0) {
      uuAddFiles(field, files);
    } else {
      uuRenderSelectedFiles(field);
    }
  });

  $('body').on('click', '.selected-files button[data-index]', function(){
    var field = $(this).data('field');
    var idx = parseInt($(this).data('index'), 10);
    if (!isNaN(idx) && window.userUtilityFileStore[field]) {
      window.userUtilityFileStore[field].splice(idx, 1);
      uuRebuildInput(field);
      uuRenderSelectedFiles(field);
    }
  });

  $('body').on('click', '.remove-existing-file', function(){
    var field = $(this).data('field');
    var file = $(this).data('file');
    var container = $('.deleted-files[data-field="' + field + '"]');
    if (container.length) {
      var input = $('<input type="hidden" name="delete_files[' + field + '][]">').val(file);
      container.append(input);
    }
    $(this).closest('div').remove();
  });
</script>
<!-- Script -->
<script>
  $(document).ready(function(){
    $("#toggleCommentBtn").click(function(){
      $("#commentForm").toggle(); // Show/Hide on each click
    });
  });
</script>