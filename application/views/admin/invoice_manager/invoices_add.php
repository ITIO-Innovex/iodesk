<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .item-row { background: #f9f9f9; padding: 10px; margin-bottom: 10px; border-radius: 4px; border: 1px solid #e5e5e5; }
    .item-row:hover { background: #f5f5f5; }
    .remove-item { color: #d9534f; cursor: pointer; }
    .remove-item:hover { color: #c9302c; }
    .totals-section { background: #f5f5f5; padding: 15px; border-radius: 4px; }
    .totals-section .row { margin-bottom: 8px; }
    .totals-section .total-label { font-weight: 600; }
    .totals-section .grand-total { font-size: 18px; color: #333; font-weight: bold; }
    .bank-details { background: #e8f4f8; padding: 10px; border-radius: 4px; margin-top: 10px; font-size: 12px; }
    .product-select { min-width: 200px; }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-my-0 tw-font-semibold"><?php echo $title; ?></h4>
                            <a href="<?php echo admin_url('invoice_manager/invoices'); ?>" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                        <hr class="hr-panel-heading" />
                        
                        <form id="invoiceForm">
                            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Invoice Details</div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Invoice Number <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="invoice_number" id="invoice_number" value="<?php echo $invoice_number; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Invoice Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" name="invoice_date" id="invoice_date" value="<?php echo date('Y-m-d'); ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Due Date</label>
                                                        <input type="date" class="form-control" name="due_date" id="due_date">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <h5 class="tw-font-semibold tw-mt-4 tw-mb-3">Bill To</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Company Name</label>
                                                        <input type="text" class="form-control" name="company_name" id="company_name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contact Person</label>
                                                        <input type="text" class="form-control" name="contact_person" id="contact_person">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" class="form-control" name="contact_email" id="contact_email">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone</label>
                                                        <input type="text" class="form-control" name="contact_phone" id="contact_phone">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address Line 1</label>
                                                        <input type="text" class="form-control" name="contact_address_line1" id="contact_address_line1">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Address Line 2</label>
                                                        <input type="text" class="form-control" name="contact_address_line2" id="contact_address_line2">
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" name="customer_id" id="customer_id" value="0">
                                        </div>
                                    </div>
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <span>Invoice Items</span>
                                            <button type="button" class="btn btn-success btn-xs pull-right" id="addItemBtn">
                                                <i class="fa fa-plus"></i> Add Item
                                            </button>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="itemsTable">
                                                    <thead>
                                                        <tr>
                                                            <th width="25%">Product/Item</th>
                                                            <th width="25%">Description</th>
                                                            <th width="10%">Qty</th>
                                                            <th width="15%">Unit Price</th>
                                                            <th width="10%">Tax %</th>
                                                            <th width="12%">Total</th>
                                                            <th width="3%"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="itemsBody">
                                                        <tr class="item-row" data-row="0">
                                                            <td>
                                                                <select class="form-control product-select" name="items[0][product_id]" onchange="loadProduct(this, 0)">
                                                                    <option value="">-- Select or type manually --</option>
                                                                    <?php foreach ($products as $p) { ?>
                                                                        <option value="<?php echo $p['id']; ?>" data-price="<?php echo $p['price']; ?>" data-tax="<?php echo $p['tax_percent']; ?>" data-desc="<?php echo htmlspecialchars($p['description'] ?? ''); ?>"><?php echo htmlspecialchars($p['product_name']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <input type="text" class="form-control mtop5" name="items[0][item_name]" placeholder="Item name" required>
                                                            </td>
                                                            <td><textarea class="form-control" name="items[0][description]" rows="2" placeholder="Description"></textarea></td>
                                                            <td><input type="number" class="form-control item-qty" name="items[0][quantity]" value="1" min="0.01" step="0.01" onchange="calculateRow(0)"></td>
                                                            <td><input type="number" class="form-control item-price" name="items[0][unit_price]" value="0" min="0" step="0.01" onchange="calculateRow(0)"></td>
                                                            <td>
                                                                <select class="form-control item-tax" name="items[0][tax_percent]" onchange="calculateRow(0)">
                                                                    <option value="0">0%</option>
                                                                    <?php foreach ($tax_rates as $tax) { ?>
                                                                        <option value="<?php echo $tax['taxrate']; ?>"><?php echo $tax['taxrate']; ?>%</option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" class="form-control item-total" name="items[0][total]" value="0.00" readonly></td>
                                                            <td class="text-center"><i class="fa fa-trash remove-item" onclick="removeRow(this)"></i></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Notes</div>
                                        <div class="panel-body">
                                            <?php /*?><div class="form-group">
                                                <label>Select Predefined Note</label>
                                                <select class="form-control" id="predefinedNote" onchange="insertNote()">
                                                    <option value="">-- Select --</option>
                                                    <?php foreach ($invoice_notes as $note) { ?>
                                                        <option value="<?php echo htmlspecialchars($note['description']); ?>">
                                                            <?php echo htmlspecialchars(strip_tags(substr($note['description'], 0, 100))); ?>...
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div><?php */?>
                                            <div class="form-group">
                                                <label>Invoice Notes</label>
                                                <textarea class="form-control" name="notes" id="invoice_notes" rows="4" placeholder="Terms, conditions, payment instructions..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Summary</div>
                                        <div class="panel-body totals-section">
                                            <div class="row">
                                                <div class="col-xs-6 total-label">Subtotal:</div>
                                                <div class="col-xs-6 text-right" id="displaySubtotal">0.00</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6 total-label">Discount:</div>
                                                <div class="col-xs-6">
                                                    <input type="number" class="form-control input-sm" name="discount" id="discount" value="0" min="0" step="0.01" onchange="calculateTotals()">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6 total-label">Tax:</div>
                                                <div class="col-xs-6 text-right" id="displayTax">0.00</div>
                                            </div>
                                            <hr style="margin: 10px 0;">
                                            <div class="row">
                                                <div class="col-xs-6 grand-total">Total:</div>
                                                <div class="col-xs-6 text-right grand-total" id="displayTotal">0.00</div>
                                            </div>
                                            <input type="hidden" name="subtotal" id="subtotal" value="0">
                                            <input type="hidden" name="tax_amount" id="tax_amount" value="0">
                                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                                        </div>
                                    </div>
                                    
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Payment</div>
                                        <div class="panel-body">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select class="form-control" name="status" id="status">
                                                    <option value="Draft">Draft</option>
                                                    <option value="Unpaid">Unpaid</option>
                                                    <option value="Partially Paid">Partially Paid</option>
                                                    <option value="Paid">Paid</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Paid Amount</label>
                                                <input type="number" class="form-control" name="paid_amount" id="paid_amount" value="0" min="0" step="0.01">
                                            </div>
                                            <div class="form-group">
                                                <label>Payment Bank / Mode</label>
                                                <select class="form-control" name="payment_bank" id="payment_bank" onchange="showBankDetails()">
                                                    <option value="">-- Select --</option>
                                                    <?php foreach ($payment_modes as $pm) { ?>
                                                        <option value="<?php echo $pm['id']; ?>" 
                                                            data-bank="<?php echo htmlspecialchars($pm['bank_name'] ?? ''); ?>"
                                                            data-iban="<?php echo htmlspecialchars($pm['bank_iban'] ?? ''); ?>"
                                                            data-routing="<?php echo htmlspecialchars($pm['bank_routing_number'] ?? ''); ?>"
                                                            data-code="<?php echo htmlspecialchars($pm['bank_code'] ?? ''); ?>">
                                                            <?php echo htmlspecialchars($pm['name']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <div class="bank-details" id="bankDetails" style="display: none;">
                                                    <strong>Bank Details:</strong><br>
                                                    <span id="bankName"></span><br>
                                                    <span id="bankIban"></span><br>
                                                    <span id="bankRouting"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block btn-lg" id="saveInvoiceBtn">
                                            <i class="fa fa-save"></i> Save Invoice
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
var rowIndex = 1;
var taxRates = <?php echo json_encode($tax_rates); ?>;
var products = <?php echo json_encode($products); ?>;

function loadProduct(select, row) {
    var $row = $('[data-row="' + row + '"]');
    var $option = $(select).find('option:selected');
    
    if ($option.val()) {
        var price = $option.data('price') || 0;
        var tax = $option.data('tax') || 0;
        var desc = $option.data('desc') || '';
        var name = $option.text();
        
        $row.find('input[name*="[item_name]"]').val(name);
        $row.find('textarea[name*="[description]"]').val(desc);
        $row.find('.item-price').val(price);
        $row.find('.item-tax').val(tax);
        calculateRow(row);
    }
}

function calculateRow(row) {
    var $row = $('[data-row="' + row + '"]');
    var qty = parseFloat($row.find('.item-qty').val()) || 0;
    var price = parseFloat($row.find('.item-price').val()) || 0;
    var taxPct = parseFloat($row.find('.item-tax').val()) || 0;
    
    var lineSubtotal = qty * price;
    var lineTax = lineSubtotal * (taxPct / 100);
    var lineTotal = lineSubtotal + lineTax;
    
    $row.find('.item-total').val(lineTotal.toFixed(2));
    calculateTotals();
}

function calculateTotals() {
    var subtotal = 0;
    var totalTax = 0;
    
    $('#itemsBody tr').each(function() {
        var qty = parseFloat($(this).find('.item-qty').val()) || 0;
        var price = parseFloat($(this).find('.item-price').val()) || 0;
        var taxPct = parseFloat($(this).find('.item-tax').val()) || 0;
        
        var lineSubtotal = qty * price;
        var lineTax = lineSubtotal * (taxPct / 100);
        
        subtotal += lineSubtotal;
        totalTax += lineTax;
    });
    
    var discount = parseFloat($('#discount').val()) || 0;
    var total = subtotal + totalTax - discount;
    
    $('#displaySubtotal').text(subtotal.toFixed(2));
    $('#displayTax').text(totalTax.toFixed(2));
    $('#displayTotal').text(total.toFixed(2));
    
    $('#subtotal').val(subtotal.toFixed(2));
    $('#tax_amount').val(totalTax.toFixed(2));
    $('#total_amount').val(total.toFixed(2));
}

function addItemRow() {
    var taxOptions = '<option value="0">0%</option>';
    taxRates.forEach(function(t) {
        taxOptions += '<option value="' + t.taxrate + '">' + t.taxrate + '%</option>';
    });
    
    var productOptions = '<option value="">-- Select or type manually --</option>';
    products.forEach(function(p) {
        var desc = (p.description || '').replace(/"/g, '&quot;');
        productOptions += '<option value="' + p.id + '" data-price="' + p.price + '" data-tax="' + p.tax_percent + '" data-desc="' + desc + '">' + p.product_name + '</option>';
    });
    
    var html = '<tr class="item-row" data-row="' + rowIndex + '">' +
        '<td>' +
            '<select class="form-control product-select" name="items[' + rowIndex + '][product_id]" onchange="loadProduct(this, ' + rowIndex + ')">' + productOptions + '</select>' +
            '<input type="text" class="form-control mtop5" name="items[' + rowIndex + '][item_name]" placeholder="Item name" required>' +
        '</td>' +
        '<td><textarea class="form-control" name="items[' + rowIndex + '][description]" rows="2" placeholder="Description"></textarea></td>' +
        '<td><input type="number" class="form-control item-qty" name="items[' + rowIndex + '][quantity]" value="1" min="0.01" step="0.01" onchange="calculateRow(' + rowIndex + ')"></td>' +
        '<td><input type="number" class="form-control item-price" name="items[' + rowIndex + '][unit_price]" value="0" min="0" step="0.01" onchange="calculateRow(' + rowIndex + ')"></td>' +
        '<td><select class="form-control item-tax" name="items[' + rowIndex + '][tax_percent]" onchange="calculateRow(' + rowIndex + ')">' + taxOptions + '</select></td>' +
        '<td><input type="text" class="form-control item-total" name="items[' + rowIndex + '][total]" value="0.00" readonly></td>' +
        '<td class="text-center"><i class="fa fa-trash remove-item" onclick="removeRow(this)"></i></td>' +
    '</tr>';
    
    $('#itemsBody').append(html);
    rowIndex++;
}

function removeRow(elem) {
    var $tbody = $('#itemsBody');
    if ($tbody.find('tr').length > 1) {
        $(elem).closest('tr').remove();
        calculateTotals();
    } else {
        alert_float('warning', 'At least one item is required');
    }
}

function insertNote() {
    var note = $('#predefinedNote').val();
    if (note) {
        var current = $('#invoice_notes').val();
        $('#invoice_notes').val(current ? current + '\n' + note : note);
        $('#predefinedNote').val('');
    }
}

function showBankDetails() {
    var $selected = $('#payment_bank option:selected');
    if ($selected.val()) {
        var bank = $selected.data('bank') || '';
        var iban = $selected.data('iban') || '';
        var routing = $selected.data('routing') || '';
        
        if (bank || iban || routing) {
            $('#bankName').text(bank ? 'Bank: ' + bank : '');
            $('#bankIban').text(iban ? 'IBAN: ' + iban : '');
            $('#bankRouting').text(routing ? 'Routing: ' + routing : '');
            $('#bankDetails').show();
        } else {
            $('#bankDetails').hide();
        }
    } else {
        $('#bankDetails').hide();
    }
}

$(function() {
    $('#addItemBtn').on('click', function() {
        addItemRow();
    });
    
    $('#invoiceForm').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#saveInvoiceBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.post(admin_url + 'invoice_manager/save_invoice', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Invoice');
            
            if (r.success) {
                alert_float('success', r.message);
                setTimeout(function() {
                    window.location.href = admin_url + 'invoice_manager/invoices';
                }, 1500);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Invoice');
            alert_float('danger', 'Failed to save invoice');
        });
    });
    
    calculateTotals();
});
</script>
</body>
</html>
