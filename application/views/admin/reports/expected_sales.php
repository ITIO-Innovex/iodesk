<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
          <i class="fa-solid fa-chart-pie tw-mx-2"></i> Expected Sales Report
        </h4>
		
        <div class="panel_s">
          <div class="panel-body">
            <div id="chart_div" style="width:400; height:300"></div>
          </div>
        </div>
        <?php //if (is_staff_member()) { ?>
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
				   
  
    
            </div>
        </div>
        <?php // } ?>
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-md tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center">
              <i class="fa-solid fa-table tw-mx-2"></i> Deal Statuses
            </h4>
            <?php if (isset($deal_statuses) && count($deal_statuses) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
              <thead>
                <th><?php echo _l('leads_status_table_name'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($deal_statuses as $status) { ?>
                <tr>
                  <td>
                    <i class="fa-solid fa-palette" style="color:<?php echo e($status['color']); ?>;"></i>&nbsp;&nbsp;
                    <a href="#"
                      data-color="<?php echo e($status['color']); ?>"
                      data-name="<?php echo e($status['name']); ?>"
                      data-order="<?php echo e($status['statusorder']); ?>">
                      <?php echo e($status['name']); ?>
                    </a><br />
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                        data-color="<?php echo e($status['color']); ?>"
                        data-name="<?php echo e($status['name']); ?>"
                        data-order="<?php echo e($status['statusorder']); ?>"
                        class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="#" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                        <i class="fa-regular fa-trash-can fa-lg"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('lead_statuses_not_found'); ?></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php init_tail(); ?>
</body></html> 