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
</style>


<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
	  <div id="loader-project"><i class="fa-solid fa-spinner fa-spin fa-5x text-warning"></i></div>
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" class="btn btn-primary" id="addTaskBtn"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Task'); ?> </a> 
		<?php if(isset($project_id)&&$project_id){ ?>
		<h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"># Project ID : <?php echo get_project_title($project_id);?></h4> 
		<?php } ?>
		
		</div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($listdata) && count($listdata) > 0) { ?>
            <div class="table-responsivexxx tw-mx-2" >
              <table class="table dt-table customizable-table" id="myTable" data-order-col="0" data-order-type="desc" style="overflow-x:auto; white-space:nowrap; max-width:100%;">
                <thead>
                  <tr>
					<th class="sticky-col first-col" style="position: sticky;"><?php echo 'ID'; ?></th>
                    <th class="sticky-col second-col" style="left: 100px;position: sticky;min-width: 200px;"><?php echo 'Task Name'; ?></th>
                     <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th ><?php echo 'Owner'; ?></th>
                    <th><?php echo 'Status'; ?></th>
					
					<th><?php echo 'Start Date'; ?></th>
                    <th><?php echo 'End Date'; ?></th>
                    <th><?php echo 'Duration'; ?></th>
                    <th><?php echo 'Priority'; ?></th>
                    <th><?php echo 'Created By'; ?></th>
					<th><?php echo 'Completion Percentage'; ?></th>
					<th><?php echo 'Tags'; ?></th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($listdata as $status) { 
                    $prod_status=proj_status_translate($status['task_status']);
                  ?>
                  <tr>
					<td  class="sticky-col first-col"  style="border-left: 5px solid <?php echo $prod_status->color; ?>;"><?php echo $status['id']; ?></td>
                    <td  class="sticky-col second-col" style="width: 300px;
    max-width: 300px;"><?php echo isset($status['task_name']) ? substr($status['task_name'], 0, 50) . '...' : '-'; ?>
	<?php //echo get_project_title($project_id);?>
	<?php if(!isset($project_id)||empty($project_id)){ ?><br />
<span class="text-warning text-bold" title="Project Title">
	<?php echo isset($status['project_id']) ? get_project_title($status['project_id'], 0, 50) . '' : '-'; ?>
	</span>
	<?php }?>
	
	<a href="<?php echo admin_url('project/tasks_details/'.$status["id"]); ?>" class="action-task action-task-btn tw-m-1 tw-p-1" style=""> <i class="fa-solid fa-file-contract"></i> View</a>

                    <td style="position:relative;">
  <i class="fa-solid fa-ellipsis-vertical project-dropdown-toggle" data-project-id="<?php echo $status['id']; ?>" style="float:inline-end; cursor:pointer;"></i>
  <div class="project-dropdown-menu" style="display:none; position:absolute; right:0; top:20px; min-width:150px; background:#fff; border:1px solid #ccc; border-radius:6px; box-shadow:0 2px 8px rgba(0,0,0,0.15); z-index:1000;">
  <a class="dropdown-item" href="<?php echo admin_url('project/tasks_details/'.$status['id']); ?>">Task Details</a>
  <a class="dropdown-item _delete" href="<?php echo admin_url('project/delete_task/'.$status['id']); ?>">Delete Task</a>  
	
	</div></td>
                    <td>
                      <?php
                        if (!empty($status['task_owner'])) {
                            $ownerIds = explode(',', $status['task_owner']);
                            echo '<div class="tw-flex -tw-space-x-1">';
                            foreach ($ownerIds as $oid) {
                                $oid = trim($oid);
                                if ($oid === '') { continue; }
                                echo '<a href="' . admin_url('profile/' . $oid) . '">'
                                  . staff_profile_image($oid, ['tw-h-7 tw-w-7 tw-inline-block tw-rounded-full tw-ring-2 tw-ring-white'], 'small', ['data-toggle' => 'tooltip', 'data-title' => e(get_staff_full_name($oid))])
                                  . '</a>';
                            }
                            echo '</div>';
                        } else {
                            echo '-';
                        }
                      ?>
                    </td>
                    
                    <td id="boredr-none" style="background:<?php echo $prod_status->color; ?>; color:#FFFFFF;" class="tw-text-white" align="center">
<span style="display:none"><?php echo get_project_status_title($status['task_status']);?></span>
  <select class="form-control task-status-select" data-task-id="<?php echo $status['id']; ?>" style="background:<?php echo $prod_status->color; ?>; color:#fff; border:none; min-width:120px;">
    <?php foreach($project_statuses as $ps): ?>
      <option value="<?php echo $ps['id']; ?>" <?php if($ps['id'] == $status['task_status']) echo 'selected'; ?>><?php echo $ps['name']; ?></option>
    <?php endforeach; ?>
  </select>
</td>
					
					<td><?php echo isset($status['task_start_date']) ? _d($status['task_start_date']) : '-'; ?></td>
                    <td><?php echo isset($status['task_end_date']) ? _d($status['task_end_date']) : '-'; ?></td>
                    <td><?php echo getDaysBetweenDates($status['task_start_date'],$status['task_end_date']);  ?></td>
                    <td><?php echo isset($status['task_priority']) ? get_project_priority($status['task_priority']) : '-'; ?></td>
                    <td><?php echo isset($status['task_addedby']) ? get_staff_full_name($status['task_addedby']) : '-'; ?></td>
					<td><?php echo get_task_percentage($status['id']); ?> %</td>
					<td><?php echo isset($status['task_tags']) ? _d($status['task_tags']) : '-'; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <?php } else { ?>
            <p class="no-margin">No tasks found. Click "New Task" to add one.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo _l('Add New Task'); ?></h4>
      </div>
      <?php echo form_open_multipart(admin_url('project/addtask'), ['id' => 'add-task-form']); ?>
	  
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
			<?php if(isset($project_id)&&$project_id){ ?>
            <input type="hidden" id="project_id" name="project_id" value="<?php echo $project_id;?>" required>
			<?php }else{ ?>
			<!-- Project Title -->
            <div class="form-group">
                <label for="owner" class="control-label"><?php echo 'Select Project'; ?></label>
                <select class="form-control" id="project_id" name="project_id" title="Select Project" required>
                  <option value=""><?php echo _l('dropdown_non_selected_tex'); ?></option>
                  <?php if (isset($project_list) && is_array($project_list)) { ?>
                    <?php foreach ($project_list as $prj) { ?>
                      <option value="<?php echo $prj['id']; ?>"><?php echo $prj['project_title']; ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
			<?php } ?>
            <!-- Project Title -->
            <div class="form-group">
              <label for="task_name" class="control-label"><?php echo _l('Task Name'); ?></label>
              <input type="text" class="form-control" id="task_name" name="task_name" required>
            </div>
            
			<div class="row">
              <div class="col-md-6">
                                       <!-- Owners (Multi-select with avatars) -->
              <div class="form-group">
                <label for="task_owner" class="control-label"><small class="req text-danger">* </small><?php echo _l('Owner'); ?></label>
                <select class="form-control selectpicker" id="task_owner" name="task_owner[]" required multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" title="Owner">
                  <?php if (isset($staff_members) && is_array($staff_members)) { ?>
                    <?php foreach ($staff_members as $staff) { ?>
                      <option data-content='<span class="tw-inline-flex tw-items-center"><?php echo str_replace("'", "&apos;", staff_profile_image($staff['staffid'], ["tw-h-6 tw-w-6 tw-rounded-full tw-inline-block tw-mr-2 tw-ring-2 tw-ring-white"], "small")); ?><span><?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?></span></span>' value="<?php echo $staff['staffid']; ?>">
                        <?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?>
                      </option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
              </div>
              <div class="col-md-6">
			  <div class="form-group">
               <label for="task_priority" class="control-label"><small class="req text-danger">* </small><?php echo _l('Priority'); ?></label>
			   
               <select class="form-control" id="task_priority" name="task_priority" title="Priority" required>
                  <?php foreach($project_priority as $pp): ?>
      <option value="<?php echo $pp['priorityid']; ?>" <?php if($pp['priorityid'] == 4) echo 'selected'; ?>><?php echo $pp['name']; ?></option>
    <?php endforeach; ?>
               </select>
             </div>
            </div></div>
            <!-- Start Date and End Date -->
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="task_start_date" class="control-label"><small class="req text-danger">* </small><?php echo _l('Start Date'); ?></label>
                  <input type="datetime-local" class="form-control" id="task_start_date" name="task_start_date" title="Start date" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="task_end_date" class="control-label"><small class="req text-danger">* </small><?php echo _l('End Date'); ?></label>
                  <input type="datetime-local" class="form-control" id="task_end_date" name="task_end_date" title="End date" required>
                </div>
              </div>
            </div>
            
                         <!-- Project Group -->
             
            
                         <!-- Description -->
             <?php echo render_textarea('task_description', '', '', ['required' => 'true'], [], '', 'tinymce'); ?>
             
                           <!-- Tags -->
              <div class="form-group">
                <label for="tags" class="control-label"><?php echo _l('Tags'); ?> <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Add tags to better manage and search records. Add text and press enter for add tag"></i></label>
                <input type="text" class="form-control" id="tagsInput" name="tags" placeholder="<?php echo _l('Enter a tag name'); ?>">
              </div>
              
              <!-- File Attachments -->
              <div class="form-group">
                <label for="task_attachments" class="control-label"><?php echo _l('Attachments'); ?> <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Choose multiple files to upload together."></i></label>
                <div class="input-group">
                  <input type="file" class="form-control" id="task_attachments" name="task_attachments[]" multiple accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.xls,.xlsx,.zip,.rar">
                  <span class="input-group-btn">
                    <button class="btn btn-default add_more_attachments" type="button" title="Add more files"><i class="fa fa-plus"></i></button>
                  </span>
                </div>
                <div id="attachments_container" class="mt-2">
                  <!-- Additional attachment fields will be added here -->
                </div>
                <small class="text-muted">Allowed file types: PDF, DOC, DOCX, TXT, JPG, PNG, GIF, XLS, XLSX, ZIP, RAR</small>
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
<!-- /.modal -->
<script>
// Wait for jQuery to be fully loaded
function initializeTasktModal() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn !== 'undefined') {
        var $ = jQuery;
        
        // Initialize form validation
        appValidateForm($("body").find('#add-task-form'), {
            task_name: 'required',
            'task_owner[]': 'required'
        }, manage_project_form);
        
        // Add project button click event
        $('#addTaskBtn').on('click', function(e) {
            e.preventDefault();
            $('#addTaskModal').modal('show');
        });
        
        // Initialize TinyMCE when modal is shown
        $('#addTaskModal').on('shown.bs.modal', function() {
            // Initialize custom tags input
           // console.log('Initializing tags input...');
            //initializeTagsInput();
            if (typeof init_selectpicker === 'function') { init_selectpicker(); }
        });
        
        // Modal hidden event
        $('#addTaskModal').on("hidden.bs.modal", function (event) {
            $('#additional').html('');
            $('#addTaskModal input[name="task_name"]').val('');
            $('#addTaskModal select[name="task_owner[]"]').val('');
            $('#addTaskModal input[name="task_start_date"]').val('');
            $('#addTaskModal input[name="task_end_date"]').val('');
            $('#addTaskModal select[name="task_priority"]').val('');
			$('#addTaskModal select[name="task_priority"]').val('');
			$('#addTaskModal input[name="project_id"]').val('');
			//$('#addProjectModal textarea[name="task_description"]').val('');
            
            
            
            // Clear custom tags
            //$('#tags').val('');
           // $('#tags-container').empty();
           // projectTags = []; // Reset global tags array
            
            // Clear attachments
            $('#task_attachments').val('');
            $('#attachments_container').empty();
        });
    } else {
        // Retry after a short delay
        setTimeout(initializeTasktModal, 100);
    }
}

// Start initialization when document is ready
if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(function() {
        initializeTasktModal();
    });
} else {
    // Fallback if jQuery is not available yet
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(initializeTasktModal, 500);
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
    var startDate = $('#task_start_date').val();
    var endDate = $('#task_end_date').val();
    if (startDate && endDate && endDate < startDate) {
        alert('End Date cannot be earlier than Start Date.');
        $('#task_end_date').focus();
        return false;
    }

    var formData = new FormData(form);
	
	//alert(formData);
    
	 //alert(JSON.stringify(formData));
	 console.log(JSON.stringify(formData));
   
  
    
    var url = form.action;
    //alert(url);
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
                $('#addTaskModal').modal('hide');
                // Show success message
                if (response.message) {
                    showFlashMessage('Task added successfully!', 'success');
					 setTimeout(function() {
                     window.location.reload();
                     }, 2000);
                }
               //window.location.reload();
            } else {
                alert(response.message || 'Error adding Task');
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
                alert('!!Error submitting form. Please try again. Error: ' + error);
            }
        },
        complete: function() {
            // Reset button state
            submitBtn.prop('disabled', false).text(originalText);
        }
    });
    
    return false;
}


