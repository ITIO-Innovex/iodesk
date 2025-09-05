<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
    <div class="widget-dragger"></div>
    <div class="row">
	
        <?php
         $initial_column = 'col-lg-4';
         
         ?>
		 
		 <?php if($departmentsID==8){ ?>
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
        <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 col-lg-6 tw-mb-2 sm:tw-mb-0">
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
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-record-vinyl menu-icon fa-2x text-warning"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Process Task</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Invoice"><?php echo ($row->process_count);?></span>
                    </span>
                </div>
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-circle-check menu-icon fa-2x text-success"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Success Task</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Invoice"><?php echo ($row->success_count);?></span>
                    </span>
                </div>
				
            </div>
        </div>
		<div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 col-lg-6 tw-mb-2 sm:tw-mb-0">
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
      function drawLeadsChart() {

        // Create the data table for Sarah's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Process', <?php echo $row->process_count;?>],
          ['Completed', <?php echo $row->success_count;?>]
          
        ]);

        // Set options for Sarah's pie chart.
        var options = {title:'Task by Status',
		               width:400,
					   height:400,
					   legend:'bottom',
					   colors: ['#008000', '#FEBE10']
					   };
                       
                       

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.PieChart(document.getElementById('Task_chart_div'));
        chart.draw(data, options);
      }
    </script> 
	<div id="Task_chart_div" style="border: 1px solid #ccc"></div>
            </div>
        </div>
        <?php } ?>
<?php }else{ ?>

        <?php if (is_staff_member()) { ?>
		<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 <?php echo e($initial_column); ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
			
			<?php
                  
				  $this->db->from(db_prefix() . 'leads');
				  if (is_super()) {
				  
				  if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
				  $this->db->where('company_id', $_SESSION['super_view_company_id']);
				  }
				  
				  }elseif (is_admin()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
				  $this->db->where('company_id', get_staff_company_id());
				  $this->db->where('assigned', get_staff_user_id());
				  }
                    
                    $this->db->select("COUNT(CASE WHEN status = 2 AND is_deal = 0  THEN 1 END) AS unassign_lead, COUNT(CASE WHEN status = 3 AND is_deal = 0 THEN 1 END) AS assign_lead, COUNT(CASE WHEN status = 4 AND is_deal = 0 THEN 3 END) AS junk_lead, COUNT(CASE WHEN status = 1 AND is_deal = 0  THEN 1 END) AS hot_lead");
                    
					
                    $row = $this->db->get()->row();
			
					//echo $this->db->last_query();echo $row->new_lead;exit;
                  ?>
               
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2">
                        <i class="fa fa-tty menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Leads Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Completed Leads"><?php echo $row->hot_lead;?></span> / <span title="Total Leads"><?php echo ($row->unassign_lead + $row->assign_lead + $row->junk_lead + $row->hot_lead);?></span>
                    </span>
                </div>
				
				 <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Sarah's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawLeadsChart);

     

      // Callback that draws the pie chart for Sarah's pizza.
      function drawLeadsChart() {

        // Create the data table for Sarah's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Hot', <?php echo $row->hot_lead;?>],
          ['Assign', <?php echo $row->assign_lead;?>],
		  ['UnAssign', <?php echo $row->unassign_lead;?>],
          ['Junk', <?php echo $row->junk_lead;?>]
          
        ]);

        // Set options for Sarah's pie chart.
        var options = {title:'Leads stats by Status',
		               width:300,
					   height:300,
					   legend:'bottom',
					   colors: ['#008000', '#FEBE10','#00BFFF','#FF0000']
					   };
                       
                       

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.PieChart(document.getElementById('Leads_chart_div'));
        chart.draw(data, options);
      }


    </script>
                <div id="Leads_chart_div" style="border: 1px solid #ccc"></div>
                
            </div>
        </div>
        <?php } ?>
		
		
		<?php if (is_staff_member()) { ?>
		<div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 <?php echo e($initial_column); ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
			
			<?php
                  $this->db->from(db_prefix() . 'leads');
                  if (is_super()) {
				  if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
				  $this->db->where('company_id', $_SESSION['super_view_company_id']);
				  }
				  }elseif (is_admin()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
				  $this->db->where('company_id', get_staff_company_id());
				  $this->db->where('assigned', get_staff_user_id());
				  }
                   
				    $this->db->where('is_deal', 1);
				    $this->db->group_by('deal_stage');
                    $this->db->select(" deal_stage, COUNT(*) AS total,");
                    $data = $this->db->get()->result_array();
					
