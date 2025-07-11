<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-credit-card tw-mx-2"></i> Sales by Payments Report
        </h4>
        <!-- Period Selection -->
        <div class="tw-absolute tw-top-0 tw-right-0 tw-mr-4 tw-mb-4">
          <div style="display: flex; align-items: center; gap: 10px;">
            <label style="margin: 0; white-space: nowrap;" class="tw-font-semibold tw-text-lg tw-text-white tw-ml-2.5">Report for period:</label>
            <select name="period" id="periodSelect" class="form-control" style="width: auto; min-width: 150px;" onchange="changePeriod(this.value)">
              <option value="this_month" <?php echo ($selected_period == 'this_month') ? 'selected' : ''; ?>>This Month</option>
              <option value="last_month" <?php echo ($selected_period == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
              <option value="this_week" <?php echo ($selected_period == 'this_week') ? 'selected' : ''; ?>>This Week</option>
              <option value="last_week" <?php echo ($selected_period == 'last_week') ? 'selected' : ''; ?>>Last Week</option>
              <option value="current_year" <?php echo ($selected_period == 'current_year') ? 'selected' : ''; ?>>Current Year</option>
              <option value="all" <?php echo ($selected_period == 'all') ? 'selected' : ''; ?>>All</option>
            </select>
          </div>
        </div>
        
        <!-- Charts Section -->
        <div class="row">
          <div class="col-md-6">
            <div class="panel_s">
              <div class="panel-body">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
                  <i class="fa-solid fa-chart-pie tw-mx-2"></i> Payment Distribution
                </h4>
                <div id="pie_chart_div" style="width:100%; height:300px;"></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel_s">
              <div class="panel-body">
                <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
                  <i class="fa-solid fa-chart-bar tw-mx-2"></i> Sales by Payment Method
                </h4>
                <div id="bar_chart_div" style="width:100%; height:300px;"></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-table tw-mx-2"></i> Payment Details
            </h4>
            <?php if (isset($sales_by_payments_table) && count($sales_by_payments_table) > 0) { ?>
              <table class="table dt-table" style="width:100%">
                <thead>
                  <tr>
                    <th>Payment Method</th>
                    <th>Payment Count</th>
                    <th>Total Amount</th>
                    <th>Average Amount</th>
                    <th>First Payment</th>
                    <th>Last Payment</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($sales_by_payments_table as $row): ?>
                    <tr>
                      <td><?php echo html_escape($row['payment_method']); ?></td>
                      <td><?php echo (int)$row['payment_count']; ?></td>
                      <td><?php echo app_format_money($row['total_amount'], $base_currency); ?></td>
                      <td><?php echo app_format_money($row['avg_amount'], $base_currency); ?></td>
                      <td><?php echo _dt($row['first_payment']); ?></td>
                      <td><?php echo _dt($row['last_payment']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="no-margin">No payment data found for the selected period.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Prepare data for pie chart
$pieData = [["Payment Method", "Amount"]];
foreach ($sales_by_payments_data as $row) {
    $pieData[] = [$row['payment_method'], (float)$row['total_amount']];
}

// Prepare data for bar chart
$barData = [["Payment Method", "Count", "Amount"]];
foreach ($sales_by_payments_data as $row) {
    $barData[] = [$row['payment_method'], (int)$row['payment_count'], (float)$row['total_amount']];
}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart', 'bar']});
  google.charts.setOnLoadCallback(drawCharts);
  
  function drawCharts() {
    drawPieChart();
    drawBarChart();
  }
  
  function drawPieChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($pieData); ?>);
    var options = {
      title: 'Payment Distribution by Amount',
      height: 300,
      legend: { position: 'bottom' },
      chartArea: {width: '80%', height: '60%'}
    };
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
    chart.draw(data, options);
  }
  
  function drawBarChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($barData); ?>);
    var options = {
      title: 'Sales by Payment Method',
      height: 300,
      legend: { position: 'top' },
      chartArea: {width: '80%', height: '60%'},
      series: {
        0: {targetAxisIndex: 0},
        1: {targetAxisIndex: 1}
      },
      vAxes: {
        0: {title: 'Payment Count'},
        1: {title: 'Total Amount'}
      }
    };
    var chart = new google.charts.Bar(document.getElementById('bar_chart_div'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
  
  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/sales_by_payments'); ?>?period=' + period;
  }
</script>

<?php init_tail(); ?>
</body></html> 