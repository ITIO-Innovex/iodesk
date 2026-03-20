<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); $companyId = get_staff_company_id(); ?>
<style>
  .email-tags-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    min-height: 38px;
    background: #fff;
    cursor: text;
  }
  .email-tags-container:focus-within {
    border-color: #66afe9;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
  }
  .email-tag {
    display: inline-flex;
    align-items: center;
    background: #e0e0e0;
    color: #333;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 13px;
  }
  .email-tag .remove-tag {
    margin-left: 6px;
    cursor: pointer;
    font-weight: bold;
    color: #666;
  }
  .email-tag .remove-tag:hover {
    color: #c00;
  }
  .email-input-field {
    flex: 1;
    min-width: 150px;
    border: none;
    outline: none;
    font-size: 14px;
    padding: 2px 0;
  }
  .email-suggestions {
    position: absolute;
    z-index: 1000;
    background: #fff;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    display: none;
  }
  .email-suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
  }
  .email-suggestion-item:hover, .email-suggestion-item.active {
    background: #f0f0f0;
  }
  .email-input-wrapper {
    position: relative;
  }
  .suggestion-name {
    font-weight: 500;
  }
  .suggestion-email {
    color: #666;
    font-size: 12px;
  }
  .expand-input {
    width:150px;
    transition: width 0.3s;
}

