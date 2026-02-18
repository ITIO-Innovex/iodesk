<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
@media (min-width: 768px) {
    .modal-dialogx {
        width: unset !important;
    }
}
.existing-attachment {
    display: inline-flex;
    align-items: center;
    background: #f5f5f5;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px 10px;
    margin: 5px;
}
.existing-attachment .file-name {
    margin-right: 10px;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.existing-attachment .remove-attachment {
    cursor: pointer;
    color: #d9534f;
    font-weight: bold;
}
.existing-attachment .remove-attachment:hover {
    color: #c9302c;
}
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
        <?php if(!empty($_SESSION['mailersdropdowns'])){ ?>
            <div class="col-md-2 picker">
                <div>
                    <span class="dropdown">
                        <button class="btn btn-default buttons-collection btn-default-dt-options dropdown-toggle" type="button" data-toggle="dropdown" style="width: 180px !important;">
                            <span title="<?= $_SESSION['webmail']['mailer_email'] ?? ''; ?>"><?= substr($_SESSION['webmail']['mailer_email'] ?? '', 0, 18); ?></span>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($_SESSION['mailersdropdowns'] as $item) { ?>
                                <li><a href="?mt=<?= $item['id']; ?>"><?= $item['mailer_email']; ?></a></li>
                            <?php } ?>
                        </ul>
                    </span>
                </div>
                <div>
                    <a href="<?php echo site_url('admin/webmail/compose'); ?>" class="btn btn-primary mtop10" style="width: 180px !important;">
                        <i class="fa-regular fa-paper-plane tw-mr-1"></i>
                        <?php echo _l('New Mail'); ?>
                    </a>
                </div>
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked mtop10" id="theme_styling_areas">
                    <?php foreach ($_SESSION['folderlist'] as $item => $val) { ?>
                        <li role="presentation" class="menu-item-leads">
                            <a href="inbox?fd=<?php echo $val['folder']; ?>"><?php echo $val['folder']; ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-10">
                <div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">
                        <i class="fa-regular fa-clock"></i> Update Scheduled Email
                    </h4>
                    <span class="label label-warning">Scheduled: <?php echo date('M d, Y h:i A', strtotime($schedule['scheduled_at'])); ?></span>
                </div>
                <div class="panel_s">
                    <div class="panel-body panel-table-full mail-bg">
                        <form id="update-schedule-form" enctype="multipart/form-data">
                            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                            <input type="hidden" name="schedule_id" value="<?php echo (int) $schedule['id']; ?>">
                            <input type="hidden" name="deleted_attachments" id="deleted_attachments" value="">

                            <div class="mb-3">
                                <label for="recipientEmail" class="form-label mtop10">To <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="recipientEmailIT" name="recipientEmail" value="<?php echo htmlspecialchars($schedule['to_email'] ?? ''); ?>" placeholder="Enter recipient email" required>
                            </div>

                            <div class="mb-3">
                                <label for="recipientCC" class="form-label mtop10">CC</label>
                                <input type="text" class="form-control" id="recipientCCIT" name="recipientCC" value="<?php echo htmlspecialchars($schedule['cc_emails'] ?? ''); ?>" placeholder="Enter CC email - Add multiple with comma separated">
                            </div>

                            <div class="mb-3">
                                <label for="recipientBCC" class="form-label mtop10">BCC</label>
                                <input type="text" class="form-control" id="recipientBCCIT" name="recipientBCC" value="<?php echo htmlspecialchars($schedule['bcc_emails'] ?? ''); ?>" placeholder="Enter BCC email">
                            </div>

                            <div class="mb-3">
                                <label for="emailSubject" class="form-label mtop10">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="emailSubjectIT" name="emailSubject" value="<?php echo htmlspecialchars($schedule['subject'] ?? ''); ?>" placeholder="Enter email subject" required>
                            </div>

                            <div class="mb-3">
                                <label for="emailBody" class="form-label mtop10">Email Body</label>
                                <textarea name="emailBody" id="emailBody" class="form-control editor"><?php echo htmlspecialchars($schedule['body'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label mtop10">Scheduled Date & Time <span class="text-danger">*</span></label>
                                <?php
                                    $scheduledAt = '';
                                    if (!empty($schedule['scheduled_at'])) {
                                        $scheduledAt = date('Y-m-d\TH:i', strtotime($schedule['scheduled_at']));
                                    }
                                ?>
                                <input type="datetime-local" name="schedule_at" id="scheduleAtInput" class="form-control" value="<?php echo $scheduledAt; ?>" required>
                            </div>

                            <?php
                            $existingAttachments = [];
                            if (!empty($schedule['attachments'])) {
                                $existingAttachments = json_decode($schedule['attachments'], true) ?: [];
                            }
                            ?>
                            <?php if (!empty($existingAttachments)): ?>
                            <div class="mb-3">
                                <label class="form-label mtop10">Existing Attachments</label>
                                <div id="existingAttachmentsList">
                                    <?php foreach ($existingAttachments as $attachment): ?>
                                        <div class="existing-attachment" data-file="<?php echo htmlspecialchars($attachment); ?>">
                                            <span class="file-name" title="<?php echo htmlspecialchars($attachment); ?>">
                                                <i class="fa fa-paperclip"></i>
                                                <?php echo htmlspecialchars(pathinfo($attachment, PATHINFO_BASENAME)); ?>
                                            </span>
                                            <a href="<?php echo base_url('uploads/email_queue/' . $attachment); ?>" target="_blank" class="btn btn-xs btn-info" title="View">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <span class="remove-attachment btn btn-xs btn-danger" title="Remove">
                                                <i class="fa fa-times"></i>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="attachments" class="form-label text-info mtop10">Add New Attachments: You can select multiple files by holding the Shift key and clicking on the files while browsing.</label>
                                <div class="tw-flex tw-items-center tw-gap-2">
                                    <input type="file" id="emailAttachments" name="attachments[]" class="form-control" multiple>
                                    <button type="button" id="addMoreAttachments" class="btn btn-default">Add another</button>
                                </div>
                                <small id="attachmentStatus" class="text-success hide">File is attached.</small>
                                <div id="attachmentList" class="mtop10"></div>
                            </div>

                            <div class="mtop20">
                                <button type="button" id="updateScheduleBtn" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Scheduled Email
                                </button>
                                <button type="button" id="deleteScheduleBtn" class="btn btn-danger">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                                <a href="<?php echo admin_url('webmail/inbox?fd=Outbox'); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back to Outbox
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <div class="alert alert-info text-center">
                <?php echo _l('No Webmail Setup Entries'); ?>
            </div>
        <?php } ?>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>
$(document).ready(function() {
    $('.editor').jqte();

    var deletedAttachments = [];

    $('.remove-attachment').on('click', function() {
        var $parent = $(this).closest('.existing-attachment');
        var fileName = $parent.data('file');
        if (fileName) {
            deletedAttachments.push(fileName);
            $('#deleted_attachments').val(JSON.stringify(deletedAttachments));
            $parent.fadeOut(300, function() { $(this).remove(); });
        }
    });

    window.emailFilesStore = window.emailFilesStore || [];

    function renderAttachmentList() {
        var list = $('#attachmentList');
        list.empty();
        if (window.emailFilesStore.length === 0) {
            $('#attachmentStatus').addClass('hide').text('');
            return;
        }
        $('#attachmentStatus').removeClass('hide').text('File is attached.');
        window.emailFilesStore.forEach(function(file, idx) {
            var row = $('<div class="tw-flex tw-items-center tw-justify-between tw-border tw-border-solid tw-border-neutral-200 tw-rounded tw-px-3 tw-py-2 tw-mb-2"></div>');
            var name = $('<span class="tw-text-sm tw-text-neutral-700"></span>').text(file.name);
            var removeBtn = $('<button type="button" class="btn btn-danger btn-xs">Delete</button>');
            removeBtn.attr('data-index', idx);
            row.append(name).append(removeBtn);
            list.append(row);
        });
    }

    function rebuildAttachmentInput() {
        var input = document.getElementById('emailAttachments');
        var dt = new DataTransfer();
        window.emailFilesStore.forEach(function(file) {
            dt.items.add(file);
        });
        input.files = dt.files;
    }

    function addFilesToStore(files) {
        for (var i = 0; i < files.length; i++) {
            var f = files[i];
            var exists = window.emailFilesStore.some(function(existing) {
                return existing.name === f.name && existing.size === f.size && existing.lastModified === f.lastModified;
            });
            if (!exists) {
                window.emailFilesStore.push(f);
            }
        }
        rebuildAttachmentInput();
        renderAttachmentList();
    }

    $('#addMoreAttachments').on('click', function() {
        $('#emailAttachments').click();
    });

    $('#attachmentList').on('click', 'button[data-index]', function() {
        var idx = parseInt($(this).attr('data-index'), 10);
        if (!isNaN(idx)) {
            window.emailFilesStore.splice(idx, 1);
            rebuildAttachmentInput();
            renderAttachmentList();
        }
    });

    $('#emailAttachments').on('change', function() {
        var files = $(this).get(0).files;
        if (files && files.length > 0) {
            addFilesToStore(files);
        } else {
            renderAttachmentList();
        }
    });

    function validateForm() {
        var recipientEmail = $.trim($('#recipientEmailIT').val());
        var emailSubject = $.trim($('#emailSubjectIT').val());
        var scheduleAt = $.trim($('#scheduleAtInput').val());

        if (recipientEmail === '') {
            alert('Please enter recipient email');
            $('#recipientEmailIT').focus();
            return false;
        }
        if (emailSubject === '') {
            alert('Please enter email subject');
            $('#emailSubjectIT').focus();
            return false;
        }
        if (scheduleAt === '') {
            alert('Please select scheduled date and time');
            $('#scheduleAtInput').focus();
            return false;
        }
        return true;
    }

    function getEditorContent() {
        var $editor = $('.jqte_editor');
        if ($editor.length) {
            return $editor.html();
        }
        return $('#emailBody').val();
    }

    $('#updateScheduleBtn').on('click', function() {
        if (!validateForm()) {
            return;
        }

        var $btn = $(this);
        var form = document.getElementById('update-schedule-form');
        var formData = new FormData(form);
        formData.set('emailBody', getEditorContent());

        $btn.prop('disabled', true).html("<i class='fa-solid fa-spinner fa-spin-pulse'></i> Updating...");

        $.ajax({
            url: admin_url + 'webmail/process_update_schedule',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var res;
                try {
                    res = JSON.parse(response);
                } catch (e) {
                    res = { success: false, message: 'Unexpected response' };
                }
                if (res.success) {
                    alert(res.message || 'Scheduled email updated successfully');
                    window.location.href = admin_url + 'webmail/inbox?fd=Outbox';
                } else {
                    alert(res.message || 'Failed to update scheduled email');
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Scheduled Email');
                }
            },
            error: function() {
                alert('Failed to update scheduled email');
                $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Scheduled Email');
            }
        });
    });

    $('#deleteScheduleBtn').on('click', function() {
        if (!confirm('Are you sure you want to delete this scheduled email? This action cannot be undone.')) {
            return;
        }

        var $btn = $(this);
        var scheduleId = $('input[name="schedule_id"]').val();

        $btn.prop('disabled', true).html("<i class='fa-solid fa-spinner fa-spin-pulse'></i> Deleting...");

        $.post(admin_url + 'webmail/delete_schedule/' + scheduleId, {
            <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
        }, function(response) {
            var res;
            try {
                res = JSON.parse(response);
            } catch (e) {
                res = { success: false, message: 'Unexpected response' };
            }
            if (res.success) {
                alert(res.message || 'Scheduled email deleted successfully');
                window.location.href = admin_url + 'webmail/inbox?fd=Outbox';
            } else {
                alert(res.message || 'Failed to delete scheduled email');
                $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Delete');
            }
        }).fail(function() {
            alert('Failed to delete scheduled email');
            $btn.prop('disabled', false).html('<i class="fa fa-trash"></i> Delete');
        });
    });
});
</script>

</body>
</html>
