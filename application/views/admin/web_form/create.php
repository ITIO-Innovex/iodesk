<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .field-row-placeholder {
    border: 2px dashed #cbd5e1;
    background: #f8fafc;
    height: 60px;
    margin-top: 10px;
  }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <?php /*?><div class="col-md-4">
        <div class="panel_s">
          <div class="panel-heading">
            <h4 class="tw-m-0">Forms</h4>
          </div>
          <div class="panel-body">
            <a href="<?php echo admin_url('web_form/create'); ?>" class="btn btn-primary mtop10">
              <i class="fa-regular fa-plus tw-mr-1"></i> New Form
            </a>
            <hr />
            <?php if (!empty($forms)) { ?>
              <ul class="list-group">
                <?php foreach ($forms as $f) { ?>
                  <li class="list-group-item tw-flex tw-items-center tw-justify-between">
                    <span>
                      <a href="<?php echo admin_url('web_form/create/' . (int)$f['id']); ?>">
                        <?php echo e($f['name']); ?>
                      </a>
                    </span>
                    <span>
                      <a href="<?php echo admin_url('web_form/manage/' . (int)$f['id']); ?>" class="btn btn-default btn-xs" title="Manage Data">
                        <i class="fa-solid fa-database"></i>
                      </a>
                      <a href="<?php echo admin_url('web_form/delete/' . (int)$f['id']); ?>" class="btn btn-danger btn-xs _delete" title="Delete (soft)">
                        <i class="fa-regular fa-trash-can"></i>
                      </a>
                    </span>
                  </li>
                <?php } ?>
              </ul>
            <?php } else { ?>
              <p class="text-muted">No forms yet. Click "New Form" to create one.</p>
            <?php } ?>
          </div>
        </div>
      </div><?php */?>

      <div class="col-md-12">
	  <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Web Forms</h4>
         <a href="<?php echo admin_url('web_form'); ?>" class="btn btn-default">
              <i class="fa-solid fa-table-list tw-mr-1"></i> Web Form
            </a>
        </div>
        <div class="panel_s">
          <div class="panel-heading">
            <h4 class="tw-m-0"><?php echo isset($edit_form) && $edit_form ? 'Edit Form' : 'Add New Form'; ?></h4>
          </div>
          <div class="panel-body">
		  <div class="alert alert-info">
    <strong>Instruction:</strong> 
<p>Enter form name, add optional description, then add multiple fields (text, textarea, editor, dropdown, radio, checkbox, file, date, etc.). </p>
<p>You can drag and drop fields to reorder them before saving.</p>
<div class="btn btn-primary btn-sm tw-my-2 toggleBtn" data-id="toggleDiv">View More</div>
<div id="toggleDiv" style="display:none;">
    <div style="background:#f4f6f9; padding:12px; border-radius:6px; font-size:13px;">

<b>Step 1: Basic Details</b><br>

Enter the <b>Form Name</b> (required)

Add <b>Description</b> (optional)

<br>

<b>Step 2: Add Form Fields</b><br>
You can add multiple fields using the <b>Add Field</b> option.

<br><br>

<b>Supported Field Types:</b><br>

Text Field

Textarea

Editor (Rich Text)

List Box (Dropdown)

Radio Button

Checkbox

File Upload

Date / Date-Time

<br>

<b>Step 3: Configure Fields</b><br>
For each field, you can:

Set <b>Field Label</b>

Define <b>Field Name</b> (unique key)

Select <b>Field Type</b>

Mark as <b>Required</b> if needed

Add options for dropdown, radio, or checkbox fields

<br>

<b>Step 4: Reorder Fields</b><br>

You can <b>drag and drop fields up or down</b> to arrange them in the desired order

<br>

<b>Step 5: Save Form</b><br>

Click <b>Save</b> to create the form

The form will be available for data entry and usage in the system

<br>

<b>Note:</b><br>

Field names should be unique and without spaces (e.g., <code>company_name</code>)

For multiple options (dropdown/radio/checkbox), enter values separated by commas

