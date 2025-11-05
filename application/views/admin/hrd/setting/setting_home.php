<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
  
    <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <i class="fa fa-cog menu-icon tw-mr-2"></i>Settings
                    </h4>
                </div>
            </div>
    <div class="row">
	  <div class="col-sm-12" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
	  
        
<div class="col-sm-12 tw-my-2"><h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-warning-300"><i class="fa-solid fa-comments menu-icon tw-mr-2"></i>Interviews</h4></div>                        
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/interview_process');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('interview_process');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/interview_source');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('interview_source');?></a> 
		  </div>
		  
<div class="col-sm-12 tw-my-2"><h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-warning-300"><i class="fa-solid fa-file-pen menu-icon tw-mr-2"></i>Leave</h4></div>                        
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/leave_type');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('leave_type');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/leave_rule');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('leave_rule');?></a> 
		  </div>
		  
<div class="col-sm-12 tw-my-2"><h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-warning-300"><i class="fa-solid fa-calendar-days menu-icon tw-mr-2"></i>Attendance</h4></div>                        
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/shift_type');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('shift_type');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/shift_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('shift_manager');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/employee_type');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('employee_type');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/branch_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('branch_manager');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/attendance_status');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('attendance_status');?></a> 
		  </div>	
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/staff_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('staff_manager');?></a> 
		  </div>	  
		
<div class="col-sm-12 tw-my-2"><h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-warning-300"><i class="fa-solid fa-warehouse menu-icon tw-mr-2"></i>Company</h4></div>                        
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/todays_thought');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('todays_thought');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/events_announcements');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('events_announcements');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/company_policies');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('company_policies');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/corporate_guidelines');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('corporate_guidelines');?></a> 
		  </div>
<div class="col-sm-3 tw-my-2"> <a href="<?php echo admin_url('hrd/setting/holiday_list');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-gears tw-mx-2"></i><?php echo _l('holiday_list');?></a> 
		  </div>	  
        
      
	  </div>
	  
	 
    </div>
	
	

  </div>
</div>
<?php init_tail(); ?>
</body></html>