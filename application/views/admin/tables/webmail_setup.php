<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * DataTables server-side for Webmail Setup
 * Maps to $this->app->get_table_data('webmail_setup')
 */

// Base columns used for searching/sorting
$aColumns = [
    'mailer_name',
    'mailer_email',
    'mailer_username',
    'mailer_smtp_host',
    'mailer_imap_host',
    'mailer_status',
    'priority',
    'date_created',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'webmail_setup';
$join         = [];
$where        = [];

// Company scope (same logic as in Webmail_setup_model::get)
if (!is_super()) {
    $where[] = 'AND company_id = ' . $this->ci->db->escape_str(get_staff_company_id());
} else {
    if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
        $where[] = 'AND company_id = ' . $this->ci->db->escape_str($_SESSION['super_view_company_id']);
    }
}

// If not admin, by default list only records for current staff or shared (staffid = 0)
if (!is_admin()) {
    $sid = (int) get_staff_user_id();
    $where[] = 'AND (staffid = ' . $sid . ' OR staffid = 0)';
}

// Additional columns to select but not used directly for searching/sorting
$additionalSelect = [
    'id',
    'staffid',
    'departmentid',
    'mailer_password',
    'mailer_smtp_port',
    'mailer_imap_port',
    'creator',
    'creator_name',
    'priority',
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    // Column: Name + row options (Edit/Delete)
    $name = e($aRow['mailer_name']);
    $rowOptions = '';
    if ((int)$aRow['creator'] === (int)get_staff_user_id() || is_admin()) {
        $editUrl   = "javascript:void(0);";
        $deleteUrl = admin_url('webmail_setup/delete/' . $aRow['id']);

        $rowOptions .= '<div class="row-options">';
        $rowOptions .= '<a href="#" onclick="edit_mailer_entry(' . (int)$aRow['id'] . '); return false;" class="text-muted">' . _l('edit') . '</a>';
        $rowOptions .= ' | <a href="' . $deleteUrl . '" class="text-danger _delete">' . _l('delete') . '</a>';
        $rowOptions .= '</div>';
    }
    $row[] = $name . $rowOptions;

    // Column: Email + department info
    $emailCol = e($aRow['mailer_email']);
    if (!empty($aRow['departmentid'])) {
        $emailCol .= '<br />' . _l('department') . ' - ' . e($aRow['departmentid']);
    }
    $row[] = $emailCol;

    // Column: Username + masked password
    $maskedPassword = '';
    if (!empty($aRow['mailer_password'])) {
        $maskedPassword = substr_replace(e($aRow['mailer_password']), '*****', 2, 7);
    }
    $row[] = e($aRow['mailer_username']) . '<br />' . $maskedPassword;

    // Column: SMTP host/port
    $row[] = e($aRow['mailer_smtp_host']) . '<br />' . e($aRow['mailer_smtp_port']);

    // Column: IMAP host/port
    $row[] = e($aRow['mailer_imap_host']) . '<br />' . e($aRow['mailer_imap_port']);

    // Column: Status (toggle link)
    if ((int)$aRow['mailer_status'] === 1) {
        $statusUrl = admin_url('webmail_setup/statusoff/' . $aRow['id']);
        $statusHtml = '<a href="' . $statusUrl . '" class="text-danger _delete" title="' . _l('settings_yes') . '">'
            . '<i class="fa-solid fa-toggle-on fa-xl text-success" style="margin-top:10px;"></i>'
            . '</a>';
    } else {
        $statusUrl = admin_url('webmail_setup/statuson/' . $aRow['id']);
        $statusHtml = '<a href="' . $statusUrl . '" class="text-danger _delete" title="' . _l('settings_no') . '">'
            . '<i class="fa-solid fa-toggle-off fa-xl" style="margin-top:10px;"></i>'
            . '</a>';
    }
    $row[] = $statusHtml;

    // Column: Priority (toggle link - increment/decrement)
    $currentPriority = isset($aRow['priority']) ? (int)$aRow['priority'] : 0;
    $priorityUpUrl = admin_url('webmail_setup/priorityup/' . $aRow['id']);
    $priorityDownUrl = admin_url('webmail_setup/prioritydown/' . $aRow['id']);
    $priorityHtml = '<div style="text-align: center;">';
    $priorityHtml .= '<a href="' . $priorityUpUrl . '" class="text-success" title="Increase Priority" style="display: inline-block; margin-right: 5px;">';
    $priorityHtml .= '<i class="fa-solid fa-arrow-up fa-lg"></i>';
    $priorityHtml .= '</a>';
    $priorityHtml .= '<span style="display: inline-block; min-width: 30px; font-weight: bold;">' . $currentPriority . '</span>';
    $priorityHtml .= '<a href="' . $priorityDownUrl . '" class="text-danger" title="Decrease Priority" style="display: inline-block; margin-left: 5px;">';
    $priorityHtml .= '<i class="fa-solid fa-arrow-down fa-lg"></i>';
    $priorityHtml .= '</a>';
    $priorityHtml .= '</div>';
    $row[] = $priorityHtml;

    // Column: Created by + time
    $createdCol = e($aRow['creator_name']) . ' - ' . e(time_ago($aRow['date_created'])) . '<br />' . e(_dt($aRow['date_created']));
    $row[] = $createdCol;

    // Add row class based on whether it's for department or staff (similar to view)
    $ctype = '';
    if (!empty($aRow['departmentid'])) {
        $ctype = 'tw-bg-warning-100';
    } elseif (!empty($aRow['staffid'])) {
        $ctype = 'tw-bg-success-100';
    }
    if ($ctype !== '') {
        $row['DT_RowClass'] = 'has-row-options ' . $ctype;
    } else {
        $row['DT_RowClass'] = 'has-row-options';
    }

    $output['aaData'][] = $row;
}

