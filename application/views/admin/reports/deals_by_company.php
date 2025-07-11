<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
  <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-building tw-mx-2"></i> Deals by Company Report
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
        <!-- Bar Chart Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-chart-bar tw-mx-2"></i> Company Distribution
            </h4>
            <div id="bar_chart_div" style="width:100%; height:400px;"></div>
          </div>
        </div>
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-table tw-mx-2"></i> Deals by Company Details
            </h4>
            <?php if (isset($deals_by_company_data) && count($deals_by_company_data) > 0) { ?>
            <table class="table dt-table" style="width:100%">
              <thead>
                <th>Company</th>
                <th>Total Deals</th>
                <th>Percentage</th>
              </thead>
              <tbody>
                <?php 
                $total_deals = 0;
                foreach ($deals_by_company_data as $row) {
                    $total_deals += $row['total'];
                }
                ?>
                <?php foreach ($deals_by_company_data as $row) { 
                    $percentage = ($total_deals > 0) ? round(($row['total'] / $total_deals) * 100, 2) : 0;
                ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['company_name']); ?></td>
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
// Prepare data for bar chart
$barData = [["Company", "Deals"]];
foreach ($deals_by_company_data as $row) {
    $barData[] = [$row['company_name'], (int)$row['total']];
}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawBarChart);
  function drawBarChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($barData); ?>);
    var options = {
      chart: {
        title: 'Deals by Company',
      },
      bars: 'horizontal',
      height: 400,
      legend: { position: 'none' },
      chartArea: {width: '80%', height: '70%'}
    };
    var chart = new google.charts.Bar(document.getElementById('bar_chart_div'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
  }
  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/deals_by_company'); ?>?period=' + period;
  }
</script>
<?php init_tail(); ?>
</body></html> 