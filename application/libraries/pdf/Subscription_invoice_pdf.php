<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Subscription_invoice_pdf extends App_pdf
{
    protected $invoice;

    public function __construct($invoice, $tag = '')
    {
        $this->invoice = $invoice;
        $this->tag     = $tag;

        parent::__construct();

        $invoiceNo = is_array($invoice) ? ($invoice['invoice_no'] ?? '') : '';
        $this->SetTitle('Subscription Invoice ' . ($invoiceNo !== '' ? '#' . $invoiceNo : ''));
    }

    public function prepare()
    {
        $this->set_view_vars([
            'invoice' => $this->invoice,
        ]);

        return $this->build();
    }

    protected function type()
    {
        return 'subscription_invoice';
    }

    protected function file_path()
    {
        $customPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/my_subscription_invoicepdf.php';
        $actualPath = APPPATH . 'views/themes/' . active_clients_theme() . '/views/subscription_invoicepdf.php';

        if (file_exists($customPath)) {
            $actualPath = $customPath;
        }

        return $actualPath;
    }
}
