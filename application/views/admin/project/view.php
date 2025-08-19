<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.panel-body.panel-table-full.mail-bg {
  position: relative; /* ensures button is positioned within this div */
}

.top-left-btn {
  position: absolute;
  top: -20px;
  left: 10px;
  z-index: 10;
  padding: 8px 12px;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
.top-left-btn.mg {
  left: 135px;
}
</style>
<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success">
    <?php echo $this->session->flashdata('success'); ?>
  </div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger">
    <?php echo $this->session->flashdata('error'); ?>
  </div>
<?php endif; ?>
<div id="wrapper">
  <div class="content">
    <div class="row ">
      <div class="col-md-12">
	  <div class="tw-mb-2 sm:tw-mb-4"><div class="_buttons"> <div class="display-block pull-right tw-space-x-0 sm:tw-space-x-1.5"><a href="<?php echo admin_url('project/');?>" class="btn btn-default btn-with-tooltip toggle-small-view hidden-xs" data-toggle="tooltip" data-original-title="Back to project" ><i class="fa-solid fa-chart-gantt menu-icon"></i></a>

<?php /*?><a href="#" class="btn btn-default btn-with-tooltip invoices-total" data-toggle="tooltip" data-original-title="View Quick Stats"><i class="fa fa-bar-chart"></i></a><?php */?></div><div class="clearfix"></div></div></div>
       <?php /*?> <div class="tw-mb-2 sm:tw-mb-4">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
            <!--<i class="fa-solid fa-chart-area tw-mx-2"></i>-->
            Project Details: <?php echo htmlspecialchars($project['project_title']); ?></h4>
        </div><?php */?>
        <div class="panel_s">
          <div class="panel-body panel-table-full mail-bg">
            <div class="mbot15">
			<div><a href="javascript:void(0)" class="btn btn-sm btn-warning tw-rounded-full top-left-btn" title="ID"><i class="fa-solid fa-chart-gantt tw-mr-2"></i> Project - <?php echo $project['id']; ?></a>
			 <?php
			 if(isset($project['project_status'])&&$project['project_status']){
			 $prod_status=proj_status_translate($project['project_status']);
			 ?>
			 <a href="javascript:void(0)" style=" background:<?php echo $prod_status->color; ?>;" class="btn btn-sm tw-rounded-full top-left-btn mg" title="Status"><?php echo $prod_status->name; ?></a>
			 <?php } ?>
			</div>
              <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-flex tw-items-center">
                <span> Project Summary :  <?php echo htmlspecialchars($project['project_title']); ?></span> </h4>
              <div class="tw-grid tw-grid-cols-2 md:tw-grid-cols-3 lg:tw-grid-cols-6 tw-gap-2">
                <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center"> <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg"> <?php echo isset($project['total_tasks']) ? _d($project['total_tasks']) : '0'; ?> </span> <span class="text-warning tw-truncate sm:tw-text-clip">Total Task</span> </div>
                <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center"> <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg"> <?php echo isset($project['total_milestones']) ? _d($project['total_milestones']) : '0'; ?></span> <span class="text-success tw-truncate sm:tw-text-clip">Total Milestone</span> </div>
                <div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center"> <span class="tw-font-semibold tw-mr-3 rtl:tw-ml-3 tw-text-lg"> <?php echo isset($project['total_issues']) ? _d($project['total_issues']) : '0'; ?></span> <span class="text-danger tw-truncate sm:tw-text-clip">Total Issues</span> </div>
				<div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center"> <span class="text-info tw-truncate sm:tw-text-clip" title="Progress"><?php echo get_project_percentage($project['id']); ?> %</span> </div>
				<div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center"> <span class="text-primary tw-truncate sm:tw-text-clip" title="Group"><?php echo isset($project['project_group_name']) ? $project['project_group_name'] : '-'; ?> </span> </div>
				<div class="md:tw-border-r md:tw-border-solid md:tw-border-neutral-300 tw-flex-1 tw-flex tw-items-center"><span class="text-secondary tw-truncate sm:tw-text-clip" title="Project Access"><?php echo isset($project['project_access']) ? get_project_access($project['project_access']) : '-'; ?></span> </div>
				
              </div>
            </div>
			
      <hr class="hr-panel-separator">
	  <div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6" style="background: cadetblue;">
	
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-circle-user tw-mx-2"></i> Owner</h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md">
<?php echo isset($project['owner']) ? staff_profile_image($project['owner'], ['staff-profile-image-small',]) : '-'; ?>&nbsp;
				<?php echo isset($project['owner']) ? get_staff_full_name($project['owner']) : '-'; ?></a> 
		  </li>
        </ul>
      </div>
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-calendar-days tw-mx-2"></i> Start Date</h4>
        <ul class="reports tw-space-y-1">
		  <li> <a class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><?php echo isset($project['start_date']) ? _d($project['start_date']) : '-'; ?></a> 
		  </li>
        </ul>
      </div>
	  
      <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-calendar-days tw-mx-2"></i> Deadline</h4>
        <ul class="reports tw-space-y-1">
		  <li> <a href="" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><?php echo isset($project['deadline']) ? _d($project['deadline']) : '-'; ?></a> 
		  </li>
        </ul>
      </div>
	  
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-calendar-days tw-mx-2"></i> Completed</h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><?php echo isset($project['date_finished']) ? _d($project['date_finished']) : '-'; ?></a> 
		  </li>
        </ul>
      </div>
	  
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-location-dot tw-mx-2"></i> IP</h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><?php echo isset($project['ip']) ? _d($project['ip']) : '-'; ?></a> 
		  </li>
        </ul>
      </div>
	  
    </div>
			<hr class="hr-panel-separator">
			
			<div class="row">
			<div class="">
			
			<ul class="nav nav-tabs" id="projectTabs">
    <li class="active"><a data-toggle="tab" href="#desc">Description</a></li>
    <li><a data-toggle="tab" href="#info">Project Information</a></li>
    <li><a data-toggle="tab" href="#comments">Comments (<?php echo count($datacomments);?>)</a></li>
    <li><a data-toggle="tab" href="#activity">Activity Stream (<?php echo count($datalogs);?>)</a></li>
  </ul>
  
            <div class="tab-content" style="background:#fff; padding:20px; border:1px solid #ddd; border-top:0;">
    <div id="desc" class="tab-pane fade in active">
      <h4>Description</h4>
      <div><?php echo !empty($project['project_description']) ? $project['project_description'] : '<em>No description provided.</em>'; ?></div>
    </div>
    <div id="info" class="tab-pane fade">
      <h4>Project Information</h4>
      <?php echo form_open(admin_url('project/updateproject'), ['id' => 'edit-project-form-details']); ?>
      <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
	  <input type="hidden" name="path" value="9">
      <div class="form-group">
        <label><small class="req text-danger">* </small>Project Title</label>
        <input type="text" class="form-control" name="project_title" value="<?php echo htmlspecialchars($project['project_title']); ?>" title="Project Title" required>
      </div>
	  
	  <div class="row">
      <div class="col-md-6">
      <div class="form-group">
        <label><small class="req text-danger">* </small>Owner</label>
        <select class="form-control" name="owner" title="Owner" required>
          <?php foreach ($staff_members as $staff) { ?>
            <option value="<?php echo $staff['staffid']; ?>" <?php if($staff['staffid'] == $project['owner']) echo 'selected'; ?>><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
          <?php } ?>
        </select>
      </div>
	  </div>
	  <div class="col-md-6">
      <div class="form-group">
        <label><small class="req text-danger">* </small>Project Group</label>
        <select class="form-control" name="project_group" title="Project Group" required>
          <option value="">Select Group</option>
          <?php foreach ($project_groups as $group) { ?>
            <option value="<?php echo $group['id']; ?>" <?php if($group['id'] == $project['project_group']) echo 'selected'; ?>><?php echo $group['name']; ?></option>
          <?php } ?>
        </select>
      </div>
	  </div></div>
	  
	  <div class="row">
      <div class="col-md-6">
      <div class="form-group">
        <label><small class="req text-danger">* </small>Start Date</label>
        <input type="date" class="form-control" name="start_date" value="<?php echo $project['start_date']; ?>" title="Select start date" required>
      </div>
	  </div>
	  <div class="col-md-6">
      <div class="form-group">
        <label><small class="req text-danger">* </small>End Date</label>
        <input type="date" class="form-control" name="deadline" value="<?php echo $project['deadline']; ?>" title="Select end date" required>
      </div>
      </div></div>
	  <?php 
	  $project_description=$project['project_description'];
	  echo render_textarea('project_description', '', $project_description, ['required' => 'true'], [], '', 'tinymce'); ?>
      <div class="form-group">
        <label>Tags</label>
        <input type="text" class="form-control" name="edit_tags" id="tagsInput" value="<?php echo isset($project['tags']) ? htmlspecialchars($project['tags']) : ''; ?>">
      </div>
      <div class="row">
      <div class="col-md-6">
      <div class="form-group">
        <?php $strict = isset($project['make_this_a_strict_project']) && ($project['make_this_a_strict_project'] == 1 || $project['make_this_a_strict_project'] === '1'); ?>
        <input type="checkbox" value="1" name="make_this_a_strict_project" id="edit_make_this_a_strict_project" <?php echo $strict ? 'checked' : ''; ?>>
        <label for="edit_make_this_a_strict_project">Make this a strict project <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Tasks, issues, milestones etc., cannot start or end outside the scheduled dates of a strict project."></i></label>
      </div>
	  </div>
	  <div class="col-md-6">
      <div class="form-group">
        <?php $access = isset($project['project_access']) ? $project['project_access'] : 1; ?>
        <label for="edit_project_access">Project Access <i class="fa-solid fa-circle-info" data-toggle="tooltip" data-title="Private : Only project users can view and access this project. and Public : Portal users can only view, follow, and comment whereas, project users will have complete access."></i></label>
        <div class="radio radio-primary radio-inline">
            <input type="radio" name="project_access" value="1" id="edit_project_access_private" <?php echo ($access == 1 || $access === '1') ? 'checked' : ''; ?>>
            <label for="edit_project_access_private">Private</label>
        </div>
        <div class="radio radio-primary radio-inline">
            <input type="radio" name="project_access" value="2" id="edit_project_access_public" <?php echo ($access == 2 || $access === '2') ? 'checked' : ''; ?>>
            <label for="edit_project_access_public">Public</label>
        </div>
      </div>
	  </div></div>
      
      <button type="submit" class="btn btn-primary">Save Changes</button>
      <?php echo form_close(); ?>
    </div>
    <div id="comments" class="tab-pane fade">
      <h4>Comments</h4>
      <div class="activity-feed tw-mt-2" style="max-height: 400px; overflow-y: auto;">
<?php //print_r($datalogs);
if(isset($datacomments)&& count($datacomments) >0){
foreach ($datacomments as $com) {
$author = get_staff_full_name($com['addedby']);
$date   = _dt($com['addedon']);
?>
<div>
<div class="media-body"><h5 class="media-heading tw-font-semibold tw-mb-0"><div class="btn-group pull-right mleft5"></div>
<?php echo staff_profile_image($com['addedby'], ['staff-profile-image-small',]); ?>
<span class="tw-px-2"><?php echo $author; ?></span></h5><div class="tw-text-sm text-danger" style="padding-left: 40px;"><?php echo $date; ?></div>
<div class="tw-my-2" style="padding-left: 40px;"><?php echo $com['comments']; ?></div></div></div>
<?php
}
}else{ ?>
<div class="alert alert-info">Comments not found.</div>					 
<?php } ?>
					 </div>
    </div>
    <div id="activity" class="tab-pane fade">
      <h4>Activity Stream</h4>
      <div class="activity-feed tw-mt-2" style="max-height: 400px; overflow-y: auto;">
<?php //print_r($datalogs);
if(isset($datalogs)&& count($datalogs) >0){
foreach ($datalogs as $log) {
$author = get_staff_full_name($log['staffid']);
$date   = _dt($log['date']);
?>
<div>
<div class="media-body"><h5 class="media-heading tw-font-semibold tw-mb-0"><div class="btn-group pull-right mleft5"></div>
<?php echo staff_profile_image($log['staffid'], ['staff-profile-image-small',]); ?>
<span class="tw-px-2"><?php echo $author; ?></span></h5><div class="tw-text-sm text-danger" style="padding-left: 40px;"><?php echo $date; ?></div>
<div class="tw-my-2" style="padding-left: 40px;"><?php echo $log['description']; ?></div></div></div>
<?php
}
}else{ ?>
<div class="alert alert-info">Activity stream not found.</div>					 
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
</div>
<script>
$(function(){
  $('#projectTabs a').click(function(e){
    e.preventDefault();
    $(this).tab('show');
  });
});

$('.toggle-btn').on('click', function() {
  var target = $(this).data('target');
  $(target).slideToggle(); // or use .toggle() / .fadeToggle()
});
</script>
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