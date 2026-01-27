<?php defined('BASEPATH') or exit('No direct script access allowed'); ?> 
<?php 

//echo get_approver_id("reporting_approver");
//$CI =& get_instance(); // Get CodeIgniter super object
//print_r($GLOBALS['current_user']); // Call session method properly 
//$CI =& get_instance();
//print_r($CI->session);
//echo "Super Admin = ".is_super();
//echo "Admin = ".is_admin();
//echo "Staff = ".get_staff_user_id();
//print_r($_SESSION);
//echo trim(get_option('smtp_host'));
//echo trim(get_option('smtp_password'));
//echo get_company_website();
//$this->load->model('departments_model');
//$xxx=$this->departments_model->get_staff_departments(get_staff_user_id(), true);
//echo $xxx[0];
//echo $departmentsID;
//echo get_departments_id();

//print_r($activity_log);

// Get Attendance Time
$in_time  = $attendance[0]['in_time']  ?? '';
$out_time = $attendance[0]['out_time'] ?? '';
$subscription_status=subscription_status(); // for check subscription


$active_department_count  = $active_department_count  ?? '';
$active_designation_count  = $active_designation_count  ?? '';
$display_setup=1;
if(isset($company_details['company_logo'])&&$company_details['company_logo']&&isset($company_details['settings'])&&$company_details['settings']&&isset($company_details['company_logo'])&&$company_details['direct_mail_smtp']&&isset($active_department_count)&&$active_department_count&&$active_designation_count&&$active_designation_count){
$display_setup=0;
}

?>

