<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'invoice_no',
    'payment_id',
    'company_id',
    'subscription_id',
    'amount',
    'currency',
    'tax',
    'total_amount',
    'invoice_date',
    'due_date',
    'payment_status',
];
$sIndexColumn = 'id';
$sTable       = 'it_crm_services_subscriptions_invoices';
$where = [];

$join = [];
$additionalSelect = ['payment_method', 'payment_json'];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $_data = $aRow[$col];
		
		if ($col === 'company_id') {
		$_data = get_staff_company_name($aRow['company_id']);
		}
		
		if ($col === 'subscription_id') {
		$_data = get_subscriptions_title($aRow['subscription_id']);
		}
        if (in_array($col, ['amount', 'tax', 'total_amount'], true)) {
            $_data = number_format((float) $aRow[$col], 2);
        } elseif ($col === 'payment_id') {
            $paymentJson = $aRow['payment_json'] ?? '';
            $paymentId = $aRow['payment_id'] ?? '';
            if ($paymentId !== '') {
                $_data = '<a href="#" onclick="view_payment_json(this); return false" data-payment-json="' . e($paymentJson) . '">' . e($paymentId) . '</a>';
            } else {
                $_data = '';
            }
        } elseif ($col === 'payment_status') {
            if ($aRow['payment_status'] === 'paid') {
                $_data = '<span class="label label-success">Paid</span>';
            } elseif ($aRow['payment_status'] === 'failed') {
                $_data = '<span class="label label-danger">Failed</span>';
            } else {
                $_data = '<span class="label label-default">Unpaid</span>';
            }
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="' . admin_url('services/subscriptions_invoice_pdf/' . $aRow['id']) . '" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"><i class="fa-solid fa-download fa-lg"></i></a>';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="view_subscription_invoice(this); return false" data-id="' . e($aRow['id']) . '" data-invoice-no="' . e($aRow['invoice_no']) . '" data-company-id="' . e($aRow['company_id']) . '" data-subscription-id="' . e($aRow['subscription_id']) . '" data-amount="' . e($aRow['amount']) . '" data-currency="' . e($aRow['currency']) . '" data-tax="' . e($aRow['tax']) . '" data-total-amount="' . e($aRow['total_amount']) . '" data-invoice-date="' . e($aRow['invoice_date']) . '" data-due-date="' . e($aRow['due_date']) . '" data-payment-status="' . e($aRow['payment_status']) . '" data-payment-method="' . e($aRow['payment_method'] ?? '') . '"><i class="fa-regular fa-eye fa-lg"></i></a>';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_subscription_invoice(this,' . e($aRow['id']) . '); return false" data-invoice-no="' . e($aRow['invoice_no']) . '" data-company-id="' . e($aRow['company_id']) . '" data-subscription-id="' . e($aRow['subscription_id']) . '" data-amount="' . e($aRow['amount']) . '" data-currency="' . e($aRow['currency']) . '" data-tax="' . e($aRow['tax']) . '" data-total-amount="' . e($aRow['total_amount']) . '" data-invoice-date="' . e($aRow['invoice_date']) . '" data-due-date="' . e($aRow['due_date']) . '" data-payment-status="' . e($aRow['payment_status']) . '" data-payment-method="' . e($aRow['payment_method'] ?? '') . '"><i class="fa-regular fa-pen-to-square fa-lg tw-hidden"></i></a>';
    $options .= '<a href="' . admin_url('services/subscriptions_invoices/delete/' . $aRow['id']) . '" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete"><i class="fa-regular fa-trash-can fa-lg tw-hidden"></i></a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}
