<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Forms extends ClientsController
{
    public function index()
    {
        show_404();
    }

    /**
     * Estimate request form
     * User no need to see anything like estimate request in the url, this is the reason the method is named quote
     * @param  string $key Estimate request form key identifier
     * @return mixed
     */
    public function quote($key = '')
    {
        if (!$key) {
            show_404();
        }

        $this->load->model('estimate_request_model');
        $form = $this->estimate_request_model->get_form([
            'form_key' => $key,
        ]);

        if (!$form) {
            show_404();
        }

        // Change the locale so the validation loader function can load
        // the proper localization file
        $GLOBALS['locale'] = get_locale_key($form->language);

        $data['form_fields'] = json_decode($form->form_data);
        if (!$data['form_fields']) {
            $data['form_fields'] = [];
        }

        if ($this->input->post('key')) {

            if ($this->input->post('key') == $key) {
                $post_data  = $this->input->post();
                $required   = [];
                $submission = [];

                foreach ($data['form_fields'] as $index => $field) {
                    if (isset($field->name)) {
                        if ($field->name == 'file-input') {
                            $submission[] = [
                            'label' => $field->label,
                            'name'  => $field->name,
                            'value' => null,
                            ];

                            continue;
                        }

                        if (!isset($post_data[$field->name])) {
                            $submission[] = [
                            'label' => property_exists($field, 'label') ? $field->label : $field->name,
                            'name'  => $field->name,
                            'value' => '',
                            ];

                            continue;
                        }

                        if ($field->type == 'radio-group') {
                            $index        = array_search($post_data[$field->name], array_column($field->values, 'value'));
                            $submission[] = [
                                'label' => property_exists($field, 'label') ? $field->label : $field->name,
                                'name'  => $field->name,
                                'value' => $field->values[$index]->label,
                            ];

                            continue;
                        }

                        if (in_array($field->type, ['select', 'checkbox-group'])) {
                            if (is_array($post_data[$field->name])) {
                                $value = '';
                                foreach ($post_data[$field->name] as $selected) {
                                    $index = array_search($selected, array_column($field->values, 'value'));
                                    $value .= $field->values[$index]->label . '<br>';
                                }
                            } else {
                                $index = array_search($post_data[$field->name], array_column($field->values, 'value'));
                                $value = $field->values[$index]->label;
                            }

                            $submission[] = [
                                'label' => property_exists($field, 'label') ? $field->label : $field->name,
                                'name'  => $field->name,
                                'value' => $value,
                            ];

                            continue;
                        }

                        if ($field->type == 'date') {
                            $submission[] = [
                                'label' => property_exists($field, 'label') ? $field->label : $field->name,
                                'name'  => $field->name,
                                'value' => $post_data[$field->name],
                            ];

                            continue;
                        }

                        if ($field->type == 'textarea') {
                            $submission[] = [
                                'label' => property_exists($field, 'label') ? $field->label : $field->name,
                                'name'  => $field->name,
                                'value' => nl2br($post_data[$field->name]),
                            ];

                            continue;
                        }

                        $submission[] = [
                            'label' => property_exists($field, 'label') ? $field->label : $field->name,
                            'name'  => $field->name,
                            'value' => $post_data[$field->name],
                        ];
                    }

                    if (isset($field->required)) {
                        $required[] = $field->name;
                    }
                }

                if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_estimate_request_form') == 1) {
                    $required[] = 'accept_terms_and_conditions';
                }

                foreach ($required as $field) {
                    if ($field == 'file-input') {
                        continue;
                    }
                    if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
                        $this->output->set_status_header(422);
                        die;
                    }
                }


                if (show_recaptcha() && $form->recaptcha == 1) {
                    if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
                        echo json_encode(['success' => false,
                            'message'               => _l('recaptcha_error'),
                        ]);
                        die;
                    }
                }

                if (isset($post_data['g-recaptcha-response'])) {
                    unset($post_data['g-recaptcha-response']);
                }

                unset($post_data['key']);
                $success      = false;
                $insert_to_db = true;
            }

            if ($insert_to_db == true) {
                $regular_fields['email']        = $post_data['email'];
                $regular_fields['status']       = $form->status;
                $regular_fields['assigned']     = $form->responsible;
                $regular_fields['date_added']   = date('Y-m-d H:i:s');
                $regular_fields['from_form_id'] = $form->id;
                $regular_fields['submission']   = json_encode($submission);

                $this->db->insert(db_prefix() . 'estimate_requests', $regular_fields);
                $estimate_request_id = $this->db->insert_id();

                hooks()->do_action('estimate_requests_created', [
                    'estimate_request_id'   => $estimate_request_id,
                    'estimate_request_form' => true,
                ]);

                $success = false;
                if ($estimate_request_id) {
                    $success = true;

                    $this->estimate_request_model->assigned_member_notification($estimate_request_id, $form->responsible, true);

                    handle_estimate_request_attachments($estimate_request_id, 'file-input', $form->name);

                    if ($form->notify_request_submitted != 0) {
                        $staff = [];
                        if ($form->notify_type != 'assigned') {
                            $ids = @unserialize($form->notify_ids);

                            if (is_array($ids) && count($ids) > 0) {
                                $this->db->where('active', 1)
                                ->where_in($form->notify_type == 'specific_staff' ? 'staffid' : 'role', $ids);

                                $staff = $this->db->get(db_prefix() . 'staff')->result_array();
                            }
                        } elseif ($form->responsible) {
                            $staff = [
                                [
                                    'staffid' => $form->responsible,
                                    'email'   => get_staff($form->responsible)->email,
                                ],
                            ];
                        }

                        $notifiedUsers = [];

                        foreach ($staff as $member) {
                            if (add_notification([
                                    'description' => 'new_estimate_request_submitted_from_form',
                                    'touserid' => $member['staffid'],
                                    'fromcompany' => 1,
                                    'fromuserid' => 0,
                                    'additional_data' => serialize([
                                        $form->name,
                                    ]),
                                    'link' => 'estimate_request/view/' . $estimate_request_id,
                                ])) {
                                array_push($notifiedUsers, $member['staffid']);
                            }

                            send_mail_template('estimate_request_form_submitted', $estimate_request_id, $member['email']);
                        }

                        pusher_trigger_notification($notifiedUsers);
                    }

                    send_mail_template('estimate_request_received_to_user', $estimate_request_id, $regular_fields['email']);
                }
            }
            // end insert_to_db
            if ($success == true) {
                if (!isset($estimate_request_id)) {
                    $estimate_request_id = 0;
                }

                hooks()->do_action('estimate_request_form_submitted', [
                    'estimate_request_id' => $estimate_request_id,
                    'form_id'             => $form->id,
                ]);
            }

            $response = ['success' => $success];

            if ($form->submit_action === '0') {
                $response['message'] = $form->success_submit_msg;
            }

            echo json_encode($response);
            die;
        }
        $data['form'] = $form;
        $this->load->view('forms/estimate_request', $data);
    }

    /**
     * Web to lead form
     * User no need to see anything like LEAD in the url, this is the reason the method is named wtl
     * @param  string $key web to lead form key identifier
     * @return mixed
     */
    public function wtl($key = '')
    {
        if (!$key) {
            show_404();
        }
		

        $this->load->model('leads_model');
		
		


// New function created by vikash for Fetch IP with country, reason,city,postal
$ipdetails=$this->leads_model->getLocationFromIP(); 
//print_r($ipdetails);
$ipx=$ipdetails['ip'];
$ccode=$ipdetails['country'] ?? 'IN';
$region=$ipdetails['region'] ?? '';
$city=$ipdetails['city'] ?? '';
$postal=$ipdetails['postal'] ?? '';
$ip_address=$city." ".$region." ".$postal;

$this->db->where('iso2', $ccode); // Fetch country_id,calling_code,calling_name from table countries
$country_details = $this->db->get(db_prefix() . 'countries')->row();

if ($country_details) {
$ip_country_id = $country_details->country_id;
//$ip_country_name = $country_details->short_name;
$ip_calling_code = $country_details->calling_code;
}






        $form = $this->leads_model->get_form([
            'form_key' => $key,
        ]);

        if (!$form) {
            show_404();
        }

	
							


        // Change the locale so the validation loader function can load
        // the proper localization file
        $GLOBALS['locale'] = get_locale_key($form->language);

        $data['form_fields'] = $form->form_data ? json_decode($form->form_data) : [];

        if ($this->input->post('key')) {
            if ($this->input->post('key') == $key) {
                $post_data = $this->input->post();
				//echo "===========>>>>";exit;
                $required  = [];

                foreach ($data['form_fields'] as $field) {
                    if (isset($field->required)) {
                        $required[] = $field->name;
                    }
                }
                if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_lead_form') == 1) {
                    $required[] = 'accept_terms_and_conditions';
                }

                foreach ($required as $field) {
                    if ($field == 'file-input') {
                        continue;
                    }
                    if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
                        $this->output->set_status_header(422);
                        die;
                    }
                }

                if (show_recaptcha() && $form->recaptcha == 1) {
                    if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
                        echo json_encode([
                            'success' => false,
                            'message' => _l('recaptcha_error'),
                        ]);
                        die;
                    }
                }

                if (isset($post_data['g-recaptcha-response'])) {
                    unset($post_data['g-recaptcha-response']);
                }
                $form_key=$post_data['key'];
                unset($post_data['key']);
				
				$company_id=$this->leads_model->getLeadFormComapnyID($form_key);
				//////////////////////////////////////
