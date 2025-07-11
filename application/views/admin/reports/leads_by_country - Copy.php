<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12 tw-mb-8">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-globe tw-mx-2"></i> Leads by Country Report
        </h4>
        <!-- Back to Reports Button -->
        <div class="tw-absolute tw-top-0 tw-right-0 tw-mr-4 tw-mb-4">
          <a href="<?php echo admin_url('reports'); ?>" class="btn btn-default">
            <i class="fa-solid fa-arrow-left tw-mr-2"></i> Back to Reports
          </a>
        </div>
        <!-- Filters Row -->
        <div class="panel_s">
          <div class="panel-body">
            <div class="row tw-items-end">
              <div class="col-md-4">
                <label for="periodSelect" class="control-label">Report for period:</label>
                <select name="period" id="periodSelect" class="form-control" onchange="changePeriod(this.value)">
                  <option value="this_month" <?php echo ($selected_period == 'this_month') ? 'selected' : ''; ?>>This Month</option>
                  <option value="last_month" <?php echo ($selected_period == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
                  <option value="this_week" <?php echo ($selected_period == 'this_week') ? 'selected' : ''; ?>>This Week</option>
                  <option value="last_week" <?php echo ($selected_period == 'last_week') ? 'selected' : ''; ?>>Last Week</option>
                  <option value="current_year" <?php echo ($selected_period == 'current_year') ? 'selected' : ''; ?>>Current Year</option>
                  <option value="all" <?php echo ($selected_period == 'all') ? 'selected' : ''; ?>>All</option>
                </select>
              </div>
              <div class="col-md-3">
                <label for="topNSelect" class="control-label">Show Top:</label>
                <select id="topNSelect" class="form-control" onchange="updateTopN()">
                  <option value="5">Top 5</option>
                  <option value="10">Top 10</option>
                  <option value="20">Top 20</option>
                  <option value="all" selected>All</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <!-- Geo Chart Section -->
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-earth-americas tw-mx-2"></i> Country Map
            </h4>
            <div id="geo_chart_div" style="width:100%; height:400px;"></div>
          </div>
        </div>
        <!-- Table Section -->
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center">
              <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
                <i class="fa-solid fa-table tw-mx-2"></i> Leads by Country Details
              </h4>
              <div>
                <button id="printTableBtn" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
              </div>
            </div>
            <?php if (isset($leads_by_country_data) && count($leads_by_country_data) > 0) { ?>
            <table id="leadsByCountryTable" class="table dt-table display nowrap" style="width:100%" data-order-col="1" data-order-type="desc">
              <thead>
                <th><?php echo _l('country'); ?></th>
                <th><?php echo _l('total'); ?></th>
                <th><?php echo _l('percentage'); ?></th>
                <th><?php echo _l('options'); ?></th>
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
                      <span class="tw-text-sm tw-text-neutral-600"><?php echo $percentage; ?>%</span>
                    </div>
                  </td>
                  <td>
                    <button class="btn btn-xs btn-info view-country-details" data-country-id="<?php echo $row['country_id']; ?>" data-country-name="<?php echo e($row['country_name']); ?>">
                      <i class="fa fa-eye"></i> Details
                    </button>
                    <a href="<?php echo admin_url('leads?country=' . $row['country_id']); ?>" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-external-link"></i> View All</a>
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

<!-- Country Details Modal -->
<div class="modal fade" id="countryDetailsModal" tabindex="-1" role="dialog" aria-labelledby="countryDetailsModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="countryDetailsModalLabel">Country Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div id="countryDetailsContent" class="table-responsive"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  var allCountryData = <?php echo json_encode($leads_by_country_data); ?>;
  var totalLeads = <?php echo isset($total_leads) ? $total_leads : 0; ?>;
  var selectedPeriod = '<?php echo $selected_period; ?>';

  // GeoChart
  google.charts.load('current', {'packages':['geochart']});
  google.charts.setOnLoadCallback(drawGeoChart);

  function drawGeoChart(topN) {
    var dataArr = [['Country', 'Leads', { role: 'tooltip', type: 'string', p: { html: true } }]];
    var shownData = allCountryData.slice();
    if (topN && topN !== 'all') {
      shownData = shownData.slice(0, parseInt(topN));
    }
    shownData.forEach(function(row) {
      var percent = totalLeads > 0 ? ((row.total / totalLeads) * 100).toFixed(2) : 0;
      var tooltip = '<div style="padding:8px 12px"><b>' + row.country_name + '</b><br>Leads: ' + row.total + '<br>Percentage: ' + percent + '%</div>';
      dataArr.push([row.country_name, row.total, tooltip]);
    });
    var data = google.visualization.arrayToDataTable(dataArr);
    var options = {
      colorAxis: {colors: ['#b3c6ff', '#0033cc']},
      legend: {textStyle: {color: '#444'}},
      backgroundColor: '#f8fafc',
      datalessRegionColor: '#f1f1f1',
      defaultColor: '#f5f5f5',
      chartArea: {width: '90%', height: '80%'},
      tooltip: { isHtml: true }
    };
    var chart = new google.visualization.GeoChart(document.getElementById('geo_chart_div'));
    chart.draw(data, options);
    // Click event
    google.visualization.events.addListener(chart, 'regionClick', function(e) {
      var countryName = e.region;
      var found = allCountryData.find(function(row) { return row.country_name === countryName; });
      if (found) {
        showCountryDetails(found.country_id, found.country_name);
      }
    });
  }

  // Top N Dropdown
  function updateTopN() {
    var topN = document.getElementById('topNSelect').value;
    // Update table
    var table = $('#leadsByCountryTable').DataTable();
    if (topN === 'all') {
      table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        this.visible(true);
      });
    } else {
      table.rows().every(function(rowIdx, tableLoop, rowLoop) {
        if (rowIdx < parseInt(topN)) {
          this.visible(true);
        } else {
          this.visible(false);
        }
      });
    }
    // Update map
    drawGeoChart(topN);
  }

  // DataTable with export buttons
  $(function() {
    var dt = $('#leadsByCountryTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'copyHtml5',
        'excelHtml5',
        'csvHtml5',
        'pdfHtml5',
        'print'
      ],
      paging: false,
      info: false,
      searching: false,
      ordering: true,
      responsive: true,
      scrollX: true
    });
    // Print button
    $('#printTableBtn').on('click', function() {
      dt.button('4').trigger();
    });
    // Top N initial
    updateTopN();
    // Row click for modal
    $('#leadsByCountryTable').on('click', '.view-country-details', function() {
      var countryId = $(this).data('country-id');
      var countryName = $(this).data('country-name');
      showCountryDetails(countryId, countryName);
    });
  });

  // Show country details modal
  function showCountryDetails(countryId, countryName) {
    $('#countryDetailsModalLabel').text('Leads in ' + countryName);
    $('#countryDetailsContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i> Loading...</div>');
    $('#countryDetailsModal').modal('show');
    $.get('<?php echo admin_url('reports/leads_by_country_details'); ?>', {
      country_id: countryId,
      period: selectedPeriod
    }, function(resp) {
      var html = '';
      if (resp && resp.success && resp.leads && resp.leads.length > 0) {
        html += '<table class="table table-bordered table-striped"><thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Date Added</th></tr></thead><tbody>';
        resp.leads.forEach(function(lead) {
          html += '<tr>';
          html += '<td>' + lead.name + '</td>';
          html += '<td>' + lead.email + '</td>';
          html += '<td>' + lead.status_name + '</td>';
          html += '<td>' + lead.dateadded + '</td>';
          html += '</tr>';
        });
        html += '</tbody></table>';
      } else {
        html = '<p>No leads found for this country and period.</p>';
      }
      $('#countryDetailsContent').html(html);
    }, 'json');
  }

  // Period change
  function changePeriod(period) {
    window.location.href = '<?php echo admin_url('reports/leads_by_country'); ?>?period=' + period;
  }
</script>

<?php init_tail(); ?>
</body></html> 