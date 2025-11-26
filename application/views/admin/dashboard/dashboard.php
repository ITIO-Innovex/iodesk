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
	<span class="tw-truncate tw-text-sm">Role : <?php  if(isset($GLOBALS['current_user']->role)&&$GLOBALS['current_user']->role) { echo get_staff_role_name($GLOBALS['current_user']->role);} ?> [<?=get_user_type();?>]
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
<a href="<?php echo admin_url('staff');?>" class="fancy-btn"><i class="fa-solid fa-users menu-icon"></i> Add New Staff</a>
<a href="<?php //echo admin_url('dashboard/testemail');?>" class="fancy-btn hide"><i class="fa-solid fa-users menu-icon"></i> Test Email</a>
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
<script>
app.calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php //$this->load->view('admin/dashboard/google_js'); ?>


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
</html>