<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa-solid fa-person-booth menu-icon"></i> HRD Setting Dashboard</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            

            <div class="row mtop15">
              <div class="col-md-3">
			  <div class="widget-card bg-success text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo (int)$counters['present']; ?></h3>
                            <p>Present Today (<?php echo e($today); ?>)</p>
                        </div>
                    </div>
                </div>
                
              </div>
              <div class="col-md-3">
				<div class="widget-card bg-warning text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo (int)$counters['absent']; ?></h3>
                            <p>Absent Today</p>
                        </div>
                    </div>
                </div>
              </div>
              <div class="col-md-3">
                
				
				<div class="widget-card bg-info text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo (int)$counters['on_leave']; ?></h3>
                            <p>Leave Application</p>
                        </div>
                    </div>
                </div>
              </div>
			  <div class="col-md-3">
                
				
				<div class="widget-card bg-info text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo (int)$counters['on_leave']; ?></h3>
                            <p>Attendance Request</p>
                        </div>
                    </div>
                </div>
              </div>
			  <div class="col-md-3">
                
				
				<div class="widget-card bg-info text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo (int)$counters['on_leave']; ?></h3>
                            <a href="<?php echo admin_url('hrd/uploaded_document');?>"  target="_blank"><p>Uploaded Document</p></a>
                        </div>
                    </div>
                </div>
              </div>
			  
            </div>

            <div class="row mtop20">
              <div class="col-md-12">
			 
			  
			  
                <div class="panel panel-default">
                  <div class="alert alert-warning" onclick="toggleSection('#company-settings');return false;">
                  <i class="fa fa-users"></i> Company Settings <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span>
                  </div>
                  <div id="company-settings" class="panel-body tw-bg-neutral-100" style="display:none;">
				  
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/todays_thought');?>" class="btn btn-warning mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('todays_thought');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/events_announcements');?>" class="btn btn-warning mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('events_announcements');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/company_policies');?>" class="btn btn-warning mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('company_policies');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/corporate_guidelines');?>" class="btn btn-warning mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('corporate_guidelines');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/holiday_list');?>" class="btn btn-warning mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('holiday_list');?></a> 
		  </div>	
                  </div>
                </div>
				

                <div class="panel panel-default">
                  <div class="alert alert-success" onclick="toggleSection('#attendance-settings');return false;">
                    <i class="fa-solid fa-calendar-days menu-icon tw-mr-2"></i> Attendance Settings <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span>
                  </div>
                  <div id="attendance-settings" class="panel-body" style="display:none;">
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/shift_type');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('shift_type');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/shift_manager');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('shift_manager');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/employee_type');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('employee_type');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/branch_manager');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('branch_manager');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/attendance_status');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('attendance_status');?></a> 
		  </div>	
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/staff_manager');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('staff_manager');?></a> 
</div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/staff_type');?>" class="btn btn-success mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('staff_type');?></a> 
</div>	
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="alert alert-danger" onclick="toggleSection('#leave-settings');return false;">
                    <i class="fa-solid fa-file-pen menu-icon tw-mr-2"></i> Leave Settings <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span>
                  </div>
                  <div id="leave-settings" class="panel-body" style="display:none;">
				  
<div class="col-sm-3 tw-my-2"> <a target="_blank"  href="<?php echo admin_url('hrd/setting/leave_balance');?>" class="btn btn-danger mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('leave_balance');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank"  href="<?php echo admin_url('hrd/setting/leave_type');?>" class="btn btn-danger mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('leave_type');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/leave_rule');?>" class="btn btn-danger mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('leave_rule');?></a> 
		  </div>
                  </div>
                </div>

                <div class="panel panel-default">
                  <div class="alert alert-info" onclick="toggleSection('#interviews-settings');return false;">
                    <i class="fa-solid fa-comments menu-icon tw-mr-2"></i> Interviews Settings <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span>
                  </div>
                  <div id="interviews-settings" class="panel-body" style="display:none;">
                    <div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/interview_process');?>" class="btn btn-info mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('interview_process');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a target="_blank" href="<?php echo admin_url('hrd/setting/interview_source');?>" class="btn btn-info mbot15 tw-w-full tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-check tw-mx-2"></i><?php echo _l('interview_source');?></a> 
		  </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row mtop20">
              <div class="col-md-12">
				<h4 class="panel-title tw-my-2"><i class="fa-solid fa-users tw-mr-2"></i>Today Present Employee List</h4>
                <div class="table-responsive">
                  <table class="table dt-table" data-order-col="0" data-order-type="asc">
                    <thead>
                      <tr>
                        <th>Employee</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>First Half</th>
                        <th>Second Half</th>
                        <th>Total Hrs</th>
                        <th>LateMark</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($present_rows)) { foreach ($present_rows as $r) { ?>
                        <tr>
                          <td><?php echo e(($r['firstname']??'').' '.($r['lastname']??'')); ?></td>
                          <td><?php echo e($r['in_time'] ?? ''); ?></td>
                          <td><?php echo e($r['out_time'] ?? ''); ?></td>
                          <td><?php echo get_attendance_status_title((int)e($r['first_half'] ?? '')); ?></td>
                          <td><?php echo get_attendance_status_title((int)e($r['second_half'] ?? '')); ?></td>
                          <td><?php echo e($r['total_hours'] ?? ''); ?></td>
                          <td><?php echo e($r['late_mark'] ?? ''); ?></td>
                        </tr>
                      <?php } } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<style>
.widget-card {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.widget-card-body {
    display: flex;
    align-items: center;
}

.widget-card-icon {
    font-size: 2.5em;
    margin-right: 15px;
    opacity: 0.8;
}

.widget-card-content h3 {
    margin: 0;
    font-size: 2em;
    font-weight: bold;
}

.widget-card-content p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}
.alert {
     margin-bottom: 0px !important;
	 }

</style>
<script>
function toggleSection(id){
  var el = document.querySelector(id);
  if(!el) return;
  el.style.display = (el.style.display === 'none' || el.style.display === '') ? 'block' : 'none';
}
</script>
<?php init_tail(); ?>
</body></html>


