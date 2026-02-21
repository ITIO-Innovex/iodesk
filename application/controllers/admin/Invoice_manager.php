<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_manager extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function invoice_notes()
    {
        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoice_notes');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->order_by('id', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        
        $data['title'] = 'Invoice Notes';
        $this->load->view('admin/invoice_manager/invoice_notes', $data);
    }

    public function get_invoice_note($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoice_notes');
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $record = $this->db->get()->row_array();
        
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function save_invoice_note()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = $this->input->post('id');
        $description = $this->input->post('description', false);
        $status = $this->input->post('status') ?: 1;
        $companyId = get_staff_company_id();

        if (empty(trim(strip_tags($description)))) {
            echo json_encode(['success' => false, 'message' => 'Description is required']);
            return;
        }

        $data = [
            'description' => $description,
            'status' => (int)$status,
            'company_id' => $companyId ?: 0
        ];

        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($companyId) {
                $this->db->where('company_id', $companyId);
            }
            $exists = $this->db->get(db_prefix() . 'sales_invoice_notes')->row();
            
            if (!$exists) {
                echo json_encode(['success' => false, 'message' => 'Record not found']);
                return;
            }

            $this->db->where('id', $id);
            $success = $this->db->update(db_prefix() . 'sales_invoice_notes', $data);
            $message = 'Invoice note updated successfully';
        } else {
            $success = $this->db->insert(db_prefix() . 'sales_invoice_notes', $data);
            $message = 'Invoice note added successfully';
        }

        if ($success) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save record']);
        }
    }

    public function delete_invoice_note($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();

        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        
        $success = $this->db->delete(db_prefix() . 'sales_invoice_notes');
        
        if ($success && $this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    }

    public function products()
    {
        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoices_products');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->order_by('id', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        
        $this->db->select('id, name, taxrate');
        $this->db->from(db_prefix() . 'taxes');
        $this->db->order_by('taxrate', 'ASC');
        $data['tax_rates'] = $this->db->get()->result_array();
        
        $data['title'] = 'Products';
        $this->load->view('admin/invoice_manager/products', $data);
    }

    public function get_product($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoices_products');
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $record = $this->db->get()->row_array();
        
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function save_product()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = $this->input->post('id');
        $productName = trim($this->input->post('product_name'));
        $description = trim($this->input->post('description'));
        $price = $this->input->post('price');
        $taxPercent = $this->input->post('tax_percent');
        $status = $this->input->post('status') ?: 1;
        $companyId = get_staff_company_id();

        if (empty($productName)) {
            echo json_encode(['success' => false, 'message' => 'Product name is required']);
            return;
        }

        if (!is_numeric($price) || $price < 0) {
            echo json_encode(['success' => false, 'message' => 'Valid price is required']);
            return;
        }

        $data = [
            'product_name' => $productName,
            'description' => $description,
            'price' => number_format((float)$price, 2, '.', ''),
            'tax_percent' => number_format((float)$taxPercent, 2, '.', ''),
            'status' => (int)$status,
            'company_id' => $companyId ?: 0
        ];

        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($companyId) {
                $this->db->where('company_id', $companyId);
            }
            $exists = $this->db->get(db_prefix() . 'sales_invoices_products')->row();
            
            if (!$exists) {
                echo json_encode(['success' => false, 'message' => 'Record not found']);
                return;
            }

            $this->db->where('id', $id);
            $success = $this->db->update(db_prefix() . 'sales_invoices_products', $data);
            $message = 'Product updated successfully';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $success = $this->db->insert(db_prefix() . 'sales_invoices_products', $data);
            $message = 'Product added successfully';
        }

        if ($success) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save record']);
        }
    }

    public function delete_product($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();

        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        
        $success = $this->db->delete(db_prefix() . 'sales_invoices_products');
        
        if ($success && $this->db->affected_rows() > 0) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
    }

    public function invoices($action = '', $id = '')
    {
        if ($action === 'add') {
            $this->add_invoice();
            return;
        }
        
        if ($action === 'view' && $id) {
            $this->view_invoice($id);
            return;
        }
        
        if ($action === 'edit' && $id) {
            $this->edit_invoice($id);
            return;
        }
        
        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoices');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->order_by('id', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        
        $data['title'] = 'Invoices';
        $this->load->view('admin/invoice_manager/invoices', $data);
    }

    private function view_invoice($id)
    {
        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoices');
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $invoice = $this->db->get()->row_array();
        
        if (!$invoice) {
            set_alert('danger', 'Invoice not found');
            redirect(admin_url('invoice_manager/invoices'));
        }
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoice_items');
        $this->db->where('invoice_id', $id);
        $data['items'] = $this->db->get()->result_array();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoice_payments');
        $this->db->where('invoice_id', $id);
        $this->db->order_by('payment_date', 'DESC');
        $data['payments'] = $this->db->get()->result_array();
        
        if ($invoice['payment_bank']) {
            $this->db->select('*');
            $this->db->from(db_prefix() . 'payment_modes');
            $this->db->where('id', $invoice['payment_bank']);
            $data['bank_details'] = $this->db->get()->row_array();
        } else {
            $data['bank_details'] = null;
        }
        
        $data['invoice'] = $invoice;
        $data['title'] = 'Invoice ' . $invoice['invoice_number'];
        $this->load->view('admin/invoice_manager/invoices_view', $data);
    }

    private function edit_invoice($id)
    {
        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoices');
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $invoice = $this->db->get()->row_array();
        
        if (!$invoice) {
            set_alert('danger', 'Invoice not found');
            redirect(admin_url('invoice_manager/invoices'));
        }
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoice_items');
        $this->db->where('invoice_id', $id);
        $data['items'] = $this->db->get()->result_array();
        
        $this->db->select('id, product_name, description, price, tax_percent');
        $this->db->from(db_prefix() . 'sales_invoices_products');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('status', 1);
        $this->db->order_by('product_name', 'ASC');
        $data['products'] = $this->db->get()->result_array();
        
        $this->db->select('id, name, taxrate');
        $this->db->from(db_prefix() . 'taxes');
        $this->db->order_by('taxrate', 'ASC');
        $data['tax_rates'] = $this->db->get()->result_array();
        
        $this->db->select('id, name, description');
        $this->db->from(db_prefix() . 'payment_modes');
        $this->db->where('active', 1);
		if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->order_by('name', 'ASC');
        $data['payment_modes'] = $this->db->get()->result_array();
        
        $this->db->select('description');
        $this->db->from(db_prefix() . 'sales_invoice_notes');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('status', 1);
        $data['invoice_notes'] = $this->db->get()->result_array();
        
        $data['invoice'] = $invoice;
        $data['title'] = 'Edit Invoice ' . $invoice['invoice_number'];
        $this->load->view('admin/invoice_manager/invoices_edit', $data);
    }

    private function add_invoice()
    {
        $companyId = get_staff_company_id();
        
        $this->db->select('id, product_name, description, price, tax_percent');
        $this->db->from(db_prefix() . 'sales_invoices_products');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('status', 1);
        $this->db->order_by('product_name', 'ASC');
        $data['products'] = $this->db->get()->result_array();
        
        $this->db->select('id, name, taxrate');
        $this->db->from(db_prefix() . 'taxes');
        $this->db->order_by('taxrate', 'ASC');
        $data['tax_rates'] = $this->db->get()->result_array();
        
        $this->db->select('id, name, description');
        $this->db->from(db_prefix() . 'payment_modes');
        $this->db->where('active', 1);
		if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->order_by('name', 'ASC');
        $data['payment_modes'] = $this->db->get()->result_array();
        
        $this->db->select('description');
        $this->db->from(db_prefix() . 'sales_invoice_notes');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->where('status', 1);
        $data['invoice_notes'] = $this->db->get()->result_array();
        
        $nextNumber = $this->get_next_invoice_number();
        $data['invoice_number'] = $nextNumber;
        
        $data['title'] = 'Add Invoice';
        $this->load->view('admin/invoice_manager/invoices_add', $data);
    }

    private function get_next_invoice_number()
    {
        $companyId = get_staff_company_id();
        $prefix = 'INV-';
        $year = date('Y');
        
        $this->db->select('invoice_number');
        $this->db->from(db_prefix() . 'sales_invoices');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->like('invoice_number', $prefix . $year, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();
        
        if ($last && preg_match('/(\d+)$/', $last->invoice_number, $matches)) {
            $nextNum = (int)$matches[1] + 1;
        } else {
            $nextNum = 1;
        }
        
        return $prefix . $year . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
    }

    public function get_product_details($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        
        $this->db->select('id, product_name, description, price, tax_percent');
        $this->db->from(db_prefix() . 'sales_invoices_products');
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $record = $this->db->get()->row_array();
        
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Product not found']);
        }
    }

    public function save_invoice()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        
        $invoiceNumber = trim($this->input->post('invoice_number'));
        $companyName = trim($this->input->post('company_name'));
        $contactPerson = trim($this->input->post('contact_person'));
        $contactEmail = trim($this->input->post('contact_email'));
        $contactPhone = trim($this->input->post('contact_phone'));
        $addressLine1 = trim($this->input->post('contact_address_line1'));
        $addressLine2 = trim($this->input->post('contact_address_line2'));
        $invoiceDate = $this->input->post('invoice_date');
        $dueDate = $this->input->post('due_date');
        $subtotal = (float)$this->input->post('subtotal');
        $discount = (float)$this->input->post('discount');
        $taxAmount = (float)$this->input->post('tax_amount');
        $totalAmount = (float)$this->input->post('total_amount');
        $paidAmount = (float)$this->input->post('paid_amount');
        $paymentBank = $this->input->post('payment_bank') ?: null;
        $status = $this->input->post('status') ?: 'Draft';
        $notes = $this->input->post('notes', false);
        
        $items = $this->input->post('items');

        if (empty($invoiceNumber)) {
            echo json_encode(['success' => false, 'message' => 'Invoice number is required']);
            return;
        }

        if (empty($invoiceDate)) {
            echo json_encode(['success' => false, 'message' => 'Invoice date is required']);
            return;
        }

        if (empty($items) || !is_array($items)) {
            echo json_encode(['success' => false, 'message' => 'At least one item is required']);
            return;
        }

        $this->db->trans_start();

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'company_name' => $companyName,
            'contact_person' => $contactPerson,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'contact_address_line1' => $addressLine1,
            'contact_address_line2' => $addressLine2,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate ?: $invoiceDate,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'discount' => number_format($discount, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'total_amount' => number_format($totalAmount, 2, '.', ''),
            'paid_amount' => number_format($paidAmount, 2, '.', ''),
            'payment_bank' => $paymentBank,
            'status' => $status,
            'notes' => $notes,
            'company_id' => $companyId ?: 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert(db_prefix() . 'sales_invoices', $invoiceData);
        $invoiceId = $this->db->insert_id();
log_message('error', 'invoiceId - '.$invoiceId );
log_message('error', 'QUERY - '.$this->db->last_query(); );
        if (!$invoiceId) {
            $this->db->trans_rollback();
            echo json_encode(['success' => false, 'message' => 'Failed to create invoice']);
            return;
        }

        foreach ($items as $item) {
            if (empty(trim($item['item_name']))) {
                continue;
            }
            
            $qty = (float)($item['quantity'] ?? 1);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            $taxPct = (float)($item['tax_percent'] ?? 0);
            $lineTotal = $qty * $unitPrice * (1 + $taxPct / 100);
            
            $itemData = [
                'invoice_id' => $invoiceId,
                'item_name' => trim($item['item_name']),
                'description' => trim($item['description'] ?? ''),
                'quantity' => number_format($qty, 2, '.', ''),
                'unit_price' => number_format($unitPrice, 2, '.', ''),
                'tax_percent' => number_format($taxPct, 2, '.', ''),
                'total' => number_format($lineTotal, 2, '.', '')
            ];
            
            $this->db->insert(db_prefix() . 'sales_invoice_items', $itemData);
        }

        //if ($paidAmount > 0) {
            $paymentData = [
                'invoice_id' => $invoiceId,
                'payment_date' => $invoiceDate,
                'amount' => number_format($paidAmount, 2, '.', ''),
                'payment_method' => $paymentBank ? 'Bank Transfer' : 'Cash',
                'transaction_id' => '',
                'notes' => 'Initial payment',
                'company_id' => $companyId ?: 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert(db_prefix() . 'sales_invoice_payments', $paymentData);
        //}

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to save invoice']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Invoice created successfully', 'invoice_id' => $invoiceId]);
        }
    }

    public function delete_invoice($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();

        $this->db->trans_start();

        $this->db->where('invoice_id', $id);
        $this->db->delete(db_prefix() . 'sales_invoice_items');

        $this->db->where('invoice_id', $id);
        $this->db->delete(db_prefix() . 'sales_invoice_payments');

        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->delete(db_prefix() . 'sales_invoices');

        $this->db->trans_complete();

        if ($this->db->trans_status() !== false) {
            echo json_encode(['success' => true, 'message' => 'Invoice deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete invoice']);
        }
    }

    public function update_invoice()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        $invoiceId = (int)$this->input->post('invoice_id');
        
        if (!$invoiceId) {
            echo json_encode(['success' => false, 'message' => 'Invalid invoice']);
            return;
        }

        $this->db->where('id', $invoiceId);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $exists = $this->db->get(db_prefix() . 'sales_invoices')->row();
        
        if (!$exists) {
            echo json_encode(['success' => false, 'message' => 'Invoice not found']);
            return;
        }
        
        $invoiceNumber = trim($this->input->post('invoice_number'));
        $companyName = trim($this->input->post('company_name'));
        $contactPerson = trim($this->input->post('contact_person'));
        $contactEmail = trim($this->input->post('contact_email'));
        $contactPhone = trim($this->input->post('contact_phone'));
        $addressLine1 = trim($this->input->post('contact_address_line1'));
        $addressLine2 = trim($this->input->post('contact_address_line2'));
        $invoiceDate = $this->input->post('invoice_date');
        $dueDate = $this->input->post('due_date');
        $subtotal = (float)$this->input->post('subtotal');
        $discount = (float)$this->input->post('discount');
        $taxAmount = (float)$this->input->post('tax_amount');
        $totalAmount = (float)$this->input->post('total_amount');
        $paidAmount = (float)$this->input->post('paid_amount');
        $paymentBank = $this->input->post('payment_bank') ?: null;
        $status = $this->input->post('status') ?: 'Draft';
        $notes = $this->input->post('notes', false);
        
        $items = $this->input->post('items');

        if (empty($invoiceNumber)) {
            echo json_encode(['success' => false, 'message' => 'Invoice number is required']);
            return;
        }

        if (empty($invoiceDate)) {
            echo json_encode(['success' => false, 'message' => 'Invoice date is required']);
            return;
        }

        if (empty($items) || !is_array($items)) {
            echo json_encode(['success' => false, 'message' => 'At least one item is required']);
            return;
        }

        $this->db->trans_start();

        $invoiceData = [
            'invoice_number' => $invoiceNumber,
            'company_name' => $companyName,
            'contact_person' => $contactPerson,
            'contact_email' => $contactEmail,
            'contact_phone' => $contactPhone,
            'contact_address_line1' => $addressLine1,
            'contact_address_line2' => $addressLine2,
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate ?: $invoiceDate,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'discount' => number_format($discount, 2, '.', ''),
            'tax_amount' => number_format($taxAmount, 2, '.', ''),
            'total_amount' => number_format($totalAmount, 2, '.', ''),
            'paid_amount' => number_format($paidAmount, 2, '.', ''),
            'payment_bank' => $paymentBank,
            'status' => $status,
            'notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $invoiceId);
        $this->db->update(db_prefix() . 'sales_invoices', $invoiceData);

        $this->db->where('invoice_id', $invoiceId);
        $this->db->delete(db_prefix() . 'sales_invoice_items');

        foreach ($items as $item) {
            if (empty(trim($item['item_name']))) {
                continue;
            }
            
            $qty = (float)($item['quantity'] ?? 1);
            $unitPrice = (float)($item['unit_price'] ?? 0);
            $taxPct = (float)($item['tax_percent'] ?? 0);
            $lineTotal = $qty * $unitPrice * (1 + $taxPct / 100);
            
            $itemData = [
                'invoice_id' => $invoiceId,
                'item_name' => trim($item['item_name']),
                'description' => trim($item['description'] ?? ''),
                'quantity' => number_format($qty, 2, '.', ''),
                'unit_price' => number_format($unitPrice, 2, '.', ''),
                'tax_percent' => number_format($taxPct, 2, '.', ''),
                'total' => number_format($lineTotal, 2, '.', '')
            ];
            
            $this->db->insert(db_prefix() . 'sales_invoice_items', $itemData);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            echo json_encode(['success' => false, 'message' => 'Failed to update invoice']);
        } else {
            echo json_encode(['success' => true, 'message' => 'Invoice updated successfully', 'invoice_id' => $invoiceId]);
        }
    }

    public function payments()
    {
        $companyId = get_staff_company_id();
        
        $this->db->select('p.*, i.invoice_number, i.company_name, i.total_amount');
        $this->db->from(db_prefix() . 'sales_invoice_payments p');
        $this->db->join(db_prefix() . 'sales_invoices i', 'i.id = p.invoice_id', 'left');
        if ($companyId) {
            $this->db->where('p.company_id', $companyId);
        }
        $this->db->order_by('p.id', 'DESC');
        $data['records'] = $this->db->get()->result_array();
        
        $this->db->select('id, invoice_number, company_name, total_amount, paid_amount');
        $this->db->from(db_prefix() . 'sales_invoices');
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $this->db->order_by('invoice_number', 'DESC');
        $data['invoices'] = $this->db->get()->result_array();
        
        $data['title'] = 'Payments';
        $this->load->view('admin/invoice_manager/payments', $data);
    }

    public function get_payment($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'sales_invoice_payments');
        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $record = $this->db->get()->row_array();
        
        if ($record) {
            echo json_encode(['success' => true, 'data' => $record]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Record not found']);
        }
    }

    public function save_payment()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = $this->input->post('id');
        $invoiceId = (int)$this->input->post('invoice_id');
        $paymentDate = $this->input->post('payment_date');
        $amount = (float)$this->input->post('amount');
        $paymentMethod = trim($this->input->post('payment_method'));
        $transactionId = trim($this->input->post('transaction_id'));
        $notes = trim($this->input->post('notes'));
        $companyId = get_staff_company_id();

        if (empty($invoiceId)) {
            echo json_encode(['success' => false, 'message' => 'Invoice is required']);
            return;
        }

        if (empty($paymentDate)) {
            echo json_encode(['success' => false, 'message' => 'Payment date is required']);
            return;
        }

        if ($amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Valid amount is required']);
            return;
        }

        $data = [
            'invoice_id' => $invoiceId,
            'payment_date' => $paymentDate,
            'amount' => number_format($amount, 2, '.', ''),
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'notes' => $notes,
            'company_id' => $companyId ?: 0
        ];

        $this->db->trans_start();

        if (!empty($id)) {
            $this->db->where('id', $id);
            if ($companyId) {
                $this->db->where('company_id', $companyId);
            }
            $exists = $this->db->get(db_prefix() . 'sales_invoice_payments')->row();
            
            if (!$exists) {
                echo json_encode(['success' => false, 'message' => 'Record not found']);
                return;
            }

            $this->db->where('id', $id);
            $this->db->update(db_prefix() . 'sales_invoice_payments', $data);
            $message = 'Payment updated successfully';
        } else {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert(db_prefix() . 'sales_invoice_payments', $data);
            $message = 'Payment added successfully';
        }

        $this->update_invoice_paid_amount($invoiceId);

        $this->db->trans_complete();

        if ($this->db->trans_status() !== false) {
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to save payment']);
        }
    }

    public function delete_payment($id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $companyId = get_staff_company_id();

        $this->db->where('id', $id);
        if ($companyId) {
            $this->db->where('company_id', $companyId);
        }
        $payment = $this->db->get(db_prefix() . 'sales_invoice_payments')->row();

        if (!$payment) {
            echo json_encode(['success' => false, 'message' => 'Payment not found']);
            return;
        }

        $invoiceId = $payment->invoice_id;

        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'sales_invoice_payments');

        $this->update_invoice_paid_amount($invoiceId);

        $this->db->trans_complete();

        if ($this->db->trans_status() !== false) {
            echo json_encode(['success' => true, 'message' => 'Payment deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete payment']);
        }
    }

    private function update_invoice_paid_amount($invoiceId)
    {
        $this->db->select('SUM(amount) as total_paid');
        $this->db->from(db_prefix() . 'sales_invoice_payments');
        $this->db->where('invoice_id', $invoiceId);
        $result = $this->db->get()->row();
        
        $totalPaid = $result ? (float)$result->total_paid : 0;

        $this->db->select('total_amount');
        $this->db->from(db_prefix() . 'sales_invoices');
        $this->db->where('id', $invoiceId);
        $invoice = $this->db->get()->row();

        $totalAmount = $invoice ? (float)$invoice->total_amount : 0;

        $status = 'Unpaid';
        if ($totalPaid >= $totalAmount && $totalAmount > 0) {
            $status = 'Paid';
        } elseif ($totalPaid > 0) {
            $status = 'Partially Paid';
        }

        $this->db->where('id', $invoiceId);
        $this->db->update(db_prefix() . 'sales_invoices', [
            'paid_amount' => number_format($totalPaid, 2, '.', ''),
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
