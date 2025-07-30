<?php defined('BASEPATH') or exit('No direct script access allowed'); ?> 
<?php //$CI =& get_instance(); // Get CodeIgniter super object
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

?>

<?php init_head(); ?>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div id="wrapper">
    <div class="screen-options-area">
	             
	<div class="top_stats_wrapper"> 
				 			                				
	<div class="row">     
	                
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon fa-2x fa-1x tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-xl">Name : <?php echo e(get_staff_full_name()); ?></span>                     
	</div>    
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon fa-2x fa-1x tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-xl">Email : <?php echo $GLOBALS['current_user']->email; ?></span>                     
	</div>
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon fa-2x tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-xl">Role : <?php echo get_staff_rolex(); ?> [<?=get_user_type();?>]
	</span>                     
	</div> 
	
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon fa-2x tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-xl">Company Name : <?php echo get_staff_company_name(); ?></span>                     
	</div>
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon fa-2x tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-xl">Created At : <?php echo $GLOBALS['current_user']->datecreated; ?></span>                     
	</div>  
	<div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6">                         
	<i class="fa-regular fa-circle-check menu-icon fa-2x tw-mx-2 text-success"></i>                         
	<span class="tw-truncate tw-text-xl">Last Login : <?php echo $GLOBALS['current_user']->last_login; ?></span>                     
	</div>              
	</span>                 
	</div> 				 				                                                
	
	</div>
	
	</div>
    <div class="screen-options-btn box-shadow-bg">
        <i class="fa-solid fa-user  menu-icon tw-mx-2 fa-2x" title="View Profile"></i>
    </div>
    <div class="content">
        <div class="row">
		<?php if(is_super() && empty($_SESSION['super_view_company_id'])){ ?>
		<div class="col-md-12 mtop20">
		<a href="<?php echo admin_url('staff/companies');?>" class="fancy-btn"><i class="fa-solid fa-building-user menu-icon"></i> Add New Company</a>
		</div>
		<?php }else{ ?>
		<div class="col-md-12 mtop20">
<a href="<?php echo admin_url('staff');?>" class="fancy-btn"><i class="fa-solid fa-users menu-icon"></i> Add New Staff</a>
<a href="<?php echo admin_url('dashboard/testemail');?>" class="fancy-btn hide"><i class="fa-solid fa-users menu-icon"></i> Test Email</a>
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
            <div class="col-md-12" data-container="bottom-left-12">
                <?php render_dashboard_widgets('bottom-left-12'); ?>
            </div>
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