<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
$tablefields=$fields;

$hidecols = $_SESSION['selected_fields'][$form['id']] ?? [];
?>

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
              <h4 class="tw-m-0"><?php echo e($form['name']); ?> - List</h4>
			  <div>
              <button type="button" class="btn btn-default btn-sm" id="openAdvancedSearchBtn" data-toggle="modal" data-target="#advancedSearchModal">
                <i class="fa fa-filter" title="Advanced Search"></i> 
              </button>
			  <button type="button" class="btn btn-default btn-sm" id="openTableSettingBtn" data-toggle="modal" data-target="#openTableSettingModal">
                <i class="fa-solid fa-table" title="Table Setting"></i> 
              </button>
              <button type="button" class="btn btn-default btn-sm" id="openColWidthBtn" title="Set table column width">
                <i class="fa-solid fa-arrows-left-right-to-line"></i>
              </button>
			  </div>
            </div>
          </div>
          <div class="panel-body">
    <?php if (!empty($entries)) { 
	foreach ($fields as $key => $row) {
    /*if (in_array($row['name'], $hidecols)) { unset($fields[$key]); }*/
	if(isset($hidecols)&&$hidecols){
	 if (!in_array($row['name'], $hidecols)) { unset($fields[$key]); }
	 }
    }
	?>
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
                      <tr data-entry-json="<?php echo e($entry['data_json']); ?>">
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
                                <?php
                                  $emailVal = trim((string) $val);
                                  $isEmailField = isset($field['type']) && $field['type'] === 'email';
                                  $isEmailValue = $isEmailField && $emailVal !== '' && filter_var($emailVal, FILTER_VALIDATE_EMAIL);
								  $isURLField = isset($field['type']) && $field['type'] === 'url';
                                ?>
                                <?php if ($isEmailValue) { ?>
                                  <?php echo e($emailVal); ?>
                                  <a href="#" class="web-email-trigger tw-ml-1 tw-inline-block tw-text-primary" title="<?php echo _l('email'); ?> — Send / compose" data-email="<?php echo e($emailVal); ?>" role="button">
                                    <i class="fa-regular fa-envelope"></i>
                                  </a> 
								  
								  <?php } elseif($isURLField) { ?>
								  <a href="<?php echo (string)$val; ?>" target="_blank"><?php echo (string)$val; ?></a><br>
                                <?php } else { ?>
                                  <?php echo (string)$val; ?>
                                <?php } ?>
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
<!-- Table Setting Modal -->
<div class="modal fade" id="openTableSettingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Table Setting</h4>
      </div>
<?php echo form_open_multipart(admin_url('web_form/update_table_field/' . (int)$form['id']), ['id' => 'update-table-field']); ?>
      <div class="modal-body">
<div class="form-group">       
<?php foreach ($tablefields as $row): ?>

<?php if(isset($hidecols)&&$hidecols){ ?>
<input type="checkbox" name="fields[]" class="customized-stage-check" value="<?php echo $row['name']; ?>" <?php echo in_array($row['name'], $hidecols) ? 'checked' : ''; ?>> <?php echo $row['label']; ?>
<?php }else{ ?>
<input type="checkbox" name="fields[]" class="customized-stage-check" value="<?php echo $row['name']; ?>"  checked="checked"> <?php echo $row['label']; ?>
<?php } ?>
<?php endforeach; ?>
</div>		
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
<?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Column Width Setting Modal (single column) -->
<div class="modal fade" id="wfColWidthModal" tabindex="-1" role="dialog" aria-labelledby="wfColWidthModalLabel">
  <div class="modal-dialog" role="document" style="max-width:520px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="wfColWidthModalLabel">Set column width</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="wfColWidthIndex" value="">
        <div class="form-group">
          <label>Column <span class="text-danger">*</span></label>
          <select id="wfColWidthSelect" class="form-control"></select>
          <p class="text-muted tw-mt-1 tw-mb-0">Selected: <strong id="wfColWidthName"></strong></p>
        </div>
        <div class="form-group">
          <label>Width (px) <span class="text-danger">*</span></label>
          <input type="number" class="form-control" id="wfColWidthPx" min="120" max="1200" step="10" value="180">
          <small class="text-muted">Minimum 180px recommended.</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="button" class="btn btn-default" id="wfColWidthResetBtn">Reset</button>
        <button type="button" class="btn btn-info" id="wfColWidthAllBtn">Set all columns</button>
        <button type="button" class="btn btn-primary" id="wfColWidthSaveBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- Column Width Setting Modal (all columns) -->
<div class="modal fade" id="wfAllColWidthModal" tabindex="-1" role="dialog" aria-labelledby="wfAllColWidthModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="max-width:860px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="wfAllColWidthModalLabel">Set all column widths</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Column</th>
                <th style="width:220px;">Width (px)</th>
              </tr>
            </thead>
            <tbody id="wfAllColWidthBody">
              <!-- rows injected by JS -->
            </tbody>
          </table>
        </div>
        <small class="text-muted">Tip: Use 180 for normal, 420 for expanded columns.</small>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="button" class="btn btn-default" id="wfAllColWidthResetBtn">Reset all</button>
        <button type="button" class="btn btn-primary" id="wfAllColWidthSaveBtn">Save</button>
      </div>
    </div>
  </div>
</div>

<style>
  #webFormEmailModal .wf-tpl-var-input.wf-tpl-var-missing {
    border-color: #dc3545 !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 6px rgba(220, 53, 69, .35);
  }
  #webFormEmailModal .email-tags-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    min-height: 36px;
    background: #fff;
    cursor: text;
  }
  #webFormEmailModal .email-tags-container:focus-within {
    border-color: #66afe9;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
  }
  #webFormEmailModal .email-tag {
    display: inline-flex;
    align-items: center;
    background: #e0e0e0;
    color: #333;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 13px;
  }
  #webFormEmailModal .email-tag.invalid {
    background: #f8d7da;
    color: #721c24;
  }
  #webFormEmailModal .email-tag .remove-tag {
    margin-left: 6px;
    cursor: pointer;
    font-weight: bold;
    color: #666;
  }
  #webFormEmailModal .email-tag .remove-tag:hover { color: #c00; }
  #webFormEmailModal .email-input-field {
    flex: 1;
    min-width: 120px;
    border: none;
    outline: none;
    font-size: 14px;
    padding: 2px 0;
  }
  #webFormEmailModal .email-suggestions {
    position: absolute;
    z-index: 1050;
    background: #fff;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    display: none;
  }
  #webFormEmailModal .email-suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
    font-size: 13px;
  }
  #webFormEmailModal .email-suggestion-item:hover,
  #webFormEmailModal .email-suggestion-item.active {
    background: #f0f0f0;
  }
  #webFormEmailModal .email-input-wrapper { position: relative; }
