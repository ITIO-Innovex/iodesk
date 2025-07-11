<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
  <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
	
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-users tw-mx-2"></i>  Staff Activity Report
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
              <i class="fa-solid fa-chart-bar tw-mx-2"></i>  Staff Activity Distribution
            </h4>
            <div id="bar_chart_div" style="width:100%; height:400px;"></div>
          </div>
        </div>
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-table tw-mx-2"></i> Staff Activity
            </h4>
            <?php if (isset($activity_by_staff_table) && count($activity_by_staff_table) > 0) { 
			
			
			?>
               <table class="table dt-table" style="width:100%">
                 <thead>
                  <tr>
                    <th>Staff</th>
                    <th>Activity Count</th>
                    <th>Last Activity</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($activity_by_staff_table as $row): ?>
                    
                    <tr>
                      <td><?php echo $row['staffid'];  ?></td>
                      <td><?php echo (int)$row['activity_count']; ?></td>
                      <td><?php echo $row['last_activity']; ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php } else { ?>
            <p class="no-margin">No Activity found for the selected period.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
// Prepare data for bar chart
$barData = [["Staff", "Activity"]];
foreach ($activity_by_staff_table as $row) {
    $barData[] = [$row['staffid'], (int)$row['activity_count']];
}
print_r($barData);
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['bar']});
  google.charts.setOnLoadCallback(drawBarChart);
  function drawBarChart() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($barData); ?>);
    var options = {
      chart: {
        title: 'Staff Activity',
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
    window.location.href = '<?php echo admin_url('reports/activity_by_staff'); ?>?period=' + period;
  }
</script>
<?php init_tail(); ?>
</body></html> 