<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
//print_r($company_list);exit;

?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  </style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_status(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Company'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($company_list) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                
                                <?php /*?><th><?php echo _l('CID'); ?></th><?php */?>
                                <th><?php echo _l('Company Name'); ?></th>
								<th><?php echo _l('Website'); ?></th>
								<th><?php echo _l('Name'); ?></th>
								<th><?php echo _l('Phone Number'); ?></th>
								<th><?php echo _l('Email'); ?></th>
								<th><?php echo _l('Type'); ?></th>
								<th><?php echo _l('Deal Form Type'); ?></th>
								<th><?php echo _l('Status'); ?></th>
								<th><?php echo _l('Total Staff'); ?></th>
								<th><?php echo _l('Added On'); ?></th>
								<th>&nbsp;&nbsp;</th>
                            </thead>
                            <tbody>
                                <?php 
								foreach ($company_list as $list) { 
								$admintype="Admin";
								$surl=0;
								if(isset($list['company_id'])&&$list['company_id']==1){
								$admintype="Super Admin";
								}
								if(isset($list['active'])&&$list['active']==0){
								$surl=1;
								}
								?>
                                <tr>
                                    <?php /*?><td><?php echo e($list['company_id']); ?></td><?php */?>
									<td><a href="<?php echo admin_url('staff/bycompany/' . e($list['company_id'])); ?>" title="click to view this company details"><?php echo e($list['companyname']); ?></a></td>
									<td><?php echo e($list['website']); ?></td>
									<td><?php echo e($list['firstname']); ?> <?php echo e($list['lastname']); ?></td>
									<td><?php echo e($list['phonenumber']); ?></td>
									<td><?php echo e($list['email']); ?></td>
									<td><?php echo $admintype; ?></td>
									<td>
  <?php
    if (isset($list['deal_form_type'])) {
      echo $list['deal_form_type'] == 1 ? 'Customized Form' : 'Default';
    } else {
      echo 'Customized Form'; // default if not set
    }
  ?>
</td>
									<td><?php //echo e($list['active']); ?>


<div class="onoffswitch">
<input type="checkbox" data-url="<?php echo admin_url('staff/deletecompany/' . e($list['company_id']).'/'. $surl); ?>"  class="onoffswitch-checkbox confirm-checkbox" onclick="return confirm('Are you sure you want to proceed?')" id="v_<?php echo e($list['company_id']); ?>"  <?php if(isset($list['active'])&&$list['active']){ ?> checked="" <?php } ?> >
<label class="onoffswitch-label" for="v_<?php echo e($list['company_id']); ?>"></label>
</div>
            </td>
<td><a class="tw-rounded-full tw-bg-danger-600 tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-6 tw-w-6 -tw-mt-1 group-hover:!tw-bg-primary-700" href="<?php echo admin_url('staff?cid='. e($list['company_id'])); ?>" title="view staff list" target="_blank"><?php echo e($list['staff_count']); ?></td>
									<td><?php echo e($list['addedon']); ?></td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
<a href="#" onclick="edit_company(this,<?php echo e($list['company_id']); ?>);return false;"
    data-company="<?php echo e($list['companyname']); ?>" 
    data-website="<?php echo e($list['website']); ?>"
    data-deal_form_type="<?php echo isset($list['deal_form_type']) ? e($list['deal_form_type']) : '1'; ?>"
    class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"> <i class="fa-regular fa-pen-to-square fa-lg"></i></a>
                                            
                                           
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('Company Not Found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="status" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('staff/company'), ['id' => 'company-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title"><?php echo _l('Edit Company'); ?></span> <span class="add-title"><?php echo _l('Add New Company'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <div class="tab-content tw-mt-5">
                               
   <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
<input type="email" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
<input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1"/>                    
<?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
<?php $required = (isset($member) ? [] : ['required' => 1]); ?>

<?php echo render_input('companyname', '<small class="req text-danger">* </small> Company Name', '', 'text', $required); ?>
<?php echo render_input('website', '<small class="req text-danger">* </small> Website', '', 'url', $required); ?>
<div class="form-group">
  <label for="deal_form_type">Deal Form Type</label>
  <select class="form-control" name="deal_form_type" id="deal_form_type">
    <option value="0" <?php if(isset($member) && isset($member->deal_form_type) && $member->deal_form_type == 0) echo 'selected'; ?>>Default</option>
    <option value="1" <?php if((isset($member) && isset($member->deal_form_type) && $member->deal_form_type == 1) || !isset($member)) echo 'selected'; ?>>Customized Form</option>
  </select>
</div>
<div id="compStaff" >
<hr class="hr-text gradient" data-content="For Company Admin Login Details">
<?php echo render_input('firstname', '<small class="req text-danger">* </small> First Name', '', 'text'); ?>
<?php echo render_input('lastname', '<small class="req text-danger">* </small> Last Name', ''); ?>
<?php echo render_input('email', '<small class="req text-danger">* </small> Email (for admin User)', '', 'email', ['autocomplete' => 'off']); ?>
<?php echo render_input('phonenumber', '<small class="req text-danger">* </small> Phone / Mobile', '', 'text',['required' => 'true','maxlength' => '15','minlength' => '10','onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57']); ?>
<?php if (!isset($member) || is_admin() || !is_admin() && $member->admin == 0) { ?>
                               
					   
                         <label for="password" class="control-label"><small class="req text-danger">* </small> <?php echo _l('staff_add_edit_password'); ?></label>
                                <div class="input-group">
   <input type="password" class="form-control password" name="password" autocomplete="off" minlength="5" maxlength="15">
                                    <span class="input-group-addon tw-border-l-0">
                                        <a href="#password" class="show_password"
                                            onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
                                    </span>
                                    <span class="input-group-addon">
                                        <a href="#" class="generate_password"
                                            onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                                    </span>
                                </div>
<?php } ?>

<div>
								<div class="tw-mt-2">
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
                            </div>
			</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <!-- /.modal-content -->
    <?php echo form_close(); ?> </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php init_tail(); ?>
<script>

<?php /*?>
 window.addEventListener('load', function () {
    appValidateForm($("body").find('#leads-status-form'), {
        name: 'required'
    }, manage_leads_statuses);
    $('#status').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#status input[name="name"]').val('');
        $('#status input[name="color"]').val('');
        $('#status input[name="statusorder"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        $('#status input[name="statusorder"]').val($('table tbody tr').length + 1);
    });
});<?php */?>

// Create lead new status
function new_status() {
    $('#status').modal('show');
    $('.edit-title').addClass('hide');
	$('#compStaff').removeClass('hide');
	$('#companyname').val('');
	$('#website').val('');
	$('#email').val('');
	$('#firstname').attr('required', true);
	$('#lastname').attr('required', true);
	$('#email').attr('required', true);
	$('#phonenumber').attr('required', true);
	$('#status input[name="password"]').attr('required', true);
}

// Edit status function which init the data to the modal
function edit_company(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#status input[name="companyname"]').val($(invoker).data('company'));
    $('#status input[name="website"]').val($(invoker).data('website'));
    // Set deal_form_type if available
    if ($(invoker).data('deal_form_type') !== undefined) {
        $('#deal_form_type').val($(invoker).data('deal_form_type'));
    }
    $('#status').modal('show');
    $('#compStaff').addClass('hide');
    $('#firstname').removeAttr('required');
    $('#lastname').removeAttr('required');
    $('#email').removeAttr('required');
    $('#phonenumber').removeAttr('required');
    $('#status input[name="password"]').removeAttr('required');
}




</script>
<script>
$('.confirm-checkbox').on('change', function () {

  const url = $(this).data('url');
  //alert(url);

    //const confirmed = confirm('Are you sure you want to proceed?');
    if (confirmed) {
	//window.onbeforeunload = null;
      window.location.href = url;
    } else {
      $(this).prop('checked', false); // Uncheck if user cancels
    }
 
});
</script>
</body></html>