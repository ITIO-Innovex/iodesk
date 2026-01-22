<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'company_id',
    'subscription_id',
    'start_date',
    'end_date',
    'status',
];
$sIndexColumn = 'id';
$sTable       = 'it_crm_services_user_subscriptions';
$where = [];

$join = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $_data = $aRow[$col];
        if ($col === 'status') {
            if ($aRow['status'] === 'active') {
                $_data = '<span class="label label-success">'._l('active').'</span>';
            } elseif ($aRow['status'] === 'expired') {
                $_data = '<span class="label label-default">Expired</span>';
            } else {
                $_data = '<span class="label label-danger">Cancelled</span>';
            }
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_user_subscription(this,' . e($aRow['id']) . '); return false" data-company-id="' . e($aRow['company_id']) . '" data-subscription-id="' . e($aRow['subscription_id']) . '" data-start-date="' . e($aRow['start_date']) . '" data-end-date="' . e($aRow['end_date']) . '" data-status="' . e($aRow['status']) . '"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>';
    $options .= '<a href="' . admin_url('services/user_subscriptions/delete/' . $aRow['id']) . '" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}
