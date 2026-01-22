<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'plan_name',
    'price',
    'currency',
    'billing_cycle',
    'duration',
    'no_of_staff',
    'tax',
    'status',
];
$sIndexColumn = 'id';
$sTable       = 'it_crm_services_subscriptions';
$where = [];

$join = [];
$additionalSelect = ['features'];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $_data = $aRow[$col];
        if ($col === 'price' || $col === 'tax') {
            $_data = number_format((float) $aRow[$col], 2);
        } elseif ($col === 'billing_cycle') {
            $_data = ucfirst(str_replace('_', ' ', $aRow['billing_cycle']));
        } elseif ($col === 'status') {
            $_data = $aRow['status'] === 'active'
                ? '<span class="label label-success">'._l('active').'</span>'
                : '<span class="label label-default">'._l('inactive').'</span>';
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_subscription(this,' . e($aRow['id']) . '); return false" data-plan-name="' . e($aRow['plan_name']) . '" data-price="' . e($aRow['price']) . '" data-currency="' . e($aRow['currency']) . '" data-billing-cycle="' . e($aRow['billing_cycle']) . '" data-duration="' . e($aRow['duration']) . '" data-no-of-staff="' . e($aRow['no_of_staff']) . '" data-tax="' . e($aRow['tax']) . '" data-status="' . e($aRow['status']) . '" data-features="' . e($aRow['features']) . '"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>';
    $options .= '<a href="' . admin_url('services/subscriptions/delete/' . $aRow['id']) . '" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}
