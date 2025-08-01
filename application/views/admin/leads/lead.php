<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
function ensureHttps($url) {
    if (!preg_match('/^https?:\/\//i', $url)) {
        return 'https://' . $url;
    }
    return $url;
}

?>

<style>
#lead-modal .modal-lg{
width: 100% !important;
}
.box-shadow{
box-shadow: rgba(0, 0, 0, 0.09) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
padding:20px;
background:#ecf3f5;
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
border-color: rgb(37 99 235 / var(--tw-border-opacity)) !important;
color: rgb(37 99 235 / var(--tw-text-opacity)) !important;
font-weight: bolder !important;
}

</style>
 
<div class="modal-header XXX">
 <?php if(isset($lead->id)&&$lead->id){ ?>

	<button type="button" class="close reminder-open" id="reminderx"  data-target=".reminder-modal-lead-<?php echo $lead->id;?>" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<?php }else{ ?>
	<button type="button" class="close" aria-label="Close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
	<?php } ?>
	
		
	<h4 class="modal-title">
	<?php 
	if (isset($lead)) {
		if (!empty($lead->name)) {
			$name = $lead->name;
		} elseif (!empty($lead->company)) {
			$name = $lead->company;
		} else {
			$name = _l('lead');
		}
		
		echo '#' . $lead->id . ' - ' . $name. ' - ' . (isset($lead->subject) ? $lead->subject  : '');
	} else {
		echo _l('add_new', _l('lead_lowercase'));
	}

	if (isset($lead)) {
		echo '<div class="tw-ml-4 -tw-mt-2 tw-inline-block">';
		if ($lead->lost == 1) {
			echo '<span class="label label-danger">' . _l('lead_lost') . '</span>';
		} elseif ($lead->junk == 1) {
			echo '<span class="label label-warning">' . _l('lead_junk') . '</span>';
		} else {
			/*if (total_rows(db_prefix() . 'clients', [
				'leadid' => $lead->id, ])) {
				echo '<span class="label label-success">' . _l('lead_is_client') . '</span>';
			}*/
		}
		echo '</div>';
	}
	?> 
	
    <!--	For Customized $_SESSION['deal_form_type']=1 else 0 for default-->
	<?php if(isset($lead->deal_status)&&$lead->deal_status&&$_SESSION['deal_form_type']==0){ ?> 
	
	
	
	<?php if(isset($lead->deal_status)&&$lead->deal_status==3&&$lead->uw_status==0&&get_staff_rolex()!=4){ ?>
	<a href="#" class="btn btn-info" ><i class="fa-solid fa-edit"></i> Pending by UW Approver</a>
	<?php }elseif(isset($lead->uw_status)&&$lead->uw_status==0&&get_staff_rolex()==4){ ?>
	<a href="#" class="btn btn-success" data-toggle="modal" data-target="#dealModal" data-backdrop="static" 
   data-keyboard="false" title="Send Quotation / Reject" ><i class="fa-solid fa-handshake"></i> <?php echo $this->leads_model->get_deal_status_data($lead->deal_status);?></a>
	<?php }else{  
	$inv_url="javascript:void(0)";
	if($lead->deal_status==4){ 
	$inv_url=admin_url('invoices/invoice?iid=' . $lead->id);
	?>
	<a href="<?php echo $inv_url;?>" class="btn tw-text-white tw-rounded-full" style="background:<?php echo $this->leads_model->get_deal_status_color($lead->deal_status);?>"  target="_blank" title="Create Invoice" ><i class="fa-solid fa-handshake"></i> <?php echo $this->leads_model->get_deal_status_data($lead->deal_status);?> Deal</a>
	<?php }else{ ?>
	<a href="<?php echo $inv_url;?>" class="btn tw-text-white tw-rounded-full" data-toggle="modal" data-target="#dealModal" style="background:<?php echo $this->leads_model->get_deal_status_color($lead->deal_status);?>" title="click for change deal status" ><i class="fa-solid fa-handshake"></i> <?php echo $this->leads_model->get_deal_status_data($lead->deal_status);?> Deal</a>
	<?php } ?>
	
	<?php } ?>
	<?php }elseif(isset($lead->deal_status)&&$lead->deal_status&&$_SESSION['deal_form_type']==1){ // for Customized
	$datastage=$_SESSION['deal_form_order'];
	//print_r($datastage);
	?>
	<?php 
	if(count($_SESSION['deal_form_order']) > $lead->deal_stage){
	//echo get_deals_stage_title($datastage[$lead->deal_stage]);
	}else{
	//echo "check status";
	}
	
	?>
	<?php 
	
	$deal_stage_status=$lead->deal_stage_status;
	
	if($deal_stage_status==1){
	$inv_url=admin_url('invoices/invoice?iid=' . $lead->id);
	?>
	<a href="<?php echo $inv_url;?>" class="btn btn-sm btn-success tw-rounded-full "  target="_blank" title="Create Invoice" ><i class="fa-solid fa-handshake"></i> Invoice</a>
	<?php
	}else{
	
	//print_r($flat);
	echo '<div class="dt-buttons btn-group">';
	$modalopen='';
	foreach($datastage as $key=>$val){
	if($key==$lead->deal_stage){ 
	$btncss="btn-success"; $modalopen='data-toggle="modal" data-target="#CustomizedDealModal"';
	}elseif($key < $lead->deal_stage){ $btncss="btn-info"; $modalopen="";
	}else{$btncss="btn-warning"; $modalopen="";}
	//echo $key." == ".$val."<br>";
	echo '<button class="btn '.$btncss.' buttons-collection btn-sm btn-default-dt-options" tabindex="0" aria-controls="leads" type="button" aria-haspopup="true" '.$modalopen.' aria-expanded="false"><span>'.get_deals_stage_title($val).'</span></button>';
	}
    echo '</div>';
	}
	?>
   



	<?php } ?>
	
	
	</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<?php if (isset($lead)) {
				echo form_hidden('leadid', $lead->id);
			} ?>
			
			<!-- Tab panes -->
			<?php /*?>============Tab Content=========<?php */?>
			<div>
				<!-- from leads modal -->
				<div role="tabpanel" class="tab-pane active" id="tab_lead_profile">
					<?php $this->load->view('admin/leads/profile'); ?>
				</div>
				
			</div>
		</div>
	</div>
