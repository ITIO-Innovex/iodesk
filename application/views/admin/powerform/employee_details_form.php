<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-my-0 tw-font-semibold"><?php echo $title; ?></h4>
                        </div>
                        <hr class="hr-panel-heading" />
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="employeeDetailsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Contact Number</th>
                                        <th>Email</th>
                                        <th>PAN Number</th>
                                        <th>Aadhaar Number</th>
                                        <th>Date of Birth</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo htmlspecialchars($record['name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['contact_number'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['pan_number'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['aadhaar_number'] ?? ''); ?></td>
                                                <td><?php echo $record['date_of_birth'] ? date('d-m-Y', strtotime($record['date_of_birth'])) : '-'; ?></td>
                                                <td>
                                                    <?php 
                                                    $s = $record['status'] ?? 'Draft';
                                                    $statusClass = 'label-warning';
                                                    if ($s === 'Submitted') $statusClass = 'label-info';
                                                    if ($s === 'Approved') $statusClass = 'label-success';
                                                    if ($s === 'Rejected') $statusClass = 'label-danger';
                                                    ?>
                                                    <span class="label <?php echo $statusClass; ?>"><?php echo htmlspecialchars($s); ?></span>
                                                </td>
                                                <td><?php echo $record['created_at'] ? date('d-m-Y H:i', strtotime($record['created_at'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs view-details" data-id="<?php echo $record['id']; ?>" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-primary btn-xs edit-details" data-id="<?php echo $record['id']; ?>" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <a href="<?php echo admin_url('powerform/download_employee_details_pdf/' . $record['id']); ?>" class="btn btn-danger btn-xs" title="Download PDF">
                                                        <i class="fa-solid fa-file-pdf"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-xs delete-details" data-id="<?php echo $record['id']; ?>" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Employee Details</h4>
            </div>
            <div class="modal-body" id="viewDetailsContent">
                <div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Details Modal -->
<div class="modal fade" id="editDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editDetailsForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Employee Details</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#basicInfo" aria-controls="basicInfo" role="tab" data-toggle="tab">Basic Info</a></li>
                        <li role="presentation"><a href="#addressInfo" aria-controls="addressInfo" role="tab" data-toggle="tab">Address</a></li>
                        <li role="presentation"><a href="#referenceInfo" aria-controls="referenceInfo" role="tab" data-toggle="tab">References</a></li>
                    </ul>
                    <div class="tab-content mtop15">
                        <!-- Basic Info Tab -->
                        <div role="tabpanel" class="tab-pane active" id="basicInfo">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="edit_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="edit_email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" id="edit_contact_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Emergency Contact Number</label>
                                        <input type="text" class="form-control" name="emergency_contact_number" id="edit_emergency_contact_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input type="text" class="form-control" name="pan_number" id="edit_pan_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Aadhaar Number</label>
                                        <input type="text" class="form-control" name="aadhaar_number" id="edit_aadhaar_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" id="edit_date_of_birth">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Joining</label>
                                        <input type="date" class="form-control" name="date_of_joining" id="edit_date_of_joining">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <input type="text" class="form-control" name="assigned_designation" id="edit_assigned_designation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <input type="text" class="form-control" name="department" id="edit_department">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="edit_status">
                                            <option value="Draft">Draft</option>
                                            <option value="Submitted">Submitted</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address Tab -->
                        <div role="tabpanel" class="tab-pane" id="addressInfo">
                            <div class="form-group">
                                <label>Current Address</label>
                                <textarea class="form-control" name="current_address" id="edit_current_address" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Permanent Address</label>
                                <textarea class="form-control" name="permanent_address" id="edit_permanent_address" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <!-- References Tab -->
                        <div role="tabpanel" class="tab-pane" id="referenceInfo">
                            <?php for ($i = 1; $i <= 8; $i++) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Reference <?php echo $i; ?></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="ref<?php echo $i; ?>_name" id="edit_ref<?php echo $i; ?>_name">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Relation</label>
                                                <input type="text" class="form-control" name="ref<?php echo $i; ?>_relation" id="edit_ref<?php echo $i; ?>_relation">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact</label>
                                                <input type="text" class="form-control" name="ref<?php echo $i; ?>_contact" id="edit_ref<?php echo $i; ?>_contact">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveEditBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>

<script>
var powerformBaseUrl = '<?php echo base_url(); ?>';
$(function() {
    // View Details
    $('.view-details').on('click', function() {
        var id = $(this).data('id');
        $('#viewDetailsContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        $('#viewDetailsModal').modal('show');
        
        $.ajax({
            url: admin_url + 'powerform/get_employee_details/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    var html = buildViewHtml(data);
                    $('#viewDetailsContent').html(html);
                } else {
                    $('#viewDetailsContent').html('<div class="alert alert-danger">' + response.message + '</div>');
                }
            },
            error: function() {
                $('#viewDetailsContent').html('<div class="alert alert-danger">Failed to load details</div>');
            }
        });
    });
    
    function docRow(label, filePath) {
        if (!filePath || filePath === '') {
            return '<tr><th width="25%">' + label + '</th><td>-</td></tr>';
        }
        var fileName = filePath.split(/[\\/]/).pop();
        var downloadUrl = powerformBaseUrl + (filePath.indexOf('http') === 0 ? '' : filePath);
        return '<tr><th width="25%">' + label + '</th><td><span class="tw-mr-2">' + escapeHtml(fileName) + '</span> <a href="' + escapeHtml(downloadUrl) + '" target="_blank" download class="btn btn-default btn-xs"><i class="fa fa-download"></i> Download</a></td></tr>';
    }
    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    function buildViewHtml(data) {
        var html = '<div class="row">';
        html += '<div class="col-md-12">';
        html += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3">Basic Information</h5>';
        html += '<table class="table table-bordered">';
        html += '<tr><th width="25%">Name</th><td>' + (data.name || '-') + '</td><th width="25%">Email</th><td>' + (data.email || '-') + '</td></tr>';
        html += '<tr><th>Contact Number</th><td>' + (data.contact_number || '-') + '</td><th>Emergency Contact</th><td>' + (data.emergency_contact_number || '-') + '</td></tr>';
        html += '<tr><th>PAN Number</th><td>' + (data.pan_number || '-') + '</td><th>Aadhaar Number</th><td>' + (data.aadhaar_number || '-') + '</td></tr>';
        html += '<tr><th>Date of Birth</th><td>' + (data.date_of_birth || '-') + '</td><th>Date of Joining</th><td>' + (data.date_of_joining || '-') + '</td></tr>';
        html += '<tr><th>Designation</th><td>' + (data.assigned_designation || '-') + '</td><th>Department</th><td>' + (data.department || '-') + '</td></tr>';
        var statusClass = 'label-warning';
                    if (data.status === 'Submitted') statusClass = 'label-info';
                    if (data.status === 'Approved') statusClass = 'label-success';
                    if (data.status === 'Rejected') statusClass = 'label-danger';
                    html += '<tr><th>Status</th><td colspan="3"><span class="label ' + statusClass + '">' + (data.status || 'Draft') + '</span></td></tr>';
        html += '</table>';
        
        html += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Address Information</h5>';
        html += '<table class="table table-bordered">';
        html += '<tr><th width="25%">Current Address</th><td>' + (data.current_address || '-') + '</td></tr>';
        html += '<tr><th>Permanent Address</th><td>' + (data.permanent_address || '-') + '</td></tr>';
        html += '</table>';
        
        html += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">References</h5>';
        html += '<table class="table table-bordered">';
        html += '<thead><tr><th>#</th><th>Name</th><th>Relation</th><th>Contact</th></tr></thead>';
        html += '<tbody>';
        for (var i = 1; i <= 8; i++) {
            var refName = data['ref' + i + '_name'] || '';
            var refRelation = data['ref' + i + '_relation'] || '';
            var refContact = data['ref' + i + '_contact'] || '';
            if (refName || refRelation || refContact) {
                html += '<tr><td>' + i + '</td><td>' + refName + '</td><td>' + refRelation + '</td><td>' + refContact + '</td></tr>';
            }
        }
        html += '</tbody></table>';
        
        html += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Documents</h5>';
        html += '<table class="table table-bordered">';
        html += docRow('Profile Picture', data.profile_pic);
        html += docRow('Educational Testimonials', data.educational_testimonials);
        html += docRow('ID Proof', data.id_proof);
        html += docRow('Address Proof', data.address_proof);
        html += docRow('Previous Company Documents', data.previous_company_documents);
        html += '</table>';
        
        html += '</div></div>';
        return html;
    }
    
    // Edit Details
    $('.edit-details').on('click', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: admin_url + 'powerform/get_employee_details/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name || '');
                    $('#edit_email').val(data.email || '');
                    $('#edit_contact_number').val(data.contact_number || '');
                    $('#edit_emergency_contact_number').val(data.emergency_contact_number || '');
                    $('#edit_pan_number').val(data.pan_number || '');
                    $('#edit_aadhaar_number').val(data.aadhaar_number || '');
                    $('#edit_date_of_birth').val(data.date_of_birth || '');
                    $('#edit_date_of_joining').val(data.date_of_joining || '');
                    $('#edit_assigned_designation').val(data.assigned_designation || '');
                    $('#edit_department').val(data.department || '');
                    $('#edit_status').val(data.status || 'Draft');
                    $('#edit_current_address').val(data.current_address || '');
                    $('#edit_permanent_address').val(data.permanent_address || '');
                    
                    for (var i = 1; i <= 8; i++) {
                        $('#edit_ref' + i + '_name').val(data['ref' + i + '_name'] || '');
                        $('#edit_ref' + i + '_relation').val(data['ref' + i + '_relation'] || '');
                        $('#edit_ref' + i + '_contact').val(data['ref' + i + '_contact'] || '');
                    }
                    
                    $('#editDetailsModal').modal('show');
                } else {
                    alert_float('danger', response.message);
                }
            },
            error: function() {
                alert_float('danger', 'Failed to load details');
            }
        });
    });
    
    // Save Edit
    $('#editDetailsForm').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#saveEditBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: admin_url + 'powerform/update_employee_details',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $btn.prop('disabled', false).html('Save Changes');
                if (response.success) {
                    alert_float('success', response.message);
                    $('#editDetailsModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert_float('danger', response.message);
                }
            },
            error: function() {
                $btn.prop('disabled', false).html('Save Changes');
                alert_float('danger', 'Failed to save changes');
            }
        });
    });
    
    // Delete Details
    $('.delete-details').on('click', function() {
        var id = $(this).data('id');
        
        if (!confirm('Are you sure you want to delete this record?')) {
            return;
        }
        
        $.ajax({
            url: admin_url + 'powerform/delete_employee_details/' + id,
            type: 'POST',
            data: {
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert_float('success', response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    alert_float('danger', response.message);
                }
            },
            error: function() {
                alert_float('danger', 'Failed to delete record');
            }
        });
    });
});
</script>
</body>
</html>
