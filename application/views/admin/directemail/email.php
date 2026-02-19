<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card{
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
.card-title{
    text-align:center;
}
.jqte_tool.jqte_tool_1 .jqte_tool_label {
    height: 20px !important;
}
.jqte {
    margin: 20px 0 !important;
}
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
    <?php
        $smtpConfig = $direct_smtp_config ?? [];
        $smtpConfigured = !empty($smtpConfig);
    ?>
	<?php if (is_admin()) { ?>
    <?php if ($smtpConfigured) { ?>
    <?php /*?><div class="alert alert-success">
        <div class="tw-font-bold tw-my-2">
            <i class="fa-solid fa-circle-check"></i> Direct SMTP configured
            <span style="float:right"><a href="javascript:void(0);" id="smtp_setup" class="btn btn-success btn-sm ms-2">Update SMTP</a></span>
        </div>
        <div class="tw-text-sm">
            <div><strong>SMTP Host:</strong> <?php echo e($smtpConfig['smtp_host'] ?? '-'); ?></div>
            <div><strong>SMTP Port:</strong> <?php echo e($smtpConfig['smtp_port'] ?? '-'); ?></div>
            <div><strong>SMTP Email:</strong> <?php echo e($smtpConfig['smtp_email'] ?? '-'); ?></div>
            <div><strong>SMTP Username:</strong> <?php echo e($smtpConfig['smtp_username'] ?? '-'); ?></div>
            <div><strong>SMTP Password:</strong> <?php echo !empty($smtpConfig['smtp_password']) ? '********' : '-'; ?></div>
        </div>
    </div><?php */?>
    <?php } else { ?>
	<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Before sending a direct email, please add your SMTP details <span style="float:right"><a href="javascript:void(0);" id="smtp_setup" class="btn btn-warning btn-sm ms-2">Add SMTP details</a></span></div>
    </div>
    <?php } ?>
	<?php } ?>
	
	<?php if (!is_admin() && empty($smtpConfig)) { ?>
	<div class="alert alert-danger">
        <div class="tw-font-bold tw-my-2">
            <i class="fa-solid fa-circle-check"></i> SMTP settings are not configured. Please contact the administrator before sending an email.
            
        </div>
        <div class="tw-text-sm">
            <?php /*?><div><strong>SMTP Host:</strong> <?php echo e($smtpConfig['smtp_host'] ?? '-'); ?></div>
            <div><strong>SMTP Port:</strong> <?php echo e($smtpConfig['smtp_port'] ?? '-'); ?></div>
            <div><strong>SMTP Email:</strong> <?php echo e($smtpConfig['smtp_email'] ?? '-'); ?></div>
            <div><strong>SMTP Username:</strong> <?php echo e($smtpConfig['smtp_username'] ?? '-'); ?></div>
            <div><strong>SMTP Password:</strong> <?php echo !empty($smtpConfig['smtp_password']) ? '********' : '-'; ?></div><?php */?>
        </div>
    </div>
	<?php } ?>
	
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="col-md-12">
                                <h2 class="no-margin">
                                    <?php echo $title; ?><?php //echo get_staff_email();?>
                                    
                                </h2>
                                <hr class="hr-panel-heading" />
                            </div>
                    <div class="card-body">
                        <?php echo form_open(admin_url('direct_email/sendMail'), ['id' => 'directEmail', 'enctype' => 'multipart/form-data']); ?>
                            <div class="row" style="padding:20px">
                                <div class="col">
                                    <div class="form-group">
                                        <div class="label">
                                           <span class="text-dark">To</span>
                                        </div>
                                        <div class="email-input-wrapper">
                                            <div class="email-tags-container" id="emailTagsContainer">
                                                <input type="text" class="email-input-field" id="emailInputField" placeholder="Type email and press Enter or comma" autocomplete="off">
                                            </div>
                                            <div class="email-suggestions" id="emailSuggestions"></div>
                                        </div>
                                        <input type="hidden" id="email" name="email" value="">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <div class="label">
                                           <span class="text-dark">Subject</span>
                                        </div>
                                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Subject..." required>
                                    </div>
                                </div>
								

                                <div class="col">
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control editor"  placeholder="Message..."></textarea>
<div class="checkbox checkbox-primary">
<input type="checkbox" id="toggleSignature" name="toggleSignature" value="1">
<label for="SignatureX">Add Signature</label>
</div>
										
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <div class="label">
                                           <span class="text-dark">Attachments</span>
                                        </div>
                                        <input type="file" id="attachments" name="attachments[]" class="form-control" multiple>
                                        <div id="attachments-list" class="mt-2"></div>
                                    </div>
                                </div>
																<div class="col">
	  <div class="tw-text-right">
	  <a name="send" class="ailoader" onclick="get_content();return false;"><img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" /></a>
	 
	  </div>

	  </div>
								
                                <div class="col">
                                    <div class="form-group">
                                    <button type="submit" style="padding-right:30px" id="sendMail" class="btn btn-primary btn-lg">Submit</button>
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
<div class="modal fade" id="direct_smtp_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('direct_email/save_direct_smtp'), ['id' => 'direct-smtp-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Direct SMTP Details</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">SMTP Encryption</label>
          <div>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="ssl" checked> SSL
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="tls"> TLS
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="none"> No Encryption
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="smtp_host" class="control-label">SMTP Host</label>
          <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="smtp.example.com" required>
        </div>
        <div class="form-group">
          <label for="smtp_port" class="control-label">SMTP Port</label>
          <input type="number" class="form-control" id="smtp_port" name="smtp_port" placeholder="587" required>
        </div>
        <div class="form-group">
          <label for="smtp_email" class="control-label">SMTP Email</label>
          <input type="email" class="form-control" id="smtp_email" name="smtp_email" placeholder="no-reply@example.com" required>
        </div>
        <div class="form-group">
          <label for="smtp_username" class="control-label">SMTP Username</label>
          <input type="text" class="form-control" id="smtp_username" name="smtp_username" placeholder="SMTP Username">
        </div>
        <div class="form-group">
          <label for="smtp_password" class="control-label">SMTP Password</label>
          <input type="password" class="form-control" id="smtp_password" name="smtp_password" placeholder="SMTP Password">
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
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>
	$('.editor').jqte();
	 // Toggle AI BOX	
 function toggleCollapse() {
 const div = document.getElementById('collapseDiv');
    div.classList.toggle('hidden');
  }
  
  function get_content() { 


//let str = $('input[name="aicontent"]').val();
let str = $('.editor').val();
let aicontent = $.trim(str);


if((aicontent !="") && (aicontent.length >= 5)){
//alert(emailSubject);
$(".ailoader").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
     $.post(admin_url + 'ai_content_generator/generate_email_ai', {
            content_title: aicontent,
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response);
			
			if(response.alert_type=="success"){
			var str = response.message.toString();
            var formattedStr = str.replace(/\\n/g, "<br>");
            var formattedStr = formattedStr.replace(/\\/g, "");
            //alert(formattedStr);
			$('.editor').jqteVal(formattedStr);
			$('.editor').val(formattedStr);
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}else{
			alert("Not Generated");
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}
			
            
        });
}else{
alert("Enter Correct Email Body with min length 5");
}

   
}
</script>

