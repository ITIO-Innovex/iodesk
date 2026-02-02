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
  <div class="panel-body panel-table-full">
    <div class="tw-flex tw-items-center tw-justify-between tw-mb-4">
      <h4 class="tw-mb-0">CRM Setup Guide</h4>
      <!--<span class="label label-info">Latest UI</span>-->
    </div>

    <div class="panel-group" id="crm-setup-accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-staff">
          <h2 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-staff" aria-expanded="true" aria-controls="collapse-staff">
              <i class="fa-solid fa-users me-2"></i> Staff Management
            </a>
          </h2>
        </div>
        <div id="collapse-staff" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-staff">
          <div class="panel-body">
            <div class="form-group">
<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can add / Update/ active / inactive/ change password and set permission.
	<p>For Add Staff Click here : <a href="<?php echo admin_url('staff');?>" target="_blank"><?php echo admin_url('staff');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>

<div class="alert alert-danger">
    <strong>Instruction:</strong> Instruction: Before adding a staff member, please ensure that Department, Designation, and Staff Type are created.
	<p>For Add Department Click here : <a href="<?php echo admin_url('departments');?>" target="_blank"><?php echo admin_url('departments');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For Add Designation Click here : <a href="<?php echo admin_url('designation');?>" target="_blank"><?php echo admin_url('designation');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For Add Staff Type Click here : <a href="<?php echo admin_url('hrd/setting/staff_type');?>" target="_blank"><?php echo admin_url('hrd/setting/staff_type');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	
</div>

<ol>
    <li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> To Add Staff</h4>
        <ul>
            <li> - Click on <strong>Add Staff Member</strong>.</li>
            <li> - Fill in all <strong>required fields</strong> and click <strong>Submit</strong>.</li>
			<li> - The <strong>email address must be unique</strong> for each staff member.</li>
			<li> - Send welcome email : When Checked a mail sent on registered email with login details</li>
			<li> <img src="<?php echo base_url('uploads/screenshot/add-staff-profile.png'); ?>" 
                         alt="screenshot" style="max-width: 90%; padding:10px;" />
                    </li>
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
            <li><strong> - Normal Staff</strong> : 
No default permissions are assigned.</li>
            <li><strong> - Department Admin</strong> : 
Can manage staff within their own department.</li>
            <li><strong>Administrator</strong> : 
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
			<li> <img src="<?php echo base_url('uploads/screenshot/staff-permission.png'); ?>" 
                         alt="screenshot" style="max-width: 90%; padding:10px;" />
            </li>
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

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-hrms">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-hrms" aria-expanded="false" aria-controls="collapse-hrms">
              <i class="fa-solid fa-id-badge me-2"></i> HRMS
            </a>
          </h4>
        </div>
        <div id="collapse-hrms" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-hrms">
          <div class="panel-body">
            <div class="form-group">
<div class="alert alert-info">
    <strong>Instruction:</strong> Here you can add / Update/ active / inactive/ change password and set permission.
	<p>For Add Staff Click here : <a href="<?php echo admin_url('staff');?>" target="_blank"><?php echo admin_url('staff');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
</div>

<div class="alert alert-danger">
    <strong>Instruction:</strong> Instruction: Before adding a staff member, please ensure that Department, Designation, and Staff Type are created.
	<p>For Add shift types Click here : <a href="<?php echo admin_url('departments');?>" target="_blank"><?php echo admin_url('departments');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For Add shift managers  Click here : <a href="<?php echo admin_url('designation');?>" target="_blank"><?php echo admin_url('designation');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For Add Employee Type Click here : <a href="<?php echo admin_url('hrd/setting/staff_type');?>" target="_blank"><?php echo admin_url('hrd/setting/staff_type');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For Add Branch Managers Type Click here : <a href="<?php echo admin_url('hrd/setting/staff_type');?>" target="_blank"><?php echo admin_url('hrd/setting/staff_type');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For  Leave rules Type Click here : <a href="<?php echo admin_url('hrd/setting/staff_type');?>" target="_blank"><?php echo admin_url('hrd/setting/staff_type');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	<p>For  Manage HRMS staff Click here : <a href="<?php echo admin_url('hrd/setting/staff_type');?>" target="_blank"><?php echo admin_url('hrd/setting/staff_type');?> <i class="fa-solid fa-square-arrow-up-right"></i></a></p>
	
