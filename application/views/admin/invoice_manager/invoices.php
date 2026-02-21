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
                            <a href="<?php echo admin_url('invoice_manager/invoices/add'); ?>" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add Invoice
                            </a>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="invoicesTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Invoice #</th>
                                        <th>Company</th>
                                        <th>Contact</th>
                                        <th>Date</th>
                                        <th>Due Date</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-right">Paid</th>
                                        <th>Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><strong><?php echo htmlspecialchars($record['invoice_number']); ?></strong></td>
                                                <td><?php echo htmlspecialchars($record['company_name'] ?? ''); ?></td>
                                                <td>
                                                    <?php echo htmlspecialchars($record['contact_person'] ?? ''); ?>
                                                    <?php if (!empty($record['contact_email'])) { ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($record['contact_email']); ?></small>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo !empty($record['invoice_date']) ? date('d-m-Y', strtotime($record['invoice_date'])) : '-'; ?></td>
                                                <td><?php echo !empty($record['due_date']) ? date('d-m-Y', strtotime($record['due_date'])) : '-'; ?></td>
                                                <td class="text-right"><?php echo number_format((float)$record['total_amount'], 2); ?></td>
                                                <td class="text-right"><?php echo number_format((float)$record['paid_amount'], 2); ?></td>
                                                <td>
                                                    <?php
                                                    $status = $record['status'] ?? 'Draft';
                                                    $statusClass = 'label-default';
                                                    if ($status === 'Paid') $statusClass = 'label-success';
                                                    elseif ($status === 'Unpaid') $statusClass = 'label-danger';
                                                    elseif ($status === 'Partially Paid') $statusClass = 'label-warning';
                                                    elseif ($status === 'Draft') $statusClass = 'label-default';
                                                    ?>
                                                    <span class="label <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                                                </td>
                                                <td>
                                                    <a href="<?php echo admin_url('invoice_manager/invoices/view/' . $record['id']); ?>" class="btn btn-info btn-xs" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo admin_url('invoice_manager/invoices/edit/' . $record['id']); ?>" class="btn btn-primary btn-xs" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger btn-xs delete-invoice" data-id="<?php echo $record['id']; ?>" title="Delete">
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

<?php init_tail(); ?>
<script>
$(function() {
    $(document).on('click', '.delete-invoice', function() {
        if (!confirm('Are you sure you want to delete this invoice?')) return;
        
        var id = $(this).data('id');
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var data = {};
        data[csrfName] = csrfHash;
        
        $.post(admin_url + 'invoice_manager/delete_invoice/' + id, data).done(function(res) {
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
});
</script>
</body>
</html>
