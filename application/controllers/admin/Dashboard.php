<?php

defined('BASEPATH') or exit('No direct script access allowed');  

class Dashboard extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    // This is admin dashboard view
    public function index() 
    {
        close_setup_menu();
        $this->load->model('departments_model');
        $this->load->model('todo_model');
        $data['departments'] = $this->departments_model->get();
		$data['departmentsID'] = $this->departments_model->get_staff_departments(get_staff_user_id(), true);

if (!empty($data['departmentsID']) && isset($data['departmentsID'][0])) {
    $data['departmentsID'] = $data['departmentsID'][0];
} else {
    $data['departmentsID'] = null; // or set a default department ID
}

        $data['todos'] = $this->todo_model->get_todo_items(0);
        // Only show last 5 finished todo items
        $this->todo_model->setTodosLimit(5);
        $data['todos_finished']            = $this->todo_model->get_todo_items(1);
        $data['upcoming_events_next_week'] = $this->dashboard_model->get_upcoming_events_next_week();
        $data['upcoming_events']           = $this->dashboard_model->get_upcoming_events();
        $data['title']                     = _l('dashboard_string');
		
		$this->load->model('hrd_model');
		$data['attendance']                = $this->hrd_model->get_todays_attendance();

        $this->load->model('contracts_model');
        $data['expiringContracts'] = $this->contracts_model->get_contracts_about_to_expire(get_staff_user_id());

        $this->load->model('currencies_model');
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['activity_log']  = $this->misc_model->get_activity_log();
        // Tickets charts
        $tickets_awaiting_reply_by_status     = $this->dashboard_model->tickets_awaiting_reply_by_status();
        $tickets_awaiting_reply_by_department = $this->dashboard_model->tickets_awaiting_reply_by_department();

        $data['tickets_reply_by_status']              = json_encode($tickets_awaiting_reply_by_status);
        $data['tickets_awaiting_reply_by_department'] = json_encode($tickets_awaiting_reply_by_department);

        $data['tickets_reply_by_status_no_json']              = $tickets_awaiting_reply_by_status;
        $data['tickets_awaiting_reply_by_department_no_json'] = $tickets_awaiting_reply_by_department;

        $data['projects_status_stats'] = json_encode($this->dashboard_model->projects_status_stats());
        $data['leads_status_stats']    = json_encode($this->dashboard_model->leads_status_stats());
        $data['google_ids_calendars']  = $this->misc_model->get_google_calendar_ids();
        $data['bodyclass']             = 'dashboard invoices-total-manual';
        $this->load->model('announcements_model');
        $data['staff_announcements']             = $this->announcements_model->get();
        //$data['total_undismissed_announcements'] = $this->announcements_model->get_total_undismissed_announcements();
        $data['deal_task']     = $this->leads_model->get_deal_task();
		$data['notes']         = $this->misc_model->get_notes_home();
		//print_r($data['notes']);exit;
        //$this->load->model('projects_model');
        //$data['projects_activity'] = $this->projects_model->get_activity('', hooks()->apply_filters('projects_activity_dashboard_limit', 20));
        add_calendar_assets();
        $this->load->model('utilities_model');
        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $wps_currency = 'undefined';
        if (is_using_multiple_currencies()) {
            $wps_currency = $data['base_currency']->id;
        }
        $data['weekly_payment_stats'] = json_encode($this->dashboard_model->get_weekly_payments_statistics($wps_currency));

        $data['dashboard'] = true;

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (! $data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        $data['tickets_report'] = [];
        if (is_admin()) {
            $data['tickets_report'] = (new app\services\TicketsReportByStaff())->filterBy('this_month');
        }
		
		$data['maintenance_notice'] = get_maintenance_notice();
	
	   
	   $company_id    = get_staff_company_id();
	   $company_details   = [];
	   $company_details = (array) $this->db->select('company_logo, favicon, settings, direct_mail_smtp')
                ->where('company_id', $company_id)
                ->get(db_prefix() . 'company_master')
                ->row_array();
            $ai_details_count = (int) $this->db->where('company_id', $company_id)
                ->count_all_results(db_prefix() . 'ai_details');
				
        
		
		$department_count = (int) $this->db->where('company_id', $company_id)
                ->count_all_results(db_prefix() . 'departments');
            $designation_count = (int) $this->db->where('company_id', $company_id)
                ->where('is_active', 1)
                ->count_all_results(db_prefix() . 'designations');
		
		$data['company_details']                = $company_details;
		$data['active_department_count']        = $department_count;
        $data['active_designation_count']       = $designation_count;		
        $data = hooks()->apply_filters('before_dashboard_render', $data);
		
		
		
        $this->load->view('admin/dashboard/dashboard', $data);
    }
	
	public function eindia() 
    {
	$data['title'] = "Eindia";
	$this->load->view('admin/dashboard/eindia', $data);
	}

    // Chart weekly payments statistics on home page / ajax
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));

            exit();
        }
    }

    // Chart monthly payments statistics on home page / ajax
    public function monthly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_monthly_payments_statistics($currency));

            exit();
        }
    }

    public function ticket_widget($type)
    {
        $data['tickets_report'] = (new app\services\TicketsReportByStaff())->filterBy($type);
        $this->load->view('admin/dashboard/widgets/tickets_report_table', $data);
    }
	
	public function testemail()
    {
        $this->dashboard_model->testemail();
		redirect(admin_url('dashboard'));
    }
}
