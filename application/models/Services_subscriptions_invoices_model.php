<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Services_subscriptions_invoices_model extends App_Model
{
    private $table = 'it_crm_services_subscriptions_invoices';

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
            log_activity('Subscription Invoice Added [Invoice: ' . $insert['invoice_no'] . ', ID: ' . $id . ']');
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
            log_activity('Subscription Invoice Updated [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        if ($this->db->affected_rows() > 0) {
            log_activity('Subscription Invoice Deleted [ID: ' . $id . ']');
            return true;
        }
        return false;
    }

    private function normalize_payload($data, $requireAll = true)
    {
        $invoice_no = trim($data['invoice_no'] ?? '');
        $company_id = isset($data['company_id']) ? (int) $data['company_id'] : 0;
        $subscription_id = isset($data['subscription_id']) ? (int) $data['subscription_id'] : 0;
        $amount = isset($data['amount']) ? (float) $data['amount'] : null;
        $currency = trim($data['currency'] ?? 'INR');
        $tax = isset($data['tax']) ? (float) $data['tax'] : 0.00;
        $total_amount = isset($data['total_amount']) ? (float) $data['total_amount'] : null;
        $invoice_date = trim($data['invoice_date'] ?? '');
        $due_date = trim($data['due_date'] ?? '');
        $payment_status = $data['payment_status'] ?? 'unpaid';
        $payment_method = $data['payment_method'] ?? null;

        $allowed_status = ['paid', 'unpaid', 'failed'];

        if ($requireAll) {
            if ($invoice_no === '' || $company_id <= 0 || $subscription_id <= 0 || $amount === null || $currency === '' || $total_amount === null || $invoice_date === '') {
                return false;
            }
        }

        if (!in_array($payment_status, $allowed_status, true)) {
            $payment_status = 'unpaid';
        }

        return [
            'invoice_no' => $invoice_no,
            'company_id' => $company_id,
            'subscription_id' => $subscription_id,
            'amount' => $amount ?? 0,
            'currency' => $currency,
            'tax' => $tax,
            'total_amount' => $total_amount ?? 0,
            'invoice_date' => $invoice_date,
            'due_date' => $due_date !== '' ? $due_date : null,
            'payment_status' => $payment_status,
            'payment_method' => $payment_method !== '' ? $payment_method : null,
        ];
    }
}
