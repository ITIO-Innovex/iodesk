<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($employees); 

?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  <?php echo $title;?> [ <?php echo get_staff_company_name(); ?> ] </span></h4>
  
    <div class="row tw-mt-2" style="clear:both">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg "><?php echo $title;?></h4>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', { packages:["orgchart"] });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {

            var data = new google.visualization.DataTable();
            data.addColumn("string", "Name");
            data.addColumn("string", "Manager");
            data.addColumn("string", "ToolTip");

            data.addRows([
                <?php foreach($employees as $row): 
				$branchname=$row['branch'] ? get_staff_branch_name($row['branch']):"";
				$designation_title=$row['designation_id'] ? get_staff_designations_name($row['designation_id']):"";
				?>
                    [{v:"<?= $row['staffid'] ?>", f:"<?= $row['full_name'] ?><div style='color:red; font-style:italic'><?php echo $designation_title; ?>"},"<?= $row['reporting_manager'] ?>", "<?php echo $branchname;?>"], 
                <?php endforeach; ?>
            ]);

            var chart = new google.visualization.OrgChart(document.getElementById("chart_div"));
            chart.draw(data, {allowHtml:true});
        }
    </script>
</head>
<div class="row">
<div class="table-responsive">
    <div id="chart_div"></div>
</div>
</div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>


