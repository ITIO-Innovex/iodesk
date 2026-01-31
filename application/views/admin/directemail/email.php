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
                                    <?php echo $title; ?>
                                    
                                </h2>
                                <hr class="hr-panel-heading" />
                            </div>
                    <div class="card-body">
                        <form id="directEmail">
                            <div class="row" style="padding:20px">
                                <div class="col">
                                    <div class="form-group">
                                        <div class="label">
                                           <span class="text-dark">To</span>
                                        </div>
                                        <input type="text" id="email" name="email" class="form-control" placeholder="E-mails (example@email.com;example2@gmail.com...)" required>
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
                        </form>
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


    var recipientEmailIT=$.trim($('#email').val());
	var emailSubjectIT=$.trim($('#subject').val());
	var emailBody=$.trim($('#message').val());
        
		
		 if(recipientEmailIT==''){
			alert('Please enter to email');
			$('#recipientEmailIT').focus();
			return false;
		}else if(emailSubjectIT==''){
		    alert('Please enter email subject');
			$('#emailSubjectIT').focus();
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
    var formData = {
        email:$('#email').val(),
        subject:$('#subject').val(),
        message:$('#message').val()
    };
    $.ajax({
        url:'Direct_email/sendMail',
        method:'POST',
        data:formData,
        success: function(response){
            const data = JSON.parse(response);
            const httpCode = data.response.match(/\d{3}/)[0];
            $("#sendMail").prop("disabled", false); // Enable submit button
            if(httpCode==200){
			    $("#sendMail").html("Submit");
                // Success Message
                alert_float('success', "Mail sent succesfully!");
            }else{
			   $("#sendMail").html("Submit");
                // Failure Message
                alert_float('danger', "Failed to send mail. Please try again.");
            }
        },
        error: function (xhr, status, error){
            $("#sendMail").prop("disabled", false); // Enable submit button
            console.error('Error:',error);
            // Failure Message
            alert_float('danger', "Failed to send mail. Please try again.");
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
  //For Add /  Remove Signature
  //toggleSignature function define on asset/js/custom.js
  //need add css editor in jq editor textarea
  const signature = `<br><br><br><br><?php echo $email_signature;?>`;
</script>