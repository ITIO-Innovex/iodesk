<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(__DIR__ . '/App_pdf.php');

class Attendance_pdf extends App_pdf
{
    /**
     * Raw attendance data array coming from the controller
     * (calendar, shift_details, filters, status_counter, etc.)
     *
     * @var array
     */
    protected $data = [];

    public function __construct($data = [])
    {
        $this->data = is_array($data) ? $data : [];

        parent::__construct();

        // Set a reasonable title
        $monthYear = isset($this->data['filters']['month_year']) && $this->data['filters']['month_year']
            ? $this->data['filters']['month_year']
            : date('Y-m');

        $this->SetTitle('Attendance - ' . $monthYear);
    }

    /**
     * Prepare the PDF by rendering the attendance view into HTML and writing it to TCPDF.
     *
     * @return mixed
     */
    public function prepare()
    {
        // Build the variables that the view expects
        $vars = [
            'calendar'       => isset($this->data['calendar']) ? $this->data['calendar'] : null,
            'shift_details'  => isset($this->data['shift_details']) ? $this->data['shift_details'] : [],
            'filters'        => isset($this->data['filters']) ? $this->data['filters'] : [],
            'status_counter' => isset($this->data['status_counter']) ? $this->data['status_counter'] : [],
        ];

        // Render the view to a string (no direct output)
        $html = $this->ci->load->view('admin/hrd/attendance_pdf', $vars, true);

        // Write the HTML into the PDF
        $this->writeHTML($html, true, false, true, false, '');

        return $this;
    }

    /**
     * Document type identifier used by the PDF system.
     *
     * @return string
     */
    protected function type()
    {
        return 'attendance';
    }

    /**
     * Required by the abstract App_pdf, but not used because we render the view manually in prepare().
     *
     * @return string
     */
    protected function file_path()
    {
        return '';
    }
}


