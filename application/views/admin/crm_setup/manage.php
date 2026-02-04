<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #crm-setup-accordion .panel-title > a {
    display: block;
    position: relative;
    padding-right: 28px;
  }
  #crm-setup-accordion .panel-title > a::after {
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    content: "\f106";
    position: absolute;
    right: 0;
    top: 2px;
    color: #6b7280;
    transition: transform 0.2s ease;
  }
  #crm-setup-accordion .panel-title > a.collapsed::after {
    content: "\f107";
  }
  .box99 { box-shadow: rgba(0, 0, 0, 0.17) 0px -23px 25px 0px inset, rgba(0, 0, 0, 0.15) 0px -36px 30px 0px inset, rgba(0, 0, 0, 0.1) 0px -79px 40px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px, rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px, rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px;
  }
  .panel-heading {
  padding:25px !important;
  background-image: linear-gradient(to right, #BA8B02 0%, #899348  51%, #BA8B02  100%) !important;
  }
  .panel-heading {
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;            
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
            display: block;
          }
 .panel-heading:hover {
            background-position: right center; /* change the direction of the change here */
            color: #fff;
            text-decoration: none;
          }
         
</style>
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
                                <!--// For Company Profile-->
								
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-building"></i> Company</strong>
                                        </div>
                                        <div class="panel-body">
<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can add or update the Company Name, Website URL, Logo, and Favicon.
	
</div>

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
$global_smtp_config = $settings ? json_decode($settings, true) : [];
$direct_smtp_config = $direct_mail_smtp ? json_decode($direct_mail_smtp, true) : [];
$nda_smtp_config = $nda_smtp ? json_decode($nda_smtp, true) : [];
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
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Manage your company name, domain, logo, favicon, and other branding details. etc <span style="float:right"><a href="javascript:void(0);" id="company_profile" class="btn btn-warning btn-sm ms-2" target="_blank">Add Company Profile</a></span></div>
</div>
<?php } ?>

<div class="modal fade" id="company_profile_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open_multipart(admin_url('crm_setup/save_company_profile'), ['id' => 'company-profile-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Company Profile</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="companyname" class="control-label">Company Name</label>
          <input type="text" class="form-control" id="companyname" name="companyname" value="<?php echo e($companyname); ?>" required>
        </div>
        <div class="form-group">
          <label for="company_website" class="control-label">Website</label>
          <input type="text" class="form-control" id="company_website" name="website" value="<?php echo e($company_website); ?>">
        </div>
        <div class="form-group">
          <label for="company_logo" class="control-label">Company Logo</label>
          <input type="file" class="form-control" id="company_logo" name="company_logo" accept="image/*">
        </div>
        <div class="form-group">
          <label for="company_favicon" class="control-label">Favicon</label>
          <input type="file" class="form-control" id="company_favicon" name="favicon" accept="image/x-icon,image/png">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>


                                        
										
										
										
										</div>
                                    </div>
                                </div>
								
								<!--// For Email Setup -->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-at"></i> Email SMTP Setup</strong>
                                        </div>
                                        <div class="panel-body">
										
<div class="smtp-instructions">
    <h4>SMTP Configuration Instructions</h4>

    <h5>1. SMTP Types</h5>
    <ul>
        <li><strong>Global SMTP Details:</strong> Used for all system-generated emails (notifications, alerts, system emails).</li>
        <li><strong>Direct Email SMTP Details:</strong> Used for sending emails directly from the CRM.</li>
        <li><strong>NDA Email SMTP Details:</strong> Used specifically for sending NDA emails from Leads / Deals.</li>
    </ul>

    <h5>2. Required SMTP Field Names</h5>
    <ul>
        <li>SMTP Host (e.g., smtp.gmail.com, smtp.zoho.com)</li>
        <li>SMTP Port (e.g., 587, 465)</li>
        <li>SMTP Username (Email address)</li>
        <li>SMTP Password (Email password or App Password)</li>
        <li>Encryption Type (SSL / TLS)</li>
        <li>From Email Address</li>
        <li>From Name</li>
    </ul>

    <h5>3. How to Get SMTP Details</h5>
    <ul>
        <li><strong>Gmail:</strong> Enable 2-Step Verification and generate an App Password.</li>
        <li><strong>Zoho Mail:</strong> Mail Settings <i class="fa-solid fa-arrow-right-long"></i> Mail Accounts <i class="fa-solid fa-arrow-right-long"></i> IMAP/POP <i class="fa-solid fa-arrow-right-long"></i> Generate App Password.</li>
        <li><strong>Outlook / Office 365:</strong> Enable SMTP Authentication and use App Password if MFA is enabled.</li>
    </ul>

    <h5>4. Permission & Access Check</h5>
    <ul>
        <li>Administrator permission is required.</li>
        <li>SMTP access must be enabled for the email account.</li>
        <li>Hosting/server must allow outbound SMTP ports (587 or 465).</li>
    </ul>

    <h5>5. Test SMTP Configuration</h5>
    <ul>
        <li>Save SMTP details.</li>
        <li>Use the <strong>Test Email</strong> option.</li>
        <li>Confirm successful email delivery.</li>
    </ul>

    <h5>6. Common Issues</h5>
    <ul>
        <li>Incorrect credentials or missing App Password</li>
        <li>Wrong SMTP port or encryption type</li>
        <li>SMTP blocked by hosting provider</li>
        <li>Email provider SMTP access disabled</li>
    </ul>
</div>

										
<?php
if (isset($settings)&&$settings) { ?>
<div class="alert alert-success">
Added Global Email SMTP Details
</div>
<?php } else { ?>										
										<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure SMTP details for sending all internal system emails. <span style="float:right"><a href="javascript:void(0);" id="global_smtp_setup" class="btn btn-warning btn-sm ms-2">Add Global Email</a></span></div>
                  </div>
<?php } ?>				  

