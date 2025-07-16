<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo _l('customize_settings'); ?>
                        </h4>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active"><a href="#company-tab" role="tab" data-toggle="tab">Company</a></li>
                            <li><a href="#leads-tab" role="tab" data-toggle="tab">Leads</a></li>
                            <li><a href="#deal-tab" role="tab" data-toggle="tab">Deal</a></li>
                        </ul>
                        <div class="tab-content" style="margin-top:20px;">
                            <div class="tab-pane active" id="company-tab">
                        <?php echo form_open_multipart(admin_url('customize'), ['id' => 'customize-form']); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <?php $this->load->view('admin/customize/includes/appearance'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right">
                                    <i class="fa fa-check"></i> <?php echo _l('submit'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                            </div>
                            <div class="tab-pane" id="leads-tab">
                            <div id="deal-stage-default-list">
                                    <ol>
                                        <li>Step 1 : Unassigned</li>
                                        <li>Step 2 : Assigned</li>
                                        <li>Step 3 : Hot</li>
                                        <li>Step 4 : Junk Leads</li>
                                    </ol> 

                                </div>
                            </div>
                            <div class="tab-pane" id="deal-tab">
                                <div class="form-group">
                                    <label><input type="radio" name="deal_stage_type" value="default" checked> Default</label>
                                    <label style="margin-left:20px;"><input type="radio" name="deal_stage_type" value="customized"> Customized</label>
                                </div>
                                <div id="deal-stage-default-list-deal">
                                    <ol>
                                        <li>Step : New</li>
                                        <li>Step : Document</li>
                                        <li>Step : Under Writing with Approver</li>
                                        <li>Step : Final Invoice</li>
                                    </ol>
                                </div>
                                <div id="deal-stage-customized-list" style="display:none;">
                                    <div id="deal-stage-customized-content">
                                        <div id="deal-stage-customized-loading">Loading...</div>
                                        <ul id="customized-stage-list" class="list-group" style="margin-top:10px; display:none;"></ul>
                                        <div id="customized-default-checkbox-wrapper" style="margin-top:10px;">
                                          <label><input type="checkbox" id="customized-default-checkbox"> Set as Default</label>
                                        </div>
                                        <button id="save-customized-stages" class="btn btn-success" style="display:none; margin-top:10px;">Save</button>
                                        <div id="deal-stage-customized-save-msg" style="margin-top:10px;"></div>
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
<!-- Modal for Dynamic Form Builder -->
<div class="modal fade" id="customizedFormModal" tabindex="-1" role="dialog" aria-labelledby="customizedFormModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="customizedFormModalLabel">Customize Form Layout</h4>
      </div>
      <div class="modal-body">
        <div id="form-builder-fields"></div>
        <button id="add-field-btn" class="btn btn-default btn-xs" style="margin-bottom:10px;">Add Field</button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-form-layout">Save</button>
      </div>
    </div>
  </div>
</div>
<!-- Add a modal for viewing the form -->
<div class="modal fade" id="viewFormModal" tabindex="-1" role="dialog" aria-labelledby="viewFormModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="viewFormModalLabel">View Form</h4>
      </div>
      <div class="modal-body" id="view-form-content">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
<script>
$(function() {
    // Initialize form validation
    $('#customize-form').on('submit', function(e) {
        // Add any custom validation here if needed
        return true;
    });
    
    // Live preview functionality for company name
    $('input[name="settings[customize_company_name]"]').on('input', function() {
        var companyName = $(this).val();
        if (companyName) {
            $('#preview-company-name').text(companyName);
        } else {
            $('#preview-company-name').text('<?php echo _l('customize_sample_company_name'); ?>');
        }
    });
    
    // Live preview functionality for company domain
    $('input[name="settings[customize_company_domain]"]').on('input', function() {
        var companyDomain = $(this).val();
        if (companyDomain) {
            $('#preview-company-domain').text(companyDomain);
        } else {
            $('#preview-company-domain').text('<?php echo _l('customize_sample_domain'); ?>');
        }
    });
    
    // File upload preview for logo
    $('input[name="customize_company_logo"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-logo').html('<img src="' + e.target.result + '" alt="Logo Preview" style="max-height: 40px; max-width: 150px;" />');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // File upload preview for favicon
    $('input[name="customize_favicon"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-favicon').html('<img src="' + e.target.result + '" alt="Favicon Preview" style="max-height: 32px; max-width: 32px;" />');
            };
            reader.readAsDataURL(file);
        }
    });

    // Bootstrap tab navigation
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        e.target // newly activated tab
        e.relatedTarget // previous active tab
    });

    // Add a global to store which stages have a form layout
    var stageFormLayoutMap = {};
    // Update renderCustomizedStages to use stageFormLayoutMap for button color
    function renderCustomizedStages(stages, checkedMap) {
        var html = '';
        stages.forEach(function(stage, idx) {
            var checked = (checkedMap && checkedMap[stage.id] == 1) ? 'checked' : '';
            html += '<li class="list-group-item" data-id="' + stage.id + '">' +
                '<input type="checkbox" class="customized-stage-check" data-id="' + stage.id + '" ' + checked + '> ' +
                (stage.stage ? stage.stage : stage.name) +
                '<span class="handle" style="cursor:move; float:right;"><i class="fa fa-arrows"></i></span>';
            if (checked) {
                html += '<button class="btn btn-primary btn-xs customized-form-btn" style="float:right; margin-right:10px;">Customized Form</button>';
                var btnClass = 'btn-danger';
                if (stageFormLayoutMap[stage.id]) btnClass = 'btn-success';
                html += '<button class="btn ' + btnClass + ' btn-xs view-form-btn" style="float:right; margin-right:10px;" data-id="' + stage.id + '">View Form</button>';
            }
            html += '</li>';
        });
        $('#customized-stage-list').html(html).show();
        $('#save-customized-stages').show();
        $('#deal-stage-customized-loading').hide();
        $("#customized-stage-list").sortable({ handle: '.handle' });
    }
    // Fetch which stages have a form layout before rendering
    function fetchStageFormLayouts(stages, checkedMap) {
        var ids = stages.map(function(s) { return s.id; });
        $.ajax({
            url: '<?php echo admin_url('customize/get_form_layout_status'); ?>',
            type: 'POST',
            data: { ids: ids },
            dataType: 'json',
            success: function(res) {
                stageFormLayoutMap = res.statusMap || {};
                renderCustomizedStages(stages, checkedMap);
            },
            error: function() {
                stageFormLayoutMap = {};
                renderCustomizedStages(stages, checkedMap);
            }
        });
    }
    // After rendering customized stages, fetch deal_form_type and set the checkbox
    function setCustomizedDefaultCheckboxFromBackend() {
        $.ajax({
            url: '<?php echo admin_url('customize/get_company_deal_form_type'); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res && typeof res.deal_form_type !== 'undefined') {
                    $('#customized-default-checkbox').prop('checked', res.deal_form_type == 1);
                }
            }
        });
    }
    // Dynamically show/hide the buttons on check/uncheck
    $(document).on('change', '.customized-stage-check', function() {
        var li = $(this).closest('li');
        var id = li.data('id');
        if ($(this).is(':checked')) {
            if (li.find('.customized-form-btn').length === 0) {
                li.append('<button class="btn btn-primary btn-xs customized-form-btn" style="float:right; margin-right:10px;">Customized Form</button>');
            }
            if (li.find('.view-form-btn').length === 0) {
                li.append('<button class="btn btn-info btn-xs view-form-btn" style="float:right; margin-right:10px;" data-id="' + id + '">View Form</button>');
            }
        } else {
            li.find('.customized-form-btn').remove();
            li.find('.view-form-btn').remove();
        }
    });
    $('input[name="deal_stage_type"]').on('change', function() {
        if ($(this).val() === 'default') {
            $('#deal-stage-default-list-deal').show();
            $('#deal-stage-customized-list').hide();
        } else {
            $('#deal-stage-default-list-deal').hide();
            $('#deal-stage-customized-list').show();
            $('#deal-stage-customized-loading').show();
            $('#customized-stage-list').hide();
            $('#save-customized-stages').hide();
            $('#deal-stage-customized-save-msg').html('');
            // Fetch customized stages via AJAX
            $.ajax({
                url: '<?php echo admin_url('customize/get_deal_stages_customized'); ?>',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.success && res.stages.length > 0) {
                        fetchStageFormLayouts(res.stages, res.checkedMap);
                        setCustomizedDefaultCheckboxFromBackend();
                    } else {
                        $('#deal-stage-customized-content').html('<div class="text-danger">No customized stages found.</div>');
                    }
                },
                error: function() {
                    $('#deal-stage-customized-content').html('<div class="text-danger">Failed to load customized stages.</div>');
                }
            });
        }
    });
    $('#save-customized-stages').on('click', function() {
        var order = [];
        var checked = {};
        $('#customized-stage-list li').each(function(i, el) {
            var id = $(el).data('id');
            order.push(id);
            checked[id] = $(el).find('.customized-stage-check').is(':checked') ? 1 : 0;
        });
        var customizedDefault = $('#customized-default-checkbox').is(':checked') ? 1 : 0;
        $.ajax({
            url: '<?php echo admin_url('customize/save_deal_stages_customized'); ?>',
            type: 'POST',
            data: { order: order, checked: checked, customized_default: customizedDefault },
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    $('#deal-stage-customized-save-msg').html('<span class="text-success">Saved successfully!</span>');
                } else {
                    $('#deal-stage-customized-save-msg').html('<span class="text-danger">Save failed.</span>');
                }
            },
            error: function() {
                $('#deal-stage-customized-save-msg').html('<span class="text-danger">Save failed.</span>');
            }
        });
    });
});
var currentDealStageId = null;
// Open modal and load form layout
$(document).on('click', '.customized-form-btn', function(e) {
    e.preventDefault();
    var li = $(this).closest('li');
    currentDealStageId = li.data('id');
    // Load existing layout if any
    $.ajax({
        url: '<?php echo admin_url('customize/get_form_layout'); ?>',
        type: 'GET',
        data: { deal_stage_id: currentDealStageId },
        dataType: 'json',
        success: function(res) {
            if (res.success && res.layout) {
                renderFormBuilderFields(res.layout);
            } else {
                renderFormBuilderFields([]);
            }
            $('#customizedFormModal').modal('show');
        },
        error: function() {
            renderFormBuilderFields([]);
            $('#customizedFormModal').modal('show');
        }
    });
});
// Render form builder fields
function renderFormBuilderFields(fields) {
    var html = '';
    if (!Array.isArray(fields)) fields = [];
    fields.forEach(function(field, idx) {
        html += formFieldHtml(field, idx);
    });
    $('#form-builder-fields').html(html);
}
// Add new field
$('#add-field-btn').on('click', function() {
    var idx = $('#form-builder-fields .form-builder-field').length;
    var field = { label: '', type: 'text', options: [] };
    $('#form-builder-fields').append(formFieldHtml(field, idx));
});
// Field HTML generator
function formFieldHtml(field, idx) {
    var html = '<div class="form-builder-field panel panel-default" data-idx="' + idx + '">' +
        '<div class="panel-body">' +
        '<div class="form-group"><label>Label</label><input type="text" class="form-control field-label" value="' + (field.label || '') + '"></div>' +
        '<div class="form-group"><label>Type</label><select class="form-control field-type">' +
        '<option value="text"' + (field.type === 'text' ? ' selected' : '') + '>Text</option>' +
        '<option value="textarea"' + (field.type === 'textarea' ? ' selected' : '') + '>Textarea</option>' +
        '<option value="listbox"' + (field.type === 'listbox' ? ' selected' : '') + '>Listbox</option>' +
        '<option value="radio"' + (field.type === 'radio' ? ' selected' : '') + '>Radio</option>' +
        '<option value="checkbox"' + (field.type === 'checkbox' ? ' selected' : '') + '>Checkbox</option>' +
        '<option value="file"' + (field.type === 'file' ? ' selected' : '') + '>File</option>' +
        '<option value="cal"' + (field.type === 'cal' ? ' selected' : '') + '>Calendar/Date</option>' +
        '</select></div>';
    html += '<div class="form-group"><label><input type="checkbox" class="field-required" ' + (field.required ? 'checked' : '') + '> Required</label></div>';
    if (["listbox","radio","checkbox"].includes(field.type)) {
        html += '<div class="form-group"><label>Options (comma separated)</label><input type="text" class="form-control field-options" value="' + (field.options ? field.options.join(',') : '') + '"></div>';
    } else {
        html += '<div class="form-group"><label>Options (N/A for this type)</label><input type="text" class="form-control field-options" value="" disabled></div>';
    }
    html += '<button class="btn btn-danger btn-xs remove-field-btn">Remove</button>';
    html += '</div></div>';
    return html;
}
// Auto-generate field id from label if not manually changed
$(document).on('input', '.field-label', function() {
    var fieldDiv = $(this).closest('.form-builder-field');
    var label = $(this).val();
    var slug = label.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
    var idInput = fieldDiv.find('.field-id');
    if (!idInput.data('manual')) {
        idInput.val(slug);
    }
    // Also suggest a field name if empty
    var nameInput = fieldDiv.find('.field-name');
    if (!nameInput.val()) {
        nameInput.val(label);
    }
});
$(document).on('input', '.field-id', function() {
    $(this).data('manual', true);
});
// Change field type dynamically
$(document).on('change', '.field-type', function() {
    var fieldDiv = $(this).closest('.form-builder-field');
    var idx = fieldDiv.data('idx');
    var type = $(this).val();
    var label = fieldDiv.find('.field-label').val();
    var options = fieldDiv.find('.field-options').val();
    var field = { label: label, type: type, options: (options ? options.split(',') : []) };
    fieldDiv.replaceWith(formFieldHtml(field, idx));
});
// Remove field
$(document).on('click', '.remove-field-btn', function() {
    $(this).closest('.form-builder-field').remove();
});
// Save form layout
$('#save-form-layout').on('click', function() {
    var fields = [];
    $('#form-builder-fields .form-builder-field').each(function() {
        var label = $(this).find('.field-label').val();
        var type = $(this).find('.field-type').val();
        var required = $(this).find('.field-required').is(':checked');
        var options = $(this).find('.field-options').val();
        var field = { label: label, type: type, required: required };
        if (["listbox","radio","checkbox"].includes(type)) {
            field.options = options ? options.split(',') : [];
        }
        fields.push(field);
    });
    $.ajax({
        url: '<?php echo admin_url('customize/save_form_layout'); ?>',
        type: 'POST',
        data: { deal_stage_id: currentDealStageId, layout: JSON.stringify(fields) },
        dataType: 'json',
        success: function(res) {
            if (res.success) {
                $('#customizedFormModal').modal('hide');
            } else {
                alert('Save failed');
            }
        },
        error: function() {
            alert('Save failed');
        }
    });
});
// View Form button click
$(document).on('click', '.view-form-btn', function(e) {
    e.preventDefault();
    var dealStageId = $(this).data('id');
    $.ajax({
        url: '<?php echo admin_url('customize/get_form_layout'); ?>',
        type: 'GET',
        data: { deal_stage_id: dealStageId },
        dataType: 'json',
        success: function(res) {
            if (res.success && res.layout && res.layout.length > 0) {
                $('#view-form-content').html(renderViewForm(res.layout));
            } else {
                $('#view-form-content').html('<div class="text-danger">No form layout found.</div>');
            }
            $('#viewFormModal').modal('show');
        },
        error: function() {
            $('#view-form-content').html('<div class="text-danger">Failed to load form layout.</div>');
            $('#viewFormModal').modal('show');
        }
    });
});
// Render the form for viewing
function renderViewForm(fields) {
    var html = '<form>';
    fields.forEach(function(field, idx) {
        var slug = field.label ? field.label.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '') : '';
        var name = slug;
        var id = slug;
        var required = field.required ? 'required' : '';
        html += '<div class="form-group">';
        html += '<label for="' + id + '">' + (field.label || 'Field');
        if (field.required) html += ' <span style="color:red">*</span>';
        html += '</label>';
        if (name) html += ' <span class="text-muted">(name: ' + name + ')</span>';
        if (id) html += ' <span class="text-muted">[id: ' + id + ']</span>';
        switch(field.type) {
            case 'text':
                html += '<input type="text" class="form-control" name="' + name + '" id="' + id + '" ' + required + ' readonly />';
                break;
            case 'textarea':
                html += '<textarea class="form-control" name="' + name + '" id="' + id + '" ' + required + ' readonly></textarea>';
                break;
            case 'listbox':
                html += '<select class="form-control" name="' + name + '" id="' + id + '" ' + required + ' disabled>';
                if (field.options && field.options.length) {
                    field.options.forEach(function(opt) {
                        html += '<option>' + opt + '</option>';
                    });
                }
                html += '</select>';
                break;
            case 'radio':
                if (field.options && field.options.length) {
                    field.options.forEach(function(opt, i) {
                        html += '<div class="radio"><label><input type="radio" name="' + name + '" id="' + id + '_' + i + '" ' + required + ' disabled>' + opt + '</label></div>';
                    });
                }
                break;
            case 'checkbox':
                if (field.options && field.options.length) {
                    field.options.forEach(function(opt, i) {
                        html += '<div class="checkbox"><label><input type="checkbox" name="' + name + '[]" id="' + id + '_' + i + '" ' + required + ' disabled>' + opt + '</label></div>';
                    });
                }
                break;
            case 'file':
                html += '<input type="file" class="form-control" name="' + name + '" id="' + id + '" ' + required + ' disabled />';
                break;
            case 'cal':
                html += '<input type="date" class="form-control" name="' + name + '" id="' + id + '" ' + required + ' readonly />';
                break;
        }
        html += '</div>';
    });
    html += '</form>';
    return html;
}
</script>
</body>
</html> 