<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();  //print_r($bank_details);
?>
<style>
    .invoice-header { background: #f8f9fa; padding: 20px; border-radius: 4px; margin-bottom: 20px; }
    .invoice-title { font-size: 28px; font-weight: bold; color: #333; }
    .invoice-number { font-size: 16px; color: #666; }
    .bill-to { background: #fff; border: 1px solid #e5e5e5; padding: 15px; border-radius: 4px; }
    .bill-to h5 { margin-bottom: 10px; font-weight: bold; color: #333; }
    .items-table th { background: #f5f5f5; }
    .totals-box { background: #f8f9fa; padding: 15px; border-radius: 4px; }
    .totals-box .row { margin-bottom: 8px; }
    .totals-box .total-label { font-weight: 600; }
    .totals-box .grand-total { font-size: 20px; font-weight: bold; color: #333; }
    .status-badge { font-size: 14px; padding: 5px 15px; }
    .bank-info { background: #e8f4f8; padding: 15px; border-radius: 4px; margin-top: 15px; }
    .payment-history { margin-top: 20px; }
    .notes-section { background: #fffde7; padding: 15px; border-radius: 4px; border-left: 4px solid #ffc107; }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-my-0 tw-font-semibold"><?php echo $title; ?></h4>
                            <div>
                                <a href="<?php echo admin_url('invoice_manager/invoices/pdf/' . $invoice['id']); ?>" class="btn btn-success">
                                    <i class="fa fa-file-pdf-o"></i> Download PDF
                                </a>
                                <a href="<?php echo admin_url('invoice_manager/invoices/edit/' . $invoice['id']); ?>" class="btn btn-primary">
                                    <i class="fa fa-pencil"></i> Edit
                                </a>
                                <a href="<?php echo admin_url('invoice_manager/invoices'); ?>" class="btn btn-default">
                                    <i class="fa fa-arrow-left"></i> Back to List
                                </a>
                            </div>
                        </div>
                        <hr class="hr-panel-heading" />
                        
                        <div class="invoice-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="invoice-title">INVOICE</div>
                                    <div class="invoice-number">#<?php echo htmlspecialchars($invoice['invoice_number']); ?></div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <?php
                                    $status = $invoice['status'] ?? 'Draft';
                                    $statusClass = 'label-default';
                                    if ($status === 'Paid') $statusClass = 'label-success';
                                    elseif ($status === 'Unpaid') $statusClass = 'label-danger';
                                    elseif ($status === 'Partially Paid') $statusClass = 'label-warning';
                                    ?>
                                    <span class="label <?php echo $statusClass; ?> status-badge"><?php echo $status; ?></span>
                                    <br><br>
                                    <strong>Invoice Date:</strong> <?php echo date('d-m-Y', strtotime($invoice['invoice_date'])); ?><br>
                                    <?php if (!empty($invoice['due_date'])) { ?>
                                        <strong>Due Date:</strong> <?php echo date('d-m-Y', strtotime($invoice['due_date'])); ?>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="bill-to">
                                    <h5>Bill To</h5>
                                    <?php if (!empty($invoice['company_name'])) { ?>
                                        <strong><?php echo htmlspecialchars($invoice['company_name']); ?></strong><br>
                                    <?php } ?>
                                    <?php if (!empty($invoice['contact_person'])) { ?>
                                        <?php echo htmlspecialchars($invoice['contact_person']); ?><br>
                                    <?php } ?>
                                    <?php if (!empty($invoice['contact_email'])) { ?>
                                        <i class="fa fa-envelope-o"></i> <?php echo htmlspecialchars($invoice['contact_email']); ?><br>
                                    <?php } ?>
                                    <?php if (!empty($invoice['contact_phone'])) { ?>
                                        <i class="fa fa-phone"></i> <?php echo htmlspecialchars($invoice['contact_phone']); ?><br>
                                    <?php } ?>
                                    <?php if (!empty($invoice['contact_address_line1'])) { ?>
                                        <?php echo htmlspecialchars($invoice['contact_address_line1']); ?><br>
                                    <?php } ?>
                                    <?php if (!empty($invoice['contact_address_line2'])) { ?>
                                        <?php echo htmlspecialchars($invoice['contact_address_line2']); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php if ($bank_details) { ?>
                                    <div class="bank-info">
                                        <h5><i class="fa fa-bank"></i> Payment Details</h5>
                                        <?php if (!empty($bank_details['name'])) { ?>
                                            <strong><?php echo htmlspecialchars($bank_details['name']); ?></strong><br>
											<strong><?php echo html_entity_decode($bank_details['description']); ?></strong><br>
                                        <?php } ?>
                                        <?php if (!empty($bank_details['bank_name'])) { ?>
                                            Bank: <?php echo htmlspecialchars($bank_details['bank_name']); ?><br>
                                        <?php } ?>
                                        <?php if (!empty($bank_details['bank_iban'])) { ?>
                                            IBAN: <?php echo htmlspecialchars($bank_details['bank_iban']); ?><br>
                                        <?php } ?>
                                        <?php if (!empty($bank_details['bank_routing_number'])) { ?>
                                            Routing: <?php echo htmlspecialchars($bank_details['bank_routing_number']); ?><br>
                                        <?php } ?>
                                        <?php if (!empty($bank_details['bank_code'])) { ?>
                                            Code: <?php echo htmlspecialchars($bank_details['bank_code']); ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <div class="mtop20">
                            <h5 class="tw-font-semibold">Items</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered items-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="30%">Item</th>
                                            <th width="25%">Description</th>
                                            <th width="10%" class="text-right">Qty</th>
                                            <th width="12%" class="text-right">Unit Price</th>
                                            <th width="8%" class="text-right">Tax %</th>
                                            <th width="10%" class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($items)) { ?>
                                            <?php $cnt = 1; foreach ($items as $item) { ?>
                                                <tr>
                                                    <td><?php echo $cnt++; ?></td>
                                                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($item['description'] ?? ''); ?></td>
                                                    <td class="text-right"><?php echo number_format((float)$item['quantity'], 2); ?></td>
                                                    <td class="text-right"><?php echo number_format((float)$item['unit_price'], 2); ?></td>
                                                    <td class="text-right"><?php echo number_format((float)$item['tax_percent'], 2); ?>%</td>
                                                    <td class="text-right"><?php echo number_format((float)$item['total'], 2); ?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row mtop20">
                            <div class="col-md-6">
                                <?php if (!empty($invoice['notes'])) { ?>
                                    <div class="notes-section">
                                        <h5><i class="fa fa-sticky-note-o"></i> Notes</h5>
                                        <?php echo nl2br(htmlspecialchars($invoice['notes'])); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <div class="totals-box">
                                    <div class="row">
                                        <div class="col-xs-6 total-label">Subtotal:</div>
                                        <div class="col-xs-6 text-right"><?php echo number_format((float)$invoice['subtotal'], 2); ?></div>
                                    </div>
                                    <?php if ((float)$invoice['discount'] > 0) { ?>
                                        <div class="row">
                                            <div class="col-xs-6 total-label">Discount:</div>
                                            <div class="col-xs-6 text-right text-danger">-<?php echo number_format((float)$invoice['discount'], 2); ?></div>
                                        </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col-xs-6 total-label">Tax:</div>
                                        <div class="col-xs-6 text-right"><?php echo number_format((float)$invoice['tax_amount'], 2); ?></div>
                                    </div>
                                    <hr style="margin: 10px 0;">
                                    <div class="row">
                                        <div class="col-xs-6 grand-total">Total:</div>
                                        <div class="col-xs-6 text-right grand-total"><?php echo number_format((float)$invoice['total_amount'], 2); ?></div>
                                    </div>
                                    <hr style="margin: 10px 0;">
                                    <div class="row">
                                        <div class="col-xs-6 total-label text-success">Paid:</div>
                                        <div class="col-xs-6 text-right text-success"><?php echo number_format((float)$invoice['paid_amount'], 2); ?></div>
                                    </div>
                                    <?php $balance = (float)$invoice['total_amount'] - (float)$invoice['paid_amount']; ?>
                                    <?php if ($balance > 0) { ?>
                                        <div class="row">
                                            <div class="col-xs-6 total-label text-danger">Balance Due:</div>
                                            <div class="col-xs-6 text-right text-danger"><?php echo number_format($balance, 2); ?></div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($payments)) { ?>
                            <div class="payment-history">
                                <h5 class="tw-font-semibold"><i class="fa fa-money"></i> Payment History</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Method</th>
                                                <th>Transaction ID</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($payments as $payment) { ?>
                                                <tr>
                                                    <td><?php echo date('d-m-Y', strtotime($payment['payment_date'])); ?></td>
                                                    <td class="text-success"><strong><?php echo number_format((float)$payment['amount'], 2); ?></strong></td>
                                                    <td><?php echo htmlspecialchars($payment['payment_method'] ?? ''); ?></td>
                                                    <td><?php echo htmlspecialchars($payment['transaction_id'] ?? '-'); ?></td>
                                                    <td><?php echo htmlspecialchars($payment['notes'] ?? ''); ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body>
</html>
