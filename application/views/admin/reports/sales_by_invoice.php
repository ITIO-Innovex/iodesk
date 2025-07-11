<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
  <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-file-invoice-dollar tw-mx-2"></i> Sales by Invoice Report
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
        <!-- Pie Charts Section -->
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-6">
                <h5 class="tw-font-semibold tw-text-sm tw-text-neutral-600 tw-mb-3">Invoice Status Distribution</h5>
                <div id="status_pie_chart" style="width:100%; height:300px;"></div>
              </div>
              <div class="col-md-6">
                <h5 class="tw-font-semibold tw-text-sm tw-text-neutral-600 tw-mb-3">Approver Status Distribution</h5>
                <div id="approver_status_pie_chart" style="width:100%; height:300px;"></div>
              </div>
            </div>
          </div>
        </div>
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-table tw-mx-2"></i> Invoice List
            </h4>
            <?php if (isset($invoice_table) && count($invoice_table) > 0) { ?>
            <table class="table dt-table" style="width:100%">
              <thead>
                <th>Invoice #</th>
                <th>Client</th>
                <th>Status</th>
                <th>Approver Status</th>
                <th>Date</th>
                <th>Total</th>
              </thead>
              <tbody>
                <?php foreach ($invoice_table as $row) { ?>
                <tr>
                  <td><?php echo htmlspecialchars($row['id']); ?></td>
                  <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['status_label']); ?></td>
                  <td><?php echo htmlspecialchars($row['approver_status_label']); ?></td>
                  <td><?php echo htmlspecialchars($row['date']); ?></td>
                  <td><?php echo htmlspecialchars(number_format($row['total'], 2)); ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
            <p class="no-margin">No invoices found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
// Prepare data for pie charts
$statusPie = [["Status", "Count"]];
foreach ($status_distribution as $row) {
    $statusPie[] = [$row['label'], (int)$row['total']];
}
$approverPie = [["Approver Status", "Count"]];
foreach ($approver_status_distribution as $row) {
    $approverPie[] = [$row['label'], (int)$row['total']];
}
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawCharts);
  function drawCharts() {
    drawStatusPie();
    drawApproverPie();
  }
  function drawStatusPie() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($statusPie); ?>);
    var options = {
      title: 'Invoice Status',
      pieHole: 0.4,
      legend: {position: 'bottom'},
      chartArea: {width: '80%', height: '70%'}
    };
    var chart = new google.visualization.PieChart(document.getElementById('status_pie_chart'));
    chart.draw(data, options);
  }
  function drawApproverPie() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($approverPie); ?>);
    var options = {
      title: 'Approver Status',
      pieHole: 0.4,
      legend: {position: 'bottom'},
      chartArea: {width: '80%', height: '70%'}
    };
    var chart = new google.visualization.PieChart(document.getElementById('approver_status_pie_chart'));
    chart.draw(data, options);
  }
  
  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/sales_by_invoice'); ?>?period=' + period;
  }
</script>
<?php init_tail(); ?>
</body></html> 