<script>
  $(function() {
    var smtpConfig = <?php echo json_encode($direct_smtp_config ?? []); ?>;

    $('#smtp_setup').on('click', function(e) {
      e.preventDefault();
      var enc = (smtpConfig.smtp_encryption || 'ssl').toLowerCase();
      if (enc !== 'ssl' && enc !== 'tls' && enc !== 'none') {
        enc = 'ssl';
      }
      $('input[name="smtp_encryption"][value="' + enc + '"]').prop('checked', true);
      $('#smtp_host').val(smtpConfig.smtp_host || '');
      $('#smtp_port').val(smtpConfig.smtp_port || '');
      $('#smtp_email').val(smtpConfig.smtp_email || '');
      $('#smtp_username').val(smtpConfig.smtp_username || '');
      $('#smtp_password').val(smtpConfig.smtp_password || '');
      $('#direct_smtp_modal').modal('show');
    });

    $('#direct-smtp-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize()).done(function(resp) {
        var r = {};
        try { r = JSON.parse(resp); } catch (e) {}
        if (r.success) {
          alert_float('success', r.message || 'SMTP saved.');
          $('#direct_smtp_modal').modal('hide');
          location.reload();
        } else {
          alert_float('warning', r.message || 'Failed to save SMTP.');
        }
      }).fail(function() {
        alert_float('danger', 'Request failed.');
      });
    });
  });
