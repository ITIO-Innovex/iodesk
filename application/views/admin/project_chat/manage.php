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
                                <h4 class="no-margin"><?php echo $title; ?></h4>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="<?php echo admin_url('project_chat/new_conversation'); ?>" class="btn btn-info">
                                    <i class="fa fa-plus"></i> Start New Conversation
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Conversations Table -->
                        <div class="table-responsive">
                            <table class="table dt-table table-striped" id="conversationsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Project</th>
                                        <th>Participants</th>
                                        <th>Created By</th>
                                        <th>Last Activity</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($conversations)): ?>
                                        <?php foreach ($conversations as $conversation): ?>
                                            <tr>
                                                <td><?php echo $conversation['id']; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($conversation['title']); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="label label-primary">
                                                        <?php echo htmlspecialchars($conversation['project_name']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($conversation['participants'])): ?>
                                                        <div class="participants-list">
                                                            <?php foreach ($conversation['participants'] as $participant): ?>
                                                                <span class="label label-default participant-tag">
                                                                    <?php echo htmlspecialchars($participant['firstname'] . ' ' . $participant['lastname']); ?>
                                                                </span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-muted">No participants</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo htmlspecialchars($conversation['firstname'] . ' ' . $conversation['lastname']); ?>
                                                </td>
                                                <td>
                                                    <?php if ($conversation['last_activity']): ?>
                                                        <span title="<?php echo $conversation['last_activity']; ?>">
                                                            <?php echo time_ago($conversation['last_activity']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">No activity</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo admin_url('project_chat/chatbox/' . $conversation['id']); ?>" class="btn btn-info btn-sm">
                                                        <i class="fa fa-comments" title="Chat"></i> 
                                                    </a>
                                                    <a href="<?php echo admin_url('project_chat/edit_conversation/' . $conversation['id']); ?>" class="btn btn-warning btn-sm">
                                                        <i class="fa fa-edit" title="Edit"></i> 
                                                    </a>
                                                    <a href="<?php echo admin_url('project_chat/delete_conversation/' . $conversation['id']); ?>" 
                                                       class="btn btn-danger btn-sm" 
                                                       onclick="return confirm('Are you sure you want to delete this conversation? This action cannot be undone.')">
                                                        <i class="fa fa-trash" title="Delete"></i> 
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <div class="empty-state">
                                                    <i class="fa fa-comments fa-3x text-muted"></i>
                                                    <h4>No conversations yet</h4>
                                                    <p class="text-muted">Start your first project conversation to collaborate with your team.</p>
                                                    <a href="<?php echo admin_url('project_chat/new_conversation'); ?>" class="btn btn-info">
                                                        <i class="fa fa-plus"></i> Start New Conversation
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#conversationsTable').DataTable({
        "order": [[ 5, "desc" ]], // Sort by last activity
        "pageLength": 25,
        "columnDefs": [
            { "orderable": false, "targets": [6] } // Disable sorting on actions column
        ]
    });
});
</script>

<style>
.participants-list {
    max-width: 200px;
}
.participant-tag {
    display: inline-block;
    margin: 2px;
    font-size: 11px;
}
.empty-state {
    padding: 40px 20px;
}
.empty-state i {
    margin-bottom: 20px;
}
.btn-group .btn {
    margin-right: 2px;
}
</style>
