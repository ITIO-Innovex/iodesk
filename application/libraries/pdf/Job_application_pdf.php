<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Job_application_pdf extends App_pdf
{
    protected $record = [];

    public function __construct($record = [])
    {
        $this->record = is_array($record) ? $record : [];

        parent::__construct();

        $name = isset($this->record['full_name']) ? $this->record['full_name'] : 'Unknown';
        $this->SetTitle('Job Application - ' . $name);
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
                width: 20%;
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
            .status-interviewed { background-color: #d9edf7; color: #31708f; }
            .status-selected { background-color: #dff0d8; color: #3c763d; }
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
        </style>';

        $html .= '<table class="header-table"><tr>';
        $html .= '<td width="30%">' . $logoUrl . '</td>';
        $html .= '<td width="70%" style="text-align: right;">';
        $html .= '<span class="title">JOB APPLICATION FORM</span><br>';
        $html .= '<span style="font-size: 10px; color: #666;">Generated on: ' . date('d-m-Y H:i') . '</span>';
        $html .= '</td></tr></table>';

        $html .= '<div class="section-title">BASIC INFORMATION</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Full Name</th><td>' . $this->esc($r['full_name'] ?? '') . '</td>';
        $html .= '<th>Applied Post</th><td>' . $this->esc($r['applied_post'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Email</th><td>' . $this->esc($r['email'] ?? '') . '</td>';
        $html .= '<th>Mobile No</th><td>' . $this->esc($r['mobile_no'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Alternative No</th><td>' . $this->esc($r['alternative_no'] ?? '') . '</td>';
        $html .= '<th>Sex</th><td>' . $this->esc($r['sex'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Marital Status</th><td>' . $this->esc($r['marital_status'] ?? '') . '</td>';
        $html .= '<th>Status</th><td>' . $this->getStatusBadge($r['status'] ?? 'Draft') . '</td></tr>';
        $html .= '<tr><th>Address</th><td colspan="3">' . $this->esc($r['address_with_pincode'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">SKILLS & SOCIAL</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Major Skill 1</th><td>' . $this->esc($r['major_skill_1'] ?? '') . '</td>';
        $html .= '<th>Major Skill 2</th><td>' . $this->esc($r['major_skill_2'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Has LinkedIn</th><td>' . $this->esc($r['has_linkedin'] ?? '') . '</td>';
        $html .= '<th>LinkedIn Connections</th><td>' . $this->esc($r['linkedin_connections'] ?? '') . '</td></tr>';
        $html .= '</table>';

        $html .= '<div class="section-title">EDUCATIONAL QUALIFICATIONS</div>';
        $html .= '<table class="info-table data-table">';
        $html .= '<tr><th width="5%">#</th><th width="25%">Degree</th><th width="30%">University</th><th width="25%">Major Subject</th><th width="15%">Year</th></tr>';
        
        $hasEducation = false;
        for ($i = 1; $i <= 5; $i++) {
            $degree = $r['edu' . $i . '_degree'] ?? '';
            $university = $r['edu' . $i . '_university'] ?? '';
            $subject = $r['edu' . $i . '_major_subject'] ?? '';
            $year = $r['edu' . $i . '_year'] ?? '';
            
            if (!empty($degree) || !empty($university) || !empty($subject) || !empty($year)) {
                $hasEducation = true;
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>' . $this->esc($degree) . '</td>';
                $html .= '<td>' . $this->esc($university) . '</td>';
                $html .= '<td>' . $this->esc($subject) . '</td>';
                $html .= '<td>' . $this->esc($year) . '</td>';
                $html .= '</tr>';
            }
        }
        
        if (!$hasEducation) {
            $html .= '<tr><td colspan="5" style="text-align: center; color: #999;">No education details provided</td></tr>';
        }
        $html .= '</table>';

        $html .= '<div class="section-title">FAMILY DETAILS</div>';
        $html .= '<table class="info-table data-table">';
        $html .= '<tr><th width="5%">#</th><th width="30%">Name</th><th width="10%">Age</th><th width="25%">Relationship</th><th width="30%">Occupation</th></tr>';
        
        $hasFamily = false;
        for ($i = 1; $i <= 6; $i++) {
            $name = $r['fam' . $i . '_name'] ?? '';
            $age = $r['fam' . $i . '_age'] ?? '';
            $relationship = $r['fam' . $i . '_relationship'] ?? '';
            $occupation = $r['fam' . $i . '_occupation'] ?? '';
            
            if (!empty($name) || !empty($age) || !empty($relationship) || !empty($occupation)) {
                $hasFamily = true;
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>' . $this->esc($name) . '</td>';
                $html .= '<td>' . $this->esc($age) . '</td>';
                $html .= '<td>' . $this->esc($relationship) . '</td>';
                $html .= '<td>' . $this->esc($occupation) . '</td>';
                $html .= '</tr>';
            }
        }
        
        if (!$hasFamily) {
            $html .= '<tr><td colspan="5" style="text-align: center; color: #999;">No family details provided</td></tr>';
        }
        $html .= '</table>';

        $html .= '<div class="section-title">JOB HISTORY</div>';
        $html .= '<table class="info-table data-table">';
        $html .= '<tr><th width="5%">#</th><th width="15%">Title</th><th width="15%">Company</th><th width="12%">Designation</th><th width="10%">Start</th><th width="10%">End</th><th width="10%">Start Salary</th><th width="10%">End Salary</th><th width="13%">Reason Leaving</th></tr>';
        
        $hasJob = false;
        for ($i = 1; $i <= 2; $i++) {
            $title = $r['job' . $i . '_title'] ?? '';
            $company = $r['job' . $i . '_company'] ?? '';
            $designation = $r['job' . $i . '_designation'] ?? '';
            $startDate = $r['job' . $i . '_start_date'] ?? '';
            $endDate = $r['job' . $i . '_end_date'] ?? '';
            $startSalary = $r['job' . $i . '_start_salary'] ?? '';
            $endSalary = $r['job' . $i . '_end_salary'] ?? '';
            $reason = $r['job' . $i . '_reason_for_leaving'] ?? '';
            
            if (!empty($title) || !empty($company) || !empty($designation)) {
                $hasJob = true;
                $html .= '<tr>';
                $html .= '<td>' . $i . '</td>';
                $html .= '<td>' . $this->esc($title) . '</td>';
                $html .= '<td>' . $this->esc($company) . '</td>';
                $html .= '<td>' . $this->esc($designation) . '</td>';
                $html .= '<td>' . $this->formatDate($startDate) . '</td>';
                $html .= '<td>' . $this->formatDate($endDate) . '</td>';
                $html .= '<td>' . $this->esc($startSalary) . '</td>';
                $html .= '<td>' . $this->esc($endSalary) . '</td>';
                $html .= '<td>' . $this->esc($reason) . '</td>';
                $html .= '</tr>';
            }
        }
        
        if (!$hasJob) {
            $html .= '<tr><td colspan="9" style="text-align: center; color: #999;">No job history provided</td></tr>';
        }
        $html .= '</table>';

        $html .= '<div class="section-title">INTERVIEW / OFFER DETAILS</div>';
        $html .= '<table class="info-table">';
        $html .= '<tr><th>Coordinating Person</th><td colspan="3">' . $this->esc($r['coordinating_person_name'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Interviewed By 1</th><td>' . $this->esc($r['interviewed_by_1'] ?? '') . '</td>';
        $html .= '<th>Interviewed By 2</th><td>' . $this->esc($r['interviewed_by_2'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Interview Remarks 1</th><td colspan="3">' . $this->esc($r['interview_remarks_1'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Interview Remarks 2</th><td colspan="3">' . $this->esc($r['interview_remarks_2'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Date of Joining</th><td>' . $this->formatDateTime($r['doj_datetime'] ?? '') . '</td>';
        $html .= '<th>Offered Designation</th><td>' . $this->esc($r['offered_designation'] ?? '') . '</td></tr>';
        $html .= '<tr><th>Offered Salary</th><td colspan="3">' . $this->esc($r['offered_salary'] ?? '') . '</td></tr>';
        $html .= '</table>';

        if (!empty($r['created_at'])) {
            $html .= '<br><div style="text-align: center; font-size: 9px; color: #999;">';
            $html .= 'Application submitted on: ' . date('d-m-Y H:i', strtotime($r['created_at']));
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

    protected function formatDateTime($datetime)
    {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return '-';
        }
        return date('d-m-Y H:i', strtotime($datetime));
    }

    protected function getStatusBadge($status)
    {
        $class = 'status-draft';
        if ($status === 'Interviewed') $class = 'status-interviewed';
        if ($status === 'Selected') $class = 'status-selected';
        if ($status === 'Rejected') $class = 'status-rejected';

        return '<span class="status-badge ' . $class . '">' . htmlspecialchars($status) . '</span>';
    }

    protected function type()
    {
        return 'job_application';
    }

    protected function file_path()
    {
        return '';
    }
}
