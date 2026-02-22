<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Invoice_sales_pdf extends App_pdf
{
    protected $invoice = [];
    protected $items = [];
    protected $payments = [];
    protected $bank_details = [];

    public function __construct($data = [])
    {
	
	
        $this->invoice = isset($data['invoice']) ? $data['invoice'] : [];
        $this->items = isset($data['items']) ? $data['items'] : [];
        $this->payments = isset($data['payments']) ? $data['payments'] : [];
        $this->bank_details = isset($data['bank_details']) ? $data['bank_details'] : [];

        parent::__construct();

        $invNumber = isset($this->invoice['invoice_number']) ? $this->invoice['invoice_number'] : 'Invoice';
        $this->SetTitle('Invoice - ' . $invNumber);
    }

    public function prepare()
    {
        $html = $this->buildHtml();
        $this->writeHTML($html, true, false, true, false, '');

        return $this;
    }

    protected function buildHtml()
    {
        $inv = $this->invoice;
        $logoUrl = pdf_logo_url();
        $html = '<style>
            .header-table { width: 100%; margin-bottom: 20px; }
            .invoice-title { font-size: 28px; font-weight: bold; color: #333; }
            .invoice-number { font-size: 14px; color: #666; }
            .section-title { 
                background-color: #f5f5f5; 
                padding: 8px 10px; 
                font-weight: bold; 
                font-size: 11px;
                border-bottom: 2px solid #333;
                margin-top: 15px;
                margin-bottom: 10px;
            }
            .info-table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 10px;
            }
            .info-table th { 
                background-color: #f9f9f9; 
                padding: 6px 8px; 
                text-align: left; 
                font-weight: bold;
                border: 1px solid #ddd;
                font-size: 12px;
            }
            .info-table td { 
                padding: 6px 8px; 
                border: 1px solid #ddd;
                font-size: 14px;
            }
            .items-table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 15px;
				margin-top: 15px;
            }
            .items-table th { 
                background-color: #4f81bd; 
                color: #fff;
                padding: 8px; 
                text-align: left;
                font-size: 12px;
            }
            .items-table td { 
                padding: 8px; 
                border: 1px solid #ddd;
                font-size: 12px;
            }
            .items-table .text-right { text-align: right; }
            .totals-table { width: 50%; margin-left: 50%; }
            .totals-table td { padding: 5px 8px; font-size: 12px; }
            .totals-table .label { text-align: right; font-weight: bold; }
            .totals-table .value { text-align: right; }
            .totals-table .grand-total { font-size: 12px; font-weight: bold; background: #f5f5f5; }
            .status-badge {
                padding: 3px 10px;
                border-radius: 3px;
                font-size: 10px;
                font-weight: bold;
            }
            .status-paid { background-color: #dff0d8; color: #3c763d; }
            .status-unpaid { background-color: #f2dede; color: #a94442; }
            .status-partial { background-color: #fcf8e3; color: #8a6d3b; }
            .status-draft { background-color: #e0e0e0; color: #555; }
            .bill-to { background: #f9f9f9; padding: 10px; border: 1px solid #ddd; }
            .bank-info { background: #e8f4f8; padding: 10px; border: 1px solid #cde; font-size: 12px; }
            .notes-section { background: #fffde7; padding: 10px; border-left: 3px solid #ffc107; font-size: 12px; margin-top: 15px; }
        </style>';

        $html .= '<table class="header-table"><tr>';
        $html .= '<td width="50%">' . $logoUrl . '</td>';
        $html .= '<td width="50%" style="text-align: right;">';
        $html .= '<span class="invoice-title">INVOICE</span><br>';
        $html .= '<span class="invoice-number">#' . $this->esc($inv['invoice_number'] ?? '') . '</span><br><br>';
        $html .= '<strong>Date:</strong> ' . $this->formatDate($inv['invoice_date'] ?? '') . '<br>';
        $html .= '<strong>Due Date:</strong> ' . $this->formatDate($inv['due_date'] ?? '') . '<br>';
        $html .= $this->getStatusBadge($inv['status'] ?? 'Draft');
        $html .= '</td></tr></table>';

        $html .= '<table class="info-table" style="margin-bottom: 20px;"><tr>';
        $html .= '<td width="50%" class="bill-to">';
        $html .= '<strong style="font-size: 17px;color: #4f81bd;">Bill To:</strong><br>';
        if (!empty($inv['company_name'])) {
            $html .= '<strong>' . $this->esc($inv['company_name']) . '</strong><br>';
        }
        if (!empty($inv['contact_person'])) {
            $html .= $this->esc($inv['contact_person']) . '<br>';
        }
        if (!empty($inv['contact_email'])) {
            $html .= $this->esc($inv['contact_email']) . '<br>';
        }
        if (!empty($inv['contact_phone'])) {
            $html .= $this->esc($inv['contact_phone']) . '<br>';
        }
        if (!empty($inv['contact_address_line1'])) {
            $html .= $this->esc($inv['contact_address_line1']) . '<br>';
        }
        if (!empty($inv['contact_address_line2'])) {
            $html .= $this->esc($inv['contact_address_line2']);
        }
        $html .= '</td>';
        
        $html .= '<td width="50%" class="bank-info">';
        if (!empty($this->bank_details)) {
            $html .= '<strong style="font-size: 17px;color: #4f81bd;">Payment Details:</strong><br>';
            if (!empty($this->bank_details['name'])) {
                $html .= '<strong>' . $this->esc($this->bank_details['name']) . '</strong><br>';
            }
            if (!empty($this->bank_details['description'])) {
                $html .= 'Bank: ' . html_entity_decode($this->esc($this->bank_details['description'])) . '<br>';
            }
            
        } else {
            $html .= '&nbsp;';
        }
        $html .= '</td>';
        $html .= '</tr></table>';

        $html .= '<table class="items-table" border=2 style="margin-bottom: 20px;" >';
        $html .= '<tr>';
        $html .= '<th width="5%">#</th>';
        $html .= '<th width="35%">Item</th>';
        $html .= '<th width="20%">Description</th>';
        $html .= '<th width="10%" style="text-align: right;">Qty</th>';
        $html .= '<th width="12%" style="text-align: right;">Unit Price</th>';
        $html .= '<th width="8%" style="text-align: right;">Tax</th>';
        $html .= '<th width="10%" style="text-align: right;">Total</th>';
        $html .= '</tr>';
        
        if (!empty($this->items)) {
            $cnt = 1;
            foreach ($this->items as $item) {
                $html .= '<tr>';
                $html .= '<td>' . $cnt++ . '</td>';
                $html .= '<td>' . $this->esc($item['item_name'] ?? '') . '</td>';
                $html .= '<td>' . $this->esc($item['description'] ?? '') . '</td>';
                $html .= '<td class="text-right">' . number_format((float)($item['quantity'] ?? 0), 2) . '</td>';
                $html .= '<td class="text-right">' . number_format((float)($item['unit_price'] ?? 0), 2) . '</td>';
                $html .= '<td class="text-right">' . number_format((float)($item['tax_percent'] ?? 0), 2) . '%</td>';
                $html .= '<td class="text-right">' . number_format((float)($item['total'] ?? 0), 2) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';

        $html .= '<table class="totals-table">';
        $html .= '<tr><td class="label">Subtotal:</td><td class="value">' . number_format((float)($inv['subtotal'] ?? 0), 2) . '</td></tr>';
        if ((float)($inv['discount'] ?? 0) > 0) {
            $html .= '<tr><td class="label">Discount:</td><td class="value" style="color: #a94442;">-' . number_format((float)$inv['discount'], 2) . '</td></tr>';
        }
        $html .= '<tr><td class="label">Tax:</td><td class="value">' . number_format((float)($inv['tax_amount'] ?? 0), 2) . '</td></tr>';
        $html .= '<tr class="grand-total"><td class="label">Total:</td><td class="value">' . number_format((float)($inv['total_amount'] ?? 0), 2) . '</td></tr>';
        $html .= '<tr><td class="label" style="color: #3c763d;">Paid:</td><td class="value" style="color: #3c763d;">' . number_format((float)($inv['paid_amount'] ?? 0), 2) . '</td></tr>';
        
        $balance = (float)($inv['total_amount'] ?? 0) - (float)($inv['paid_amount'] ?? 0);
        if ($balance > 0) {
            $html .= '<tr><td class="label" style="color: #a94442;">Balance Due:</td><td class="value" style="color: #a94442; font-weight: bold;">' . number_format($balance, 2) . '</td></tr>';
        }
        $html .= '</table>';

        if (!empty($inv['notes'])) {
            $html .= '<div class="notes-section">';
            $html .= '<strong>Notes:</strong><br>';
            $html .= nl2br($this->esc($inv['notes']));
            $html .= '</div>';
        }

        /*if (!empty($this->payments)) {
            $html .= '<div class="section-title">PAYMENT HISTORY</div>';
            $html .= '<table class="info-table">';
            $html .= '<tr><th>Date</th><th>Amount</th><th>Method</th><th>Transaction ID</th><th>Notes</th></tr>';
            foreach ($this->payments as $payment) {
                $html .= '<tr>';
                $html .= '<td>' . $this->formatDate($payment['payment_date'] ?? '') . '</td>';
                $html .= '<td style="color: #3c763d; font-weight: bold;">' . number_format((float)($payment['amount'] ?? 0), 2) . '</td>';
                $html .= '<td>' . $this->esc($payment['payment_method'] ?? '') . '</td>';
                $html .= '<td>' . $this->esc($payment['transaction_id'] ?? '-') . '</td>';
                $html .= '<td>' . $this->esc($payment['notes'] ?? '') . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';
        }*/

        $html .= '<br><div style="text-align: center; font-size: 9px; color: #999;">';
        $html .= 'Generated on: ' . date('d-m-Y H:i');
        $html .= '</div>';
        //echo $html;exit;

        return $html;
    }

    protected function esc($value)
    {
        return $value === null || $value === '' ? '-' : htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    protected function formatDate($date)
    {
        if (empty($date) || $date === '0000-00-00') {
            return '-';
        }
        return date('d-m-Y', strtotime($date));
    }

    protected function getStatusBadge($status)
    {
        $class = 'status-draft';
        if ($status === 'Paid') $class = 'status-paid';
        elseif ($status === 'Unpaid') $class = 'status-unpaid';
        elseif ($status === 'Partially Paid') $class = 'status-partial';

        return '<span class="status-badge ' . $class . '">' . htmlspecialchars($status) . '</span>';
    }

    protected function type()
    {
        return 'invoice';
    }

    protected function file_path()
    {
        return '';
    }
}