// Add this inside initializeTasktModal after modal is shown
$('#task_start_date').on('change', function() {
    var startDate = $(this).val();
    $('#task_end_date').attr('min', startDate);
});

// Add more attachments functionality
$('body').on('click', '.add_more_attachments', function() {
    var attachmentHtml = '<div class="input-group mt-2">' +
        '<input type="file" class="form-control" name="task_attachments[]" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif,.xls,.xlsx,.zip,.rar">' +
        '<span class="input-group-btn">' +
        '<button class="btn btn-danger remove_attachment" type="button"><i class="fa fa-minus"></i></button>' +
        '</span>' +
        '</div>';
    $('#attachments_container').append(attachmentHtml);
});

// Remove attachment field
$('body').on('click', '.remove_attachment', function() {
    $(this).closest('.input-group').remove();
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
 

<style>

/* Attachment styles */
.attachment-link {
    display: inline-block;
    transition: all 0.2s ease;
}
.attachment-link:hover {
    transform: scale(1.1);
}
.add_more_attachments {
    border-left: 1px solid #ddd;
}
.remove_attachment {
    border-left: 1px solid #ddd;
}
#attachments_container .input-group {
    margin-bottom: 5px;
}
</style>

<?php init_tail(); ?>
<!-- Tagify CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.min.js"></script>
<script>
// Initialize Tagify
var input = document.querySelector('#tagsInput');
new Tagify(input);
</script>
</body></html> 