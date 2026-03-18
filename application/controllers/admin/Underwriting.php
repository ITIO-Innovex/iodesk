<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Underwriting extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!is_admin() && !staff_can('adder', 'under_writing') && !staff_can('approver', 'under_writing')) {
		//echo "kkkkkk";exit;
            access_denied('Underwriting');
        }
    }

    /**
     * List all underwriting records.
     */
    public function index()
    {
        $company_id = get_staff_company_id();

        if (function_exists('is_super') && is_super()) {
            $this->db->from(db_prefix() . 'deal_underwriting');
            $this->db->where('is_deleted', 0);
        } else {
            $this->db->from(db_prefix() . 'deal_underwriting');
            $this->db->where('company_id', $company_id);
            $this->db->where('is_deleted', 0);
        }

        $this->db->order_by('dateadded', 'DESC');
        $data['underwritings'] = $this->db->get()->result_array();

        $data['title'] = 'Underwriting List';
        $this->load->view('admin/underwriting/manage', $data);
    }

    /**
     * Create or update underwriting record (from modal).
     */
    public function save()
    {
        if (!$this->input->post()) {
            redirect(admin_url('underwriting'));
        }

        $id            = (int) $this->input->post('id');
        $forCompany    = trim($this->input->post('for_company', true));
        $forWebLink    = trim($this->input->post('web_link', true));
        $MDR           = trim($this->input->post('MDR', true));
        $SetupFee      = trim($this->input->post('SetupFee', true));
        $HoldBack      = trim($this->input->post('HoldBack', true));
        $CardTypePost  = $this->input->post('CardType');
        $Settlement    = trim($this->input->post('Settlement', true));
        $SettlementFee = trim($this->input->post('SettlementFee', true));
        $MinSettlement = trim($this->input->post('MinSettlement', true));
        $MonthlyFee    = trim($this->input->post('MonthlyFee', true));
        $Descriptor    = $this->input->post('Descriptor', true);
		$Remarks    = $this->input->post('Remarks', true);
        $ccEmail       = trim($this->input->post('cc_email', true));
        $status        = (int) $this->input->post('status');
        $Reason        = $this->input->post('Reason', true);

        // status: 1 = Approved, 2 = Pending, 3 = Rejected
        if (!in_array($status, [1, 2, 3], true)) {
            $status = 1; // default Approve
        }
        $isRejected = ($status === 3);
       
        if ($forCompany === '') {
            set_alert('danger', 'Please enter Company Name.');
            redirect(admin_url('underwriting'));
        }

        $CardType = '';
        if (is_array($CardTypePost)) {
            $CardType = implode(', ', array_filter(array_map('trim', $CardTypePost)));
        } else {
            $CardType = trim((string) $CardTypePost);
        }

        if (!$isRejected) {
            if ($MDR === '' && $SetupFee === '' && $HoldBack === '' && $CardType === '') {
                set_alert('danger', 'Please fill at least MDR, Setup Fee, Hold Back or Card Type.');
                redirect(admin_url('underwriting'));
            }
        } else {
            // On rejection, these fields are hidden/optional
            $MDR = '';
            $SetupFee = '';
            $HoldBack = '';
            $CardType = '';
            $Settlement = '';
            $SettlementFee = '';
            $MinSettlement = '';
            $MonthlyFee = '';
            $Descriptor = '';
        }

        $data = [
            'for_company'   => $forCompany,
            'web_link'  => $forWebLink,
            'MDR'           => $MDR,
            'SetupFee'      => $SetupFee,
            'HoldBack'      => $HoldBack,
            'CardType'      => $CardType,
            'Settlement'    => $Settlement,
            'SettlementFee' => $SettlementFee,
            'MinSettlement' => $MinSettlement,
            'MonthlyFee'    => $MonthlyFee,
            'Descriptor'    => $Descriptor,
			'Remarks'    	=> $Remarks,
            'cc_email'      => $ccEmail,
            'status'        => $status,
            'Reason'        => $Reason,
        ];

        $table = db_prefix() . 'deal_underwriting';
        $company_id = get_staff_company_id();
        $mail_sent=false;
        if ($id > 0) {
            // Update
            $this->db->where('id', $id);
            $this->db->where('company_id', $company_id);
            $success = $this->db->update($table, $data);
            if ($success) {
			$mail_sent=true;
                set_alert('success', 'Underwriting details updated successfully.');
            } else {
                set_alert('danger', 'Failed to update underwriting details.');
            }
        } else {
            // Insert
            $data['addedby'] = get_staff_user_id();
			$data['status'] = $status;
			$data['company_id'] = $company_id;
            $this->db->insert($table, $data);
            $insert_id = $this->db->insert_id();
            if ($insert_id) {
			$mail_sent=true;
                set_alert('success', 'Underwriting details added successfully.');
            } else {
                set_alert('danger', 'Failed to add underwriting details.');
            }
        }
		if($mail_sent){
		$email_underwriting=get_company_fields($company_id ,'email_underwriting');
		$staffName = get_staff_full_name() ?? 'Staff';
		$ccEmails = $ccEmail ?? "";
		$statusVal = ($status == 1) ? 'Approved' : 'Rejected';
		$emailBody = '<h3>Please find under writing the approval for the website: '.$forWebLink.'</h3>'
    . '<p><strong>Company Name:</strong> ' . $forCompany . '</p>'
    . '<p><strong>Website:</strong> ' . $forWebLink . '</p>'
    . '<p><strong>Status:</strong> ' . $statusVal . '</p>';

// Condition block
if ($status == 1) {
    $emailBody .= '<p><strong>MDR:</strong> ' . $MDR . '</p>'
        . '<p><strong>Setup Fee:</strong> ' . $SetupFee . '</p>'
        . '<p><strong>Hold Back:</strong> ' . $HoldBack . '</p>'
        . '<p><strong>Card Type:</strong> ' . $CardType . '</p>'
        . '<p><strong>Settlement:</strong> ' . $Settlement . '</p>'
        . '<p><strong>Settlement Fee:</strong> ' . $SettlementFee . '</p>'
        . '<p><strong>Min Settlement:</strong> ' . $MinSettlement . '</p>'
        . '<p><strong>Monthly Fee:</strong> ' . $MonthlyFee . '</p>'
        . '<p><strong>Descriptor:</strong> ' . $Descriptor . '</p>';
}

// Always add remarks
$emailBody .= '<p><strong>Remarks:</strong> ' . $Remarks . '</p>'
    . '<p><strong>Best Regards,<br><br></strong> ' . $staffName . '</p>';
	
	
		 // Prepare email data
                $msgdata = [
                    'recipientEmail' => $email_underwriting,
                    'recipientCC' => $ccEmails,
                    'emailSubject' => 'Under Writing - ' . $forWebLink . ' - ' . date('d-m-Y'),
                    'emailBody' => $emailBody,
                ];
				 
       
                
                // Send email if recipient exists
                if (!empty($msgdata['recipientEmail'])) {
                    $this->load->model('webmail_model');
                    $this->webmail_model->compose_email_super($msgdata);
                }
				
		//echo "Mail Send";exit;
		}

        redirect(admin_url('underwriting'));
    }

    /**
     * Delete underwriting record.
     */
    public function delete($id)
    {
        $id = (int) $id;
        if ($id <= 0) {
            redirect(admin_url('underwriting'));
        }

        $table = db_prefix() . 'deal_underwriting';
        $company_id = get_staff_company_id();

        if (function_exists('is_super') && is_super()) {
            $this->db->where('id', $id);
        } else {
            $this->db->where('id', $id);
            $this->db->where('company_id', $company_id);
        }

        $success = $this->db->update($table, ['is_deleted' => 1]);

        if ($success) {
            set_alert('success', 'Underwriting record deleted successfully.');
        } else {
            set_alert('danger', 'Failed to delete underwriting record.');
        }

        redirect(admin_url('underwriting'));
    }

    /**
     * Keep /admin/underwriting/add working, redirect to list.
     */
    public function add()
    {
        redirect(admin_url('underwriting'));
    }
	
	
	/**
     * Create or update underwriting record (from modal).
     */
    public function approve()
    {
        if (!$this->input->post()) {
            redirect(admin_url('underwriting'));
        }
		
		

        $id            = (int) $this->input->post('id');
        $status        = (int) $this->input->post('uw_status');
        $Reason        = $this->input->post('Reason', true);
       
       

        if ($id === '' || $status === '' || $Reason === '' ) {
            set_alert('danger', 'Please fill reason.');
            redirect(admin_url('underwriting'));
        }

          
        $data = [
            'status'        => $status,
            'Reason'        => $Reason,
        ];
		
		
		

        $table = db_prefix() . 'deal_underwriting';
        $company_id = get_staff_company_id();
        
        
            // Update
            $this->db->where('id', $id);
            $this->db->where('company_id', $company_id);
            $success = $this->db->update($table, $data);
			//echo $this->db->last_query();exit;
            if ($success) {
			
                set_alert('success', 'Underwriting details updated successfully.');
            } else {
                set_alert('danger', 'Failed to update underwriting details.');
            }
      
		/////////////Email Send //////////
		//$email_underwriting=get_company_fields($company_id ,'email_underwriting');
		
		$staffName = get_staff_full_name() ?? 'Staff';
		$ccEmails = $ccEmail ?? "";
		
								$statusUW="";
								if($status==1){
								$statusUW="Approved";
								}elseif($status==2){
								$statusUW="Pending";
								}else{
								$statusUW="Rejected";
								}
								
		$this->db->select('for_company, web_link, addedby');
        $this->db->where('id', $id);
        $this->db->where('company_id', $company_id);
        $uw_data = $this->db->get($table, $data)->row();
		$for_company="";
		$web_link="";
		$addedby="";
		if($uw_data){
		$for_company=$uw_data->for_company;
		$web_link=$uw_data->web_link;
		$addedby=$uw_data->addedby;
		}
        $staffEmail = get_staff_email($addedby) ?? '';
		 // Prepare email data
                $msgdata = [
                    'recipientEmail' => $staffEmail,
                    'recipientCC' => $ccEmails,
                    'emailSubject' => 'Under Writing ' . $statusUW . ' By - ' . $staffName . ' - ' . date('d-m-Y'),
                    'emailBody' => '<h3>Please find under writing the ' . $statusUW . ' details</h3>'
						. '<p><strong>Company Name:</strong> ' . $for_company . '</p>'
						. '<p><strong>Website:</strong> ' . $web_link . '</p>'
						. '<p><strong>Comments / Reason:</strong> ' . $Reason . '</p>'
						. '<p><strong>Status:</strong> ' . $statusUW . '</p>'
						. '<p><strong>Best Regards, <br><br></strong> ' . $staffName . '</p>',
                ];
				 
       //print_r( $msgdata);exit;
                
                // Send email if recipient exists
                if (!empty($msgdata['recipientEmail'])) {
                    $this->load->model('webmail_model');
                    $this->webmail_model->compose_email_super($msgdata);
                }
				
		//echo "Mail Send";exit;
		

        redirect(admin_url('underwriting'));
    }
}

