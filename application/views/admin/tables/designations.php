<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'department_id',
    'title',
    'is_active',
    'company_id',
];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'designations';
$where = [];
if (!is_super()) {
    array_push($where, 'AND ' . db_prefix() . 'designations.company_id=' . $this->ci->db->escape_str(get_staff_company_id()));
} else {
    if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
        array_push($where, 'AND ' . db_prefix() . 'designations.company_id=' . $this->ci->db->escape_str($_SESSION['super_view_company_id']));
    }
}

$join = [];
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, []);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    foreach ($aColumns as $col) {
        $_data = $aRow[$col];
        if ($col === 'department_id') {
            // Get department name
            $dep = $this->ci->db->select('name')->where('departmentid', $aRow['department_id'])->get(db_prefix().'departments')->row();
            $_data = $dep ? e($dep->name) : '-';
        } elseif ($col === 'is_active') {
            $_data = $aRow['is_active'] == 1 ? '<span class="label label-success">'._l('active').'</span>' : '<span class="label label-default">'._l('inactive').'</span>';
        } elseif ($col === 'company_id') {
            $_data = get_staff_company_name($aRow['company_id']);
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_designation(this,' . e($aRow['id']) . '); return false" data-department-id="' . e($aRow['department_id']) . '" data-title="' . e($aRow['title']) . '" data-active="' . e($aRow['is_active']) . '"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>';
    $options .= '<a href="' . admin_url('designation/delete/' . $aRow['id']) . '" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete"><i class="fa-regular fa-trash-can fa-lg"></i></a>';
    $options .= '</div>';

    $row[] = $options;

    $output['aaData'][] = $row;
}
