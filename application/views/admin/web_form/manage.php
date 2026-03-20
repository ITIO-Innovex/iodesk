<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <div>
            <h4 class="tw-m-0 tw-text-white"><?php echo e($form['name']); ?> <?php /*?><small class="tw-text-neutral-500">Form Data</small><?php */?></h4>
          </div>
          <div class="tw-flex tw-items-center tw-gap-2">
            <button type="button" class="btn btn-primary" id="add-entry-btn">
              <i class="fa-regular fa-plus tw-mr-1"></i> Add Entry
            </button>
			<button type="button" class="btn btn-primary" id="toggleBtn">
              <i class="fa-solid fa-upload tw-mr-1"></i> Upload CSV
            </button>
            <a href="<?php echo admin_url('web_form/create/' . (int)$form['id']); ?>" class="btn btn-default">
              <i class="fa-regular fa-pen-to-square tw-mr-1"></i> Edit Form
            </a>
            <a href="<?php echo admin_url('web_form'); ?>" class="btn btn-default">
              <i class="fa-solid fa-table-list tw-mr-1"></i> Web Form
            </a>
          </div>
        </div>

        <div class="panel_s mtop10" id="myCsvBox" style="display:none;">
          <div class="panel-heading">
            <h4 class="tw-m-0">Bulk Upload via CSV</h4>
          </div>
          <div class="panel-body" style="">
            <div class="row">
              <div class="col-md-6">
                <h5>CSV Format</h5>
                <p class="text-muted">
                  First row must be the header with field names. Each next row is one entry.
                </p>
                <div class="well well-sm table-responsive" style="white-space:pre-wrap;">
<?php
  $headerCols = [];
  foreach ($fields as $f) {
      if ($f['type'] === 'file') { continue; } // files not supported in CSV
      $headerCols[] = $f['name'];
  }
  echo $headerdata=e(implode(',', $headerCols));
  //echo $headerdata=str_replace(',', ',<br>', $headerdata);
