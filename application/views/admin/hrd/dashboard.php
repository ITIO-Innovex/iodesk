<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
// Get Attendance Time
$in_time  = $attendance[0]['in_time']  ?? '';
$out_time = $attendance[0]['out_time'] ?? '';
//print_r($attendance_stats);
//print_r($status_counter);
$fullday=$attendance_stats['fullday'] ?? 0;
$half=$attendance_stats['half'] ?? 0;
$absent=$attendance_stats['absent'] ?? 0;
$totaldays=($fullday +($half / 2)) ?? 0;

// for display attendance checkin / checkout button
$attendance_display_status=attendance_display_status();
?>
<style>
<?php /*?>.box-gradient-bg1{background-image: radial-gradient(circle, #eaedb4 0%, #daeaed 100%);}
.box-gradient-bg2{
    background: conic-gradient(from 210deg, rgb(197, 187, 184) 0deg, rgb(197, 187, 184) 24deg, rgb(184, 181, 184) 24deg, rgb(184, 181, 184) 48deg, rgb(169, 175, 183) 48deg, rgb(169, 175, 183) 72deg, rgb(154, 168, 181) 72deg, rgb(154, 168, 181) 96deg, rgb(139, 161, 179) 96deg, rgb(139, 161, 179) 120deg, rgb(125, 152, 175) 120deg, rgb(125, 152, 175) 144deg, rgb(112, 144, 171) 144deg, rgb(112, 144, 171) 168deg, rgb(101, 135, 166) 168deg, rgb(101, 135, 166) 192deg, rgb(135 156 178 / 82%) 192deg, rgb(92, 126, 161) 216deg, rgb(85 117 155 / 80%) 216deg, rgb(85, 117, 155) 240deg, rgb(81 108 148 / 58%) 240deg, rgb(81 108 148 / 46%) 264deg, rgb(79 99 141 / 55%) 264deg, rgb(79 99 141 / 24%) 288deg, rgb(183 195 245) 288deg, rgb(234 225 228) 312deg, rgb(202 243 213) 312deg, rgb(238 237 241) 336deg, rgb(228 222 145) 336deg, rgb(221 215 231) 360deg);
}
.box-gradient-bg3{background-image: linear-gradient(to right top, #dcb8cc, #c7adc4, #b2a2ba, #9e97af, #8b8ca2, #8b8ca2, #8b8ca2, #8b8ca2, #9e97af, #b2a2ba, #c7adc4, #dcb8cc);}<?php */?>

