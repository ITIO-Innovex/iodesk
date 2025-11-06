// JavaScript Document

// For Add / Remove Signature
$('#toggleSignature').change(function() { 
  // Get current content
  
  //alert(content);
  //alert(signature);
     if (this.checked) { 
	 let content = $('.editor').val() || $('.editor').html();
	 //alert("Add");
	 		// Append signature (if not already present)
			if (!content.includes(signature)) {
				content += signature;
			}
			// Set content back
			$('.editor').jqteVal(content);    // for <div contenteditable> or WYSIWYG
			$('.editor').val(content);     // for <textarea>
			
	 }else{ 
	 //alert("Remove");
	 let content = $('.editor').val() || $('.editor').html();
			// Remove signature
			content = content.replace(signature, '').trim();
			
			// Set content back
			$('.editor').jqteVal(content);    // for <div contenteditable> or WYSIWYG
			$('.editor').val(content);     // for <textarea>

	 }
  });

// End Add / Remove Signature

// Project ellipsis dropdown toggle
$(document).on('click', '.project-dropdown-toggle', function(e) {
  e.stopPropagation();
  // Close any open dropdowns
  $('.project-dropdown-menu').hide();
  // Open the clicked one
  $(this).siblings('.project-dropdown-menu').toggle();
});
// Close dropdown when clicking outside
$(document).on('click', function() {
  $('.project-dropdown-menu').hide();
});
// Prevent closing when clicking inside the dropdown
$(document).on('click', '.project-dropdown-menu', function(e) {
  e.stopPropagation();
});

// Inline project status update
$(document).on('change', '.project-status-select', function() {
															
if (!confirm("Are you sure you want to change status?")) {return false; }

  var $select = $(this);
  var projectId = $select.data('project-id');
  var newStatus = $select.val();
  $("#loader-project").fadeIn();
  $select.prop('disabled', true);
  $.ajax({
    url: window.admin_url + 'project/update_status',
    method: 'POST',
    data: { project_id: projectId, status: newStatus },
    dataType: 'json',
    success: function(res) {
      if(res.success) {
        // Optionally update color
        var color = res.color || '#666';
        $select.css({background: color});
		$select.closest('td').css({background: color});
        // Optionally show notification
        //if(window.toastr) toastr.success('Project status updated');
		showFlashMessage('Project status updated', 'success');
      } else {
        //if(window.toastr) toastr.error('Failed to update status');
		showFlashMessage('Failed to update status', 'failed');
      }
	  $("#loader-project").fadeOut();
    },
    error: function() {
      //if(window.toastr) toastr.error('Failed to update status');
	  showFlashMessage('Failed to update status', 'failed');
	  $("#loader-project").fadeOut();
    },
    complete: function() {
      $select.prop('disabled', false);
    }
  });
});

// Inline project owner update
$(document).on('change', '.project-owner-select', function() {
if (!confirm("Are you sure you want to change owner?")) {return false; }
  var $select = $(this);
  var projectId = $select.data('project-id');
  var newOwner = $select.val();
  $("#loader-project").fadeIn();
  $select.prop('disabled', true);
  $.ajax({
    url: window.admin_url + 'project/update_owner',
    method: 'POST',
    data: { project_id: projectId, owner: newOwner },
    dataType: 'json',
    success: function(res) { //alert(JSON.stringify(res));
      if(res.success) { 
        //if(window.toastr) toastr.success('Project owner updated');
		showFlashMessage('Project owner updated', 'success');
      } else {
        //if(window.toastr) toastr.error('Failed to update owner');
		showFlashMessage('Failed to update owner', 'failed');
      }
	  $("#loader-project").fadeOut();
    },
    error: function(res) {
		//alert(JSON.stringify(res));
		//alert(9999);
      //if(window.toastr) toastr.error('Failed to update owner');
	  showFlashMessage('Failed to update owner', 'failed');
	  $("#loader-project").fadeOut();
    },
    complete: function() {
      $select.prop('disabled', false);
    }
  });
});

