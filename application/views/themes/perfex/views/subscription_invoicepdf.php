<?php

defined('BASEPATH') or exit('No direct script access allowed');

$invoice = is_array($invoice) ? $invoice : [];
$dimensions = $pdf->getPageDimensions();

$company_info = '<div style="color:#424242;">';
$company_info .= format_organization_info();
$company_info .= '</div>';

$invoice_no = $invoice['invoice_no'] ?? '';
$issued_on = $invoice['invoice_date'] ?? '';
$due_on = $invoice['due_date'] ?? '';
$status = $invoice['payment_status'] ?? '';
$method = $invoice['payment_method'] ?? '';
$payment_id = $invoice['payment_id'] ?? '';
$currency = $invoice['currency'] ?? 'INR';
$amount = isset($invoice['amount']) ? number_format((float) $invoice['amount'], 2) : '0.00';
$tax = isset($invoice['tax']) ? number_format((float) $invoice['tax'], 2) : '0.00';
$total = isset($invoice['total_amount']) ? number_format((float) $invoice['total_amount'], 2) : '0.00';

$right_info = '<strong>Subscription Invoice</strong><br>';
$right_info .= 'Invoice No: ' . ($invoice_no !== '' ? $invoice_no : '-') . '<br>';
$right_info .= 'Invoice Date: ' . ($issued_on !== '' ? _d($issued_on) : '-') . '<br>';
$right_info .= 'Due Date: ' . ($due_on !== '' ? _d($due_on) : '-') . '<br>';
$right_info .= 'Status: ' . ($status !== '' ? ucfirst($status) : '-') . '<br>';
if ($method !== '') {
    $right_info .= 'Method: ' . $method . '<br>';
}
if ($payment_id !== '') {
    $right_info .= 'Payment ID: ' . $payment_id . '<br>';
}

pdf_multi_row($company_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->Ln(8);
$pdf->SetFont($font_name, 'B', 12);
$pdf->Cell(0, 0, 'Invoice Summary', 0, 1, 'L', 0, '', 0);
$pdf->Ln(5);
$pdf->SetFont($font_name, '', $font_size);

$tbl = '<table width="100%" cellspacing="0" cellpadding="6" border="0">
    <tbody>
        <tr>
            <td width="50%"><strong>Amount</strong></td>
            <td width="50%" align="right">' . $currency . ' ' . $amount . '</td>
        </tr>
        <tr>
            <td><strong>Tax</strong></td>
            <td align="right">' . $currency . ' ' . $tax . '</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td align="right">' . $currency . ' ' . $total . '</td>
        </tr>
    </tbody>
</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');