<?php
if (isset($direct_mail_smtp)&&$direct_mail_smtp) { ?>
<div class="alert alert-success">
Added Direct Email SMTP Details
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure SMTP details for sending direct emails. <span style="float:right"><a href="javascript:void(0);" id="smtp_setup" class="btn btn-warning btn-sm ms-2">Add Direct Email SMTP details</a></span></div>
</div>
				  
<?php } ?>				  

<?php
if (isset($nda_smtp)&&$nda_smtp) { ?>
<div class="alert alert-success">
Added NDA Email SMTP Details
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure SMTP details for sending NDA emails from Leads and Deals. <span style="float:right"><a href="javascript:void(0);" id="nda_smtp_setup" class="btn btn-warning btn-sm ms-2">Add NDA Email SMTP details</a></span></div>
                  </div>
<?php } ?>				  
                                            
                                        </div>
                                    </div>
                                </div>
								
								<!--// For Staff Management-->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-users me-2"></i> Staff Module</strong>
                                        </div>
                                        <div class="panel-body">
										
<div class="alert alert-info">
    Here you can add, update, activate, or deactivate, change password and set permission.
	<p><a href="<?php echo admin_url('staff');?>" target="_blank"><?php echo admin_url('staff');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>
<div class="alert alert-danger">
    <strong>Instruction:</strong> Before adding a staff member, please ensure that Department, Designation, and Staff Type are created.
</div>
<?php
$department_count = (int) ($active_department_count ?? 0);
if ($department_count > 0) { ?>
<div class="alert alert-success">
Active department count: <?php echo $department_count; ?>
<span style="float:right"><a href="<?php echo admin_url('departments');?>" class="btn btn-info btn-sm" target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="add_department">Add New Departments</a></span>

</div>
<?php } else { ?>
										<div class="alert alert-danger tw-bg-danger-500 msgbox_department">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add departments before creating staff members. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="add_department">Add New Departments</a></span></div>
                  </div>
<?php } ?>
<?php
$designation_count = (int) ($active_designation_count ?? 0);
if ($designation_count > 0) { ?>
<div class="alert alert-success">
Active designation count: <?php echo $designation_count; ?>
<span style="float:right"><a href="<?php echo admin_url('designation');?>" class="btn btn-info btn-sm" target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="add_designation">Add New Designation</a></span>

</div>
<?php } else { ?>
				  						<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add designations before creating staff members. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="add_designation">Add New Designation</a></span></div>
                  </div>
<?php } ?>
<?php
$staff_type_count = (int) ($active_staff_type_count ?? 0);
if ($staff_type_count > 0) { ?>
<div class="alert alert-success">
Active staff type count: <?php echo $staff_type_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/staff_type');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="add_staff_type">Add New Staff Type</a></span>

</div>
<?php } else { ?>
				 						<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add staff type to activate staff-related features. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="add_staff_type">Add New Staff Type</a></span></div>
                  </div>
<?php } ?>

<div class="form-group">


<ol>
    <li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> To Add Staff</h4>
        <ul>
            <li> - Click on <strong>Add Staff Member</strong>.</li>
            <li> - Fill in all <strong>required fields</strong> and click <strong>Submit</strong>.</li>
			<li> - The <strong>email address must be unique</strong> for each staff member.</li>
			<li> - Send welcome email : When Checked a mail sent on registered email with login details</li>
			<?php /*?><li> <img src="<?php echo base_url('uploads/screenshot/add-staff-profile.png'); ?>" 
                         alt="screenshot" style="max-width: 90%; padding:10px;" />
                    </li><?php */?>
        </ul>
    </li>
	
	<li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> Send Welcome Email</h4>
        <ul>
            <li> - If <strong>Send Welcome Email</strong> is checked,a welcome email with login credentials will be sent to the registered email address.</li>
            
        </ul>
    </li>

    <li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> Staff Role &amp; Permissions</h4>
        <ul>
            <li><strong> - Normal Staff : </strong> : 
No default permissions are assigned.</li>
            <li><strong> - Department Admin : </strong> : 
Can manage staff within their own department.</li>
            <li><strong>Administrator : </strong> : 
Has full system access based on assigned permissions.</li>
        </ul>
    </li>

        <li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> Permission Settings</h4>
        <ul>
            <li> - After submitting staff details, the Permissions tab will appear.</li>
            <li> - Selecting a Role will automatically assign predefined permissions.</li>
            <li> - Manually checked permissions will be available to the staff after login.</li>
			<li> - Only selected permissions will be visible in the staff account.</li>
			<?php /*?><li> <img src="<?php echo base_url('uploads/screenshot/staff-permission.png'); ?>" 
                         alt="screenshot" style="max-width: 90%; padding:10px;" /></li><?php */?>
            
        </ul>
        
    </li>
	
	<li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> To Change Password</h4>
        <ul>
            <li> - Admin must Edit the staff profile.</li>
            <li> - Enter the new password.</li>
            <li> - Click Save to update the password.</li>
        </ul>
        
    </li>
</ol>
				</div>
                                            
                                        </div>
                                    </div>
                                </div>
								
								<!--// For HRMS Management-->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-id-badge me-2"></i> HRMS Setup</strong>
                                        </div>
                                        
 <div class="panel-body">										
	<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can Manage Staff Types, Shifts, Branches & Assignments.
	<p><a href="<?php echo admin_url('hrd/setting/self_service');?>" target="_blank"><?php echo admin_url('hrd/setting/self_service');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>
<div class="alert alert-danger">
    <strong>Instruction:</strong> Before adding a staff member, please ensure that Department, Designation, and Staff Type are created.