</script>

<script>
$('#directEmail').on('submit', function(event){
    
    // Ensure hidden input is updated with current tags
    if (typeof window.updateDirectEmailHiddenInput === 'function') {
        window.updateDirectEmailHiddenInput();
    }

    var recipientEmailIT=$.trim($('#email').val());
	var emailSubjectIT=$.trim($('#subject').val());
	var emailBody=$.trim($('#message').val());
    
    if(recipientEmailIT==''){
			alert('Please enter to email');
			$('#emailInputField').focus();
			return false;
		}else if(emailSubjectIT==''){
		    alert('Please enter email subject');
			$('#subject').focus();
			return false;
		}else if(emailBody=='' || emailBody.length < 6 ){
		    alert('Please check Email body before submit / Min content length 5 character');
			$('.jqte_editor').focus();
			return false;
		}else{
		$("#sendMail").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
		}
		
    $("#sendMail").prop("disabled", true); // Disable submit button
    event.preventDefault();
    var formData = new FormData(this);
    var filesStore = window.directEmailFilesStore || [];
    formData.delete('attachments[]');
    filesStore.forEach(function(file) {
      formData.append('attachments[]', file);
    });
    $.ajax({
        url: $(this).attr('action'),
        method:'POST',
        data:formData,
        processData: false,
        contentType: false,
        success: function(response){
            var data = {};
            try { data = typeof response === 'string' ? JSON.parse(response) : response; } catch (e) {
                data = { success: false, message: response };
            }
            $("#sendMail").prop("disabled", false); // Enable submit button
            if(data.success){
			    $("#sendMail").html("Submit");
                // Success Message
                alert_float('success', data.message || "Mail sent succesfully!");
            }else{
			   $("#sendMail").html("Submit");
                // Failure Message
                alert_float('danger', data.message || "Failed to send mail. Please try again.");
            }
        },
        error: function (xhr, status, error){
            $("#sendMail").prop("disabled", false); // Enable submit button
            console.error('Error:',error);
            // Failure Message
            var msg = (xhr && xhr.responseText) ? xhr.responseText : "Failed to send mail. Please try again.";
            alert_float('danger', msg);
        }
    })    
});
<?php /*?>$(document).ready(function() {
  setTimeout(function() {
    $('#lead-modal').removeAttr('id');
	$('#_task_modal').removeAttr('id');
    console.log('ID removed from modal');
  }, 5000); // 5000 ms = 5 seconds
});<?php */?>