</style>
<!-- Send email from row (compose or template) -->
<div class="modal fade" id="webFormEmailModal" tabindex="-1" role="dialog" aria-labelledby="webFormEmailModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="webFormEmailModalLabel">Send email <span id="wfModalEmailLabel"></span></h4>
      </div>
      <div class="modal-body">
        <div class="form-group tw-mb-3">
          <label class="radio-inline">
            <input type="radio" name="wf_email_mode" value="new" checked> Send New Email
          </label>
          <label class="radio-inline tw-ml-3">
            <input type="radio" name="wf_email_mode" value="template"> Send Template Email
          </label>
        </div>

        <div id="wfTemplateOnlyBlock" style="display:none;">
          <div class="form-group">
            <label>Template</label>
            <select id="wfTemplateSelect" class="form-control">
              <option value="">— Select template —</option>
            </select>
          </div>

          <div id="wfTplVarsWrap" class="tw-mb-3" style="display:none;">
            <label>Template variables</label>
            <p class="text-muted tw-text-sm tw-mb-2">Fill or change values for dynamic fields (e.g. <code>{{FromDate}}</code>, <code>{{ToDate}}</code>) before sending. Row data is used when it matches the name.</p>
            <div id="wfTplVarsContainer" class="well well-sm" style="max-height:220px; overflow:auto;"></div>
          </div>

          <div class="alert alert-warning hide tw-mb-3" id="wfPlaceholderWarning"></div>
        </div>
<?php
//print_r($_SESSION['mailersdropdowns']);
//$reply_from_email=$_SESSION['webmail']['id'] ?? '';

//echo $_SESSION['smtp_fetch_type'];
//echo $_SESSION['STAFFSMTP']['smtp_user'];  // Get Webmail Setup Email ID after login config / email.php
//echo $_SESSION['staff_fromemai_id'] ?? ''; // Get Webmail Setup Email table ID after login config / email.php
?>
        <hr class="tw-my-3" />
        <div id="wfCommonEmailFields">
		  <div class="form-group">
            <label>From : </label>
			<?php if($_SESSION['smtp_fetch_type']=='CompanySMTP'){ echo $_SESSION['STAFFSMTP']['smtp_user']; echo '<i class="fa-solid fa-circle-info tw-mx-2 text-info" title="Staff Email Not Configured – Please Add Details in Webmail Setup"></i>';
			?>
			<input type="hidden" name="reply_from_email" id="reply_from_email"  />
			<?php
			}else{ 
			//echo $_SESSION['STAFFSMTP']['smtp_user'];?>
            <select name="reply_from_email" id="reply_from_email" class="form-control" required>
    <option value="">Select Email</option>
   <?php foreach ($_SESSION['mailersdropdowns'] as $row) { ?>
        <option value="<?php echo $row['id']; ?>" <?php if($row['mailer_email']==$_SESSION['STAFFSMTP']['smtp_user']){ ?> selected="selected" <?php } ?> ><?php echo $row['mailer_email']; ?> </option>
    <?php } ?>

</select>
            <?php } ?>
			 
          </div>
		  
          <div class="form-group">
            <label>To <span class="text-danger">*</span></label>
            <input type="text" id="wfEmailTo" class="form-control" placeholder="recipient@example.com">
          </div>
		  <span class="pull-right">
		<a class="tw-px-0 toggleBtn" data-id="toggleCc" title="Add Cc">Cc</a> <a class="tw-px-2 toggleBtn" data-id="toggleBcc" title="Add Bcc">Bcc</a></span>
          <div class="form-group" id="toggleCc" style="display:none;">
            <label>Cc</label>
            <input type="hidden" id="wfEmailCc" value="">
            <div class="email-input-wrapper">
              <div class="email-tags-container" id="wfCcTagsContainer">
                <input type="text" class="email-input-field" id="wfCcInputField" placeholder="Type email — suggestions from your mail history" autocomplete="off">
              </div>
              <div class="email-suggestions" id="wfCcEmailSuggestions"></div>
            </div>
          </div>
          <div class="form-group" id="toggleBcc" style="display:none;">
            <label>Bcc</label>
            <input type="hidden" id="wfEmailBcc" value="">
            <div class="email-input-wrapper">
              <div class="email-tags-container" id="wfBccTagsContainer">
                <input type="text" class="email-input-field" id="wfBccInputField" placeholder="Type email — suggestions from your mail history" autocomplete="off">
              </div>
              <div class="email-suggestions" id="wfBccEmailSuggestions"></div>
            </div>
          </div>
          <div class="form-group">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" id="wfFinalSubject" class="form-control">
          </div>
          <div class="form-group">
            <label>Body <span class="text-danger">*</span></label>
            <textarea id="wfFinalBody" class="form-control email_editor" rows="8"></textarea>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="button" class="btn btn-primary" id="wfSendEmailBtn">
          <i class="fa-regular fa-paper-plane tw-mr-1"></i> Send
        </button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<?php /*?><link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>//$('.editor').jqte();</script><?php */?>

