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
                            <table class="table table-striped table-bordered dt-table" id="joiningFormTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Father / Husband Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
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
                                                <td><?php echo htmlspecialchars($record['father_husband_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['contact_number'] ?? ''); ?></td>
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
                                                <td><?php echo !empty($record['created_at']) ? date('d-m-Y H:i', strtotime($record['created_at'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs view-jf-details" data-id="<?php echo $record['id']; ?>" title="View"><i class="fa fa-eye"></i></button>
                                                    <button type="button" class="btn btn-primary btn-xs edit-jf-details" data-id="<?php echo $record['id']; ?>" title="Edit"><i class="fa fa-pencil"></i></button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-jf-details" data-id="<?php echo $record['id']; ?>" title="Delete"><i class="fa fa-trash"></i></button>
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
<div class="modal fade" id="viewJfModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Joining Form Details</h4>
            </div>
            <div class="modal-body" id="viewJfContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Details Modal -->
<div class="modal fade" id="editJfModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editJfForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="jf_edit_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Edit Joining Form</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#jfBasic" data-toggle="tab">Basic Info</a></li>
                        <li><a href="#jfAddress" data-toggle="tab">Address</a></li>
                    </ul>
                    <div class="tab-content mtop15">
                        <div class="tab-pane active" id="jfBasic">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="jf_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Father / Husband Name</label>
                                        <input type="text" class="form-control" name="father_husband_name" id="jf_father_husband_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="jf_email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" id="jf_contact_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Emergency Contact Number</label>
                                        <input type="text" class="form-control" name="emergency_contact_number" id="jf_emergency_contact_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="jf_status">
                                            <option value="Draft">Draft</option>
                                            <option value="Submitted">Submitted</option>
                                            <option value="Approved">Approved</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input type="text" class="form-control" name="pan_number" id="jf_pan_number">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Aadhaar Number</label>
                                        <input type="text" class="form-control" name="aadhaar_number" id="jf_aadhaar_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" id="jf_date_of_birth">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date of Joining</label>
                                        <input type="date" class="form-control" name="date_of_joining" id="jf_date_of_joining">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <input type="text" class="form-control" name="assigned_designation" id="jf_assigned_designation">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" class="form-control" name="department" id="jf_department">
                            </div>
                        </div>
                        <div class="tab-pane" id="jfAddress">
                            <h5 class="tw-font-semibold tw-mb-3">Current Address</h5>
                            <div class="form-group">
                                <label>Line 1</label>
                                <input type="text" class="form-control" name="current_address_line1" id="jf_current_address_line1">
                            </div>
                            <div class="form-group">
                                <label>Line 2</label>
                                <input type="text" class="form-control" name="current_address_line2" id="jf_current_address_line2">
                            </div>
                            <div class="form-group">
                                <label>Line 3</label>
                                <input type="text" class="form-control" name="current_address_line3" id="jf_current_address_line3">
                            </div>
                            <h5 class="tw-font-semibold tw-mb-3 tw-mt-4">Permanent Address</h5>
                            <div class="form-group">
                                <label>Line 1</label>
                                <input type="text" class="form-control" name="permanent_address_line1" id="jf_permanent_address_line1">
                            </div>
                            <div class="form-group">
                                <label>Line 2</label>
                                <input type="text" class="form-control" name="permanent_address_line2" id="jf_permanent_address_line2">
                            </div>
                            <div class="form-group">
                                <label>Line 3</label>
                                <input type="text" class="form-control" name="permanent_address_line3" id="jf_permanent_address_line3">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="jfSaveBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    function esc(v) { return v == null || v === '' ? '-' : String(v); }
    function statusClass(s) {
        if (s === 'Submitted') return 'label-info';
        if (s === 'Approved') return 'label-success';
        if (s === 'Rejected') return 'label-danger';
        return 'label-warning';
    }
    function buildViewHtml(d) {
        var h = '<div class="row"><div class="col-md-12">';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3">Basic Information</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Name</th><td>' + esc(d.name) + '</td><th width="22%">Father / Husband Name</th><td>' + esc(d.father_husband_name) + '</td></tr>';
        h += '<tr><th>Email</th><td>' + esc(d.email) + '</td><th>Contact Number</th><td>' + esc(d.contact_number) + '</td></tr>';
        h += '<tr><th>Emergency Contact</th><td>' + esc(d.emergency_contact_number) + '</td><th>Status</th><td><span class="label ' + statusClass(d.status) + '">' + esc(d.status) + '</span></td></tr>';
        h += '<tr><th>PAN Number</th><td>' + esc(d.pan_number) + '</td><th>Aadhaar Number</th><td>' + esc(d.aadhaar_number) + '</td></tr>';
        h += '<tr><th>Date of Birth</th><td>' + esc(d.date_of_birth) + '</td><th>Date of Joining</th><td>' + esc(d.date_of_joining) + '</td></tr>';
        h += '<tr><th>Designation</th><td>' + esc(d.assigned_designation) + '</td><th>Department</th><td>' + esc(d.department) + '</td></tr>';
        h += '</table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Current Address</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Line 1</th><td>' + esc(d.current_address_line1) + '</td></tr>';
        h += '<tr><th>Line 2</th><td>' + esc(d.current_address_line2) + '</td></tr>';
        h += '<tr><th>Line 3</th><td>' + esc(d.current_address_line3) + '</td></tr>';
        h += '</table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Permanent Address</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Line 1</th><td>' + esc(d.permanent_address_line1) + '</td></tr>';
        h += '<tr><th>Line 2</th><td>' + esc(d.permanent_address_line2) + '</td></tr>';
        h += '<tr><th>Line 3</th><td>' + esc(d.permanent_address_line3) + '</td></tr>';
        h += '</table>';
        h += '</div></div>';
        return h;
    }
    function setEditForm(d) {
        $('#jf_edit_id').val(d.id);
        $('#jf_name').val(d.name || '');
        $('#jf_father_husband_name').val(d.father_husband_name || '');
        $('#jf_email').val(d.email || '');
        $('#jf_contact_number').val(d.contact_number || '');
        $('#jf_emergency_contact_number').val(d.emergency_contact_number || '');
        $('#jf_pan_number').val(d.pan_number || '');
        $('#jf_aadhaar_number').val(d.aadhaar_number || '');
        $('#jf_date_of_birth').val(d.date_of_birth || '');
        $('#jf_date_of_joining').val(d.date_of_joining || '');
        $('#jf_assigned_designation').val(d.assigned_designation || '');
        $('#jf_department').val(d.department || '');
        $('#jf_status').val(d.status || 'Draft');
        $('#jf_current_address_line1').val(d.current_address_line1 || '');
        $('#jf_current_address_line2').val(d.current_address_line2 || '');
        $('#jf_current_address_line3').val(d.current_address_line3 || '');
        $('#jf_permanent_address_line1').val(d.permanent_address_line1 || '');
        $('#jf_permanent_address_line2').val(d.permanent_address_line2 || '');
        $('#jf_permanent_address_line3').val(d.permanent_address_line3 || '');
    }
    $('.view-jf-details').on('click', function() {
        var id = $(this).data('id');
        $('#viewJfContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        $('#viewJfModal').modal('show');
        $.get(admin_url + 'powerform/get_joining_form_details/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) $('#viewJfContent').html(buildViewHtml(r.data));
            else $('#viewJfContent').html('<div class="alert alert-danger">' + (r.message || 'Not found') + '</div>');
        }).fail(function() { $('#viewJfContent').html('<div class="alert alert-danger">Failed to load</div>'); });
    });
    $('.edit-jf-details').on('click', function() {
        var id = $(this).data('id');
        $.get(admin_url + 'powerform/get_joining_form_details/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) { setEditForm(r.data); $('#editJfModal').modal('show'); }
            else alert_float('danger', r.message || 'Not found');
        }).fail(function() { alert_float('danger', 'Failed to load'); });
    });
    $('#editJfForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#jfSaveBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.post(admin_url + 'powerform/update_joining_form_details', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save Changes');
            if (r.success) { alert_float('success', r.message); $('#editJfModal').modal('hide'); setTimeout(function() { location.reload(); }, 1000); }
            else alert_float('danger', r.message);
        }).fail(function() { $btn.prop('disabled', false).html('Save Changes'); alert_float('danger', 'Failed to save'); });
    });
    $('.delete-jf-details').on('click', function() {
        if (!confirm('Delete this joining form record?')) return;
        var id = $(this).data('id');
        $.post(admin_url + 'powerform/delete_joining_form_details/' + id, { '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' }).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) { alert_float('success', r.message); setTimeout(function() { location.reload(); }, 1000); }
            else alert_float('danger', r.message);
        }).fail(function() { alert_float('danger', 'Failed to delete'); });
    });
});
</script>
</body>
</html>
