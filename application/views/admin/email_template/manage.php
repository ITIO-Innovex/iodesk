<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style>
  .jqte { margin: 0; }
  .jqte_editor { min-height: 220px; }
  .template-actions .btn { margin-right: 5px; }
  .tpl-vars-wrap { max-height: 220px; overflow: auto; border: 1px solid #e5e5e5; padding: 10px; border-radius: 4px; background: #fafafa; }
  .tpl-preview-wrap { border: 1px solid #e5e5e5; padding: 12px; border-radius: 4px; background: #fff; }
  .tpl-preview-subject { font-weight: 600; margin-bottom: 10px; }
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
            <div class="col-md-6">
              <div class="form-group">
                <label>To Email <span class="text-danger">*</span></label>
                <input type="text" name="to_email" id="send_to_email" class="form-control" placeholder="example@domain.com, example2@domain.com" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>CC Email</label>
                <input type="text" name="cc_email" id="send_cc_email" class="form-control" placeholder="example@domain.com, example2@domain.com">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Template Variables</label>
              <div class="tpl-vars-wrap" id="tplVarsContainer">
                <div class="text-muted">No variables found.</div>
              </div>
              <small class="text-muted">Use variables like <code>{{client_name}}</code> in subject/description.</small>
            </div>
            <div class="col-md-8">
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
          <button type="submit" class="btn btn-primary" id="sendTemplateBtn"><?php echo _l('submit'); ?></button>
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

  function validateSend() {
    var warn = [];
    var toEmail = $.trim($('#send_to_email').val());
    if (!toEmail) warn.push('To Email is required.');

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
</script>

</body>
</html>

