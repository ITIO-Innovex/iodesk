<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
  <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-chart-pie tw-mx-2"></i> Leads by Source Report
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
              <i class="fa-solid fa-chart-simple tw-mx-2"></i> Source Distribution
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
              <i class="fa-solid fa-table tw-mx-2"></i> Leads by Source Details
            </h4>
            <?php if (isset($leads_by_source_data) && count($leads_by_source_data) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="desc">
              <thead>
                <th><?php echo _l('leads_source_table_name'); ?></th>
                <th><?php echo _l('total'); ?></th>
                <th><?php echo _l('percentage'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php 
                $total_leads = 0;
                foreach ($leads_by_source_data as $source) {
                    $total_leads += $source['total'];
                }
                ?>
                <?php foreach ($leads_by_source_data as $source) { 
                    $percentage = ($total_leads > 0) ? round(($source['total'] / $total_leads) * 100, 2) : 0;
                ?>
                <tr>
                  <td>
                     <a href="<?php echo admin_url('leads?source=' . $source['source_id']); ?>" target="_blank">
                       <?php echo e($source['source_name']); ?>
                     </a>
                  </td>
                  <td>
                    <span class="tw-font-semibold"><?php echo e($source['total']); ?></span>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center">
                      <div class="tw-w-16 tw-bg-neutral-200 tw-rounded-full tw-h-2 tw-mr-2">
                        <div class="tw-bg-blue-600 tw-h-2 tw-rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                      </div>
                      <span class="tw-text-sm tw-text-neutral-600"><?php echo $percentage; ?>%</span>
                    </div>
                  </td>
                  <td>
                    <a href="<?php echo admin_url('leads?source=' . $source['source_id']); ?>" 
                       class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700"
                       title="View leads from this source">
                      <i class="fa-regular fa-eye fa-lg"></i>
                    </a>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td><strong><?php echo _l('total'); ?></strong></td>
                  <td><strong><?php echo $total_leads; ?></strong></td>
                  <td><strong>100%</strong></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('no_leads_found_for_selected_period'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawCharts);

  function drawCharts() {
    drawPieChart();
    drawBarChart();
  }

  function drawPieChart() {
    var data = google.visualization.arrayToDataTable([
      ['Source', 'Leads'],
      <?php 
      if (isset($leads_by_source_data) && count($leads_by_source_data) > 0) {
          foreach ($leads_by_source_data as $source) {
              echo "['" . addslashes($source['source_name']) . "', " . $source['total'] . "],";
          }
      }
      ?>
    ]);

    var options = {
      title: 'Leads Distribution by Source',
      pieHole: 0.4,
      colors: ['#6495ED','#DE3163','#40E0D0','#CCCCFF','#FF7F50','#FFBF00','#9FE2BF'],
      legend: {position: 'bottom'},
      chartArea: {width: '80%', height: '70%'}
    };

    var chart = new google.visualization.PieChart(document.getElementById('pie_chart_div'));
    chart.draw(data, options);
  }

  function drawBarChart() {
    var data = google.visualization.arrayToDataTable([
      ['Source', 'Leads'],
      <?php 
      if (isset($leads_by_source_data) && count($leads_by_source_data) > 0) {
          foreach ($leads_by_source_data as $source) {
              echo "['" . addslashes($source['source_name']) . "', " . $source['total'] . "],";
          }
      }
      ?>
    ]);

    var options = {
      title: 'Leads by Source',
      bars: 'horizontal',
      colors: ['#6495ED','#DE3163','#40E0D0','#CCCCFF','#FF7F50','#FFBF00','#9FE2BF'],
      legend: {position: 'none'},
      chartArea: {width: '80%', height: '70%'},
      hAxis: {
        title: 'Number of Leads',
        minValue: 0
      },
      vAxis: {
        title: 'Sources'
      }
    };

    var chart = new google.visualization.BarChart(document.getElementById('bar_chart_div'));
    chart.draw(data, options);
  }

  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/leads_by_source'); ?>?period=' + period;
  }
</script>

<?php init_tail(); ?>
</body></html> 