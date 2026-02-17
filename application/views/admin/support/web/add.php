<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.card {
    padding: 20px;
    background: #ffffff;
    border-radius: 5px;
    box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
}
.card-title {
    text-align: center;
}
.jqte_tool.jqte_tool_1 .jqte_tool_label {
    height: 20px !important;
}
.jqte {
    margin: 20px 0 !important;
}
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
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="col-md-12">
                        <h2 class="no-margin">
                            <i class="fa-solid fa-ticket tw-mr-2"></i> <?php echo $title; ?>
                        </h2>
                        <hr class="hr-panel-heading" />
                    </div>
                    <div class="card-body">
                        <?php echo form_open(admin_url('support/web/submit'), ['id' => 'supportTicketForm', 'enctype' => 'multipart/form-data']); ?>
                            <div class="row" style="padding:20px">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="text-dark">Subject <span class="text-danger">*</span></label>
                                        <input type="text" id="subject" name="subject" class="form-control" placeholder="Brief summary of your issue..." required maxlength="255">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="text-dark">Priority <span class="text-danger">*</span></label>
                                        <select name="priority" id="priority" class="form-control" required>
                                            <?php foreach ($priorities as $p) { ?>
                                                <option value="<?php echo e($p); ?>" <?php echo $p === 'Medium' ? 'selected' : ''; ?>><?php echo e($p); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-dark">Description <span class="text-danger">*</span></label>
                                        <textarea name="description" id="description" class="form-control editor" placeholder="Describe your issue in detail..."></textarea>
                                        <small class="text-muted">Minimum 10 characters required</small>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-dark">Attachments</label>
                                        <input type="file" id="attachments" name="attachments[]" class="form-control" multiple>
                                        <small class="text-muted">You can attach screenshots or relevant files</small>
                                        <div id="attachments-list" class="mt-2"></div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" id="submitTicket" class="btn btn-primary btn-lg">
                                            <i class="fa-solid fa-paper-plane tw-mr-1"></i> Submit Ticket
                                        </button>
                                        <a href="<?php echo admin_url('support/web'); ?>" class="btn btn-default btn-lg">
                                            <i class="fa-solid fa-list tw-mr-1"></i> My Tickets
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php echo form_close(); ?>
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

    // File attachments management
    var $input = $('#attachments');
    var $list = $('#attachments-list');
    var filesStore = [];
    window.supportTicketFilesStore = filesStore;

    function renderList() {
        $list.empty();
        if (filesStore.length === 0) {
            return;
        }
        var $ul = $('<ul class="list-unstyled mb-0"></ul>');
        filesStore.forEach(function(file, index) {
            var $li = $('<li class="tw-my-2"></li>');
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

    // Form submission
    $('#supportTicketForm').on('submit', function(event) {
        event.preventDefault();

        var subject = $.trim($('#subject').val());
        var description = $.trim($('#description').val());

        if (subject === '') {
            alert('Please enter the subject');
            $('#subject').focus();
            return false;
        }

        if (description === '' || description.length < 10) {
            alert('Please enter a description (minimum 10 characters)');
            $('.jqte_editor').focus();
            return false;
        }

        $("#submitTicket").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i> Submitting...").prop("disabled", true);

        var formData = new FormData(this);
        
        // Handle attachments
        formData.delete('attachments[]');
        filesStore.forEach(function(file) {
            formData.append('attachments[]', file);
        });

        $.ajax({
            url: $(this).attr('action'),
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

                $("#submitTicket").html('<i class="fa-solid fa-paper-plane tw-mr-1"></i> Submit Ticket').prop("disabled", false);

                if (data.success) {
                    alert_float('success', data.message || "Ticket submitted successfully!");
                    // Reset form
                    $('#supportTicketForm')[0].reset();
                    $('.editor').jqteVal('');
                    filesStore.length = 0;
                    renderList();
                    
                    // Optionally redirect to ticket list
                    setTimeout(function() {
                        window.location.href = '<?php echo admin_url('support/web'); ?>';
                    }, 2000);
                } else {
                    alert_float('danger', data.message || "Failed to submit ticket. Please try again.");
                }
            },
            error: function(xhr, status, error) {
                $("#submitTicket").html('<i class="fa-solid fa-paper-plane tw-mr-1"></i> Submit Ticket').prop("disabled", false);
                console.error('Error:', error);
                var msg = (xhr && xhr.responseText) ? xhr.responseText : "Failed to submit ticket. Please try again.";
                alert_float('danger', msg);
            }
        });
    });
});
</script>
</body></html>
