<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
    <div class="widget-dragger"></div>
    <div class="row">
<?php if(is_admin()){  ?>
<?php $this->load->view('admin/dashboard/sales_dashboard'); ?>
<?php $this->load->view('admin/dashboard/team_document'); ?>
<?php }elseif(isset($GLOBALS['current_user']->role)&&$GLOBALS['current_user']->role==6) {  
redirect(admin_url('project/dashboard'));?>		 
<?php }elseif((isset($GLOBALS['current_user']->role)&&$GLOBALS['current_user']->role==5)) {   ?>
<?php $this->load->view('admin/dashboard/team_document'); ?>
<?php }elseif((isset($GLOBALS['current_user']->role)&&$GLOBALS['current_user']->role==8)) {   ?>
<?php $this->load->view('admin/dashboard/sales_dashboard'); ?>
<?php }else{ ?>  
<?php $this->load->view('admin/dashboard/team_document'); ?>
<?php } ?>    
        
    </div>
</div>