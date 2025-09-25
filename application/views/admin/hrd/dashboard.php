<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <i class="fa-solid fa-chart-line tw-mr-2"></i><?php echo $title; ?>
                    </h4>
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
                            <h3><?php //echo $title; ?> 11</h3>
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
                            <h3><?php //echo $stats['active_projects']; ?> 11</h3>
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
                            <h3><?php //echo $stats['total_tasks']; ?> 11</h3>
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
                            <h3><?php //echo $stats['task_completion_rate']; ?> 11</h3>
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
                        <h4 class="panel-title"><i class="fa-solid fa-circle-question  text-warning"></i> Today's Thought</h4>
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
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i><?php echo $company_policies['title'] ?? ''; ?>
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
                <div class="panel_s" style="position: relative; height: 500px; width: 100%;">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-chart-gantt tw-mr-2"></i>Holiday List <i class="fa-solid fa-circle-info" title="Passed Holiday display in color" style=" color:khaki;"></i>
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php if (!empty($holiday_lists)){ ?>
<?php foreach ($holiday_lists as $holidays){ 
$holidayDate = strtotime($holidays['holiday_date']);
$today = strtotime(date("Y-m-d"));
    $isPast = ($holidayDate < $today); // check if holiday is before today
?>
<tr <?php if($isPast){ echo 'style="background-color: khaki;"'; }?> >
<td><?php if (!empty($holidays['holiday_date'])){ echo $holidays['holiday_date']; }?></td>
<td><?php if (!empty($holidays['holiday_date'])){ echo date("l", strtotime($holidays['holiday_date'])); }?></td>
<td><?php if (!empty($holidays['holiday_remark'])) {echo $holidays['holiday_remark'];}?></td>
</tr>
<?php } ?>
<?php }else{ ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No Hplidays found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                 
                    </div>
                </div>
            </div>

            
        </div>
		
		  <!-- Latest Listings -->
        <div class="row">
            
<div class="col-md-6">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Event & Announcement
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2"><?php echo $events_announcements[0]['details'] ?? ''; ?></div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Corporate Guide
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2"><?php echo $corporate_guidelines[0]['details'] ?? ''; ?></div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
		
		<div class="row">
            
<div class="col-md-6">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Leave Rule
                        </h4>
                        <div class="table-responsive" style="height: 430px; overflow-y: auto; padding-right:5px;">
                          <div class="tw-px-2"><?php echo $leave_rule[0]['details'] ?? ''; ?></div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel_s" style="position: relative; height: 500px; width: 100%; min-height:500px;">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Corporate Guide
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
<style>


  /* Outer Clock Circle */
  .analog-watch {
    width: 290px;
    height: 290px;
    background: #1e293b;
    border: 8px solid #334155;
    border-radius: 50%;
    position: relative;
    box-shadow: 0 0 25px rgba(0,0,0,.6), inset 0 0 10px rgba(255,255,255,.05);
  }

  /* Clock center dot */
  .center-dot {
    width: 14px;
    height: 14px;
    background: #facc15;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    margin: -7px 0 0 -7px;
    z-index: 20;
  }

  /* Hands */
  .hand {
    position: absolute;
    top: 50%;
    left: 50%;
    transform-origin: 50% 100%;
    transform: rotate(0deg);
  }

  .hour {
    width: 6px;
    height: 55px;
    background: #f8fafc;
    border-radius: 6px;
    z-index: 10;
    margin: -55px 0 0 -3px;
  }

  .minute {
    width: 4px;
    height: 75px;
    background: #60a5fa;
    border-radius: 4px;
    z-index: 9;
    margin: -75px 0 0 -2px;
  }

  .second {
    width: 2px;
    height: 90px;
    background: #f87171;
    border-radius: 2px;
    z-index: 8;
    margin: -90px 0 0 -1px;
  }

  /* Hour markers */
  .mark {
    position: absolute;
    width: 4px;
    height: 12px;
    background: #94a3b8;
    top: 10px;
    left: 50%;
    margin-left: -2px;
    transform-origin: center 100px;
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
    <?php /*?>const taskStatusData = <?php echo json_encode($task_status_chart); ?>;<?php */?>
	const taskStatusData = [{"name":"CL","color":"#2cc8ba","count":"8"},{"name":"PL","color":"#08aeea","count":"1"},{"name":"EL","color":"#a593ff","count":"2"},{"name":"LWP","color":"#4fd3e5","count":"12"}];
    
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