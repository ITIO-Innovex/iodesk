<?php

use app\services\imap\Imap;
use app\services\imap\ConnectionErrorException;
use Ddeboer\Imap\Exception\MailboxDoesNotExistException;
defined('BASEPATH') or exit('No direct script access allowed');

class Webmail_setup extends AdminController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('webmail_setup_model');
        if (!is_admin()) {
            //access_denied('Access Webmail Setup');
			//$sid=get_staff_user_id();
        }
        
    }

    /* List all custom fields */
    public function index()
    {
	    $sid="";
	    if (!is_admin()) {
            //access_denied('Access Webmail Setup');
			$sid=get_staff_user_id();
        }
		
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('webmail_setup');
        }
        $data['title'] = _l('Webmail Setup');
        $where="";
        $data['webmaillist']=$this->webmail_setup_model->get($sid);
		$data['departmentlist']   = $this->webmail_setup_model->getlist('', $where);
		
		// Load staff list for multi-select dropdown
		$this->load->model('staff_model');
		if (!is_super()) {
			$this->db->where('company_id', get_staff_company_id());
		} else {
			if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
				$this->db->where('company_id', $_SESSION['super_view_company_id']);
			} else {
				$this->db->where('company_id', get_staff_company_id());
			}
		}
		$this->db->where('active', 1);
		$data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
		
        $this->load->view('admin/webmail_setup', $data);
    }
	
	
	//Add Webmail Setup 
    public function webmail_setup_create()
    {
	
        $data = $this->input->post();
		//log_message('error', 'Display data - ' . print_r($data, true));
		
		if (isset($data['fakeusernameremembered'])) {
            unset($data['fakeusernameremembered']);
        }

        if (isset($data['fakepasswordremembered'])) {
            unset($data['fakepasswordremembered']);
        }
		if (isset($data['emailprovider'])) {
            unset($data['emailprovider']);
        }
        if (isset($data['source'])) {
            unset($data['source']);
			$data['mailer_smtp_host']="smtppro.zoho.in";
			$data['mailer_smtp_port']="465";
			$data['mailer_imap_host']="imappro.zoho.in";
			$data['mailer_imap_port']="993";
			$data['encryption']="ssl";
			$data['mailer_username']=$data['mailer_email'];
        }


        unset($data['id']);
        $data['creator']      = get_staff_user_id();
        $data['creator_name'] = get_staff_full_name($data['creator']);

        if (empty($data['port'])) {
            unset($data['port']);
        }
         //print_r($data);
		$data['company_id']  = get_staff_company_id();
        
        // Handle assignto multi-select - convert array to comma-separated string
        if (isset($data['assignto']) && is_array($data['assignto'])) {
            $data['assignto'] = implode(',', array_filter(array_map('intval', $data['assignto'])));
        } else {
            $data['assignto'] = '';
        }
        
        // If staffid is provided in POST (from staff listing), use it; otherwise use default logic
        if (isset($data['staffid']) && $data['staffid'] != '') {
            // staffid is already set from form, keep it
        } else {
            // Default behavior: set staffid based on user type
            $data['staffid'] = get_staff_user_id();
            if (is_admin()) {
			    if ($data['assignto']=="" && $data['departmentid']=="") {
                $data['staffid'] = get_staff_user_id();
				}else{
				$data['staffid'] = 0;
				}
            }
        }
        //log_message('error', 'Controller data !! - ' . print_r($data, true));
        $this->webmail_setup_model->create($data);
        set_alert('success', _l('added_successfully', _l('Webmail Setup')));
        
        // If called from staff listing, redirect back to staff page
        if (isset($data['staffid']) && $data['staffid'] > 0 && !is_admin()) {
            redirect(admin_url('staff'));
        } else {
            redirect(admin_url('webmail_setup'));
        }
    }
	
	
	//Update Webmail Setup 
    public function webmail_setup_update($entry_id)
    {
	
        $entry = $this->webmail_setup_model->get($entry_id);
		
        if ((is_admin()) || ( !is_admin()&& get_staff_user_id())) {
            $data = $this->input->post();

            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }
			if (isset($data['emailprovider'])) {
            unset($data['emailprovider']);
            }
			
			if (isset($data['source'])) {
            unset($data['source']);
        }
		
			unset($_SESSION['webmail']);

            $data['last_updated_from'] = get_staff_full_name(get_staff_user_id());
            //$data['description']       = nl2br($data['description']);

            // Handle assignto multi-select - convert array to comma-separated string
            if (isset($data['assignto']) && is_array($data['assignto'])) {
                $data['assignto'] = implode(',', array_filter(array_map('intval', $data['assignto'])));
            } elseif (!isset($data['assignto'])) {
                // If not set in POST, preserve existing value
                if (isset($entry[0]['assignto'])) {
                    $data['assignto'] = $entry[0]['assignto'];
                } else {
                    $data['assignto'] = '';
                }
            }
            
            // Preserve staffid if it's set in POST (from staff listing)
            if (isset($data['staffid']) && $data['staffid'] != '') {
                // Keep the staffid from POST
            } else {
                // If not set, preserve the existing staffid from entry
                if (isset($entry[0]['staffid'])) {
                    $data['staffid'] = $entry[0]['staffid'];
                }
            }

            $this->webmail_setup_model->update($entry_id, $data);
            set_alert('success', _l('updated_successfully', _l('Webmail Setup')));
            
            // If called from staff listing (staffid > 0 and not admin), redirect back to staff page
            if (isset($data['staffid']) && $data['staffid'] > 0 && !is_admin()) {
                redirect(admin_url('staff'));
            }
        }
        redirect(admin_url('webmail_setup'));
    }
	
	//Delete Webmail Setup from database 
    public function delete($id)
    {
	
	
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        $response = $this->webmail_setup_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('webmail setup')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('webmail setup')));
        }
        redirect(admin_url('webmail_setup'));
    }
	
	//Fetch for for update by id
    public function webmail_setup_entry($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }

        // Limit by company
        $this->db->where('company_id', get_staff_company_id());

        // Always match by primary ID
        $this->db->where('id', (int)$id);

        // For non-admins, additionally ensure the record belongs to the logged-in staff (or shared staffid = 0)
        if (!is_admin()) {
            $sid = (int)get_staff_user_id();
            $this->db->group_start();
            $this->db->where('staffid', $sid);
            $this->db->or_where('staffid', 0);
            $this->db->group_end();
        }

        $entry = $this->db->get(db_prefix() . 'webmail_setup')->row_array();

        // Return empty object if nothing found to avoid undefined index notices
        echo json_encode($entry ? $entry : []);
    }

    //Delete Webmail Setup from database 
    public function statusoff($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        $response = $this->webmail_setup_model->status($id ,0);
        if ($response == true) {
            set_alert('success', _l('webmail setup status updated', _l('webmail setup status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('webmail setup')));
        }
        redirect(admin_url('webmail_setup'));
    }
	//Delete Webmail Setup from database 
    public function statuson($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        $response = $this->webmail_setup_model->status($id ,1);
        if ($response == true) {
            set_alert('success', _l('webmail setup status updated', _l('webmail setup status')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('webmail setup')));
        }
        redirect(admin_url('webmail_setup'));
    }
	
	//Increase Priority
    public function prioritychange($id)
    {
        if (!$id) {
            redirect(admin_url('webmail_setup'));
        }
        
        
        
       
            //log_message('error', 'USER ID - '.$id );
            $response = $this->webmail_setup_model->priority($id);
            if ($response == true) {
                set_alert('success', _l('webmail setup priority updated', _l('webmail setup priority')));
            } else {
                set_alert('warning', _l('problem_updating', _l('webmail setup priority')));
            }
       
        
        redirect(admin_url('webmail_setup'));
    }
	

	
	
	public function folders()
    {
        app_check_imap_open_function();

        $imap = new Imap(
           $this->input->post('username') ? $this->input->post('username') : $this->input->post('email'),
           $this->input->post('password', false),
           $this->input->post('host'),
           $this->input->post('encryption')
        );

        try {
            echo json_encode($imap->getSelectableFolders());
        } catch (ConnectionErrorException $e) {
            echo json_encode([
                'alert_type' => 'warning',
                'message'    => $e->getMessage(),
            ]);
        }
    }
	
	

    public function test_imap_connection()
    {
        app_check_imap_open_function();

        $imap = new Imap(
           $this->input->post('username') ? $this->input->post('username') : $this->input->post('email'),
           $this->input->post('password', false),
           $this->input->post('host'),
           $this->input->post('encryption')
        );

        try {
            $connection = $imap->testConnection();

            try {
                $folder = $this->input->post('folder');

                $connection->getMailbox(empty($folder) ? 'INBOX' : $folder);
            } catch (MailboxDoesNotExistException $e) {
                echo json_encode([
                    'alert_type' => 'warning',
                    'message'    => $e->getMessage(),
                ]);
                die;
            }
            echo json_encode([
                'alert_type' => 'success',
                'message'    => _l('lead_email_connection_ok'),
            ]);
        } catch (ConnectionErrorException $e) {
            echo json_encode([
                'alert_type' => 'warning',
                'message'    => $e->getMessage(),
            ]);
        }
    }



}
 