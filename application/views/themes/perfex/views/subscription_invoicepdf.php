<?php

defined('BASEPATH') or exit('No direct script access allowed');

$invoice = is_array($invoice) ? $invoice : [];
$dimensions = $pdf->getPageDimensions();

$company_name = get_option('companyname');
$company_logo = get_option('company_logo');
$logo_img = '';
if (!empty($company_logo)) {
    $logo_img = '<img src="' . base_url('uploads/company/' . $company_logo) . '" style="max-height:60px;" alt="' . e($company_name) . '">';
}

$company_info = '<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td width="40%" style="vertical-align:top;">' . $logo_img . '</td>
        <td width="60%" style="text-align:right; vertical-align:top;">
            <div style="font-size:16px; font-weight:700; color:#111827;">' . e($company_name) . '</div>
            <div style="color:#6b7280; font-size:10px;">' . format_organization_info() . '</div>
        </td>
    </tr>
</table>';

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

$right_info = '<div style="text-align:right;">
    <div style="font-size:18px; font-weight:700; color:#111827;">Subscription Invoice</div>
    <div style="margin-top:6px; color:#4b5563; font-size:10px;">
        Invoice No: ' . ($invoice_no !== '' ? $invoice_no : '-') . '<br>
        Invoice Date: ' . ($issued_on !== '' ? _d($issued_on) : '-') . '<br>
        Due Date: ' . ($due_on !== '' ? _d($due_on) : '-') . '<br>
        Status: ' . ($status !== '' ? ucfirst($status) : '-') . '<br>
        ' . ($method !== '' ? ('Method: ' . $method . '<br>') : '') . '
        ' . ($payment_id !== '' ? ('Payment ID: ' . $payment_id . '<br>') : '') . '
    </div>
</div>';

pdf_multi_row($company_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->Ln(10);
$pdf->SetFont($font_name, 'B', 12);
$pdf->Cell(0, 0, 'Invoice Summary', 0, 1, 'L', 0, '', 0);
$pdf->Ln(6);
$pdf->SetFont($font_name, '', $font_size);

$tbl = '<table width="100%" cellspacing="0" cellpadding="8" border="0">
    <tbody>
        <tr style="background-color:#f9fafb;">
            <td width="50%"><strong>Amount</strong></td>
            <td width="50%" align="right">' . $currency . ' ' . $amount . '</td>
        </tr>
        <tr>
            <td><strong>Tax</strong></td>
            <td align="right">' . $tax . ' % </td>
        </tr>
        <tr style="background-color:#f9fafb;">
            <td><strong>Total</strong></td>
            <td align="right"><strong>' . $currency . ' ' . $total . '</strong></td>
        </tr>
    </tbody>
</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');
