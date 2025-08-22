<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>

                        <form action="<?php echo admin_url('project_chat/new_conversation'); ?>" method="post" id="newConversationForm">
                            <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                            
                            <div class="form-group">
                                <label for="project_id">Select Project <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="project_id" name="project_id" required data-live-search="true">
                                    <option value="">Choose a project...</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?php echo $project['id']; ?>">
                                            <?php echo htmlspecialchars($project['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="title">Conversation Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required 
                                       placeholder="Enter conversation title...">
                                <small class="text-muted">Give your conversation a descriptive title</small>
                            </div>

                            <div class="form-group">
                                <label for="participants">Select Participants <span class="text-danger">*</span></label>
                                <select class="form-control selectpicker" id="participants" name="participants[]" required multiple data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" title="Participants">
                  <?php if (isset($staff_members) && is_array($staff_members)) { ?>
				  
                    <?php foreach ($staff_members as $staff) { ?>
                   
                      <option data-content='<span class="tw-inline-flex tw-items-center"><?php echo str_replace("'", "&apos;", staff_profile_image($staff['staffid'], ["tw-h-6 tw-w-6 tw-rounded-full tw-inline-block tw-mr-2 tw-ring-2 tw-ring-white"], "small")); ?><span><?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?></span></span>' value="<?php echo $staff['staffid']; ?>">
                        <?php echo e($staff['firstname'] . ' ' . $staff['lastname']); ?>
                      </option>
                    <?php } ?>
                  <?php } ?>
                </select>
                                <small class="text-muted">Select team members to include in this conversation</small>
                            </div>

                            <div class="form-group text-right">
                                <a href="<?php echo admin_url('project_chat'); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back to Conversations
                                </a>
                                <button type="submit" class="btn btn-info">
                                    <i class="fa fa-comments"></i> Start Conversation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    // Initialize Select2 for participants
    $('#participants').select2({
        placeholder: "Select participants...",
        allowClear: true,
        width: '100%'
    });

    // Initialize Bootstrap Select for projects
    $('.selectpicker').selectpicker();

    // Form validation
    $('#newConversationForm').on('submit', function(e) {
        var projectId = $('#project_id').val();
        var title = $('#title').val().trim();
        var participants = $('#participants').val();

        if (!projectId) {
            alert('Please select a project');
            e.preventDefault();
            return false;
        }

        if (!title) {
            alert('Please enter a conversation title');
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

    // Auto-generate title based on project selection
    $('#project_id').on('change', function() {
        var selectedText = $(this).find('option:selected').text();
        var currentTitle = $('#title').val();
        
        if (!currentTitle && selectedText !== 'Choose a project...') {
            $('#title').val(selectedText + ' Discussion');
        }
    });
});
</script>

<style>
.form-group {
    margin-bottom: 20px;
}
.select2-container {
    width: 100% !important;
}
.select2-selection--multiple {
    min-height: 38px;
}
</style>
