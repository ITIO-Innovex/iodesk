<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa fa-home menu-icon tw-mr-2 "></i>  Self Service</h4>
    <div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6">
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-user tw-mx-2"></i> Profile</h4>
        <ul class="reports tw-space-y-1">
		  <li> <a href="<?php echo admin_url('hrd/profile');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>My Profile</a> </li>
		  
		  
		  
		  <li> <a href="<?php echo admin_url('hrd/leave_balance_register');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Register</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/my_document');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>My Document</a> 
		  </li>
		  
        </ul>
      </div>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Quick Info</h4>
        <ul class="reports tw-space-y-1">
          <?php /*?><li> <a href="<?php echo admin_url('hrd/leave_balance');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Balance</a> 
		  </li><?php */?>
		  <?php /*?><li> <a href="<?php echo admin_url('hrd/leave_balance_register');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Register</a> 
		  </li><?php */?>
		  
		  <li> <a href="<?php echo admin_url('hrd/awards');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Awards</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/holidays_list');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Holidays list</a> 
		  </li>
        </ul>
      </div>
	  <?php if(is_admin()){?>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-hand-holding-dollar tw-mx-2"></i> Salary</h4>
		
        <ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('payroll/ctc');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>CTC</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('payroll/salary_slip');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Pay Slip</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('payroll/download_salary_slip');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Download Pay Slip</a> 
		  </li>
		 
        </ul>
		
      </div>
	  <?php } ?>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-folder-tree tw-mx-2"></i> Workflow</h4>
        <ul class="reports tw-space-y-1">
		<li> <a href="<?php echo admin_url('hrd/approver');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>My Approver</a> </li>
		
          <li> <a href="<?php echo admin_url('hrd/organization_chart');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Organization Chart</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/organization_chart_collapsible');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>ORG Chart Corporate View</a> 
		  </li>
		 
        </ul>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>

