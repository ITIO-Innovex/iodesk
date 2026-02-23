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
                            <button type="button" class="btn btn-primary" id="addCompanyBtn">
                                <i class="fa fa-plus"></i> Add Company
                            </button>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="invoiceCompanyTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Company Name</th>
                                        <th>Address</th>
                                        <th>Email</th>
                                        <th>Contact</th>
                                        <th width="8%">Status</th>
                                        <th width="12%">Added On</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo htmlspecialchars($record['inv_company_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['inv_company_address'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['inv_company_email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['inv_company_contact'] ?? ''); ?></td>
                                                <td>
                                                    <?php if (isset($record['inv_company_status']) && $record['inv_company_status'] == 1) { ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php } else { ?>
                                                        <span class="label label-default">Inactive</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo !empty($record['addedon']) ? date('d-m-Y H:i', strtotime($record['addedon'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs edit-company" data-id="<?php echo $record['inv_company_id']; ?>" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-company" data-id="<?php echo $record['inv_company_id']; ?>" title="Delete">
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

<!-- Add/Edit Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="companyForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="inv_company_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="modalTitle">Add Invoice Company</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inv_company_name">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="inv_company_name" id="inv_company_name" required>
                    </div>
                    <div class="form-group">
                        <label for="inv_company_address">Address</label>
                        <input type="text" class="form-control" name="inv_company_address" id="inv_company_address">
                    </div>
                    <div class="form-group">
                        <label for="inv_company_email">Email</label>
                        <input type="email" class="form-control" name="inv_company_email" id="inv_company_email">
                    </div>
                    <div class="form-group">
                        <label for="inv_company_contact">Contact</label>
                        <input type="text" class="form-control" name="inv_company_contact" id="inv_company_contact">
                    </div>
                    <div class="form-group">
                        <label for="inv_company_status">Status</label>
                        <select class="form-control" name="inv_company_status" id="inv_company_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveCompanyBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    function resetForm() {
        $('#inv_company_id').val('');
        $('#inv_company_name').val('');
        $('#inv_company_address').val('');
        $('#inv_company_email').val('');
        $('#inv_company_contact').val('');
        $('#inv_company_status').val('1');
        $('#modalTitle').text('Add Invoice Company');
    }

    $('#addCompanyBtn').on('click', function() {
        resetForm();
        $('#companyModal').modal('show');
    });

    $(document).on('click', '.edit-company', function() {
        var id = $(this).data('id');
        resetForm();

        $.get(admin_url + 'invoice_manager/get_invoice_company/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) {
                var d = r.data;
                $('#inv_company_id').val(d.inv_company_id);
                $('#inv_company_name').val(d.inv_company_name || '');
                $('#inv_company_address').val(d.inv_company_address || '');
                $('#inv_company_email').val(d.inv_company_email || '');
                $('#inv_company_contact').val(d.inv_company_contact || '');
                $('#inv_company_status').val(d.inv_company_status !== undefined ? d.inv_company_status : '1');
                $('#modalTitle').text('Edit Invoice Company');
                $('#companyModal').modal('show');
            } else {
                alert_float('danger', r.message || 'Record not found');
            }
        }).fail(function() {
            alert_float('danger', 'Failed to load record');
        });
    });

    $('#companyForm').on('submit', function(e) {
        e.preventDefault();

        var $btn = $('#saveCompanyBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.post(admin_url + 'invoice_manager/save_invoice_company', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save');

            if (r.success) {
                alert_float('success', r.message);
                $('#companyModal').modal('hide');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            $btn.prop('disabled', false).html('Save');
            alert_float('danger', 'Failed to save');
        });
    });

    $(document).on('click', '.delete-company', function() {
        if (!confirm('Are you sure you want to delete this invoice company?')) return;

        var id = $(this).data('id');
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var data = {};
        data[csrfName] = csrfHash;

        $.post(admin_url + 'invoice_manager/delete_invoice_company/' + id, data).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) {
                alert_float('success', r.message);
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            alert_float('danger', 'Failed to delete');
        });
    });

    $('#companyModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});
</script>
</body>
</html>
