<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open_multipart(current_url(), array('id' => 'dynamic-form')); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
                                    <a href="<?php echo admin_url('user_utility'); ?>" class="btn btn-default pull-right">
                                        <i class="fa fa-arrow-left"></i> <?php echo _l('back'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('user_utility/edit/' . $form->id); ?>" class="btn btn-info pull-right" style="margin-right: 10px;">
                                        <i class="fa fa-edit"></i> Edit Form
                                    </a>
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
                                        <input type="file" 
                                               name="<?php echo $field_name; ?>" 
                                               id="<?php echo $field_name; ?>"
                                               class="form-control" 
                                               <?php echo $field_required ? 'required' : ''; ?>>
                                        <?php if ($field_value) { ?>
                                            <small class="text-muted">
                                                Current file: <a href="<?php echo base_url('uploads/user_utility/' . $field_value); ?>" target="_blank"><?php echo $field_value; ?></a>
                                            </small>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                                
                                <?php } ?>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Save Form Data
                                    </button>
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
                                        <div class="form-group">
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $key)); ?>:</strong><br>
                                            <?php 
                                            if (is_array($value)) {
                                                echo implode(', ', $value);
                                            } elseif (strpos($value, '.') !== false && file_exists('./uploads/user_utility/' . $value)) {
                                                echo '<a href="' . base_url('uploads/user_utility/' . $value) . '" target="_blank">' . $value . '</a>';
                                            } else {
                                                echo htmlspecialchars($value);
                                            }
                                            ?>
                                        </div>
                                        <?php } ?>
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