Ensure required fields are properly marked before saving

</div>
</div>
</div>
            <?php $lockFields = !empty($has_entries); ?>
            <?php
              $actionUrl = admin_url('web_form/save');
              echo form_open($actionUrl, ['id' => 'web-form-builder']);
            ?>
            <?php if (isset($edit_form) && $edit_form) { ?>
              <input type="hidden" name="form_id" value="<?php echo (int)$edit_form['id']; ?>">
            <?php } ?>

            <div class="form-group">
              <label>Form Name <small class="req text-danger">*</small></label>
              <input type="text" class="form-control" name="name" required
                     value="<?php echo isset($edit_form['name']) ? e($edit_form['name']) : ''; ?>">
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea class="form-control" name="description" rows="2"><?php echo isset($edit_form['description']) ? e($edit_form['description']) : ''; ?></textarea>
            </div>

            <div class="form-group">
              <label>Status</label>
              <?php $isActive = isset($edit_form['is_active']) ? (int)$edit_form['is_active'] : 1; ?>
              <select name="is_active" class="form-control">
                <option value="1" <?php echo $isActive === 1 ? 'selected' : ''; ?>>Active</option>
                <option value="0" <?php echo $isActive === 0 ? 'selected' : ''; ?>>Inactive</option>
              </select>
            </div>

            <hr />

            <h5>Fields</h5>
            <p class="text-muted">Add form fields. For listbox / radio / checkbox, enter options one per line or separated by commas.</p> <p class="text-muted">The form fields can be easily rearranged using the drag-and-drop feature. Simply drag a field and move it up or down to set the desired order.</p>

            <div id="fields-container">
              <?php
              $existingFields = isset($edit_fields) && is_array($edit_fields) ? $edit_fields : [];
              if (!empty($existingFields)) {
                  foreach ($existingFields as $idx => $field) {
                      ?>
                      <div class="panel panel-default field-row mtop10">

                        <div class="panel-body mail-bg">
                          <input type="hidden" name="fields[<?php echo $idx; ?>][field_id]" value="<?php echo (int)$field['id']; ?>"><?php /*?><div ><span class=" tw-my-2 tw-mx-2" style="position: relative;z-index: 9999999;" ><i class="fa fa-arrows" title="You can reorder the form fields by dragging and dropping them up or down to arrange them in the desired order."></i></span></div><?php */?>
                          <div class="row">
						  <div class="col-md-1">
						  <i class="fa-solid fa-grip-vertical tw-mt-6 fa-2x" title="Drag to change field position up/down"></i>
						  </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label>Label <small class="req text-danger">*</small></label>
                                <input type="text" class="form-control" name="fields[<?php echo $idx; ?>][label]" value="<?php echo e($field['label']); ?>" required>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label>Field Name <small class="req text-danger">*</small></label>
                                <input type="text" class="form-control" name="fields[<?php echo $idx; ?>][name]" value="<?php echo e($field['name']); ?>" required <?php echo $lockFields ? 'readonly' : ''; ?>>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="form-group">
                                <label>Type</label>
                                <select name="fields[<?php echo $idx; ?>][type]" class="form-control field-type">
                                  <?php
                                  $types = [
                                      'text'     => 'Text',
                                      'email'    => 'Email',
                                      'url'      => 'URL',
                                      'number'   => 'Number',
                                      'textarea' => 'Textarea',
                                      'editor'   => 'Editor',
                                      'select'   => 'Listbox',
                                      'radio'    => 'Radio',
                                      'checkbox' => 'Checkbox',
                                      'datetime' => 'Date/Time',
                                      'file'     => 'File',
                                  ];
                                  foreach ($types as $tKey => $tLabel) {
                                      $sel = ($field['type'] === $tKey) ? 'selected' : '';
                                      echo '<option value="' . $tKey . '" ' . $sel . '>' . $tLabel . '</option>';
                                  }
                                  ?>
                                </select>
                              </div>
                            </div>
                            <div class="col-md-2">
                              <div class="checkbox mtop25">
                                <input type="checkbox" name="fields[<?php echo $idx; ?>][required]" id="required-<?php echo $idx; ?>" <?php echo !empty($field['is_required']) ? 'checked' : ''; ?>>
                                <label for="required-<?php echo $idx; ?>">Required</label>
                              </div>
                            </div>
                          </div>
                          <div class="row field-options-row" <?php echo in_array($field['type'], ['select', 'radio', 'checkbox']) ? '' : 'style="display:none"'; ?>>
                            <div class="col-md-12">
                              <div class="form-group">
                                <label>Options (one per line)</label>
                                <textarea class="form-control" name="fields[<?php echo $idx; ?>][options]" rows="2"><?php
                                  if (!empty($field['options_json'])) {
                                      $optsArray = json_decode($field['options_json'], true) ?: [];
                                      echo e(implode("\n", $optsArray));
                                  }
                                ?></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="tw-text-right">
                            <?php if (!$lockFields) { ?>
                              <button type="button" class="btn btn-danger btn-xs remove-field"><i class="fa-regular fa-trash-can"></i></button>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                      <?php
                  }
              }
              ?>
            </div>

            <button type="button" class="btn btn-default mtop10" id="add-field-btn">
              <i class="fa-regular fa-plus"></i> Add Field
            </button>

            <hr />

            <button type="submit" class="btn btn-primary">Save Form</button>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script src="<?php echo base_url('assets/plugins/jquery-ui/jquery-ui.js'); ?>"></script>
<script>
(function() {
  var fieldIndex = <?php echo isset($existingFields) ? (int)count($existingFields) : 0; ?>;

  function slugifyFieldName(str) {
    str = (str || '').toString().trim().toLowerCase();
    // Replace non-alphanumeric with underscore
    str = str.replace(/[^a-z0-9]+/g, '_');
    // Trim underscores
    str = str.replace(/^_+|_+$/g, '');
    // Must not start with a number (safe for PHP array keys + HTML names)
    if (/^[0-9]/.test(str)) {
      str = 'field_' + str;
    }
    return str || 'field';
  }

  function maybeAutofillName($row) {
    var $label = $row.find('input[name*="[label]"]');
    var $name  = $row.find('input[name*="[name]"]');
    if (!$label.length || !$name.length) return;

    // Only auto-fill when name is empty or previously auto-generated
    var current = ($name.val() || '').trim();
    var isAuto  = $name.data('auto') === 1;
    if (current === '' || isAuto) {
      var next = slugifyFieldName($label.val());
      $name.val(next);
      $name.data('auto', 1);
    }
  }

  function getFieldRowHtml(idx) {
    var types = {
      text: 'Text',
      email: 'Email',
      url: 'URL',
      number: 'Number',
      textarea: 'Textarea',
      editor: 'Editor',
      select: 'Listbox',
      radio: 'Radio',
      checkbox: 'Checkbox',
      datetime: 'Date/Time',
      file: 'File'
    };

    var typeOptions = '';
    for (var key in types) {
      typeOptions += '<option value=\"' + key + '\">' + types[key] + '</option>';
    }

    return '' +
      '<div class=\"panel panel-default field-row mtop10\">' +
      ' <div class=\"panel-body\">' +
      '  <div class=\"row\">' +
      '    <div class=\"col-md-4\">' +
      '      <div class=\"form-group\"><label>Label <small class=\"req text-danger\">* </small></label>' +
      '        <input type=\"text\" class=\"form-control\" name=\"fields[' + idx + '][label]\" required>' +
      '      </div>' +
      '    </div>' +
      '    <div class=\"col-md-3\">' +
      '      <div class=\"form-group\"><label>Field Name <small class=\"req text-danger\">* </small></label>' +
      '        <input type=\"text\" class=\"form-control\" name=\"fields[' + idx + '][name]\" required>' +
      '      </div>' +
      '    </div>' +
      '    <div class=\"col-md-3\">' +
      '      <div class=\"form-group\"><label>Type</label>' +
      '        <select name=\"fields[' + idx + '][type]\" class=\"form-control field-type\">' + typeOptions + '</select>' +
      '      </div>' +
      '    </div>' +
      '    <div class=\"col-md-2\">' +
      '      <div class=\"checkbox mtop25\">' +
      '        <input type=\"checkbox\" name=\"fields[' + idx + '][required]\" id=\"required-' + idx + '\">' +
      '        <label for=\"required-' + idx + '\">Required</label>' +
      '      </div>' +
      '    </div>' +
      '  </div>' +
      '  <div class=\"row field-options-row\" style=\"display:none\">' +
      '    <div class=\"col-md-12\">' +
      '      <div class=\"form-group\"><label>Options (one per line)</label>' +
      '        <textarea class=\"form-control\" name=\"fields[' + idx + '][options]\" rows=\"2\"></textarea>' +
      '      </div>' +
      '    </div>' +
      '  </div>' +
      '  <div class=\"tw-text-right\">' +
      '    <button type=\"button\" class=\"btn btn-danger btn-xs remove-field\"><i class=\"fa-regular fa-trash-can\"></i></button>' +
      '  </div>' +
      ' </div>' +
      '</div>';
  }

  $('#add-field-btn').on('click', function() {
    var idx = fieldIndex++;
    var $node = $(getFieldRowHtml(idx));
    $('#fields-container').append($node);
    maybeAutofillName($node);
  });

  $('#fields-container').on('click', '.remove-field', function() {
    $(this).closest('.field-row').remove();
  });

  $('#fields-container').on('change', '.field-type', function() {
    var val = $(this).val();
    var $row = $(this).closest('.field-row');
    if (val === 'select' || val === 'radio' || val === 'checkbox') {
      $row.find('.field-options-row').slideDown();
    } else {
      $row.find('.field-options-row').slideUp();
    }
  });

  // Drag & drop order
  if ($.fn.sortable) {
    $('#fields-container').sortable({
      items: '.field-row',
      handle: '.panel-body',
      placeholder: 'field-row-placeholder',
      tolerance: 'pointer'
    });
    $('#fields-container').disableSelection();
  }

  // Before submit: re-index field names based on current DOM order
  function reindexFields() {
    $('#fields-container .field-row').each(function(i) {
      var $row = $(this);
      $row.find('input, select, textarea, label').each(function() {
        var $el = $(this);

        // Update name attributes: fields[<old>][key] -> fields[i][key]
        var name = $el.attr('name');
        if (name) {
          name = name.replace(/fields\[\d+\]/g, 'fields[' + i + ']');
          $el.attr('name', name);
        }

        // Update required checkbox id/for pairs (so label click still works)
        var id = $el.attr('id');
        if (id && id.indexOf('required-') === 0) {
          $el.attr('id', 'required-' + i);
        }
        var htmlFor = $el.attr('for');
        if (htmlFor && htmlFor.indexOf('required-') === 0) {
          $el.attr('for', 'required-' + i);
        }
      });
    });
  }

  $('#web-form-builder').on('submit', function() {
    reindexFields();
  });

  // Auto-generate field name from label while user types label
  $('#fields-container').on('input', 'input[name*="[label]"]', function() {
    var $row = $(this).closest('.field-row');
    maybeAutofillName($row);
  });

  // If user manually edits the Field Name, stop auto-updating it
  $('#fields-container').on('input', 'input[name*="[name]"]', function() {
    $(this).data('auto', 0);
  });

  // Initialize existing rows (mark auto if name matches slug(label) or empty)
  $('#fields-container .field-row').each(function() {
    var $row = $(this);
    var $label = $row.find('input[name*="[label]"]');
    var $name  = $row.find('input[name*="[name]"]');
    if (!$label.length || !$name.length) return;
    var expected = slugifyFieldName($label.val());
    var cur = ($name.val() || '').trim();
    if (cur === '' || cur === expected) {
      $name.data('auto', 1);
      if (cur === '') {
        $name.val(expected);
      }
    }
  });
})();


</script>
</body>
</html>