</div>

<script>
    // open reminder modal from close button
    $('.reminder-open').click(function() {
      var targetModal = $(this).data('target');
      $(targetModal).modal('show');
	  $('#reminderx').removeClass('reminder-open');
    });
	
	// close leads modal on click reminder modal
	 $('.close-reminder-modal').click(function() { 
	 $('#lead-modal').modal('hide');

    });
</script>
<script>
chatBox	= document.querySelector(".lead_conversion_list");

/* code for retetrive telegram conversion*/
$(document).ready(function() {
	scrollToBottom();
	// Event listener for the send button
	$('#send_telegram_conv').click(function(e) {

		// alert(111);
		e.preventDefault(); // Prevent the default form submission

		// Get the URL from the button's data-url attribute
		var url = $(this).data('url');

		//alert(url)
		// Get the text from the textarea
		var message = $('input[name="telegram_send_message"]').val();

		if (message.trim() === '') {
			alert('Please enter a message!');
			return;
		}

		// Show a loading indicator if you want (optional)
		$('#lead_conversion_list').html('<p>Loading...</p>');

		// AJAX request
		$.ajax({
			url: url, // Use the dynamically fetched URL here
			type: 'POST', // Request method
			data: {
				telegram_message: message, // Sending the message to the specified URL
				lead_id: <?php echo e($lead->id);?>, // Sending the message to the specified URL
				staff_user_id: <?php echo e($_SESSION['staff_user_id']);?>,
			},
			success: function(response) {
				// Handle the response (e.g., display in the lead_conversion_list)
				$('#lead_conversion_list').html(response);
				// Clear the textarea after sending the message (optional)
				$('textarea[name="telegram_send_message"]').val('');
				scrollToBottom();
			},
			error: function(xhr, status, error) {
				// Handle errors if any
				$('#lead_conversion_list').html('<p>Error: ' + error + '</p>');
			}
		});
	});
	/* --- script for refresh div tag	--- */
	/* --- script for refresh div tag	--- */
});

function scrollToBottom(){
	//alert(chatBox.scrollHeight);
	chatBox.scrollTop=chatBox.scrollHeight;
}

// for hide deal modal

   $('#dealModalClose').on('click', function() {
	  $('#dealModal').modal('hide');
    });
	
	
	function convert_to_deal() {
	
	if($('#deal_status').val()==""){ alert("Please select Deal status"); return false; }
	if($('#deal_id').val()==""){ alert("something wront try again after some time"); return false; }
	

   $.post(admin_url + 'leads/convert_to_deal', {
            deal_status: $('#deal_status').val(),
            is_deal : 1,
			id : $('#deal_id').val(),
        })
        .done(function(response) { 
            response = JSON.parse(response);
            alert_float(response.alert_type, response.message);
			setTimeout(function() {
      location.reload();
    }, 2000); // 5000 milliseconds = 5 seconds
        });
}



	  
	  $('#task_type').on('change', function () {
        var selected = $(this).val();
		//alert(selected);
        $('#other_task_title').hide(); // Hide all boxes
		$('#other_task_title').removeAttr('readonly');
        if (selected==14) {
        $('#other_task_title').show(); // Show selected box
		$('#other_task_title').attr('readonly', true);
        }
      });
	  
	  
	   $('#add-field').click(function () {
        $('#custom-fields').append(`
          <div class="field-group form-group row">
	  <div class="col-sm-3">
        <input type="text" class="form-control" name="custom_field_name[]" placeholder="Field Name" required>
		</div><div class="col-sm-7">
        <input type="text" class="form-control" name="custom_field_value[]" placeholder="Field Value" required>
		</div><div class="col-sm-2">
		<a href="#" class="remove text-danger" title="Remove"><i class="fa fa fa-times"></i></a>
		</div>
      </div>`);
      });

      $(document).on('click', '.remove', function () {
        $(this).closest('.field-group').remove();
      });
	  
	  
	  	$('.change_task_status').on('click', function() {
		
		var taskid=$(this).attr('data-tid');
		
		if(taskid==""){ return false; }
		
		
		if (!confirm("Are you sure you want to completed this task?")) {
        return false;
        }
	$(this).removeClass('text-warning').addClass('text-success');
	

   $.post(admin_url + 'leads/change_task_status', {
			id : taskid,
        })
        .done(function(response) { 
            response = JSON.parse(response);
            alert_float(response.alert_type, response.message);
			//alert("Done");
        });
       });