$statuses = $this->leads_model->get_deal_form_order();
	//print_r($statuses);	
	$totalcnt=0;
	$graphdata="";			
foreach ($data as $item) {
   if(isset($statuses[$item['deal_stage']])&&$statuses[$item['deal_stage']]){
   $totalcnt=($totalcnt + $item['total']);
   $graphdata.="['".get_deals_stage_title($statuses[$item['deal_stage']])."', ".$item['total']."],";	
   }
   
}

                  $this->db->from(db_prefix() . 'leads');
                  if (is_super()) {
				  if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
				  $this->db->where('company_id', $_SESSION['super_view_company_id']);
				  }
				  }elseif (is_admin()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
				  $this->db->where('company_id', get_staff_company_id());
				  $this->db->where('assigned', get_staff_user_id());
				  }
$this->db->where('is_deal', 1);
$this->db->group_by('deal_stage_status');
$this->db->select(" deal_stage_status, COUNT(*) AS ftotal,");
$this->db->where('deal_stage_status', 1);
$this->db->or_where('deal_stage_status', 2);
//echo $this->db->get_compiled_select(); exit;
$data = $this->db->get()->result_array();
//echo $this->db->last_query();exit;
$completed=0;
foreach ($data as $item) {
   if(isset($item['deal_stage_status'])&&$item['deal_stage_status']){
   $totalcnt=($totalcnt + $item['ftotal']);
	   if($item['deal_stage_status']==1){
	   $graphdata.="['Completed', ".$item['ftotal']."],";
	   $completed=$item['ftotal'];
	   }else{
	   $graphdata.="['Lost', ".$item['ftotal']."],";
	   }	
   }
   
}

//echo $totalcnt;	
//echo $graphdata;				

                  ?>
               
				<div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2">
                        <i class="fa-solid fa-handshake menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Deal Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Completed Deals"><?php echo $completed;?></span> / <span title="Total Deals"><?php echo $totalcnt;?></span>
                    </span>
                </div>
				
				 <script type="text/javascript">

      // Load Charts and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Draw the pie chart for Sarah's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawDealChart);

     

      // Callback that draws the pie chart for Sarah's pizza.
      function drawDealChart() {

        // Create the data table for Sarah's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          <?php echo $graphdata;?>
        ]);

        // Set options for Sarah's pie chart.
        var options = {title:'Deal stats by Status',
		               width:300,
					   height:300,
					   legend:'bottom',
					   colors: ['#00BFFF','#FFD700', '#FEBE10','#008000']
					   };
                       
                       

        // Instantiate and draw the chart for Sarah's pizza.
        var chart = new google.visualization.PieChart(document.getElementById('Deal_chart_div'));
        chart.draw(data, options);
      }


    </script>
                <div id="Deal_chart_div" style="border: 1px solid #ccc"></div>
                
            </div>
        </div>
        <?php } ?>
		
		
        <?php if (is_staff_member()) { ?>
        <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 <?php echo e($initial_column); ?> tw-mb-2 sm:tw-mb-0">
            <div class="top_stats_wrapper">
                <?php
                  $this->db->from(db_prefix() . 'invoices');
                    if (is_super()) {
					if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
				  $this->db->where('company_id', $_SESSION['super_view_company_id']);
				  }
				  }elseif (is_admin()) {
				  $this->db->where('company_id', get_staff_company_id());
				  }else{
				  $this->db->where('company_id', get_staff_company_id());
				  $this->db->where('sale_agent', get_staff_user_id());
				  }
                    
                    $this->db->select("COUNT(CASE WHEN status = 1 and approver_status=1  THEN 1 END) AS new_count, COUNT(CASE WHEN status = 2 and approver_status=1 THEN 1 END) AS process_count, COUNT(CASE WHEN status = 2 and approver_status=2 THEN 1 END) AS success_count");
                    
					
                    $row = $this->db->get()->row();
			
				//echo $this->db->last_query();exit;
				//echo $row->new_count;
                   
                  ?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-receipt menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Invoice Status</span>
                    </div>
                    <span class="tw-font-semibold tw-text-neutral-600 tw-shrink-0">
