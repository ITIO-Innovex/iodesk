<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('services_subscriptions_model');
        $this->load->model('services_user_subscriptions_model');
        $this->load->model('services_subscriptions_invoices_model');

        if (!is_admin()) {
            access_denied('Service Subscriptions');
        }
    }

    public function subscriptions()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('services_subscriptions');
        }

        $data['title'] = 'Service Subscriptions';
        $this->load->view('admin/services/subscriptions/manage', $data);
    }

    public function choose_subscriptions()
    {
        $plans = $this->services_subscriptions_model->get();
        $activePlans = array_values(array_filter($plans, function ($plan) {
            return isset($plan['status']) ? $plan['status'] === 'active' : true;
        }));

        $data['title'] = 'Choose Subscription';
        $data['plans'] = $activePlans;
        $this->load->view('admin/services/choose_subscriptions/manage', $data);
    }

    public function my_subscriptions()
    {
        $companyId = get_staff_company_id();

        $this->db->select('sus.*, s.plan_name, s.price, s.currency, s.billing_cycle, s.no_of_staff, s.duration, s.features, s.tax');
        $this->db->from(db_prefix() . 'services_user_subscriptions sus');
        $this->db->join(db_prefix() . 'services_subscriptions s', 's.id = sus.subscription_id', 'left');
        $this->db->where('sus.company_id', $companyId);
        $this->db->order_by('sus.id', 'desc');
        $plan = $this->db->get()->row_array();

        $this->db->where('company_id', $companyId);
        $this->db->order_by('id', 'desc');
        $payments = $this->db->get(db_prefix() . 'services_subscriptions_invoices')->result_array();

        $data['title'] = 'My Subscription';
        $data['plan'] = $plan;
        $data['payments'] = $payments;
        $this->load->view('admin/services/my_subscriptions/manage', $data);
    }

    public function payment_status()
    {
        $companyId = get_staff_company_id();
        $invoiceNo = $this->input->get('invoice_no');
        $sessionId = $this->input->get('session_id');
        if (!$invoiceNo) {
            show_404();
        }

        $this->db->where('company_id', $companyId);
        $this->db->where('invoice_no', $invoiceNo);
        $payment = $this->db->get(db_prefix() . 'services_subscriptions_invoices')->row_array();

        if (!$payment) {
            show_404();
        }

        if ($sessionId && $payment['payment_status'] !== 'paid') {
            try {
                $this->load->library('stripe_core');
                $session = $this->stripe_core->retrieve_session([
                    'id'     => $sessionId,
                    'expand' => ['payment_intent'],
                ]);
				//print_r($session);
				//print_r($session->payment_status)."=====>";exit;

                if ($session->payment_status === 'paid') {
                    $this->db->where('company_id', $companyId);
                    $this->db->where('invoice_no', $invoiceNo);
                    $this->db->update(db_prefix() . 'services_subscriptions_invoices', [
                        'payment_status' => 'paid',
                        'payment_method' => 'Stripe',
						'payment_id' => $session->payment_intent->id,
						'payment_json' => json_encode($session),
                    ]);

                    $this->db->where('company_id', $companyId);
                    $this->db->where('subscription_id', $payment['subscription_id']);
                    $this->db->update(db_prefix() . 'services_user_subscriptions', [
                        'status' => 'active',
                    ]);
					
                    $this->db->where('subscription_id', $payment['subscription_id']);
                    $subs = $this->db->get(db_prefix() . 'services_user_subscriptions')->row_array();
					
					if($subs){
					$_SESSION['cms_subscription_id']=$payment['subscription_id'];
					$_SESSION['cms_subscription_start_date']=$subs['start_date'];
					$_SESSION['cms_subscription_end_date']=$subs['end_date'];
					$_SESSION['cms_subscription_status']=$subs['status'];
					$_SESSION['cms_subscription_created_at']=$subs['created_at'];
					}

                    $this->db->where('company_id', $companyId);
                    $this->db->where('invoice_no', $invoiceNo);
                    $payment = $this->db->get(db_prefix() . 'services_subscriptions_invoices')->row_array();
					set_alert('success', 'Payment Completed');
					
                }
            } catch (Exception $e) {
                log_message('error', 'Stripe session check failed: ' . $e->getMessage());
            }
        }

        $data['title'] = 'Payment Status';
        $data['payment'] = $payment;
        $this->load->view('admin/services/payment_status/manage', $data);
    }

    public function invoice($id)
    {
        if (!is_numeric($id)) {
            show_404();
        }

        $plan = $this->services_subscriptions_model->get((int) $id);
        if (!$plan) {
            show_404();
        }

        $data['title'] = 'Subscription Invoice';
        $data['plan'] = (array) $plan;
        $this->load->view('admin/services/invoice/manage', $data);
    }
	
	    public function plan_details($id)
    {
        if (!is_numeric($id)) {
            show_404();
        }

        $plan = $this->services_subscriptions_model->get((int) $id);
        if (!$plan) {
            show_404();
        }

        $data['title'] = 'Subscription Plan Details';
        $data['plan'] = (array) $plan;
        $this->load->view('admin/services/plan_details/manage', $data);
    }

    public function subscriptions_manage()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('plan_name', 'Plan Name', 'required|max_length[100]');
            $this->form_validation->set_rules('price', 'Price', 'required|numeric');
            $this->form_validation->set_rules('currency', 'Currency', 'required|max_length[10]');
            $this->form_validation->set_rules('billing_cycle', 'Billing Cycle', 'required|in_list[monthly,yearly,pro_data,per_month]');
            $this->form_validation->set_rules('duration', 'Duration', 'required|integer');
            if ($this->input->post('no_of_staff') !== '') {
                $this->form_validation->set_rules('no_of_staff', 'No of Staff', 'trim|integer');
            }
            if ($this->input->post('tax') !== '') {
                $this->form_validation->set_rules('tax', 'Tax', 'trim|numeric');
            }
            $this->form_validation->set_rules('status', 'Status', 'in_list[active,inactive]');

            $is_ajax = $this->input->is_ajax_request();
            $id = $this->input->post('id');

            if ($this->form_validation->run() === false) {
                $errors = validation_errors();
                log_message('error', 'Subscriptions validation failed: ' . $errors);
                if ($is_ajax) {
                    echo json_encode(['success' => false, 'message' => $errors]);
                    die;
                }
                set_alert('warning', $errors);
                redirect(admin_url('services/subscriptions'));
            }

            $data = $this->input->post();
           //log_message('error', 'Display data11 - ' . print_r($data, true));
            if (empty($id)) {
                $insert_id = $this->services_subscriptions_model->add($data);
                $success = (bool) $insert_id;
                $message = $success ? _l('added_successfully', 'Subscription') : _l('problem_adding', 'Subscription');
            } else {
			//log_message('error', 'Display data11 - '.$id );
			//log_message('error', 'Display data11 - ' . print_r($data, true));
                $success = $this->services_subscriptions_model->update((int) $id, $data);
                $message = $success ? _l('updated_successfully', 'Subscription') : _l('problem_updating', 'Subscription');
            }

            if ($is_ajax) {
                echo json_encode(['success' => $success, 'message' => $message]);
                die;
            }

            if ($success) {
                set_alert('success', $message);
            } else {
                set_alert('warning', $message);
            }
            redirect(admin_url('services/subscriptions'));
        }
    }

    public function subscriptions_delete($id)
    {
        if (!$id) {
            redirect(admin_url('services/subscriptions'));
        }

        $success = $this->services_subscriptions_model->delete($id);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $success, 'message' => $success ? _l('deleted', 'Subscription') : _l('problem_deleting', 'Subscription')]);
            die;
        }
        if ($success) {
            set_alert('success', _l('deleted', 'Subscription'));
        } else {
            set_alert('warning', _l('problem_deleting', 'Subscription'));
        }
        redirect(admin_url('services/subscriptions'));
    }

    public function user_subscriptions()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('services_user_subscriptions');
        }

        $data['title'] = 'Service User Subscriptions';
        $this->load->view('admin/services/user_subscriptions/manage', $data);
    }

    public function user_subscriptions_manage()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('company_id', 'Company ID', 'required|integer');
            $this->form_validation->set_rules('subscription_id', 'Subscription ID', 'required|integer');
            $this->form_validation->set_rules('start_date', 'Start Date', 'required');
            $this->form_validation->set_rules('end_date', 'End Date', 'required');
            $this->form_validation->set_rules('status', 'Status', 'in_list[active,expired,cancelled]');

            $is_ajax = $this->input->is_ajax_request();
            $id = $this->input->post('id');

            if ($this->form_validation->run() === false) {
                $errors = validation_errors();
                if ($is_ajax) {
                    echo json_encode(['success' => false, 'message' => $errors]);
                    die;
                }
                set_alert('warning', $errors);
                redirect(admin_url('services/user_subscriptions'));
            }

            $data = $this->input->post();
            if (empty($id)) {
                $insert_id = $this->services_user_subscriptions_model->add($data);
                $success = (bool) $insert_id;
                $message = $success ? _l('added_successfully', 'User Subscription') : _l('problem_adding', 'User Subscription');
            } else {
                $success = $this->services_user_subscriptions_model->update((int) $id, $data);
                $message = $success ? _l('updated_successfully', 'User Subscription') : _l('problem_updating', 'User Subscription');
            }

            if ($is_ajax) {
                echo json_encode(['success' => $success, 'message' => $message]);
                die;
            }

            if ($success) {
                set_alert('success', $message);
            } else {
                set_alert('warning', $message);
            }
            redirect(admin_url('services/user_subscriptions'));
        }
    }

    public function user_subscriptions_delete($id)
    {
        if (!$id) {
            redirect(admin_url('services/user_subscriptions'));
        }

        $success = $this->services_user_subscriptions_model->delete($id);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $success, 'message' => $success ? _l('deleted', 'User Subscription') : _l('problem_deleting', 'User Subscription')]);
            die;
        }
        if ($success) {
            set_alert('success', _l('deleted', 'User Subscription'));
        } else {
            set_alert('warning', _l('problem_deleting', 'User Subscription'));
        }
        redirect(admin_url('services/user_subscriptions'));
    }

    public function subscriptions_invoices()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('services_subscriptions_invoices');
        }

        $data['title'] = 'Subscription Invoices';
        $this->load->view('admin/services/subscriptions_invoices/manage', $data);
    }

    public function subscriptions_invoices_manage()
    {
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('invoice_no', 'Invoice No', 'required|max_length[50]');
            $this->form_validation->set_rules('company_id', 'Company ID', 'required|integer');
            $this->form_validation->set_rules('subscription_id', 'Subscription ID', 'required|integer');
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            $this->form_validation->set_rules('currency', 'Currency', 'required|max_length[10]');
            $this->form_validation->set_rules('tax', 'Tax', 'numeric');
            $this->form_validation->set_rules('total_amount', 'Total Amount', 'required|numeric');
            $this->form_validation->set_rules('invoice_date', 'Invoice Date', 'required');
            $this->form_validation->set_rules('payment_status', 'Payment Status', 'in_list[paid,unpaid,failed]');
            $this->form_validation->set_rules('payment_method', 'Payment Method', 'max_length[50]');

            $is_ajax = $this->input->is_ajax_request();
            $id = $this->input->post('id');

            if ($this->form_validation->run() === false) {
                $errors = validation_errors();
                if ($is_ajax) {
                    echo json_encode(['success' => false, 'message' => $errors]);
                    die;
                }
                set_alert('warning', $errors);
                redirect(admin_url('services/subscriptions_invoices'));
            }

            $data = $this->input->post();
            if (empty($id)) {
                $insert_id = $this->services_subscriptions_invoices_model->add($data);
                $success = (bool) $insert_id;
                $message = $success ? _l('added_successfully', 'Subscription Invoice') : _l('problem_adding', 'Subscription Invoice');
            } else {
                $success = $this->services_subscriptions_invoices_model->update((int) $id, $data);
                $message = $success ? _l('updated_successfully', 'Subscription Invoice') : _l('problem_updating', 'Subscription Invoice');
            }

            if ($is_ajax) {
                echo json_encode(['success' => $success, 'message' => $message]);
                die;
            }

            if ($success) {
                set_alert('success', $message);
            } else {
                set_alert('warning', $message);
            }
            redirect(admin_url('services/subscriptions_invoices'));
        }
    }

    public function subscriptions_invoices_delete($id)
    {
        if (!$id) {
            redirect(admin_url('services/subscriptions_invoices'));
        }

        $success = $this->services_subscriptions_invoices_model->delete($id);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['success' => $success, 'message' => $success ? _l('deleted', 'Subscription Invoice') : _l('problem_deleting', 'Subscription Invoice')]);
            die;
        }
        if ($success) {
            set_alert('success', _l('deleted', 'Subscription Invoice'));
        } else {
            set_alert('warning', _l('problem_deleting', 'Subscription Invoice'));
        }
        redirect(admin_url('services/subscriptions_invoices'));
    }
	
	public function subscriptions_payment()
    {
	$id = $this->input->get('pid');
	 if (!is_numeric($id)) {
            show_404();
        }else{
		$subscription_id = $id;
		}

        $plan = $this->services_subscriptions_model->get((int) $id);
        if (!$plan) {
            show_404();
        }
		

		
		//print_r($plan);
		//echo $plan->price;
		
					$amount = (float) $plan->price;
					$tax_percent = (float) $plan->tax;
					$tax_amount = ($amount * $tax_percent) / 100;
					$total_amount = $amount + $tax_amount;
	                $duration = isset($plan->duration) ? (int) $plan->duration : 0;
	              // check if pro_data
				  if($plan->billing_cycle=='pro_data'){
				  $datediff=get_remaining_days_in_month();
				  $amount = $amount * $datediff;
				  $tax_amount = ($amount * $tax_percent) / 100;
                  $total_amount = $amount + $tax_amount;
				  $duration=$duration * $datediff;
				  }
	
	$todayDate = new DateTime();
	$days=$plan->duration ?? 0;
    $endDate   = (clone $todayDate)->modify("+$days days");
	
	$payment_status="unpaid";	
	$payment_method="";
	
	$invoice_no=(10000+get_staff_company_id()).date("YmdHis"); // Generate Invoice Number
	$company_id=get_staff_company_id(); // Get Company ID
	$data = [
    'invoice_no'      => $invoice_no,
	'company_id'      => $company_id,
    'subscription_id' => $id,
    'amount'          => $amount,
    'currency'        => $plan->currency, // corrected (was price)
    'tax'             => $plan->tax,
    'total_amount'    => $total_amount,
    'invoice_date'    => $todayDate->format('Y-m-d'),
    'due_date'        => $todayDate->format('Y-m-d'),
    'payment_method'  => $payment_method,
    'created_at'      => $todayDate->format('Y-m-d'),
    ];
	
	   $pay_type=1;	
	
		if($plan->price==0){ 
		$data['payment_method']="Free";
		$data['payment_status']="paid";
		$status="active";
		$pay_type=0;
		}else{
		$data['payment_method']="Online";
		$status="new";
		}
		
		
		$this->db->insert(db_prefix() . 'services_subscriptions_invoices', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
        log_activity('Subscription Invoice Added [Plan: ' . $invoice_no . ', ID: ' . $insert_id . ']');
	
	// Check if company subscription exists
    $this->db->where('company_id', $company_id);
    $query = $this->db->get(db_prefix().'services_user_subscriptions');

    if ($query->num_rows() > 0) {

        // Update existing subscription
        $updateData = [
            'subscription_id' => $subscription_id,
            'start_date'      => $todayDate->format('Y-m-d'),
            'end_date'        => $endDate->format('Y-m-d'),
            'status'          => $status,
        ];

        $this->db->where('company_id', $company_id);
        $this->db->update(db_prefix().'services_user_subscriptions', $updateData);

    } else {

        // Insert new subscription
        $insertData = [
            'company_id'      => $company_id,
            'subscription_id' => $subscription_id,
            'start_date'      => $todayDate->format('Y-m-d'),
            'end_date'        => $endDate->format('Y-m-d'),
            'status'          => $status,
            'created_at'      => date('Y-m-d H:i:s')
        ];

        $this->db->insert(db_prefix().'services_user_subscriptions', $insertData);
    }
        }
		
	if($pay_type==1){
        try {
            $strp=$this->load->library('stripe_core');

            if (!$this->stripe_core->has_api_key() || !$this->stripe_core->get_publishable_key()) {
                set_alert('warning', 'Stripe keys are not configured.');
                redirect(admin_url('services/payment_status?invoice_no=' . $invoice_no));
            }

            $currency = strtolower($plan->currency ?? 'inr');
            if ($currency !== 'inr') {
                set_alert('warning', 'International payments are restricted to registered Indian businesses. Please use INR.');
                redirect(admin_url('services/payment_status?invoice_no=' . $invoice_no));
            }
            $amountCents = (int) round($total_amount * 100);
            $successUrl = admin_url('services/payment_status?invoice_no=' . $invoice_no . '&session_id={CHECKOUT_SESSION_ID}');
            $cancelUrl = admin_url('services/payment_status?invoice_no=' . $invoice_no);

            $sessionData = [
                'payment_method_types' => ['card'],
                'mode'                 => 'payment',
                'billing_address_collection' => 'required',
                'line_items'           => [
                    [
                        'price_data' => [
                            'currency'     => $currency,
                            'unit_amount'  => $amountCents,
                            'product_data' => [
                                'name'        => $plan->plan_name,
                                'description' => 'Subscription ' . ($plan->billing_cycle ?? ''),
                            ],
                        ],
                        'quantity' => 1,
                    ],
                ],
                'success_url' => $successUrl,
                'cancel_url'  => $cancelUrl,
                'metadata'    => [
                    'invoice_no'      => $invoice_no,
                    'company_id'      => $company_id,
                    'subscription_id' => $subscription_id,
                ],
            ];

            $staffEmail = $this->db->select('email')->where('staffid', get_staff_user_id())->get(db_prefix().'staff')->row();
            if ($staffEmail && $staffEmail->email) {
                $sessionData['customer_email'] = $staffEmail->email;
            }

            $session = $this->stripe_core->create_session($sessionData);
            redirect_to_stripe_checkout($session->id);
        } catch (Exception $e) {
            log_message('error', 'Stripe checkout failed: ' . $e->getMessage());
            set_alert('warning', $e->getMessage());
            redirect(admin_url('services/payment_status?invoice_no=' . $invoice_no));
        }
	
	}else{
	// Set Data in Session		
	$_SESSION['cms_subscription_id']=$subscription_id;
	$_SESSION['cms_subscription_start_date']=$todayDate->format('Y-m-d');
	$_SESSION['cms_subscription_end_date']=$endDate->format('Y-m-d');
	$_SESSION['cms_subscription_status']=$status;
	$_SESSION['cms_subscription_created_at']=$todayDate->format('Y-m-d');
	set_alert('success', 'Account Activate');
    redirect(admin_url('services/payment_status?invoice_no='.$invoice_no));
	}
		
		
		exit;

       
		
		
	}
}
