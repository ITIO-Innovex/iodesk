<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="tw-mb-4 tw-font-semibold tw-text-lg"><i class="fa-solid fa-gear"></i> CRM Setup</h4>
                        <div class="alert alert-info">
                            This is the CRM setup page. The categories below are placeholders to help you configure each module.
                        </div>
                       
                        <div class="row">
                            
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Company</strong>
                                        </div>
                                        <div class="panel-body">
<?php
$company_details = $company_details ?? [];
$company_logo = $company_details['company_logo'] ?? '';
$company_favicon = $company_details['favicon'] ?? '';
$company_website = $company_details['website'] ?? '';
$companyname = $company_details['companyname'] ?? '';
$nda_url = $company_details['nda_url'] ?? '';
$settings = $company_details['settings'] ?? '';
$nda_smtp = $company_details['nda_smtp'] ?? '';
$direct_mail_smtp = $company_details['direct_mail_smtp'] ?? '';
?>


<?php
if (isset($company_logo)&&$company_logo&&isset($company_website)&&$company_website&&isset($companyname)&&$companyname) { ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th style="width: 220px;">Company Name</th>
                <td><?php echo e($company_details['companyname'] ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Website</th>
                <td>
                    <?php if (!empty($company_website)) { ?>
                        <a href="<?php echo e($company_website); ?>" target="_blank" rel="noopener">
                            <?php echo e($company_website); ?>
                        </a>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>Company Logo</th>
                <td>
                    <?php if (!empty($company_logo)) { ?>
                        <img src="<?php echo base_url('uploads/company/' . $company_logo); ?>" alt="Company Logo" style="max-height: 40px; max-width: 160px;" class="img-thumbnail" />
                        <div class="text-muted"><?php echo e($company_logo); ?></div>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th>Favicon</th>
                <td>
                    <?php if (!empty($company_favicon)) { ?>
                        <img src="<?php echo base_url('uploads/company/' . $company_favicon); ?>" alt="Favicon" style="max-height: 32px; max-width: 32px;" class="img-thumbnail" />
                        <div class="text-muted"><?php echo e($company_favicon); ?></div>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
            <?php /*?><tr>
                <th>Settings</th>
                <td><pre class="tw-text-xs tw-mb-0"><?php echo e($company_details['settings'] ?? '-'); ?></pre></td>
            </tr><?php */?>
             <?php /*?><tr>
                <th>NDA URL</th>
                <td>
                    <?php if (!empty($nda_url)) { ?>
                        <a href="<?php echo e($nda_url); ?>" target="_blank" rel="noopener">
                            <?php echo e($nda_url); ?>
                        </a>
                    <?php } else { ?>
                        -
                    <?php } ?>
                </td>
            </tr>
           <tr>
                <th>NDA SMTP</th>
                <td><?php echo e($company_details['nda_smtp'] ?? '-'); ?></td>
            </tr>
            <tr>
                <th>Direct Mail SMTP</th>
                <td><?php echo e($company_details['direct_mail_smtp'] ?? '-'); ?></td>
            </tr><?php */?>
        </tbody>
    </table>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Manage your company name, domain, logo, favicon, and other branding details. etc <span style="float:right"><a href="<?php echo admin_url('customize');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Company Profile</a></span></div>
</div>
<?php } ?>

<?php if (!empty($nda_url)) { ?>
<div class="alert alert-success">
<a href="<?php echo e($nda_url); ?>" target="_blank" rel="noopener">
                            <?php echo e($nda_url); ?>
                        </a>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure your NDA URL used for sending NDA emails from Leads and Deals.  <span style="float:right"><a href="<?php echo admin_url('customize');?>" class="btn btn-warning btn-sm ms-2" target="_blank">NDA URL</a></span></div>
</div>
<?php } ?>
                                        
										
										
										
										</div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Email SMTP Setup</strong>
                                        </div>
                                        <div class="panel-body">
										
<?php
if (isset($settings)&&$settings) { ?>
<div class="alert alert-success">
Added Global Email SMTP Details
</div>
<?php } else { ?>										
										<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure SMTP details for sending all internal system emails. <span style="float:right"><a href="<?php echo admin_url('customize');?>" class="btn btn-warning btn-sm ms-2">Global Email</a></span></div>
                  </div>
<?php } ?>				  

<?php
if (isset($direct_mail_smtp)&&$direct_mail_smtp) { ?>
<div class="alert alert-success">
Added Direct Email SMTP Details
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure SMTP details for sending direct emails. <span style="float:right"><a href="<?php echo admin_url('customize/smtp_setting');?>" class="btn btn-warning btn-sm ms-2">Direct Email</a></span></div>
</div>
				  
<?php } ?>				  

<?php
if (isset($nda_smtp)&&$nda_smtp) { ?>
<div class="alert alert-success">
Added NDA Email SMTP Details
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure SMTP details for sending NDA emails from Leads and Deals. <span style="float:right"><a href="<?php echo admin_url('customize/smtp_setting');?>" class="btn btn-warning btn-sm ms-2">NDA Email</a></span></div>
                  </div>
<?php } ?>				  
                                            
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Staff Module</strong>
                                        </div>
                                        <div class="panel-body">
<?php
$department_count = (int) ($active_department_count ?? 0);
if ($department_count > 0) { ?>
<div class="alert alert-success">
Active department count: <?php echo $department_count; ?>
</div>
<?php } else { ?>
										<div class="alert alert-danger tw-bg-danger-500 msgbox_department">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add departments before creating staff members. <span style="float:right"><a href="<?php echo admin_url('departments');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add New Departments</a></span></div>
                  </div>
<?php } ?>
<?php
$designation_count = (int) ($active_designation_count ?? 0);
if ($designation_count > 0) { ?>
<div class="alert alert-success">
Active designation count: <?php echo $designation_count; ?>
</div>
<?php } else { ?>
				  						<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add designations before creating staff members. <span style="float:right"><a href="<?php echo admin_url('designation');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add New Designation</a></span></div>
                  </div>
<?php } ?>
<?php
$staff_type_count = (int) ($active_staff_type_count ?? 0);
if ($staff_type_count > 0) { ?>
<div class="alert alert-success">
Active staff type count: <?php echo $staff_type_count; ?>
</div>
<?php } else { ?>
				 						<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add staff type to activate staff-related features. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/staff_type');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add New Staff Type</a></span></div>
                  </div>
<?php } ?>
                                            
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Leads / Deals</strong>
                                        </div>
                                        <div class="panel-body">
<?php /*?><?php
$lead_source_count = (int) ($active_lead_source_count ?? 0);
if ($lead_source_count > 0) { ?>
<div class="alert alert-success">
Active lead source count: <?php echo $lead_source_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500 msgbox_department">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add lead sources before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/sources');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Lead Source</a></span></div>
</div>
<?php } ?><?php */?>
<?php
$task_status_count = (int) ($active_task_status_count ?? 0);
if ($task_status_count > 0) { ?>
<div class="alert alert-success">
Active task status count: <?php echo $task_status_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> PPlease add task statuses before using the Leads module. <span style="float:right"><a href="<?php echo admin_url('leads/task_status');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Task Status</a></span></div>
</div>
<?php } ?>
<?php
$deal_stage_count = (int) ($active_deal_stage_count ?? 0);
if ($deal_stage_count > 0) { ?>
<div class="alert alert-success">
Active deal stage count: <?php echo $deal_stage_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add deal stages before using the Leads module. <span style="float:right"><a href="<?php echo admin_url('leads/deal_stage');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add New Deal Stage</a></span></div>
</div>
<?php } ?>
				  
<?php /*?>				  <div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add lead form before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/forms');?>" class="btn btn-warning btn-sm ms-2">Add New Leads Forms</a></span></div>
                  </div><?php */?>
                                            
                                        </div>
                                    </div>
                                </div>
								
								
								
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>HRMS Setup</strong>
                                        </div>
                                        
 <div class="panel-body">										
										
<?php
$shift_type_count = (int) ($active_shift_type_count ?? 0);
if ($shift_type_count > 0) { ?>
<div class="alert alert-success">
Active shift type count: <?php echo $shift_type_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add shift types to manage attendance. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/shift_type');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Shift Type</a></span></div>
</div>
<?php } ?>
                         
										
<?php
$shift_manager_count = (int) ($active_shift_manager_count ?? 0);
if ($shift_manager_count > 0) { ?>
<div class="alert alert-success">
Active shift manager count: <?php echo $shift_manager_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add shift managers to manage attendance. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/shift_manager');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Shift Manager</a></span></div>
</div>
<?php } ?>
                                   
										
										
<?php
$employee_type_count = (int) ($active_employee_type_count ?? 0);
if ($employee_type_count > 0) { ?>
<div class="alert alert-success">
Active employee type count: <?php echo $employee_type_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add employee types to manage staff records. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/employee_type');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Employee Type</a></span></div>
</div>
<?php } ?>
                                      

<?php
$branch_manager_count = (int) ($active_branch_manager_count ?? 0);
if ($branch_manager_count > 0) { ?>
<div class="alert alert-success">
Active branch manager count: <?php echo $branch_manager_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add branch managers to manage attendance. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/branch_manager');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Branch Manager</a></span></div>
</div>
<?php } ?>

										

<?php
$leave_type_count = (int) ($active_leave_type_count ?? 0);
if ($leave_type_count > 0) { ?>
<div class="alert alert-success">
Active leave type count: <?php echo $leave_type_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add leave types to manage employee leave. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/leave_type');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Leave Type</a></span></div>
</div>
<?php } ?>


<?php
$leave_rule_count = (int) ($active_leave_rule_count ?? 0);
if ($leave_rule_count > 0) { ?>
<div class="alert alert-success">
Active leave rule count: <?php echo $leave_rule_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add interview rules to manage interviews. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/leave_rule');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Leave Rule</a></span></div>
</div>
<?php } ?>

  </div>										
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Project</strong>
                                        </div>
                                        <div class="panel-body">
<?php
$project_group_count = (int) ($active_project_group_count ?? 0);
if ($project_group_count > 0) { ?>
<div class="alert alert-success">
Active project group count: <?php echo $project_group_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add project groups to organize and manage your projects effectively. <span style="float:right"><a href="<?php echo admin_url('project/project_group');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Project Group</a></span></div>
</div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Interview</strong>
                                        </div>

                                        <div class="panel-body">
<?php 
$interview_process_count = (int) ($active_interview_process_count ?? 0);
if($interview_process_count > 0){ ?>
<div class="alert alert-success">
Active interview process count: <?php echo $interview_process_count; ?>
</div>
<?php } else{ ?>
 <div class="alert alert-danger tw-bg-danger-500">
 <div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add interview processes to manage interviews. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/interview_process');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Interview Process</a></span></div>
 </div>
<?php } ?>
                                        
<?php
$interview_source_count = (int) ($active_interview_source_count ?? 0);
if ($interview_source_count > 0) { ?>
<div class="alert alert-success">
Active interview source count: <?php echo $interview_source_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add interview sources to track interview origins. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/interview_source');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Interview Source</a></span></div>
</div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>API Keys</strong>
                                        </div>
                                        <div class="panel-body">
<?php
$ai_details_count = (int) ($active_ai_details_count ?? 0);
if ($ai_details_count > 0) { ?>
<div class="alert alert-success">
Chatgtp API Key count: <?php echo $ai_details_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure your ChatGPT API key to enable AI-powered support. <span style="float:right"><a href="<?php echo admin_url('ai_content_generator');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Chatgtp API Key</a></span></div>
</div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>
								
								
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
</body>
</html>
