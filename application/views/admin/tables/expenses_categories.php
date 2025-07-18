<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'id',
    'name',
    'description',
    ];
$sIndexColumn = 'id';
$sTable       = db_prefix() . 'expenses_categories';


$where = [];

if(!is_super()){
array_push($where, 'AND ' . db_prefix() . 'expenses_categories.company_id=' . $this->ci->db->escape_str(get_staff_company_id()));
}else{

if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
array_push($where, 'AND ' . db_prefix() . 'expenses_categories.company_id=' . $this->ci->db->escape_str($_SESSION['super_view_company_id']));
}

}
$result       = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, []);
$output       = $result['output'];
$rResult      = $result['rResult'];
foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'name' || $aColumns[$i] == 'id') {
            $_data = '<a href="#" onclick="edit_category(this,' . $aRow['id'] . '); return false;" data-name="' . e($aRow['name']) . '" data-description="' . e(clear_textarea_breaks($aRow['description'])) . '">' . e($_data) . '</a>';
        } else if($aColumns[$i] == 'description') {
            $_data = process_text_content_for_display($_data);
        }
        $row[] = $_data;
    }

    $options = '<div class="tw-flex tw-items-center tw-space-x-3">';
    $options .= '<a href="#" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700" onclick="edit_category(this,' . $aRow['id'] . '); return false;" data-name="' . e($aRow['name']) . '" data-description="' . e(clear_textarea_breaks($aRow['description'])) . '">
        <i class="fa-regular fa-pen-to-square fa-lg"></i>
    </a>';

    $options .= '<a href="' . admin_url('expenses/delete_category/' . $aRow['id']) . '"
    class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
        <i class="fa-regular fa-trash-can fa-lg"></i>
    </a>';
    $options .= '</div>';

    $row[]              = $options;
    $output['aaData'][] = $row;
}