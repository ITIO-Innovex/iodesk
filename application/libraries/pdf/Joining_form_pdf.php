<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Joining_form_pdf extends App_pdf
{
    protected $record = [];

    public function __construct($record = [])
    {
        $this->record = is_array($record) ? $record : [];

        parent::__construct();

        $name = isset($this->record['name']) ? $this->record['name'] : 'Unknown';
        $this->SetTitle('Joining Form - ' . $name);
    }

    public function prepare()
    {
        $html = $this->buildHtml();
        $this->writeHTML($html, true, false, true, false, '');

        return $this;
    }

    protected function buildHtml()
    {
        $r = $this->record;
        $logoUrl = pdf_logo_url();

        $html = '<style>
            .section-title { 
                background-color: #f5f5f5; 
                padding: 8px 10px; 
                font-weight: bold; 
                font-size: 12px;
                border-bottom: 2px solid #333;
                margin-top: 15px;
                margin-bottom: 10px;
            }
            .info-table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 10px;
            }
            .info-table th { 
                background-color: #f9f9f9; 
                padding: 6px 8px; 
                text-align: left; 
                font-weight: bold;
                width: 25%;
                border: 1px solid #ddd;
                font-size: 10px;
            }
            .info-table td { 
                padding: 6px 8px; 
                border: 1px solid #ddd;
                font-size: 10px;
            }
            .header-table { 
                width: 100%; 
                margin-bottom: 20px; 
            }
            .title { 
                font-size: 18px; 
                font-weight: bold; 
                text-align: center;
                padding: 10px 0;
            }
            .status-badge {
                padding: 3px 8px;
                border-radius: 3px;
                font-size: 10px;
                font-weight: bold;
            }
            .status-submitted { background-color: #d9edf7; color: #31708f; }
            .status-approved { background-color: #dff0d8; color: #3c763d; }
            .status-rejected { background-color: #f2dede; color: #a94442; }
            .status-draft { background-color: #fcf8e3; color: #8a6d3b; }
        </style>';

        $html .= '<table class="header-table"><tr>';
        $html .= '<td width="30%">' . $logoUrl . '</td>';
        $html .= '<td width="70%" style="text-align: right;">';
        $html .= '<span class="title">JOINING FORM</span><br>';
        $html .= '<span style="font-size: 10px; color: #666;">Generated on: ' . date('d-m-Y H:i') . '</span>';
        $html .= '</td></tr></table>';

        $html .= '<div class="section-title">PERSONAL INFORMATION</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Full Name</th><td>' . $this->esc($r['name'] ?? '') . '</td>';
        $html .= '<th>Father / Husband Name</th><td>' . $this->esc($r['father_husband_name'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Email</th><td>' . $this->esc($r['email'] ?? '') . '</td>';
        $html .= '<th>Contact Number</th><td>' . $this->esc($r['contact_number'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Emergency Contact</th><td>' . $this->esc($r['emergency_contact_number'] ?? '') . '</td>';
        $html .= '<th>Date of Birth</th><td>' . $this->formatDate($r['date_of_birth'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">IDENTITY DETAILS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>PAN Number</th><td>' . $this->esc($r['pan_number'] ?? '') . '</td>';
        $html .= '<th>Aadhaar Number</th><td>' . $this->esc($r['aadhaar_number'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">EMPLOYMENT DETAILS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Date of Joining</th><td>' . $this->formatDate($r['date_of_joining'] ?? '') . '</td>';
        $html .= '<th>Designation</th><td>' . $this->esc($r['assigned_designation'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Department</th><td>' . $this->esc($r['department'] ?? '') . '</td>';
        $html .= '<th>Status</th><td>' . $this->getStatusBadge($r['status'] ?? 'Draft') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">CURRENT ADDRESS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Address Line 1</th><td colspan="3">' . $this->esc($r['current_address_line1'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address Line 2</th><td colspan="3">' . $this->esc($r['current_address_line2'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address Line 3</th><td colspan="3">' . $this->esc($r['current_address_line3'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">PERMANENT ADDRESS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Address Line 1</th><td colspan="3">' . $this->esc($r['permanent_address_line1'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address Line 2</th><td colspan="3">' . $this->esc($r['permanent_address_line2'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address Line 3</th><td colspan="3">' . $this->esc($r['permanent_address_line3'] ?? '') . '</td></tr>';
        $html .= '</table>';

        if (!empty($r['created_at'])) {
            $html .= '<br><div style="text-align: center; font-size: 9px; color: #999;">';
            $html .= 'Form submitted on: ' . date('d-m-Y H:i', strtotime($r['created_at']));
            $html .= '</div>';
        }

        return $html;
    }

    protected function esc($value)
    {
        return $value === null || $value === '' ? '-' : htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    protected function formatDate($date)
    {
        if (empty($date) || $date === '0000-00-00') {
            return '-';
        }
        return date('d-m-Y', strtotime($date));
    }

    protected function getStatusBadge($status)
    {
        $class = 'status-draft';
        if ($status === 'Submitted') $class = 'status-submitted';
        if ($status === 'Approved') $class = 'status-approved';
        if ($status === 'Rejected') $class = 'status-rejected';

        return '<span class="status-badge ' . $class . '">' . htmlspecialchars($status) . '</span>';
    }

    protected function type()
    {
        return 'joining_form';
    }

    protected function file_path()
    {
        return '';
    }
}
