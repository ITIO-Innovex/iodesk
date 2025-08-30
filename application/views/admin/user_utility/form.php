<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($form); ?>
<style>
.checkbox input[type=checkbox], .checkbox input[type=radio] {
    opacity: 1 !important;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open(current_url(), array('id' => 'user-utility-form')); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
                                    <a href="<?php echo admin_url('user_utility'); ?>" class="btn btn-default pull-right">
                                        <i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?>
                                    </a>
                                </h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
<?php echo render_input('form_name', 'Form Name', isset($form) ? $form->form_name : '', 'text', array('required' => true)); ?>

<div class="form-group">
                <label for="task_owner" class="control-label"><?php echo _l('Assign To'); ?></label>
                <select class="form-control selectpicker" id="share_with" name="share_with[]" multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" title="Assign To">
                  <?php if (isset($staff_members) && is_array($staff_members)) { ?>
                    <?php foreach ($staff_members as $staff) { ?>
                      <?php 
                      $selected = '';
                      if (isset($form)&&$form->share_with) {
                          $ownerIds = explode(',', $form->share_with);
                          if (in_array($staff['staffid'], $ownerIds)) {
                              $selected = 'selected';
                          }
                      }
                      ?>
                      <option data-content='<span class="tw-inline-flex tw-items-center"><?php echo str_replace("'", "&apos;", staff_profile_image($staff['staffid'], ["tw-h-6 tw-w-6 tw-rounded-full tw-inline-block tw-mr-2 tw-ring-2 tw-ring-white"], "small")); ?><span><?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?></span></span>' value="<?php echo $staff['staffid']; ?>" <?php echo $selected; ?>>
                        <?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?>
                      </option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Form Fields</h5>
                                <div id="form-fields-container">
                                    <?php if (isset($form) && !empty($form->form_fields)) { 
                                        foreach ($form->form_fields as $index => $field) { ?>
                                    <div class="form-field-row" data-index="<?php echo $index; ?>">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <label>Field Name</label>
                                                        <input type="text" name="form_fields[<?php echo $index; ?>][name]" 
                                                               class="form-control field-name" 
                                                               value="<?php echo $field['name']; ?>" required>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Field Type</label>
<select name="form_fields[<?php echo $index; ?>][type]" class="form-control field-type" required>
<option value="text" <?php echo $field['type'] == 'text' ? 'selected' : ''; ?>>Text</option>
<option value="textarea" <?php echo $field['type'] == 'textarea' ? 'selected' : ''; ?>>Textarea</option>
<option value="editor" <?php echo $field['type'] == 'editor' ? 'selected' : ''; ?>>Editor</option>
<option value="listbox" <?php echo $field['type'] == 'listbox' ? 'selected' : ''; ?>>Listbox</option>
<option value="radio" <?php echo $field['type'] == 'radio' ? 'selected' : ''; ?>>Radio</option>
<option value="checkbox" <?php echo $field['type'] == 'checkbox' ? 'selected' : ''; ?>>Checkbox</option>
<option value="datetime" <?php echo $field['type'] == 'datetime' ? 'selected' : ''; ?>>Date/Time</option>
<option value="file" <?php echo $field['type'] == 'file' ? 'selected' : ''; ?>>File</option>
</select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label>Options (for listbox/radio/checkbox)</label>
                                                        <input type="text" name="form_fields[<?php echo $index; ?>][options]" 
                                                               class="form-control field-options" 
                                                               value="<?php echo isset($field['options']) ? $field['options'] : ''; ?>"
                                                               placeholder="Option1,Option2,Option3">
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>Required</label>
                                                        <div class="checkbox">
                                                            <input type="checkbox" name="form_fields[<?php echo $index; ?>][required]" 
                                                                   value="1" <?php echo isset($field['required']) && $field['required'] ? 'checked' : ''; ?>>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <label>&nbsp;</label>
                                                        <div>
                                                            <button type="button" class="btn btn-danger btn-sm remove-field">
                                                                <i class="fa fa-remove"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } } ?>
                                </div>
                                
                                <button type="button" id="add-field" class="btn btn-success">
                                    <i class="fa fa-plus"></i> Add Field
                                </button>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <button type="submit" class="btn btn-info">
                                    <?php echo isset($form) ? _l('update') : _l('save'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>
<script>
$(document).ready(function() {
    var fieldIndex = <?php echo isset($form) && !empty($form->form_fields) ? count($form->form_fields) : 0; ?>;
    
    // Add new field
    $('#add-field').click(function() {
        var fieldHtml = `
        <div class="form-field-row" data-index="${fieldIndex}">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Field Name</label>
                            <input type="text" name="form_fields[${fieldIndex}][name]" 
                                   class="form-control field-name" required>
                        </div>
                        <div class="col-md-3">
                            <label>Field Type</label>
                            <select name="form_fields[${fieldIndex}][type]" 
                                    class="form-control field-type" required>
                                <option value="text">Text</option>
                                <option value="textarea">Textarea</option>
								<option value="editor">Editor</option>
                                <option value="listbox">Listbox</option>
                                <option value="radio">Radio</option>
                                <option value="checkbox">Checkbox</option>
                                <option value="datetime">Date/Time</option>
                                <option value="file">File</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Options (for listbox/radio/checkbox)</label>
                            <input type="text" name="form_fields[${fieldIndex}][options]" 
                                   class="form-control field-options" 
                                   placeholder="Option1,Option2,Option3">
                        </div>
                        <div class="col-md-1">
                            <label>Required</label>
                            <div class="checkbox">
                                <input type="checkbox" name="form_fields[${fieldIndex}][required]" value="1">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-danger btn-sm remove-field">
                                    <i class="fa fa-remove"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
        
        $('#form-fields-container').append(fieldHtml);
        fieldIndex++;
    });
    
    // Remove field
    $(document).on('click', '.remove-field', function() {
        $(this).closest('.form-field-row').remove();
        reindexFields();
    });
    
    // Reindex fields after removal
    function reindexFields() {
        $('#form-fields-container .form-field-row').each(function(index) {
            $(this).attr('data-index', index);
            $(this).find('input, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                    $(this).attr('name', newName);
                }
            });
        });
        fieldIndex = $('#form-fields-container .form-field-row').length;
    }
    
    // Form validation
    $('#user-utility-form').submit(function(e) {
        var formName = $('input[name="form_name"]').val().trim();
        var fieldCount = $('#form-fields-container .form-field-row').length;
        
        if (!formName) {
            alert('Please enter a form name');
            e.preventDefault();
            return false;
        }
        
        if (fieldCount === 0) {
            alert('Please add at least one field');
            e.preventDefault();
            return false;
        }
        
        // Validate field names
        var fieldNames = [];
        var isValid = true;
        
        $('#form-fields-container .field-name').each(function() {
            var fieldName = $(this).val().trim();
            if (!fieldName) {
                alert('All fields must have a name');
                isValid = false;
                return false;
            }
            if (fieldNames.indexOf(fieldName) !== -1) {
                alert('Field names must be unique: ' + fieldName);
                isValid = false;
                return false;
            }
            fieldNames.push(fieldName);
        });
        
        if (!isValid) {
            e.preventDefault();
            return false;
        }
    });
});
</script>