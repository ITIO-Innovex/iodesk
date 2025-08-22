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
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addProviderModal">
                                    <i class="fa fa-plus"></i> Add New Provider
                                </button>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />

                        <!-- Providers Table -->
                        <div class="table-responsive">
                            <table class="table dt-table table-striped" id="providersTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Provider Name</th>
                                        <th>URL</th>
                                        <th>API Key</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($providers)): ?>
                                        <?php foreach ($providers as $provider): ?>
                                            <tr>
                                                <td><?php echo $provider['id']; ?></td>
                                                <td><?php echo htmlspecialchars($provider['provider_name']); ?></td>
                                                <td>
                                                    <small><?php echo htmlspecialchars(substr($provider['provider_url'], 0, 50)); ?>
                                                    <?php echo strlen($provider['provider_url']) > 50 ? '...' : ''; ?></small>
                                                </td>
                                                <td>
                                                    <span class="text-muted">
                                                        <?php echo str_repeat('*', 20) . substr($provider['api_key'], -4); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if ($provider['status'] == 1): ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="label label-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('Y-m-d H:i', strtotime($provider['created_at'])); ?></td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-default btn-xs edit-provider" 
                                                                data-id="<?php echo $provider['id']; ?>">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        <a href="<?php echo admin_url('ai_content_generator/delete_ai_provider/' . $provider['id']); ?>" 
                                                           class="btn btn-danger btn-xs" 
                                                           onclick="return confirm('Are you sure you want to delete this provider?');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No AI providers found.</td>
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

<!-- Add Provider Modal -->
<div class="modal fade" id="addProviderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Add New AI Provider</h4>
            </div>
            <form action="<?php echo admin_url('ai_content_generator/add_ai_provider'); ?>" method="post">
                <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="provider_name">Provider Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="provider_name" name="provider_name" required>
                    </div>
                    <div class="form-group">
                        <label for="provider_url">Provider URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="provider_url" name="provider_url" required>
                        <small class="text-muted">Full API endpoint URL</small>
                    </div>
                    <div class="form-group">
                        <label for="api_key">API Key</label>
                        <textarea class="form-control" id="api_key" name="api_key" rows="3"></textarea>
                        <small class="text-muted">Your API key for this provider</small>
                    </div>
                    <div class="form-group">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add Provider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Provider Modal -->
<div class="modal fade" id="editProviderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Edit AI Provider</h4>
            </div>
            <form id="editProviderForm" method="post">
                <?php echo form_hidden($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_provider_name">Provider Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_provider_name" name="provider_name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_provider_url">Provider URL <span class="text-danger">*</span></label>
                        <input type="url" class="form-control" id="edit_provider_url" name="provider_url" required>
                        <small class="text-muted">Full API endpoint URL</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_api_key">API Key</label>
                        <textarea class="form-control" id="edit_api_key" name="api_key" rows="3" ></textarea>
                        <small class="text-muted">Your API key for this provider</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status <span class="text-danger">*</span></label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update Provider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    
    // Edit provider functionality - Use event delegation for dynamically loaded content
    $(document).on('click', '.edit-provider', function() { 
        var providerId = $(this).data('id');
        
        // Fetch provider data
        $.get('<?php echo admin_url("ai_content_generator/get_ai_provider/"); ?>' + providerId, function(data) {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            // Populate edit form
            $('#edit_provider_name').val(data.provider_name);
            $('#edit_provider_url').val(data.provider_url);
            $('#edit_api_key').val(data.api_key);
            $('#edit_status').val(data.status);
            
            // Set form action
            $('#editProviderForm').attr('action', '<?php echo admin_url("ai_content_generator/edit_ai_provider/"); ?>' + providerId);
            
            // Add CSRF token to edit form
            var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
            var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
            $('#editProviderForm input[name="' + csrfName + '"]').val(csrfHash);
            
            // Show modal
            $('#editProviderModal').modal('show');
        }, 'json');
    });
	
	// Initialize DataTable
    $('#providersTable').DataTable({
        "order": [[ 0, "desc" ]],
        "pageLength": 25
    });

});
</script>

<style>
.table td {
    vertical-align: middle;
}
.btn-group .btn {
    margin-right: 2px;
}
</style>
