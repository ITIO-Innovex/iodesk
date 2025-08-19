<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($task); ?>
<style>
.panel-body.panel-table-full.mail-bg {position: relative;}
.top-left-btn { position: absolute; top: -20px; left: 10px; z-index: 10; padding: 8px 12px; color: white; border: none; border-radius: 4px; cursor: pointer; }
.top-left-btn.mg { left: 135px; }
.alert {margin-bottom: 2px;}

</style>
<div id="wrapper">
  <div class="content">
    <div class="row ">
      <div class="col-md-12">
	  <div id="loader-project"><i class="fa-solid fa-spinner fa-spin fa-5x text-warning"></i></div>
	  <div class="tw-mb-2 sm:tw-mb-4"><div class="_buttons"> <div class="display-block pull-right tw-space-x-0 sm:tw-space-x-1.5"><a href="<?php echo admin_url('project/');?>" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" data-toggle="tooltip" data-original-title="Back to project" ><i class="fa-solid fa-chart-gantt menu-icon"></i></a>
<a href="<?php echo admin_url('project/tasks/'.$task['project_id']);?>" class="btn btn-default btn-with-tooltip invoices-total" data-toggle="tooltip"  data-original-title="Back to Task"><i class="fa fa-bar-chart"></i></a>
<?php /*?><a href="javascript:void(0)" class="btn btn-default btn-with-tooltip invoices-total" data-toggle="tooltip" data-original-title="View Quick Stats"><i class="fa fa-bar-chart"></i></a><?php */?></div><div class="clearfix"></div></div></div>
        <div class="panel_s">
          <div class="panel-body panel-table-full mail-bg">
            <div class="mbot15">
              <div>
                <a href="javascript:void(0)" class="btn btn-sm btn-warning tw-rounded-full top-left-btn" title="ID"><i class="fa-solid fa-chart-gantt tw-mr-2"></i> Task - <?php echo $task['id']; ?></a>
               
              </div>
			  <div class="row">
			  <div class="col-sm-6"><h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                <span title="Task Name"><?php echo htmlspecialchars($task['task_name']); ?>  </span>
              </h4></div>
			  <div class="col-sm-3"><?php
                        if (!empty($task['task_owner'])) {
                            $ownerIds = explode(',', $task['task_owner']);
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
                      ?></div>
					  
<div class="col-sm-3">
<?php
if(isset($task['task_status']) && $task['task_status']){
$prod_status = proj_status_translate($task['task_status']);
} 
?>					  
					  <div class="btn tw-px-0">
                  <select class="form-control tw-text-white task-status-select" data-task-id="<?php echo $task['id']; ?>" name="task_status" style="background:<?php echo $prod_status->color; ?>; color:#FFFFFF;"  title="Task Status">
    <?php foreach($project_statuses as $ps): ?>
      <option value="<?php echo $ps['id']; ?>" <?php if($ps['id'] == $task['task_status']) echo 'selected'; ?>><?php echo $ps['name']; ?></option>
    <?php endforeach; ?>
  </select>
                </div>
<div class="btn tw-px-0">
<select class="form-control tw-text-white task-priority-select" data-task-id="<?php echo $task['id']; ?>" name="task_priority"  id="task_priority" style="background:<?php echo get_proj_priority_color($task['task_priority']); ?>; color:#FFFFFF;"    title="Task Priority">
    <?php foreach($project_priority as $pp): ?>
      <option value="<?php echo $pp['priorityid']; ?>" <?php if($pp['priorityid'] == $task['task_priority']) echo 'selected'; ?>><?php echo $pp['name']; ?></option>
    <?php endforeach; ?>
  </select>
<?php /*?><select class="form-control task-priority-select" data-task-id="<?php echo $task['id']; ?>" id="task_priority" name="task_priority" title="Task Priority">
 <option value="0" <?php if(isset($task['task_priority'])&&$task['task_priority']==0){?> selected="selected" <?php }?>>None</option>
 <option value="3" <?php if(isset($task['task_priority'])&&$task['task_priority']==3){?> selected="selected" <?php }?>>Low</option>
 <option value="2" <?php if(isset($task['task_priority'])&&$task['task_priority']==2){?> selected="selected" <?php }?>>Medium</option>
 <option value="1" <?php if(isset($task['task_priority'])&&$task['task_priority']==1){?> selected="selected" <?php }?>>High</option>
</select><?php */?>
            
</div>
</div>
			  </div>
              
<div class="tw-p-2" style="background: #F7F8F8;
background: -webkit-linear-gradient(to right, #ACBB78, #F7F8F8);
background: linear-gradient(to right, #ACBB78, #F7F8F8);
border-radius: 20px;
">
             <span title="Addeb By">By <?php echo isset($task['task_addedby']) ? get_staff_full_name($task['task_addedby']) : '-'; ?></span> | <span title="Project Title"><i class="fa-solid fa-chart-gantt menu-icon"></i> <?php echo isset($task['project_id']) ? get_project_title($task['project_id']) : '-'; ?></span> | <span title="Comments"><i class="fa-solid fa-comment-dots"></i></span> | <span title="Documents"><i class="fa-solid fa-paperclip"></i></span>
			 </div>

            </div>
            <hr class="hr-panel-separator">
            <div class="col-md-12 col-xs-12 mbot25">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv1')">Description <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv1" class="tw-border-neutral-200" style="display:none;">
<div class="panel-body tw-mb-4">
<div class="form-group">
<?php echo isset($task['task_description']) ? $task['task_description'] : '-'; ?>
</div>
</div>
</div>
</div>

<div class="col-md-12 col-xs-12 mbot25">
               
<div class="alert alert-warning" onclick="togglediv('#myDiv2')">Task Information <span class="pull-right mt-2 lead-view"><i class="fa-solid fa-angle-down"></i></span></div>
<div id="myDiv2" class="tw-border-neutral-200" >
<div class="panel-body tw-mb-4">

<form id="task-update-form">
<input type="hidden" name="task_id" value="<?php echo $task['id']; ?>">
<input type="hidden" name="task_status" value="<?php echo $task['task_status']; ?>">
<input type="hidden" name="task_priority" value="<?php echo $task['task_priority']; ?>">
<div class="form-group">
                <label for="task_owner" class="control-label"><small class="req text-danger">* </small><?php echo _l('Owner'); ?></label>
                <select class="form-control selectpicker" id="task_owner" name="task_owner[]" required multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" title="Owner">
                  <?php if (isset($staff_members) && is_array($staff_members)) { ?>
                    <?php foreach ($staff_members as $staff) { ?>
                      <?php 
                      $selected = '';
                      if (!empty($task['task_owner'])) {
                          $ownerIds = explode(',', $task['task_owner']);
                          if (in_array($staff['staffid'], $ownerIds)) {
                              $selected = 'selected';
                          }
                      }
                      ?>
                      <option data-content='<span class="tw-inline-flex tw-items-center"><?php echo str_replace("'", "&apos;", staff_profile_image($staff['staffid'], ["tw-h-6 tw-w-6 tw-rounded-full tw-inline-block tw-mr-2 tw-ring-2 tw-ring-white"], "small")); ?><span><?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?></span></span>' value="<?php echo $staff['staffid']; ?>" <?php echo $selected; ?>>
                        <?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?>
                      </option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
<div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="task_start_date" class="control-label"><small class="req text-danger">* </small><?php echo _l('Start Date'); ?></label>
                  <input type="date" class="form-control" id="task_start_date" name="task_start_date" value="<?php echo isset($task['task_start_date']) ? $task['task_start_date'] : ''; ?>" title="Select start date" required>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="task_end_date" class="control-label"><small class="req text-danger">* </small><?php echo _l('End Date'); ?></label>
                  <input type="date" class="form-control" id="task_end_date" name="task_end_date" value="<?php echo isset($task['task_end_date']) ? $task['task_end_date'] : ''; ?>" title="Select end date" required>
                </div>
              </div>
            </div>	
<div class="row">
              <div class="col-md-6">
<div class="form-group">
        <label>Tags</label>
        <input type="text" class="form-control" name="task_tags" id="tagsInput" value="<?php echo isset($task['task_tags']) ? htmlspecialchars($task['task_tags']) : ''; ?>">
      </div>
	  </div>
	  <div class="col-md-6">
	  <label for="task_start_date" class="control-label">Progress (%)</label>
	  <input type="number" min="0" max="100" step="1" oninput="validity.valid||(value='<?php echo $task['task_progress']; ?>');" name="task_progress" id="task_progress" class="form-control" value="<?php echo $task['task_progress']; ?>" required>
	  </div>
</div>
	  
	  
<div class="form-group">
  <button type="button" class="btn btn-primary" id="save-task-btn">
    <i class="fa fa-save"></i> Save Changes
  </button>
  <span id="save-status" class="text-muted ml-2"></span>
</div>

</form>

</div>
</div>
</div>
            <hr class="hr-panel-separator">
            <div class="row">
              <div class="col-md-12">
                <ul class="nav nav-tabs" id="taskTabs">
                  <li class="active"><a data-toggle="tab" href="#commentsX">Comments</a></li>
                  <li><a data-toggle="tab" href="#documentsX">Documents</a></li>
				  <li><a data-toggle="tab" href="#activityX">Activity Stream</a></li>
                </ul>
                <div class="tab-content" style="background:#fff; padding:20px; border:1px solid #ddd; border-top:0;">
                  <div id="commentsX" class="tab-pane fade in active">
                    <div>
					<?php echo form_open(admin_url('project/addcomments'), ['id' => 'add-comment-form', 'onsubmit' => 'return submitCommentForm(this);']); ?>
					<input type="hidden" name="project_id" value="<?php echo (int)$task['project_id']; ?>">
					<input type="hidden" name="task_id" value="<?php echo (int)$task['id']; ?>">
					<?php echo render_textarea('comments', '', '', [], [], '', 'tinymce'); ?>
					<button type="submit" class="btn btn-primary submit-loader">Submit</button>
					<span id="submit-loader-message" class="text-info tw-px-2"></span>
					<?php echo form_close(); ?>
					</div>
					
					
					
<div class="activity-feed tw-mt-2" style="max-height: 400px; overflow-y: auto;">
<div id="commentList"></div>
</div>

                  </div>
                  <div id="documentsX" class="tab-pane fade">
                    <h4>Documents</h4>
                    <?php
                        // Get task attachments
                        $CI = &get_instance();
                        $CI->db->where('task_id', $task['id']);
                        //$CI->db->where('rel_type', 'task');
                        $attachments = $CI->db->get(db_prefix() . 'project_documents')->result_array();
                        
                        if (!empty($attachments)) {
                            echo '<div class="tw-flexx -tw-space-x-1x">';
                            foreach ($attachments as $attachment) {
                                $file_ext = pathinfo($attachment['file_name'], PATHINFO_EXTENSION);
                                $icon_class = 'fa-file';
                                
                                // Set icon based on file type
                                if (in_array(strtolower($file_ext), ['pdf'])) {
                                    $icon_class = 'fa-file-pdf text-danger';
                                } elseif (in_array(strtolower($file_ext), ['doc', 'docx'])) {
                                    $icon_class = 'fa-file-word';
                                } elseif (in_array(strtolower($file_ext), ['xls', 'xlsx'])) {
                                    $icon_class = 'fa-file-excel text-success';
                                } elseif (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                                    $icon_class = 'fa-file-image';
                                } elseif (in_array(strtolower($file_ext), ['zip', 'rar'])) {
                                    $icon_class = 'fa-file-archive';
                                }
                                
 echo '<div><a href="' . base_url('uploads/project_task/' . $task['id'] . '/' . $attachment['file_name']) . '" target="_blank" data-toggle="tooltip" data-title="' . e($attachment['file_name']) . '" class="attachment-link">';
 echo '<i class="fa ' . $icon_class . ' tw-text-blue-500 tw-text-lg tw-p-1 tw-rounded hover:tw-bg-blue-100"></i>'. $attachment['file_name'];
 echo '</a></div>';
                            }
                            echo '</div>';
                        } else {
                            echo '-';
                        }
                      ?>
                  </div>
				  
				  <div id="activityX" class="tab-pane fade">
                    <h4>Activity Stream</h4>
					<div class="tab-content" style="background:#fff; padding:20px; border:1px solid #ddd; border-top:0;">
                   <?php //print_r($datalogs);
					foreach ($datalogs as $log) {
					$author = get_staff_full_name($log['staffid']);
                    $date   = _dt($log['date']);
echo '<div>';
echo '<div class="media-body"><h5 class="media-heading tw-font-semibold tw-mb-0"><div class="btn-group pull-right mleft5"></div>';
echo staff_profile_image($log['staffid'], ['staff-profile-image-small',]);
echo '<span class="tw-px-2">'.$author.'</span></h5><div class="tw-text-sm text-danger" style="padding-left: 40px;">'.$date.'</div>';
echo '<div class="tw-my-2" style="padding-left: 40px;">'. $log['description'].'</div></div></div>';
					 }
					 ?>
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
<script>


	
// Wait for jQuery to be available
function waitForJQuery(callback) {
  if (typeof jQuery !== 'undefined') {
    callback();
  } else {
    setTimeout(function() {
      waitForJQuery(callback);
    }, 100);
  }
}

function loadComments(){
  var projectId = <?php echo (int)$task['project_id']; ?>;
  var taskId = <?php echo (int)$task['id']; ?>;
  
  // Check if jQuery is available before using it
  if (typeof $ !== 'undefined') {
    $.get('<?php echo admin_url('project/getcomments'); ?>', {project_id: projectId, task_id: taskId}).done(function(html){
      $('#commentList').html(html);
    });
  } else {
    console.log('jQuery not available for loadComments');
  }
}

window.onload = function () {
    console.log('Window load event triggered');
    // Don't call loadComments here, wait for jQuery
  };

// Also try document ready as backup
if (typeof $ !== 'undefined') {
  $(document).ready(function() {
    console.log('Document ready backup triggered');
  });
}

function submitCommentForm(form){ 
  if (typeof $ === 'undefined') {
    console.error('jQuery not available for submitCommentForm');
    return false;
  }
  
  var $form = $(form);
  // Pull TinyMCE content if active
  if (typeof tinymce !== 'undefined' && tinymce.get('comments')) {
    $form.find('textarea[name="comments"]').val(tinymce.get('comments').getContent());
  }
  var formData = $form.serialize();
  $.post($form.attr('action'), formData).done(function(resp){
    var data;
    try { data = JSON.parse(resp); } catch(e){ data = {success:false}; }
    if (data && data.success) {
      // Clear editor/textarea
      if (typeof tinymce !== 'undefined' && tinymce.get('comments')) {
        tinymce.get('comments').setContent('');
      }
	  showFlashMessage('Comment added', 'success');
      $form.find('textarea[name="comments"]').val('');
	  $("#submit-loader-message").text("");
      loadComments();
    } else {
      //alert((data && data.message) ? data.message : 'Failed to add comment');
	  showFlashMessage('Failed to add comment', 'success');
    }
  }).fail(function(){
    alert('Server error');
  });
  return false; // Prevent default submission
}

// Wait for jQuery to be available before running main code
waitForJQuery(function() {
  console.log('jQuery is now available, version:', $.fn.jquery);
  
  $(function(){
    console.log('Document ready function started');
    console.log('jQuery version:', $.fn.jquery);
    console.log('jQuery object:', typeof $);
    
    // Simple test to verify jQuery is working
    $('body').append('<div id="jquery-test" style="display:none;">jQuery is working!</div>');
    console.log('jQuery test element created:', $('#jquery-test').length);
    
    // Simple alert test
    //alert('JavaScript is running! jQuery version: ' + $.fn.jquery);
    
    $('#taskTabs a').click(function(e){
      e.preventDefault();
      $(this).tab('show');
    });
    
    loadComments();

   

    // Initialize selectpicker for owner selection
    if (typeof init_selectpicker === 'function') {
      init_selectpicker();
    }

    // Debug: Check if form elements exist
    //console.log('Form elements check:');
    //console.log('- Task update form:', $('#task-update-form').length);
    //console.log('- Save button:', $('#save-task-btn').length);
    //console.log('- Owner select:', $('#task_owner').length);
    //console.log('- Task ID input:', $('input[name="task_id"]').val());

    // Wait a bit for DOM to be fully ready
    setTimeout(function() {
      //console.log('Delayed check - Form elements:');
      //console.log('- Task update form:', $('#task-update-form').length);
      //console.log('- Save button:', $('#save-task-btn').length);
      
      // Handle task update form submission
      $('#task-update-form').on('submit', function(e) {
        //console.log('Form submit event triggered!');
        e.preventDefault();
        e.stopPropagation();
        submitTaskUpdateForm();
        return false;
      });

      // Handle save button click
      $('#save-task-btn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Save button clicked!');
        submitTaskUpdateForm();
        return false;
      });

      // Test button to verify functionality
      <?php /*?>$('<button type="button" class="btn btn-info" id="test-debug-btn" style="margin-left: 10px;"><i class="fa fa-bug"></i> Test Debug</button>').insertAfter('#save-task-btn');<?php */?>
      
      <?php /*?>$('#test-debug-btn').on('click', function() {
        console.log('Test debug button clicked!');
        console.log('Form data test:');
        console.log('- Task ID:', $('input[name="task_id"]').val());
        console.log('- Selected owners:', $('#task_owner').val());
        console.log('- Start date:', $('input[name="task_start_date"]').val());
        console.log('- End date:', $('input[name="task_end_date"]').val());
        console.log('- Status:', $('select[name="task_status"]').val());
        console.log('- Priority:', $('select[name="task_priority"]').val());
        
        // Test form submission
        submitTaskUpdateForm();
      });<?php */?>
    }, 500);

    // Function to submit task update form
    function submitTaskUpdateForm() {
      var $form = $('#task-update-form');
      var $btn = $('#save-task-btn');
      var $status = $('#save-status');
      
      console.log('Submitting task update form...');
      
      // Get selected owners
      var selectedOwners = $('#task_owner').val();
      console.log('Selected owners:', selectedOwners);
      
      if (!selectedOwners || selectedOwners.length === 0) {
        alert('Please select at least one owner');
        return false;
      }

      // Get all form values
      var taskId = $form.find('input[name="task_id"]').val();
      var startDate = $form.find('input[name="task_start_date"]').val();
      var endDate = $form.find('input[name="task_end_date"]').val();
      var taskStatus = $form.find('input[name="task_status"]').val();
      var taskPriority = $form.find('input[name="task_priority"]').val();
	  var taskTags = $form.find('input[name="task_tags"]').val();
	  var taskProgress = $form.find('input[name="task_progress"]').val();
      
      console.log('Form values:', {
        taskId: taskId,
        selectedOwners: selectedOwners,
        startDate: startDate,
        endDate: endDate,
        taskStatus: taskStatus,
        taskPriority: taskPriority,
		taskTags: taskTags
      });

      // Prepare form data
      var formData = {
        task_id: taskId,
        task_owner: selectedOwners.join(','),
        task_start_date: startDate,
        task_end_date: endDate,
        task_status: taskStatus,
        task_priority: taskPriority,
		task_progress: taskProgress,
		task_tags: taskTags
      };
      
      console.log('Form data to send:', formData);

      // Show loading state
      $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
      $status.html('Saving changes...').removeClass('text-success text-danger').addClass('text-info');

      // Send AJAX request
      $.ajax({
        url: '<?php echo admin_url('project/update_task_info'); ?>',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function(response) {
          console.log('Success response:', response);
          if (response.success) {
            $status.html('Changes saved successfully!').removeClass('text-info text-danger').addClass('text-success');
            // Update the owner display in the header
            updateOwnerDisplay(selectedOwners);
            // Update status and priority displays if they changed
            if (response.data && response.data.status_color) {
              $('select[name="task_status"]').css('background', response.data.status_color);
            }
          } else {
            $status.html('Failed to save: ' + (response.message || 'Unknown error')).removeClass('text-info text-success').addClass('text-danger');
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error Details:', {xhr: xhr, status: status, error: error});
          console.error('Response Text:', xhr.responseText);
          $status.html('Error: ' + error).removeClass('text-info text-success').addClass('text-danger');
        },
        complete: function() {
          $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Changes');
          // Clear status after 3 seconds
          setTimeout(function() {
            $status.html('').removeClass('text-success text-danger text-info');
          }, 3000);
        }
      });
    }

    // Function to update owner display in header
    function updateOwnerDisplay(ownerIds) {
      var $ownerContainer = $('.col-sm-4 .tw-flex');
      if (ownerIds && ownerIds.length > 0) {
        var html = '<div class="tw-flex -tw-space-x-1">';
        ownerIds.forEach(function(oid) {
          if (oid && oid.trim() !== '') {
            // Get staff name from the select option
            var $option = $('#task_owner option[value="' + oid + '"]');
            var staffName = $option.text().trim();
            
            html += '<a href="' + '<?php echo admin_url('profile/'); ?>' + oid + '" data-toggle="tooltip" data-title="' + staffName + '">' +
                    '<img src="' + '<?php echo base_url('uploads/staff_profile_images/'); ?>' + oid + '.jpg" ' +
                    'class="tw-h-7 tw-w-7 tw-inline-block tw-rounded-full tw-ring-2 tw-ring-white" ' +
                    'onerror="this.src=\'<?php echo base_url('assets/images/user-placeholder.jpg'); ?>\'" ' +
                    'alt="' + staffName + '">' +
                    '</a>';
          }
        });
        html += '</div>';
        $ownerContainer.html(html);
      } else {
        $ownerContainer.html('-');
      }
    }
  });
});

// Toggle slider
function togglediv(divdata){
  if (typeof $ !== 'undefined') {
    $(divdata).slideToggle(); // Animated toggle
  } else {
    // Fallback without jQuery
    var element = document.querySelector(divdata);
    if (element) {
      element.style.display = element.style.display === 'none' ? 'block' : 'none';
    }
  }
}


</script>
<?php init_tail(); ?>
<script>
$('.submit-loader').click(function(e){
      var content = tinymce.get("comments").getContent();
	  //alert(content);
	  $("#submit-loader-message").text("");
        if(content === ""){
            e.preventDefault(); // stop form submit
            $("#submit-loader-message").text("Comments cannot be blank!");
			return false;
        } else{
		$("#submit-loader-message").text("Process ....");
		}
});
</script>
<!-- Tagify CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tagify/4.17.8/tagify.min.js"></script>
<script>
// Initialize Tagify
var input = document.querySelector('#tagsInput');
new Tagify(input);
</script>
</body></html>