<span title="Success Invoice"><?php echo $row->success_count;?></span> / <span title="Total Invoice"><?php echo ($row->success_count + $row->new_count + $row->process_count);?></span>
                    </span>
                </div>
				<script type="text/javascript">


        // Draw the pie chart for the Anthony's pizza when Charts is loaded.
      google.charts.setOnLoadCallback(drawInvoiceChart);
      // Callback that draws the pie chart for Anthony's pizza.
      function drawInvoiceChart() {

        // Create the data table for Anthony's pizza.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['New', <?php echo $row->new_count;?>],
          ['Process', <?php echo $row->process_count;?>],
          ['Completed', <?php echo $row->success_count;?>]
        ]);

        // Set options for Anthony's pie chart.
        var options = {
		               title:'Invoice Stats By Status',
					   legend:'bottom',
					   width:300, 
					   height:300,
					   colors: ['#00BFFF','#FFD700', '#008000']
					   };
                       
                      

        // Instantiate and draw the chart for Invoice
        var chart = new google.visualization.PieChart(document.getElementById('Invoice_chart_div'));
        chart.draw(data, options);
      }
    </script>
				 <div id="Invoice_chart_div" style="border: 1px solid #ccc"></div>
            </div>
        </div>
        <?php } ?>
		
		<?php if (is_staff_member()) { ?>
        <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 col-lg-12 tw-mb-2 sm:tw-mb-0 mtop10">
            <div class="top_stats_wrapper">
<?php
$this->db->from(db_prefix() . 'projects');
//if (!is_admin()) {$this->db->where('addedfrom', get_staff_user_id());}
$year = $_GET['year'] ?? date('Y');
?>
                <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                    <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate  tw-my-2">
                        <i class="fa-solid fa-chart-simple menu-icon fa-2x"></i>
                        <span class="tw-truncate tw-text-xl">&nbsp;&nbsp;Overall Performance</span>
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
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Month', 'Leads', 'Deals', 'Invoice'],
<?php foreach ($months as $num => $name) {

$monthyear=$name." - ". $year;
$monthyearnum=$year."-".$num; 
$swhere="";
$iwhere="";
                  if (is_super()) {
				  if(isset($_SESSION['super_view_company_id'])&&$_SESSION['super_view_company_id']){
				  $swhere= ' AND leads.company_id = '.$_SESSION['super_view_company_id'];
				  }
				  }elseif (is_admin()) {
				  $swhere= ' AND leads.company_id = '.get_staff_company_id();
				  $iwhere= ' AND company_id = '.get_staff_company_id();
				  }else{
				  $swhere= ' AND leads.company_id = '.get_staff_company_id().' AND assigned = '.get_staff_user_id();
				  $iwhere= ' AND company_id = '.get_staff_company_id().' AND addedfrom = '.get_staff_user_id();
				  }
	
	//Count Total Added Leads Except Junk
	$_where=' `dateadded` LIKE "%' . $monthyearnum . '%" AND leads.status <> 4 AND `is_deal` = 0 '.$swhere; //exit;
	$leads = total_rows(db_prefix() . 'leads', $_where);
	
		
	//Count Total Added Deals Except Junk
	$_where=' `dateadded` LIKE "%' . $monthyearnum . '%" AND `is_deal` = 1 '.$swhere;//exit;
	$deals = total_rows(db_prefix() . 'leads', $_where);
	
	
	//Count Total Added Invoice
	$_where=' `datecreated` LIKE "%' . $monthyearnum . '%" '.$iwhere;
	$invoice = total_rows(db_prefix() . 'invoices', $_where);
	//echo $this->db->last_query();exit;
	
	
?>		  
 ['<?php echo $monthyear;?>', <?php echo $leads;?>, <?php echo $deals;?>, <?php echo $invoice;?>],
 <?php } ?>         
		  
        ]);

        var options = {
          chart: {
            title: '',
            subtitle: 'Leads, Deals, and Invoice: <?php echo $year;?>',
          },
          bars: 'vertical',
          vAxis: {format: 'decimal'},
          height: 400,
          colors: ['#00BFFF','#FFD700', '#008000']
        };

        var chart = new google.charts.Bar(document.getElementById('chart_div'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

        var btns = document.getElementById('btn-group');

        btns.onclick = function (e) {

          if (e.target.tagName === 'BUTTON') {
            options.vAxis.format = e.target.id === 'none' ? '' : e.target.id;
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      }

    </script>
				    <div id="chart_div"></div>
  
    
            </div>
        </div>
        <?php } ?>
		
		
<?php } ?>        
        
    </div>
</div>