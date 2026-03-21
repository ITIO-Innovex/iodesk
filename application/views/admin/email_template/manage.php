<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style>
  .jqte { margin: 0; }
  .jqte_editor { min-height: 220px; }
  .template-actions .btn { margin-right: 5px; }
  .tpl-vars-wrap { max-height: 220px; overflow: auto; border: 1px solid #e5e5e5; padding: 10px; border-radius: 4px; background: #fafafa; }
  .tpl-preview-wrap { border: 1px solid #e5e5e5; padding: 12px; border-radius: 4px; background: #fff; }
  .tpl-preview-subject { font-weight: 600; margin-bottom: 10px; }
  .email-tags-container {
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
.email-tag.invalid {
    background: #f8d7da;
    color: #721c24;
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
</style>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
	  <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <div>
            <h4 class="tw-m-0 tw-text-white">Email Templates</h4>
          </div>
          <div class="tw-flex tw-items-center tw-gap-2">
            <button type="button" class="btn btn-primary" id="addTemplateBtn">
            <i class="fa fa-plus"></i> Add Template
          </button>
			
            <a href="<?php echo admin_url('webmail/compose');?>" class="btn btn-primary">
              <i class="fa-regular fa-paper-plane tw-mr-1"></i> New Email
            </a>
          </div>
        </div>
		
        

        <div class="panel_s">
          <div class="panel-body panel-table-fullx">
            <div class="table-responsive">
              <table class="table dt-table" data-order-col="1" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Subject</th>
                    <th>Created</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach (($templates ?? []) as $t) { ?>
                    <tr>
                      <td><?php echo html_escape($t['subject'] ?? ''); ?></td>
                      <td><?php echo !empty($t['created_at']) ? _dt($t['created_at']) : '-'; ?></td>
                      <td class="template-actions">
                        <button
                          type="button"
                          class="btn btn-info btn-xs edit-template"
                          data-id="<?php echo (int) $t['id']; ?>"
                          data-subject="<?php echo html_escape($t['subject'] ?? ''); ?>"
                          data-body="<?php echo htmlspecialchars($t['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        >
                          <i class="fa fa-pencil"></i>
                        </button>
                        <a href="<?php echo admin_url('email_template/delete/' . (int) $t['id']); ?>" class="btn btn-danger btn-xs _delete">
                          <i class="fa fa-trash"></i>
                        </a>
                        <button
                          type="button"
                          class="btn btn-success btn-xs send-template"
                          title="Send Template Email"
                          data-id="<?php echo (int) $t['id']; ?>"
                          data-subject="<?php echo html_escape($t['subject'] ?? ''); ?>"
                          data-body="<?php echo htmlspecialchars($t['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        >
                          <i class="fa fa-paper-plane"></i>
                        </button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Add/Edit Template Modal -->
<div class="modal fade" id="templateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('email_template/save'), ['id' => 'templateForm', 'novalidate' => 'novalidate']); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="templateModalTitle">Add Template</h4>
        </div>
        <div class="modal-body">
		<div class="alert alert-info">
    <strong>Instruction:</strong> 
<p>Use dynamic fields like {{Name}}, {{CompanyName}} in Subject and Email Body. </p>
<p>These will be replaced automatically when sending email</p>
<div class="btn btn-primary btn-sm tw-my-2 toggleBtn" data-id="toggleDiv">View More</div> 

<div id="toggleDiv" style="display:none;">
    <div style="background:#f4f6f9; padding:12px; border-radius:6px; font-size:13px;">

<b>Dynamic Fields Usage:</b><br><br>

You can use dynamic fields in the <b>Subject</b> and <b>Email Body</b> using <code>{{ }}</code>.

<br><br>

<b>Examples:</b><br>
<code>{{Name}}</code> - Name<br>
<code>{{CompanyName}}</code> - Company Name<br>
<code>{{Email}}</code> - Email Address

<br><br>

<b>Sample:</b><br>
Subject: <code>Welcome {{Name}} to {{CompanyName}}</code><br><br>

Body:<br>
<code>
Dear {{Name}},<br>
Welcome to {{CompanyName}}.
</code>

<br><br>

<b>Note:</b><br>

Field names must match exactly<br>

Do not use spaces inside brackets ( <i class="fa-solid fa-circle-xmark text-danger"></i> {{ Name }})<br>

Empty values will appear blank in email

</div>
</div>
</div>

		
          <input type="hidden" name="id" id="template_id" value="0">

          <div class="form-group">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" id="template_subject" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="body" id="template_body" class="form-control" rows="6"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Preview & Send Modal -->
<div class="modal fade" id="templatePreviewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('email_template/send'), ['id' => 'templateSendForm']); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">Preview Email</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="template_id" id="send_template_id" value="0">
          <input type="hidden" name="final_subject" id="send_final_subject" value="">
          <input type="hidden" name="final_body" id="send_final_body" value="">

          

          <div class="row">
            <div class="col-md-4">
              <label>Template Variables</label>
              <div class="tpl-vars-wrap" id="tplVarsContainer">
                <div class="text-muted">No variables found.</div>
              </div>
              <small class="text-muted">Use variables like <code>{{client_name}}</code> in subject/description.</small>
            </div>
            <div class="col-md-8">
			<div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>To Email <span class="text-danger">*</span></label>
                <div class="email-input-wrapper">
                  <div class="email-tags-container" id="tplToTagsContainer">
                    <input type="text" class="email-input-field" id="tplToInputField" placeholder="Type email and press Enter" autocomplete="off">
                  </div>
                  <div class="email-suggestions" id="tplToSuggestions"></div>
                </div>
                <input type="hidden" name="to_email" id="send_to_email">
              </div>
              <span class="pull-right">
                <a class="tw-px-0 toggleBtn" data-id="tplToggleCc" title="Add Cc">Cc</a>
                <a class="tw-px-2 toggleBtn" data-id="tplToggleBcc" title="Add Bcc">Bcc</a>
              </span>
            </div>
            <div class="col-md-12" id="tplToggleCc" style="display:none;">
              <div class="form-group">
                <label>Cc Email</label>
                <div class="email-input-wrapper">
                  <div class="email-tags-container" id="tplCcTagsContainer">
                    <input type="text" class="email-input-field" id="tplCcInputField" placeholder="Type Cc email and press Enter" autocomplete="off">
                  </div>
                  <div class="email-suggestions" id="tplCcSuggestions"></div>
                </div>
                <input type="hidden" name="cc_email" id="send_cc_email">
              </div>
            </div>
            <div class="col-md-12" id="tplToggleBcc" style="display:none;">
              <div class="form-group">
                <label>Bcc Email</label>
                <div class="email-input-wrapper">
                  <div class="email-tags-container" id="tplBccTagsContainer">
                    <input type="text" class="email-input-field" id="tplBccInputField" placeholder="Type Bcc email and press Enter" autocomplete="off">
                  </div>
                  <div class="email-suggestions" id="tplBccSuggestions"></div>
                </div>
                <input type="hidden" name="bcc_email" id="send_bcc_email">
              </div>
            </div>
          </div>
              <label>Preview</label>
              <div class="tpl-preview-wrap">
                <div class="tpl-preview-subject" id="tplPreviewSubject"></div>
                <div id="tplPreviewBody"></div>
              </div>
              <div class="alert alert-warning mtop10 hide" id="tplPreviewWarning"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="submit" class="btn btn-primary" id="sendTemplateBtn">Send</button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<link
  rel="stylesheet"
  href="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.css"
/>
<script src="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.js"></script>

<script>
const editor = Jodit.make('#template_body');

</script>
<script>
$(function() {
  //$('#template_body').jqte();

  var currentTpl = { id: 0, subject: '', body: '' };
  var currentVars = [];

  function extractVars(text) {
    var vars = [];
    if (!text) return vars;
    var re = /\{\{\s*([^}]+?)\s*\}\}/g;
    var m;
    while ((m = re.exec(text)) !== null) {
      var key = $.trim(m[1] || '');
      if (key && vars.indexOf(key) === -1) vars.push(key);
    }
    return vars;
  }

  function escapeHtml(str) {
    return $('<div/>').text(str || '').html();
  }

  function applyVarsToText(text, values) {
    if (!text) return '';
    return text.replace(/\{\{\s*([^}]+?)\s*\}\}/g, function(_, key) {
      key = $.trim(key || '');
      var v = values[key];
      return (v !== undefined && v !== null) ? v : '';
    });
  }

  function buildVarsUi(vars) {
    var $c = $('#tplVarsContainer');
    $c.empty();
    if (!vars.length) {
      $c.append('<div class="text-muted">No variables found.</div>');
      return;
    }
    vars.forEach(function(v) {
      var safe = escapeHtml(v);
      $c.append(
        '<div class="form-group" style="margin-bottom:8px;">' +
          '<label style="font-size:12px;margin-bottom:4px;">{{' + safe + '}}</label>' +
          '<input type="text" class="form-control input-sm tpl-var-input" data-var="' + safe + '" placeholder="Enter value">' +
        '</div>'
      );
    });
  }

  function getVarValues() {
    var values = {};
    $('.tpl-var-input').each(function() {
      var k = $(this).data('var');
      values[k] = $(this).val();
    });
    return values;
  }

  // --- Email tag + autosuggest (reuse logic from webmail compose) ---
  var searchEmail = '<?php echo isset($_SESSION["webmail"]["mailer_email"]) ? addslashes($_SESSION["webmail"]["mailer_email"]) : ""; ?>';

  function createEmailTagInput(options) {
    var emailTags = [];
    var $container = $(options.container);
    var $inputField = $(options.inputField);
    var $hiddenInput = $(options.hiddenInput);
    var $suggestions = $(options.suggestions);
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

    function createTag(email) {
      email = email.trim();
      if (!email) return;
      if (emailTags.indexOf(email) !== -1) {
        return;
      }
      emailTags.push(email);

      var isValid = isValidEmail(email);
      var $tag = $('<span class="email-tag' + (isValid ? '' : ' invalid') + '"></span>');
      $tag.text(email);
      var $remove = $('<span class="remove-tag">&times;</span>');
      $remove.on('click', function() {
        var idx = emailTags.indexOf(email);
        if (idx > -1) {
          emailTags.splice(idx, 1);
        }
        $tag.remove();
        updateHiddenInput();
      });
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

      if (emails.length === 0) {
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
        $suggestions
          .find('.email-suggestion-item[data-index="' + activeSuggestionIndex + '"]')
          .addClass('active');
      }
    }

    function searchEmails(term) {
      if (term.length < 2 || !searchEmail) {
        hideSuggestions();
        return;
      }

      $.ajax({
        url: admin_url + 'webmail/search_emails',
        type: 'GET',
        data: { term: term, email: searchEmail },
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
        },
      });
    }

    $inputField.on('keydown', function(e) {
      var val = $(this).val();

      if (e.key === 'ArrowDown') {
        e.preventDefault();
        if (currentSuggestions.length > 0) {
          activeSuggestionIndex = Math.min(
            activeSuggestionIndex + 1,
            currentSuggestions.length - 1
          );
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
        var parts = val.split(/[,;]+/);
        parts.forEach(function(part) {
          if (part.trim()) {
            createTag(part.trim());
          }
        });
        $(this).val('');
        hideSuggestions();
        return;
      }

      if (searchTimeout) {
        clearTimeout(searchTimeout);
      }

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
      var emails = pasteData.split(/[,;\s\n]+/);
      emails.forEach(function(email) {
        if (email.trim()) {
          createTag(email.trim());
        }
      });
    });

    return {
      createTag: createTag,
      getTags: function() {
        return emailTags;
      },
      updateHiddenInput: updateHiddenInput,
    };
  }

  var tplToInput = createEmailTagInput({
    container: '#tplToTagsContainer',
    inputField: '#tplToInputField',
    hiddenInput: '#send_to_email',
    suggestions: '#tplToSuggestions',
  });

  var tplCcInput = createEmailTagInput({
    container: '#tplCcTagsContainer',
    inputField: '#tplCcInputField',
    hiddenInput: '#send_cc_email',
    suggestions: '#tplCcSuggestions',
  });

  var tplBccInput = createEmailTagInput({
    container: '#tplBccTagsContainer',
    inputField: '#tplBccInputField',
    hiddenInput: '#send_bcc_email',
    suggestions: '#tplBccSuggestions',
  });

  function validateSend() {
    var warn = [];
    // ensure hidden inputs updated from tag components
    tplToInput.updateHiddenInput();
    tplCcInput.updateHiddenInput();
    tplBccInput.updateHiddenInput();

    //var toEmail = $.trim($('#send_to_email').val());
    //if (!toEmail) warn.push('To Email is required.');

    // All vars must be filled
    var missing = [];
    $('.tpl-var-input').each(function() {
      var k = $(this).data('var');
      if (!$.trim($(this).val())) missing.push('{{' + k + '}}');
    });
    if (missing.length) warn.push('Please fill variables: ' + missing.join(', '));

    var values = getVarValues();
    var finalSubject = applyVarsToText(currentTpl.subject || '', values);
    var finalBody = applyVarsToText(currentTpl.body || '', values);

    // If any placeholders remain, block
    if (/\{\{\s*[^}]+\s*\}\}/.test(finalSubject + ' ' + finalBody)) {
      warn.push('Template still contains unfilled variables.');
    }

    $('#send_final_subject').val(finalSubject);
    $('#send_final_body').val(finalBody);

    if (warn.length) {
      $('#tplPreviewWarning').removeClass('hide').text(warn.join(' '));
      $('#sendTemplateBtn').prop('disabled', true);
      return false;
    }
    $('#tplPreviewWarning').addClass('hide').text('');
    $('#sendTemplateBtn').prop('disabled', false);
    return true;
  }

  function renderPreview() {
    var values = getVarValues();
    var finalSubject = applyVarsToText(currentTpl.subject || '', values);
    var finalBody = applyVarsToText(currentTpl.body || '', values);

    $('#tplPreviewSubject').html(escapeHtml(finalSubject));
    // body is HTML from jqte, keep HTML but after replacements
    $('#tplPreviewBody').html(finalBody);
    validateSend();
  }

  function openAddModal() {
    $('#templateModalTitle').text('Add Template');
    $('#template_id').val(0);
    $('#template_subject').val('');
    $('#template_body').val('');
    $('#templateModal').modal('show');
  }

  function openEditModal($btn) {
    $('#templateModalTitle').text('Edit Template');
    $('#template_id').val($btn.data('id') || 0);
    $('#template_subject').val($btn.data('subject') || '');
    var body = $btn.data('body') || '';
    //$('#template_body').val(body);
	editor.value = body;
    $('#templateModal').modal('show');
  }

  $('#addTemplateBtn').on('click', function() {
    openAddModal();
  });

  $('body').on('click', '.edit-template', function() {
    openEditModal($(this));
  });

  $('body').on('click', '.send-template', function() {
    var $btn = $(this);
    currentTpl.id = $btn.data('id') || 0;
    currentTpl.subject = $btn.data('subject') || '';
    currentTpl.body = $btn.data('body') || '';

    $('#send_template_id').val(currentTpl.id);
    $('#send_to_email').val('');
    $('#send_cc_email').val('');
    $('#send_bcc_email').val('');
    // clear any existing tags
    $('#tplToTagsContainer .email-tag, #tplCcTagsContainer .email-tag, #tplBccTagsContainer .email-tag').remove();

    currentVars = extractVars(currentTpl.subject + ' ' + currentTpl.body);
    buildVarsUi(currentVars);
    renderPreview();

    $('#templatePreviewModal').modal('show');
  });

  $('body').on('input', '.tpl-var-input, #send_to_email, #send_cc_email', function() {
    renderPreview();
  });

  $('#templateSendForm').on('submit', function(e) {
    if (!validateSend()) {
      e.preventDefault();
      return false;
    }
    return true;
  });

  function _tplIsEmptyHtml(html) {
    var t = String(html || '')
      .replace(/<[^>]*>/g, '')
      .replace(/&nbsp;/g, ' ')
      .replace(/\s+/g, ' ')
      .trim();
    return t.length === 0;
  }

  $('#templateForm').on('submit', function(e) {
    var subject = $.trim($('#template_subject').val());
    var bodyHtml = $('#template_body').val();
    $('#template_body').val(bodyHtml);
    if (!subject || _tplIsEmptyHtml(bodyHtml)) {
      e.preventDefault();
      alert('Subject and Description are required');
      return false;
    }
    return true;
  });
});
// To Email Validation
$('#sendTemplateBtn').on('click', function (e) {
    var email = $('#send_to_email').val().trim(); // change #email to your input ID

    if (email === '') {
        alert('Email is required.');
        $('#tplToInputField').focus();
        return false;
    }

});

</script>

</body>
</html>

