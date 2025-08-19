<?php

defined('BASEPATH') or exit('No direct script access allowed');

$this->ci->load->model('project_model');
$statuses = $this->ci->project_model->get_proj_status();
//print_r($statuses);
//echo "============>>>";exit;
$rules = [
    App_table_filter::new('project_title', 'TextRule')->label(_l('Project Title')),
    /*App_table_filter::new('department', 'SelectRule')->label(_l('ticket_dt_department'))->options(function ($ci) {
        return collect($ci->departments_model->get())->map(fn ($dep) => [
            'value' => $dep['departmentid'],
            'label' => $dep['name']
        ])->all();
    })->isVisible(fn () => is_admin()),*/
    /*App_table_filter::new('status', 'MultiSelectRule')->label(_l('ticket_dt_status'))->options(function ($ci) use ($statuses) {
        return collect($statuses)->map(fn ($status) => [
            'value' => $status['ticketstatusid'],
            'label' => ticket_status_translate($status['ticketstatusid'])
        ])->all();
    }),
    App_table_filter::new('priority', 'SelectRule')->label(_l('ticket_dt_priority'))->options(function ($ci) {
        return collect($ci->tickets_model->get_priority())->map(fn ($priority) => [
            'value' => $priority['priorityid'],
            'label' => ticket_priority_translate($priority['priorityid'])
        ])->all();
    }),
    App_table_filter::new('service', 'SelectRule')->label(_l('ticket_dt_service'))->options(function ($ci) use ($statuses) {
        return collect($ci->tickets_model->get_service())->map(fn ($service) => [
            'value' => $service['serviceid'],
            'label' => $service['name']
        ])->all();
    }),
    App_table_filter::new('merged', 'BooleanRule')->label(_l('merged'))->raw(function ($value) {
        return $value == "1" ? 'merged_ticket_id IS NOT NULL' : 'merged_ticket_id IS NULL';
    }),
    App_table_filter::new('my_tickets', 'BooleanRule')->label(_l('my_tickets_assigned'))->raw(function ($value) {
        return $value == "1" ? 'assigned = ' . get_staff_user_id() : 'assigned != ' . get_staff_user_id();
    }),*/
];

$rules[] = App_table_filter::new('assigned', 'SelectRule')->label(_l('ticket_assigned'))
    ->withEmptyOperators()
    ->emptyOperatorValue(0)
    ->isVisible(fn () => is_admin())
    ->options(function ($ci) {
        $staff = $ci->staff_model->get('', ['active' => 1]);

        return collect($staff)->map(function ($staff) {
            return [
                'value' => $staff['staffid'],
                'label' => $staff['firstname'] . ' ' . $staff['lastname']
            ];
        })->all();
    });
