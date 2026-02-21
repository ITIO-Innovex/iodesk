<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Employee_details_pdf extends App_pdf
{
    protected $record = [];

    public function __construct($record = [])
    {
        $this->record = is_array($record) ? $record : [];

        parent::__construct();

        $name = isset($this->record['name']) ? $this->record['name'] : 'Unknown';
        $this->SetTitle('Employee Details - ' . $name);
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
            .ref-table th { 
                background-color: #f0f0f0; 
                padding: 5px 8px; 
                text-align: left;
                border: 1px solid #ddd;
                font-size: 9px;
            }
            .ref-table td { 
                padding: 5px 8px; 
                border: 1px solid #ddd;
                font-size: 9px;
            }
        </style>';

        $html .= '<table class="header-table"><tr>';
        $html .= '<td width="30%">' . $logoUrl . '</td>';
        $html .= '<td width="70%" style="text-align: right;">';
        $html .= '<span class="title">EMPLOYEE DETAILS FORM</span><br>';
        $html .= '<span style="font-size: 10px; color: #666;">Generated on: ' . date('d-m-Y H:i') . '</span>';
        $html .= '</td></tr></table>';

        $html .= '<div class="section-title">PERSONAL INFORMATION</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Full Name</th><td>' . $this->esc($r['name'] ?? '') . '</td>';
        $html .= '<th>Email</th><td>' . $this->esc($r['email'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Contact Number</th><td>' . $this->esc($r['contact_number'] ?? '') . '</td>';
        $html .= '<th>Emergency Contact</th><td>' . $this->esc($r['emergency_contact_number'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Date of Birth</th><td>' . $this->formatDate($r['date_of_birth'] ?? '') . '</td>';
        $html .= '<th>Status</th><td>' . $this->getStatusBadge($r['status'] ?? 'Draft') . '</td></tr>';
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
        $html .= '<tr><th>Department</th><td colspan="3">' . $this->esc($r['department'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">ADDRESS INFORMATION</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Current Address</th><td colspan="3">' . $this->esc($r['current_address'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Permanent Address</th><td colspan="3">' . $this->esc($r['permanent_address'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">REFERENCES</div>';
        $html .= '<table class="info-table ref-table">';
        $html .= '<tr><th width="5%">#</th><th width="35%">Name</th><th width="30%">Relation</th><th width="30%">Contact</th></tr>';
        
        $hasReferences = false;
        for ($i = 1; $i <= 8; $i++) {
            $refName = $r['ref' . $i . '_name'] ?? '';
            $refRelation = $r['ref' . $i . '_relation'] ?? '';
            $refContact = $r['ref' . $i . '_contact'] ?? '';
            
            if (!empty($refName) || !empty($refRelation) || !empty($refContact)) {
                $hasReferences = true;
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>' . $this->esc($refName) . '</td>';
                $html .= '<td>' . $this->esc($refRelation) . '</td>';
                $html .= '<td>' . $this->esc($refContact) . '</td>';
                $html .= '</tr>';
            }
        }
        
        if (!$hasReferences) {
            $html .= '<tr><td colspan="4" style="text-align: center; color: #999;">No references provided</td></tr>';
        }
        
        $html .= '</table>';

        $html .= '<div class="section-title">DOCUMENTS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Profile Picture</th><td>' . $this->getDocStatus($r['profile_pic'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Educational Testimonials</th><td>' . $this->getDocStatus($r['educational_testimonials'] ?? '') . '</td></tr>';
        $html .= '<tr><th>ID Proof</th><td>' . $this->getDocStatus($r['id_proof'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address Proof</th><td>' . $this->getDocStatus($r['address_proof'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Previous Company Documents</th><td>' . $this->getDocStatus($r['previous_company_documents'] ?? '') . '</td></tr>';
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

    protected function getDocStatus($filePath)
    {
        if (empty($filePath)) {
            return '<span style="color: #999;">Not uploaded</span>';
        }
        $fileName = basename($filePath);
        return '<span style="color: #3c763d;">Uploaded: ' . htmlspecialchars($fileName) . '</span>';
    }

    protected function type()
    {
        return 'employee_details';
    }

    protected function file_path()
    {
        return '';
    }
}
