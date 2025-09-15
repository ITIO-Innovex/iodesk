<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Designations_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            if (!is_super()) {
                $this->db->where('company_id', get_staff_company_id());
            }
            return $this->db->get(db_prefix() . 'designations')->row();
        }

        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } elseif (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
            $this->db->where('company_id', $_SESSION['super_view_company_id']);
        }

        return $this->db->get(db_prefix() . 'designations')->result_array();
    }

    public function add($data)
    {
        $insert = [
            'department_id' => (int)($data['department_id'] ?? 0),
            'title'         => trim($data['title'] ?? ''),
            'is_active'     => isset($data['is_active']) ? 1 : 0,
            'created_by'    => get_staff_user_id(),
            'date_created'  => date('Y-m-d H:i:s'),
            'company_id'    => get_staff_company_id(),
        ];

        if ($insert['title'] === '' || $insert['department_id'] <= 0) {
            return false;
        }

        $this->db->insert(db_prefix() . 'designations', $insert);
        $id = $this->db->insert_id();
        if ($id) {
            log_activity('Designation Added [Title: ' . $insert['title'] . ', ID: ' . $id . ']');
        }
        return $id;
    }

    public function update($id, $data)
    {
        $update = [
            'department_id' => (int)($data['department_id'] ?? 0),
            'title'         => trim($data['title'] ?? ''),
            'is_active'     => isset($data['is_active']) ? 1 : 0,
            'updated_by'    => get_staff_user_id(),
            'date_updated'  => date('Y-m-d H:i:s'),
        ];
        if ($update['title'] === '' || $update['department_id'] <= 0) {
            return false;
        }

        $this->db->where('id', $id);
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->update(db_prefix() . 'designations', $update);
        if ($this->db->affected_rows() > 0) {
            log_activity('Designation Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        }
        $this->db->delete(db_prefix() . 'designations');
        if ($this->db->affected_rows() > 0) {
            log_activity('Designation Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }
}
