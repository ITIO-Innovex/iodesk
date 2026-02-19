<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();  ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2">
      <i class="fa-solid fa-users tw-mr-2"></i> Approver List
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
<?php if (!empty($approver_list) || !empty($reporting_manager)) { // Your object
$approverData = json_decode($approver_list);
//print_r($approver_list);
?>


              <div class="table-responsive">
                <table class="table table-bordered table-striped dt-table" data-order-col="0" data-order-type="asc">
                  <thead>
                    <tr style="background-color: #f9f9f9;">
                      <th style="width: 25%;">Department</th>
                      <th style="width: 20%;">Approver Name</th>
                      <th style="width: 20%;">Approver Email</th>
                      <?php /*?><th style="width: 15%;">Phone Number</th><?php */?>
                    </tr>
                  </thead>
                  <tbody>
                   <?php foreach ($approverData as $key => $value) { 
				   if(!empty($value)){
				   ?>
                      <tr>
                        <td><?php echo _l($key);?></td>
                        <td><?php echo get_staff_full_name($value);?></td>
                        <td><?php echo get_staff_email($value);?></td>
                        <?php /*?><td><?php echo $value;?></td><?php */?>
                      </tr>
                    <?php } } ?>
					<?php if(isset($reporting_manager)&&$reporting_manager){?>
					<tr>
                        <td>Reporting Manager / Team Leader</td>
                        <td><?php echo get_staff_full_name($reporting_manager);?></td>
                        <td><?php echo get_staff_email($reporting_manager);?></td>
                        <?php /*?><td><?php echo $reporting_manager;?></td><?php */?>
                      </tr> 
					 <?php } ?>
					
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                No approvers found. Please ensure staff members have approver information configured.
              </div>
            <?php } ?>
			
			
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<?php hooks()->do_action('app_admin_footer'); ?>