// Edit Project modal open handler
$(document).on('click', '.project-dropdown-menu .dropdown-item[href*="/edit/"]', function(e) {
  e.preventDefault();
  var url = $(this).attr('href');
  var projectId = url.split('/').pop();
  // Fetch project data
  $.ajax({
    url: window.admin_url + 'project/get_project',
    method: 'GET',
    data: { project_id: projectId },
    dataType: 'json',
    success: function(res) {
      if(res.success && res.data) {
       // var p = res.data;
		var p = res.data[0]; 
        $('#edit_project_id').val(p.id);
        $('#edit_project_title').val(p.project_title);
        $('#edit_owner').val(p.owner);
        $('#edit_project_group').val(p.project_group);
        $('#edit_start_date').val(p.start_date);
        $('#edit_deadline').val(p.deadline);
		$('#edit_tags').val(p.tags);
		$('#edit_project_description').jqteVal(p.project_description);
        //if(window.tinymce && tinymce.get('edit_project_description')) {
          //tinymce.get('edit_project_description').setContent(p.project_description || '');
        //} else {
          //$('#edit_project_description').val(p.project_description);
        //}
        // Set strict project checkbox
        $('#edit_make_this_a_strict_project').prop('checked', p.make_this_a_strict_project == 1);
        // Set project access radio buttons
        if(p.project_access == 1) {
          $('#edit_project_access_private').prop('checked', true);
        } else if(p.project_access == 2) {
          $('#edit_project_access_public').prop('checked', true);
        }
        // Tags
      
        $('#editProjectModal').modal('show');
      } else {
        //if(window.toastr) toastr.error('Failed to load project data');
		showFlashMessage('Failed to load project data!', 'failed');
      }
    },
    error: function() {
      //if(window.toastr) toastr.error('Failed to load project data');
	  showFlashMessage('Failed to load project data!', 'failed');
    }
  });
});

// Add Attendance
$(document).on('click', '.attendance-submit', function(e) {
  e.preventDefault();
  //alert(11);
  var mode = $(this).attr('data-mode');
  
  //alert(mode);
  //var projectId = url.split('/').pop();
  // Fetch project data
  $.ajax({
    url: window.admin_url + 'hrd/add_attendance',
    method: 'GET',
    data: { mode: mode },
    dataType: 'json',
    success: function(res) {
      if(res.success && res.data) {
       // var p = res.data;
		var p = res.data[0];
		//alert(mode);
		if(mode=='Out'){
		showFlashMessage('Mark Out Completed', 'success');
		}else{
		showFlashMessage('Mark In Completed', 'success');	
		}
		setTimeout(function() {
        window.location.href = window.admin_url + 'hrd/dashboard';
      }, 2000); // 1 second delay
      } else {
        //if(window.toastr) toastr.error('Failed to load project data');
		showFlashMessage('Attendance Failed , Try Again', 'failed');
      }
    },
    error: function() {
      //if(window.toastr) toastr.error('Failed to load project data');
	  showFlashMessage('Attendance Failed , Try Again', 'failed');
    }
  });
});

// Edit Project modal form submit
$(document).on('submit', '#edit-project-form', function(e) {
  e.preventDefault();
  var form = this;
  var formData = new FormData(form);
  // Get TinyMCE content if present
  if(window.tinymce && tinymce.get('edit_project_description')) {
    formData.set('project_description', tinymce.get('edit_project_description').getContent());
  }
  // Get tags from tag items
  var tags = [];
  $('#edit-tags-container .tag-item').each(function() {
    var tagText = $(this).clone().children().remove().end().text().trim();
    if(tagText) tags.push(tagText);
  });
  formData.set('tags', tags.join(','));
  console.log('Submitting tags:', tags.join(',')); // Debug log
  var $btn = $(form).find('button[type="submit"]');
  var origText = $btn.text();
  $btn.prop('disabled', true).text('Saving...');
  $.ajax({
    url: form.action,
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    dataType: 'json',
    success: function(res) {
      if(res.success) {
        $('#editProjectModal').modal('hide');
        //if(window.toastr) toastr.success(res.message || 'Project updated');
		
        window.location.reload();
		showFlashMessage('Project updated!', 'success');
      } else {
        //if(window.toastr) toastr.error(res.message || 'Failed to update project');
		showFlashMessage('Failed to update project!', 'failed');
      }
    },
    error: function() {
     //if(window.toastr) toastr.error('Failed to update project');
	  showFlashMessage('Failed to update project!', 'failed');
    },
    complete: function() {
      $btn.prop('disabled', false).text(origText);
    }
  });
});