</div>

<ol>
 <li class="tw-my-2">
        <h4><i class="fa-solid fa-circle-right"></i> To Add Staff</h4>
        <ul>
            <li> - Click on <strong>Add Staff Member</strong>.</li>
            <li> - Fill in all <strong>required fields</strong> and click <strong>Submit</strong>.</li>
			<li> - The <strong>email address must be unique</strong> for each staff member.</li>
			<li> - Send welcome email : When Checked a mail sent on registered email with login details</li>
			<li> <img src="<?php echo base_url('uploads/screenshot/add-staff-profile.png'); ?>" 
                         alt="screenshot" style="max-width: 90%; padding:10px;" />
                    </li>
        </ul>
    </li>
</ol>
				</div>
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-projects">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-projects" aria-expanded="false" aria-controls="collapse-projects">
              <i class="fa-solid fa-diagram-project me-2"></i> Projects
            </a>
          </h4>
        </div>
        <div id="collapse-projects" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-projects">
          <div class="panel-body">
            Create project groups and define statuses. Dummy steps: add a "Product" group and create a sample project.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-team-docs">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-team-docs" aria-expanded="false" aria-controls="collapse-team-docs">
              <i class="fa-solid fa-file-lines me-2"></i> Team Document
            </a>
          </h4>
        </div>
        <div id="collapse-team-docs" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-team-docs">
          <div class="panel-body">
            Upload templates and set approval workflows. Dummy steps: add an NDA template and a policy document.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-direct-email">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-direct-email" aria-expanded="false" aria-controls="collapse-direct-email">
              <i class="fa-solid fa-envelope me-2"></i> Direct Email
            </a>
          </h4>
        </div>
        <div id="collapse-direct-email" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-direct-email">
          <div class="panel-body">
            Configure direct SMTP credentials and test sending. Dummy steps: add SMTP host, port, and a test sender.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-webmail">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-webmail" aria-expanded="false" aria-controls="collapse-webmail">
              <i class="fa-solid fa-inbox me-2"></i> Webmail
            </a>
          </h4>
        </div>
        <div id="collapse-webmail" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-webmail">
          <div class="panel-body">
            Link inbox, verify IMAP settings, and sync folders. Dummy steps: connect a test mailbox.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-ai-support">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-ai-support" aria-expanded="false" aria-controls="collapse-ai-support">
              <i class="fa-solid fa-robot me-2"></i> AI Support
            </a>
          </h4>
        </div>
        <div id="collapse-ai-support" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-ai-support">
          <div class="panel-body">
            Add API key and select providers. Dummy steps: add key, enable one provider, test prompt.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-subscriptions">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-subscriptions" aria-expanded="false" aria-controls="collapse-subscriptions">
              <i class="fa-solid fa-credit-card me-2"></i> Subscriptions
            </a>
          </h4>
        </div>
        <div id="collapse-subscriptions" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-subscriptions">
          <div class="panel-body">
            Create plans, set billing cycle, and test invoice flow. Dummy steps: add monthly plan and generate invoice.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-leads">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-leads" aria-expanded="false" aria-controls="collapse-leads">
              <i class="fa-solid fa-handshake me-2"></i> Leads & Deals
            </a>
          </h4>
        </div>
        <div id="collapse-leads" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-leads">
          <div class="panel-body">
            Add lead sources, deal stages, and task statuses. Dummy steps: create 3 stages and 2 sources.
          </div>
        </div>
      </div>

      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="heading-sales">
          <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#crm-setup-accordion" href="#collapse-sales" aria-expanded="false" aria-controls="collapse-sales">
              <i class="fa-solid fa-chart-line me-2"></i> Sales
            </a>
          </h4>
        </div>
        <div id="collapse-sales" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-sales">
          <div class="panel-body">
            Configure pricing, taxes, and invoice templates. Dummy steps: add a tax rule and create a sample quote.
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



<!-- Modal -->

<?php init_tail(); ?>


</body>

</html>