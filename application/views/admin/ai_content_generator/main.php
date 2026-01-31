<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 

?>
<?php
function cleartext($txt){
$txt=str_replace('\n', '<br>', $txt);
$txt=str_replace('\"', ' "', $txt);
$txt=str_replace("\'", "'", $txt);
return $txt;
}

?>
<?php //print_r($webmaillist);?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
<?php if (is_admin()) {  ?>
<div class="tw-mb-2 sm:tw-mb-2" >
<h4 class="tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5" style="float: left;">ChatGTP</h4>
<a class="btn btn-primar" onclick="edit_key(); return false;" title="Update AI API KEY" style="float:right">
<i class="fa fa-cog menu-icon tw-mr-1 fa-2x tw-text-neutral-400"></i></a>&nbsp;<a class="btn btn-primar"  href="<?php echo admin_url('ai_content_generator/manage_ai_provider'); ?>"  title="Add new AI" style="float:right">
<i class="fa-solid fa-circle-plus tw-mr-1 fa-2x tw-text-neutral-400"></i></a>

</div>
<div class="clearfix tw-mb-2"></div>
<?php } ?>
                

<div class="panel_s">
                    <div class="panel-body panel-table-full">
<?php if(isset($_SESSION['ai-apikey']) && $_SESSION['ai-apikey']){?>

                
<form action="<?=  admin_url('ai_content_generator/generate') ?>" method="post">
<!-- CSRF Token -->
<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
<div class="mb-3">
        <label for="recipientEmail" class="form-label mtop10">Content Title / Subject</label>
        <input type="text" class="form-control" id="content_title" name="content_title" value="" required>
      </div>
<button type="submit" name="submit" class="btn btn-primary mtop20">Generate Content</button>
<div style="float:right">
<?php
foreach ($providers as $provider) {

if(isset($provider['viewon'])&&$provider['viewon']==2) {
?>
<a href="<?php echo $provider['provider_url'];?>"  name="submit" class="btn btn-success mtop20" target="_blank" title="Redirect to <?php echo $provider['provider_url'];?>" ><?php echo $provider['provider_name'];?> <i class="fa-solid fa-up-right-from-square"></i></a>
<?php
}else{
?>
<a type="submit" name="submit" class="btn btn-primary mtop20 provider_modal" data-title="<?php echo $provider['provider_name'];?>" data-url="<?php echo $provider['provider_url'];?>"><?php echo $provider['provider_name'];?> <i class="fa-solid fa-expand"></i></a>
<?php
    //echo "Name: " . $provider['provider_name'] . " | URL: " . $provider['provider_url'] . "<br>";
}
}

?>
</div>
</form>
       
	    

<?php }else{ ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> To proceed, please add your ChatGPT API key.  <span style="float:right"><a href="#" onclick="edit_key(); return false;" class="btn btn-warning btn-sm ms-2">Set Chatgtp API Key</a></span></div>
                  </div>
				  
<?php } ?>
 </div>
                </div>
                    
				
	<?php if(isset($content_description)&&$content_description){?>			
				<div class="panel_s">
                    <div class="panel-body panel-table-full">
					<div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5" data-v-app=""><button type="button" class="btn btn-danger" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $content_title;?> </button>&nbsp;<button class="btn btn-default"  title="Copy Content" onClick='CopyValTestbox("myInput")'>&nbsp;<i class="fa-solid fa-copy  text-danger" aria-hidden="true"></i>&nbsp;</button></div>
					<div  style="clear:both;"></div>
					
					<div class="p-card" style="max-width:100% !important;">
					<h5 class="bold">Content : <?php echo $content_title;?></h5>
					<?php echo cleartext($content_description) ;?>
					</div>
<textarea style="height:1px;width:1px;float:inline-end;" id="myInput"><?php echo cleartext($content_description);?></textarea>
					</div>
				</div>
	<?php }?>	
	
	<?php if (count($_SESSION['datalists']) > 0) { ?>
	<div class="panel_s">
	<h4>&nbsp;&nbsp;History (Last 5)</h4>
	<?php foreach ($_SESSION['datalists'] as $rs) { ?>
                    <div class="panel-body panel-table-full">
					<div id="vueApp" class="tw-inline pull-right tw-ml-0 sm:tw-ml-1.5" data-v-app=""><button type="button" class="btn btn-danger " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $rs['content_title'];?> </button>&nbsp;<button class="btn btn-default" title="Copy Content" onClick='CopyValTestbox("vkg<?php echo $rs['content_id'];?>")'>&nbsp;<i class="fa-solid fa-copy text-danger" aria-hidden="true"></i>&nbsp;</button></div>
					<div  style="clear:both;"></div>
					
					<div class="p-card" style="max-width:100% !important;" >
					
					<?php 
					echo cleartext($rs['content']);
					?>
<textarea style="height:1px;width:1px;float:inline-end;" id="vkg<?php echo $rs['content_id'];?>">Subject : <?php echo $rs['content_title']."\r\n";?><?php echo cleartext($rs['content']);?></textarea>
					
					
					</div>
					</div>
					<?php }?>	
				</div>
	<?php }?>	
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="entryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('ai_content_generator/ai_setup_create/'), ['data-create-url' => admin_url('ai_content_generator/ai_setup_create/'), 'data-update-url' => admin_url('ai_content_generator/ai_setup_update')]); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo 'AI APIKey Update'; ?></h4>
            </div>
            <div class="modal-body">
                <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
                <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1" />
				
                 
