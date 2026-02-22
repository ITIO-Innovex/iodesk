<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? ''); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body { 
            background: #f5f5f5; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0;
        }
        .invoice-container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: #fff; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .invoice-header { 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 30px; 
        }
        .invoice-header .invoice-title { 
            font-size: 32px; 
            font-weight: bold; 
        }
        .invoice-header .invoice-number { 
            font-size: 18px; 
            opacity: 0.9;
        }
        .invoice-body { padding: 30px; }
        .bill-to { 
            background: #f8f9fa; 
            border: 1px solid #e5e5e5; 
            padding: 20px; 
            border-radius: 6px; 
        }
        .bill-to h5 { 
            margin-bottom: 15px; 
            font-weight: bold; 
            color: #333;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .bank-info { 
            background: #e8f4f8; 
            padding: 20px; 
            border-radius: 6px; 
            border-left: 4px solid #17a2b8;
        }
        .bank-info h5 {
            margin-bottom: 15px;
            color: #17a2b8;
        }
        .items-table { margin-top: 30px; }
        .items-table th { 
            background: #667eea; 
            color: #fff;
            border: none;
        }
        .items-table td { vertical-align: middle; }
        .totals-box { 
            background: #f8f9fa; 
            padding: 20px; 
            border-radius: 6px;
            border: 2px solid #e5e5e5;
        }
        .totals-box .total-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .totals-box .total-row:last-child { border-bottom: none; }
        .totals-box .total-label { font-weight: 600; }
        .totals-box .grand-total { 
            font-size: 22px; 
            font-weight: bold; 
            color: #333;
            background: #667eea;
            color: #fff;
            margin: 10px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 4px 4px;
        }
        .status-badge { 
            font-size: 14px; 
            padding: 8px 20px; 
            border-radius: 20px;
            font-weight: bold;
        }
        .status-paid { background: #d4edda; color: #155724; }
        .status-unpaid { background: #f8d7da; color: #721c24; }
        .status-partial { background: #fff3cd; color: #856404; }
        .status-draft { background: #e2e3e5; color: #383d41; }
        .notes-section { 
            background: #fffde7; 
            padding: 20px; 
            border-radius: 6px; 
            border-left: 4px solid #ffc107;
            margin-top: 20px;
        }
        .payment-history { margin-top: 30px; }
        .payment-history h5 { 
            color: #28a745;
            border-bottom: 2px solid #28a745;
            padding-bottom: 10px;
        }
        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: #fff;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 25px;
        }
        .btn-download:hover {
            opacity: 0.9;
            color: #fff;
        }
        .company-info { margin-bottom: 20px; }
        .company-logo { max-height: 60px; margin-bottom: 10px; }
        @media print {
            body { background: #fff; }
            .invoice-container { box-shadow: none; }
            .btn-download { display: none; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="row">
                <div class="col-sm-6">
                    <?php if (!empty($company['company_logo'])) { ?>
                        <img src="<?php echo base_url('uploads/company/' . $company['company_logo']); ?>" alt="Logo" class="company-logo" style="background: #fff; padding: 5px; border-radius: 4px;">
                        <br>
                    <?php } ?>
                    <?php if (!empty($company['companyname'])) { ?>
                        <strong style="font-size: 18px;"><?php echo htmlspecialchars($company['companyname']); ?></strong><br>
                    <?php } ?>
                    <?php if (!empty($company['phonenumber'])) { ?>
                        <small><i class="fa fa-phone"></i> <?php echo htmlspecialchars($company['phonenumber']); ?></small>
                    <?php } ?>
                </div>
                <div class="col-sm-6 text-right">
                    <div class="invoice-title">INVOICE</div>
                    <div class="invoice-number">#<?php echo htmlspecialchars($invoice['invoice_number']); ?></div>
                    <br>
                    <strong>Date:</strong> <?php echo date('d M Y', strtotime($invoice['invoice_date'])); ?><br>
                    <?php if (!empty($invoice['due_date'])) { ?>
                        <strong>Due:</strong> <?php echo date('d M Y', strtotime($invoice['due_date'])); ?>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="invoice-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="bill-to">
                        <h5><i class="fa fa-user"></i> Bill To</h5>
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
                <div class="col-sm-6">
                    <?php if ($bank_details) { ?>
                        <div class="bank-info">
                            <h5><i class="fa fa-bank"></i> Payment Details</h5>
                            <?php if (!empty($bank_details['name'])) { ?>
                                <strong><?php echo htmlspecialchars($bank_details['name']); ?></strong><br>
                            <?php } ?>
                            <?php if (!empty($bank_details['description'])) { ?>
                                <?php echo html_entity_decode($bank_details['description']); ?><br>
                            <?php } ?>
                            <?php if (!empty($bank_details['bank_name'])) { ?>
                                Bank: <?php echo htmlspecialchars($bank_details['bank_name']); ?><br>
                            <?php } ?>
                            <?php if (!empty($bank_details['bank_iban'])) { ?>
                                IBAN: <?php echo htmlspecialchars($bank_details['bank_iban']); ?><br>
                            <?php } ?>
                            <?php if (!empty($bank_details['bank_routing_number'])) { ?>
                                Routing: <?php echo htmlspecialchars($bank_details['bank_routing_number']); ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    
                    <div class="text-center" style="margin-top: 20px;">
                        <?php
                        $status = $invoice['status'] ?? 'Draft';
                        $statusClass = 'status-draft';
                        if ($status === 'Paid') $statusClass = 'status-paid';
                        elseif ($status === 'Unpaid') $statusClass = 'status-unpaid';
                        elseif ($status === 'Partially Paid') $statusClass = 'status-partial';
                        ?>
                        <span class="status-badge <?php echo $statusClass; ?>"><?php echo $status; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="items-table">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Item</th>
                                <th width="20%">Description</th>
                                <th width="10%" class="text-right">Qty</th>
                                <th width="12%" class="text-right">Price</th>
                                <th width="8%" class="text-right">Tax</th>
                                <th width="10%" class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)) { ?>
                                <?php $cnt = 1; foreach ($items as $item) { ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><strong><?php echo htmlspecialchars($item['item_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($item['description'] ?? ''); ?></td>
                                        <td class="text-right"><?php echo number_format((float)$item['quantity'], 2); ?></td>
                                        <td class="text-right"><?php echo number_format((float)$item['unit_price'], 2); ?></td>
                                        <td class="text-right"><?php echo number_format((float)$item['tax_percent'], 2); ?>%</td>
                                        <td class="text-right"><strong><?php echo number_format((float)$item['total'], 2); ?></strong></td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <?php if (!empty($invoice['notes'])) { ?>
                        <div class="notes-section">
                            <h5><i class="fa fa-sticky-note-o"></i> Notes</h5>
                            <?php echo nl2br(htmlspecialchars($invoice['notes'])); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-6">
                    <div class="totals-box">
                        <div class="total-row">
                            <span class="total-label">Subtotal:</span>
                            <span><?php echo number_format((float)$invoice['subtotal'], 2); ?></span>
                        </div>
                        <?php if ((float)$invoice['discount'] > 0) { ?>
                            <div class="total-row">
                                <span class="total-label">Discount:</span>
                                <span class="text-danger">-<?php echo number_format((float)$invoice['discount'], 2); ?></span>
                            </div>
                        <?php } ?>
                        <div class="total-row">
                            <span class="total-label">Tax:</span>
                            <span><?php echo number_format((float)$invoice['tax_amount'], 2); ?></span>
                        </div>
                        <div class="total-row">
                            <span class="total-label text-success">Paid:</span>
                            <span class="text-success"><?php echo number_format((float)$invoice['paid_amount'], 2); ?></span>
                        </div>
                        <?php $balance = (float)$invoice['total_amount'] - (float)$invoice['paid_amount']; ?>
                        <?php if ($balance > 0) { ?>
                            <div class="total-row">
                                <span class="total-label text-danger">Balance Due:</span>
                                <span class="text-danger"><strong><?php echo number_format($balance, 2); ?></strong></span>
                            </div>
                        <?php } ?>
                        <div class="grand-total">
                            <div class="total-row" style="border: none; padding: 0;">
                                <span>Total:</span>
                                <span><?php echo number_format((float)$invoice['total_amount'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($payments)) { ?>
                <div class="payment-history">
                    <h5><i class="fa fa-money"></i> Payment History</h5>
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
                                        <td><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
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
            
            <div class="text-center" style="margin-top: 30px;">
                <a href="<?php echo site_url('invoice_view/pdf/' . $invoice['id'] . '/' . $hash); ?>" class="btn btn-download">
                    <i class="fa fa-file-pdf-o"></i> Download PDF
                </a>
                <button onclick="window.print();" class="btn btn-default" style="margin-left: 10px;">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
    
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
