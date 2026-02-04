<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
 #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  th, td {
    border: 1px solid #ccc;
    padding: 8px;
    background: white;
    white-space: nowrap;
  }

  .sticky-col {
    position: sticky;
    background: #f9f9f9;
    z-index: 2;
  }

  .first-col {
    left: 50px;
    z-index: 3; /* To be above .second-col */
  }

  .second-col {
    left: 100px; /* Width of first column */
  }
.table-responsive{ min-height:500px; }

/* Red border for project description validation error */
.jqte_editor.error-border {
    border: 2px solid #dc3545 !important;
    border-radius: 4px;
    padding: 2px;
    transition: border-color 0.3s ease;
}

.jqte_editor.error-border [contenteditable="true"] {
    border: 1px solid #dc3545 !important;
    outline: none;
}

/* Focus effect for project description */
#project_description:focus + .jqte_editor,
.jqte_editor:has([contenteditable="true"]:focus) {
    border: 2px solid #007bff;
    border-radius: 4px;
    padding: 2px;
    transition: border-color 0.3s ease;
}

.jqte_editor [contenteditable="true"]:focus {
    border: 1px solid #007bff;
    outline: none;
}
</style>


<div id="wrapper">
  <div class="content">
  
    <div class="row">
	<!-- Loader -->
