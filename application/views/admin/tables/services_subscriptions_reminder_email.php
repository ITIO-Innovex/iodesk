<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'company_id',
    'mail_to',
    'renewal_date',
    'sent_on',
];
$sIndexColumn = 'id';
$sTable       = 'it_crm_services_subscriptions_reminder_email';
$where = [];

$join = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $_data = $aRow[$col];
        if ($col === 'company_id') {
            $_data = get_staff_company_name($aRow['company_id']);
        }
        $row[] = $_data;
    }

    $output['aaData'][] = $row;
}
