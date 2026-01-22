<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services_user_subscriptions_model extends App_Model
{
    private $table = 'it_crm_services_user_subscriptions';

    public function get($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get($this->table)->row();
        }

        return $this->db->get($this->table)->result_array();
    }

    public function add($data)
    {
        $insert = $this->normalize_payload($data);
        if (!$insert) {
            return false;
        }

        $insert['created_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->table, $insert);
        $id = $this->db->insert_id();
        if ($id) {
            log_activity('Service User Subscription Added [ID: ' . $id . ']');
        }
        return $id;
    }

    public function update($id, $data)
    {
        $update = $this->normalize_payload($data, false);
        if (!$update) {
            return false;
        }

        $this->db->where('id', $id);
        $this->db->update($this->table, $update);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service User Subscription Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service User Subscription Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    private function normalize_payload($data, $requireAll = true)
    {
        $company_id = isset($data['company_id']) ? (int) $data['company_id'] : 0;
        $subscription_id = isset($data['subscription_id']) ? (int) $data['subscription_id'] : 0;
        $start_date = trim($data['start_date'] ?? '');
        $end_date = trim($data['end_date'] ?? '');
        $status = $data['status'] ?? 'active';

        $allowed_status = ['active', 'expired', 'cancelled'];

        if ($requireAll) {
            if ($company_id <= 0 || $subscription_id <= 0 || $start_date === '' || $end_date === '') {
                return false;
            }
        }

        if (!in_array($status, $allowed_status, true)) {
            $status = 'active';
        }

        return [
            'company_id' => $company_id,
            'subscription_id' => $subscription_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status,
        ];
    }
}
