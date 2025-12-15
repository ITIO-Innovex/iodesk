<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Salary_slip_pdf extends App_pdf
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = is_array($data) ? $data : [];
        parent::__construct();

        $staffName = isset($this->data['slip']) ? trim(($this->data['slip']['firstname'] ?? '') . ' ' . ($this->data['slip']['lastname'] ?? '')) : '';
        $monthText = isset($this->data['month']) ? date('F Y', strtotime($this->data['month'] . '-01')) : date('F Y');
        $title = 'Salary Slip - ' . ($staffName ?: 'Staff') . ' - ' . $monthText;
        $this->SetTitle($title);
    }

    public function prepare()
    {
        $html = $this->ci->load->view('admin/payroll/setting/salary_slip_pdf', $this->data, true);
        $this->writeHTML($html, true, false, true, false, '');
        return $this;
    }

    protected function type()
    {
        return 'salary_slip';
    }

    protected function file_path()
    {
        return '';
    }
}
