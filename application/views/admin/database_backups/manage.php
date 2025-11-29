<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-font-semibold tw-mt-0 tw-text-neutral-800">
                        <?php echo $title; ?> <?php //echo $backup_dir = FCPATH . 'backups' . DIRECTORY_SEPARATOR; ?>
                    </h4>
                </div>

                <?php if ($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>

                <?php if ($this->session->flashdata('danger')) { ?>
                <div class="alert alert-danger">
                    <?php echo $this->session->flashdata('danger'); ?>
                </div>
                <?php } ?>

                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-mb-4">
                            <?php 
							if(is_super()){
							echo form_open(admin_url('database_backups/export_backup'), ['id' => 'backup-form']); 
							}else if(is_admin()){
							echo form_open(admin_url('database_backups/export_db_data'), ['id' => 'backup-form']); 
							}
							
							?>
                            <button type="submit" class="btn btn-primary" id="export-backup-btn">
                                <i class="fa fa-download tw-mr-1"></i>
                                Export Backup
                            </button>
                            <?php echo form_close(); ?>
                        </div>

                        <div class="alert alert-info">
                            <i class="fa fa-info-circle tw-mr-1"></i>
                            <strong>Note:</strong> Backup files are automatically compressed into ZIP format and saved securely on the server. 
                            The backup process may take a few moments depending on your database size.
                        </div>
                    </div>
                </div>
 <?php  if(is_super()){ ?>
                <div class="panel_s">
                    <div class="panel-body">
                        <h5 class="tw-font-semibold tw-mb-3">Previous Backups</h5>
                        
                        <?php if (empty($backups)) { ?>
                        <div class="text-center tw-py-8">
                            <i class="fa fa-database fa-3x text-muted tw-mb-3"></i>
                            <p class="text-muted">No backup files found. Create your first backup using the button above.</p>
                        </div>
                        <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>File Name</th>
                                        <th>Created Date</th>
                                        <th>File Size</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($backups as $backup) { ?>
                                    <tr>
                                        <td>
                                            <i class="fa fa-file-archive-o tw-mr-2"></i>
                                            <?php echo e($backup['filename']); ?>
                                        </td>
                                        <td><?php echo e($backup['created_date']); ?></td>
                                        <td><?php echo e($backup['size']); ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('database_backups/download/' . urlencode($backup['filename'])); ?>" 
                                               class="btn btn-sm btn-success" title="Download">
                                                <i class="fa fa-download"></i> Download
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger delete-backup" 
                                                    data-filename="<?php echo e($backup['filename']); ?>"
                                                    title="Delete">
                                                <i class="fa fa-trash"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                </div>
 <?php  } ?>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBackupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Confirm Delete</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this backup file?</p>
                <p><strong>File:</strong> <span id="delete-filename"></span></p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>



<?php init_tail(); ?>
<script>
$(document).ready(function() {
    var deleteFilename = '';
    
    // Handle export backup button
    $('#backup-form').on('submit', function() {
        $('#export-backup-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin tw-mr-1"></i> Creating Backup...');
    });
    
    // Handle delete backup button
    $('.delete-backup').on('click', function() {
        deleteFilename = $(this).data('filename');
        $('#delete-filename').text(deleteFilename);
        $('#deleteBackupModal').modal('show');
    });
    
    // Handle confirm delete
    $('#confirm-delete').on('click', function() {
        if (!deleteFilename) return;
        
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin tw-mr-1"></i> Deleting...');
        
        $.ajax({
            url: '<?php echo admin_url('database_backups/delete'); ?>',
            type: 'POST',
            data: {
                filename: deleteFilename
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#deleteBackupModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred while deleting the backup.');
            },
            complete: function() {
                $btn.prop('disabled', false).html('Delete');
            }
        });
    });
    
    // Reset modal when closed
    $('#deleteBackupModal').on('hidden.bs.modal', function() {
        deleteFilename = '';
        $('#delete-filename').text('');
        $('#confirm-delete').prop('disabled', false).html('Delete');
    });
});
</script>