$(document).on('change', '.task-status-select', function() {
  if (!confirm("Are you sure you want to change task status?")) {return false; }
  var $select = $(this);
  var taskId = $select.data('task-id');
  var newStatus = $select.val();
  $("#loader-project").fadeIn();
  $select.prop('disabled', true);
  $.ajax({
    url: window.admin_url + 'project/update_task_status',
    method: 'POST',
    data: { task_id: taskId, status: newStatus },
    dataType: 'json',
    success: function(res) {
      if(res.success) {
        var color = res.color || '#666';
        $select.css({background: color});
		$select.closest('td').css({background: color});
        showFlashMessage('Task status saved successfully!', 'success');
      } else {
        showFlashMessage('Failed to update task status!', 'failed');
      
      }
	  $("#loader-project").fadeOut();
    },
    error: function() {
      //if(window.toastr) toastr.error('Failed to update task status');
	  showFlashMessage('Failed to update task status!', 'failed');
	  $("#loader-project").fadeOut();
    },
    complete: function() {
      $select.prop('disabled', false);
    }
  });
});

$(document).on('change', '.document-status-select', function() {
  if (!confirm("Are you sure you want to change document status?")) {return false; }
  var $select = $(this);
  var docId = $select.data('doc-id');
  var newStatus = $select.val();
  $("#loader-project").fadeIn();
  $select.prop('disabled', true);
  //alert(docId);
  // alert(newStatus);
  $.ajax({
    url: window.admin_url + 'user_utility/update_doc_status',
    method: 'POST',
    data: { doc_id: docId, status: newStatus },
    dataType: 'json',
    success: function(res) {
      if(res.success) {
        var color = res.color || '#666';
        $select.css({background: color});
		$select.closest('td').css({background: color});
        showFlashMessage('Document status saved successfully!', 'success');
      } else {
        showFlashMessage('Failed to update Document status!', 'failed');
      
      }
	  $("#loader-project").fadeOut();
    },
    error: function() {
      //if(window.toastr) toastr.error('Failed to update task status');
	  showFlashMessage('Failed to update task status!', 'failed');
	  $("#loader-project").fadeOut();
    },
    complete: function() {
      $select.prop('disabled', false);
    }
  });
});

$(document).on('change', '.task-priority-select', function() {
  if (!confirm("Are you sure you want to change priority?")) {return false; }
  var $select = $(this);
  var taskId = $select.data('task-id');
  var newStatus = $select.val();
  $("#loader-project").fadeIn();
  $select.prop('disabled', true);
  $.ajax({
    url: window.admin_url + 'project/update_task_priority',
    method: 'POST',
    data: { task_id: taskId, status: newStatus },
    dataType: 'json',
    success: function(res) {
      if(res.success) {
        var color = res.color || '#666';
        $select.css({background: color});
		$select.closest('td').css({background: color});
		
		showFlashMessage('Project saved successfully!', 'success');
      } else {
        showFlashMessage('Failed to update task priority!', 'failed');
      }
	  $("#loader-project").fadeOut();
    },
    error: function() {
	  showFlashMessage('Failed to update task priority!', 'failed');
	  $("#loader-project").fadeOut();
    },
    complete: function() {
      $select.prop('disabled', false);
    }
  });
});
function showFlashMessage(message, type = 'success') {
    let $flash = $('#flash-message');

    $flash
        .removeClass()
        .addClass(type === 'success' ? 'flash-success' : 'flash-error')
        .text(message)
        .fadeIn();

    // Auto-hide after 3 seconds
    setTimeout(function() {
        $flash.fadeOut();
    }, 2000);
}

(function($){
      // Helper to pad numbers
      function pad(n){ return n < 10 ? '0' + n : '' + n; }

      // Update clock (uses client's local time)
      function updateClock(){
        var now = new Date(); // uses user's browser time
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();

        // 12-hour format with AM/PM
        var ampm = hours >= 12 ? 'PM' : 'AM';
        var hours12 = hours % 12;
        if (hours12 === 0) hours12 = 12;

        // Set text
        $('#dc-hours-min').text(pad(hours12) + ':' + pad(minutes));
        $('#dc-seconds').text(':' + pad(seconds));
        $('#dc-ampm').text(ampm);
      }

      // Start: update immediately and then every 1 second
      $(function(){
        updateClock();
        // Use setInterval to update every 1000ms. This is fine for clocks.
        setInterval(updateClock, 1000);
      });
    })(jQuery);