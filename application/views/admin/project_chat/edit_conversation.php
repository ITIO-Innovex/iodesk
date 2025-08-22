<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
                                </h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="<?php echo admin_url('project_chat'); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back to Conversations
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />
                        
                        <form action="<?php echo admin_url('project_chat/edit_conversation/' . $conversation['id']); ?>" method="post" id="edit-conversation-form">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="control-label">Conversation Title <span class="text-danger">*</span></label>
                                    <input type="text" id="title" name="title" class="form-control" value="<?php echo set_value('title', $conversation['title']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id" class="control-label">Project <span class="text-danger">*</span></label>
                                    <select name="project_id" id="project_id" class="form-control selectpicker" data-live-search="true" required>
                                        <option value="">Select Project</option>
                                        <?php foreach($projects as $project): ?>
                                            <option value="<?php echo $project['id']; ?>" <?php echo set_select('project_id', $project['id'], ($project['id'] == $conversation['project_id'])); ?>>
                                                <?php echo $project['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="participants" class="control-label">Participants <span class="text-danger">*</span></label>
									<select class="form-control selectpicker" id="participants" name="participants[]" required multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" title="Participants">
                  <?php if (isset($staff_members) && is_array($staff_members)) { 
				  $current_participant_ids = array_column($current_participants, 'staff_id');
				  ?>
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
                      <option data-content='<span class="tw-inline-flex tw-items-center"><?php echo str_replace("'", "&apos;", staff_profile_image($staff['staffid'], ["tw-h-6 tw-w-6 tw-rounded-full tw-inline-block tw-mr-2 tw-ring-2 tw-ring-white"], "small")); ?><span><?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?></span></span>' value="<?php echo $staff['staffid']; ?>" <?php echo in_array($staff['staffid'], $current_participant_ids) ? 'selected' : ''; ?>>
                        <?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?>
                      </option>
                    <?php } ?>
                  <?php } ?>
                </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <button type="submit" class="btn btn-info">Update Conversation</button>
                                <a href="<?php echo admin_url('project_chat'); ?>" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Select2 for participants
    $('#participants').select2({
        placeholder: "Select participants",
        allowClear: true,
        width: '100%'
    });
    
    // Initialize selectpicker for project dropdown
    $('.selectpicker').selectpicker();
    
    // Form validation
    $('#edit-conversation-form').on('submit', function(e) {
        var title = $('#title').val().trim();
        var project_id = $('#project_id').val();
        var participants = $('#participants').val();
        
        if (!title) {
            alert('Please enter a conversation title');
            e.preventDefault();
            return false;
        }
        
        if (!project_id) {
            alert('Please select a project');
            e.preventDefault();
            return false;
        }
        
        if (!participants || participants.length === 0) {
            alert('Please select at least one participant');
            e.preventDefault();
            return false;
        }
        
        return true;
    });
});
</script>

<?php init_tail(); ?>
