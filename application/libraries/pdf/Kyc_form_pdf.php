<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Kyc_form_pdf extends App_pdf
{
    protected $record = [];

    public function __construct($record = [])
    {
        $this->record = is_array($record) ? $record : [];

        parent::__construct();

        $name = isset($this->record['candidate_name']) ? $this->record['candidate_name'] : 'Unknown';
        $this->SetTitle('KYC Form - ' . $name);
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
                padding: 5px 8px; 
                text-align: left; 
                font-weight: bold;
                width: 22%;
                border: 1px solid #ddd;
                font-size: 9px;
            }
            .info-table td { 
                padding: 5px 8px; 
                border: 1px solid #ddd;
                font-size: 9px;
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
            .status-verified { background-color: #dff0d8; color: #3c763d; }
            .status-rejected { background-color: #f2dede; color: #a94442; }
            .status-draft { background-color: #fcf8e3; color: #8a6d3b; }
            .data-table th { 
                background-color: #f0f0f0; 
                padding: 4px 6px; 
                text-align: left;
                border: 1px solid #ddd;
                font-size: 8px;
            }
            .data-table td { 
                padding: 4px 6px; 
                border: 1px solid #ddd;
                font-size: 8px;
            }
            .subsection { 
                font-weight: bold; 
                font-size: 10px; 
                margin-top: 10px;
                margin-bottom: 5px;
                color: #333;
            }
        </style>';

        $html .= '<table class="header-table"><tr>';
        $html .= '<td width="30%">' . $logoUrl . '</td>';
        $html .= '<td width="70%" style="text-align: right;">';
        $html .= '<span class="title">KYC VERIFICATION FORM</span><br>';
        $html .= '<span style="font-size: 10px; color: #666;">Generated on: ' . date('d-m-Y H:i') . '</span>';
        $html .= '</td></tr></table>';

        $html .= '<div class="section-title">BASIC INFORMATION</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Candidate Name</th><td>' . $this->esc($r['candidate_name'] ?? '') . '</td>';
        $html .= '<th>Father Name</th><td>' . $this->esc($r['father_name'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Mother Name</th><td>' . $this->esc($r['mother_name'] ?? '') . '</td>';
        $html .= '<th>Date of Birth</th><td>' . $this->formatDate($r['date_of_birth'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Marital Status</th><td>' . $this->esc($r['marital_status'] ?? '') . '</td>';
        $html .= '<th>Status</th><td>' . $this->getStatusBadge($r['status'] ?? 'Draft') . '</td></tr>';
        $html .= '<tr><th>Email</th><td>' . $this->esc($r['email'] ?? '') . '</td>';
        $html .= '<th>Contact Number</th><td>' . $this->esc($r['contact_number'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Alternate Contact</th><td>' . $this->esc($r['alternate_contact_number'] ?? '') . '</td>';
        $html .= '<th>Aadhaar Number</th><td>' . $this->esc($r['aadhaar_number'] ?? '') . '</td></tr>';
        $html .= '<tr><th>PAN Number</th><td colspan="3">' . $this->esc($r['pan_number'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">PRESENT ADDRESS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Complete Address</th><td colspan="3">' . $this->esc($r['present_complete_address'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Landmark</th><td>' . $this->esc($r['present_landmark'] ?? '') . '</td>';
        $html .= '<th>City</th><td>' . $this->esc($r['present_city'] ?? '') . '</td></tr>';
        $html .= '<tr><th>State</th><td>' . $this->esc($r['present_state'] ?? '') . '</td>';
        $html .= '<th>Pin Code</th><td>' . $this->esc($r['present_pin_code'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Police Station</th><td>' . $this->esc($r['present_police_station'] ?? '') . '</td>';
        $html .= '<th>Stay Period</th><td>' . $this->esc($r['present_stay_from'] ?? '') . ' - ' . $this->esc($r['present_stay_to'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">PERMANENT ADDRESS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Complete Address</th><td colspan="3">' . $this->esc($r['permanent_complete_address'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Landmark</th><td>' . $this->esc($r['permanent_landmark'] ?? '') . '</td>';
        $html .= '<th>City</th><td>' . $this->esc($r['permanent_city'] ?? '') . '</td></tr>';
        $html .= '<tr><th>State</th><td>' . $this->esc($r['permanent_state'] ?? '') . '</td>';
        $html .= '<th>Pin Code</th><td>' . $this->esc($r['permanent_pin_code'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Police Station</th><td>' . $this->esc($r['permanent_police_station'] ?? '') . '</td>';
        $html .= '<th>Stay Period</th><td>' . $this->esc($r['permanent_stay_from'] ?? '') . ' - ' . $this->esc($r['permanent_stay_to'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">EDUCATIONAL QUALIFICATIONS</div>';
        $html .= '<table class="info-table data-table">';
        $html .= '<tr><th width="5%">#</th><th width="30%">Institute Name</th><th width="25%">Course</th><th width="10%">Year</th><th width="15%">Reg. No.</th><th width="15%">Mode</th></tr>';
        
        $hasEducation = false;
        for ($i = 1; $i <= 3; $i++) {
            $institute = $r['edu' . $i . '_institute_name'] ?? '';
            $course = $r['edu' . $i . '_course_name'] ?? '';
            $year = $r['edu' . $i . '_passing_year'] ?? '';
            $regNo = $r['edu' . $i . '_registration_number'] ?? '';
            $mode = $r['edu' . $i . '_mode'] ?? '';
            
            if (!empty($institute) || !empty($course) || !empty($year)) {
                $hasEducation = true;
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>' . $this->esc($institute) . '</td>';
                $html .= '<td>' . $this->esc($course) . '</td>';
                $html .= '<td>' . $this->esc($year) . '</td>';
                $html .= '<td>' . $this->esc($regNo) . '</td>';
                $html .= '<td>' . $this->esc($mode) . '</td>';
                $html .= '</tr>';
            }
        }
        
        if (!$hasEducation) {
            $html .= '<tr><td colspan="6" style="text-align: center; color: #999;">No education details provided</td></tr>';
        }
        $html .= '</table>';

        $html .= '<div class="section-title">PREVIOUS ORGANIZATION</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Organization Name</th><td colspan="3">' . $this->esc($r['org1_name'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address</th><td colspan="3">' . $this->esc($r['org1_address'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Designation</th><td>' . $this->esc($r['org1_designation'] ?? '') . '</td>';
        $html .= '<th>Employee Code</th><td>' . $this->esc($r['org1_employee_code'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Date of Joining</th><td>' . $this->formatDate($r['org1_date_of_joining'] ?? '') . '</td>';
        $html .= '<th>Last Working Day</th><td>' . $this->formatDate($r['org1_last_working_day'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Salary CTC</th><td>' . $this->esc($r['org1_salary_ctc'] ?? '') . '</td>';
        $html .= '<th>Reason for Leaving</th><td>' . $this->esc($r['org1_reason_for_leaving'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="subsection">Reporting Manager</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Name</th><td>' . $this->esc($r['org1_reporting_manager_name'] ?? '') . '</td>';
        $html .= '<th>Contact</th><td>' . $this->esc($r['org1_reporting_manager_contact'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Email</th><td colspan="3">' . $this->esc($r['org1_reporting_manager_email'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="subsection">HR Contact 1</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Name</th><td>' . $this->esc($r['org1_hr1_name'] ?? '') . '</td>';
        $html .= '<th>Contact</th><td>' . $this->esc($r['org1_hr1_contact'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Email</th><td colspan="3">' . $this->esc($r['org1_hr1_email'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="subsection">HR Contact 2</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Name</th><td>' . $this->esc($r['org1_hr2_name'] ?? '') . '</td>';
        $html .= '<th>Contact</th><td>' . $this->esc($r['org1_hr2_contact'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Email</th><td colspan="3">' . $this->esc($r['org1_hr2_email'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">REFEREES</div>';
        $html .= '<table class="info-table data-table">';
        $html .= '<tr><th width="5%">#</th><th width="20%">Name</th><th width="20%">Organization</th><th width="15%">Designation</th><th width="20%">Contact</th><th width="20%">Email</th></tr>';
        
        $hasReferee = false;
        for ($i = 1; $i <= 2; $i++) {
            $name = $r['referee' . $i . '_name'] ?? '';
            $org = $r['referee' . $i . '_organization'] ?? '';
            $designation = $r['referee' . $i . '_designation'] ?? '';
            $contact = $r['referee' . $i . '_contact'] ?? '';
            $email = $r['referee' . $i . '_email'] ?? '';
            
            if (!empty($name) || !empty($org) || !empty($contact)) {
                $hasReferee = true;
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>' . $this->esc($name) . '</td>';
                $html .= '<td>' . $this->esc($org) . '</td>';
                $html .= '<td>' . $this->esc($designation) . '</td>';
                $html .= '<td>' . $this->esc($contact) . '</td>';
                $html .= '<td>' . $this->esc($email) . '</td>';
                $html .= '</tr>';
            }
        }
        
        if (!$hasReferee) {
            $html .= '<tr><td colspan="6" style="text-align: center; color: #999;">No referees provided</td></tr>';
        }
        $html .= '</table>';

        $html .= '<div class="section-title">VERIFICATION DOCUMENTS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Education Verification</th><td>' . $this->getDocStatus($r['education_verification_doc'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Employment Verification</th><td>' . $this->getDocStatus($r['employment_verification_doc'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Address/Criminal Verification</th><td>' . $this->getDocStatus($r['address_criminal_verification_doc'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Identity Verification</th><td>' . $this->getDocStatus($r['identity_verification_doc'] ?? '') . '</td></tr>';
        $html .= '<tr><th>CIBIL Verification</th><td>' . $this->getDocStatus($r['cibil_verification_doc'] ?? '') . '</td></tr>';
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
        if ($status === 'Verified') $class = 'status-verified';
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
        return 'kyc_form';
    }

    protected function file_path()
    {
        return '';
    }
}