.box-gradient-bg11{background-image: radial-gradient(circle, #eaedb4 0%, #daeaed 100%);}
.box-gradient-bg12{background-image: radial-gradient(circle, #a6dcce 0%, #edbbc663 100%);}
.box-gradient-bg13{background-image: radial-gradient(circle, #f5f2b7 0%, #ba9e9c 100%);}
.box-gradient-bg14{background-image: radial-gradient(circle, #eaedb4 0%, #daeaed 100%);}
::selection {
  background: #ff4401;
  color: #fff;
}
::-webkit-scrollbar {
  width: 5px;
}

::-webkit-scrollbar-track {
  background: #ccc;
}

::-webkit-scrollbar-thumb {
  background: #888;
}



</style>
<div id="wrapper">
<?php if (!empty($maintenance_notice)) : ?>

    <?php foreach ($maintenance_notice as $notice) : ?>
        <div class="top_stats_wrapper tw-my-2 tw-mx-2" style="background-color: <?= $notice->background_color ?>; 
                    color: <?= $notice->text_color ?>;">
            <strong><?= $notice->title ?></strong> : <br />
<?= $notice->message ?>
            <?= $notice->message ?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <span class="pull-left display-block mright5"><i class="fa-solid fa-chart-line tw-mr-2"></i><?php echo $title; ?> - Login Time :: <?php echo $in_time; ?></span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span>
                    </h4>
					
                </div>
            </div>
        </div>
<div class="pb-[10px]">
  <div class="tw-flex tw-justify-end tw-gap-2 tw-my-2">
 <div class="tw-mt-2" style="margin-left: 30px;">
        <!-- Place this where you want the clock to appear -->
  <div class="digital-clock" aria-live="polite" title="Local time">
    <div class="dc-time" id="dc-hours-min">00:00</div>
    <div class="dc-seconds" id="dc-seconds">:00</div>
    <div class="dc-ampm" id="dc-ampm">AM</div>
  </div>
  <?php if($attendance_display_status==1){ ?>
  <?php if(isset($in_time)&&$in_time){ ?>
 <button type="submit" class="digital-btn btn-success attendance-submit"  name="attendance" data-mode="Out" data-toggle="tooltip" data-title="Your Mark in Time : <?php echo date("Y F d");?> <?php echo $in_time;?>" data-original-title="" ><i class="fa-solid fa-right-from-bracket"></i> Mark out </button>
  <?php }else{ ?>
   <button type="submit" class="digital-btn btn-warning attendance-submit"  name="attendance" data-mode="In" > Mark in <i class="fa-solid fa-right-from-bracket fa-rotate-180"></i></button>
  <?php } ?>
  <a  href="<?php echo admin_url('hrd/dar');?>" class="digital-btn btn-info" title="Add your Daily Activity Report (DAR)"> DAR <i class="fa-solid fa-file-pen"></i></a>
  <?php }else{ ?>
 
  <?php } ?>
    </div>
  </div>
  </div> 
        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="widget-card bg-success text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $totaldays; ?></h3>
                            <p>Total Present</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget-card bg-warning text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $fullday; ?></h3>
                            <p>Total Full Present</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget-card bg-info text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $half; ?></h3>
                            <p>Total Half Present</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget-card bg-danger text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-calendar"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $absent; ?></h3>
                            <p>Total Absent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title"><i class="fa-solid fa-circle-question  text-warning"></i> Today's Thought
<?php if(is_admin()){ ?>
<a href="<?php echo admin_url('hrd/setting/todays_thought');?>" target="_blank" title="Manage Today's Thought"><i class="fa-solid fa-gear fa-beat-fade pull-right text-success"></i></a>
<?php } ?>						
						</h4>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <i class="fas fa-quote-left fa-2x text-info"></i>
<div class="h4 tw-px-2"><?php echo $todays_thought[0]['details'] ?? ''; ?></div>
                          <i class="fas fa-quote-right fa-2x text-info" style="float:right"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title"><i class="fa-solid fa-clock text-warning"></i> Clock</h4>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;text-align: -webkit-center;">
                            
							<div class="analog-watch">
  <!-- Hour marks -->
  <div class="marks"></div>

  <!-- Hands -->
  <div class="hand hour" id="hour"></div>
  <div class="hand minute" id="minute"></div>
  <div class="hand second" id="second"></div>

  <div class="center-dot"></div>
</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title"><i class="fa-solid fa-chart-pie text-warning"></i> Attendance Stats</h4>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		

        <!-- Latest Listings -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
<div class="panel-body box-gradient-bg11">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i><?php echo $company_policies['title'] ?? 'Company Policies'; ?>
<?php if(is_admin()){ ?>
<a href="<?php echo admin_url('hrd/setting/company_policies');?>" target="_blank" title="Manage Company Policies"><i class="fa-solid fa-gear fa-beat-fade pull-right text-success"></i></a>
<?php } ?>
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2">
						  <h4><?php echo $company_policies['details'] ?? ''; ?></h4>
						  <?php if (!empty($company_policies['attachments'])){ ?>
                          <?php foreach ($company_policies['attachments'] as $attachment){ ?>
						  <div class="attachment-item" style="margin-bottom: 5px;">
                            <a href="<?php echo base_url($attachment['file_path']); ?>" target="_blank" class="text-primary">
                              <i class="fa fa-file"></i> <?php echo e($attachment['original_name']); ?>
                            </a>
                            
                          </div>
						  
						  <?php } ?>
						  <?php } ?>
						  
						  </div>
                        </div>
                        
                    </div>
                </div>
            </div>
			<div class="col-md-6">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body box-gradient-bg12">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Leave Rule 
<?php if(is_admin()){ ?>
<a href="<?php echo admin_url('hrd/setting/leave_rule');?>" target="_blank" title="Manage Leave Rule"><i class="fa-solid fa-gear fa-beat-fade pull-right text-success"></i></a>
<?php } ?>
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2"><?php echo $leave_rule[0]['details'] ?? ''; ?></div>
                        </div>
                        
                    </div>
                </div>
            </div>
			

            
        </div>
		
		  <!-- Latest Listings -->
        <div class="row">
            
<div class="col-md-12">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body mail-bg">
                        <h4 class="panel-title ">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Event & Announcement
<?php if(is_admin()){ ?>
<a href="<?php echo admin_url('hrd/setting/events_announcements');?>" target="_blank" title="Manage Event & Announcement"><i class="fa-solid fa-gear fa-beat-fade pull-right text-success"></i></a>
<?php } ?>
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2"><?php echo $events_announcements[0]['details'] ?? ''; ?></div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body box-gradient-bg14">
                        <h4 class="panel-title ">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Corporate Guide
<?php if(is_admin()){ ?>
<a href="<?php echo admin_url('hrd/setting/corporate_guidelines');?>" target="_blank" title="Manage Corporate Guide"><i class="fa-solid fa-gear fa-beat-fade pull-right text-success"></i></a>
<?php } ?>
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2"><?php echo $corporate_guidelines[0]['details'] ?? ''; ?></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
		
		
    </div>
</div>

<style>
.widget-card {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.widget-card-body {
    display: flex;
    align-items: center;
}

.widget-card-icon {
    font-size: 2.5em;
    margin-right: 15px;
    opacity: 0.8;
}

.widget-card-content h3 {
    margin: 0;
    font-size: 2em;
    font-weight: bold;
}

.widget-card-content p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}

.panel_s {
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
}

.panel-body {
    padding: 15px;
}

.panel-title {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
}

.table th {
    background-color: #f5f5f5;
    border-bottom: 2px solid #ddd;
}

.label {
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: bold;
}

/* Chart container styles */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
    margin: 0 auto;
}

.chart-container canvas {
    max-height: 100%;
    max-width: 100%;
}

/* Ensure panels have consistent heights */
.panel_s {
    height: 100%;
}

.panel_s .panel-body {
    display: flex;
    flex-direction: column;
}

.panel_s .panel-title {
    margin-bottom: 15px;
    flex-shrink: 0;
}

.chart-container {
    flex: 1;
    min-height: 300px;
}
</style>

<?php init_tail(); ?>
<script>
$(function(){
  // Generate 12 hour marks
  for(let i=0;i<12;i++){
    let $mark = $('<div class="mark"></div>');
    $mark.css('transform','rotate('+(i*30)+'deg)');
    $('.marks').append($mark);
  }

  function updateClock(){
    var now = new Date();
    var h = now.getHours();
    var m = now.getMinutes();
    var s = now.getSeconds();

    // Angles
    var hDeg = (h % 12) * 30 + m * 0.5;   // 30° per hour + 0.5° per minute
    var mDeg = m * 6 + s * 0.1;           // 6° per minute + 0.1° per sec
    var sDeg = s * 6;                     // 6° per second

    $('#hour').css('transform','rotate('+hDeg+'deg)');
    $('#minute').css('transform','rotate('+mDeg+'deg)');
    $('#second').css('transform','rotate('+sDeg+'deg)');
  }

  // update every second
  updateClock();
  setInterval(updateClock,1000);
});
</script>
<script>
$(document).ready(function() {


    // Task Status Chart
    const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
	const taskStatusData = [
	<?php foreach ($status_counter as $sts){ 
	$dt=get_attendance_status($sts['first_half']);
	$title=$dt->title;
	$colors=$dt->color;
	$total_count=$sts['total_count'];
	?>
	{"name":"<?php echo $title;?>","color":"<?php echo $colors;?>","count":"<?php echo $total_count;?>"},
	<?php }?>
	];
    
    new Chart(taskStatusCtx, {
        type: 'doughnut',
        data: {
            labels: taskStatusData.map(item => item.name),
            datasets: [{
                data: taskStatusData.map(item => item.count),
                backgroundColor: taskStatusData.map(item => item.color || '#28a745'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

});
</script>



</body></html>