<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($webmaillist);?>
<style>
@media (min-width: 768px) {
    .modal-dialogx {
        width: unset !important;
    }
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
        <div class="row">
		<?php if(!empty($_SESSION['mailersdropdowns'])){ ?>
		
		    <div class="col-md-2 picker">

<div>			
<span class="dropdown">
  <button class="btn btn-default buttons-collection btn-default-dt-options dropdown-toggle" type="button" data-toggle="dropdown" style="width: 180px !important;"><span title="<?=$_SESSION['webmail']['mailer_email'];?>"><?=substr($_SESSION['webmail']['mailer_email'],0,18);?></span>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<?php  foreach ($_SESSION['mailersdropdowns'] as $item) { ?>
	<li><a href="?mt=<?=$item['id'];?>"><?=$item['mailer_email'];?></a></li>
	<?php  } ?>
  </ul>
</span>
</div>
<div>
<a href="<?php echo site_url('admin/webmail/compose'); ?>" class="btn btn-primary mtop10" style="width: 180px !important;">
        <i class="fa-regular fa-paper-plane tw-mr-1"></i>
        <?php echo _l('New Mail'); ?>
</a>
</div>
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked mtop10" id="theme_styling_areas">
				
				<?php  foreach ($_SESSION['folderlist'] as $item => $val) { ?>
                    <li role="presentation" class="menu-item-leads">
                        <a href="inbox?fd=<?php echo $val['folder'];?>"><?php echo $val['folder'];?></a>
                    </li>
					
				  <?php  } ?>  
                </ul>
            </div>
            <div class="col-md-10">
                <div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">Sent New Email</h4>
             </div>
<div class="panel_s">
<div class="panel-body panel-table-full mail-bg">

<form action="<?=  admin_url('webmail/reply') ?>" method="post" enctype="multipart/form-data" id="compose-form-data">
	<!-- CSRF Token -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
               value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" name="redirect" value="inbox.php">
      <div class="mb-3">
        <label for="recipientEmail" class="form-label mtop10">To</label>
        <div class="email-input-wrapper">
            <div class="email-tags-container" id="toEmailTagsContainer">
                <input type="text" class="email-input-field" id="toEmailInputField" placeholder="Type email and press Enter" autocomplete="off">
            </div>
            <div class="email-suggestions" id="toEmailSuggestions"></div>
        </div>
        <input type="hidden" id="recipientEmailIT" name="recipientEmail" value="<?php if(isset($_GET['id']) && !empty($_GET['id'])){ echo htmlspecialchars($_GET['id']); } ?>">
      </div>
	  <div class="mb-3">
        <label for="recipientCC" class="form-label mtop10">CC</label>
        <div class="email-input-wrapper">
            <div class="email-tags-container" id="ccEmailTagsContainer">
                <input type="text" class="email-input-field" id="ccEmailInputField" placeholder="Type CC email and press Enter" autocomplete="off">
            </div>
            <div class="email-suggestions" id="ccEmailSuggestions"></div>
        </div>
        <input type="hidden" id="recipientCCIT" name="recipientCC" value="">
      </div>
	  <?php /*?><div class="mb-3">
        <label for="recipientBCCEmail" class="form-label mtop10">BCC</label>
        <input type="text" class="form-control" id="recipientBCCIT" name="recipientBCC" value="" placeholder="Enter BCC email" >
      </div><?php */?>
      <div class="mb-3">
	  <label for="emailSubject" class="form-label mtop10">Subject</label>
	  <input type="text" class="form-control" id="emailSubjectIT" name="emailSubject" value="" placeholder="Enter email subject"  required>
	  
	  </div>
	  
      <div class="mb-3">
        
	   <textarea  name="emailBody" id="emailBody" class="form-control editor" required></textarea>
	   <div class="checkbox checkbox-primary">
<input type="checkbox" id="toggleSignature" name="toggleSignature" value="1">
<label for="SignatureX">Add Signature</label>
</div>
         <div class="mb-3">
	  <div class="tw-text-right">
	  <a name="send" class="ailoader" onclick="get_content();return false;"><img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" /></a>
	 
	  </div>

	  </div>                      
      </div>
      <div class="mb-3">
        <label for="recipientEmail" class="form-label text-info">Attach Files: You can select multiple files by holding the Shift key and clicking on the files while browsing.</label>
        <div class="tw-flex tw-items-center tw-gap-2">
          <input type="file" id="emailAttachments" name="attachments[]" class="form-control" multiple>
          <button type="button" id="addMoreAttachments" class="btn btn-default">Add another</button>
        </div>
        <small id="attachmentStatus" class="text-success hide">File is attached.</small>
        <div id="attachmentList" class="mtop10"></div>
      </div>
      <button type="submit" name="send" class="btn btn-primary mtop20 submitemail"><i class="fa-solid fa-envelope-circle-check"></i> Send Email</button>
	  <button type="button" id="openScheduleModal" class="btn btn-primary mtop20"><i class="fa-regular fa-clock"></i> Send Later</button>
    </form>

</div>
</div>
            </div>
			
			
		<?php }else{?>
		<div class="alert alert-info text-center">
        <?php echo _l('No Webmail Setup Entries'); ?>
        </div>
		<?php } ?>
        </div>
    </div>
</div>

<div class="modal fade" id="emailScheduleModal" tabindex="-1" role="dialog" aria-labelledby="emailScheduleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emailScheduleModalLabel">Email Scheduling</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="scheduleAtInput">Scheduled At</label>
                    <input type="datetime-local" name="schedule_at" id="scheduleAtInput" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="scheduleSendBtn" class="btn btn-primary">Schedule and Send</button>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>

	$('.editor').jqte();

</script>
<script>


  
 // Toggle AI BOX	
 function toggleCollapse() {
 const div = document.getElementById('collapseDiv');
    div.classList.toggle('hidden');
  }
  
  // Toggle AI BOX	
function validateComposeForm() {
	// Ensure hidden inputs are updated with current tags
	if (typeof window.updateWebmailEmailInputs === 'function') {
		window.updateWebmailEmailInputs();
	}
	
	var recipientEmailIT=$.trim($('#recipientEmailIT').val());
	var emailSubjectIT=$.trim($('#emailSubjectIT').val());
	var emailBody=$.trim($('#emailBody').val());
	
	// Attachment keyword check
	const body = emailBody.toLowerCase();
	const filesCount = (window.emailFilesStore && window.emailFilesStore.length) ? window.emailFilesStore.length : document.getElementById('emailAttachments').files.length;
	const attachmentKeywords = ['attach', 'attached', 'attachment', 'enclosed', 'file', 'document'];
	const keywordFound = attachmentKeywords.some(word => body.includes(word));
    //for Attachment Validation //
	
	
	if(recipientEmailIT===''){
		alert('Please enter to email');
		$('#toEmailInputField').focus();
		return false;
	}else if(emailSubjectIT===''){
		alert('Please enter email subject');
		$('#emailSubjectIT').focus();
		return false;
	}else if(emailBody=='' || emailBody.length < 6 ){
		alert('Please check Email body before submit / Min content length 5 character');
		$('.jqte_editor').focus();
		return false;
	 }else if (keywordFound && filesCount === 0) {

		const confirmSend = confirm(
			"Did you mean to attach files?\n\n" +
			"You mentioned attachments in your message, but no files are attached.\n\n" +
			"Click OK to send anyway or Cancel to attach files."
		);

		if (!confirmSend) {
			// User clicked Cancel - STOP submit
			return false;
		}
	
	}
	
	return true;
}

// Attachments list handling
window.emailFilesStore = window.emailFilesStore || [];
var maxTotalSize = 25 * 1024 * 1024; // 25 MB in bytes
var maxSingleFileSize = 25 * 1024 * 1024; // 25 MB per file

function getTotalAttachmentSize() {
	var total = 0;
	window.emailFilesStore.forEach(function(file) {
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

function renderAttachmentList() {
	var list = $('#attachmentList');
	list.empty();
	if (window.emailFilesStore.length === 0) {
		$('#attachmentStatus').addClass('hide').text('');
		return;
	}
	$('#attachmentStatus').removeClass('hide').text('File is attached.');
	window.emailFilesStore.forEach(function(file, idx){
		var row = $('<div class="tw-flex tw-items-center tw-justify-between tw-border tw-border-solid tw-border-neutral-200 tw-rounded tw-px-3 tw-py-2 tw-mb-2"></div>');
		var name = $('<span class="tw-text-sm tw-text-neutral-700"></span>').text(file.name + ' (' + formatFileSize(file.size) + ')');
		var removeBtn = $('<button type="button" class="btn btn-danger btn-xs">Delete</button>');
		removeBtn.attr('data-index', idx);
		row.append(name).append(removeBtn);
		list.append(row);
	});
	// Show total size
	var totalSize = getTotalAttachmentSize();
	var sizeClass = totalSize > maxTotalSize ? 'text-danger' : 'text-success';
	var totalRow = $('<div class="tw-font-bold tw-mt-2"><span class="' + sizeClass + '">Total Size: ' + formatFileSize(totalSize) + ' / 25 MB</span></div>');
	list.append(totalRow);
}

function rebuildAttachmentInput() {
	var input = document.getElementById('emailAttachments');
	var dt = new DataTransfer();
	window.emailFilesStore.forEach(function(file){
		dt.items.add(file);
	});
	input.files = dt.files;
}

function addFilesToStore(files) {
	var rejectedFiles = [];
	for (var i = 0; i < files.length; i++) {
		var f = files[i];
		
		// Check individual file size
		if (f.size > maxSingleFileSize) {
			rejectedFiles.push(f.name + ' (' + formatFileSize(f.size) + ') - exceeds 25 MB limit');
			continue;
		}
		
		// Check if adding this file would exceed total limit
		var currentTotal = getTotalAttachmentSize();
		if (currentTotal + f.size > maxTotalSize) {
			rejectedFiles.push(f.name + ' - would exceed 25 MB total limit');
			continue;
		}
		
		var exists = window.emailFilesStore.some(function(existing){
			return existing.name === f.name && existing.size === f.size && existing.lastModified === f.lastModified;
		});
		if (!exists) {
			window.emailFilesStore.push(f);
		}
	}
	
	if (rejectedFiles.length > 0) {
		alert('The following file(s) were not added because they exceed the 25 MB limit:\n\n' + rejectedFiles.join('\n'));
	}
	
	rebuildAttachmentInput();
	renderAttachmentList();
}

$('#addMoreAttachments').on('click', function(){
	$('#emailAttachments').click();
});

$('#attachmentList').on('click', 'button[data-index]', function(){
	var idx = parseInt($(this).attr('data-index'), 10);
	if (!isNaN(idx)) {
		window.emailFilesStore.splice(idx, 1);
		rebuildAttachmentInput();
		renderAttachmentList();
	}
});

$('.submitemail').click(function(){ 
	if(!validateComposeForm()){
		return false;
	}
	// Check total attachment size before submit
	var totalSize = getTotalAttachmentSize();
	if (totalSize > maxTotalSize) {
		alert('Total attachment size (' + formatFileSize(totalSize) + ') exceeds the maximum limit of 25 MB. Please remove some attachments.');
		return false;
	}
	$(".submitemail").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
});

$('#openScheduleModal').on('click', function(){
	if(!validateComposeForm()){
		return;
	}
	$('#emailScheduleModal').modal('show');
});

$('#scheduleSendBtn').on('click', function(){
	if(!validateComposeForm()){
		return;
	}
	// Check total attachment size before schedule
	var totalSize = getTotalAttachmentSize();
	if (totalSize > maxTotalSize) {
		alert('Total attachment size (' + formatFileSize(totalSize) + ') exceeds the maximum limit of 25 MB. Please remove some attachments.');
		return;
	}
	var scheduleAt = $.trim($('#scheduleAtInput').val());
	if(scheduleAt === ''){
		alert('Please select scheduled date and time');
		$('#scheduleAtInput').focus();
		return;
	}

	var form = document.getElementById('compose-form-data');
	var formData = new FormData(form);
	formData.append('schedule_at', scheduleAt);

	$('#scheduleSendBtn').prop('disabled', true).html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");

	$.ajax({
		url: admin_url + 'webmail/schedule_send',
		type: 'POST',
		data: formData,
		contentType: false,
		processData: false,
		success: function(response){
			var res;
			try {
				res = JSON.parse(response);
			} catch (e) {
				res = {success:false, message:'Unexpected response'};
			}
			if(res.success){
				alert(res.message || 'Email scheduled successfully');
				window.location.href = admin_url + 'webmail/inbox';
			}else{
				alert(res.message || 'Failed to schedule email');
				$('#scheduleSendBtn').prop('disabled', false).html('Schedule and Send');
			}
		},
		error: function(){
			alert('Failed to schedule email');
			$('#scheduleSendBtn').prop('disabled', false).html('Schedule and Send');
		}
	});
});

$('#emailAttachments').on('change', function() {
  var files = $(this).get(0).files;
  if (files && files.length > 0) {
    addFilesToStore(files);
  } else {
    renderAttachmentList();
  }
});


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

$(document).ready(function() {
  setTimeout(function() {
    $('#lead-modal').removeAttr('id');
    console.log('ID removed from modal');
  }, 5000); // 5000 ms = 5 seconds
})

</script>
  
<script>
  //For Add /  Remove Signature
  //toggleSignature function define on asset/js/custom.js
  //need add css editor in jq editor textarea
  const signature = `<br><br><br><br><?php echo $email_signature;?>`;
</script>
<script>
window.addEventListener('beforeunload', function (e) {
  if (window.tinymce && tinymce.activeEditor && tinymce.activeEditor.isDirty()) {
    return;
  }
  e.stopImmediatePropagation();
}, true);
</script>

<script>
(function() {
    var searchEmail = '<?php echo isset($_SESSION['webmail']['mailer_email']) ? addslashes($_SESSION['webmail']['mailer_email']) : ''; ?>';
    var initialToEmail = '<?php echo isset($_GET['id']) && !empty($_GET['id']) ? addslashes($_GET['id']) : ''; ?>';
    
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
            getTags: function() { return emailTags; },
            updateHiddenInput: updateHiddenInput
        };
    }
    
    var toEmailInput = createEmailTagInput({
        container: '#toEmailTagsContainer',
        inputField: '#toEmailInputField',
        hiddenInput: '#recipientEmailIT',
        suggestions: '#toEmailSuggestions'
    });
    
    var ccEmailInput = createEmailTagInput({
        container: '#ccEmailTagsContainer',
        inputField: '#ccEmailInputField',
        hiddenInput: '#recipientCCIT',
        suggestions: '#ccEmailSuggestions'
    });
    
    // Expose globally for form submission
    window.webmailToEmailInput = toEmailInput;
    window.webmailCcEmailInput = ccEmailInput;
    
    window.updateWebmailEmailInputs = function() {
        toEmailInput.updateHiddenInput();
        ccEmailInput.updateHiddenInput();
    };
    
    if (initialToEmail) {
        var initialEmails = initialToEmail.split(/[,;]+/);
        initialEmails.forEach(function(email) {
            if (email.trim()) {
                toEmailInput.createTag(email.trim());
            }
        });
    }
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.email-input-wrapper').length) {
            $('#toEmailSuggestions, #ccEmailSuggestions').hide().empty();
        }
    });
})();
</script>

</body>

</html>