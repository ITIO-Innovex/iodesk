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
                            <button type="button" class="btn btn-primary" id="addPaymentBtn">
                                <i class="fa fa-plus"></i> Add Payment
                            </button>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="paymentsTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Invoice #</th>
                                        <th>Company</th>
                                        <th>Payment Date</th>
                                        <th class="text-right">Amount</th>
                                        <th>Method</th>
                                        <th>Transaction ID</th>
                                        <th>Notes</th>
                                        <th>Created At</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td>
                                                    <a href="<?php echo admin_url('invoice_manager/invoices/view/' . $record['invoice_id']); ?>">
                                                        <strong><?php echo htmlspecialchars($record['invoice_number'] ?? ''); ?></strong>
                                                    </a>
                                                </td>
                                                <td><?php echo htmlspecialchars($record['company_name'] ?? ''); ?></td>
                                                <td><?php echo !empty($record['payment_date']) ? date('d-m-Y', strtotime($record['payment_date'])) : '-'; ?></td>
                                                <td class="text-right text-success"><strong><?php echo number_format((float)$record['amount'], 2); ?></strong></td>
                                                <td><?php echo htmlspecialchars($record['payment_method'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['transaction_id'] ?? '-'); ?></td>
                                                <td><?php echo htmlspecialchars(substr($record['notes'] ?? '', 0, 50)); ?><?php echo strlen($record['notes'] ?? '') > 50 ? '...' : ''; ?></td>
                                                <td><?php echo !empty($record['created_at']) ? date('d-m-Y H:i', strtotime($record['created_at'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs edit-payment" data-id="<?php echo $record['id']; ?>" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-payment" data-id="<?php echo $record['id']; ?>" title="Delete">
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
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="paymentForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="payment_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="modalTitle">Add Payment</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="invoice_id">Invoice <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker" name="invoice_id" id="invoice_id" data-live-search="true" required>
                            <option value="">-- Select Invoice --</option>
                            <?php foreach ($invoices as $inv) { ?>
                                <?php 
                                $balance = (float)$inv['total_amount'] - (float)$inv['paid_amount'];
                                $balanceText = $balance > 0 ? ' (Balance: ' . number_format($balance, 2) . ')' : ' (Fully Paid)';
                                ?>
                                <option value="<?php echo $inv['id']; ?>" data-balance="<?php echo $balance; ?>">
                                    <?php echo htmlspecialchars($inv['invoice_number']); ?> - <?php echo htmlspecialchars($inv['company_name'] ?? 'N/A'); ?><?php echo $balanceText; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_date">Payment Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="payment_date" id="payment_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="amount" id="amount" step="0.01" min="0.01" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method">Payment Method</label>
                                <select class="form-control" name="payment_method" id="payment_method">
                                    <option value="">-- Select --</option>
                                    <option value="Cash">Cash</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Debit Card">Debit Card</option>
                                    <option value="UPI">UPI</option>
                                    <option value="Online">Online</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transaction_id">Transaction ID</label>
                                <input type="text" class="form-control" name="transaction_id" id="transaction_id" placeholder="Reference number">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Payment notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="savePaymentBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    function resetForm() {
        $('#payment_id').val('');
        $('#invoice_id').val('').selectpicker('refresh');
        $('#payment_date').val('<?php echo date('Y-m-d'); ?>');
        $('#amount').val('');
        $('#payment_method').val('');
        $('#transaction_id').val('');
        $('#notes').val('');
        $('#modalTitle').text('Add Payment');
    }

    $('#addPaymentBtn').on('click', function() {
        resetForm();
        $('#paymentModal').modal('show');
    });

    $(document).on('click', '.edit-payment', function() {
        var id = $(this).data('id');
        resetForm();
        
        $.get(admin_url + 'invoice_manager/get_payment/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) {
                $('#payment_id').val(r.data.id);
                $('#invoice_id').val(r.data.invoice_id).selectpicker('refresh');
                $('#payment_date').val(r.data.payment_date || '');
                $('#amount').val(r.data.amount || '');
                $('#payment_method').val(r.data.payment_method || '');
                $('#transaction_id').val(r.data.transaction_id || '');
                $('#notes').val(r.data.notes || '');
                $('#modalTitle').text('Edit Payment');
                $('#paymentModal').modal('show');
            } else {
                alert_float('danger', r.message || 'Record not found');
            }
        }).fail(function() {
            alert_float('danger', 'Failed to load record');
        });
    });

    $('#paymentForm').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#savePaymentBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.post(admin_url + 'invoice_manager/save_payment', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save');
            
            if (r.success) {
                alert_float('success', r.message);
                $('#paymentModal').modal('hide');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            $btn.prop('disabled', false).html('Save');
            alert_float('danger', 'Failed to save');
        });
    });

    $(document).on('click', '.delete-payment', function() {
        if (!confirm('Are you sure you want to delete this payment?')) return;
        
        var id = $(this).data('id');
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var data = {};
        data[csrfName] = csrfHash;
        
        $.post(admin_url + 'invoice_manager/delete_payment/' + id, data).done(function(res) {
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

    $('#paymentModal').on('hidden.bs.modal', function() {
        resetForm();
    });

    $('#invoice_id').on('change', function() {
        var balance = $(this).find('option:selected').data('balance');
        if (balance && balance > 0) {
            $('#amount').attr('max', balance);
        } else {
            $('#amount').removeAttr('max');
        }
    });
});
</script>
</body>
</html>