$status=2;	
$form->responsible="";	
// Auto assign to staff when activate from admin



if(get_company_fields($company_id,'automatically_assign_to_staff')==1){
$status=3;
$form->responsible = get_company_fields($company_id,'lead_auto_assign_to_staff');
}elseif(get_option('automatically_assign_to_staff')==1){
$status=3;
$values=json_decode(get_option('lead_auto_assign_to_staff'));

	if(count($values) > 0){
	$randomKey = array_rand($values);
	$assignValue = $values[$randomKey];
	$form->responsible = $assignValue;
	}
}
				
				/////////////////////////////////////

                $regular_fields = [];
                $custom_fields  = [];
                foreach ($post_data as $name => $val) {
                    if (strpos($name, 'form-cf-') !== false) {
                        array_push($custom_fields, [
                            'name'  => $name,
                            'value' => $val,
                        ]);
                    } else {
                        if ($this->db->field_exists($name, db_prefix() . 'leads')) {
                            if ($name == 'country') {
                                if (!is_numeric($val)) {
                                    if ($val == '') {
                                        $val = 0;
                                    } else {
                                        $this->db->where('iso2', $val);
                                        $this->db->or_where('short_name', $val);
                                        $this->db->or_where('long_name', $val);
                                        $country = $this->db->get(db_prefix() . 'countries')->row();
                                        if ($country) {
                                            $val = $country->country_id;
                                        } else {
                                            $val = 0;
                                        }
                                    }
                                }
                            } elseif ($name == 'address') {
                                $val = trim($val);
                                $val = nl2br($val);
                            }

                            $regular_fields[$name] = $val;
                        }
                    }
                }
                $success      = false;
                $insert_to_db = true;

                if ($form->allow_duplicate == 0) {
                    $where = [];
                    if (!empty($form->track_duplicate_field) && isset($regular_fields[$form->track_duplicate_field])) {
                        $where[$form->track_duplicate_field] = $regular_fields[$form->track_duplicate_field];
                    }
                    if (!empty($form->track_duplicate_field_and) && isset($regular_fields[$form->track_duplicate_field_and])) {
                        $where[$form->track_duplicate_field_and] = $regular_fields[$form->track_duplicate_field_and];
                    }

                    if (count($where) > 0) {
                        $total = total_rows(db_prefix() . 'leads', $where);

                        $duplicateLead = false;
                        /**
                         * Check if the lead is only 1 time duplicate
                         * Because we wont be able to know how user is tracking duplicate and to send the email template for
                         * the request
                         */
                        if ($total == 1) {
                            $this->db->where($where);
                            $duplicateLead = $this->db->get(db_prefix() . 'leads')->row();
                        }

                        if ($total > 0) {
                            // Success set to true for the response.
                            $success      = true;
                            $insert_to_db = false;
                            if ($form->create_task_on_duplicate == 1) {
                                $task_name_from_form_name = false;
                                $task_name                = '';
                                if (isset($regular_fields['name'])) {
                                    $task_name = $regular_fields['name'];
                                } elseif (isset($regular_fields['email'])) {
                                    $task_name = $regular_fields['email'];
                                } elseif (isset($regular_fields['company'])) {
                                    $task_name = $regular_fields['company'];
                                } else {
                                    $task_name_from_form_name = true;
                                    $task_name                = $form->name;
                                }
                                if ($task_name_from_form_name == false) {
                                    $task_name .= ' - ' . $form->name;
                                }

                                $description          = '';
                                $custom_fields_parsed = [];
                                foreach ($custom_fields as $key => $field) {
                                    $custom_fields_parsed[$field['name']] = $field['value'];
                                }

                                $all_fields    = array_merge($regular_fields, $custom_fields_parsed);
                                $fields_labels = [];
                                foreach ($data['form_fields'] as $f) {
                                    if ($f->type != 'header' && $f->type != 'paragraph' && $f->type != 'file') {
                                        $fields_labels[$f->name] = $f->label;
                                    }
                                }

                                $description .= $form->name . '<br /><br />';
                                foreach ($all_fields as $name => $val) {
                                    if (isset($fields_labels[$name])) {
                                        if ($name == 'country' && is_numeric($val)) {
                                            $c = get_country($val);
                                            if ($c) {
                                                $val = $c->short_name;
                                            } else {
                                                $val = 'Unknown';
                                            }
                                        }

                                        $description .= $fields_labels[$name] . ': ' . $val . '<br />';
                                    }
                                }
								
									

                                $task_data = [
                                    'name'        => $task_name,
                                    'priority'    => get_option('default_task_priority'),
                                    'dateadded'   => date('Y-m-d H:i:s'),
                                    'startdate'   => date('Y-m-d'),
                                    'addedfrom'   => $form->responsible,
                                    'status'      => $status,
                                    'description' => $description,
                                ];

                                $task_data = hooks()->apply_filters('before_add_task', $task_data);
                                $this->db->insert(db_prefix() . 'tasks', $task_data);
                                $task_id = $this->db->insert_id();
                                if ($task_id) {
                                    $attachment = handle_task_attachments_array($task_id, 'file-input');

                                    if ($attachment && count($attachment) > 0) {
                                        $this->tasks_model->add_attachment_to_database($task_id, $attachment, false, false);
                                    }

                                    $assignee_data = [
                                        'taskid'   => $task_id,
                                        'assignee' => $form->responsible,
                                    ];
                                    $this->tasks_model->add_task_assignees($assignee_data, true);

                                    hooks()->do_action('after_add_task', $task_id);
                                    if ($duplicateLead && $duplicateLead->email != '') {
                                        send_mail_template('lead_web_form_submitted', $duplicateLead);
                                    }
                                }
                            }
                        }
                    }
                }
                
                if ($insert_to_db == true) {
                    $regular_fields['status'] = $form->lead_status;
                    if ((isset($regular_fields['name']) && empty($regular_fields['name'])) || !isset($regular_fields['name'])) {
                        $regular_fields['name'] = 'Unknown';
                    }
                    //$regular_fields['name']         = $form->lead_name_prefix ." - ". $regular_fields['name'];
					$regular_fields['name']         = $regular_fields['name'];
                    $regular_fields['source']       = $form->lead_source;
                    $regular_fields['addedfrom']    = 0;
                    $regular_fields['lastcontact']  = null;
                    $regular_fields['assigned']     = $form->responsible;
                    $regular_fields['dateadded']    = date('Y-m-d H:i:s');
                    $regular_fields['from_form_id'] = $form->id;
                    $regular_fields['is_public']    = $form->mark_public;
					$regular_fields['address']      = $ip_address;
					$regular_fields['country']      = $ip_country_id;
					$regular_fields['country_code'] = $ip_calling_code;
					$regular_fields['ip']           = $ipx;
					$regular_fields['status']       = $status;
					$regular_fields['company_id']   = $company_id;
                    $this->db->insert(db_prefix() . 'leads', $regular_fields);
                    $lead_id = $this->db->insert_id();
					
					//echo $lead_id;
					

                    hooks()->do_action('lead_created', [
                        'lead_id'          => $lead_id,
                        'web_to_lead_form' => true,
                    ]);

                    $success = false;
                    if ($lead_id) {
                        $success = true;

                        $this->leads_model->log_lead_activity($lead_id, 'not_lead_imported_from_form', true, serialize([
                            $form->name,
                        ]));
                        // /handle_custom_fields_post
                        $custom_fields_build['leads'] = [];
                        foreach ($custom_fields as $cf) {
                            $cf_id                                = strafter($cf['name'], 'form-cf-');
                            $custom_fields_build['leads'][$cf_id] = $cf['value'];
                        }

                        handle_custom_fields_post($lead_id, $custom_fields_build);

                        $this->leads_model->lead_assigned_member_notification($lead_id, $form->responsible, true);

                        handle_lead_attachments($lead_id, 'file-input', $form->name);

                        if ($form->notify_lead_imported != 0) {
                            $staff = [];
                            if ($form->notify_type != 'assigned') {
                                $ids = @unserialize($form->notify_ids);

                                if (is_array($ids) && count($ids) > 0) {
                                    $this->db->where('active', 1)
                                    ->where_in($form->notify_type == 'specific_staff' ? 'staffid' : 'role', $ids);
                                    $staff = $this->db->get(db_prefix() . 'staff')->result_array();
                                }
                            } elseif ($form->responsible) {
                                $staff = [
                                [
                                    'staffid' => $form->responsible,
                                ],
                            ];
                            }

                            $notifiedUsers = [];
                            foreach ($staff as $member) {
                                if (add_notification([
                                        'description' => 'not_lead_imported_from_form',
                                        'touserid' => $member['staffid'],
                                        'fromcompany' => 1,
                                        'fromuserid' => 0,
                                        'additional_data' => serialize([
                                            $form->name,
                                        ]),
                                        'link' => '#leadid=' . $lead_id,
                                    ])) {
                                    array_push($notifiedUsers, $member['staffid']);
                                }
                            }
                            pusher_trigger_notification($notifiedUsers);
                        }

                        if (isset($regular_fields['email']) && $regular_fields['email'] != '') {
                            $lead = $this->leads_model->get($lead_id);
                            send_mail_template('lead_web_form_submitted', $lead);
                        }
                    }
                } // end insert_to_db
                if ($success == true) {
                    if (!isset($lead_id)) {
                        $lead_id = 0;
                    }
                    if (!isset($task_id)) {
                        $task_id = 0;
                    }
                    hooks()->do_action('web_to_lead_form_submitted', [
                        'lead_id' => $lead_id,
                        'form_id' => $form->id,
                        'task_id' => $task_id,
                    ]);
                }

                $response = ['success' => $success];

                if ($form->submit_action === '0') {
                    $response['message'] = $form->success_submit_msg;
                }

                echo json_encode($response);
                die;
            }
        }

        $data['form'] = $form;
        $this->load->view('forms/web_to_lead', $data);
    }

    /**
     * Web to lead form
     * User no need to see anything like LEAD in the url, this is the reason the method is named eq lead
     * @param  string $hash lead unique identifier
     * @return mixed
     */
    public function l($hash = '')
    {
        if (!$hash) {
            show_404();
        }

        if (get_option('gdpr_enable_lead_public_form') == '0') {
            show_404();
        }
        $this->load->model('leads_model');
        $this->load->model('gdpr_model');
        $lead = $this->leads_model->get('', ['hash' => $hash]);

        if (!$lead || count($lead) > 1) {
            show_404();
        }

        $lead = array_to_object($lead[0]);
        load_lead_language($lead->id);

        if ($this->input->post('update')) {
            $data = $this->input->post();
            unset($data['update']);
            $this->leads_model->update($data, $lead->id);
            redirect(site_url('forms/l/' . $hash));
        } elseif ($this->input->post('export') && get_option('gdpr_data_portability_leads') == '1') {
            $this->load->library('gdpr/gdpr_lead');
            $this->gdpr_lead->export($lead->id);
        } elseif ($this->input->post('removal_request')) {
            $success = $this->gdpr_model->add_removal_request([
                'description'  => nl2br($this->input->post('removal_description')),
                'request_from' => $lead->name,
                'lead_id'      => $lead->id,
            ]);
            if ($success) {
                send_gdpr_email_template('gdpr_removal_request_by_lead', $lead->id);
                set_alert('success', _l('data_removal_request_sent'));
            }
            redirect(site_url('forms/l/' . $hash));
        }

        $lead->attachments = $this->leads_model->get_lead_attachments($lead->id);
        $this->disableNavigation();
        $this->disableSubMenu();
        $data['title'] = $lead->name;
        $data['lead']  = $lead;
        $this->view('forms/lead');
        $this->data($data);
        $this->layout(true);
    }

    public function public_ticket($key = '')
    {
        if (!$key) {
            show_404();
        }

        $this->load->model('tickets_model');
		

        if (strlen($key) != 32) {
            show_error('Invalid ticket key.');
        }

        $ticket = $this->tickets_model->get_ticket_by_id($key);

        if (!$ticket) {
            show_404();
        }

        hooks()->do_action('view_public_ticket', $ticket);

        if (!empty($ticket->merged_ticket_id)) {
            redirect(site_url('forms/tickets/' . $this->tickets_model->get($ticket->merged_ticket_id)->ticketkey));
        }

        if (!is_client_logged_in() && $ticket->userid) {
            load_client_language($ticket->userid);
        }

        if ($this->input->post()) {
            $this->form_validation->set_rules('message', _l('ticket_reply'), 'required');

            if ($this->form_validation->run() !== false) {
                $replyData = ['message' => $this->input->post('message')];

                if ($ticket->userid && $ticket->contactid) {
                    $replyData['userid']    = $ticket->userid;
                    $replyData['contactid'] = $ticket->contactid;
                } else {
                    $replyData['name']  = $ticket->from_name;
                    $replyData['email'] = $ticket->ticket_email;
                }

                $replyid = $this->tickets_model->add_reply($replyData, $ticket->ticketid);

                if ($replyid) {
                    set_alert('success', _l('replied_to_ticket_successfully', $ticket->ticketid));
                }

                redirect(get_ticket_public_url($ticket));
            }
        }

        $data['title']          = $ticket->subject;
        $data['ticket_replies'] = $this->tickets_model->get_ticket_replies($ticket->ticketid);
        $data['ticket']         = $ticket;
        hooks()->add_action('app_customers_footer', 'ticket_public_form_customers_footer');
        $data['single_ticket_view'] = $this->load->view($this->createThemeViewPath('single_ticket'), $data, true);

        $navigationDisabled = hooks()->apply_filters('disable_navigation_on_public_ticket_view', true);
        if ($navigationDisabled) {
            $this->disableNavigation();
        }

        $this->disableSubMenu();

        $this->data($data);

        $this->view('forms/public_ticket');
        no_index_customers_area();
        $this->layout(true);
    }

    public function ticket()
    {
        $provided_language = $this->input->get('language');
        $form              = new stdClass();
        $form->language    = $provided_language ? $provided_language : get_option('active_language');
        $form->recaptcha   = 1;

        $this->lang->load($form->language . '_lang', $form->language);
        load_custom_lang_file($form->language);

        $form->success_submit_msg = _l('success_submit_msg');

        $form = hooks()->apply_filters('ticket_form_settings', $form);

        if ($this->input->post() && $this->input->is_ajax_request()) {
            $post_data = $this->input->post();

            $required = ['subject', 'department', 'email', 'name', 'message', 'priority'];

            if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions_ticket_form') == 1) {
                $required[] = 'accept_terms_and_conditions';
            }

            foreach ($required as $field) {
                if (!isset($post_data[$field]) || isset($post_data[$field]) && empty($post_data[$field])) {
                    $this->output->set_status_header(422);
                    die;
                }
            }

            if (show_recaptcha() && $form->recaptcha == 1) {
                if (!do_recaptcha_validation($post_data['g-recaptcha-response'])) {
                    echo json_encode([
                        'success' => false,
                        'message' => _l('recaptcha_error'),
                    ]);
                    die;
                }
            }

            $post_data = [
                'email'      => $post_data['email'],
                'name'       => $post_data['name'],
                'subject'    => $post_data['subject'],
                'department' => $post_data['department'],
                'priority'   => $post_data['priority'],
                'service'    => isset($post_data['service']) && is_numeric($post_data['service'])
                    ? $post_data['service']
                    : null,
                'custom_fields' => isset($post_data['custom_fields']) && is_array($post_data['custom_fields'])
                    ? $post_data['custom_fields']
                    : [],
                'message' => $post_data['message'],
            ];

            $success = false;

            $this->db->where('email', $post_data['email']);
            $result = $this->db->get(db_prefix() . 'contacts')->row();

            if ($result) {
                $post_data['userid']    = $result->userid;
                $post_data['contactid'] = $result->id;
                unset($post_data['email']);
                unset($post_data['name']);
            }

            $this->load->model('tickets_model');

            $post_data = hooks()->apply_filters('ticket_external_form_insert_data', $post_data);
            $ticket_id = $this->tickets_model->add($post_data);

            if ($ticket_id) {
                $success = true;
            }

            if ($success == true) {
                hooks()->do_action('ticket_form_submitted', [
                    'ticket_id' => $ticket_id,
                ]);
            }

            echo json_encode([
                'success' => $success,
                'message' => $form->success_submit_msg,
            ]);

            die;
        }

        $this->load->model('tickets_model');
        $this->load->model('departments_model');
        $data['departments'] = $this->departments_model->get();
        $data['priorities']  = $this->tickets_model->get_priority();

        $data['priorities']['callback_translate'] = 'ticket_priority_translate';
        $data['services']                         = $this->tickets_model->get_service();

        $data['form'] = $form;
        $this->load->view('forms/ticket', $data);
    }
	
	
}