.expand-input:focus {
    width:400px;
}
</style>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
              Daily Activity Report (DAR) - <?php echo get_company_fields($companyId ,'email_dar') ?? '';?>
            <a href="<?php echo admin_url('hrd/dar_list');?>" id="upgrade_plan" class="btn btn-info btn-sm pull-right"><i class="fa-regular fa-eye"></i> View Send DAR</a></h4>
            <hr class="hr-panel-heading">

            <?php echo form_open(admin_url('hrd/daily_activity_report_dar'), ['id' => 'dar-entry-form']); ?>
            <input type="hidden" name="status" id="dar-status" value="2">
			<input type="hidden" id="dar_date" name="date" class="form-control" value="<?php echo html_escape($date); ?>" required>
            <?php /*?><div class="row tw-mb-3">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="dar_date" class="control-label">Date <small class="text-danger">*</small></label>
                  <input type="date" id="dar_date" name="date" class="form-control" value="<?php echo html_escape($date); ?>" required>
                </div>
              </div>
            </div><?php */?>
			
			

            <div class="row tw-mb-3">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="cc_email">CC</label>
                  <div class="email-input-wrapper">
                    <div class="email-tags-container" id="ccEmailTagsContainer">
                      <input type="text" name="cc_email_ip" class="email-input-field" id="ccEmailInputField" placeholder="Type name or email to search and press enter" autocomplete="off">
                    </div>
                    <div class="email-suggestions" id="ccEmailSuggestions"></div>
                  </div>
                  <input type="hidden" id="cc_email" name="cc_email" value="">
                </div>
              </div>
            </div>
            <?php if (!empty($dar_fields)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <?php foreach ($dar_fields as $f) { ?>
                        <th><?php echo html_escape($f['field_title']); ?> <small class="req text-danger" title="Required field">* </small></th>
                      <?php } ?>
                      <th style="width:90px;">Action</th>
                    </tr>
                  </thead>
                  <tbody id="dar-rows">
                    <?php
                    $rows = [];
                    if (!empty($existing_details) && is_array($existing_details)) {
                        $rows = $existing_details;
                    } else {
                        $rows = [[]]; // one empty row
                    }
                    foreach ($rows as $row) {
                        // Build map field_id => value for this row
                        $valuesById = [];
                        if (is_array($row)) {
                            foreach ($row as $cell) {
                                if (isset($cell['id'])) {
                                    $valuesById[(int)$cell['id']] = $cell['value'] ?? '';
                                }
                            }
                        }
                        ?>
                        <tr class="dar-row">
                          <?php foreach ($dar_fields as $f) {
                              $fid = (int)$f['id'];
                              $val = isset($valuesById[$fid]) ? $valuesById[$fid] : '';
							  $ftype="text";
							  $fieldcss="expand-input";
							  
							  
							  if(strstr($f['field_title'],"Time")){
							  $ftype="time";
							  $fieldcss="";
							  }elseif(strstr($f['field_title'],"Date")){
							  $ftype="date";
							  $fieldcss="";
							  }
							  
							  
                              ?>
                              <td>
<?php if(strstr($f['field_title'],"Status")){ ?>
  <select name="field_<?php echo $fid; ?>[]"  class="form-control <?php echo $fieldcss; ?>">
  <option value="">Select Status</option>
  <option value="Completed" <?php if($val=="Completed"){ ?> selected="selected" <?php } ?>>Completed</option>
  <option value="Pending" <?php if($val=="Pending"){ ?> selected="selected" <?php } ?>>Pending</option>
  <option value="Working" <?php if($val=="Working"){ ?> selected="selected" <?php } ?>>Working</option>
  </select>
							  
							  <?php }else{ ?>
                                <input type="<?php echo $ftype; ?>"
                                       name="field_<?php echo $fid; ?>[]"
                                       class="form-control <?php echo $fieldcss; ?>"
                                       value="<?php echo html_escape($val); ?>"
                                       placeholder="<?php echo html_escape($f['field_title']); ?>">
								<?php } ?>
                              </td>
                          <?php } ?>
                          <td>
                            <button type="button" class="btn btn-danger btn-xs dar-remove-row" title="Delete Row">
                              <i class="fa fa-trash"></i>
                            </button>
                          </td>
                        </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <div class="tw-my-2 pull-right">
                <button type="button" class="btn btn-danger add-dar-row" id="add-dar-row" mode='new'>
                  <i class="fa fa-plus-circle tw-mr-1"></i> Add New Row
                </button>
				<button type="button" class="btn btn-warning add-dar-row" mode='copy'>
                  <i class="fa fa-plus-circle tw-mr-1"></i> Copy Same Row
                </button>
              </div>
            <?php } else { ?>
              <p>No DAR fields configured for your department. Please contact HR.</p>
            <?php } ?>

            <div class="row tw-mt-3">
              <div class="col-md-12">
                <?php /*?><button type="submit" class="btn btn-primary">
                  <i class="fa fa-save tw-mr-1"></i> Save DAR
                </button><?php */?>
				<?php if (!isset($existing_status) || (int)$existing_status !== 1) { ?>
                  <button type="button" class="btn btn-default" data-status="2" id="dar-save-later" onclick="return confirm('Data will be saved as a draft. You can edit it anytime before final submission.')">Save Draft</button>
                  <button type="button" class="btn btn-primary" data-status="1" id="dar-save-submit" onclick="return confirm('Please confirm your submission. Once submitted, the data cannot be modified.')">Submit Now</button>
                <?php } else { ?>
                  <span class="btn btn-success">DAR Submitted </span>
                <?php } ?>
				
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
  $(function () {
    <?php /*?>$('#dar-entry-form').on('submit', function () {
      var dateVal = $('#dar_date').val();
      if (!dateVal) {
        alert('Please select a date.');
        $('#dar_date').focus();
        return false;
      }
      return true;
    });<?php */?>
	
	$('#dar-save-later, #dar-save-submit').on('click', function() {
      var status = $(this).data('status');
	  
	  var dateVal = $('#dar_date').val();
	  //alert(dateVal);
      if (!dateVal) {
        alert('Please select a date.');
        $('#dar_date').focus();
        return false;
      }
	  
      // Validate all DAR inputs are filled
      var firstEmpty = null;
      $('#dar-rows tr.dar-row').each(function() {
        $(this).find('input').each(function() {
          var val = $.trim($(this).val());
          if (!val && !firstEmpty) {
            firstEmpty = this;
          }
        });
      });

      if (firstEmpty) {
        alert('All DAR fields are required. Please fill in all cells before submitting.');
        $(firstEmpty).focus();
        return false;
      }
	  
      $('#dar-status').val(status);
      $('#dar-entry-form').submit();
    });

    $('.add-dar-row').on('click', function () {
      var $tbody = $('#dar-rows');
      var $first = $tbody.find('tr.dar-row:first');
      if ($first.length === 0) {
        return;
      }
	  var mode = $(this).attr('mode');
      var $clone = $first.clone();
	  if (mode === 'new') {
      $clone.find('input').val('');
	  }
      $tbody.append($clone);
    });

    // Delete row (keep at least one)
    $('#dar-rows').on('click', '.dar-remove-row', function () {
      var $rows = $('#dar-rows').find('tr.dar-row');
      if ($rows.length <= 1) {
        alert('At least one row is required.');
        return;
      }
	  
	   // confirm before delete
    if (!confirm('Are you sure you want to remove this row?')) {
        return;
    }
	
      $(this).closest('tr.dar-row').remove();
    });
  
    // CC Email Autocomplete with multiple tags (same as HRD DAR)
    (function() {
      var emailTags = [];
      var $container = $('#ccEmailTagsContainer');
      var $inputField = $('#ccEmailInputField');
      var $hiddenInput = $('#cc_email');
      var $suggestions = $('#ccEmailSuggestions');
      var searchTimeout = null;
      var activeSuggestionIndex = -1;
      var currentSuggestions = [];

      function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
      }

      function updateHiddenInput() {
        $hiddenInput.val(emailTags.join(','));
      }

      function createTag(email, name) {
        email = email.trim();
        if (!email) return;

        if (emailTags.indexOf(email) !== -1) {
          return;
        }

        emailTags.push(email);

        var displayText = name ? name + ' <' + email + '>' : email;
        var $tag = $('<span class="email-tag"></span>');
        $tag.text(displayText);
        $tag.attr('data-email', email);
        var $remove = $('<span class="remove-tag">&times;</span>');
        $remove.on('click', function() {
          var idx = emailTags.indexOf(email);
          if (idx > -1) {
            emailTags.splice(idx, 1);
          }
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

      function showSuggestions(staffList) {
        $suggestions.empty();
        currentSuggestions = staffList;
        activeSuggestionIndex = -1;

        if (staffList.length === 0) {
          hideSuggestions();
          return;
        }

        staffList.forEach(function(staff, idx) {
          var $item = $('<div class="email-suggestion-item"></div>');
          $item.html('<span class="suggestion-name">' + escapeHtml(staff.name) + '</span><br><span class="suggestion-email">' + escapeHtml(staff.email) + '</span>');
          $item.attr('data-index', idx);
          $item.on('mousedown', function(e) {
            e.preventDefault();
            $inputField.val('');
            createTag(staff.email, staff.name);
            hideSuggestions();
            $inputField.focus();
          });
          $suggestions.append($item);
        });

        $suggestions.show();
      }

      function escapeHtml(text) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(text));
        return div.innerHTML;
      }

      function selectActiveSuggestion() {
        if (activeSuggestionIndex >= 0 && activeSuggestionIndex < currentSuggestions.length) {
          var staff = currentSuggestions[activeSuggestionIndex];
          createTag(staff.email, staff.name);
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

      function searchStaffEmails(term) {
        if (term.length < 2) {
          hideSuggestions();
          return;
        }

        $.ajax({
          url: admin_url + 'hrd/search_staff_emails',
          type: 'GET',
          data: { term: term },
          dataType: 'json',
          success: function(data) {
            if (Array.isArray(data)) {
              var filtered = data.filter(function(staff) {
                return emailTags.indexOf(staff.email) === -1;
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
          } else if (val.trim() && isValidEmail(val.trim())) {
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
        var val = $(this).val().trim();
        if (searchTimeout) {
          clearTimeout(searchTimeout);
        }
        searchTimeout = setTimeout(function() {
          searchStaffEmails(val);
        }, 300);
      });

      $inputField.on('blur', function() {
        setTimeout(function() {
          hideSuggestions();
        }, 200);
      });

      $container.on('click', function() {
        $inputField.focus();
      });
    })();
  });
</script>

</body></html>

