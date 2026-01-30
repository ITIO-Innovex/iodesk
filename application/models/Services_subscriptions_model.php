<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services_subscriptions_model extends App_Model
{
    private $table = 'it_crm_services_subscriptions';

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
        $insert['updated_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->table, $insert);
        $id = $this->db->insert_id();
        if ($id) {
            log_activity('Service Subscription Added [Plan: ' . $insert['plan_name'] . ', ID: ' . $id . ']');
        }
        return $id;
    }

    public function update($id, $data)
    {
        $update = $this->normalize_payload($data, false);
        if (!$update) {
            return false;
        }

        $update['updated_at'] = date('Y-m-d H:i:s');

        $this->db->where('id', $id);
        $this->db->update($this->table, $update);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Subscription Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Service Subscription Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    private function normalize_payload($data, $requireAll = true)
    {
        $plan_name = trim($data['plan_name'] ?? '');
        $price = isset($data['price']) ? (float) $data['price'] : null;
        $currency = trim($data['currency'] ?? 'INR');
        $billing_cycle = $data['billing_cycle'] ?? '';
        if ($billing_cycle === 'per_month') {
            $billing_cycle = 'pro_data';
        }
        $duration = isset($data['duration']) ? (int) $data['duration'] : 0;
        $no_of_staff = isset($data['no_of_staff']) && $data['no_of_staff'] !== ''
            ? (int) $data['no_of_staff']
            : null;
        $tax = isset($data['tax']) ? (float) $data['tax'] : 0.00;
        $features = $data['features'] ?? null;
        $status = $data['status'] ?? 'active';

        $allowed_cycles = ['monthly', 'yearly', 'pro_data'];
        $allowed_status = ['active', 'inactive'];

        if ($requireAll) {
            if ($plan_name === '' || $price === null || $currency === '' || $billing_cycle === '' || $duration <= 0) {
                return false;
            }
        }

        if ($billing_cycle !== '' && !in_array($billing_cycle, $allowed_cycles, true)) {
            return false;
        }

        if (!in_array($status, $allowed_status, true)) {
            $status = 'active';
        }

        return [
            'plan_name'     => $plan_name,
            'price'         => $price ?? 0,
            'currency'      => $currency,
            'billing_cycle' => $billing_cycle !== '' ? $billing_cycle : 'monthly',
            'duration'      => $duration,
            'no_of_staff'   => $no_of_staff,
            'tax'           => $tax,
            'features'      => $features,
            'status'        => $status,
        ];
    }
	
	public function log_service_activity($subscription_id, $service_type, $description)
    {
        $log = [
            'date'            => date('Y-m-d H:i:s'),
            'description'     => $description,
			'staffid'         => get_staff_user_id(),
			'company_id'      => get_staff_company_id(),
            'subscription_id' => $subscription_id,
			'service_type'    => $service_type
        ];
        

        $this->db->insert(db_prefix() . 'service_log', $log);

        return $this->db->insert_id();
    }
}