<?php $project_group_count = (int) ($project_group_count ?? 0); ?>
<?php if ($project_group_count > 0) { ?>
<?php /*?><div class="alert alert-success">
Active project group count: <?php echo $project_group_count; ?>
</div><?php */?>
<?php } else { ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Add project groups to organize and manage your projects effectively. <span style="float:right"><a href="javascript:void(0);" id="add_project_group_btn" class="btn btn-warning btn-sm ms-2">Add Project Group</a></span></div>
                  </div>
<?php } ?>

      <div class="col-md-12">
	  <div id="loader-project"><i class="fa-solid fa-spinner fa-spin fa-5x text-warning"></i></div>
  

        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" class="btn btn-primary" id="addProjectBtn"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Project'); ?> </a> <span class="btn btn-warning"> Total : <?php echo count($listdata)??0?>  </span> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($listdata) && count($listdata) > 0) { ?>
            <div class="table-responsivexxx tw-mx-2" >
              <table class="table dt-table customizable-table" id="myTable" data-order-col="0" data-order-type="desc" style="overflow-x:auto; white-space:nowrap; max-width:100%;">
                <thead>
                  <tr>
                    <?php /*?><th style="display:none" ><?php echo 'ID'; ?></th><?php */?>
					<th  class="sticky-col first-col" style="position: sticky;"><?php echo 'ID'; ?></th>
                    <th  class="sticky-col second-col" style="left: 100px;position: sticky;min-width: 200px;"><?php echo 'Project Name'; ?></th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th><?php echo '%'; ?></th>
                    <th ><?php echo 'Owner'; ?></th>
                    <th><?php echo 'Status'; ?></th>
                    <th><?php echo 'Task'; ?></th>
                    <?php /*?><th><?php echo 'Milestone'; ?></th>
                    <th><?php echo 'Issues'; ?></th><?php */?>
                    <th><?php echo 'Start Date'; ?></th>
                    <th><?php echo 'End Date'; ?></th>
                    <th><?php echo 'Tags'; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($listdata as $status) { 
				  
				   $completion = getProjectCompletion($status['id']);
				   //echo "Project completion: {$completion}%";exit;
                    $prod_status=proj_status_translate($status['project_status']);
					
					if($completion==100 && $status['project_status']<>10){
					
					$this->db->where('id', $status['id']);
					$currdate=date("Y-m-d H:i:s");
                    $this->db->update(db_prefix() . 'project_master', ['project_status' => 10, 'date_finished' => $currdate]);
		
					$status['project_status']=10;
					$prod_status->color="#4fd3e5";
					}
                  ?>
                  <tr>
                    <?php /*?><td style="display:none" ><?php echo $status['id']; ?></td><?php */?>
					<td class="sticky-col first-col" style="border-left: 5px solid <?php echo $prod_status->color; ?>;"><?php echo $status['id']; ?></td>
                    <td class="sticky-col second-col" style="width: 300px;
    max-width: 300px;"><?php echo isset($status['project_title']) ? substr($status['project_title'], 0, 30) . '...' : '-'; ?>
<a href="<?php echo admin_url('project/tasks/'.$status["id"]); ?>" class="action-task action-task-btn tw-m-1 tw-p-1" style=""> <i class="fa-solid fa-file-contract"></i> Access Project</a></td>
                    <td style="position:relative;">
  <i class="fa-solid fa-ellipsis-vertical project-dropdown-toggle" data-project-id="<?php echo $status['id']; ?>" style="float:inline-end; cursor:pointer;"></i>
  <div class="project-dropdown-menu" style="display:none; position:absolute; right:0; top:20px; min-width:150px; background:#fff; border:1px solid #ccc; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.15); z-index:1000;">
    <a class="dropdown-item" href="<?php echo admin_url('project/tasks/'.$status['id']); ?>">Access Project</a>
    <a class="dropdown-item" href="<?php echo admin_url('project/view/'.$status['id']); ?>">View Details</a>
    <a class="dropdown-item" href="<?php echo admin_url('project/edit/'.$status['id']); ?>">Edit Project</a>
	<a class="dropdown-item _delete" href="<?php echo admin_url('project/delete_project/'.$status['id']); ?>">Delete Project</a>  
	
	</div></td>
					<td><?php echo $completion; ?>%</td>
                    <td>
					<span style="display:none"><?php echo get_staff_full_name($status['owner']);?></span>
  <select class="form-control project-owner-select" data-project-id="<?php echo $status['id']; ?>" style="min-width:120px;">
    <?php foreach($staff_members as $staff): ?>
      <option value="<?php echo $staff['staffid']; ?>" <?php if($staff['staffid'] == $status['owner']) echo 'selected'; ?>><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
    <?php endforeach; ?>
  </select></td>
                    <td id="boredr-none" style="background:<?php echo $prod_status->color; ?>; color:#FFFFFF;" class="tw-text-white" align="center">
					<span style="display:none"><?php echo get_project_status_title($status['project_status']);?></span>
  <select class="form-control project-status-select" data-project-id="<?php echo $status['id']; ?>" style="background:<?php echo $prod_status->color; ?>; color:#fff; border:none; min-width:120px;">
    <?php foreach($project_statuses as $ps): ?>
      <option value="<?php echo $ps['id']; ?>" <?php if($ps['id'] == $status['project_status']) echo 'selected'; ?>><?php echo $ps['name']; ?></option>
    <?php endforeach; ?>
  </select></td>
                    <td><?php echo isset($status['total_tasks']) ? _d($status['total_tasks']) : '0'; ?></td>
                    <?php /*?><td><?php echo isset($status['total_milestones']) ? _d($status['total_milestones']) : '0'; ?></td>
                    <td><?php echo isset($status['total_issues']) ? _d($status['total_issues']) : '0'; ?></td><?php */?>
                    <td><?php echo isset($status['start_date']) ? _d($status['start_date']) : '-'; ?></td>
                    <td><?php echo isset($status['deadline']) ? _d($status['deadline']) : '-'; ?>
					<?php 
					if(isset($status['deadline'])&&$status['deadline']<>"0000-00-00"){
					echo getDateDifference($status['deadline'],$prod_status->name); 
					}
					?>					</td>
                    <td><?php echo isset($status['tags']) ? _d($status['tags']) : '-'; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } else { ?>
            <p class="no-margin style1 style2"><?php echo _l('no_projects_found'); ?></p>
            <span class="style3">
            <?php } ?>
          </span><span class="style1">            </span></div>
        </div>
      </div>
    </div>
  </div>
