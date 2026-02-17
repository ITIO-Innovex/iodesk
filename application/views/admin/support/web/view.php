<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.priority-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.priority-Low { background: #28a745; color: #fff; }
.priority-Medium { background: #ffc107; color: #000; }
.priority-High { background: #fd7e14; color: #fff; }
.priority-Urgent { background: #dc3545; color: #fff; }

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.status-Open { background: #17a2b8; color: #fff; }
.status-In-Progress { background: #007bff; color: #fff; }
.status-On-Hold { background: #6c757d; color: #fff; }
.status-Resolved { background: #28a745; color: #fff; }
.status-Closed { background: #343a40; color: #fff; }

.ticket-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}
.ticket-info dt {
    font-weight: 600;
    color: #333;
}
.ticket-info dd {
    margin-bottom: 10px;
}
.ticket-description {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 20px;
    min-height: 100px;
}
.reply-item {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: #fff;
}
.reply-item.staff-reply {
    background: #f0f7ff;
    border-color: #b3d4fc;
}
.reply-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
}
.reply-author {
    font-weight: 600;
    color: #333;
}
.reply-date {
    font-size: 12px;
    color: #888;
}
.reply-content {
    color: #444;
    line-height: 1.6;
}
.reply-attachments {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #eee;
}
.jqte {
    margin: 10px 0 !important;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <i class="fa-solid fa-ticket tw-mr-2"></i> <?php echo $title; ?>: <?php echo e($ticket['subject']); ?>
                    </h4>
                    <div>
                        <a href="<?php echo admin_url('support/web/add'); ?>" class="btn btn-primary">
                            <i class="fa-regular fa-plus"></i> New Ticket
                        </a>
                        <a href="<?php echo admin_url('support/web'); ?>" class="btn btn-default">
                            <i class="fa-solid fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Original Ticket Description -->
                                <h5 class="tw-font-semibold tw-mb-3">Description</h5>
                                <div class="ticket-description">
                                    <?php echo $ticket['description']; ?>
                                </div>

                                <?php if (!empty($attachments)) { ?>
                                <div class="tw-mt-3">
                                    <strong>Attachments:</strong>
                                    <?php foreach ($attachments as $att) { ?>
                                        <a href="<?php echo base_url($att['file_path']); ?>" target="_blank" class="btn btn-xs btn-default tw-ml-1">
                                            <i class="fa-solid fa-paperclip"></i> <?php echo basename($att['file_path']); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                                <?php } ?>

                                <!-- Replies Section -->
                                <div class="tw-mt-5">
                                    <h5 class="tw-font-semibold tw-mb-3">
                                        <i class="fa-solid fa-comments tw-mr-1"></i> Replies 
                                        <span class="badge"><?php echo count($replies); ?></span>
                                    </h5>

                                    <?php if (!empty($replies)) { ?>
                                        <?php foreach ($replies as $reply) { 
                                            $isCurrentUser = ((int)$reply['staffid'] === (int)get_staff_user_id());
                                            $replyClass = $isCurrentUser ? 'staff-reply' : '';
                                            $authorName = trim(($reply['firstname'] ?? '') . ' ' . ($reply['lastname'] ?? ''));
                                            if (!$authorName) $authorName = 'Staff #' . $reply['staffid'];
                                        ?>
                                        <div class="reply-item <?php echo $replyClass; ?>">
                                            <div class="reply-header">
                                                <span class="reply-author">
                                                    <i class="fa-solid fa-user tw-mr-1"></i> <?php echo e($authorName); ?>
                                                    <?php if ($isCurrentUser) { ?><span class="label label-info">You</span><?php } ?>
                                                </span>
                                                <span class="reply-date">
                                                    <i class="fa-regular fa-clock tw-mr-1"></i>
                                                    <?php echo date('d M Y H:i', strtotime($reply['created_at'])); ?>
                                                </span>
                                            </div>
                                            <div class="reply-content">
                                                <?php echo $reply['message']; ?>
                                            </div>
                                            <?php if (!empty($reply['attachments'])) { 
                                                $replyAttachments = explode(',', $reply['attachments']);
                                            ?>
                                            <div class="reply-attachments">
                                                <strong><i class="fa-solid fa-paperclip"></i> Attachments:</strong>
                                                <?php foreach ($replyAttachments as $rAtt) { 
                                                    $rAtt = trim($rAtt);
                                                    if ($rAtt) {
                                                ?>
                                                    <a href="<?php echo base_url($rAtt); ?>" target="_blank" class="btn btn-xs btn-default tw-ml-1">
                                                        <?php echo basename($rAtt); ?>
                                                    </a>
                                                <?php } } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <div class="alert alert-info">No replies yet.</div>
                                    <?php } ?>
                                </div>

                                <!-- Reply Form -->
                                <?php if ($ticket['status'] !== 'Closed') { ?>
                                <div class="tw-mt-4">
                                    <h5 class="tw-font-semibold tw-mb-3">
                                        <i class="fa-solid fa-reply tw-mr-1"></i> Add Reply
                                    </h5>
                                    <form id="replyForm" enctype="multipart/form-data">
                                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                                        <input type="hidden" name="ticket_id" value="<?php echo (int) $ticket['id']; ?>">
                                        
                                        <div class="form-group">
                                            <textarea name="message" id="reply-message" class="form-control editor" placeholder="Type your reply here..."></textarea>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label>Attachments (optional)</label>
                                            <input type="file" name="attachments[]" id="reply-attachments" class="form-control" multiple>
                                            <div id="reply-attachments-list" class="tw-mt-2"></div>
                                        </div>
                                        
                                        <button type="submit" id="submitReply" class="btn btn-primary">
                                            <i class="fa-solid fa-paper-plane tw-mr-1"></i> Send Reply
                                        </button>
                                    </form>
                                </div>
                                <?php } else { ?>
                                <div class="alert alert-warning tw-mt-4">
                                    <i class="fa-solid fa-lock tw-mr-1"></i> This ticket is closed. You cannot add new replies.
                                </div>
                                <?php } ?>
                            </div>

                            <div class="col-md-4">
                                <div class="ticket-info">
                                    <dl>
                                        <dt>Ticket ID</dt>
                                        <dd>#<?php echo (int) $ticket['id']; ?></dd>

                                        <dt>Status</dt>
                                        <dd>
                                            <?php $statusClass = str_replace(' ', '-', $ticket['status']); ?>
                                            <span class="status-badge status-<?php echo $statusClass; ?>">
                                                <?php echo e($ticket['status']); ?>
                                            </span>
                                        </dd>

                                        <dt>Priority</dt>
                                        <dd>
                                            <span class="priority-badge priority-<?php echo e($ticket['priority']); ?>">
                                                <?php echo e($ticket['priority']); ?>
                                            </span>
                                        </dd>

                                        <dt>Created</dt>
                                        <dd><?php echo date('d M Y H:i', strtotime($ticket['created_at'])); ?></dd>

                                        <?php if ($ticket['updated_at']) { ?>
                                        <dt>Last Updated</dt>
                                        <dd><?php echo date('d M Y H:i', strtotime($ticket['updated_at'])); ?></dd>
                                        <?php } ?>

                                        <?php if (!empty($ticket['closed_at'])) { ?>
                                        <dt>Closed</dt>
                                        <dd><?php echo date('d M Y H:i', strtotime($ticket['closed_at'])); ?></dd>
                                        <?php } ?>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
$(function() {
    $('.editor').jqte();

    // File attachments management for reply
    var $input = $('#reply-attachments');
    var $list = $('#reply-attachments-list');
    var filesStore = [];
    window.replyFilesStore = filesStore;

    function renderList() {
        $list.empty();
        if (filesStore.length === 0) return;
        var $ul = $('<ul class="list-unstyled mb-0"></ul>');
        filesStore.forEach(function(file, index) {
            var $li = $('<li class="tw-my-1"></li>');
            var $name = $('<span></span>').text(file.name + ' (' + Math.round(file.size / 1024) + ' KB)');
            var $btn = $('<button type="button" class="btn btn-xs btn-danger ml-2">Remove</button>');
            $btn.on('click', function() {
                filesStore.splice(index, 1);
                renderList();
            });
            $li.append($name).append($btn);
            $ul.append($li);
        });
        $list.append($ul);
    }

    $input.on('change', function() {
        var newFiles = Array.from($input[0].files);
        newFiles.forEach(function(file) {
            filesStore.push(file);
        });
        renderList();
        $input.val('');
    });

    // Reply form submission
    $('#replyForm').on('submit', function(e) {
        e.preventDefault();

        var message = $.trim($('#reply-message').val());
        if (message === '' || message.length < 5) {
            alert_float('warning', 'Please enter a reply message (minimum 5 characters)');
            return false;
        }

        $('#submitReply').html("<i class='fa-solid fa-spinner fa-spin-pulse'></i> Sending...").prop('disabled', true);

        var formData = new FormData(this);
        formData.delete('attachments[]');
        filesStore.forEach(function(file) {
            formData.append('attachments[]', file);
        });

        $.ajax({
            url: '<?php echo admin_url('support/web/reply'); ?>',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                var data = {};
                try {
                    data = typeof response === 'string' ? JSON.parse(response) : response;
                } catch (e) {
                    data = { success: false, message: response };
                }

                $('#submitReply').html('<i class="fa-solid fa-paper-plane tw-mr-1"></i> Send Reply').prop('disabled', false);

                if (data.success) {
                    alert_float('success', data.message || 'Reply added successfully!');
                    // Reload page to show new reply
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert_float('danger', data.message || 'Failed to add reply.');
                }
            },
            error: function(xhr, status, error) {
                $('#submitReply').html('<i class="fa-solid fa-paper-plane tw-mr-1"></i> Send Reply').prop('disabled', false);
                alert_float('danger', 'Failed to add reply. Please try again.');
            }
        });
    });
});
</script>
</body></html>