?>
                </div>
				<a href="<?php echo admin_url('web_form/download_csv_format/' . (int)$form['id']); ?>" class="btn btn-default tw-mt-6" title="Download CSV Template & Upload with your data"><i class="fa-solid fa-download tw-mr-1 "></i> Download CSV Format </a>
                  
                
              </div>
              <div class="col-md-6">
                <h5>Instructions</h5>
                <ul class="text-muted">
                  <li>Use <strong>comma (,)</strong> as separator.</li>
                  <li>Header row must match the field names exactly (see left).</li>
                  <li><strong>Required fields</strong> must not be empty; rows with missing required values are skipped.</li>
                  <li><strong>File fields</strong> are not imported via CSV; upload attachments manually after import if needed.</li>
                  <li>To leave a value empty, just keep the column blank.</li>
                </ul>
              </div>
            </div>
            <hr />
            <?php echo form_open_multipart(admin_url('web_form/upload_csv/' . (int)$form['id']), ['id' => 'web-form-csv']); ?>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Upload CSV File <small class="req text-danger">* </small></label>
                  <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                </div>
              </div>
              <div class="col-md-6 tw-flex tw-items-end">
                <button type="submit" class="btn btn-success tw-mt-6">
                  <i class="fa-solid fa-upload tw-mr-1 "></i> Upload CSV
                </button>
              </div>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>

        <div class="panel_s mtop20">
          <div class="panel-heading">
            <div class="tw-flex tw-items-center tw-justify-between">
              <h4 class="tw-m-0">Entries</h4>
              <button type="button" class="btn btn-default btn-sm" id="openAdvancedSearchBtn" data-toggle="modal" data-target="#advancedSearchModal">
                <i class="fa fa-filter"></i> Advanced Search
              </button>
            </div>
          </div>
          <div class="panel-body">
            <?php if (!empty($entries)) { ?>
              <table id="webFormEntriesTable" class="table dt-table" data-order-col="0" data-order-type="desc">
                <thead>
                  <tr>
                    <th class="tw-hidden" >ID</th>
                    <?php foreach ($fields as $field) { ?>
                      <th data-field-name="<?php echo e($field['name']); ?>" class="expand-input"><?php echo e($field['label']); ?></th>
                    <?php } ?>
                    <th class="expand-input">Created At</th>
                    <th><?php echo _l('options'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($entries as $entry) {
                      $data = json_decode($entry['data_json'], true) ?: [];
                      ?>
                      <tr>
                        <td class="tw-hidden"><?php echo (int)$entry['id']; ?></td>
                        <?php foreach ($fields as $field) {
                            $fname = $field['name'];
                            $val   = isset($data[$fname]) ? $data[$fname] : '';
                            ?>
                            <td data-field-name="<?php echo e($fname); ?>">
                              <?php if (is_array($val)) { ?>
                                <?php foreach ($val as $p) { ?>
                                  <?php if ($p) { ?>
                                    <a href="<?php echo base_url($p); ?>" target="_blank"><?php echo e(basename($p)); ?></a><br>
                                  <?php } ?>
                                <?php } ?>
                              <?php } else { ?>
                                <?php echo (string)$val; ?>
                              <?php } ?>
                            </td>
                        <?php } ?>
                        <td><?php echo e($entry['created_at']); ?></td>
                        <td>
                          <button type="button"
                                  class="btn btn-default btn-xs edit-entry-btn"
                                  data-entry-id="<?php echo (int)$entry['id']; ?>"
                                  data-entry-json="<?php echo e($entry['data_json']); ?>">
                            <i class="fa-regular fa-pen-to-square"></i>
                          </button>
                          <a href="<?php echo admin_url('web_form/delete_entry/' . (int)$form['id'] . '/' . (int)$entry['id']); ?>" class="btn btn-danger btn-xs _delete">
                            <i class="fa-regular fa-trash-can"></i>
                          </a>
                          <a href="<?php echo admin_url('web_form/download_entry_excel/' . (int)$form['id'] . '/' . (int)$entry['id']); ?>" class="btn btn-success btn-xs" title="Download Excel">
                            <i class="fa-regular fa-file-excel"></i>
                          </a>
                          <a href="<?php echo admin_url('web_form/download_entry_pdf/' . (int)$form['id'] . '/' . (int)$entry['id']); ?>" class="btn btn-info btn-xs" title="Download PDF">
                            <i class="fa-regular fa-file-pdf"></i>
                          </a>
						  
                        </td>
                      </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="text-muted">No entries yet.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="entryModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="entryModalTitle">Add Entry</h4>
      </div>
      <div class="modal-body">
        <?php
          $actionUrl = admin_url('web_form/save_entry/' . (int)$form['id']);
          echo form_open_multipart($actionUrl, ['id' => 'web-form-entry']);
        ?>
        <input type="hidden" name="entry_id" id="entry_id" value="">
        <div class="row">
          <?php foreach ($fields as $field) {
              $fname = $field['name'];
              $label = $field['label'];
              $required = !empty($field['is_required']);
              $type = $field['type'];
              $opts = [];
              if (!empty($field['options_json'])) {
                  $opts = json_decode($field['options_json'], true) ?: [];
              }
			  $field_width="col-md-6";
			  if (($type === 'textarea') || ($type === 'editor')){
			  $field_width="col-md-12";
			  }
              ?>
              <div class="<?php echo $field_width;?>">
                <div class="form-group">
                  <label><?php echo e($label); ?><?php if ($required) { ?><small class="req text-danger">* </small><?php } ?></label>
                  <?php if ($type === 'text') { ?>
                    <input type="text" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'email') { ?>
                    <input type="email" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'url') { ?>
                    <input type="url" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'number') { ?>
                    <input type="number" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'textarea') { ?>
                    <textarea class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" rows="3" <?php echo $required ? 'required' : ''; ?>></textarea>
                  <?php } elseif ($type === 'editor') { ?>
                    <textarea class="form-control editor web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" rows="4" <?php echo $required ? 'required' : ''; ?>></textarea>
                  <?php } elseif ($type === 'select') { ?>
                    <select name="<?php echo e($fname); ?>" data-field-name="<?php echo e($fname); ?>" class="form-control web-entry-field" <?php echo $required ? 'required' : ''; ?>>
                      <option value="">-- Select --</option>
                      <?php foreach ($opts as $opt) { ?>
                        <option value="<?php echo e($opt); ?>"><?php echo e($opt); ?></option>
                      <?php } ?>
                    </select>
                  <?php } elseif ($type === 'radio') { ?>
                    <div class="web-entry-field" data-field-name="<?php echo e($fname); ?>">
                      <?php foreach ($opts as $opt) { ?>
                        <label class="radio-inline">
                          <input type="radio" name="<?php echo e($fname); ?>" value="<?php echo e($opt); ?>" <?php echo $required ? 'required' : ''; ?>> <?php echo e($opt); ?>
                        </label>
                      <?php } ?>
                    </div>
                  <?php } elseif ($type === 'checkbox') { ?>
                    <div class="web-entry-field" data-field-name="<?php echo e($fname); ?>">
                      <?php foreach ($opts as $opt) { ?>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="<?php echo e($fname); ?>[]" value="<?php echo e($opt); ?>"> <?php echo e($opt); ?>
                        </label>
                      <?php } ?>
                    </div>
                  <?php } elseif ($type === 'datetime') { ?>
                    <input type="datetime-local" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'file') { ?>
                    <input type="file" class="form-control" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>[]" multiple <?php echo $required ? 'required' : ''; ?>>
                    <div class="tw-mt-2 existing-files" data-field-name="<?php echo e($fname); ?>" style="display:none;"></div>
                  <?php } else { ?>
                    <input type="text" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } ?>
                </div>
              </div>
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

<!-- Advanced Search Modal -->
<div class="modal fade" id="advancedSearchModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Advanced Search</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label>Match Mode</label>
              <select class="form-control" id="advancedSearchMatchMode">
                <option value="all" selected>Match All (AND)</option>
                <option value="any">Match Any (OR)</option>
              </select>
            </div>
          </div>
          <div class="col-md-8">
            <div class="alert alert-info mtop25" style="margin-bottom:0;">
              Add multiple filters to search by one or more fields.
            </div>
          </div>
        </div>

        <hr />

        <div id="advancedSearchCriteriaContainer">
          <!-- rows injected by JS -->
        </div>

        <div class="tw-text-right">
          <button type="button" class="btn btn-default btn-sm" id="addAdvancedCriterionBtn">
            <i class="fa fa-plus"></i> Add Field Filter
          </button>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" id="clearAdvancedSearchBtn">Clear</button>
        <button type="button" class="btn btn-primary" id="applyAdvancedSearchBtn">Search</button>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>$('.editor').jqte();</script>
<script>
  (function() {
    function resetEntryModal() {
      $('#entry_id').val('');
      $('#entryModalTitle').text('Add Entry');
      $('#web-form-entry')[0].reset();
      // reset checkbox/radio explicitly
      $('#web-form-entry').find('input[type="checkbox"], input[type="radio"]').prop('checked', false);
      // clear existing file list
      $('#web-form-entry').find('.existing-files').hide().empty();
      // clear jqte editors if available
      if (typeof $.fn.jqteVal === 'function') {
        $('#web-form-entry').find('.editor').each(function() {
          $(this).jqteVal('');
        });
      }
    }

    function renderExistingFiles(data) {
      $('#web-form-entry').find('.existing-files').each(function() {
        var $box = $(this);
        var key = $box.data('field-name');
        if (!key) return;
        var files = data && data[key] ? data[key] : [];
        if (!Array.isArray(files) || files.length === 0) {
          $box.hide().empty();
          return;
        }
		$('input[name="file[]"]').removeAttr('required');
        var html = '<div class="text-muted" style="margin-bottom:4px;">Existing attachments:</div>';
        files.forEach(function(p) {
          if (!p) return;
          var name = (p + '').split('/').pop();
          // base_url is available globally in Perfex/CI admin
          var href = (typeof base_url !== 'undefined') ? (base_url + p) : p;
          html += '<div class="tw-flex tw-items-center tw-justify-between tw-gap-2" style="padding:2px 0;">' +
                  '  <a href="' + href + '" target="_blank">' + name + '</a>' +
                  '  <button type="button" class="btn btn-danger btn-xs webfile-delete" data-field-name="' + key + '" data-file-path="' + p + '" title="Delete file">' +
                  '    <i class="fa-regular fa-trash-can"></i>' +
                  '  </button>' +
                  '</div>';
        });
		
        $box.html(html).show();
      });
    }

    $('#add-entry-btn').on('click', function() {
      resetEntryModal();
      $('#entryModal').appendTo('body').modal('show');
    });

    $('body').on('click', '.edit-entry-btn', function() {
      resetEntryModal();
      $('#entryModalTitle').text('Edit Entry');

      var entryId = $(this).data('entry-id');
      var jsonStr = $(this).attr('data-entry-json') || '{}';
      var data = {};
      try { data = JSON.parse(jsonStr) || {}; } catch(e) { data = {}; }

      $('#entry_id').val(entryId);

      // Fill text/select/textarea/datetime
      $('#web-form-entry').find('.web-entry-field').each(function() {
        var $el = $(this);
        var key = $el.data('field-name');
        if (!key) return;
        var val = (data[key] !== undefined && data[key] !== null) ? (data[key] + '') : '';

        if ($el.is('input') || $el.is('textarea') || $el.is('select')) {
          // jqte editor support
          if ($el.hasClass('editor') && typeof $el.jqteVal === 'function') {
            $el.jqteVal(val);
          } else {
            $el.val(val);
          }
        } else {
          // wrapper div for radio/checkbox groups
          if ($el.find('input[type="radio"]').length) {
            $el.find('input[type="radio"][value="' + val.replace(/"/g,'&quot;') + '"]').prop('checked', true);
          }
          if ($el.find('input[type="checkbox"]').length) {
            var parts = val.split(',').map(function(s){ return s.trim(); }).filter(Boolean);
            $el.find('input[type="checkbox"]').each(function() {
              var v = $(this).val();
              $(this).prop('checked', parts.indexOf(v) !== -1);
            });
          }
        }
      });

      // Show existing attachments for file fields
      renderExistingFiles(data);

      $('#entryModal').appendTo('body').modal('show');
    });

    // Delete file from entry (AJAX)
    $('body').on('click', '.webfile-delete', function() {
      var $btn = $(this);
      if (!confirm('Delete this file?')) {
        return;
      }
      var formId = <?php echo (int)$form['id']; ?>;
      var entryId = $('#entry_id').val();
      var fieldName = $btn.data('field-name');
      var filePath = $btn.data('file-path');

      $.post(admin_url + 'web_form/delete_entry_file', {
        form_id: formId,
        entry_id: entryId,
        field_name: fieldName,
        file_path: filePath,
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      }).done(function(resp) {
        try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) { resp = null; }
        if (resp && resp.success) {
          // remove row in UI
          $btn.closest('div').remove();
        } else {
          alert_float('danger', (resp && resp.message) ? resp.message : 'Failed to delete file');
        }
      }).fail(function() {
        alert_float('danger', 'Failed to delete file');
      });
    });
  })();
  
  // Advanced search for entries table (client-side, via DataTables ext.search)
  (function() {
    var $table = $('#webFormEntriesTable');
    if (!$table.length) return;

    var fieldNameToColIndex = {};
    var dtInstance = null;
    var extSearchAdded = false;

    var state = {
      matchMode: 'all', // all|any
      criteria: []      // { field, op, value }
    };

    function normalizeText(v) {
      return String(v || '')
        .replace(/\s+/g, ' ')
        .trim()
        .toLowerCase();
    }

    function buildFieldColIndex() {
      fieldNameToColIndex = {};
      $table.find('thead th[data-field-name]').each(function() {
        var fieldName = $(this).attr('data-field-name');
        fieldNameToColIndex[fieldName] = $(this).index();
      });
    }

    function initDataTableIfReady() {
      if (!$.fn.DataTable) return;

      if ($.fn.dataTable && $.fn.dataTable.isDataTable($table)) {
        dtInstance = $table.DataTable();
        buildFieldColIndex();

        if (!extSearchAdded) {
          $.fn.dataTable.ext.search.push(function(settings, data) {
            if (!dtInstance) return true;
            if (settings.nTable !== dtInstance.table().node()) return true;

            if (!state.criteria.length) return true;

            var results = state.criteria.map(function(c) {
              var colIndex = fieldNameToColIndex[c.field];
              if (colIndex === undefined) return false;

              var cellText = normalizeText(data[colIndex]);
              var target = normalizeText(c.value);

              if (!target) return false;

              if (c.op === 'equals') {
                return cellText === target;
              }
              // contains (default)
              return cellText.indexOf(target) !== -1;
            });

            if (state.matchMode === 'any') return results.some(Boolean);
            return results.every(Boolean);
          });

          extSearchAdded = true;
        }
      } else {
        setTimeout(initDataTableIfReady, 300);
      }
    }

    var searchFields = [];
    <?php foreach ($fields as $f) { ?>
      searchFields.push({
        name: <?php echo json_encode($f['name']); ?>,
        label: <?php echo json_encode($f['label']); ?>
      });
    <?php } ?>

    function makeCriterionRow(criterion) {
      var c = criterion || { field: (searchFields[0] ? searchFields[0].name : ''), op: 'contains', value: '' };

      var $row = $('<div class="row advanced-criterion-row tw-mb-2"></div>');
      var $fieldCol = $('<div class="col-md-5"></div>');
      var $opCol = $('<div class="col-md-3"></div>');
      var $valCol = $('<div class="col-md-3"></div>');
      var $btnCol = $('<div class="col-md-1 tw-flex tw-items-center"></div>');

      var $fieldSelect = $('<select class="form-control criterion-field" required></select>');
      searchFields.forEach(function(f) {
        var $opt = $('<option></option>').val(f.name).text(f.label);
        if (c.field === f.name) $opt.attr('selected', 'selected');
        $fieldSelect.append($opt);
      });

      var $opSelect = $('<select class="form-control criterion-op"></select>');
      $opSelect.append('<option value="contains" ' + (c.op === 'contains' ? 'selected' : '') + '>Contains</option>');
      $opSelect.append('<option value="equals" ' + (c.op === 'equals' ? 'selected' : '') + '>Equals</option>');

      var $valueInput = $('<input type="text" class="form-control criterion-value" placeholder="Enter value">');
      $valueInput.val(c.value || '');

      var $removeBtn = $('<button type="button" class="btn btn-danger btn-xs remove-criterion-btn" title="Remove filter"><i class="fa fa-trash-can"></i></button>');
      $removeBtn.on('click', function() {
        $row.remove();
      });

      $fieldCol.append($fieldSelect);
      $opCol.append($opSelect);
      $valCol.append($valueInput);
      $btnCol.append($removeBtn);

      $row.append($fieldCol).append($opCol).append($valCol).append($btnCol);
      return $row;
    }

    function renderCriteriaUi() {
      var $c = $('#advancedSearchCriteriaContainer');
      $c.empty();

      if (!state.criteria.length) {
        $c.append(makeCriterionRow({ field: (searchFields[0] ? searchFields[0].name : ''), op: 'contains', value: '' }));
        return;
      }

      state.criteria.forEach(function(c) {
        $c.append(makeCriterionRow(c));
      });
    }

    $('#openAdvancedSearchBtn, #advancedSearchModal').on('show.bs.modal', function() {
      renderCriteriaUi();
      $('#advancedSearchMatchMode').val(state.matchMode || 'all');
    });

    $('#addAdvancedCriterionBtn').on('click', function() {
      $('#advancedSearchCriteriaContainer').append(makeCriterionRow());
    });

    $('#clearAdvancedSearchBtn').on('click', function() {
      state.criteria = [];
      state.matchMode = 'all';
      $('#advancedSearchMatchMode').val('all');
      renderCriteriaUi();
      if (dtInstance) dtInstance.draw();
    });

    $('#applyAdvancedSearchBtn').on('click', function() {
      state.matchMode = $('#advancedSearchMatchMode').val() || 'all';
      var newCriteria = [];

      $('#advancedSearchCriteriaContainer .advanced-criterion-row').each(function() {
        var field = $(this).find('.criterion-field').val();
        var op = $(this).find('.criterion-op').val() || 'contains';
        var value = $(this).find('.criterion-value').val();

        if (field && $.trim(value) !== '') {
          newCriteria.push({ field: field, op: op, value: value });
        }
      });

      state.criteria = newCriteria;
      if (dtInstance) dtInstance.draw();
      $('#advancedSearchModal').modal('hide');
    });

    initDataTableIfReady();
  })();

// toggle csv upload box 
$('#toggleBtn').on('click', function() {
    $('#myCsvBox').slideToggle();
});

$(document).on('click', '.expand-input', function () {
    $('th.expand-input').removeClass('active-expand');
    $(this).addClass('active-expand');
});
</script>

</body>
</html>

