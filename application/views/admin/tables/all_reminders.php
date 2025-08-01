<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'CASE ' . db_prefix() . 'reminders.rel_type
        WHEN \'customer\' THEN ' . db_prefix() . 'clients.company
        WHEN \'lead\' THEN ' . db_prefix() . 'leads.name
        WHEN \'estimate\' THEN ' . db_prefix() . 'estimates.id
        WHEN \'invoice\' THEN ' . db_prefix() . 'invoices.id
        WHEN \'proposal\' THEN ' . db_prefix() . 'proposals.subject
        WHEN \'expense\' THEN ' . db_prefix() . 'expenses.id
        WHEN \'credit_note\' THEN ' . db_prefix() . 'creditnotes.id
        WHEN \'ticket\' THEN ' . db_prefix() . 'tickets.subject
        WHEN \'task\' THEN ' . db_prefix() . 'tasks.name
        ELSE ' . db_prefix() . 'reminders.rel_type END as rel_type_name',
    db_prefix() . 'reminders.description',
    db_prefix() . 'reminders.date',
    'CONCAT(firstname, " ", lastname) as full_name',
    'isnotified',

    ];

$sIndexColumn = 'id';

$sTable = db_prefix() . 'reminders';
$where  = [];




if(!is_super()){
$where = ['AND (staff = ' . get_staff_user_id() . ' OR creator=' . get_staff_user_id() . ')'];
}else{
	
	if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
	$where = ['AND ('. db_prefix() . 'reminders.staff = ' . get_staff_user_id() . ' OR '. db_prefix() . 'reminders.company_id=' . $_SESSION['super_view_company_id'] . ')'];
	}

	
	}


$join = [
    'JOIN ' . db_prefix() . 'staff ON ' . db_prefix() . 'staff.staffid = ' . db_prefix() . 'reminders.staff',
    'LEFT JOIN ' . db_prefix() . 'clients ON ' . db_prefix() . 'clients.userid = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="customer"',
    'LEFT JOIN ' . db_prefix() . 'leads ON ' . db_prefix() . 'leads.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="lead"',
    'LEFT JOIN ' . db_prefix() . 'estimates ON ' . db_prefix() . 'estimates.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="estimate"',
    'LEFT JOIN ' . db_prefix() . 'invoices ON ' . db_prefix() . 'invoices.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="invoice"',
    'LEFT JOIN ' . db_prefix() . 'proposals ON ' . db_prefix() . 'proposals.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="proposal"',
    'LEFT JOIN ' . db_prefix() . 'expenses ON ' . db_prefix() . 'expenses.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="expense"',
    'LEFT JOIN ' . db_prefix() . 'creditnotes ON ' . db_prefix() . 'creditnotes.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="credit_note"',
    'LEFT JOIN ' . db_prefix() . 'tickets ON ' . db_prefix() . 'tickets.ticketid = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="ticket"',
    'LEFT JOIN ' . db_prefix() . 'tasks ON ' . db_prefix() . 'tasks.id = ' . db_prefix() . 'reminders.rel_id AND ' . db_prefix() . 'reminders.rel_type="task"',
    ];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'reminders.id',
    db_prefix() . 'reminders.creator',
    db_prefix() . 'reminders.rel_type',
    db_prefix() . 'reminders.rel_id',
    ]);

$output  = $result['output'];
$rResult = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }

        if ($aColumns[$i] == db_prefix() . 'reminders.date') {
            $_data = e(_dt($_data));
        } elseif ($i == 0) {
            // rel type name
            $rel_data   = get_relation_data($aRow['rel_type'], $aRow['rel_id']);
            $rel_values = get_relation_values($rel_data, $aRow['rel_type']);
            $_data      = '<a href="' . $rel_values['link'] . '">' . e($rel_values['name']) . '</a>';

            if ($aRow['creator'] == get_staff_user_id() || is_admin()) {
                $_data .= '<div class="row-options">';
                if ($aRow['isnotified'] == 0) {
                    $_data .= '<a href="#" onclick="edit_reminder(' . $aRow['id'] . ',this); return false;" class="edit-reminder">' . _l('edit') . '</a> | ';
                }
                $_data .= '<a href="' . admin_url('misc/delete_reminder/' . $aRow['rel_id'] . '/' . $aRow['id'] . '/' . $aRow['rel_type']) . '" class="text-danger delete-reminder">' . _l('delete') . '</a>';
                $_data .= '</div>';
            }
        } elseif ($i == 1) {
            $_data = process_text_content_for_display($aRow[db_prefix().'reminders.description']);
        } elseif ($aColumns[$i] == 'isnotified') {
            if ($_data == 1) {
                $_data = _l('reminder_is_notified_boolean_yes');
            } else {
                $_data = _l('reminder_is_notified_boolean_no');
            }
        } else {
            $_data = e($_data);
        }

        $row[] = $_data;
    }


    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