</div>									
<?php
$shift_type_count = (int) ($active_shift_type_count ?? 0);
if ($shift_type_count > 0) { ?>
<div class="alert alert-success">
Active shift type count: <?php echo $shift_type_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/shift_type');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="shift-type" >Add Shift Type</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add shift types to manage attendance. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="shift-type" >Add Shift Type</a></span></div>
</div>
<?php } ?>
                         
										
<?php
$shift_manager_count = (int) ($active_shift_manager_count ?? 0);
if ($shift_manager_count > 0) { ?>
<div class="alert alert-success">
Active shift manager count: <?php echo $shift_manager_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/shift_manager');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="add_shift_manager">Add Shift Manager</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add shift managers to manage attendance. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="add_shift_manager">Add Shift Manager</a></span></div>
</div>
<?php } ?>
                                   

<?php
$branch_manager_count = (int) ($active_branch_manager_count ?? 0);
if ($branch_manager_count > 0) { ?>
<div class="alert alert-success">
Active branch manager count: <?php echo $branch_manager_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/branch_manager');?>" class="btn btn-info btn-sm" target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="add_branch_manager">Add Branch Manager</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add branch managers to manage attendance. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="add_branch_manager">Add Branch Manager</a></span></div>
</div>
<?php } ?>

										
<div class="alert alert-warning">
    <strong>Once Staff Types, Shifts, Branches, Departments, and Designations are added, the Manage Staff page will be updated accordingly.
Attendance Check-In / Check-Out will then be displayed on the dashboard and function properly.</strong> 
<p><a href="<?php echo admin_url('hrd/staff_manager');?>" target="_blank"><?php echo admin_url('hrd/staff_manager');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>


<?php
$employee_type_count = (int) ($active_employee_type_count ?? 0);
if ($employee_type_count > 0) { ?>
<div class="alert alert-success">
Active employee type count: <?php echo $employee_type_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/employee_type');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="add_employee_type">Add Employee Type</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add employee types to manage staff records. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="add_employee_type">Add Employee Type</a></span></div>
</div>
<?php } ?>

<?php
$leave_rule_count = (int) ($active_leave_rule_count ?? 0);
if ($leave_rule_count > 0) { ?>
<div class="alert alert-success">
Active leave rule count: <?php echo $leave_rule_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/leave_rule');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="leave-rule">Manage Leave Rule</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Leave rules must be added to manage leave applications. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="leave-rule">Add Leave Rule</a></span></div>
</div>
<?php } ?>

  </div>										
                                    </div>
                                </div>
								
								<!--// For Project Management-->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-diagram-project me-2"></i> Project</strong>
                                        </div>
                                        <div class="panel-body">

<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can add, Update, Comment, and Chat on Projects & Tasks
	<p><a href="<?php echo admin_url('project/dashboard');?>" target="_blank"><?php echo admin_url('project/dashboard');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>
<div class="alert alert-danger">
    <strong>Instruction:</strong> Before adding a project, please ensure that project groups are created.
</div>
<div class="project-instructions">
    <h4>Project Management</h4>

    <h5>1. Project Group Setup</h5>
    <ul>
        <li>Before adding a new project, you must first create a <strong>Project Group</strong>.</li>
    </ul>

    <h5>2. Add New Project</h5>
    <ul>
        <li>Click on <strong>Add New Project</strong>.</li>
        <li>Fill in the required details:</li>
        <ul>
            <li>Project Title, Project Owner, Project Group, Start Date, End Date, Project Details</li>
        </ul>
        <li>All fields are mandatory.</li>
        <li>Click <strong>Submit</strong> to create the project.</li>
    </ul>

    <h5>3. Project Actions</h5>
    <ul>
        <li>Use the <strong>three-dot (<i class="fa-solid fa-ellipsis-vertical"></i>) icon</strong> in the project listing to:</li>
        <ul>
            <li>View, Edit, Delete</li>
        </ul>
    </ul>

    <h5>4. Task Management</h5>
    <ul>
        <li>After creating a project, you can add tasks.</li>
        <li>For each task, you can:</li>
        <ul>
            <li>View, Edit, Delete, Add comments for collaboration</li>

        </ul>
    </ul>

    <h5>5. Collaboration & Activity Tracking</h5>
    <ul>
        <li>All project collaboration and updates appear in the <strong>Activity Feed</strong>.</li>
        <li>Projects and tasks are also visible in the <strong>Calendar View</strong>.</li>
    </ul>

    <h5>6. Project Chat</h5>
    <ul>
        <li>Chat and collaborate with members of the created <strong>Project Group</strong>.</li>
    </ul>
</div>


