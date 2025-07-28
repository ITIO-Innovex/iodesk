<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *  Leads conversions monthly report
     * @param   mixed $month  which month / chart
     * @return  array          chart data
     */
    public function leads_monthly_report($month)
    {
        $result      = $this->db->query('select last_status_change from ' . db_prefix() . 'leads where MONTH(last_status_change) = ' . $month . ' AND status = 1 and lost = 0')->result_array();
        $month_dates = [];
        $data        = [];
        for ($d = 1; $d <= 31; $d++) {
            $time = mktime(12, 0, 0, $month, $d, date('Y'));
            if (date('m', $time) == $month) {
                $month_dates[] = _d(date('Y-m-d', $time));
                $data[]        = 0;
            }
        }
        $chart = [
            'labels'   => $month_dates,
            'datasets' => [
                [
                    'label'           => _l('leads'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor'     => '#c53da9',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $data,
                ],
            ],
        ];
        foreach ($result as $lead) {
            $i = 0;
            foreach ($chart['labels'] as $date) {
                if (_d(date('Y-m-d', strtotime($lead['last_status_change']))) == $date) {
                    $chart['datasets'][0]['data'][$i]++;
                }
                $i++;
            }
        }

        return $chart;
    }

    public function get_stats_chart_data($label, $where, $dataset_options, $year)
    {
        $chart = [
            'labels'   => [],
            'datasets' => [
                [
                    'label'       => $label,
                    'borderWidth' => 1,
                    'tension'     => false,
                    'data'        => [],
                ],
            ],
        ];

        foreach ($dataset_options as $key => $val) {
            $chart['datasets'][0][$key] = $val;
        }
        $this->load->model('expenses_model');
        $categories = $this->expenses_model->get_category();
        foreach ($categories as $category) {
            $_where['category']   = $category['id'];
            $_where['YEAR(date)'] = $year;
            if (count($where) > 0) {
                foreach ($where as $key => $val) {
                    $_where[$key] = $this->db->escape_str($val);
                }
            }
            array_push($chart['labels'], $category['name']);
            array_push($chart['datasets'][0]['data'], total_rows(db_prefix() . 'expenses', $_where));
        }

        return $chart;
    }

    public function get_expenses_vs_income_report($year = '')
    {
        $this->load->model('expenses_model');

        $months_labels  = [];
        $total_expenses = [];
        $total_income   = [];
        $i              = 0;
        if (!is_numeric($year)) {
            $year = date('Y');
        }
        for ($m = 1; $m <= 12; $m++) {
            array_push($months_labels, _l(date('F', mktime(0, 0, 0, $m, 1))));
            $this->db->select('id')->from(db_prefix() . 'expenses')->where('MONTH(date)', $m)->where('YEAR(date)', $year);
            $expenses = $this->db->get()->result_array();
            if (!isset($total_expenses[$i])) {
                $total_expenses[$i] = [];
            }
            if (count($expenses) > 0) {
                foreach ($expenses as $expense) {
                    $expense = $this->expenses_model->get($expense['id']);
                    $total   = $expense->amount;
                    // Check if tax is applied
                    if ($expense->tax != 0) {
                        $total += ($total / 100 * $expense->taxrate);
                    }
                    if ($expense->tax2 != 0) {
                        $total += ($expense->amount / 100 * $expense->taxrate2);
                    }
                    $total_expenses[$i][] = $total;
                }
            } else {
                $total_expenses[$i][] = 0;
            }
            $total_expenses[$i] = array_sum($total_expenses[$i]);
            // Calculate the income
            $this->db->select('amount');
            $this->db->from(db_prefix() . 'invoicepaymentrecords');
            $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
            $this->db->where('MONTH(' . db_prefix() . 'invoicepaymentrecords.date)', $m);
            $this->db->where('YEAR(' . db_prefix() . 'invoicepaymentrecords.date)', $year);
            $payments = $this->db->get()->result_array();
            if (!isset($total_income[$m])) {
                $total_income[$i] = [];
            }
            if (count($payments) > 0) {
                foreach ($payments as $payment) {
                    $total_income[$i][] = $payment['amount'];
                }
            } else {
                $total_income[$i][] = 0;
            }
            $total_income[$i] = array_sum($total_income[$i]);
            $i++;
        }
        $chart = [
            'labels'   => $months_labels,
            'datasets' => [
                [
                    'label'           => _l('report_sales_type_income'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_income,
                ],
                [
                    'label'           => _l('expenses'),
                    'backgroundColor' => 'rgba(252,45,66,0.4)',
                    'borderColor'     => '#fc2d42',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $total_expenses,
                ],
            ],
        ];

        return $chart;
    }

    /**
     * Chart leads weeekly report
     * @return array  chart data
     */
    public function leads_this_week_report()
    {
        $this->db->where('CAST(last_status_change as DATE) >= "' . date('Y-m-d', strtotime('monday this week')) . '" AND CAST(last_status_change as DATE) <= "' . date('Y-m-d', strtotime('sunday this week')) . '" AND status = 1 and lost = 0');
        $weekly = $this->db->get(db_prefix() . 'leads')->result_array();
        $colors = get_system_favourite_colors();
        $chart  = [
            'labels' => [
                _l('wd_monday'),
                _l('wd_tuesday'),
                _l('wd_wednesday'),
                _l('wd_thursday'),
                _l('wd_friday'),
                _l('wd_saturday'),
                _l('wd_sunday'),
            ],
            'datasets' => [
                [
                    'data' => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                    'backgroundColor' => [
                        $colors[0],
                        $colors[1],
                        $colors[2],
                        $colors[3],
                        $colors[4],
                        $colors[5],
                        $colors[6],
                    ],
                    'hoverBackgroundColor' => [
                        adjust_color_brightness($colors[0], -20),
                        adjust_color_brightness($colors[1], -20),
                        adjust_color_brightness($colors[2], -20),
                        adjust_color_brightness($colors[3], -20),
                        adjust_color_brightness($colors[4], -20),
                        adjust_color_brightness($colors[5], -20),
                        adjust_color_brightness($colors[6], -20),
                    ],
                ],
            ],
        ];
        foreach ($weekly as $weekly) {
            $lead_status_day = _l(mb_strtolower('wd_' . date('l', strtotime($weekly['last_status_change']))));
            $i               = 0;
            foreach ($chart['labels'] as $dat) {
                if ($lead_status_day == $dat) {
                    $chart['datasets'][0]['data'][$i]++;
                }
                $i++;
            }
        }

        return $chart;
    }

    public function leads_staff_report()
    {
        $this->load->model('staff_model');
        $staff = $this->staff_model->get();
        if ($this->input->post()) {
            $from_date = to_sql_date($this->input->post('staff_report_from_date'));
            $to_date   = to_sql_date($this->input->post('staff_report_to_date'));
        }
        $chart = [
            'labels'   => [],
            'datasets' => [
                [
                    'label'           => _l('leads_staff_report_created'),
                    'backgroundColor' => 'rgba(3,169,244,0.2)',
                    'borderColor'     => '#03a9f4',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [],
                ],
                [
                    'label'           => _l('leads_staff_report_lost'),
                    'backgroundColor' => 'rgba(252,45,66,0.4)',
                    'borderColor'     => '#fc2d42',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [],
                ],
                [
                    'label'           => _l('leads_staff_report_converted'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [],
                ],
            ],
        ];

        foreach ($staff as $member) {
            array_push($chart['labels'], $member['firstname'] . ' ' . $member['lastname']);

            if (!isset($to_date) && !isset($from_date)) {
                $this->db->where('CASE WHEN assigned=0 THEN addedfrom=' . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END
                    AND status=1', '', false);
                $total_rows_converted = $this->db->count_all_results(db_prefix() . 'leads');

                $total_rows_created = total_rows(db_prefix() . 'leads', [
                    'addedfrom' => $member['staffid'],
                ]);

                $this->db->where('CASE WHEN assigned=0 THEN addedfrom=' . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END
                    AND lost=1', '', false);
                $total_rows_lost = $this->db->count_all_results(db_prefix() . 'leads');
            } else {
                $sql                  = 'SELECT COUNT(' . db_prefix() . 'leads.id) as total FROM ' . db_prefix() . "leads WHERE DATE(last_status_change) BETWEEN '" . $this->db->escape_str($from_date) . "' AND '" . $this->db->escape_str($to_date) . "' AND status = 1 AND CASE WHEN assigned=0 THEN addedfrom=" . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END';
                $total_rows_converted = $this->db->query($sql)->row()->total;

                $sql                = 'SELECT COUNT(' . db_prefix() . 'leads.id) as total FROM ' . db_prefix() . "leads WHERE DATE(dateadded) BETWEEN '" . $this->db->escape_str($from_date) . "' AND '" . $this->db->escape_str($to_date) . "' AND addedfrom=" . $member['staffid'] . '';
                $total_rows_created = $this->db->query($sql)->row()->total;

                $sql = 'SELECT COUNT(' . db_prefix() . 'leads.id) as total FROM ' . db_prefix() . "leads WHERE DATE(last_status_change) BETWEEN '" . $this->db->escape_str($from_date) . "' AND '" . $this->db->escape_str($to_date) . "' AND lost = 1 AND CASE WHEN assigned=0 THEN addedfrom=" . $member['staffid'] . ' ELSE assigned=' . $member['staffid'] . ' END';

                $total_rows_lost = $this->db->query($sql)->row()->total;
            }

            array_push($chart['datasets'][0]['data'], $total_rows_created);
            array_push($chart['datasets'][1]['data'], $total_rows_lost);
            array_push($chart['datasets'][2]['data'], $total_rows_converted);
        }

        return $chart;
    }

    /**
     * Lead conversion by sources report / chart
     * @return arrray chart data
     */
    public function leads_sources_report()
    {
        $this->load->model('leads_model');
        $sources = $this->leads_model->get_source();
        $chart   = [
            'labels'   => [],
            'datasets' => [
                [
                    'label'           => _l('report_leads_sources_conversions'),
                    'backgroundColor' => 'rgba(124, 179, 66, 0.5)',
                    'borderColor'     => '#7cb342',
                    'data'            => [],
                ],
            ],
        ];
        foreach ($sources as $source) {
            array_push($chart['labels'], $source['name']);
            array_push($chart['datasets'][0]['data'], total_rows(db_prefix() . 'leads', [
                'source' => $source['id'],
                'status' => 1,
                'lost'   => 0,
            ]));
        }

        return $chart;
    }

    public function report_by_customer_groups()
    {
        $months_report = $this->input->post('months_report');
        $groups        = $this->clients_model->get_groups();
        if ($months_report != '') {
            $custom_date_select = '';
            if (is_numeric($months_report)) {
                // Last month
                if ($months_report == '1') {
                    $beginMonth = date('Y-m-01', strtotime('first day of last month'));
                    $endMonth   = date('Y-m-t', strtotime('last day of last month'));
                } else {
                    $months_report = (int) $months_report;
                    $months_report--;
                    $beginMonth = date('Y-m-01', strtotime("-$months_report MONTH"));
                    $endMonth   = date('Y-m-t');
                }

                $custom_date_select = '(' . db_prefix() . 'invoicepaymentrecords.date BETWEEN "' . $beginMonth . '" AND "' . $endMonth . '")';
            } elseif ($months_report == 'this_month') {
                $custom_date_select = '(' . db_prefix() . 'invoicepaymentrecords.date BETWEEN "' . date('Y-m-01') . '" AND "' . date('Y-m-t') . '")';
            } elseif ($months_report == 'this_year') {
                $custom_date_select = '(' . db_prefix() . 'invoicepaymentrecords.date BETWEEN "' .
                date('Y-m-d', strtotime(date('Y-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date('Y-12-31'))) . '")';
            } elseif ($months_report == 'last_year') {
                $custom_date_select = '(' . db_prefix() . 'invoicepaymentrecords.date BETWEEN "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-01-01'))) .
                '" AND "' .
                date('Y-m-d', strtotime(date(date('Y', strtotime('last year')) . '-12-31'))) . '")';
            } elseif ($months_report == 'custom') {
                $from_date = to_sql_date($this->input->post('report_from'));
                $to_date   = to_sql_date($this->input->post('report_to'));
                if ($from_date == $to_date) {
                    $custom_date_select = db_prefix() . 'invoicepaymentrecords.date ="' . $from_date . '"';
                } else {
                    $custom_date_select = '(' . db_prefix() . 'invoicepaymentrecords.date BETWEEN "' . $from_date . '" AND "' . $to_date . '")';
                }
            }
            $this->db->where($custom_date_select);
        }
        $this->db->select('amount,' . db_prefix() . 'invoicepaymentrecords.date,' . db_prefix() . 'invoices.clientid,(SELECT GROUP_CONCAT(name) FROM ' . db_prefix() . 'customers_groups LEFT JOIN ' . db_prefix() . 'customer_groups ON ' . db_prefix() . 'customer_groups.groupid = ' . db_prefix() . 'customers_groups.id WHERE customer_id = ' . db_prefix() . 'invoices.clientid) as customerGroups');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->join(db_prefix() . 'invoices', db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->db->where(db_prefix() . 'invoices.clientid IN (select customer_id FROM ' . db_prefix() . 'customer_groups)');
        $this->db->where(db_prefix() . 'invoices.status !=', 5);
        $by_currency = $this->input->post('report_currency');
        if ($by_currency) {
            $this->db->where('currency', $by_currency);
        }
        $payments       = $this->db->get()->result_array();
        $data           = [];
        $data['temp']   = [];
        $data['total']  = [];
        $data['labels'] = [];
        foreach ($groups as $group) {
            if (!isset($data['groups'][$group['name']])) {
                $data['groups'][$group['name']] = $group['name'];
            }
        }

        // If any groups found
        if (isset($data['groups'])) {
            foreach ($data['groups'] as $group) {
                foreach ($payments as $payment) {
                    $p_groups = explode(',', $payment['customerGroups']);
                    foreach ($p_groups as $p_group) {
                        if ($p_group == $group) {
                            $data['temp'][$group][] = $payment['amount'];
                        }
                    }
                }
                array_push($data['labels'], $group);
                if (isset($data['temp'][$group])) {
                    $data['total'][] = array_sum($data['temp'][$group]);
                } else {
                    $data['total'][] = 0;
                }
            }
        }

        $chart = [
            'labels'   => $data['labels'],
            'datasets' => [
                [
                    'label'           => _l('total_amount'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.2)',
                    'borderColor'     => '#c53da9',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => $data['total'],
                ],
            ],
        ];

        return $chart;
    }

    public function report_by_payment_modes()
    {
        $this->load->model('payment_modes_model');
        $modes  = $this->payment_modes_model->get('', [], true, true);
        $year   = $this->input->post('year');
        $colors = get_system_favourite_colors();
        $this->db->select('amount,' . db_prefix() . 'invoicepaymentrecords.date');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->where('YEAR(' . db_prefix() . 'invoicepaymentrecords.date)', $year);
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $by_currency = $this->input->post('report_currency');
        if ($by_currency) {
            $this->db->where('currency', $by_currency);
        }
        $all_payments = $this->db->get()->result_array();
        $chart        = [
            'labels'   => [],
            'datasets' => [],
        ];
        $data           = [];
        $data['months'] = [];
        foreach ($all_payments as $payment) {
            $month   = date('m', strtotime($payment['date']));
            $dateObj = DateTime::createFromFormat('!m', $month);
            $month   = $dateObj->format('F');
            if (!isset($data['months'][$month])) {
                $data['months'][$month] = $month;
            }
        }
        usort($data['months'], function ($a, $b) {
            $month1 = date_parse($a);
            $month2 = date_parse($b);

            return $month1['month'] - $month2['month'];
        });

        foreach ($data['months'] as $month) {
            array_push($chart['labels'], _l($month) . ' - ' . $year);
        }
        $i = 0;
        foreach ($modes as $mode) {
            if (total_rows(db_prefix() . 'invoicepaymentrecords', [
                'paymentmode' => $mode['id'],
            ]) == 0) {
                continue;
            }
            $color = '#4B5158';
            if (isset($colors[$i])) {
                $color = $colors[$i];
            }
            $this->db->select('amount,' . db_prefix() . 'invoicepaymentrecords.date');
            $this->db->from(db_prefix() . 'invoicepaymentrecords');
            $this->db->where('YEAR(' . db_prefix() . 'invoicepaymentrecords.date)', $year);
            $this->db->where(db_prefix() . 'invoicepaymentrecords.paymentmode', $mode['id']);
            $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
            $by_currency = $this->input->post('report_currency');
            if ($by_currency) {
                $this->db->where('currency', $by_currency);
            }
            $payments = $this->db->get()->result_array();

            $datasets_data          = [];
            $datasets_data['total'] = [];
            foreach ($data['months'] as $month) {
                $total_payments = [];
                if (!isset($datasets_data['temp'][$month])) {
                    $datasets_data['temp'][$month] = [];
                }
                foreach ($payments as $payment) {
                    $_month  = date('m', strtotime($payment['date']));
                    $dateObj = DateTime::createFromFormat('!m', $_month);
                    $_month  = $dateObj->format('F');
                    if ($month == $_month) {
                        $total_payments[] = $payment['amount'];
                    }
                }
                $datasets_data['total'][] = array_sum($total_payments);
            }
            $chart['datasets'][] = [
                'label'           => $mode['name'],
                'backgroundColor' => $color,
                'borderColor'     => adjust_color_brightness($color, -20),
                'tension'         => false,
                'borderWidth'     => 1,
                'data'            => $datasets_data['total'],
            ];
            $i++;
        }

        return $chart;
    }

    /**
     * Total income report / chart
     * @return array chart data
     */
    public function total_income_report()
    {
        $year = $this->input->post('year');
        $this->db->select('amount,' . db_prefix() . 'invoicepaymentrecords.date');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->where('YEAR(' . db_prefix() . 'invoicepaymentrecords.date)', $year);
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $by_currency = $this->input->post('report_currency');

        if ($by_currency) {
            $this->db->where('currency', $by_currency);
        }

        $payments       = $this->db->get()->result_array();
        $data           = [];
        $data['months'] = [];
        $data['temp']   = [];
        $data['total']  = [];
        $data['labels'] = [];

        foreach ($payments as $payment) {
            $month   = date('m', strtotime($payment['date']));
            $dateObj = DateTime::createFromFormat('!m', $month);
            $month   = $dateObj->format('F');
            if (!isset($data['months'][$month])) {
                $data['months'][$month] = $month;
            }
        }

        usort($data['months'], function ($a, $b) {
            $month1 = date_parse($a);
            $month2 = date_parse($b);

            return $month1['month'] - $month2['month'];
        });

        foreach ($data['months'] as $month) {
            foreach ($payments as $payment) {
                $monthNumber = date('m', strtotime($payment['date']));
                $dateObj     = DateTime::createFromFormat('!m', $monthNumber);
                $_month      = $dateObj->format('F');
                if ($month == $_month) {
                    $data['temp'][$month][] = $payment['amount'];
                }
            }

            array_push($data['labels'], _l($month) . ' - ' . $year);

            $data['total'][] = array_sum($data['temp'][$month]) - $this->calculate_refunded_amount($year, $monthNumber, $by_currency);
        }

        $chart = [
            'labels'   => $data['labels'],
            'datasets' => [
                [
                    'label'           => _l('report_sales_type_income'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'tension'         => false,
                    'borderWidth'     => 1,
                    'data'            => $data['total'],
                ],
            ],
        ];

        return $chart;
    }

    public function get_distinct_payments_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'invoicepaymentrecords ORDER BY year DESC')->result_array();
    }

    /**
     * Get leads by stage data for different periods
     * @param string $period
     * @return array
     */
    public function get_leads_by_stage_data($period = 'this_month')
    {
        $this->load->model('leads_model');
        $statuses = $this->leads_model->get_status();
        $data = [];
        // Set date conditions based on period
        switch ($period) {
            case 'this_week':
                $date_start = date('Y-m-d', strtotime('monday this week'));
                $date_end = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'last_week':
                $date_start = date('Y-m-d', strtotime('monday last week'));
                $date_end = date('Y-m-d', strtotime('sunday last week'));
                break;
            case 'last_month':
                $month = date('n', strtotime('last month'));
                $year = date('Y', strtotime('last month'));
                break;
            case 'current_year':
                $year = date('Y');
                break;
            case 'all':
                // No date filter for all time
                break;
            case 'this_month':
            default:
                $month = date('n');
                $year = date('Y');
                break;
        }
        foreach ($statuses as $status) {
            $this->db->select('COUNT(*) as total');
            $this->db->from(db_prefix() . 'leads');
            $this->db->where('status', $status['id']);
            // Date filter
            if (isset($date_start) && isset($date_end)) {
                $this->db->where('DATE(dateadded) >=', $date_start);
                $this->db->where('DATE(dateadded) <=', $date_end);
            } elseif (isset($month) && isset($year)) {
                $this->db->where('MONTH(dateadded)', $month);
                $this->db->where('YEAR(dateadded)', $year);
            } elseif (isset($year)) {
                $this->db->where('YEAR(dateadded)', $year);
            }
            // Add company filter
            if (!is_super()) {
                $this->db->where('company_id', get_staff_company_id());
            } else {
                if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
                    $this->db->where('company_id', $_SESSION['super_view_company_id']);
                }
            }
            $result = $this->db->get()->row();
            $data[] = [
                'status_name' => $status['name'],
                'status_color' => $status['color'],
                'total' => $result->total,
                'status_id' => $status['id']
            ];
        }
        return $data;
    }

    /**
     * Get leads by source data for different periods
     * @param string $period
     * @return array
     */
    public function get_leads_by_source_data($period = 'this_month')
    {
        $this->load->model('leads_model');
        $sources = $this->leads_model->get_source();
        $data = [];
        // Set date conditions based on period
        switch ($period) {
            case 'this_week':
                $date_start = date('Y-m-d', strtotime('monday this week'));
                $date_end = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'last_week':
                $date_start = date('Y-m-d', strtotime('monday last week'));
                $date_end = date('Y-m-d', strtotime('sunday last week'));
                break;
            case 'last_month':
                $month = date('n', strtotime('last month'));
                $year = date('Y', strtotime('last month'));
                break;
            case 'current_year':
                $year = date('Y');
                break;
            case 'all':
                // No date filter for all time
                break;
            case 'this_month':
            default:
                $month = date('n');
                $year = date('Y');
                break;
        }
        // Get data for each source
        foreach ($sources as $source) {
            $this->db->select('COUNT(*) as total');
            $this->db->from(db_prefix() . 'leads');
            $this->db->where('source', $source['id']);
            // Date filter
            if (isset($date_start) && isset($date_end)) {
                $this->db->where('DATE(dateadded) >=', $date_start);
                $this->db->where('DATE(dateadded) <=', $date_end);
            } elseif (isset($month) && isset($year)) {
                $this->db->where('MONTH(dateadded)', $month);
                $this->db->where('YEAR(dateadded)', $year);
            } elseif (isset($year)) {
                $this->db->where('YEAR(dateadded)', $year);
            }
            // Add company filter
            if (!is_super()) {
                $this->db->where('company_id', get_staff_company_id());
            } else {
                if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
                    $this->db->where('company_id', $_SESSION['super_view_company_id']);
                }
            }
            $result = $this->db->get()->row();
            $data[] = [
                'source_name' => $source['name'],
                'source_color' => $source['color'] ?? '#757575',
                'total' => $result->total,
                'source_id' => $source['id']
            ];
        }
        return $data;
    }

    /**
     * Get leads by country data for different periods
     * @param string $period
     * @return array
     */
    public function get_leads_by_country_data($period = 'this_month')
    {
        $this->load->model('leads_model');
        $data = [];
        // Set date conditions based on period
        switch ($period) {
            case 'this_week':
                $this->db->where('DATE(dateadded) >=', date('Y-m-d', strtotime('monday this week')));
                $this->db->where('DATE(dateadded) <=', date('Y-m-d', strtotime('sunday this week')));
                break;
            case 'last_week':
                $this->db->where('DATE(dateadded) >=', date('Y-m-d', strtotime('monday last week')));
                $this->db->where('DATE(dateadded) <=', date('Y-m-d', strtotime('sunday last week')));
                break;
            case 'last_month':
                $this->db->where('MONTH(dateadded)', date('n', strtotime('last month')));
                $this->db->where('YEAR(dateadded)', date('Y', strtotime('last month')));
                break;
            case 'current_year':
                $this->db->where('YEAR(dateadded)', date('Y'));
                break;
            case 'all':
                // No date filter for all time
                break;
            case 'this_month':
            default:
                $this->db->where('MONTH(dateadded)', date('n'));
                $this->db->where('YEAR(dateadded)', date('Y'));
                break;
        }
        // Add company filter
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            }
        }
        $this->db->select('country, COUNT(*) as total');
        $this->db->from(db_prefix() . 'leads');
        $this->db->group_by('country');
        $this->db->having('total > 0');
        $this->db->order_by('total', 'DESC');
        $results = $this->db->get()->result_array();
        // Optionally, get country names from a helper or config
        foreach ($results as $row) {
            $country_id = $row['country'];
            $country_name = 'Unknown';
            if (isset($country_id) && $country_id) {
                $country_name = get_country_name($country_id);
            }
            $data[] = [
                'country_id' => $country_id,
                'country_name' => $country_name,
                'total' => $row['total']
            ];
        }
        return $data;
    }

    public function get_distinct_customer_invoices_years()
    {
        return $this->db->query('SELECT DISTINCT(YEAR(date)) as year FROM ' . db_prefix() . 'invoices WHERE clientid=' . get_client_user_id())->result_array();
    }

    protected function calculate_refunded_amount($year, $month, $currency)
    {
        $sql = 'SELECT
        SUM(' . db_prefix() . 'creditnote_refunds.amount) as refunds_amount
        FROM ' . db_prefix() . 'creditnote_refunds
        WHERE YEAR(refunded_on) = ' . $year . ' AND MONTH(refunded_on) = ' . $month;

        if ($currency) {
            $sql .= ' AND credit_note_id IN (SELECT id FROM ' . db_prefix() . 'creditnotes WHERE currency=' . $currency . ')';
        }

        $refunds_amount = $this->db->query($sql)->row()->refunds_amount;

        if ($refunds_amount === null) {
            $refunds_amount = 0;
        }

        return $refunds_amount;
    }

    /**
     * Get leads for a specific country and period
     * @param int $country_id
     * @param string $period
     * @return array
     */
    public function get_leads_for_country($country_id, $period = 'this_month')
    {
        $this->load->model('leads_model');
        // Set date conditions based on period
        $date_condition = '';
        switch ($period) {
            case 'this_week':
                $date_condition = " AND DATE(dateadded) >= '" . date('Y-m-d', strtotime('monday this week')) . "' AND DATE(dateadded) <= '" . date('Y-m-d', strtotime('sunday this week')) . "'";
                break;
            case 'last_week':
                $date_condition = " AND DATE(dateadded) >= '" . date('Y-m-d', strtotime('monday last week')) . "' AND DATE(dateadded) <= '" . date('Y-m-d', strtotime('sunday last week')) . "'";
                break;
            case 'last_month':
                $date_condition = " AND MONTH(dateadded) = " . date('n', strtotime('last month')) . " AND YEAR(dateadded) = " . date('Y', strtotime('last month'));
                break;
            case 'current_year':
                $date_condition = " AND YEAR(dateadded) = " . date('Y');
                break;
            case 'all':
                $date_condition = ""; // No date filter for all time
                break;
            case 'this_month':
            default:
                $date_condition = " AND MONTH(dateadded) = " . date('n') . " AND YEAR(dateadded) = " . date('Y');
                break;
        }
        // Add company filter
        $company_filter = '';
        if (is_super()) {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND company_id = ' . $_SESSION['super_view_company_id'];
            }
        } elseif (is_admin()) {
            $company_filter = ' AND company_id = ' . get_staff_company_id();
        } else {
            $company_filter = ' AND company_id = ' . get_staff_company_id() . ' AND assigned = ' . get_staff_user_id();
        }
        $sql = "SELECT id, name, email, status, dateadded FROM " . db_prefix() . "leads WHERE country = " . (int)$country_id . " " . $date_condition . $company_filter . " ORDER BY dateadded DESC";
        $results = $this->db->query($sql)->result_array();
        // Optionally, get status names
        $statuses = $this->leads_model->get_status();
        $status_map = [];
        foreach ($statuses as $s) {
            $status_map[$s['id']] = $s['name'];
        }
        foreach ($results as &$lead) {
            $lead['status_name'] = isset($status_map[$lead['status']]) ? $status_map[$lead['status']] : '';
            $lead['dateadded'] = _dt($lead['dateadded']);
        }
        return $results;
    }

    /**
     * Get deals by company data for different periods
     * @param string $period
     * @return array
     */
    public function get_deals_by_company_data($period = 'this_month')
    {
        $this->load->model('leads_model');
        $data = [];
        // Set date conditions based on period
        switch ($period) {
            case 'this_week':
                $this->db->where('DATE(dateadded) >=', date('Y-m-d', strtotime('monday this week')));
                $this->db->where('DATE(dateadded) <=', date('Y-m-d', strtotime('sunday this week')));
                break;
            case 'last_week':
                $this->db->where('DATE(dateadded) >=', date('Y-m-d', strtotime('monday last week')));
                $this->db->where('DATE(dateadded) <=', date('Y-m-d', strtotime('sunday last week')));
                break;
            case 'last_month':
                $this->db->where('MONTH(dateadded)', date('n', strtotime('last month')));
                $this->db->where('YEAR(dateadded)', date('Y', strtotime('last month')));
                break;
            case 'current_year':
                $this->db->where('YEAR(dateadded)', date('Y'));
                break;
            case 'all':
                // No date filter for all time
                break;
            case 'this_month':
            default:
                $this->db->where('MONTH(dateadded)', date('n'));
                $this->db->where('YEAR(dateadded)', date('Y'));
                break;
        }
        // Add company filter
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            }
        }
        $this->db->select('company_id, COUNT(*) as total');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('is_deal', 1);
        $this->db->group_by('company_id');
        $this->db->having('total > 0');
        $this->db->order_by('total', 'DESC');
        $results = $this->db->get()->result_array();
		//print_r($results);
		//echo $this->db->last_query();exit;
        // Optionally, get company names
        foreach ($results as $row) {
            $company_id = $row['company_id'];
            $company_name = get_staff_company_name($company_id);
            // Try to get company name from leads_model if available
            
            $data[] = [
                'company_id' => $company_id,
                'company_name' => $company_name,
                'total' => $row['total']
            ];
        }
        return $data;
    }

    /**
     * Get deals by status data for different periods
     * @param string $period
     * @return array
     */
    public function get_deals_by_status_data($period = 'this_month')
    {
        $this->load->model('leads_model');
        $statuses = $this->leads_model->get_deal_form_order();
        $data = [];
        // Set date conditions based on period
        $date_condition = '';
        switch ($period) {
            case 'this_week':
                $date_condition = " AND DATE(dateadded) >= '" . date('Y-m-d', strtotime('monday this week')) . "' AND DATE(dateadded) <= '" . date('Y-m-d', strtotime('sunday this week')) . "'";
                break;
            case 'last_week':
                $date_condition = " AND DATE(dateadded) >= '" . date('Y-m-d', strtotime('monday last week')) . "' AND DATE(dateadded) <= '" . date('Y-m-d', strtotime('sunday last week')) . "'";
                break;
            case 'last_month':
                $date_condition = " AND MONTH(dateadded) = " . date('n', strtotime('last month')) . " AND YEAR(dateadded) = " . date('Y', strtotime('last month'));
                break;
            case 'current_year':
                $date_condition = " AND YEAR(dateadded) = " . date('Y');
                break;
            case 'all':
                $date_condition = ""; // No date filter for all time
                break;
            case 'this_month':
            default:
                $date_condition = " AND MONTH(dateadded) = " . date('n') . " AND YEAR(dateadded) = " . date('Y');
                break;
        }
        // Get data for each deal status
		//print_r($statuses);exit;
        foreach ($statuses as $key=>$status) {
		//print_r($status);exit;
            $this->db->select('COUNT(*) as total');
            $this->db->from(db_prefix() . 'leads');
            $this->db->where('deal_stage', $key+1);
            $this->db->where('is_deal', 1);
            //$this->db->where('lost', 0); // Exclude lost deals
            //$this->db->where('junk', 0); // Exclude junk deals
            
            // Add company filter
            if (!is_super()) {
                $this->db->where('company_id', get_staff_company_id());
            } else {
                if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                    $this->db->where('company_id', $_SESSION['super_view_company_id']);
                }
            }
            
            // Apply date filter
            if ($period != 'all') {
                switch ($period) {
                    case 'this_month':
                        $this->db->where('dateadded >=', date('Y-m-01'));
                        break;
                    case 'last_month':
                        $this->db->where('dateadded >=', date('Y-m-01', strtotime('last month')));
                        $this->db->where('dateadded <', date('Y-m-01'));
                        break;
                    case 'this_week':
                        $this->db->where('dateadded >=', date('Y-m-d', strtotime('monday this week')));
                        break;
                    case 'last_week':
                        $this->db->where('dateadded >=', date('Y-m-d', strtotime('monday last week')));
                        $this->db->where('dateadded <', date('Y-m-d', strtotime('monday this week')));
                        break;
                    case 'current_year':
                        $this->db->where('dateadded >=', date('Y-01-01'));
                        break;
                }
            }
            
            $result = $this->db->get()->row();
			//echo $this->db->last_query();
            $data[] = [
                'status_name' => get_deals_stage_title($status),
                'total' => $result->total,
                'status_id' => $status
            ];
        }
		//exit;
        return $data;
    }
	
	
    public function get_deals_by_final_status_data($period = 'this_month')
    {
     
		$this->db->from(db_prefix() . 'leads');
		if (is_super()) {
		if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
		$this->db->where('company_id', $_SESSION['super_view_company_id']);
		}
		}elseif (is_admin()) {
		$this->db->where('company_id', get_staff_company_id());
		}else{
		$this->db->where('company_id', get_staff_company_id());
		$this->db->where('assigned', get_staff_user_id());
		}
		$this->db->select("COUNT(CASE WHEN deal_stage_status = 1 AND is_deal = 1  THEN 1 END) AS Success, COUNT(CASE WHEN deal_stage_status = 2 AND is_deal = 1 THEN 1 END) AS Failed, COUNT(CASE WHEN deal_stage_status = 0 AND is_deal = 1 THEN 3 END) AS Process");
		$data = $this->db->get()->result_array();
       
        return $data;
    }
    /**
     * Get deals count by staff for the selected period
     */
    public function get_deals_by_staff_data($period = 'this_month')
    {
        $this->load->model('staff_model');
        $data = [];
        // Set date conditions based on period
        switch ($period) {
            case 'this_week':
                $this->db->where('DATE(dateadded) >=', date('Y-m-d', strtotime('monday this week')));
                $this->db->where('DATE(dateadded) <=', date('Y-m-d', strtotime('sunday this week')));
                break;
            case 'last_week':
                $this->db->where('DATE(dateadded) >=', date('Y-m-d', strtotime('monday last week')));
                $this->db->where('DATE(dateadded) <=', date('Y-m-d', strtotime('sunday last week')));
                break;
            case 'last_month':
                $this->db->where('MONTH(dateadded)', date('n', strtotime('last month')));
                $this->db->where('YEAR(dateadded)', date('Y', strtotime('last month')));
                break;
            case 'current_year':
                $this->db->where('YEAR(dateadded)', date('Y'));
                break;
            case 'all':
                // No date filter for all time
                break;
            case 'this_month':
            default:
                $this->db->where('MONTH(dateadded)', date('n'));
                $this->db->where('YEAR(dateadded)', date('Y'));
                break;
        }
        // Add company filter
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            }
        }
        $this->db->select('assigned, COUNT(*) as total');
        $this->db->from(db_prefix() . 'leads');
        $this->db->where('is_deal', 1);
        $this->db->group_by('assigned');
        $this->db->having('total > 0');
        $this->db->order_by('total', 'DESC');
        $results = $this->db->get()->result_array();
        foreach ($results as $row) {
            $staff = $this->staff_model->get($row['assigned']);
            $staff_name = $staff ? $staff->full_name : 'Unknown';
            $data[] = [
                'staff_id' => $row['assigned'],
                'staff_name' => $staff_name,
                'total' => $row['total']
            ];
        }
        return $data;
    }

    /**
     * Get deals by staff and status for the table
     */
    public function get_deals_by_staff_status_table($period = 'this_month')
    {
        $data = [];
        // Set date conditions based on period
        $date_condition = '';
        switch ($period) {
            case 'this_week':
                $date_condition = " AND DATE(dateadded) >= '" . date('Y-m-d', strtotime('monday this week')) . "' AND DATE(dateadded) <= '" . date('Y-m-d', strtotime('sunday this week')) . "'";
                break;
            case 'last_week':
                $date_condition = " AND DATE(dateadded) >= '" . date('Y-m-d', strtotime('monday last week')) . "' AND DATE(dateadded) <= '" . date('Y-m-d', strtotime('sunday last week')) . "'";
                break;
            case 'last_month':
                $date_condition = " AND MONTH(dateadded) = " . date('n', strtotime('last month')) . " AND YEAR(dateadded) = " . date('Y', strtotime('last month'));
                break;
            case 'current_year':
                $date_condition = " AND YEAR(dateadded) = " . date('Y');
                break;
            case 'all':
                $date_condition = ""; // No date filter for all time
                break;
            case 'this_month':
            default:
                $date_condition = " AND MONTH(dateadded) = " . date('n') . " AND YEAR(dateadded) = " . date('Y');
                break;
        }
        // Get all staff with at least one assigned deal
        $sql = "SELECT assigned FROM " . db_prefix() . "leads WHERE is_deal=1 " . $date_condition . " GROUP BY assigned";
        $staffs = $this->db->query($sql)->result_array();
		//print_r($staffs);exit;
        foreach ($staffs as $s) {
           $staff_id = $s['assigned'];
            
            
                $this->db->select('assigned,
    COUNT(CASE WHEN deal_status = 1 THEN 1 END) AS status_new,
    COUNT(CASE WHEN deal_status = 2 THEN 1 END) AS status_doc,
    COUNT(CASE WHEN deal_status = 3 THEN 1 END) AS status_uw,
    COUNT(CASE WHEN deal_status = 4 THEN 1 END) AS status_fin');
                $this->db->from(db_prefix() . 'leads');
                $this->db->where('assigned', $staff_id);
                $this->db->where('is_deal', 1);
                $where_clause = '1=1' . $date_condition;
                $this->db->where($where_clause);
                $result = $this->db->get()->row();
				
				$data[$staff_id] = $result;
				//echo $this->db->last_query();exit;
				
          
        }
		//print_r($data);exit;
        return $data;
    }

    /**
     * Get invoice status distribution for pie chart
     */
    public function get_invoice_status_distribution($period = 'all')
    {
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT status, COUNT(*) as total FROM " . db_prefix() . "invoices WHERE 1=1 " . $where_clause . $company_filter . " GROUP BY status";
        $results = $this->db->query($sql)->result_array();
        // Map status code to label
        $status_labels = [
            1 => 'Unpaid',
            2 => 'Paid',
            3 => 'Partially Paid',
            4 => 'Overdue',
            5 => 'Cancelled',
        ];
        $data = [];
        foreach ($results as $row) {
            $label = isset($status_labels[$row['status']]) ? $status_labels[$row['status']] : 'Unknown';
            $data[] = [
                'label' => $label,
                'total' => $row['total']
            ];
        }
        return $data;
    }

    /**
     * Get invoice approver_status distribution for pie chart
     */
    public function get_invoice_approver_status_distribution($period = 'all')
    {
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT approver_status, COUNT(*) as total FROM " . db_prefix() . "invoices WHERE 1=1 " . $where_clause . $company_filter . " GROUP BY approver_status";
        $results = $this->db->query($sql)->result_array();
        // Map approver_status code to label (customize as needed)
        $status_labels = [
            0 => 'Not Set',
            1 => 'Pending',
            2 => 'Approved',
            3 => 'Rejected',
        ];
        $data = [];
        foreach ($results as $row) {
            $label = isset($status_labels[$row['approver_status']]) ? $status_labels[$row['approver_status']] : 'Unknown';
            $data[] = [
                'label' => $label,
                'total' => $row['total']
            ];
        }
        return $data;
    }

    /**
     * Get invoice table data for the report
     */
    public function get_invoice_table_data($period = 'all')
    {
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT id, clientid, status, approver_status, date, total FROM " . db_prefix() . "invoices WHERE 1=1 " . $where_clause . $company_filter . " ORDER BY date DESC";
        $results = $this->db->query($sql)->result_array();
        // Map status code to label
        $status_labels = [
            1 => 'Unpaid',
            2 => 'Paid',
            3 => 'Partially Paid',
            4 => 'Overdue',
            5 => 'Cancelled',
        ];
        $approver_labels = [
            0 => 'Not Set',
            1 => 'Pending',
            2 => 'Approved',
            3 => 'Rejected',
        ];
        $this->load->model('clients_model');
        foreach ($results as &$row) {
            $row['status_label'] = isset($status_labels[$row['status']]) ? $status_labels[$row['status']] : 'Unknown';
            $row['approver_status_label'] = isset($approver_labels[$row['approver_status']]) ? $approver_labels[$row['approver_status']] : 'Unknown';
            $client = $this->clients_model->get($row['clientid']);
            $row['client_name'] = $client ? $client->company : 'Unknown';
        }
        return $results;
    }

    /**
     * Get invoices count by staff
     */
    public function get_invoices_by_staff_data($period = 'all')
    {
        $this->load->model('staff_model');
        $data = [];
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT addedfrom, COUNT(*) as total FROM " . db_prefix() . "invoices WHERE 1=1 " . $where_clause . $company_filter . " GROUP BY addedfrom HAVING total > 0 ORDER BY total DESC";
        $results = $this->db->query($sql)->result_array();
        foreach ($results as $row) {
            $staff = $this->staff_model->get($row['addedfrom']);
            $staff_name = $staff ? $staff->full_name : 'Unknown';
            $data[] = [
                'staff_id' => $row['addedfrom'],
                'staff_name' => $staff_name,
                'total' => $row['total']
            ];
        }
        return $data;
    }

    /**
     * Get invoices by staff and status for the table
     */
    public function get_invoices_by_staff_status_table($period = 'all')
    {
        $statuses = [1 => 'Unpaid', 2 => 'Paid', 3 => 'Partially Paid', 4 => 'Overdue', 5 => 'Cancelled'];
        $data = [];
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT addedfrom FROM " . db_prefix() . "invoices WHERE 1=1 " . $where_clause . $company_filter . " GROUP BY addedfrom";
        $staffs = $this->db->query($sql)->result_array();
        foreach ($staffs as $s) {
            $staff_id = $s['addedfrom'];
            $data[$staff_id] = array_fill_keys($statuses, 0);
            foreach ($statuses as $status_code => $status_label) {
                $this->db->select('COUNT(*) as total');
                $this->db->from(db_prefix() . 'invoices');
                $this->db->where('addedfrom', $staff_id);
                $this->db->where('status', $status_code);
                
                // Add company filter
                if (!is_super()) {
                    $this->db->where('company_id', get_staff_company_id());
                } else {
                    if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                        $this->db->where('company_id', $_SESSION['super_view_company_id']);
                    }
                }
                
                // Add period filter using raw SQL
                if ($period != 'all') {
                    switch ($period) {
                        case 'this_month':
                            $this->db->where('date >=', date('Y-m-01'));
                            break;
                        case 'last_month':
                            $this->db->where('date >=', date('Y-m-01', strtotime('last month')));
                            $this->db->where('date <', date('Y-m-01'));
                            break;
                        case 'this_week':
                            $this->db->where('date >=', date('Y-m-d', strtotime('monday this week')));
                            break;
                        case 'last_week':
                            $this->db->where('date >=', date('Y-m-d', strtotime('monday last week')));
                            $this->db->where('date <', date('Y-m-d', strtotime('monday this week')));
                            break;
                        case 'current_year':
                            $this->db->where('date >=', date('Y-01-01'));
                            break;
                    }
                }
                $result = $this->db->get()->row();
                $data[$staff_id][$status_label] = $result->total;
            }
        }
        return $data;
    }

    /**
     * Get activity log count by staff for chart
     */
    public function get_activity_by_staff_data($period = 'all')
    {
        $this->db->select('staffid, COUNT(*) as activity_count');
        $this->db->from('it_crm_activity_log');
        $this->db->where('staffid IS NOT NULL', null, false);
        
        // Add company filter
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            }
        }
        
        // Add period filter
        if ($period != 'all') {
            switch ($period) {
                case 'this_month':
                    $this->db->where('date >=', date('Y-m-01'));
                    break;
                case 'last_month':
                    $this->db->where('date >=', date('Y-m-01', strtotime('last month')));
                    $this->db->where('date <', date('Y-m-01'));
                    break;
                case 'this_week':
                    $this->db->where('date >=', date('Y-m-d', strtotime('monday this week')));
                    break;
                case 'last_week':
                    $this->db->where('date >=', date('Y-m-d', strtotime('monday last week')));
                    $this->db->where('date <', date('Y-m-d', strtotime('monday this week')));
                    break;
                case 'current_year':
                    $this->db->where('date >=', date('Y-01-01'));
                    break;
            }
        }
        
        $this->db->group_by('staffid');
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Get activity log details by staff for table
     */
    public function get_activity_by_staff_table($period = 'all')
    {
        $this->db->select('staffid, COUNT(*) as activity_count, MAX(date) as last_activity');
        $this->db->from('it_crm_activity_log');
        $this->db->where('staffid IS NOT NULL', null, false);
        
        // Add company filter
        if (!is_super()) {
            $this->db->where('company_id', get_staff_company_id());
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $this->db->where('company_id', $_SESSION['super_view_company_id']);
            }
        }
        
        // Add period filter
        if ($period != 'all') {
            switch ($period) {
                case 'this_month':
                    $this->db->where('date >=', date('Y-m-01'));
                    break;
                case 'last_month':
                    $this->db->where('date >=', date('Y-m-01', strtotime('last month')));
                    $this->db->where('date <', date('Y-m-01'));
                    break;
                case 'this_week':
                    $this->db->where('date >=', date('Y-m-d', strtotime('monday this week')));
                    break;
                case 'last_week':
                    $this->db->where('date >=', date('Y-m-d', strtotime('monday last week')));
                    $this->db->where('date <', date('Y-m-d', strtotime('monday this week')));
                    break;
                case 'current_year':
                    $this->db->where('date >=', date('Y-01-01'));
                    break;
            }
        }
        
        $this->db->group_by('staffid');
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Helper function to build WHERE clause for period filtering
     */
    private function get_period_where_clause($period)
    {
        switch ($period) {
            case 'this_month':
                return "AND date >= DATE_FORMAT(NOW(), '%Y-%m-01')";
            case 'last_month':
                return "AND date >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 1 MONTH), '%Y-%m-01') AND date < DATE_FORMAT(NOW(), '%Y-%m-01')";
            case 'this_week':
                return "AND date >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)";
            case 'last_week':
                return "AND date >= DATE_SUB(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 1 WEEK) AND date < DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)";
            case 'current_year':
                return "AND date >= DATE_FORMAT(NOW(), '%Y-01-01')";
            case 'all':
            default:
                return "";
        }
    }

    /**
     * Get sales by payment methods for chart
     */
    public function get_sales_by_payments_data($period = 'all')
    {
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND ' . db_prefix() . 'invoicepaymentrecords.company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND ' . db_prefix() . 'invoicepaymentrecords.company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT 
                    COALESCE(" . db_prefix() . "payment_modes.name, 'Unknown') as payment_method,
                    COUNT(*) as payment_count, 
                    SUM(" . db_prefix() . "invoicepaymentrecords.amount) as total_amount 
                FROM " . db_prefix() . "invoicepaymentrecords 
                LEFT JOIN " . db_prefix() . "payment_modes ON " . db_prefix() . "payment_modes.id = " . db_prefix() . "invoicepaymentrecords.paymentmode
                WHERE 1=1 " . $where_clause . $company_filter . " 
                GROUP BY " . db_prefix() . "invoicepaymentrecords.paymentmode 
                ORDER BY total_amount DESC";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }

    /**
     * Get sales by payment methods for table
     */
    public function get_sales_by_payments_table($period = 'all')
    {
        $where_clause = $this->get_period_where_clause($period);
        
        // Add company filter
        $company_filter = '';
        if (!is_super()) {
            $company_filter = ' AND ' . db_prefix() . 'invoicepaymentrecords.company_id = ' . get_staff_company_id();
        } else {
            if (isset($_SESSION['super_view_company_id']) && $_SESSION['super_view_company_id']) {
                $company_filter = ' AND ' . db_prefix() . 'invoicepaymentrecords.company_id = ' . $_SESSION['super_view_company_id'];
            }
        }
        
        $sql = "SELECT 
                    COALESCE(" . db_prefix() . "payment_modes.name, 'Unknown') as payment_method,
                    COUNT(*) as payment_count, 
                    SUM(" . db_prefix() . "invoicepaymentrecords.amount) as total_amount,
                    AVG(" . db_prefix() . "invoicepaymentrecords.amount) as avg_amount, 
                    MIN(" . db_prefix() . "invoicepaymentrecords.date) as first_payment, 
                    MAX(" . db_prefix() . "invoicepaymentrecords.date) as last_payment
                FROM " . db_prefix() . "invoicepaymentrecords 
                LEFT JOIN " . db_prefix() . "payment_modes ON " . db_prefix() . "payment_modes.id = " . db_prefix() . "invoicepaymentrecords.paymentmode
                WHERE 1=1 " . $where_clause . $company_filter . " 
                GROUP BY " . db_prefix() . "invoicepaymentrecords.paymentmode 
                ORDER BY total_amount DESC";
        $result = $this->db->query($sql)->result_array();
        return $result;
    }
}
