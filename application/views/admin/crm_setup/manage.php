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
                            This is a demo CRM setup page. The categories below are placeholders.
                        </div>
                       
                        <div class="row">
                            
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Company</strong>
                                        </div>
                                        <div class="panel-body">
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Manage your Company Name, Domain, Logo, Favicon etc <span style="float:right"><a href="<?php echo admin_url('customize');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Company Profile</a></span></div>
</div>
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Email SMTP Setup</strong>
                                        </div>
                                        <div class="panel-body">
										
										<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Setup SMTP Details for Send all internal Email <span style="float:right"><a href="<?php echo admin_url('customize');?>" class="btn btn-warning btn-sm ms-2">Global Email</a></span></div>
                  </div>
				  						<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Setup SMTP Details for Send Direct Email <span style="float:right"><a href="<?php echo admin_url('customize/smtp_setting');?>" class="btn btn-warning btn-sm ms-2">Direct Email</a></span></div>
                  </div>
				 						<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Setup SMTP Details for Send NDA Email from Leads / Deals <span style="float:right"><a href="<?php echo admin_url('customize/smtp_setting');?>" class="btn btn-warning btn-sm ms-2">NDA Email</a></span></div>
                  </div>
				  
                                            
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Staff Module</strong>
                                        </div>
                                        <div class="panel-body">
										<div class="alert alert-danger tw-bg-danger-500 msgbox_department">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add departments before creating staff. <span style="float:right"><a href="<?php echo admin_url('departments');?>" class="btn btn-warning btn-sm ms-2">Add New Departments</a></span></div>
                  </div>
				  						<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add designation before creating staff. <span style="float:right"><a href="<?php echo admin_url('designation');?>" class="btn btn-warning btn-sm ms-2">Add New Designation</a></span></div>
                  </div>
				 						<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add New Staff before creating staff. <span style="float:right"><a href="<?php echo admin_url('hrd/setting/staff_type');?>" class="btn btn-warning btn-sm ms-2">Add New Staff Type</a></span></div>
                  </div>
                                            
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Leads / Deals</strong>
                                        </div>
                                        <div class="panel-body">
										<div class="alert alert-danger tw-bg-danger-500 msgbox_department">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add lead sources before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/sources');?>" class="btn btn-warning btn-sm ms-2">Add Lead Source</a></span></div>
                  </div>
				  						<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add task status before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/task_status');?>" class="btn btn-warning btn-sm ms-2">Add Task Status</a></span></div>
                  </div>
				 						<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add deal stage before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/deal_stage');?>" class="btn btn-warning btn-sm ms-2">Add New Deal Stage</a></span></div>
                  </div>
				  
				  <div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add lead form before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/forms');?>" class="btn btn-warning btn-sm ms-2">Add New Leads Forms</a></span></div>
                  </div>
                                            
                                        </div>
                                    </div>
                                </div>
								
								
								<?php /*?><div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>HRMS Module</strong>
                                        </div>
                                        <div class="panel-body">
										<div class="alert alert-danger tw-bg-danger-500 msgbox_department">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add departments before creating staff. <span style="float:right"><a href="#" class="btn btn-warning btn-sm ms-2">Add New Designation</a></span></div>
                  </div>
				  						<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add designation before creating staff. <span style="float:right"><a href="#" class="btn btn-warning btn-sm ms-2">Add New Designation</a></span></div>
                  </div>
				 						<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add New Staff before creating staff. <span style="float:right"><a href="#" class="btn btn-warning btn-sm ms-2">Add New Staff Type</a></span></div>
                  </div>
                                            
                                        </div>
                                    </div>
                                </div><?php */?>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>Project</strong>
                                        </div>
                                        <div class="panel-body">
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add project groups to structure and manage your projects. <span style="float:right"><a href="<?php echo admin_url('project/project_group');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Project Group</a></span></div>
</div>
                                        </div>
                                    </div>
                                </div>
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong>API Keys</strong>
                                        </div>
                                        <div class="panel-body">
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Chatgtp API Key for AI Support <span style="float:right"><a href="<?php echo admin_url('ai_content_generator');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Chatgtp API Key</a></span></div>
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
</div>
<?php init_tail(); ?>
</body>
</html>
