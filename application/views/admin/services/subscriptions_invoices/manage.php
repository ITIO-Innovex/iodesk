<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
              <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Subscription Invoices</h4>
              <a href="#" class="btn btn-primary" onclick="open_subscription_invoice_modal(); return false;">
                <i class="fa-regular fa-plus tw-mr-1"></i> Add New Invoice
              </a>
            </div>
            <?php render_datatable([
              'ID',
              'Invoice No',
              'Company ID',
              'Subscription ID',
              'Amount',
              'Currency',
              'Tax',
              'Total Amount',
              'Invoice Date',
              'Due Date',
              'Payment Status',
              _l('options'),
            ], 'services_subscriptions_invoices'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="subscription_invoice_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('services/subscriptions_invoices_manage'), ['id' => 'subscription-invoice-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="subscription_invoice_modal_label">Add Subscription Invoice</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="subscription_invoice_id" value="">
        <div class="form-group">
          <label for="invoice_no" class="control-label">Invoice No</label>
          <input type="text" class="form-control" id="invoice_no" name="invoice_no" placeholder="INV-0001">
        </div>
        <div class="form-group">
          <label for="invoice_company_id" class="control-label">Company ID</label>
          <input type="number" class="form-control" id="invoice_company_id" name="company_id" placeholder="Company ID">
        </div>
        <div class="form-group">
          <label for="invoice_subscription_id" class="control-label">Subscription ID</label>
          <input type="number" class="form-control" id="invoice_subscription_id" name="subscription_id" placeholder="Subscription ID">
        </div>
        <div class="form-group">
          <label for="amount" class="control-label">Amount</label>
          <input type="number" step="0.01" class="form-control" id="amount" name="amount" placeholder="0.00">
        </div>
        <div class="form-group">
          <label for="currency" class="control-label">Currency</label>
          <input type="text" class="form-control" id="currency" name="currency" value="INR">
        </div>
        <div class="form-group">
          <label for="tax" class="control-label">Tax</label>
          <input type="number" step="0.01" class="form-control" id="tax" name="tax" value="0.00">
        </div>
        <div class="form-group">
          <label for="total_amount" class="control-label">Total Amount</label>
          <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" placeholder="0.00">
        </div>
        <div class="form-group">
          <label for="invoice_date" class="control-label">Invoice Date</label>
          <input type="date" class="form-control" id="invoice_date" name="invoice_date">
        </div>
        <div class="form-group">
          <label for="due_date" class="control-label">Due Date</label>
          <input type="date" class="form-control" id="due_date" name="due_date">
        </div>
        <div class="form-group">
          <label for="payment_status" class="control-label">Payment Status</label>
          <select class="form-control selectpicker" name="payment_status" id="payment_status">
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
            <option value="failed">Failed</option>
          </select>
        </div>
        <div class="form-group">
          <label for="payment_method" class="control-label">Payment Method</label>
          <input type="text" class="form-control" id="payment_method" name="payment_method" placeholder="e.g. Card, UPI">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  function open_subscription_invoice_modal() {
    $('#subscription_invoice_modal_label').text('Add Subscription Invoice');
    $('#subscription_invoice_id').val('');
    $('#invoice_no').val('');
    $('#invoice_company_id').val('');
    $('#invoice_subscription_id').val('');
    $('#amount').val('');
    $('#currency').val('INR');
    $('#tax').val('0.00');
    $('#total_amount').val('');
    $('#invoice_date').val('');
    $('#due_date').val('');
    $('#payment_status').selectpicker('val', 'unpaid');
    $('#payment_method').val('');
    $('#subscription_invoice_modal').modal('show');
  }

  function edit_subscription_invoice(el, id) {
    $('#subscription_invoice_modal_label').text('Edit Subscription Invoice');
    $('#subscription_invoice_id').val(id);
    $('#invoice_no').val($(el).data('invoice-no'));
    $('#invoice_company_id').val($(el).data('company-id'));
    $('#invoice_subscription_id').val($(el).data('subscription-id'));
    $('#amount').val($(el).data('amount'));
    $('#currency').val($(el).data('currency'));
    $('#tax').val($(el).data('tax'));
    $('#total_amount').val($(el).data('total-amount'));
    $('#invoice_date').val($(el).data('invoice-date'));
    $('#due_date').val($(el).data('due-date'));
    $('#payment_status').selectpicker('val', $(el).data('payment-status'));
    $('#payment_method').val($(el).data('payment-method'));
    $('#subscription_invoice_modal').modal('show');
  }

  $(function(){
    // Columns: 0 ID, 1 Invoice No, 2 Company ID, 3 Subscription ID, 4 Amount, 5 Currency, 6 Tax, 7 Total, 8 Invoice Date, 9 Due Date, 10 Status, 11 Options
    initDataTable('.table-services_subscriptions_invoices', window.location.href, [11], [11], undefined, [0, 'desc']);

    $('#subscription-invoice-form').on('submit', function(e){
      e.preventDefault();
      var $f = $(this);
      $.post($f.attr('action'), $f.serialize()).done(function(resp){
        var r = {};
        try { r = JSON.parse(resp); } catch(e) {}
        if (r.success) {
          alert_float('success', r.message || 'Saved');
          $('#subscription_invoice_modal').modal('hide');
          $('.table-services_subscriptions_invoices').DataTable().ajax.reload();
        } else {
          alert_float('warning', r.message || 'Validation failed');
        }
      }).fail(function(){
        alert_float('danger', 'Request failed');
      })
    });
  });
</script>
</body>
</html>
