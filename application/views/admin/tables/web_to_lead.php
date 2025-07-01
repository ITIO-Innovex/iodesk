<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = ['id', 'company_id', 'name', '(SELECT COUNT(id) FROM '.db_prefix().'leads WHERE '.db_prefix().'leads.from_form_id = '.db_prefix().'web_to_lead.id)', 'dateadded'];

$sIndexColumn = 'id';
$sTable       = db_prefix().'web_to_lead';
$where = [];

if(!is_super()){
$where = ['AND (company_id = ' . get_staff_company_id() . ')'];
}else{
  
if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
$where = ['AND (company_id = ' . $_SESSION['super_view_company_id'] . ')'];
 }

}


$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], $where, ['form_key', 'id']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0 ; $i < count($aColumns) ; $i++) {
        $_data = $aRow[$aColumns[$i]];
		if ($aColumns[$i] == 'company_id') {
		$_data = get_staff_company_name($aRow['company_id']);
        }elseif ($aColumns[$i] == 'name') {
            $_data = '<a href="' . admin_url('leads/form/' . $aRow['id']) . '">' . e($_data) . '</a>';
            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . site_url('forms/wtl/' . $aRow['form_key']) . '" target="_blank">' . _l('view') . '</a>';
            $_data .= ' | <a href="' . admin_url('leads/form/' . $aRow['id']) . '">' . _l('edit') . '</a>';
            $_data .= ' | <a href="' . admin_url('leads/delete_form/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
            $_data .= '</div>';
			
        } elseif ($aColumns[$i] == 'dateadded') {
            $_data = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . e(_dt($_data)) . '">' . e(time_ago($_data)) . '</span>';
        }

        $row[] = $_data;
    }
    $row['DT_RowClass'] = 'has-row-options';

    $output['aaData'][] = $row;
}
