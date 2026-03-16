<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menu_setup_company_model extends App_Model
{
    protected $table;

    public function __construct()
    {
        parent::__construct();
        $this->table = db_prefix() . 'menu_setup_company';
    }

    /**
     * Get menu JSON for a company and type (aside/setup)
     */
    public function get_menu($company_id, $menu_type = 'aside')
    {
        $this->db->where('company_id', (int) $company_id);
        $this->db->where('menu_type', $menu_type);
        $row = $this->db->get($this->table)->row();

        return $row ? $row->menu_json : null;
    }

    /**
     * Save menu JSON for a company and type (aside/setup)
     */
    public function save_menu($company_id, $menu_type, $menu_json)
    {
        $company_id = (int) $company_id;
        $menu_type  = $menu_type === 'setup' ? 'setup' : 'aside';

        $data = [
            'company_id' => $company_id,
            'menu_type'  => $menu_type,
            'menu_json'  => $menu_json,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $exists = $this->db->where('company_id', $company_id)
                           ->where('menu_type', $menu_type)
                           ->get($this->table)
                           ->row();

        if ($exists) {
            $this->db->where('id', $exists->id);
            $this->db->update($this->table, $data);
        } else {
            $this->db->insert($this->table, $data);
        }

        return true;
    }
}