<?php
$project_group_count = (int) ($active_project_group_count ?? 0);
if ($project_group_count > 0) { ?>
<div class="alert alert-success">
Active project group count: <?php echo $project_group_count; ?>

<span style="float:right"><a href="<?php echo admin_url('project/project_group');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="project_group">Add Project Group</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add project groups to organize and manage your projects effectively. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="project_group">Add Project Group</a></span></div>
</div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>
								
								<!--// For Interview Management-->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-person-booth menu-icon"></i> Interview</strong>
                                        </div>

<div class="panel-body">
<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can add, update, view, and manage interview records.
	<p><a href="<?php echo admin_url('hrd/interviews');?>" target="_blank"><?php echo admin_url('hrd/interviews');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>

<div class="interview-instructions">
    <ul>
        <li>Add interview details by filling in all required fields.</li>
        <li>View interview details at any time.</li>
        <li>Update interview information and change the interview <strong>processing stage</strong> or <strong>status</strong>.</li>
        <li>Send interview emails with the scheduled date, time, and other relevant details.</li>
    </ul>
</div>
<div class="alert alert-danger">
    <strong>Instruction:</strong> Before adding  interview, please ensure that interview processes and interview sources are created.
</div>
<?php 
$interview_process_count = (int) ($active_interview_process_count ?? 0);
if($interview_process_count > 0){ ?>
<div class="alert alert-success">
Active interview process count: <?php echo $interview_process_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/interview_process');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2" id="interview_process">Add Interview Process</a></span>

</div>
<?php } else{ ?>
 <div class="alert alert-danger tw-bg-danger-500">
 <div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add interview processes to manage interviews. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2" id="interview_process">Add Interview Process</a></span></div>
 </div>
<?php } ?>
                                        
<?php
$interview_source_count = (int) ($active_interview_source_count ?? 0);
if ($interview_source_count > 0) { ?>
<div class="alert alert-success">
Active interview source count: <?php echo $interview_source_count; ?>
<span style="float:right"><a href="<?php echo admin_url('hrd/setting/interview_source');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2"  id="interview_source">Add Interview Source</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add interview sources to track interview origins. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2"  id="interview_source">Add Interview Source</a></span></div>
</div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>
								
								<!--// For Chat GTP API KEY-->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-robot me-2"></i> API Keys</strong>
                                        </div>
<div class="panel-body">
<div class="alert alert-info">
    <strong>Instruction:</strong> Get instant help and smart suggestions using AI support.
	<p><a href="<?php echo admin_url('ai_content_generator');?>" target="_blank"><?php echo admin_url('ai_content_generator');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>

                    <ul>
                    <li>ChatGPT-powered AI assistance</li>
                    <li>Works after adding API key</li>
                    <li>
                        API Key Guide:
                        <a href="https://platform.openai.com/api-keys" target="_blank">
                            Get ChatGPT API Key
                        </a>
                    </li>
                </ul>
				
<div class="alert alert-danger">
    <strong>Instruction:</strong> Before using AI support, please ensure that the ChatGPT API key is added.
</div>
<?php
$ai_details_count = (int) ($active_ai_details_count ?? 0);
if ($ai_details_count > 0) { ?>
<div class="alert alert-success">
Chatgtp API Key count: <?php echo $ai_details_count; ?>
<span style="float:right"><a href="<?php echo admin_url('ai_content_generator');?>" class="btn btn-warning btn-sm ms-2" target="_blank">View Added</a></span>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Configure your ChatGPT API key to enable AI-powered support. <span style="float:right"><a href="<?php echo admin_url('ai_content_generator');?>" class="btn btn-warning btn-sm ms-2" target="_blank">Add Chatgtp API Key</a></span></div>
</div>
<?php } ?>
                                        </div>
                                    </div>
                                </div>
								
								<!--// Leads / Deals Management-->
								
								<div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <strong><i class="fa-solid fa-handshake me-2"></i> Leads / Deals</strong>
                                        </div>
                                        <div class="panel-body">
<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can add / Update/ manage your leads.
	<p><a href="<?php echo admin_url('leads');?>" target="_blank"><?php echo admin_url('leads');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>
<div class="alert alert-danger">
    <strong>Instruction:</strong> Before using the Leads module, please ensure that task statuses are configured.
</div>
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

<span style="float:right"><a href="<?php echo admin_url('leads/task_status');?>" class="btn btn-info btn-sm " target="_blank" >View Added</a></span>
<span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm tw-mx-2"  id="task_status">Add Task Status</a></span>

</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500 msgbox_designation">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> To use the Leads module, please configure task statuses first. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2"  id="task_status">Add Task Status</a></span></div>
</div>
<?php } ?>
<?php /*?><?php
$deal_stage_count = (int) ($active_deal_stage_count ?? 0);
if ($deal_stage_count > 0) { ?>
<div class="alert alert-success">
Active deal stage count: <?php echo $deal_stage_count; ?>
</div>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> To use the Leads module, please configure deal stage first. <span style="float:right"><a href="javascript:void(0);" class="btn btn-warning btn-sm ms-2"  id="deal_stage">Add Deal Stage</a></span></div>
</div>
<?php } ?><?php */?>
				  
<?php /*?>				  <div class="alert alert-danger tw-bg-danger-500 msgbox_staff_type">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Please add lead form before using lead module. <span style="float:right"><a href="<?php echo admin_url('leads/forms');?>" class="btn btn-warning btn-sm ms-2">Add New Leads Forms</a></span></div>
                  </div><?php */?>
                                            
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

<div class="modal fade" id="global_smtp_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('direct_email/save_global_smtp'), ['id' => 'global-smtp-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Global SMTP Details</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">SMTP Encryption</label>
          <div>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="ssl" checked> SSL
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="tls"> TLS
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="none"> No Encryption
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="global_smtp_host" class="control-label">SMTP Host</label>
          <input type="text" class="form-control" id="global_smtp_host" name="smtp_host" placeholder="smtp.example.com" required>
        </div>
        <div class="form-group">
          <label for="global_smtp_port" class="control-label">SMTP Port</label>
          <input type="number" class="form-control" id="global_smtp_port" name="smtp_port" placeholder="587" required>
        </div>
        <div class="form-group">
          <label for="global_smtp_email" class="control-label">SMTP Email</label>
          <input type="email" class="form-control" id="global_smtp_email" name="smtp_email" placeholder="no-reply@example.com" required>
        </div>
        <div class="form-group">
          <label for="global_smtp_username" class="control-label">SMTP Username</label>
          <input type="text" class="form-control" id="global_smtp_username" name="smtp_username" placeholder="SMTP Username">
        </div>
        <div class="form-group">
          <label for="global_smtp_password" class="control-label">SMTP Password</label>
          <input type="password" class="form-control" id="global_smtp_password" name="smtp_password" placeholder="SMTP Password">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="direct_smtp_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('direct_email/save_direct_smtp'), ['id' => 'direct-smtp-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Direct SMTP Details</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">SMTP Encryption</label>
          <div>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="ssl" checked> SSL
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="tls"> TLS
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="none"> No Encryption
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="smtp_host" class="control-label">SMTP Host</label>
          <input type="text" class="form-control" id="smtp_host" name="smtp_host" placeholder="smtp.example.com" required>
        </div>
        <div class="form-group">
          <label for="smtp_port" class="control-label">SMTP Port</label>
          <input type="number" class="form-control" id="smtp_port" name="smtp_port" placeholder="587" required>
        </div>
        <div class="form-group">
          <label for="smtp_email" class="control-label">SMTP Email</label>
          <input type="email" class="form-control" id="smtp_email" name="smtp_email" placeholder="no-reply@example.com" required>
        </div>
        <div class="form-group">
          <label for="smtp_username" class="control-label">SMTP Username</label>
          <input type="text" class="form-control" id="smtp_username" name="smtp_username" placeholder="SMTP Username">
        </div>
        <div class="form-group">
          <label for="smtp_password" class="control-label">SMTP Password</label>
          <input type="password" class="form-control" id="smtp_password" name="smtp_password" placeholder="SMTP Password">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="nda_smtp_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('direct_email/save_nda_smtp'), ['id' => 'nda-smtp-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">NDA SMTP Details</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="control-label">SMTP Encryption</label>
          <div>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="ssl" checked> SSL
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="tls"> TLS
            </label>
            <label class="radio-inline">
              <input type="radio" name="smtp_encryption" value="none"> No Encryption
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="nda_smtp_host" class="control-label">SMTP Host</label>
          <input type="text" class="form-control" id="nda_smtp_host" name="smtp_host" placeholder="smtp.example.com" required>
        </div>
        <div class="form-group">
          <label for="nda_smtp_port" class="control-label">SMTP Port</label>
          <input type="number" class="form-control" id="nda_smtp_port" name="smtp_port" placeholder="587" required>
        </div>
        <div class="form-group">
          <label for="nda_smtp_email" class="control-label">SMTP Email</label>
          <input type="email" class="form-control" id="nda_smtp_email" name="smtp_email" placeholder="no-reply@example.com" required>
        </div>
        <div class="form-group">
          <label for="nda_smtp_username" class="control-label">SMTP Username</label>
          <input type="text" class="form-control" id="nda_smtp_username" name="smtp_username" placeholder="SMTP Username">
        </div>
        <div class="form-group">
          <label for="nda_smtp_password" class="control-label">SMTP Password</label>
          <input type="password" class="form-control" id="nda_smtp_password" name="smtp_password" placeholder="SMTP Password">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="interview_source_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('hrd/interviewsource'), ['id' => 'interview-source-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Interview Source</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="source_title" class="control-label">Source Title</label>
          <input type="text" class="form-control" id="source_title" name="name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="interview_process_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('hrd/interviewprocess'), ['id' => 'interview-process-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Interview Process</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="process_title" class="control-label">Process Title</label>
          <input type="text" class="form-control" id="process_title" name="name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="project_group_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('project/projectgroup'), ['id' => 'project-group-form']); ?>
      <input type="hidden" name="inline" value="1">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Project Group</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="group_title" class="control-label">Group Title</label>
          <input type="text" class="form-control" id="group_title" name="name" required>
        </div>
        <div class="form-group">
          <label for="group_color" class="control-label">Group Color</label>
          <input type="color" class="form-control" id="group_color" name="color" value="#757575">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="shift_type_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('hrd/shifttype'), ['id' => 'shift-type-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Shift Type</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="shift_type_title" class="control-label">Shift Type Title</label>
          <input type="text" class="form-control" id="shift_type_title" name="name" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="leave_rule_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/leaverule'), ['id' => 'leave-rule-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Leave Rule'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="leave-rule-additional"></div>
            <?php echo render_input('title', 'Title'); ?>
            <div class="form-group">
              <label for="leave_rule_branch">Branch</label>
              <select name="branch" id="leave_rule_branch" class="form-control">
                <option value="">-- Select Branch --</option>
                <?php if (!empty($branches)) { foreach ($branches as $b) { ?>
                  <option value="<?php echo (int)$b['id']; ?>"><?php echo e($b['branch_name']); ?></option>
                <?php } } ?>
              </select>
            </div>
            <div class="form-group">
              <label for="leave_rule_details">Details</label>
              <textarea name="details" id="leave_rule_details" class="form-control editor" rows="5" required></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="deal_stage_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('leads/dealstage'), ['id' => 'deal-stage-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Deal Stage'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="deal-stage-additional"></div>
            <?php echo render_input('name', 'Stage Title'); ?>
            <?php echo render_color_picker('color', _l('Stage Color')); ?>
            <div class="form-group">
              <label for="deal_stage_status">Status</label>
              <select name="status" id="deal_stage_status" class="form-control">
                <option value="1">Active</option>
                <option value="0">Deactive</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="task_status_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('leads/taskstatus'), ['id' => 'task-status-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Task Status'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="task-status-additional"></div>
            <?php echo render_input('name', 'Status Title'); ?>
            <?php echo render_color_picker('color', _l('Status Color')); ?>
            <?php echo render_input('statusorder', 'leads_status_add_edit_order', total_rows(db_prefix() . 'task_status') + 1, 'number'); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="department_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('departments/department'), ['id' => 'department-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('new_department'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="department-additional"></div>
            <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value="" tabindex="-1" />
            <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value="" tabindex="-1" />
            <?php echo render_input('name', 'department_name'); ?>
            <?php if (get_option('google_api_key') != '') { ?>
              <?php echo render_input('calendar_id', 'department_calendar_id'); ?>
            <?php } ?>
            <div class="checkbox checkbox-primary">
              <input type="checkbox" name="hidefromclient" id="department_hidefromclient">
              <label for="department_hidefromclient"><?php echo _l('department_hide_from_client'); ?></label>
            </div>
            <hr />
            <?php echo render_input('email', 'department_email', '', 'email'); ?>
            <br />
            <h4><?php echo _l('email_to_ticket_config'); ?></h4>
            <br />
            <i class="fa-regular fa-circle-question pull-left tw-mt-0.5 tw-mr-1" data-toggle="tooltip"
               data-title="<?php echo _l('department_username_help'); ?>"></i>
            <?php echo render_input('imap_username', 'department_username'); ?>
            <?php echo render_input('host', 'dept_imap_host'); ?>
            <?php echo render_input('password', 'dept_email_password', '', 'password'); ?>
            <div class="form-group">
              <label for="department_encryption"><?php echo _l('dept_encryption'); ?></label><br />
              <div class="radio radio-primary radio-inline">
                <input type="radio" name="encryption" value="tls" id="department_tls">
                <label for="department_tls">TLS</label>
              </div>
              <div class="radio radio-primary radio-inline">
                <input type="radio" name="encryption" value="ssl" id="department_ssl">
                <label for="department_ssl">SSL</label>
              </div>
              <div class="radio radio-primary radio-inline">
                <input type="radio" name="encryption" value="" id="department_no_enc" checked>
                <label for="department_no_enc"><?php echo _l('dept_email_no_encryption'); ?></label>
              </div>
            </div>
            <div class="form-group">
              <label for="department_folder" class="control-label">
                <?php echo _l('imap_folder'); ?>
                <a href="#" onclick="retrieve_imap_department_folders(); return false;">
                  <i class="fa fa-refresh hidden" id="folders-loader"></i>
                  <?php echo _l('retrieve_folders'); ?>
                </a>
              </label>
              <select name="folder" class="form-control selectpicker" id="department_folder"></select>
            </div>
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input type="checkbox" name="delete_after_import" id="department_delete_after_import">
                <label for="department_delete_after_import"><?php echo _l('delete_mail_after_import'); ?>
              </div>
              <hr />
              <button onclick="test_dep_imap_connection(); return false;" class="btn btn-default">
                <?php echo _l('leads_email_integration_test_connection'); ?>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="designation_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('designation/manage'), ['id' => 'designation-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="designation_modal_label">Add Designation</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="designation_id" value="">
        <div class="form-group">
          <label for="designation_department_id" class="control-label">Department</label>
          <select class="form-control selectpicker" data-live-search="true" name="department_id" id="designation_department_id" data-none-selected-text="Select department">
            <option value="">Select department</option>
            <?php if (!empty($departments)) { foreach ($departments as $dep) { ?>
              <option value="<?php echo (int)$dep['departmentid']; ?>"><?php echo e($dep['name']); ?></option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="designation_title" class="control-label">Designation</label>
          <input type="text" class="form-control" id="designation_title" name="title" placeholder="e.g. Senior Developer">
        </div>
        <div class="checkbox checkbox-primary">
          <input type="checkbox" id="designation_is_active" name="is_active" checked>
          <label for="designation_is_active">Active</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="staff_type_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/stafftype'), ['id' => 'staff-type-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Staff Type'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="staff-type-additional"></div>
            <?php echo render_input('name', 'Staff Type Title'); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="shift_manager_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl">
    <?php echo form_open(admin_url('hrd/shiftmanager'), ['id' => 'shift-manager-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Shift'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div id="shift-manager-additional"></div>
          <div class="col-md-4">
            <?php echo render_input('shift_code', 'Shift Code'); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_input('shift_name', 'Shift Name'); ?>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_in">Shift In</label>
              <input type="time" name="shift_in" id="shift_in" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_out">Shift Out</label>
              <input type="time" name="shift_out" id="shift_out" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <?php echo render_input('grace_period', 'Grace Period (minutes)', '', 'number'); ?>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="shift_type">Shift Type</label>
              <select name="shift_type" id="shift_type" class="form-control">
                <option value="">-- Select Shift Type --</option>
                <?php if (!empty($shift_types)) { foreach ($shift_types as $t) { ?>
                  <option value="<?php echo (int)$t['id']; ?>"><?php echo e($t['title']); ?></option>
                <?php } } ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <?php echo render_input('tea_break_in_minut', 'Tea Break (minutes)', '', 'number'); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_input('lunch_break_in_minut', 'Lunch Break (minutes)', '', 'number'); ?>
          </div>
          <div class="col-md-4">
            <?php echo render_input('dinner_break_in_minut', 'Dinner Break (minutes)', '', 'number'); ?>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="first_half_start">First Half Start</label>
              <input type="time" name="first_half_start" id="first_half_start" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="first_half_end">First Half End</label>
              <input type="time" name="first_half_end" id="first_half_end" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="second_half_start">Second Half Start</label>
              <input type="time" name="second_half_start" id="second_half_start" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="second_half_end">Second Half End</label>
              <input type="time" name="second_half_end" id="second_half_end" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="saturday_rule">Saturday Rule</label>
              <select name="saturday_rule" id="saturday_rule" class="form-control">
                <option value="">-- Select Rule --</option>
                <?php if (!empty($saturday_rules)) { foreach ($saturday_rules as $sr) { ?>
                  <option value="<?php echo (int)$sr['id']; ?>"><?php echo e($sr['title']); ?></option>
                <?php } } ?>
              </select>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="saturday_work_start">Sat Work Start</label>
              <input type="time" name="saturday_work_start" id="saturday_work_start" class="form-control" />
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="saturday_work_end">Sat Work End</label>
              <input type="time" name="saturday_work_end" id="saturday_work_end" class="form-control" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="employee_type_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/employeetype'), ['id' => 'employee-type-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Employee Type'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="employee-type-additional"></div>
            <?php echo render_input('name', 'Employee Type Title'); ?>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="branch_manager_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/branchmanager'), ['id' => 'branch-manager-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Branch Manager'); ?></h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="branch-manager-additional"></div>
            <?php echo render_input('branch_name', 'Branch Name'); ?>
            <div class="form-group">
              <label for="branch_address">Branch Address</label>
              <textarea name="branch_address" id="branch_address" class="form-control" rows="3" required></textarea>
            </div>
            <div class="form-group">
              <label for="branch_shift">Shift</label>
              <select name="shift" id="branch_shift" class="form-control">
                <option value="">-- Select Shift --</option>
                <?php if (!empty($shifts)) { foreach ($shifts as $s) { ?>
                  <option value="<?php echo (int)$s['shift_id']; ?>"><?php echo e($s['shift_code'] . ' - ' . $s['shift_name']); ?></option>
                <?php } } ?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
  $(function() {
    var smtpConfig = <?php echo json_encode($direct_smtp_config ?? []); ?>;
    var ndaConfig = <?php echo json_encode($nda_smtp_config ?? []); ?>;
    var globalConfig = <?php echo json_encode($global_smtp_config ?? []); ?>;
    if ($.fn.jqte) {
      $('.editor').jqte();
    }

    $('#company_profile').on('click', function(e) {
      e.preventDefault();
      $('#company_profile_modal').appendTo('body').modal('show');
    });

    $('#smtp_setup').on('click', function(e) {
      e.preventDefault();
      var enc = (smtpConfig.smtp_encryption || 'ssl').toLowerCase();
      if (enc !== 'ssl' && enc !== 'tls' && enc !== 'none') {
        enc = 'ssl';
      }
      $('input[name="smtp_encryption"][value="' + enc + '"]').prop('checked', true);
      $('#smtp_host').val(smtpConfig.smtp_host || '');
      $('#smtp_port').val(smtpConfig.smtp_port || '');
      $('#smtp_email').val(smtpConfig.smtp_email || '');
      $('#smtp_username').val(smtpConfig.smtp_username || '');
      $('#smtp_password').val(smtpConfig.smtp_password || '');
      $('#direct_smtp_modal').modal('show');
    });

    $('#global_smtp_setup').on('click', function(e) {
      e.preventDefault();
      var enc = (globalConfig.smtp_encryption || 'ssl').toLowerCase();
      if (enc !== 'ssl' && enc !== 'tls' && enc !== 'none') {
        enc = 'ssl';
      }
      $('#global_smtp_modal').find('input[name="smtp_encryption"][value="' + enc + '"]').prop('checked', true);
      $('#global_smtp_host').val(globalConfig.smtp_host || '');
      $('#global_smtp_port').val(globalConfig.smtp_port || '');
      $('#global_smtp_email').val(globalConfig.smtp_email || '');
      $('#global_smtp_username').val(globalConfig.smtp_username || '');
      $('#global_smtp_password').val(globalConfig.smtp_password || '');
      $('#global_smtp_modal').modal('show');
    });

    $('#global-smtp-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize()).done(function(resp) {
        var r = {};
        try { r = JSON.parse(resp); } catch (e) {}
        if (r.success) {
          alert_float('success', r.message || 'Global SMTP saved.');
          $('#global_smtp_modal').modal('hide');
          location.reload();
        } else {
          alert_float('warning', r.message || 'Failed to save Global SMTP.');
        }
      }).fail(function() {
        alert_float('danger', 'Request failed.');
      });
    });

    $('#direct-smtp-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize()).done(function(resp) {
        var r = {};
        try { r = JSON.parse(resp); } catch (e) {}
        if (r.success) {
          alert_float('success', r.message || 'SMTP saved.');
          $('#direct_smtp_modal').modal('hide');
          location.reload();
        } else {
          alert_float('warning', r.message || 'Failed to save SMTP.');
        }
      }).fail(function() {
        alert_float('danger', 'Request failed.');
      });
    });

    $('#nda_smtp_setup').on('click', function(e) {
      e.preventDefault();
      var enc = (ndaConfig.smtp_encryption || 'ssl').toLowerCase();
      if (enc !== 'ssl' && enc !== 'tls' && enc !== 'none') {
        enc = 'ssl';
      }
      $('#nda_smtp_modal').find('input[name="smtp_encryption"][value="' + enc + '"]').prop('checked', true);
      $('#nda_smtp_host').val(ndaConfig.smtp_host || '');
      $('#nda_smtp_port').val(ndaConfig.smtp_port || '');
      $('#nda_smtp_email').val(ndaConfig.smtp_email || '');
      $('#nda_smtp_username').val(ndaConfig.smtp_username || '');
      $('#nda_smtp_password').val(ndaConfig.smtp_password || '');
      $('#nda_smtp_modal').modal('show');
    });

    $('#nda-smtp-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize()).done(function(resp) {
        var r = {};
        try { r = JSON.parse(resp); } catch (e) {}
        if (r.success) {
          alert_float('success', r.message || 'NDA SMTP saved.');
          $('#nda_smtp_modal').modal('hide');
          location.reload();
        } else {
          alert_float('warning', r.message || 'Failed to save NDA SMTP.');
        }
      }).fail(function() {
        alert_float('danger', 'Request failed.');
      });
    });

    $('body').on('click', '#interview_process', function(e) {
      e.preventDefault();
      $('#process_title').val('');
      $('#interview_process_modal').appendTo('body').modal('show');
    });

    $('#interview-process-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Interview process added successfully');
          $('#interview_process_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save interview process');
        });
    });

    $('body').on('click', '#project_group', function(e) {
      e.preventDefault();
      $('#group_title').val('');
      $('#group_color').val('#757575');
      $('#project_group_modal').appendTo('body').modal('show');
    });

    $('#project-group-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function(resp) {
          var r = {};
          try { r = JSON.parse(resp); } catch (e) {}
          if (r.success) {
            alert_float('success', 'Project group added successfully');
            $('#project_group_modal').modal('hide');
            window.location.reload();
          } else {
            alert_float('warning', 'Failed to save project group');
          }
        })
        .fail(function() {
          alert_float('danger', 'Failed to save project group');
        });
    });

    $('body').on('click', '#shift-type', function(e) {
      e.preventDefault();
      $('#shift_type_title').val('');
      $('#shift_type_modal').appendTo('body').modal('show');
    });

    $('#shift-type-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Shift type added successfully');
          $('#shift_type_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save shift type');
        });
    });

    $('body').on('click', '#deal_stage', function(e) {
      e.preventDefault();
      $('#deal-stage-additional').html('');
      $('#deal_stage_modal input[name="name"]').val('');
      $('#deal_stage_modal input[name="color"]').val('');
      $('#deal_stage_modal select[name="status"]').val('1');
      $('#deal_stage_modal').appendTo('body').modal('show');
    });

    $('#deal-stage-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Deal stage added successfully');
          $('#deal_stage_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save deal stage');
        });
    });

    $('body').on('click', '#task_status', function(e) {
      e.preventDefault();
      $('#task-status-additional').html('');
      $('#task_status_modal input[name="name"]').val('');
      $('#task_status_modal input[name="color"]').val('');
      $('#task_status_modal input[name="statusorder"]').val('');
      $('#task_status_modal').appendTo('body').modal('show');
    });

    $('#task-status-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Task status added successfully');
          $('#task_status_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save task status');
        });
    });

    $('body').on('click', '#add_department', function(e) {
      e.preventDefault();
      $('#department-additional').html('');
      $('#department_modal input[type="text"]').val('');
      $('#department_modal input[type="email"]').val('');
      $('#department_modal input[type="password"]').val('');
      $('#department_modal input[type="checkbox"]').prop('checked', false);
      if ($.fn.selectpicker) {
        $('#department_folder').html('').selectpicker('refresh');
      } else {
        $('#department_folder').html('');
      }
      $('#department_modal').appendTo('body').modal('show');
    });

    $('#department-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function(response) {
          var result = {};
          try { result = JSON.parse(response); } catch (e) {}
          if (result.success) {
            alert_float('success', result.message || 'Department added successfully');
            $('#department_modal').modal('hide');
            window.location.reload();
            return;
          }
          if (result.email_exist_as_staff) {
            window.location.reload();
            return;
          }
          alert_float('warning', result.message || 'Failed to save department');
        })
        .fail(function(xhr) {
          var err = {};
          try { err = JSON.parse(xhr.responseText); } catch (e) {}
          alert_float('danger', err.message || 'Failed to save department');
        });
    });

    $('body').on('click', '#add_designation', function(e) {
      e.preventDefault();
      $('#designation_modal_label').text('Add Designation');
      $('#designation_id').val('');
      $('#designation_title').val('');
      $('#designation_is_active').prop('checked', true);
      if ($.fn.selectpicker) {
        $('#designation_department_id').selectpicker('refresh');
      }
      $('#designation_modal').appendTo('body').modal('show');
    });

    $('#designation-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function(resp) {
          var r = {};
          try { r = JSON.parse(resp); } catch (e) {}
          if (r.success) {
            alert_float('success', r.message || 'Designation added successfully');
            $('#designation_modal').modal('hide');
            window.location.reload();
          } else {
            alert_float('warning', r.message || 'Validation failed');
          }
        })
        .fail(function() {
          alert_float('danger', 'Request failed');
        });
    });

    $('body').on('click', '#add_staff_type', function(e) {
      e.preventDefault();
      $('#staff-type-additional').html('');
      $('#staff_type_modal input[name="name"]').val('');
      $('#staff_type_modal').appendTo('body').modal('show');
    });

    $('#staff-type-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Staff type added successfully');
          $('#staff_type_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save staff type');
        });
    });

    $('body').on('click', '#add_shift_manager', function(e) {
      e.preventDefault();
      $('#shift-manager-additional').html('');
      $('#shift_manager_modal input').not('[type="hidden"]').val('');
      $('#shift_manager_modal select').val('');
      $('#shift_manager_modal').appendTo('body').modal('show');
    });

    appValidateForm($("body").find('#shift-manager-form'), {
      shift_code: 'required',
      shift_name: 'required',
      shift_in: 'required',
      shift_out: 'required',
      shift_type: 'required'
    }, function(form) {
      var data = $(form).serialize();
      $.post(form.action, data).done(function () {
        window.location.reload();
      });
      return false;
    });

    $('body').on('click', '#add_employee_type', function(e) {
      e.preventDefault();
      $('#employee-type-additional').html('');
      $('#employee_type_modal input[name="name"]').val('');
      $('#employee_type_modal').appendTo('body').modal('show');
    });

    $('#employee-type-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Employee type added successfully');
          $('#employee_type_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save employee type');
        });
    });

    $('body').on('click', '#add_branch_manager', function(e) {
      e.preventDefault();
      $('#branch-manager-additional').html('');
      $('#branch_manager_modal input').not('[type="hidden"]').val('');
      $('#branch_manager_modal textarea').val('');
      $('#branch_manager_modal select').val('');
      $('#branch_manager_modal').appendTo('body').modal('show');
    });

    appValidateForm($("body").find('#branch-manager-form'), {
      branch_name: 'required',
      branch_address: 'required'
    }, function(form) {
      var data = $(form).serialize();
      $.post(form.action, data).done(function () {
        window.location.reload();
      });
      return false;
    });

    $('body').on('click', '#leave-rule', function(e) {
      e.preventDefault();
      $('#leave_rule_modal').appendTo('body').modal('show');
    });

    $('#leave-rule-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Leave rule added successfully');
          $('#leave_rule_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save leave rule');
        });
    });

    $('body').on('click', '#interview_source', function(e) {
      e.preventDefault();
      $('#source_title').val('');
      $('#interview_source_modal').appendTo('body').modal('show');
    });

    $('#interview-source-form').on('submit', function(e) {
      e.preventDefault();
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize())
        .done(function() {
          alert_float('success', 'Interview source added successfully');
          $('#interview_source_modal').modal('hide');
          window.location.reload();
        })
        .fail(function() {
          alert_float('danger', 'Failed to save interview source');
        });
    });
  });
</script>
</body>
</html>