</script>
<script>
  (function() {
    var $input = $('#attachments');
    var $list = $('#attachments-list');
    if ($input.length === 0 || $list.length === 0) {
      return;
    }
    var filesStore = [];
    window.directEmailFilesStore = filesStore;
    var maxTotalSize = 25 * 1024 * 1024; // 25 MB in bytes
    var maxSingleFileSize = 25 * 1024 * 1024; // 25 MB per file
    
    function getTotalSize() {
      var total = 0;
      filesStore.forEach(function(file) {
        total += file.size;
      });
      return total;
    }
    
    function formatFileSize(bytes) {
      if (bytes >= 1024 * 1024) {
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
      }
      return Math.round(bytes / 1024) + ' KB';
    }
    
    function renderList() {
      $list.empty();
      if (filesStore.length === 0) {
        return;
      }
      var totalSize = getTotalSize();
      var $ul = $('<ul class="list-unstyled mb-0"></ul>');
      filesStore.forEach(function(file, index) {
        var $li = $('<li class="tw-my-2"></li>');
        var $name = $('<span></span>').text(file.name + ' (' + formatFileSize(file.size) + ')');
        var $btn = $('<button type="button" class="btn btn-xs btn-danger ml-2">Remove</button>');
        $btn.on('click', function() {
          filesStore.splice(index, 1);
          renderList();
        });
        $li.append($name).append($btn);
        $ul.append($li);
      });
      // Show total size
      var $totalLi = $('<li class="tw-my-2 tw-font-bold"></li>');
      var sizeClass = totalSize > maxTotalSize ? 'text-danger' : 'text-success';
      $totalLi.html('<span class="' + sizeClass + '">Total Size: ' + formatFileSize(totalSize) + ' / 25 MB</span>');
      $ul.append($totalLi);
      $list.append($ul);
    }
    
    $input.on('change', function() {
      var newFiles = Array.from($input[0].files);
      var rejectedFiles = [];
      
      newFiles.forEach(function(file) {
        // Check individual file size
        if (file.size > maxSingleFileSize) {
          rejectedFiles.push(file.name + ' (' + formatFileSize(file.size) + ')');
          return;
        }
        
        // Check if adding this file would exceed total limit
        var currentTotal = getTotalSize();
        if (currentTotal + file.size > maxTotalSize) {
          rejectedFiles.push(file.name + ' - would exceed 25 MB limit');
          return;
        }
        
        filesStore.push(file);
      });
      
      if (rejectedFiles.length > 0) {
        alert('The following file(s) were not added because they exceed the 25 MB limit:\n\n' + rejectedFiles.join('\n'));
      }
      
      renderList();
      $input.val('');
    });
    
    // Validate before form submission
    $('#directEmail').on('submit', function(e) {
      var totalSize = getTotalSize();
      if (totalSize > maxTotalSize) {
        alert('Total attachment size (' + formatFileSize(totalSize) + ') exceeds the maximum limit of 25 MB. Please remove some attachments.');
        e.preventDefault();
        return false;
      }
    });
  })();
</script>
<script>
  //For Add /  Remove Signature
  //toggleSignature function define on asset/js/custom.js
  //need add css editor in jq editor textarea
  const signature = `<br><br><br><br><?php echo $email_signature;?>`;
</script>
<?php $user_emails=get_staff_email() ?? ''; ?>
<script>
(function() {
    window.directEmailTags = [];
    var emailTags = window.directEmailTags;
    var $container = $('#emailTagsContainer');
    var $inputField = $('#emailInputField');
    var $hiddenInput = $('#email');
    var $suggestions = $('#emailSuggestions');
    var searchTimeout = null;
    var activeSuggestionIndex = -1;
    var currentSuggestions = [];
    var searchEmail = '<?php echo $user_emails; ?>';
    
    function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
    
    function updateHiddenInput() {
        $hiddenInput.val(emailTags.join(';'));
    }
    
    window.updateDirectEmailHiddenInput = updateHiddenInput;
    
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
            $suggestions.find('.email-suggestion-item[data-index="' + activeSuggestionIndex + '"]').addClass('active');
        }
    }
    
    function searchEmails(term) {
        if (term.length < 2 || !searchEmail) {
            hideSuggestions();
            return;
        }
        
        $.ajax({
            url: admin_url + 'direct_email/search_emails',
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
                var lastEmail = emailTags.pop();
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
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.email-input-wrapper').length) {
            hideSuggestions();
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
})();
</script>