//echo "============>>>".rand();exit;
log_message('error', 'STAGE 1');
return App_table::find('project')
    ->outputUsing(function ($params) use ($statuses) {
        extract($params);

        $aColumns = [
            '1', // bulk actions
            'id',
            'project_title',
            'owner',
            'start_date',
			'deadline',
            'date_finished',
        ];

        $contactColumn = 6;
        $tagsColumns   = 3;

        $additionalSelect = [
        ];

        $join = [];

        $custom_fields = get_table_custom_fields('project_master');
		
		
        foreach ($custom_fields as $key => $field) {
            $selectAs = (is_cf_date($field) ? 'date_picker_cvalue_' . $key : 'cvalue_' . $key);
            array_push($customFieldsColumns, $selectAs);
            array_push($aColumns, 'ctable_' . $key . '.value as ' . $selectAs);
            array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $key . ' ON ' . db_prefix() . 'tickets.ticketid = ctable_' . $key . '.relid AND ctable_' . $key . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $key . '.fieldid=' . $field['id']);
        }

        $where  = [];
		
if(!is_super()){
array_push($where, 'AND ' . db_prefix() . 'tickets.company_id = ' . $this->ci->db->escape_str(get_staff_company_id()));
}else{
  
if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
array_push($where, 'AND ' . db_prefix() . 'tickets.company_id = ' . $this->ci->db->escape_str($_SESSION['super_view_company_id']));
 }

}


        if ($filtersWhere = $this->getWhereFromRules()) {
            $where[] = $filtersWhere;
        }

        if (isset($userid) && $userid != '') {
            array_push($where, 'AND ' . db_prefix() . 'tickets.userid = ' . $this->ci->db->escape_str($userid));
        } 
        

        if ($project_id = $this->ci->input->post('project_id')) {
            array_push($where, 'AND project_id = ' . $this->ci->db->escape_str($project_id));
        }

   

        $sIndexColumn = 'id';
        $sTable       = db_prefix() . 'project_master';

        // Fix for big queries. Some hosting have max_join_limit
        if (count($custom_fields) > 4) {
            @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
        }

        $result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, $additionalSelect);

        $output  = $result['output'];
        $rResult = $result['rResult'];

        // Remove serial number column
        foreach ($rResult as $aRow) {
            $row = [];
            for ($i = 0; $i < count($aColumns); $i++) {
                if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
                    $_data = $aRow[strafter($aColumns[$i], 'as ')];
                } else {
                    $_data = $aRow[$aColumns[$i]];
                }

                if ($aColumns[$i] == '1') {
                    $_data = '<div class="checkbox"><input type="checkbox" value="' . $aRow['ticketid'] . '" data-name="' . $aRow['subject'] . '" data-status="' . $aRow['status'] . '"><label></label></div>';
                } elseif ($aColumns[$i] == 'lastreply') {
                    if ($aRow[$aColumns[$i]] == null) {
                        $_data = _l('ticket_no_reply_yet');
                    } else {
                        $_data = e(_dt($aRow[$aColumns[$i]]));
                    }
                } elseif ($aColumns[$i] == 'subject' || $aColumns[$i] == 'ticketid') {
                    // Ticket is assigned
                    if ($aRow['assigned'] != 0) {
                        if ($aColumns[$i] != 'ticketid') {
                            $_data .= '<a href="' . admin_url('profile/' . $aRow['assigned']) . '" data-toggle="tooltip" title="' . e(get_staff_full_name($aRow['assigned'])) . '" class="pull-left mright5">' . staff_profile_image($aRow['assigned'], [
                                'staff-profile-image-xs',
                            ]) . '</a>';
                        } else {
                            $_data = e($_data);
                        }
                    } else {
                        $_data = e($_data);
                    }

                    $url   = admin_url('tickets/ticket/' . $aRow['ticketid']);
                    $_data = '<a href="' . $url . '" class="valign">' . $_data . '</a>';
                    if ($aColumns[$i] == 'subject') {
                        $_data .= '<div class="row-options">';
                        $_data .= '<a href="' . $url . '">' . _l('view') . '</a>';
                        $_data .= ' | <a href="' . $url . '?tab=settings">' . _l('edit') . '</a>';
                        $_data .= ' | <a href="' . get_ticket_public_url($aRow) . '" target="_blank">' . _l('view_public_form') . '</a>';
                        if (can_staff_delete_ticket()) {
                            $_data .= ' | <a href="' . admin_url('tickets/delete/' . $aRow['ticketid']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                        }
                        $_data .= '</div>';
                    }
                } elseif ($i == $tagsColumns) {
                    $_data = render_tags($_data);
                } elseif ($i == $contactColumn) {
                    if ($aRow['userid'] != 0) {
                        $_data = '<a href="' . admin_url('clients/client/' . $aRow['userid'] . '?group=contacts') . '">' . e($aRow['contact_full_name']);
                        if (!empty($aRow['company'])) {
                            $_data .= ' (' . e($aRow['company']) . ')';
                        }
                        $_data .= '</a>';
                    } else {
                        $_data = e($aRow['ticket_opened_by_name']);
                    }
                } elseif ($aColumns[$i] == 'status') {
                    $_data = '<span class="label ticket-status-' . $aRow['status'] . '" style="border:1px solid ' . adjust_hex_brightness($aRow['statuscolor'], 0.4) . '; color:' . $aRow['statuscolor'] . ';background: ' . adjust_hex_brightness($aRow['statuscolor'], 0.04) . ';">' . e(ticket_status_translate($aRow['status'])) . '</span>';
                } elseif ($aColumns[$i] == db_prefix() . 'tickets.date') {
                    $_data = e(_dt($_data));
                } elseif (strpos($aColumns[$i],'service_name') !== false) {
                    $_data = e($_data);
                } elseif ($aColumns[$i] == 'priority') {
                    $_data = e(ticket_priority_translate($aRow['priority']));
                } else {
                    if (strpos($aColumns[$i], 'date_picker_') !== false) {
                        $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
                    }
                }

                // Remove serial number before subject column
                // (No serial number insertion)
                $row[] = $_data;

                if ($aRow['adminread'] == 0) {
                    $row['DT_RowClass'] = 'text-danger';
                }
            }

            if (isset($row['DT_RowClass'])) {
                $row['DT_RowClass'] .= ' has-row-options';
            } else {
                $row['DT_RowClass'] = 'has-row-options';
            }

            $row = hooks()->apply_filters('admin_project_table_row_data', $row, $aRow);
            $output['aaData'][] = $row;
        }

        return $output;
    })->setRules($rules);
