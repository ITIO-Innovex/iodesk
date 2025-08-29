<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ai_content_generator extends AdminController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ai_content_generator_model');
        $this->load->library('form_validation');
        if (!is_admin()) {
            //access_denied('Access Webmail Setup');
        }
		
		/////////////////Get KEY/////////////
		$_SESSION['ai-apikey']="";
		if(empty($_SESSION['ai-apikey']) ){  
		$data['ai-apikey']= $this->ai_content_generator_model->getapikey();
		if(isset($data['ai-apikey'])&&!empty($data['ai-apikey'])){
		$_SESSION['ai-apikey']=$data['ai-apikey'][0]['apikey'];
		}else{
		set_alert('failed', _l('Failed', _l('ChatGtp APIKEY Not Found')));
		}
		
		}
		
		////////////////////////////////////
		
		
		$data['user_id']      = get_staff_user_id();
        $data['added_by'] = get_staff_full_name($data['user_id']);
		$where=" user_id = '".$data['user_id']."' AND added_by ='".$data['added_by']."'";
		$_SESSION['datalists']   = $this->ai_content_generator_model->getlist('', $where);
		
        
    }
    /* Redirect Index to Inbox page */
    public function index()
    {
	    $data['title'] = _l('Generate Content');
		$data['providers'] = $this->ai_content_generator_model->get_ai_providers(1);
		//print_r($data['provider']);
		$this->load->view('admin/ai_content_generator/main', $data);
    }
	
	public function generate()
    {
	
	    $data = $this->input->post();
		$title = isset($data['content_title']) ? trim($data['content_title']) : '';
         if ($title === '') {
		 set_alert('danger', _l('Error : Empty / Wrong Content'));
		 $data['providers'] = $this->ai_content_generator_model->get_ai_providers(1);
		$this->load->view('admin/ai_content_generator/main', $data);
        }else{
		
		if (isset($data['submit'])) { unset($data['submit'] );}
		$data['user_id']      = get_staff_user_id();
        $data['added_by'] = get_staff_full_name($data['user_id']);
        $data['ai_content']=$this->ai_content_generator_model->generate($data);
		$data['providers'] = $this->ai_content_generator_model->get_ai_providers(1);
		//print_r($data['ai_content']);exit;
		
		if(isset($data['ai_content']['error'])&&!empty($data['ai_content']['error'])){
		//echo $data['ai_content']['error'];
		$data['content_description']=$data['ai_content']['error'];
		set_alert('danger', _l('Error : Content Not Generated'));
		$this->load->view('admin/ai_content_generator/main', $data);
		//exit;
		
		}else{
		
		$data['content_description']=$data['ai_content']['content'];
		//exit;
        set_alert('success', _l('added_successfully', _l('AI Content')));
		$this->load->view('admin/ai_content_generator/main', $data);
		}
		}
    }
	
	public function generate_email_ai()
    {
	
	    $data = $this->input->post();
		if (isset($data['submit'])) { unset($data['submit'] );}
		$data['user_id']      = get_staff_user_id();
        $data['added_by'] = get_staff_full_name($data['user_id']);
        $data['ai_content']=$this->ai_content_generator_model->generate($data);
		
	
			
		if(isset($data['ai_content']['error'])&&!empty($data['ai_content']['error'])){
		//echo $data['ai_content']['error'];
		$data['content_description']=$data['ai_content']['error'];
		echo json_encode([
                'alert_type' => 'failed',
                'message'    => $data['content_description'],
            ]);
		
		}else{
		
		$data['content_description']=$data['ai_content']['content'];
		echo json_encode([
                'alert_type' => 'success',
                'message'    => $data['content_description'],
            ]);
		}
    }
	

    //Fetch for for update by id
    public function ai_setup()
    {
        $entry=$this->ai_content_generator_model->getapikey();
		
		if(isset($entry)&&!empty($entry)){
		echo json_encode($entry[0]);
		}else{
		echo '{"apikey":""}';
		}
	}
	
	//Update Webmail Setup 
    public function ai_setup_update()
    {
	       
		
        if (is_admin()) {
            $data = $this->input->post();

            if (isset($data['fakeusernameremembered'])) {
                unset($data['fakeusernameremembered']);
            }
            if (isset($data['fakepasswordremembered'])) {
                unset($data['fakepasswordremembered']);
            }


            $this->ai_content_generator_model->update($data);
            set_alert('success', _l('updated_successfully', _l('AI APIKEY Setup')));
        }
        redirect(admin_url('ai_content_generator'));
    }

    // AI Provider Management Methods
    public function manage_ai_provider()
    {
        if (!is_admin()) {
            access_denied('ai_providers');
        }

        $data['title'] = 'Manage AI Providers';
        $data['providers'] = $this->ai_content_generator_model->get_ai_providers();
        $this->load->view('admin/ai_content_generator/manage_ai_provider', $data);
    }

    public function add_ai_provider()
    {
        if (!is_admin()) {
            access_denied('ai_providers');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            
            // Debug: Log received data
            log_message('debug', 'AI Provider Add Data: ' . print_r($data, true));
            
            // Validation
            $this->form_validation->set_rules('provider_name', 'Provider Name', 'required');
            $this->form_validation->set_rules('provider_url', 'Provider URL', 'required');
            //$this->form_validation->set_rules('api_key', 'API Key', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[1,0]');

            if ($this->form_validation->run() == TRUE) {
                $insert_data = [
                    'provider_name' => $this->input->post('provider_name'),
                    'provider_url' => $this->input->post('provider_url'),
                    /*'api_key' => $this->input->post('api_key'),*/
                    'status' => $this->input->post('status'),
					'company_id' => get_staff_company_id(),
					'viewon' => $this->input->post('viewon'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Debug: Log insert data
                log_message('debug', 'AI Provider Insert Data: ' . print_r($insert_data, true));

                $result = $this->ai_content_generator_model->add_ai_provider($insert_data);
                
                // Debug: Log result
                log_message('debug', 'AI Provider Insert Result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                log_message('debug', 'Last Query: ' . $this->db->last_query());

                if ($result) {
                    set_alert('success', 'AI Provider added successfully');
                } else {
                    set_alert('danger', 'Failed to add AI Provider. Error: ' . $this->db->error()['message']);
                }
            } else {
                set_alert('danger', validation_errors());
            }
        }

        redirect(admin_url('ai_content_generator/manage_ai_provider'));
    }

    public function edit_ai_provider($id = '')
    {
        if (!is_admin()) {
            access_denied('ai_providers');
        }

        if (empty($id)) {
            redirect(admin_url('ai_content_generator/manage_ai_provider'));
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            
            // Debug: Log received data
            log_message('debug', 'AI Provider Edit Data for ID ' . $id . ': ' . print_r($data, true));
            
            // Validation
            $this->form_validation->set_rules('provider_name', 'Provider Name', 'required');
            $this->form_validation->set_rules('provider_url', 'Provider URL', 'required');
            //$this->form_validation->set_rules('api_key', 'API Key', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[1,0]');

            if ($this->form_validation->run() == TRUE) {
                $update_data = [
                    'provider_name' => $this->input->post('provider_name'),
                    'provider_url' => $this->input->post('provider_url'),
                    /*'api_key' => $this->input->post('api_key'),*/
                    'status' => $this->input->post('status'),
					'viewon' => $this->input->post('viewon'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Debug: Log update data
                log_message('debug', 'AI Provider Update Data: ' . print_r($update_data, true));

                $result = $this->ai_content_generator_model->update_ai_provider($id, $update_data);
                
                // Debug: Log result
                log_message('debug', 'AI Provider Update Result: ' . ($result ? 'SUCCESS' : 'FAILED'));
                log_message('debug', 'Last Query: ' . $this->db->last_query());

                if ($result) {
                    set_alert('success', 'AI Provider updated successfully');
                } else {
                    set_alert('danger', 'Failed to update AI Provider. Error: ' . $this->db->error()['message']);
                }
            } else {
                set_alert('danger', validation_errors());
            }
        }

        redirect(admin_url('ai_content_generator/manage_ai_provider'));
    }

    public function delete_ai_provider($id = '')
    {
        if (!is_admin()) {
            access_denied('ai_providers');
        }

        if (empty($id)) {
            redirect(admin_url('ai_content_generator/manage_ai_provider'));
        }

        if ($this->ai_content_generator_model->delete_ai_provider($id)) {
            set_alert('success', 'AI Provider deleted successfully');
        } else {
            set_alert('danger', 'Failed to delete AI Provider');
        }

        redirect(admin_url('ai_content_generator/manage_ai_provider'));
    }

    public function get_ai_provider($id = '')
    {
        if (!is_admin()) {
            echo json_encode(['error' => 'Access denied']);
            return;
        }

        if (empty($id)) {
            echo json_encode(['error' => 'Invalid ID']);
            return;
        }

        $provider = $this->ai_content_generator_model->get_ai_provider($id);
        echo json_encode($provider);
    }

}