<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <?php $this->load->view('admin/reports/includes/menu'); ?>
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-globe tw-mx-2"></i> Leads by Country Report
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
        <!-- Geo Chart Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-earth-americas tw-mx-2"></i> Country Map
            </h4>
            <div id="regions_div" style="width: 900px; height: 500px;"></div>
          </div>
        </div>
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center">
              <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
                <i class="fa-solid fa-table tw-mx-2"></i> Leads by Country Details
              </h4>
              <?php /*?><div>
                <button id="printTableBtn" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
              </div><?php */?>
            </div>
            <?php if (isset($leads_by_country_data) && count($leads_by_country_data) > 0) { ?>
            <table id="leadsByCountryTable" class="table dt-table display nowrap" style="width:100%" data-order-col="1" data-order-type="desc">
              <thead>
                <th>Country</th>
                <th>Total</th>
                <th>Percentage</th>
                </thead>
              <tbody>
                <?php 
                $total_leads = 0;
                foreach ($leads_by_country_data as $row) {
                    $total_leads += $row['total'];
                }
                ?>
                <?php foreach ($leads_by_country_data as $row) { 
                    $percentage = ($total_leads > 0) ? round(($row['total'] / $total_leads) * 100, 2) : 0;
                ?>
                <tr data-country-id="<?php echo $row['country_id']; ?>" data-country-name="<?php echo e($row['country_name']); ?>">
                  <td><?php echo e($row['country_name']); ?></td>
                  <td><span class="tw-font-semibold"><?php echo e($row['total']); ?></span></td>
                  <td>
                    <div class="tw-flex tw-items-center">
                      <div class="tw-w-16 tw-bg-neutral-200 tw-rounded-full tw-h-2 tw-mr-2">
                        <div class="tw-bg-blue-600 tw-h-2 tw-rounded-full" style="width: <?php echo $percentage; ?>%"></div>
                      </div>
                      <span class="tw-text-sm tw-text-neutral-600"><?php echo $percentage; ?>%</span>                    </div>                  </td>
                </tr>
                <?php } ?>
              </tbody>
              <tfoot>
                <tr>
                  <td><strong>Total</strong></td>
                  <td><strong><?php echo $total_leads; ?></strong></td>
                  <td><strong>100%</strong></td>
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

<!-- Country Details Modal -->
<?php //print_r($leads_by_country_data);
$result = [["Country", "Popularity"]];
foreach ($leads_by_country_data as $row) {
    $result[] = [$row['country_name'], (int)$row['total']];
}
echo $dt=json_encode($result, 1);
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages':['geochart'],
      });
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable(<?php echo $dt;?>);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }



 // Period change
  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/leads_by_country'); ?>?period=' + period;
  }
</script>


<?php init_tail(); ?>
</body></html> 