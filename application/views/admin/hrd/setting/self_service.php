<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa fa-home menu-icon tw-mr-2 "></i>  Self Service</h4>
    <div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6">
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Attendance</h4>
		
		<ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('hrd/manage_attendance_by_date');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Manage Attendance By Date</a> 
		  </li>
		  <?php /*?><li> <a href="<?php echo admin_url('hrd/manage_attendance_master');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Manage Attendance By Month</a> <?php */?>
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/manage_attendance_maestro');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Manage Attendance Maestro</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/manage_attendance_by_user');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Manage Attendance By User</a> 
		  </li>
		   <li> <a href="<?php echo admin_url('hrd/attendance_master');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Attendance Master</a> 
		  </li>
        </ul>
		
		
        
      </div>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i>  Requests</h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('hrd/setting/attendance_request');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i> Attendance Request</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/leave_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Manager</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/uploaded_document');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Uploaded Document</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/uploaded_document_by_user');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Uploaded Document By User</a> 
		  </li>
        </ul>
      </div>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i>  Reports </h4>
        <ul class="reports tw-space-y-1">
         <ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('hrd/setting/top_10_leave_takers');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Top 10 Leave Takers</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/setting/shift_wise_employee_count');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Shift Wise Employee Count</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/setting/top_10_employee_having_late_mark');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Top 10 Employee having Late Mark</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/setting/list_of_employee_early_going');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>List of Employee Early Going</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/setting/employee_count_analysis');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Employee Count Analysis</a> 
		  </li>
        </ul>
		 
        </ul>
      </div>
	  
	  
    </div><br />

	<div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6">
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Interview</h4>
        <ul class="reports tw-space-y-1">
		
		<li> <a href="<?php echo admin_url('hrd/interviews');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Manage Interviews</a> 
		  </li>
      
		  <li> <a href="<?php echo admin_url('hrd/setting/interview_process');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Interview Process</a> 
		  </li>
		   <li> <a href="<?php echo admin_url('hrd/setting/interview_source');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Interview Source</a> 
		  </li>
		  
        </ul>
      </div>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Company </h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('hrd/setting/todays_thought');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Today`s Thought</a> 
		  </li>
		  
		  <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/events_announcements');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Events and Announcements</a> 
		  </li>
		  <?php } ?>
		  <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/company_policies');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Company Policies</a> 
		  </li>
		  <?php } ?>
		  <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/corporate_guidelines');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Corporate Guidelines</a> 
		  </li>
		  <?php } ?>
		  <li> <a href="<?php echo admin_url('hrd/setting/holiday_list');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Holiday List</a> 
		  </li>
        </ul>
      </div>
	 
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i>  Leave &  DAR </h4>
        <ul class="reports tw-space-y-1">
          <?php /*?><li> <a href="<?php echo admin_url('hrd/setting/leave_balance');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Balance</a> 
		  </li><?php */?>
		  <li> <a href="<?php echo admin_url('hrd/leave_register');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Register</a> 
		  </li>
		   <?php if(is_super()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/leave_type');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Type</a> 
		  </li>
		  <?php } ?>
		   <li> <a href="<?php echo admin_url('hrd/setting/leave_rule');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leave Rule</a> 
		  </li>
		  
		  <li> <a href="<?php echo admin_url('hrd/setting/dar_master');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>DAR Manager</a> 
		  </li>
		 
        </ul>
      </div>
	  
    </div>
	<br />

	<div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6">
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Shift & Branch</h4>
        <ul class="reports tw-space-y-1">
		<?php if(is_department_admin() || is_admin()){?>
		<li> <a href="<?php echo admin_url('hrd/setting/shift_type');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Shift Type</a> 
		  </li>
		  <?php } ?>
		  <?php if(is_department_admin() || is_admin()){?>
		   <li> <a href="<?php echo admin_url('hrd/setting/shift_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Shift Manager</a> 
		  </li>
          <?php } ?>
	      <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/employee_type');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Employee Type</a> 
		  </li>
		  <?php } ?>
		   <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/branch_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Branch Manager</a> 
		  </li>
		  <?php } ?>
		  <?php if(is_super()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/attendance_status');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Attendance Status</a> 
		  </li>
		  <?php } ?>
		  
        </ul>
      </div>
	  <?php $display=1; if(is_admin() && $display==2 ){?>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Payroll</h4>
        <ul class="reports tw-space-y-1">
		
		
		  
		   <li> <a href="<?php echo admin_url('payroll/setting/generate_salary_slip');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Generate Salary Slip</a> 
		  </li>
		  
		  <li> <a href="<?php echo admin_url('payroll/setting/generated_salary_slip');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Generated Salary Slip</a> 
		  </li>
          
	      
		  <li> <a href="<?php echo admin_url('payroll/setting/ctc');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>CTC</a> 
		  </li>
		 
		  
		  <li> <a href="<?php echo admin_url('payroll/setting/components');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Components</a> 
		  </li>
		  
		  
        </ul>
      </div>
	 <?php } ?> 
	  
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-file-text tw-mx-2"></i> Settings</h4>
        <ul class="reports tw-space-y-1">
		
		<li> <a href="<?php echo admin_url('hrd/staff_manager');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Staff Manager</a> 
		  </li>
		  <?php if(is_department_admin() || is_admin()){?>
		   <li> <a href="<?php echo admin_url('departments');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Departments</a> 
		  </li>
          <?php } ?>
	      <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('designation');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Designation</a> 
		  </li>
		  <?php } ?>
		  <?php if(is_department_admin() || is_admin()){?>
		  <li> <a href="<?php echo admin_url('hrd/setting/awards');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Awards / Gallery</a> 
		  </li>
		  <?php } ?>
		  
        </ul>
      </div>
	  <?php if(get_staff_company_id()==8){ ?>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-id-card tw-mx-2"></i> Joining Forms</h4>
        <ul class="reports tw-space-y-1">
		<li> <a href="<?php echo admin_url('hrd/setting/employee_details_form');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Employee Details Form</a> </li>
		
          <li> <a href="<?php echo admin_url('hrd/setting/job_application_form');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Job Application Form</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/setting/joining_form');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Joining Form</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('hrd/setting/kyc_form');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>KYC Form</a> 
		  </li>
        </ul>
      </div>
	  <?php } ?> 
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>

