<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_utility_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all user utility forms
     */
    public function get_all_forms($where = [])
    {
        $this->db->select('*');
		$this->db->group_start();
		$this->db->where("FIND_IN_SET(".get_staff_user_id().", share_with) >", 0, FALSE); // FALSE = don't escape
		$this->db->or_where('created_by', get_staff_user_id());
		$this->db->group_end();
		$this->db->where('is_deleted', 0);
        $this->db->from(db_prefix() . 'user_utility_forms');
        
        if (!empty($where)) {
            $this->db->where($where);
        }
        
        // Filter by company if not admin
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        
        $this->db->order_by('date_created', 'DESC');
        //echo $this->db->get_compiled_select(); exit;
        return $this->db->get()->result(); //return 
		echo $this->db->last_query();exit;
		
    }

    /**
     * Get single form by ID
     */
    public function get($id)
    {
	
	
	     $this->db->group_start();
	    $this->db->where("FIND_IN_SET(".get_staff_user_id().", share_with) >", 0, FALSE); // FALSE = don't escape
		$this->db->or_where('created_by', get_staff_user_id());
		$this->db->group_end();
		$this->db->where('id', $id);
		$this->db->where('is_deleted', 0);
       
        //$this->db->where('created_by', get_staff_user_id());
        // Filter by company if not admin
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        
        return $this->db->get(db_prefix() . 'user_utility_forms')->row();
		//echo $this->db->last_query();exit;//return
    }

    /**
     * Add new form
     */
    public function add($data)
    {
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'user_utility_forms', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('User Utility Form Created [ID: ' . $insert_id . ', Name: ' . $data['form_name'] . ']');
        }

        return $insert_id;
    } 
	
	public function addcomment($data)
    {
        $data['date_created'] = date('Y-m-d H:i:s');
        
        $this->db->insert(db_prefix() . 'user_utility_comments', $data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            log_activity('User Utility Comment Created [ID: ' . $insert_id . ']');
        }

        return $insert_id;
    }

    /**
     * Update form
     */
    public function update($id, $data)
    {
        $data['date_updated'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $id);
        
        // Filter by company if not admin
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        
        $this->db->update(db_prefix() . 'user_utility_forms', $data);
        
        if ($this->db->affected_rows() > 0) {
            log_activity('User Utility Form Updated [ID: ' . $id . ']');
            return true;
        }

        return false;
    }

    /**
     * Delete form
     */
    public function delete($id)
    {
        // Get form details for logging
        $form = $this->get($id);
        
        if (!$form) {
            return false;
        }

		
		$data['date_updated'] = date('Y-m-d H:i:s');
		$data['is_deleted'] = 1;
        
        $this->db->where('id', $id);
        
        // Filter by company if not admin
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        
        $this->db->update(db_prefix() . 'user_utility_forms', $data);
		//echo $this->db->last_query();exit;
		
        
        if ($this->db->affected_rows() > 0) {
            log_activity('User Utility Form Deleted [ID: ' . $id . ', Name: ' . $form->form_name . ' By '.get_staff_user_id().']');
            
            // Clean up uploaded files if any
            $this->cleanup_form_files($form);
            
            return true;
        }

        return false;
    }

    /**
     * Clean up uploaded files for a form
     */
    private function cleanup_form_files($form)
    {
        if (!$form->form_data) {
            return;
        }

        $form_data = json_decode($form->form_data, true);
        $form_fields = json_decode($form->form_fields, true);

        if (!$form_data || !$form_fields) {
            return;
        }

        foreach ($form_fields as $field) {
            if ($field['type'] === 'file' && isset($form_data[$field['name']])) {
                $file_path = './uploads/user_utility/' . $form_data[$field['name']];
                if (file_exists($file_path)) {
                    @unlink($file_path);
                }
            }
        }
    }

    /**
     * Get forms by user
     */
    public function get_forms_by_user($user_id)
    {
        $this->db->where('created_by', $user_id);
        return $this->get_all_forms();
    }

    /**
     * Search forms
     */
    public function search($search_term)
    {
        $this->db->group_start();
        $this->db->like('form_name', $search_term);
        $this->db->or_like('form_fields', $search_term);
        $this->db->group_end();
        
        return $this->get_all_forms();
    }

    /**
     * Get form statistics
     */
    public function get_stats()
    {
        $stats = [];
        
        // Total forms
        $this->db->select('COUNT(*) as total');
        $this->db->from(db_prefix() . 'user_utility_forms');
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        $result = $this->db->get()->row();
        $stats['total_forms'] = $result->total;
        
        // Forms with data
        $this->db->select('COUNT(*) as total');
        $this->db->from(db_prefix() . 'user_utility_forms');
        $this->db->where('form_data IS NOT NULL');
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        $result = $this->db->get()->row();
        $stats['forms_with_data'] = $result->total;
        
        // Recent forms (last 30 days)
        $this->db->select('COUNT(*) as total');
        $this->db->from(db_prefix() . 'user_utility_forms');
        $this->db->where('date_created >=', date('Y-m-d', strtotime('-30 days')));
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        $result = $this->db->get()->row();
        $stats['recent_forms'] = $result->total;
        
        return $stats;
    }
	
	public function commentlist($id)
    {
		$this->db->where('utility_id', $id);
		$this->db->order_by('date_created', 'DESC');
        return $this->db->get(db_prefix() . 'user_utility_comments')->result();
    }
}