</div>
<span class="style2"></span>
<div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Project'); ?></h4>
      </div>
      <?php echo form_open(admin_url('project/addproject'), ['id' => 'add-project-form']); ?>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            
            <!-- Project Title -->
            <div class="form-group">
              <label for="project_title" class="control-label"><?php echo _l('Project Title'); ?></label>
              <input type="text" class="form-control" id="project_title" name="project_title" title="Project title" required>
            </div>
            
			<div class="row">
              <div class="col-md-6">
                                       <!-- Owner -->
              <div class="form-group">
                <label for="owner" class="control-label"><small class="req text-danger">* </small><?php echo _l('Owner'); ?></label>
                <select class="form-control" id="owner" name="owner" title="Owner" required>
                  <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                  <?php if (isset($staff_members) && is_array($staff_members)) { ?>
                    <?php foreach ($staff_members as $staff) { ?>
                      <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
			  </div>
              <div class="col-md-6">
			  <div class="form-group">
               <label for="project_group" class="control-label"><small class="req text-danger">* </small><?php echo _l('Project Group'); ?> <a href="<?php echo admin_url('project/project_group');?>" data-toggle="tooltip" data-original-title="Click for add new project group"  target="_blank" ><i class="fa-solid fa-circle-plus"></i></a></label>
               <select class="form-control" id="project_group" name="project_group" title="Select project group" required>
                 <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                 <?php if (isset($project_groups) && is_array($project_groups)) { ?>
                   <?php foreach ($project_groups as $group) { ?>
                     <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                   <?php } ?>
                 <?php } ?>
               </select>
             </div>
            </div></div>
            <!-- Start Date and End Date -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="start_date" class="control-label"><small class="req text-danger">* </small><?php echo _l('Start Date'); ?></label>
                  <input type="date" class="form-control" id="start_date" name="start_date" title="Select start date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="deadline" class="control-label"><small class="req text-danger">* </small><?php echo _l('End Date'); ?></label>
                  <input type="date" class="form-control" id="deadline" name="deadline" title="Select end date" required>
                </div>
              </div>
            </div>
            
                         <!-- Project Group -->
             <!-- Project Title -->
            <div class="form-group">
               <input type="checkbox" value="1" name="make_this_a_strict_project" id="make_this_a_strict_project">
               <label for="make_this_a_strict_project">Make this a strict project <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title=" Tasks, issues, milestones etc., cannot start or end outside the scheduled dates of a strict project."></i></label>
            </div>
			
            
                         <!-- Description -->
             <div class="form-group">
             <label for="task_start_date" class="control-label"><small class="req text-danger">* </small>Project Description</label>
			 <?php echo render_textarea('project_description', '', '', [], [], '', 'editor'); ?>
             </div>
			 
                           <!-- Tags -->
			<div class="row">
              <div class="col-md-6">
              <div class="form-group">
                <label for="tags" class="control-label"><?php echo _l('Tags'); ?> <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Add tags to better manage and search records. Add text and press enter for add tag"></i></label>
                <input type="text" class="form-control tagify-input" id="tags" name="tags" placeholder="<?php echo _l('Enter a tag name'); ?>">
                
              </div>
			  </div>
			  <div class="col-md-6">
              <div class="form-group">
                            <label for="encryption">Project Access <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Private : Only project users can view and access this project. and Public : 
Portal users can only view, follow, and comment whereas, project users will have complete access."></i></label><br>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="project_access" value="1" id="add_project_access_private" checked="">
                                <label for="add_project_access_private">Private</label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="project_access" value="2" id="add_project_access_public" >
                                <label for="add_project_access_public">Public</label>
                            </div>
                            
                        </div>
			  </div>
           </div> 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
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
<!-- /.modal -->
<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Project</h4>
      </div>
      <?php echo form_open(admin_url('project/updateproject'), ['id' => 'edit-project-form']); ?>
      <div class="modal-body">
        <input type="hidden" name="project_id" id="edit_project_id">
        <div class="row">
          <div class="col-md-12">
            <div id="edit-additional"></div>
            <div class="form-group">
              <label for="edit_project_title" class="control-label"><small class="req text-danger">* </small>Project Title</label>
              <input type="text" class="form-control" id="edit_project_title" name="project_title" title="Project title" required>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="edit_owner" class="control-label"><small class="req text-danger">* </small>Owner</label>
                  <select class="form-control" id="edit_owner" name="owner" title="Owner" required>
                    <option value="">Select Owner</option>
                    <?php foreach ($staff_members as $staff) { ?>
                      <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="edit_project_group" class="control-label"><small class="req text-danger">* </small>Project Group</label>
                  <select class="form-control" id="edit_project_group" name="project_group" title="Project group" required>
                    <option value="">Select Group</option>
                    <?php foreach ($project_groups as $group) { ?>
                      <option value="<?php echo $group['id']; ?>"><?php echo $group['name']; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="edit_start_date" class="control-label"><small class="req text-danger">* </small>Start Date</label>
                  <input type="date" class="form-control" id="edit_start_date" name="start_date" title="Start date" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="edit_deadline" class="control-label"><small class="req text-danger">* </small>End Date</label>
                  <input type="date" class="form-control" id="edit_deadline" name="deadline" title="End date" required>
                </div>
              </div>
            </div>
			<?php echo render_textarea('edit_project_description', '', '', [], [], '', 'editor'); ?>
           <div class="form-group">
              <label for="edit_tags" class="control-label">Tags</label>
              <input type="text" class="form-control tagify-input" id="edit_tags" name="edit_tags" placeholder="Enter a tag name">
              
            </div>
			
			
			<div class="row">
              <div class="col-md-6">
              <div class="form-group">
               <input type="checkbox" value="1" name="make_this_a_strict_project" id="edit_make_this_a_strict_project">
               <label for="make_this_a_strict_project">Make this a strict project <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title=" Tasks, issues, milestones etc., cannot start or end outside the scheduled dates of a strict project."></i></label>
            </div>
			  </div>
			  <div class="col-md-6">
              <div class="form-group">
                            <label for="encryption">Project Access <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Private : Only project users can view and access this project. and Public : 
Portal users can only view, follow, and comment whereas, project users will have complete access."></i></label>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="project_access" value="1" id="edit_project_access_private" checked="">
                                <label for="edit_project_access_private">Private</label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" name="project_access" value="2" id="edit_project_access_public" >
                                <label for="edit_project_access_public">Public</label>
                            </div>
                            
                        </div>
			  </div>
           </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save Changes</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<script>
// Wait for jQuery to be fully loaded
function initializeProjectModal() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn !== 'undefined') {
        var $ = jQuery;
        
        // Initialize form validation
        appValidateForm($("body").find('#add-project-form'), {
            project_title: 'required',
            owner: 'required'
        }, manage_project_form);
        
        // Add project button click event
        $('#addProjectBtn').on('click', function(e) {
            e.preventDefault();
            var today = new Date().toISOString().split('T')[0];
            $('#start_date').attr('min', today);
            $('#deadline').attr('min', today);
            $('#addProjectModal').modal('show');
        });

        // Add project group button click event
        $('#add_project_group_btn').on('click', function(e) {
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
        
        // Initialize TinyMCE when modal is shown
        $('#addProjectModal').on('shown.bs.modal', function() {
            
            
            // Initialize custom tags input
           // console.log('Initializing tags input...');
           // initializeTagsInput();
        });
        
        // Modal hidden event
        $('#addProjectModal').on("hidden.bs.modal", function (event) {
            $('#additional').html('');
            $('#addProjectModal input[name="project_title"]').val('');
            $('#addProjectModal select[name="owner"]').val('');
            $('#addProjectModal input[name="start_date"]').val('');
            $('#addProjectModal input[name="deadline"]').val('');
            $('#addProjectModal select[name="project_group"]').val('');
			$('#addProjectModal textarea[name="project_description"]').val('');
            
            // Clear TinyMCE content
            //if (typeof tinymce !== 'undefined' && tinymce.get('project_description')) {
                //tinymce.get('project_description').setContent('');
           // }
            
            // Clear custom tags
            //$('#tags').val('');
            //$('#tags-container').empty();
           // projectTags = []; // Reset global tags array
        });
    } else {
        // Retry after a short delay
        setTimeout(initializeProjectModal, 100);
    }
}

// Start initialization when document is ready
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function() {
        initializeProjectModal();
    });
} else {
    // Fallback if jQuery is not available yet
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initializeProjectModal, 500);
    });
}

