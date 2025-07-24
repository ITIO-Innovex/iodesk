<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php 

if(!isset($_SESSION['deal_form_order'])||empty($_SESSION['deal_form_order'])){
$_SESSION['deal_form_order']=$this->leads_model->get_deal_form_order();
}
?>

<div id="wrapper">
  <div class="content">
  <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-chart-pie tw-mx-2"></i> Deals by Status Report
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
        <!-- Chart Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-chart-simple tw-mx-2"></i> Status Distribution
            </h4>
            <div class="row">
              <div class="col-md-6">
                <h5 class="tw-font-semibold tw-text-sm tw-text-neutral-600 tw-mb-3">Pie Chart</h5>
                <div id="pie_chart_div" style="width:100%; height:300px;"></div>
              </div>
              <div class="col-md-6">
                <h5 class="tw-font-semibold tw-text-sm tw-text-neutral-600 tw-mb-3">Bar Chart</h5>
                <div id="bar_chart_div" style="width:100%; height:300px;"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-table tw-mx-2"></i> Deals by Status Details
            </h4>
            <?php if (isset($deals_by_status_data) && count($deals_by_status_data) > 0) { ?>
            <table class="table dt-table" style="width:100%">
              <thead>
                <th>Status</th>
                <th>Total</th>
                <th>Percentage</th>
              </thead>
              <tbody>
                <?php 
                $total_deals = 0;
                foreach ($deals_by_status_data as $row) {
                    $total_deals += $row['total'];
                }
                ?>
                <?php foreach ($deals_by_status_data as $row) { 
                    $percentage = ($total_deals > 0) ? round(($row['total'] / $total_deals) * 100, 2) : 0;
                ?>
                <tr>
                  <td>
                    <?php echo htmlspecialchars($row['status_name']); ?>
                  </td>
                  <td><span class="tw-font-semibold"><?php echo $row['total']; ?></span></td>
                  <td>
                    <div class="tw-flex tw-items-center">
                      <div class="tw-w-16 tw-bg-neutral-200 tw-rounded-full tw-h-2 tw-mr-2">
                        <div class="tw-bg-blue-600 tw-h-2 tw-rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                      </div>
                      <span class="tw-text-sm tw-text-neutral-600"><?php echo $percentage; ?>%</span>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td><strong>Total</strong></td>
                  <td><strong><?php echo $total_deals; ?></strong></td>
                  <td><strong>100%</strong></td>
                </tr>
              </tfoot>
            </table>
            <?php } else { ?>
            <p class="no-margin">No deals found for the selected period.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Prepare data for charts
$pieData = [["Status", "Deals"]];
$barData = [["Status", "Deals"]];
foreach ($deals_by_status_data as $row) {
    $pieData[] = [$row['status_name'], (int)$row['total']];
    $barData[] = [$row['status_name'], (int)$row['total']];
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
      title: 'Deals Distribution by Status',
      pieHole: 0.4,
      legend: {position: 'bottom'},
      chartArea: {width: '80%', height: '70%'}
    };
    var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
    chart.draw(data, options);
  }
  function drawBarChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($barData); ?>);
    var options = {
      chart: {
        title: 'Deals by Status',
      },
      bars: 'horizontal',
      legend: { position: 'none' },
      chartArea: {width: '80%', height: '70%'}
    };
    var chart = new google.charts.Bar(document.getElementById('bar_chart_div'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/deals_by_status'); ?>?period=' + period;
  }
</script>
<?php init_tail(); ?>
</body></html> 