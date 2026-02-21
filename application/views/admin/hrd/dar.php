<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); $companyId = get_staff_company_id(); ?>
<style>
  .jqte_tool.jqte_tool_1 .jqte_tool_label { height: 20px !important; }
  .jqte { margin: 10px 0 !important; }
  .jqte_editor { height: 300px !important; }
  
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
<label for="dar-description">To : </label><br />
<?php echo get_company_fields($companyId ,'email_dar') ?? '';?>
<div class="form-group">
                <label for="cc_email">CC</label>
                <div class="email-input-wrapper">
                    <div class="email-tags-container" id="ccEmailTagsContainer">
                        <input type="text" class="email-input-field" id="ccEmailInputField" placeholder="Type name or email to search" autocomplete="off">
                    </div>
                    <div class="email-suggestions" id="ccEmailSuggestions"></div>
                </div>
                <input type="hidden" id="cc_email" name="cc_email" value="">
              </div>
				
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
                                <?php if (empty($dar) || (int)$dar['status'] !== 1) { ?>
                                  <button type="button" class="btn btn-xs btn-danger dar-delete-file" data-id="<?php echo (int) ($dar['id'] ?? 0); ?>" data-file="<?php echo e(basename($file)); ?>">Delete</button>
                                <?php }else{ ?>
                                <button type="button" class="btn btn-xs btn-success dar-copy-link" data-link="<?php echo e(base_url($file)); ?>">Copy</button>
                                <?php } ?>
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
                  <button type="button" class="btn btn-primary" data-status="1" id="dar-save-submit" onclick="return confirm('Once submitted, you won\'t be able to edit this data. Do you want to continue?')">Submit</button>
                <?php } else { ?>
                  <span class="btn btn-success">DAR Submitted </span>
                <?php } ?>
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

    $('.dar-copy-link').on('click', function() {
      var link = $(this).data('link');
      if (!link) { return; }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(link).then(function() {
          alert_float('success', 'Link copied');
        }).catch(function() {
          alert('Link copied');
        });
        return;
      }
      var $temp = $('<input>');
      $('body').append($temp);
      $temp.val(link).select();
      document.execCommand('copy');
      $temp.remove();
      alert_float('success', 'Link copied');
    });

    $('#copy-dar-link').on('click', function() {
      var link = $('#dar-share-link').val();
      if (!link) { return; }
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(link).then(function() {
          alert_float('success', 'Link copied');
        }).catch(function() {
          alert('Link copied');
        });
        return;
      }
      var $temp = $('<input>');
      $('body').append($temp);
      $temp.val(link).select();
      document.execCommand('copy');
      $temp.remove();
      alert_float('success', 'Link copied');
    });

    // CC Email Autocomplete with multiple tags
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