// Form handler function for project form
function manage_project_form(form) {
    if (typeof jQuery === 'undefined') {
        alert('jQuery is not loaded. Please refresh the page.');
        return false;
    }
    
    var $ = jQuery;

    // Date validation
    var startDate = $('#start_date').val();
    var endDate = $('#deadline').val();
    if (startDate && endDate && endDate < startDate) {
        alert('End Date cannot be earlier than Start Date.');
        $('#deadline').focus();
        return false;
    }

    // Validate project_description
    var projectDescription = '';
    var $descriptionEditor = $('#project_description');
    var isEmpty = true;
    
    if ($descriptionEditor.length) {
        // Method 1: Try jqteVal() function (if available)
        if (typeof $.fn.jqteVal === 'function') {
            try {
                projectDescription = $descriptionEditor.jqteVal() || '';
            } catch(e) {
                // Continue to other methods
            }
        }
        
        // Method 2: Get from textarea value (jqte syncs to textarea)
        if (!projectDescription) {
            projectDescription = $descriptionEditor.val() || '';
        }
        
        // Method 3: Get directly from jqte editor's contenteditable div
        // jqte creates a structure like: .jqte > .jqte_editor > [contenteditable div]
        var $jqteEditor = $descriptionEditor.siblings('.jqte_editor').length ? 
            $descriptionEditor.siblings('.jqte_editor') : 
            $descriptionEditor.parent().find('.jqte_editor').first();
            
        if ($jqteEditor.length && !projectDescription) {
            var $contentEditable = $jqteEditor.find('[contenteditable="true"]');
            if ($contentEditable.length) {
                projectDescription = $contentEditable.html() || '';
            }
        }
        
        // Extract text content from HTML
        if (projectDescription) {
            var tempDiv = document.createElement('div');
            tempDiv.innerHTML = projectDescription;
            var textContent = (tempDiv.textContent || tempDiv.innerText || '').trim();
            // Remove HTML entities and extra whitespace
            textContent = textContent.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
            isEmpty = !textContent || textContent === '';
        }
    }
    
    if (isEmpty) {
        alert('Project Description is required. Please enter a description.');
        // Focus the jqte editor and add red border
        var $jqteEditor = $descriptionEditor.siblings('.jqte_editor').length ? 
            $descriptionEditor.siblings('.jqte_editor') : 
            $descriptionEditor.parent().find('.jqte_editor').first();
        if ($jqteEditor.length) { 
            var $contentEditable = $jqteEditor.find('[contenteditable="true"]');
            if ($contentEditable.length) {
                // Add error class for red border styling
                $jqteEditor.addClass('error-border');
                
                // Multiple methods to ensure focus works
                setTimeout(function() {
                    // Method 1: Direct focus
                    $contentEditable[0].focus();
                    
                    // Method 2: Click to focus
                    $contentEditable[0].click();
                    
                    // Method 3: Set cursor position
                    if (window.getSelection && document.createRange) {
                        var range = document.createRange();
                        range.selectNodeContents($contentEditable[0]);
                        range.collapse(false);
                        var sel = window.getSelection();
                        sel.removeAllRanges();
                        sel.addRange(range);
                    }
                    
                    // Scroll into view
                    $contentEditable[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
                
                // Remove red border when user starts typing
                var removeErrorBorder = function() {
                    $jqteEditor.removeClass('error-border');
                    $contentEditable.off('input keydown paste', removeErrorBorder);
                };
                $contentEditable.on('input keydown paste', removeErrorBorder);
            } else {
                $descriptionEditor.css('border', '2px solid #dc3545');
                setTimeout(function() {
                    $descriptionEditor.focus();
                    $descriptionEditor[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 100);
                // Remove border on input
                $descriptionEditor.on('input', function() {
                    $(this).css('border', '');
                });
            }
        } else {
            $descriptionEditor.css('border', '2px solid #dc3545');
            setTimeout(function() {
                $descriptionEditor.focus();
                $descriptionEditor[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
            // Remove border on input
            $descriptionEditor.on('input', function() {
                $(this).css('border', '');
            });
        }
        return false;
    }

    var formData = new FormData(form);
    
    // Get TinyMCE content
    //if (typeof tinymce !== 'undefined' && tinymce.get('project_description')) {
       // var description = tinymce.get('project_description').getContent();
       // formData.set('project_description', description);
    //}
    
    // Get tags as comma-separated string
    /*var tags = [];
    $('#tags-container .tag-item').each(function() {
        var tagText = $(this).text().replace('×', '').trim();
        if (tagText) {
            tags.push(tagText);
        }
    });
    formData.set('tags', tags.join(','));*/
    
    var url = form.action;
    
    // Show loading state
    var submitBtn = $(form).find('button[type="submit"]');
    var originalText = submitBtn.text();
    submitBtn.prop('disabled', true).text('Adding...');
    
    // Use jQuery AJAX with proper error handling
    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            console.log('Response:', response);
            if (response.success) {
                $('#addProjectModal').modal('hide');
                // Show success message
                if (response.message) {
                    //alert(response.message);
					
		             showFlashMessage('Project added successfully!', 'success');
					 setTimeout(function() {
                     window.location.reload();
                     }, 2000);
                }
                //window.location.reload();
            } else {
                alert(response.message || 'Error adding project');
            }
        },
        error: function(xhr, status, error) {
            console.log('AJAX Error:', error);
            console.log('Status:', status);
            console.log('Response Text:', xhr.responseText);
            
            // Try to parse error response
            try {
                var errorResponse = JSON.parse(xhr.responseText);
                alert(errorResponse.message || 'Error submitting form. Please try again.');
            } catch (e) {
                alert('Error submitting form. Please try again. Error: ' + error);
            }
        },
        complete: function() {
            // Reset button state
            submitBtn.prop('disabled', false).text(originalText);
        }
    });
    
    return false;
}


// Add this inside initializeProjectModal after modal is shown
$('#start_date').on('change', function() {
    var startDate = $(this).val();
    $('#deadline').attr('min', startDate);
    if ($('#deadline').val() && $('#deadline').val() < startDate) {
        $('#deadline').val('');
    }
});

$('#deadline').on('change', function() {
    var startDate = $('#start_date').val();
    var endDate = $(this).val();
    if (startDate && endDate && endDate < startDate) {
        alert('End Date cannot be earlier than Start Date.');
        $(this).val('');
    }
});


 </script>
 
<script>
  // Select all rows except the header row
  const rows = document.querySelectorAll('#myTable tr');

  rows.forEach((row, index) => {
    if (index === 0) return; // skip header row

    row.addEventListener('mouseenter', () => {
      const link = row.querySelector('.action-task');
      if (link) link.style.display = 'inline-block';
    });

    row.addEventListener('mouseleave', () => {
      const link = row.querySelector('.action-task');
      if (link) link.style.display = 'none';
    });
  });
</script>
 


<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>
	// Initialize jqte editors, but exclude edit modal editors for now
	$('.editor').not('#edit_project_description').jqte();
	
	 $("#edit-project-form").on("submit", function(e){
    let startDate = new Date($("#edit_start_date").val());
    let endDate   = new Date($("#edit_deadline").val());

    if(startDate > endDate){
      e.preventDefault(); // stop form submit
      alert("Start Date cannot be later than End Date.");
      return false;
    }
  });
  
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.min.js"></script>
<script>
// Initialize Tagify
document.querySelectorAll('.tagify-input').forEach(function(input){
new Tagify(input);
});
</script>

</body></html> 