<?php init_head(); ?>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="wrapper">
    <div class="screen-options-area">
	             
	<div class="top_stats_wrapper modal-content"> 
				 			                				
	<div class="row ">  
	              
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-sm">Name : <?php echo e(get_staff_full_name()); ?></span>                     
	</div>    
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon  tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-sm">Email : <?php echo $GLOBALS['current_user']->email; ?></span>                     
	</div>
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-sm">Role : <?php  if(isset($GLOBALS['current_user']->role)&&$GLOBALS['current_user']->role) { echo get_staff_role_name($GLOBALS['current_user']->role);} ?> <?php  if(isset($GLOBALS['current_user']->designation_id)&&$GLOBALS['current_user']->designation_id) { echo get_staff_designations_name($GLOBALS['current_user']->designation_id);} ?> [<?=get_user_type();?>] 
	</span>                     
	</div> 
	
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6 ">                         
	<i class="fa-regular fa-circle-check menu-icon  tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-sm">Company Name : <?php echo get_staff_company_name(); ?></span>                     
	</div>
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon  tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-sm">Created At : <?php echo $GLOBALS['current_user']->datecreated; ?></span>                     
	</div>  
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-sm">Last Login : <?php echo $GLOBALS['current_user']->last_login; ?></span>                     
	</div>              
	</span>                 
	</div> 				 				                                                
	
	</div>
	
	</div>
    <div class="screen-options-btn box-shadow-bg tw-mt-2">
        <i class="fa-solid fa-user  menu-icon tw-mx-2 fa-2x" title="View Profile - <?=get_user_type();?>"></i>
    </div>
	<div class="tw-mt-2" style="margin-left: 30px;">
        <!-- Place this where you want the clock to appear -->
  <div class="digital-clock" aria-live="polite" title="Local time">
    <div class="dc-time" id="dc-hours-min">00:00</div>
    <div class="dc-seconds" id="dc-seconds">:00</div>
    <div class="dc-ampm" id="dc-ampm">AM</div>
  </div>
  <?php if(isset($in_time)&&$in_time){ ?>
 <button type="submit" class="digital-btn btn-success attendance-submit"  name="attendance" data-mode="Out" data-toggle="tooltip" data-title="Your Mark in Time : <?php echo date("Y F d");?> <?php echo $in_time;?>" data-original-title="" ><i class="fa-solid fa-right-from-bracket"></i> Mark out </button>
  <?php }else{ ?>
   <button type="submit" class="digital-btn btn-warning attendance-submit"  name="attendance" data-mode="In" > Mark in <i class="fa-solid fa-right-from-bracket fa-rotate-180"></i></button>
  <?php } ?>
    </div>
    <div class="content">
        <div class="row">
		<?php if(is_super() && empty($_SESSION['super_view_company_id'])){ ?>
		<div class="col-md-12 mtop20">
		<a href="<?php echo admin_url('staff/companies');?>" class="fancy-btn"><i class="fa-solid fa-building-user menu-icon"></i> Add New Company</a>
		</div>
		<?php }elseif(is_admin() || is_department_admin()){ ?>
		<div class="col-md-12 mtop20">
		
			
<?php if($subscription_status<>'active'){ ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo _l($subscription_status);?> <span style="float:right"><a href="<?php echo admin_url('services/choose_subscriptions');?>" class="btn btn-warning btn-sm ms-2">Subscribe Now</a></span></div>
                  </div>
<?php }else{ ?>
<?php /*?><a href="<?php echo admin_url('staff');?>" class="fancy-btn"><i class="fa-solid fa-users menu-icon"></i> Add New Staff</a><?php */?>

<?php } ?>	
<?php if($display_setup==1){ ?> 
<div class="alert alert-danger tw-bg-warning-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> CRM Settings Guide where you can set up and manage all modules from one place. <span style="float:right"><a href="<?php echo admin_url('crm_setup');?>" class="btn btn-warning btn-sm ms-2">CRM SETUP GUIDE</a></span></div>

<?php } ?>	


				  
<?php /*?><a href="<?php //echo admin_url('dashboard/testemail');?>" class="fancy-btn hide"><i class="fa-solid fa-users menu-icon"></i> Test Email</a><?php */?>
		</div>
		<?php } ?>
            <?php //$this->load->view('admin/includes/alerts'); ?>

                <?php //hooks()->do_action('before_start_render_dashboard_content'); ?>

            <div class="clearfix"></div>

            <div class="col-md-12 mtop20" data-container="left-12">
                <?php render_dashboard_widgets('top-12'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard_top_container'); ?>

            <div class="col-md-6" data-container="middle-left-6">
                <?php render_dashboard_widgets('middle-left-6'); ?>
            </div>
            <div class="col-md-6" data-container="middle-right-6">
                <?php render_dashboard_widgets('middle-right-6'); ?>
            </div>

            <?php /*?><div class="col-md-12" data-container="left-12">
                <?php render_dashboard_widgets('left-12'); ?>
            </div><?php */?>

            <?php hooks()->do_action('after_dashboard_half_container'); ?>

            <?php /*?><div class="col-md-8" data-container="left-8">
                <?php render_dashboard_widgets('left-8'); ?>
            </div>
            <div class="col-md-4" data-container="right-4">
                <?php render_dashboard_widgets('right-4'); ?>
            </div><?php */?>
			<?php //if($departmentsID!=8){ ?>
            <div class="col-md-12" data-container="bottom-left-12">
                <?php render_dashboard_widgets('bottom-left-12'); ?>
            </div>
			<?php //} ?>
            <div class="col-md-8" data-container="bottom-left-8">
                <?php //render_dashboard_widgets('bottom-left-8'); ?>
            </div>


            <div class="clearfix"></div>

            <?php hooks()->do_action('after_dashboard'); ?>
        </div>
    </div>
</div>

<!-- Webmail Setup Modal -->
<div class="modal fade" id="webmailSetupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('webmail_setup/webmail_setup_create/'), ['data-create-url' => admin_url('webmail_setup/webmail_setup_create/'), 'data-update-url' => admin_url('webmail_setup/webmail_setup_update'), 'id' => 'webmail-setup-form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo 'Webmail Setup'; ?> <span id="staff-info-header" class="text-muted"></span></h4>
            </div>
            <div class="modal-body">
                <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
                <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1" />
                <input type="hidden" name="staffid" id="webmail_staffid" value="" />
				<input type="hidden" name="source" value="staff" />
				<input type="hidden" name="mailer_name" id="mailer_name" value="" />
				<input type="hidden" name="mailer_email" id="mailer_email" value="" />
                 
                <div class="form-group">
                <div class="text-center">
        <img src="https://static.zohocdn.com/iam/v2/components/images/newZoho_logo.5f6895fcb293501287eccaf0007b39a5.svg" class="" alt="ZOHO"></div>    
                </div>
                
                <div class="loaderssmtp"></div>
                
              
                
                <div class="form-group">
                    <label for="mailer_password" class="control-label">Password (Zoho Email)</label>
                    <?php echo render_input('mailer_password', '', '', 'password', ['required' => 'true', 'id' => 'mailer_password']); ?>
                </div>
				
<div class="form-group">
<div class="alert alert-info">
    <strong>Instruction:</strong> Please enable <b>IMAP</b> before submitting your password.
</div>

<h4>How to Enable IMAP in Zoho Mail</h4>

<ol>
    <li class="tw-my-2">
        <b>Step 1: Login to Zoho Mail</b>
        <ul>
            <li>Open <a href="https://mail.zoho.in" target="_blank">https://mail.zoho.in</a></li>
            <li>Login using your Zoho email ID and password</li>
        </ul>
    </li>

    <li class="tw-my-2">
        <b>Step 2: Open Mail Settings</b>
        <ul>
            <li>Click the <i class="fa-solid fa-gear"></i> <b>Settings</b> (top-right corner)</li>
            <li>Go to <b>Mail Accounts</b></li>
            <li>Select your email account</li>
        </ul>
    </li>

        <li class="tw-my-2">
        <b>Step 3: Checked Below </b>
        <ul>
            <li>Chacked on <b><i class="fa-solid fa-circle-check text-primary"></i> Pop Access</b></li>
            <li>Chacked on <b><i class="fa-solid fa-circle-check text-primary"></i> IMAP Access</b></li>
            <li>Chacked on <b><i class="fa-solid fa-circle-check text-primary"></i> SMTP Save copy of send email</b></li>
            <li>Click <b>Save</b></li>
        </ul>
        
    </li>
</ol>
				</div>
                           
            </div>
            <div class="modal-footer">
               <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>-->
                <button type="submit" class="btn btn-primary loaderssmtp"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Webmail Setup Modal -->
<script>
app.calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php //$this->load->view('admin/dashboard/google_js'); ?>
<?php if($_SESSION['smtp_fetch_type']=="CompanySMTP"){ 
$staffId    = $GLOBALS['current_user']->staffid ?? '';
$staffEmail = $GLOBALS['current_user']->email ?? '';

if(isset($staffId)&&$staffId&&isset($staffEmail)&&$staffEmail&&
    str_ends_with($staffEmail, '@itio.in')){
?>
<script>
  $(function () {
    openWebmailSetup(
      <?= (int)$staffId ?>,
      <?= json_encode($staffEmail) ?>
    );
  });
</script>
<?php
 }

 }
 ?>

</body>
<script>
$('#leads').addClass('table-striped tw-bg-info-100');
$('#leads tr:first').addClass('tw-bg-info-300');
</script>
<script>
document.getElementById('yearSelect').addEventListener('change', function () {
    const selectedYear = this.value;
    // Redirect to the same page with new year parameter
    window.location.href = '?year=' + selectedYear;
});
</script>
<script>
function openWebmailSetup(staffId, staffEmail) {
    $('#webmailSetupModal').modal('show');
    $('#webmail_staffid').val(staffId);
    
    // If email is passed from table, pre-fill it immediately
    if (staffEmail) {
        $('#webmailSetupModal #mailer_email').val(staffEmail);
    }
    
    // Get staff information via AJAX (for full name and to confirm email)
    $.get(admin_url + 'staff/getStaffInfo/' + staffId, function(staffInfo) {
        if (staffInfo && staffInfo.email) {
            // Display staff information in modal header
            var staffName = staffInfo.firstname + ' ' + staffInfo.lastname;
            $('#staff-info-header').html(' - <small>' + staffName + ' (' + staffInfo.email + ')</small>');
            
            // Pre-populate email field with staff email
            $('#webmailSetupModal #mailer_email').val(staffInfo.email);
            // Pre-populate name field with staff full name
            $('#webmailSetupModal #mailer_name').val(staffName);
        }
        
        // Check if webmail setup already exists for this staff
        $.get(admin_url + 'staff/getStaffWebmail/' + staffEmail, function(response) {
            if (response && response.id) {
                // Edit mode - populate form with existing data
                var $form = $('#webmail-setup-form');
                $form.attr('action', $form.data('update-url') + '/' + response.id);
                $form.find('#mailer_name').val(response.mailer_name || '');
                $form.find('#mailer_email').val(response.mailer_email || '');
                // Populate password field with existing password
                $form.find('#mailer_password').val(response.mailer_password || '');
            } else {
                // Create mode - form already populated with staff info above
                var $form = $('#webmail-setup-form');
                $form.attr('action', $form.data('create-url'));
            }
        }, 'json').fail(function() {
            // If no existing setup, form is already set for create mode
            var $form = $('#webmail-setup-form');
            $form.attr('action', $form.data('create-url'));
        });
    }, 'json').fail(function() {
        // If staff info fetch fails, just show modal
        $('#staff-info-header').html('');
    });
}
// Webmail Setup Modal handlers
var $webmailModal = $('#webmailSetupModal');
$(function() {
    appValidateForm($webmailModal.find('form'), {
        mailer_name: 'required',
        mailer_email: 'required',
        mailer_password: 'required',
    });
    
    setTimeout(function() {
        $($webmailModal.find('form')).trigger('reinitialize.areYouSure');
    }, 1000);
    
    $webmailModal.on('hidden.bs.modal', function() {
        var $form = $webmailModal.find('form');
        $form.attr('action', $form.data('create-url'));
        $form[0].reset();
        $('#staff-info-header').html(''); // Clear staff info header
    });
    
    // Handle form submission
    $('#webmail-setup-form').on('submit', function(e) {
        e.preventDefault();
        
        // Additional client-side validation for password
        var password = $('#mailer_password').val().trim();
        if (!password) {
            alert_float('warning', 'Password is required');
            $('#mailer_password').focus();
            return false;
        }
        
        // Check if password contains only whitespace
        if (password.length === 0 || /^\s*$/.test(password)) {
            alert_float('warning', 'Password cannot be empty or contain only spaces');
            $('#mailer_password').focus();
            return false;
        }
        $(".loaderssmtp").html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        var formData = $(this).serialize();
        var url = $(this).attr('action');
        
        $.post(url, formData).done(function(response) {
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (result.success === false) { 
				$(".loaderssmtp").html('<?php echo _l('submit'); ?>');
				alert("Wrong Password - please check your password");
                    // Show validation error message
                    alert_float('danger', result.message || 'Failed to save webmail setup');
                } else {
                    // Success case
                    alert_float('success', 'Webmail setup saved successfully');
                    $('#webmailSetupModal').modal('hide');
                    // Reload the table
                    if ($.fn.DataTable.isDataTable('.table-staff')) {
                        $('.table-staff').DataTable().ajax.reload(null, false);
                    } else {
                        window.location.reload();
                    }
                }
            } catch(e) {
                // If response is not JSON, assume success (backward compatibility)
                alert_float('success', 'Webmail setup saved successfully');
                $('#webmailSetupModal').modal('hide');
                if ($.fn.DataTable.isDataTable('.table-staff')) {
                    $('.table-staff').DataTable().ajax.reload(null, false);
                } else {
                    window.location.reload();
                }
            }
        }).fail(function(xhr) { 
            var message = 'Failed to save webmail setup';
            if (xhr.responseText) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        message = response.message;
                    }
                } catch(e) {
                    // Not JSON, use default message
                }
            }
			
            alert_float('danger', message);
        });
    });
});

$(document).ready(function(){
    $("#webmailSetupModal #mailer_email").on("keyup", function(){
        // In future, you can add additional reactive behaviors here if needed
    });
});
</script>
</html>