<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if (is_staff_member()) { ?>
<?php
                  $this->db->from(db_prefix() . 'user_utility_forms');
                    if (is_super()) {
					if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
				  $this->db->where('company_id', $_SESSION['super_view_company_id']);
				  }
				  }elseif (is_admin()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
				  $this->db->where('company_id', get_staff_company_id());
				  $this->db->where('created_by', get_staff_user_id());
				  }
                    
$this->db->select("COUNT(CASE WHEN status = 1   THEN 1 END) AS success_count, COUNT(CASE WHEN status = 2  THEN 1 END) AS process_count");
                    
					
                    $row = $this->db->get()->row();
			
				//echo $this->db->last_query();exit;
				//echo $row->new_count;
                   
                  ?>
        <div class="quick-stats-leads col-xs-12 col-md-12 col-sm-12 col-lg-12 tw-mb-2 sm:tw-mb-0 mtop10">
		         <div class="row tw-mb-2 sm:tw-mb-0">
				 <div class=" col-sm-4 m-1 tw-pb-1">
                <div class="top_stats_wrapper">
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-circle-info menu-icon fa-2x text-info"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Total Task</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Invoice"><?php echo ($row->success_count  + $row->process_count);?></span>
                    </span>
                </div>
				</div>
				</div>
				<div class=" col-sm-4 m-1 tw-pb-1">
				<div class="top_stats_wrapper">
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-record-vinyl menu-icon fa-2x text-warning"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Process Task</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Invoice"><?php echo ($row->process_count);?></span>
                    </span>
                </div>
				</div></div>
				<div class=" col-sm-4 m-1 tw-pb-1">
				<div class="top_stats_wrapper">
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-circle-check menu-icon fa-2x text-success"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Completed Task</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Invoice"><?php echo ($row->success_count);?></span>
                    </span>
                </div>
				</div></div>
				</div>
            </div>
        </div>
		<div class="row m-1">
		<div class="quick-stats-leads col-sm-4 tw-mb-2 tw-mt-2 sm:tw-mb-0">
		<div class="row tw-pr-2 tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-receipt menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Task Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Task"><?php echo $row->success_count;?></span> / <span title="Process Task"><?php echo ($row->success_count  + $row->process_count);?></span>
                    </span>
                </div>
				<script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Sarah's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawLeadsChart);

     

      // Callback that draws the pie chart for Sarah's pizza.
      // Make sure google.charts.load is already called ONCE on page
google.charts.setOnLoadCallback(drawLeadsChart);

function drawLeadsChart() {

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Status');
    data.addColumn('number', 'Tasks');

    data.addRows([
        ['Process', <?php echo (int)$row->process_count;?>],
        ['Completed', <?php echo (int)$row->success_count;?>]
    ]);

    var options = {
        title: 'Task by Status',
        legend: { position: 'bottom' },
        chartArea: {
            width: '90%',
            height: '75%'
        },
        colors: ['#FEBE10', '#008000'],
        tooltip: { text: 'percentage' },
        pieHole: 0 // 0 = normal pie | 0.4 = donut
    };

    var chart = new google.visualization.PieChart(
        document.getElementById('Task_chart_div')
    );

    chart.draw(data, options);

    //  Responsive redraw
    window.addEventListener('resize', function () {
        chart.draw(data, options);
    });
}

    </script> 
	<div id="Task_chart_div" style="width:100%; height:450px;border: 1px solid #ccc"></div>
            </div>
			</div>
        </div>
		<div class="quick-stats-leads col-xs-12 col-md-8 col-sm-8 col-lg-8 tw-mb-2 tw-mt-2 sm:tw-mb-0">
         <div class="row tw-pl-2 tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
<?php
$year = $_GET['year'] ?? date('Y');
?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-chart-simple menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Overall Task Performance</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<label><select name="leads_length" id="yearSelect" aria-controls="leads" class="form-control input-sm">
<?php
for ($i = 2020; $i <= 2030; $i++) {
?>
<option value="<?php echo $i;?>" <?php if($i==$year){ ?> selected="selected" <?php } ?> ><?php echo $i;?></option>
<?php
}
?>
</select></label>
                    </span>
                </div>
<?php


// Numeric month values with leading zeros
$months = [
    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr',
    '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug',
    '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'
];

?>

<script type="text/javascript">

 google.charts.load('current', {'packages':['bar']});

      // Make sure google.charts.load('current', {'packages':['bar']}) is already called ONCE
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

    var data = google.visualization.arrayToDataTable([
        ['Month', 'Total', 'Process', 'Completed'],
<?php foreach ($months as $num => $name) {

$monthyear=$name." - ". $year;
$monthyearnum=$year."-".$num; 

// Process Task
$_where = [
    'status'     => 2,
    'company_id' => get_staff_company_id(),
    'created_by' => get_staff_user_id(),
];
$this->db->like('date_created', $monthyearnum);
$process_task = total_rows(db_prefix() . 'user_utility_forms', $_where);

// Completed Task
$_where = [
    'status'     => 1,
    'company_id' => get_staff_company_id(),
    'created_by' => get_staff_user_id(),
];
$this->db->like('date_created', $monthyearnum);
$completed_task = total_rows(db_prefix() . 'user_utility_forms', $_where);

// Total Task
$_where = [
    'company_id' => get_staff_company_id(),
    'created_by' => get_staff_user_id(),
];
$this->db->like('date_created', $monthyearnum);
$total_task = total_rows(db_prefix() . 'user_utility_forms', $_where);
?>
        ['<?php echo $monthyear;?>',
         <?php echo (int)$total_task;?>,
         <?php echo (int)$process_task;?>,
         <?php echo (int)$completed_task;?>],
<?php } ?>
    ]);

    var mobile = window.innerWidth < 768;

    var options = {
        chart: {
            subtitle: 'Tasks: <?php echo $year;?>',
        },
        bars: 'vertical',
        vAxis: { format: 'decimal' },
        height: mobile ? 300 : 450,
        chartArea: {
            width: '85%',
            height: '70%'
        },
        colors: ['#00BFFF','#FFD700','#008000']
    };

    var chart = new google.charts.Bar(document.getElementById('chart_divXX'));

    chart.draw(data, google.charts.Bar.convertOptions(options));

    //  Responsive Redraw
    window.addEventListener('resize', function () {
        options.height = window.innerWidth < 768 ? 300 : 450;
        chart.draw(data, google.charts.Bar.convertOptions(options));
    });

    // Format buttons
    var btns = document.getElementById('btn-group');
    if (btns) {
        btns.onclick = function (e) {
            if (e.target.tagName === 'BUTTON') {
                options.vAxis.format = e.target.id === 'none' ? '' : e.target.id;
                chart.draw(data, google.charts.Bar.convertOptions(options));
            }
        };
    }
}


    </script>
				    <div id="chart_divXX" style="width:100%; height:450px;"></div>
  
    
            </div>
			</div>
        </div>
		</div>	
           
        <?php } ?>