//change quotation status
	   
$('input[name="quotation_status"]').on('change', function() { 
      if ($(this).val() == 1) {
	    
        $('#approveDiv').slideDown(); // You can also use .show()
		$('#rejectDiv').slideUp(); // Or use .hide()
		$('#Reason').removeAttr('required');
		$('#MDR').attr('required', true);
		$('#SetupFee').attr('required', true);
		$('#HoldBack').attr('required', true);
		$('#CardType').attr('required', true);
		$('#Settlement').attr('required', true);
		$('#SettlementFee').attr('required', true);
		$('#MinSettlement').attr('required', true);
		$('#MonthlyFee').attr('required', true);
		$('#Descriptor').attr('required', true);
		$('#Remark').attr('required', true);
      } else {
	  
        $('#rejectDiv').slideDown(); // Or use .hide()
		$('#approveDiv').slideUp(); // You can also use .show()
		$('#Reason').attr('required', true);
		$('#MDR').removeAttr('required');
		$('#SetupFee').removeAttr('required');
		$('#HoldBack').removeAttr('required');
		$('#CardType').removeAttr('required');
		$('#Settlement').removeAttr('required');
		$('#SettlementFee').removeAttr('required');
		$('#MinSettlement').removeAttr('required');
		$('#MonthlyFee').removeAttr('required');
		$('#Descriptor').removeAttr('required');
		$('#Remark').removeAttr('required');
      }
    });
	  
</script>
 <script>
  
$('input[type="radio"][name="accept_cards"]').on('click', function() { 
    var selectedValue = $(this).val();

    // You can do something based on the selected value
    if (selectedValue == 1) {
        // Do something for Option A
		document.getElementById('cardsDiv').style.display = 'block';
    } else {
        // Do something for Option B
		document.getElementById('cardsDiv').style.display = 'none';
    }
});


// Toggle slider
 function togglediv(divdata){
 $(divdata).slideToggle(); // Animated toggle
 }
 
  function closemodal(divdata){ $('#dealModal').modal('hide');
  //alert(divdata);
  //$('#dealModal').modal('hide');
  //$('#lead-modal').modal('show');
  //$('body').addClass('modal-open');

  }

$(function() {
  
  setCheckboxSelectLabels();
  
  $('.toggle-next').click(function() {
    $(this).next('.checkboxes').slideToggle(400);
  });
  
  $('.ckkBox').change(function() {
    toggleCheckedAll(this);
    setCheckboxSelectLabels(); 
  });
  
});
  
function setCheckboxSelectLabels(elem) {
  var wrappers = $('.wrapper'); 
  $.each( wrappers, function( key, wrapper ) {
    var checkboxes = $(wrapper).find('.ckkBox');
    var label = $(wrapper).find('.checkboxes').attr('id');
    var prevText = '';
    $.each( checkboxes, function( i, checkbox ) {
      var button = $(wrapper).find('button');
      if( $(checkbox).prop('checked') == true) {
        var text = $(checkbox).next().html();
        var btnText = prevText + text;
        var numberOfChecked = $(wrapper).find('input.val:checkbox:checked').length;
        if(numberOfChecked >= 4) {
           btnText = numberOfChecked +' '+ label + ' selected';
        }
        $(button).text(btnText); 
        prevText = btnText + ', ';
      }
    });
  });
}

function toggleCheckedAll(checkbox) {
  var apply = $(checkbox).closest('.wrapper').find('.apply-selection');
  apply.fadeIn('slow'); 
  
  var val = $(checkbox).closest('.checkboxes').find('.val');
  var all = $(checkbox).closest('.checkboxes').find('.all');
  var ckkBox = $(checkbox).closest('.checkboxes').find('.ckkBox');

  if(!$(ckkBox).is(':checked')) {
    $(all).prop('checked', true);
    return;
  }

  if( $(checkbox).hasClass('all') ) {
    $(val).prop('checked', false);
  } else {
    $(all).prop('checked', false);
  }
}


$(document).ready(function () {
  function checkWidth() {
    if ($(window).width() >= 992) {
      $('#lead-modal').addClass('lead-modal-width');
    } else {
      $('#lead-modal').removeClass('lead-modal-width');
    }
  }

  checkWidth(); // Initial check
  $(window).resize(checkWidth); // Re-check on resize
  // for complete deal status
	 $('#systemStatus').on('change', function() {
        var status = $(this).val();
        if (status === '1') {
            $('#noteArea').show();
        } else {
            $('#noteArea').hide();
        }
    });
});
</script>

<?php 
hooks()->do_action('lead_modal_profile_bottom', (isset($lead) ? $lead->id : '')); 
?>