<link rel="stylesheet" href="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.css"/>
<script src="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.js"></script>

<script>
window.email_editor = Jodit.make('#wfFinalBody');
window.editor = Jodit.make('.editor');
</script>

<script>
  (function() {
    var webFormFieldsMeta = <?php echo json_encode(array_map(function ($f) {
      return ['name' => $f['name'], 'label' => $f['label'] ?? ''];
    }, $tablefields)); ?>;

    var wfSearchMailerEmail = '<?php echo isset($_SESSION['webmail']['mailer_email']) ? addslashes($_SESSION['webmail']['mailer_email']) : ''; ?>';

    /** Cc/Bcc tag inputs + webmail/search_emails autosuggest (same pattern as email templates) */
    function wfCreateEmailTagInput(options) {
      var emailTags = [];
      var $container = $(options.container);
      var $inputField = $(options.inputField);
      var $hiddenInput = $(options.hiddenInput);
      var $suggestions = $(options.suggestions);
      var searchTimeout = null;
      var activeSuggestionIndex = -1;
      var currentSuggestions = [];

      function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
      }

      function updateHiddenInput() {
        $hiddenInput.val(emailTags.join(','));
      }

      function createTag(email) {
        email = (email || '').trim();
        if (!email) return;
        if (emailTags.indexOf(email) !== -1) return;
        emailTags.push(email);
        var isValid = isValidEmail(email);
        var $tag = $('<span class="email-tag' + (isValid ? '' : ' invalid') + '"></span>');
        $tag.text(email);
        var $remove = $('<span class="remove-tag">&times;</span>');
        $remove.on('click', function() {
          var idx = emailTags.indexOf(email);
          if (idx > -1) emailTags.splice(idx, 1);
          $tag.remove();
          updateHiddenInput();
        });
        $tag.append($remove);
        $tag.insertBefore($inputField);
        updateHiddenInput();
      }

      function hideSuggestions() {
        $suggestions.hide().empty();
        currentSuggestions = [];
        activeSuggestionIndex = -1;
      }

      function showSuggestions(emails) {
        $suggestions.empty();
        currentSuggestions = emails;
        activeSuggestionIndex = -1;
        if (!emails.length) {
          hideSuggestions();
          return;
        }
        emails.forEach(function(email, idx) {
          var $item = $('<div class="email-suggestion-item"></div>');
          $item.text(email);
          $item.attr('data-index', idx);
          $item.on('mousedown', function(e) {
            e.preventDefault();
            $inputField.val('');
            createTag(email);
            hideSuggestions();
            $inputField.focus();
          });
          $suggestions.append($item);
        });
        $suggestions.show();
      }

      function selectActiveSuggestion() {
        if (activeSuggestionIndex >= 0 && activeSuggestionIndex < currentSuggestions.length) {
          createTag(currentSuggestions[activeSuggestionIndex]);
          $inputField.val('');
          hideSuggestions();
        }
      }

      function updateActiveSuggestion() {
        $suggestions.find('.email-suggestion-item').removeClass('active');
        if (activeSuggestionIndex >= 0) {
          $suggestions.find('.email-suggestion-item[data-index="' + activeSuggestionIndex + '"]').addClass('active');
        }
      }

      function searchEmails(term) {
        if (term.length < 2 || !wfSearchMailerEmail) {
          hideSuggestions();
          return;
        }
        $.ajax({
          url: admin_url + 'webmail/search_emails',
          type: 'GET',
          data: { term: term, email: wfSearchMailerEmail },
          dataType: 'json',
          success: function(data) {
            if (Array.isArray(data)) {
              var filtered = data.filter(function(e) {
                return emailTags.indexOf(e) === -1;
              });
              showSuggestions(filtered);
            } else {
              hideSuggestions();
            }
          },
          error: function() {
            hideSuggestions();
          }
        });
      }

      $inputField.on('keydown', function(e) {
        var val = $(this).val();
        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (currentSuggestions.length > 0) {
            activeSuggestionIndex = Math.min(activeSuggestionIndex + 1, currentSuggestions.length - 1);
            updateActiveSuggestion();
          }
          return;
        }
        if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (currentSuggestions.length > 0) {
            activeSuggestionIndex = Math.max(activeSuggestionIndex - 1, 0);
            updateActiveSuggestion();
          }
          return;
        }
        if (e.key === 'Enter' || e.key === ',' || e.key === ';') {
          e.preventDefault();
          if (activeSuggestionIndex >= 0) {
            selectActiveSuggestion();
          } else if (val.trim()) {
            createTag(val.trim());
            $(this).val('');
            hideSuggestions();
          }
          return;
        }
        if (e.key === 'Backspace' && val === '') {
          if (emailTags.length > 0) {
            emailTags.pop();
            $container.find('.email-tag').last().remove();
            updateHiddenInput();
          }
          return;
        }
        if (e.key === 'Escape') {
          hideSuggestions();
          return;
        }
      });

      $inputField.on('input', function() {
        var val = $(this).val();
        if (val.indexOf(',') > -1 || val.indexOf(';') > -1) {
          val.split(/[,;]+/).forEach(function(part) {
            if (part.trim()) createTag(part.trim());
          });
          $(this).val('');
          hideSuggestions();
          return;
        }
        if (searchTimeout) clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
          searchEmails(val.trim());
        }, 300);
      });

      $inputField.on('blur', function() {
        var val = $(this).val().trim();
        if (val) {
          createTag(val);
          $(this).val('');
        }
        setTimeout(hideSuggestions, 200);
      });

      $container.on('click', function(e) {
        if (e.target === this || $(e.target).hasClass('email-tags-container')) {
          $inputField.focus();
        }
      });

      $inputField.on('paste', function(e) {
        e.preventDefault();
        var pasteData = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        pasteData.split(/[,;\s\n]+/).forEach(function(email) {
          if (email.trim()) createTag(email.trim());
        });
      });

      return {
        createTag: createTag,
        updateHiddenInput: updateHiddenInput,
        reset: function() {
          emailTags = [];
          $container.find('.email-tag').remove();
          $inputField.val('');
          updateHiddenInput();
          hideSuggestions();
        }
      };
    }

    var wfCcTagInst = wfCreateEmailTagInput({
      container: '#wfCcTagsContainer',
      inputField: '#wfCcInputField',
      hiddenInput: '#wfEmailCc',
      suggestions: '#wfCcEmailSuggestions'
    });
    var wfBccTagInst = wfCreateEmailTagInput({
      container: '#wfBccTagsContainer',
      inputField: '#wfBccInputField',
      hiddenInput: '#wfEmailBcc',
      suggestions: '#wfBccEmailSuggestions'
    });

    function wfFlushCcBccInputsToTags() {
      var v = $('#wfCcInputField').val().trim();
      if (v) {
        wfCcTagInst.createTag(v);
        $('#wfCcInputField').val('');
      }
      v = $('#wfBccInputField').val().trim();
      if (v) {
        wfBccTagInst.createTag(v);
        $('#wfBccInputField').val('');
      }
      wfCcTagInst.updateHiddenInput();
      wfBccTagInst.updateHiddenInput();
    }

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
          //$(this).jqteVal('');
		  window.editor.value = '';
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
          if ($el.hasClass('editor')) {
            window.editor.value = val;
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

    // --- Row email: compose link or send from CRM email template ---
    var wfTemplatesLoaded = false;
    var wfCurrentTemplateId = 0;
    var wfRebuildTimer = null;

    function wfBuildMapFromEntry(entryData, fieldsMeta) {
      var map = {};
      Object.keys(entryData || {}).forEach(function(k) {
        var v = entryData[k];
        if (Array.isArray(v)) v = v.join(', ');
        map[k] = (v === null || v === undefined) ? '' : String(v);
      });
      (fieldsMeta || []).forEach(function(f) {
        var name = f.name;
        var labelKey = (f.label || '').replace(/\s+/g, '');
        if (labelKey && map[name] !== undefined) {
          map[labelKey] = map[name];
        }
      });
      return map;
    }

    function wfApplyPlaceholdersMap(text, map) {
      if (!text) return '';
      return text.replace(/\{\{\s*([^}]+?)\s*\}\}/g, function(_, raw) {
        var k = raw.trim();
        if (map[k] !== undefined) return map[k];
        var lower = k.toLowerCase();
        for (var ek in map) {
          if (ek.toLowerCase() === lower) return map[ek];
        }
        return '';
      });
    }

    function wfApplyPlaceholders(text, entryData, fieldsMeta) {
      return wfApplyPlaceholdersMap(text, wfBuildMapFromEntry(entryData, fieldsMeta));
    }

    function wfExtractVarNames(subject, body) {
      var text = (subject || '') + '\n' + (body || '');
      var vars = [];
      var re = /\{\{\s*([^}]+?)\s*\}\}/g;
      var m;
      while ((m = re.exec(text)) !== null) {
        var key = (m[1] || '').trim();
        if (key && vars.indexOf(key) === -1) {
          vars.push(key);
        }
      }
      return vars;
    }

    function wfRenderTemplateVarInputs(tpl) {
      var names = wfExtractVarNames(tpl.subject, tpl.body);
      var $wrap = $('#wfTplVarsWrap');
      var $c = $('#wfTplVarsContainer');
      $c.empty();
      if (!names.length) {
        $wrap.hide();
        return;
      }
      var baseMap = wfBuildMapFromEntry(window._wfModalEntryData || {}, webFormFieldsMeta);
      names.forEach(function(name) {
        var val = '';
        if (baseMap[name] !== undefined) {
          val = baseMap[name];
        } else {
          var lower = name.toLowerCase();
          for (var ek in baseMap) {
            if (ek.toLowerCase() === lower) {
              val = baseMap[ek];
              break;
            }
          }
        }
        var $div = $('<div class="form-group tw-mb-2"></div>');
        $div.append($('<label class="tw-text-sm tw-mb-1" style="font-weight:normal;"></label>').text('{{' + name + '}}'));
        var $inp = $('<input type="text" class="form-control input-sm wf-tpl-var-input" autocomplete="off"/>');
        $inp.attr('data-var', name);
        $inp.val(val);
        $div.append($inp);
        $c.append($div);
      });
      $wrap.show();
    }

    function wfRebuildFromTemplateVars() {
      var raw = window._wfRawTpl;
      if (!raw) {
        return;
      }
      var merged = wfBuildMapFromEntry(window._wfModalEntryData || {}, webFormFieldsMeta);
      $('.wf-tpl-var-input').each(function() {
        var k = $(this).attr('data-var');
        if (k) {
          merged[k] = $(this).val();
        }
      });
      var subj = wfApplyPlaceholdersMap(raw.subject || '', merged);
      var body = wfApplyPlaceholdersMap(raw.body || '', merged);
      $('#wfFinalSubject').val(subj);
      if (typeof window.email_editor !== 'undefined' && window.email_editor) {
        window.email_editor.value = body;
      } else {
        $('#wfFinalBody').val(body);
      }
      wfShowPlaceholderWarning(subj, body);
    }

    function wfScheduleRebuildFromVars() {
      clearTimeout(wfRebuildTimer);
      wfRebuildTimer = setTimeout(function() {
        wfRebuildFromTemplateVars();
      }, 120);
    }

    function wfShowPlaceholderWarning(subject, body) {
      var combined = (subject || '') + ' ' + (body || '');
      if (/\{\{\s*[^}]+\s*\}\}/.test(combined)) {
        $('#wfPlaceholderWarning').removeClass('hide').text('Some {{placeholders}} are still in the text. Use the Template variables fields above, or edit Subject/Body below.');
      } else {
        $('#wfPlaceholderWarning').addClass('hide').text('');
      }
    }

    function wfLoadTemplates() {
      if (wfTemplatesLoaded) return;
      $.getJSON(admin_url + 'email_template/templates_json')
        .done(function(resp) {
          wfTemplatesLoaded = true;
          if (!resp || !resp.success || !resp.templates) return;
          var $sel = $('#wfTemplateSelect');
          $sel.find('option:not(:first)').remove();
          resp.templates.forEach(function(t) {
            $sel.append($('<option></option>').attr('value', t.id).text(t.subject || ('Template #' + t.id)));
          });
        });
    }

    function wfFetchTemplateAndApply(id) {
      id = parseInt(id, 10) || 0;
      if (!id) {
        window._wfRawTpl = null;
        $('#wfTplVarsWrap').hide();
        $('#wfTplVarsContainer').empty();
        wfCurrentTemplateId = 0;
        $('#wfFinalSubject').val('');
        if (typeof window.email_editor !== 'undefined' && window.email_editor) {
          window.email_editor.value = '';
        } else {
          $('#wfFinalBody').val('');
        }
        $('#wfPlaceholderWarning').addClass('hide');
        return;
      }
      $.getJSON(admin_url + 'email_template/template_json/' + id)
        .done(function(resp) {
          if (!resp || !resp.success || !resp.template) {
            alert_float('danger', 'Could not load template');
            return;
          }
          var tpl = resp.template;
          wfCurrentTemplateId = tpl.id;
          window._wfRawTpl = {
            subject: tpl.subject || '',
            body: tpl.body || ''
          };
          wfRenderTemplateVarInputs(tpl);
          wfRebuildFromTemplateVars();
        });
    }

    $('body').on('click', '.web-email-trigger', function(e) {
      e.preventDefault();
      e.stopPropagation();
      var email = $(this).data('email') || '';
      var jsonStr = $(this).closest('tr').attr('data-entry-json') || '{}';
      var entryData = {};
      try { entryData = JSON.parse(jsonStr) || {}; } catch (err) { entryData = {}; }
      window._wfModalEntryData = entryData;
      $('#wfModalEmailLabel').text('To: ' + email);
      $('#wfEmailTo').val(email);
      $('input[name="wf_email_mode"][value="new"]').prop('checked', true);
      $('#wfTemplateOnlyBlock').hide();
      $('#wfTemplateSelect').val('');
      window._wfRawTpl = null;
      $('#wfTplVarsWrap').hide();
      $('#wfTplVarsContainer').empty();
      wfCcTagInst.reset();
      wfBccTagInst.reset();
      $('#wfFinalSubject').val('');
      $('#wfFinalBody').val('');
      if (typeof window.email_editor !== 'undefined' && window.email_editor) {
        window.email_editor.value = '';
      }
      $('#wfPlaceholderWarning').addClass('hide');
      wfCurrentTemplateId = 0;
      wfTemplatesLoaded = false;
      $('#wfTemplateSelect').find('option:not(:first)').remove();
      $('#webFormEmailModal').appendTo('body').modal('show');
    });

    function wfValidateDynamicFieldsBeforeSend() {
      if ($('input[name="wf_email_mode"]:checked').val() !== 'template') {
        return { ok: true };
      }
      $('.wf-tpl-var-input').removeClass('wf-tpl-var-missing');
      var emptyNames = [];
      $('.wf-tpl-var-input').each(function() {
        var $inp = $(this);
        var name = $inp.attr('data-var') || '';
        if ($.trim($inp.val()) === '') {
          emptyNames.push(name ? ('{{' + name + '}}') : '?');
          $inp.addClass('wf-tpl-var-missing');
        }
      });
      if (emptyNames.length) {
        var $first = $('#wfTplVarsContainer').find('.wf-tpl-var-missing').first();
        if ($first.length) {
          $('#wfTplVarsWrap').show();
          $first.focus();
        }
        return {
          ok: false,
          message: 'Fill all template variables before sending: ' + emptyNames.join(', ')
        };
      }
      var subj = $('#wfFinalSubject').val() || '';
      var bodyStr = '';
      if (typeof window.email_editor !== 'undefined' && window.email_editor) {
        bodyStr = window.email_editor.value || '';
      } else {
        bodyStr = $('#wfFinalBody').val() || '';
      }
      if (/\{\{\s*[^}]+\s*\}\}/.test(subj + ' ' + bodyStr)) {
        return {
          ok: false,
          message: 'Unresolved {{placeholders}} remain. Fill all template variables or remove placeholders from subject/body.'
        };
      }
      return { ok: true };
    }

    $('body').on('input change', '.wf-tpl-var-input', function() {
      $(this).removeClass('wf-tpl-var-missing');
      wfScheduleRebuildFromVars();
    });

    $('input[name="wf_email_mode"]').on('change', function() {
      var v = $('input[name="wf_email_mode"]:checked').val();
      if (v === 'template') {
        $('#wfTemplateOnlyBlock').show();
        wfLoadTemplates();
        wfFetchTemplateAndApply($('#wfTemplateSelect').val());
      } else {
        $('#wfTemplateOnlyBlock').hide();
        window._wfRawTpl = null;
        wfCurrentTemplateId = 0;
        $('#wfTplVarsWrap').hide();
        $('#wfTplVarsContainer').empty();
        $('#wfTemplateSelect').val('');
        $('#wfPlaceholderWarning').addClass('hide');
      }
    });

    $('#wfTemplateSelect').on('change', function() {
      wfFetchTemplateAndApply($(this).val());
    });

    $('#wfSendEmailBtn').on('click', function() {
      var mode = $('input[name="wf_email_mode"]:checked').val();
      var to = $('#wfEmailTo').val().trim();
      if (!to) {
        alert_float('warning', 'To is required');
        return;
      }
	  var reply_from = $('#reply_from_email').val().trim();
	  //alert(reply_from); return false;
      var subj = $('#wfFinalSubject').val().trim();
      var body = '';
      if (typeof window.email_editor !== 'undefined' && window.email_editor) {
        body = window.email_editor.value;
      } else {
        body = $('#wfFinalBody').val();
      }
      body = body || '';
      if (!subj || !body) {
        alert_float('warning', 'Subject and body are required');
        return;
      }

      wfFlushCcBccInputsToTags();

      var $btn = $(this);
      $btn.prop('disabled', true);

      if (mode === 'template') {
        var tid = parseInt(wfCurrentTemplateId, 10) || parseInt($('#wfTemplateSelect').val(), 10);
        if (!tid) {
          alert_float('warning', 'Select a template');
          $btn.prop('disabled', false);
          return;
        }
        var dynCheck = wfValidateDynamicFieldsBeforeSend();
        if (!dynCheck.ok) {
          alert_float('warning', dynCheck.message);
          $btn.prop('disabled', false);
          return;
        }
        $.ajax({
          url: admin_url + 'email_template/send',
          type: 'POST',
          dataType: 'json',
          data: {
            template_id: tid,
            reply_from: reply_from,
			to_email: to,
            cc_email: $('#wfEmailCc').val(),
            bcc_email: $('#wfEmailBcc').val(),
            final_subject: subj,
            final_body: body,
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
          }
        }).done(function(resp) {
          if (resp && resp.success) {
            alert_float('success', resp.message || 'Sent');
            $('#webFormEmailModal').modal('hide');
          } else {
            alert_float('danger', (resp && resp.message) ? resp.message : 'Failed to send');
          }
        }).fail(function(xhr) {
          var msg = 'Request failed';
          if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
          alert_float('danger', msg);
        }).always(function() {
          $btn.prop('disabled', false);
        });
        return;
      }

      // Send New Email — plain send (no compose URL)
      $.ajax({
        url: admin_url + 'web_form/send_row_email',
        type: 'POST',
        dataType: 'json',
        data: {
          to_email: to,
		  reply_from: reply_from,
          cc_email: $('#wfEmailCc').val(),
          bcc_email: $('#wfEmailBcc').val(),
          final_subject: subj,
          final_body: body,
          <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
        }
      }).done(function(resp) {
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Sent');
          $('#webFormEmailModal').modal('hide');
        } else {
          alert_float('danger', (resp && resp.message) ? resp.message : 'Failed to send');
        }
      }).fail(function(xhr) {
        var msg = 'Request failed';
        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
        alert_float('danger', msg);
      }).always(function() {
        $btn.prop('disabled', false);
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

      var $fieldSelect = $('<select class="form-control criterion-field tw-mb-2" required></select>');
      searchFields.forEach(function(f) {
        var $opt = $('<option></option>').val(f.name).text(f.label);
        if (c.field === f.name) $opt.attr('selected', 'selected');
        $fieldSelect.append($opt);
      });

      var $opSelect = $('<select class="form-control tw-mb-2 criterion-op"></select>');
      $opSelect.append('<option value="contains" ' + (c.op === 'contains' ? 'selected' : '') + '>Contains</option>');
      $opSelect.append('<option value="equals" ' + (c.op === 'equals' ? 'selected' : '') + '>Equals</option>');

      var $valueInput = $('<input type="text" class="form-control criterion-value tw-mb-2" placeholder="Enter value">');
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

// Column width settings (click heading to set width)
(function() {
  var formId = <?php echo (int)$form['id']; ?>;
  var storageKey = 'wf_col_widths_' + formId;
  var expandedIndex = null; // used for quick expand toggle (dblclick)

  function loadMap() {
    try {
      var raw = localStorage.getItem(storageKey);
      var obj = raw ? JSON.parse(raw) : {};
      return (obj && typeof obj === 'object') ? obj : {};
    } catch (e) {
      return {};
    }
  }

  function saveMap(map) {
    try { localStorage.setItem(storageKey, JSON.stringify(map || {})); } catch (e) {}
  }

  function getTable() {
    return $('#webFormEntriesTable');
  }

  function getExpandableThs() {
    return getTable().find('thead th.expand-input');
  }

  function clearInlineWidths() {
    var $t = getTable();
    $t.find('thead th, tbody td').css({ width: '', 'min-width': '' });
  }

  function applyMap(map) {
    var $t = getTable();
    if (!$t.length) return;
    var $ths = $t.find('thead th');
    var colCount = $ths.length;

    for (var i = 0; i < colCount; i++) {
      var thSel = 'thead th:nth-child(' + (i + 1) + ')';
      var tdSel = 'tbody td:nth-child(' + (i + 1) + ')';
      var px = map[i];
      if (px === undefined || px === null || px === '') continue;
      px = parseInt(px, 10);
      if (isNaN(px) || px < 120) continue;
      $t.find(thSel + ',' + tdSel).css({ width: px + 'px', 'min-width': px + 'px' });
    }
  }

  function applyExpandedToggle() {
    var $t = getTable();
    if (!$t.length) return;
    $t.find('thead th').removeClass('wf-col-expanded wf-col-normal wf-col-active');
    $t.find('tbody td').removeClass('wf-col-expanded wf-col-normal');

    // default min width for all expandable headings
    $t.find('thead th.expand-input, tbody td').each(function() {});

    var $ths = $t.find('thead th');
    var colCount = $ths.length;
    for (var i = 0; i < colCount; i++) {
      var thSel = 'thead th:nth-child(' + (i + 1) + ')';
      var tdSel = 'tbody td:nth-child(' + (i + 1) + ')';
      if (!$t.find(thSel).hasClass('expand-input')) continue;

      if (expandedIndex !== null && i === expandedIndex) {
        $t.find(thSel).addClass('wf-col-expanded wf-col-active');
        $t.find(tdSel).addClass('wf-col-expanded');
      } else {
        $t.find(thSel).addClass('wf-col-normal');
        $t.find(tdSel).addClass('wf-col-normal');
      }
    }
  }

  function reapplyAll() {
    clearInlineWidths();
    applyExpandedToggle();
    applyMap(loadMap());
    if ($.fn.dataTable && $.fn.dataTable.isDataTable(getTable())) {
      getTable().DataTable().columns.adjust();
    }
  }

  function openSingleModal(idx, name) {
    var map = loadMap();
    var cur = map[idx];
    if (cur === undefined || cur === null || cur === '') cur = 180;
    $('#wfColWidthIndex').val(idx);
    $('#wfColWidthName').text(name || ('Column ' + (idx + 1)));
    $('#wfColWidthPx').val(parseInt(cur, 10) || 180);
    $('#wfColWidthModal').appendTo('body').modal('show');
  }

  function renderAllModal() {
    var map = loadMap();
    var $body = $('#wfAllColWidthBody');
    $body.empty();
    getExpandableThs().each(function() {
      var idx = $(this).index();
      var name = $.trim($(this).text() || '');
      var val = map[idx];
      if (val === undefined || val === null || val === '') val = 180;
      var safeName = $('<div/>').text(name).html();
      $body.append(
        '<tr>' +
          '<td>' + safeName + '</td>' +
          '<td><input type="number" class="form-control input-sm wf-all-col-width" data-idx="' + idx + '" min="120" max="1200" step="10" value="' + (parseInt(val, 10) || 180) + '"></td>' +
        '</tr>'
      );
    });
  }

  // Click header: quick expand/collapse (no modal)
  $(document).on('click', '#webFormEntriesTable thead th.expand-input', function(e) {
    e.preventDefault();
    var idx = $(this).index();
    expandedIndex = (expandedIndex === idx) ? null : idx;
    reapplyAll();
  });

  // Modal actions
  function syncSingleModalFromSelect() {
    var idx = parseInt($('#wfColWidthSelect').val(), 10);
    if (isNaN(idx) || idx < 0) return;
    var name = $('#wfColWidthSelect option:selected').text() || ('Column ' + (idx + 1));
    $('#wfColWidthIndex').val(idx);
    $('#wfColWidthName').text(name);
    var map = loadMap();
    var cur = map[idx];
    if (cur === undefined || cur === null || cur === '') cur = 180;
    $('#wfColWidthPx').val(parseInt(cur, 10) || 180);
  }

  function openSingleModal(idx, name) {
    // build dropdown each time (in case columns changed)
    var $sel = $('#wfColWidthSelect');
    $sel.empty();
    getExpandableThs().each(function() {
      var i = $(this).index();
      var n = $.trim($(this).text() || ('Column ' + (i + 1)));
      $sel.append($('<option></option>').val(i).text(n));
    });
    if (idx !== undefined && idx !== null) {
      $sel.val(String(idx));
    } else {
      $sel.val($sel.find('option:first').val());
    }
    $('#wfColWidthModal').appendTo('body').modal('show');
    syncSingleModalFromSelect();
  }

  $('#wfColWidthSelect').on('change', function() {
    syncSingleModalFromSelect();
  });

  // Open modal from icon button (not from heading)
  $('#openColWidthBtn').on('click', function(e) {
    e.preventDefault();
    openSingleModal(expandedIndex, '');
  });

  $('#wfColWidthSaveBtn').on('click', function() {
    var idx = parseInt($('#wfColWidthIndex').val(), 10);
    var px = parseInt($('#wfColWidthPx').val(), 10);
    if (isNaN(idx) || idx < 0) return;
    if (isNaN(px) || px < 120) {
      alert_float('warning', 'Please enter a valid width (min 120px)');
      return;
    }
    var map = loadMap();
    map[idx] = px;
    saveMap(map);
    $('#wfColWidthModal').modal('hide');
    reapplyAll();
  });

  $('#wfColWidthResetBtn').on('click', function() {
    var idx = parseInt($('#wfColWidthIndex').val(), 10);
    if (isNaN(idx) || idx < 0) return;
    var map = loadMap();
    delete map[idx];
    saveMap(map);
    $('#wfColWidthModal').modal('hide');
    reapplyAll();
  });

  $('#wfColWidthAllBtn').on('click', function() {
    $('#wfColWidthModal').modal('hide');
    renderAllModal();
    $('#wfAllColWidthModal').appendTo('body').modal('show');
  });

  $('#wfAllColWidthSaveBtn').on('click', function() {
    var map = loadMap();
    $('#wfAllColWidthBody .wf-all-col-width').each(function() {
      var idx = parseInt($(this).attr('data-idx'), 10);
      var px = parseInt($(this).val(), 10);
      if (!isNaN(idx) && !isNaN(px) && px >= 120) {
        map[idx] = px;
      }
    });
    saveMap(map);
    $('#wfAllColWidthModal').modal('hide');
    reapplyAll();
  });

  $('#wfAllColWidthResetBtn').on('click', function() {
    saveMap({});
    $('#wfAllColWidthModal').modal('hide');
    reapplyAll();
  });

  // Reapply after DataTables redraw
  getTable().on('draw.dt', function() {
    reapplyAll();
  });

  // Initial apply
  $(function() { reapplyAll(); });
})();
</script>

</body>
</html>