<div class="table-responsive">
<span style=" float:right"><a href="https://platform.openai.com/api-keys" title="Generate API KEY" target="_blank">https://platform.openai.com/api-keys</a></span>
  <table class="table table-bordered roles no-margin">
    <tbody>
	
      <tr data-name="bulk_pdf_exporter">
        <td><?php echo render_input('apikey', 'Chatgtp API Key','', 'text', ['required' => 'true']); ?></td>
      </tr>

</tbody>
</table>
</div>   
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Modal -->
<div class="modal fade" id="providerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
	   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
        <h5 class="modal-title" id="providerModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" style="height:80vh;">
	  <div><a id="providerHref" href="#" class="btn btn-success tw-my-2"  target="_blank" title="Move to web" style="float:right">Move to website</a></div>
        <iframe id="providerIframe" src="" style="width:100%; height:100%; border:0;"></iframe>
      </div>
	  <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>

var $entryModal = $('#entryModal');
$(function() {

    appValidateForm($entryModal.find('form'), {
        server_address: 'required',
        username: 'required',
        password: 'required',
    });
    setTimeout(function() {
        $($entryModal.find('form')).trigger('reinitialize.areYouSure');
    }, 1000)
    $entryModal.on('hidden.bs.modal', function() {
        var $form = $entryModal.find('form');
        $form.attr('action', $form.data('create-url'));
        $form.find('input[type="text"]').val('');
        $form.find('#share_in_projects').prop('checked', false);
    });
});

$(function() {
    initDataTable('.table-custom-fields', window.location.href);
});

	// js for copy data
	function CopyValTestbox(divid) {
	//alert(divid)
   
	var range = document.createRange();
	range.selectNode(document.getElementById(divid));
	window.getSelection().removeAllRanges(); // clear current selection
	window.getSelection().addRange(range); // to select text
	
	
	        if (document.execCommand('copy')) {
                window.getSelection().removeAllRanges();// to deselect
				//alert("Copied : " + theLabel);
				alert("Content Copied");
				
            }
	}

function edit_key() {
    $.get(admin_url + 'ai_content_generator/ai_setup', function(response) {
        //alert(JSON.stringify(response, null, "\t"))
		
		if(response.apikey==""){
		alert("Before Start add Chatgtp API Key");
		}
        var $form = $entryModal.find('form');
        $form.attr('action', $form.data('update-url'));
		$form.find('#apikey').val(response.apikey);
        $entryModal.modal('show');
    }, 'json');
}

$(document).on("click", ".provider_modal", function () { 
    var title = $(this).data("title");
    var url = $(this).data("url");
    $("#providerModalLabel").text(title);
	$("#providerHref").text(title);
    $("#providerIframe").attr("src", url);
	$("#providerHref").attr("href", url);
    $('#providerModal').modal('show');
   
});
</script>

</body>

</html>