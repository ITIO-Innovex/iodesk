<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($webmaillist);?>
<style>
@media (min-width: 768px) {
    .modal-dialogx {
        width: unset !important;
    }
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
        <input type="text" class="form-control" id="recipientEmailIT" name="recipientEmail" value="<?php if(isset($_GET['id']) && !empty($_GET['id'])){ echo $_GET['id'] ; } ?>" placeholder="Enter recipient email" required>
      </div>
	  <div class="mb-3">
        <label for="recipientEmail" class="form-label mtop10">CC</label>
        <input type="text" class="form-control" id="recipientCCIT" name="recipientCC" value="" placeholder="Enter CC email -  Add multiple with comma seperated " >
      </div>
	  <div class="mb-3">
        <label for="recipientBCCEmail" class="form-label mtop10">BCC</label>
        <input type="text" class="form-control" id="recipientBCCIT" name="recipientBCC" value="" placeholder="Enter BCC email" >
      </div>
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
		$('#recipientEmailIT').focus();
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
		var name = $('<span class="tw-text-sm tw-text-neutral-700"></span>').text(file.name);
		var removeBtn = $('<button type="button" class="btn btn-danger btn-xs">Delete</button>');
		removeBtn.attr('data-index', idx);
		row.append(name).append(removeBtn);
		list.append(row);
	});
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
	for (var i = 0; i < files.length; i++) {
		var f = files[i];
		var exists = window.emailFilesStore.some(function(existing){
			return existing.name === f.name && existing.size === f.size && existing.lastModified === f.lastModified;
		});
		if (!exists) {
			window.emailFilesStore.push(f);
		}
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


</body>

</html>