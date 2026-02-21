<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 


//print_r($status_counter); 
//print_r($calendar);
//print_r($shift_details);
$shift_id  = $shift_details[0]['shift_id']  ?? '-';
$get_shift_code  = $shift_details[0]['shift_code']  ?? '-';
$get_shift_in = $shift_details[0]['shift_in'] ?? '-';
$get_shift_out = $shift_details[0]['shift_out'] ?? '-';
$get_saturday_rule = $shift_details[0]['saturday_rule'] ?? '-';
$staff_type = (isset($GLOBALS['current_user']->staff_type) && $GLOBALS['current_user']->staff_type)? $GLOBALS['current_user']->staff_type: null;
$staff_type_title =get_staff_staff_type($staff_type);

if(empty($shift_details)){ echo "Shift Not Mapped. contact web admin"; } ?>

<style media="print">
/* Show only the calendar when printing */
body * { visibility: hidden !important; }
#calendar-section, #calendar-section * { visibility: visible !important; }

/* Layout fixes */
#calendar-section { width: 100%; }
#calendar-section .table-responsive { overflow: visible !important; }

/* Page break rules */
#calendar-section table { page-break-inside: auto; }
#calendar-section tr {
    page-break-inside: avoid;
    page-break-after: auto;
    border: 1px solid #000; /* Added border */
}
#calendar-section thead { display: table-header-group; }
#calendar-section tfoot { display: table-footer-group; }

/* Print settings */
@page { size: auto; margin: 12mm; }
html, body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
table td, 
table th {
    border: 1px solid #000;
}
table {
    border-collapse: collapse;
}
</style>
<style>
@media print {
  body * {
    visibility: hidden;
  }
  #calendar-section, #calendar-section * {
    visibility: visible;
  }
  #calendar-section {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>

<div id="wrapper">
  <div class="content">
  <div>
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  My Attendance</span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span></h4>
  </div>
  <div style="clear:both"></div>
  <?php if(empty($shift_details)){ ?>
  <div class="row" style="clear:both">
      <div class="col-md-12">
        
        
          <div class="alert alert-danger tw-bg-danger-500">
		  <div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> Shift Not Mapped. contact web admin <?php if(is_admin()){ ?><span style="float:right"><a href="<?php echo admin_url('hrd/setting/shift_manager');?>" class="btn btn-warning btn-sm ms-2">Add Shift Manager</a></span><?php } ?></div>
		  </div>
		  
		  </div>
		  </div>
   <?php }else{ ?>
		  
    <div class="row">
      <div class="col-md-12">
        
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Month - Year</label>
                    <?php $my = isset($filters['month_year']) ? $filters['month_year'] : ''; ?>
                    <input type="month" name="month_year" class="form-control" value="<?php echo e($my); ?>" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary  btn-sm"><i class="fa fa-search" title="Search"></i><span class="m-hide"> Search</span></button>
                      <a href="<?php echo admin_url('hrd/attendance'); ?>" class="btn btn-default  btn-sm"><i class="fa-solid fa-xmark" title="Reset"></i><span class="m-hide"> Reset</span></a>
                      <button type="button" class="btn btn-success btn-sm" onclick="printDiv('calendar-section')"><i class="fa-solid fa-print" title="Print"></i><span class="m-hide"> Print</span></button>
                      <?php $pdf_my = isset($filters['month_year']) ? $filters['month_year'] : ''; ?>
                      <a href="<?php echo admin_url('hrd/attendance?month_year=' . urlencode($pdf_my) . '&download=pdf'); ?>" class="btn btn-danger  btn-sm">
                        <i class="fa-regular fa-file-pdf" title="Download PDF"></i><span class="m-hide"> Download PDF</span>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

<script>
function printDiv(divId) { 
  var divContents = document.getElementById(divId).innerHTML;
  var printWindow = window.open('', '', 'height=600,width=800');
  printWindow.document.write('<html><head><title>Print</title>');
  printWindow.document.write('</head><body>');
  printWindow.document.write(divContents);
  printWindow.document.write('</body></html>');
  printWindow.document.close();
  printWindow.print();
}
</script>
            <?php if (!empty($calendar)) { $cal = $calendar; ?>
            <div id="calendar-section" class="row" style="margin-bottom:15px;">
              <div class="col-md-12">
                <h4 style="margin-top:0;"><?php echo e(get_staff_full_name()); ?> : <?php echo date('F Y', strtotime(sprintf('%04d-%02d-01', (int)$cal['year'], (int)$cal['month']))); ?></h4>
                <div class="table-responsive">
				<table class="table table-bordered" border="1" >
				   <thead>
                      <tr class="tw-bg-neutral-200 tw-text-xs">
                        <?php /*?><th>Month for : <?php echo date('F Y', strtotime(sprintf('%04d-%02d-01', (int)$cal['year'], (int)$cal['month']))); ?></th><?php */?>
                        <th><?php echo e(get_staff_full_name()); ?></th>
						<th>Emp. Code : <?php echo get_staff_fields('','employee_code');?></th>
						<?php /*?><th>Shift Code    : <?php echo $get_shift_code;?></th><?php */?>
                        <th>Shift InTime  : <?php 
							// Convert to DateTime object
							$time = new DateTime($get_shift_in);
							// Subtract 15 minutes
							$time->modify('-15 minutes');
							// Convert back to string if needed
							$get_shift_in = $time->format('H:i:s');
							echo $get_shift_in; // Output: 09:30:00
						 ?></th>
						<th>Shift OutTime : <?php echo $get_shift_out;?></th>
					    <th>Staff Type : <?php  if(isset($GLOBALS['current_user']->staff_type)&&$GLOBALS['current_user']->staff_type) { echo "[ ".get_staff_staff_type($GLOBALS['current_user']->staff_type)." ]";} ?></th>
						<th>Saturday Rule : <?php echo get_saturday_rule($get_saturday_rule);?></th>
					   </tr>
					  
                    </thead>
				  </table>
					<?php
					
					//print_r($status_counter);
					foreach ($status_counter as $sc) {
					
					$fhTitle = '';
					$first  = $sc['first_half']?(int)$sc['first_half']:0;
                    $second = $sc['second_half']?(int)$sc['second_half']:0;
                    $count  = $sc['total_count']?(int)$sc['total_count']:0;
					
					if($first==1 && $second==0){
					$fhTitle = get_attendance_status_title((int)$sc['first_half']);
					}elseif($first==2 && $second==0){
					$fhTitle = get_attendance_status_title((int)$first);
					}elseif(($first==1 && $second==8) || ($first==8 && $second==1)){
					$fhTitle = get_attendance_status_title(8);
					}elseif(($first==8 && $second==0) || $first==4){
					$fhTitle = get_attendance_status_title(4);
					}elseif($first==3){
					$fhTitle = get_attendance_status_title(3);
					}elseif($first==7){
					$fhTitle = get_attendance_status_title(7);
					}else{
					$fhTitle = get_attendance_status_title(4);
					}
					
					
					
						
						
						$label = $fhTitle !== '' ? $fhTitle : (isset($sc['first_half']) ? e($sc['first_half']) : '-');
						echo "<a class='btn btn-default mx-2'>".$label." (".$sc['total_count'].")&nbsp;</a>&nbsp;";
					}
					?>
                  <table class="table table-bordered" border="1" style="background:#fff;">
				   
                    <thead>
                      <tr>
                        <th style="width:150px;">Date</th>
                        <th>Day</th>
                        <th>InTime</th>
                        <th>OutTime	</th>
                        <th>First Half</th>
						<th>Second Half</th>
                        <th>Portion</th>
						<th>Tot. Hrs.</th>
                        <th>Late Mark</th>
						<th>Remark</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      // Accumulators for footer totals
                      $sumPortion = 0.0; 
                      $sumTotSecs = 0; 
                      $sumLateSecs = 0; 
                      $parseHms = function($hms){
                        if (!$hms || $hms==='-') { return 0; }
                        $parts = explode(':', $hms);
                        if (count($parts) !== 3) { return 0; }
                        return ((int)$parts[0])*3600 + ((int)$parts[1])*60 + (int)$parts[2];
                      };
                      $fmtHms = function($secs){
                        if ($secs < 0) { $secs = 0; }
                        $h = floor($secs/3600);
                        $m = floor(($secs%3600)/60);
                        $s = $secs%60;
                        return sprintf('%02d:%02d:%02d', $h, $m, $s);
                      };
					  $sat_count=0;
                      foreach ($cal['days'] as $cell) { 
					  $staffid = get_staff_user_id();
		              $company_id = get_staff_company_id();
					  
			
                      if (strtotime($cell['date']) > time()) { continue; }
					  $bgClass="bg-light";
					  if(date('l', strtotime($cell['date']))=='Sunday'){
					  $bgClass="tw-bg-danger-200";
					  }elseif(date('l', strtotime($cell['date']))=='Saturday'){
					  $bgClass="tw-bg-warning-200";
					  $sat_count++;
					  }
					  
	///////////////////////////All Condation By Shift///////////////
	
	
	
	/////////////////////////////////////////////////////////////////				  
					  
					  
					  
					  
					  
					  
					  //print_r($cell['items']);
					  ?>
                        <tr class="<?php echo $bgClass;?>">
                          <td><strong><a href="#" title="Click for view attendace logs" class="open-att-logs" data-entry_date="<?php echo e($cell['date']); ?>" data-att_id="<?php echo (!empty($cell['items']) && isset($cell['items'][0]['attendance_id'])) ? (int)$cell['items'][0]['attendance_id'] : 0; ?>"><?php echo date('D, d M Y', strtotime($cell['date'])); ?></a></strong></td>
                          <td ><a href="#"  class="open-att-req" data-entry_date="<?php echo e($cell['date']); ?>" data-att_id="<?php echo (!empty($cell['items']) && isset($cell['items'][0]['attendance_id'])) ? (int)$cell['items'][0]['attendance_id'] : 0; ?>" title="Click for send attendance update request" ><?php echo date('l', strtotime($cell['date'])); ?></a> </td>
                          <?php
                            if (!empty($cell['items'])) {
                              // Take only the first record to avoid duplicates
                              $it = $cell['items'][0];$it = $cell['items'][0];
							  
			
			
                              
                              // Format in_time - handle both time and datetime formats
                              $inTimeRaw = $it['in_time'] ?? null;
                              if ($inTimeRaw) {
                                // If it's a datetime, extract time part; if it's already time, use as is
                                if (strpos($inTimeRaw, ' ') !== false) {
                                  $inTime = date('H:i:s', strtotime($inTimeRaw));
                                } else {
                                  $inTime = $inTimeRaw;
                                }
                                $inTime = e($inTime);
                              } else {
                                $inTime = '-';
                              }
                              
                              // Format out_time - handle both time and datetime formats
                              $outTimeRaw = $it['out_time'] ?? null;
                              if ($outTimeRaw) {
                                // If it's a datetime, extract time part; if it's already time, use as is
                                if (strpos($outTimeRaw, ' ') !== false) {
                                  $outTime = date('H:i:s', strtotime($outTimeRaw));
                                } else {
                                  $outTime = $outTimeRaw;
                                }
                                $outTime = e($outTime);
                              } else {
                                $outTime = '-';
                              }
                              
                              $fhRaw = $it['first_half'] ?? '';
                              $shRaw = $it['second_half'] ?? '';
                              
                              // Process first_half: if numeric, get formatted title, otherwise show raw value
                              if ($fhRaw !== '' && is_numeric($fhRaw)) {
                                $fhTitle = get_attendance_status_title((int)$fhRaw);
                                $firstHalf = $fhTitle !== '' ? $fhTitle : e($fhRaw);
                              } else {
                                $firstHalf = $fhRaw !== '' ? e($fhRaw) : '-';
                              }
                              
                              // Process second_half: if numeric, get formatted title, otherwise show raw value
                              if ($shRaw !== '' && is_numeric($shRaw)) {
                                $shTitle = get_attendance_status_title((int)$shRaw);
                                $secondHalf = $shTitle !== '' ? $shTitle : e($shRaw);
                              } else {
                                $secondHalf = $shRaw !== '' ? e($shRaw) : '-';
                              }
                              
                              //$position = number_format($it['position'],2)??'';
							  $position = $it['position'] !== null ? number_format($it['position'], 2) : '';
                              $totals = e($it['total_hours']??'');
                              $lates = e($it['late_mark']??'');
							  
		
$result=getAttendanceStatus($staffid, $shift_id, $cell['date'], $staff_type, $it['attendance_id']);
$firstHalf=get_attendance_status_title($result['status']);
$secondHalf=get_attendance_status_title($result['substatus']);

$position=number_format($result['position'],2);	
$remarks=$result['remarks'];				
                              
                              echo '<td>'.$inTime.'</td>';
                              echo '<td>'.$outTime.'</td>';
                              echo '<td>'.$firstHalf.'</td>';
                              echo '<td>'.$secondHalf.'</td>';
                              echo '<td>'.($position ?: '-').'</td>';
                              echo '<td>'.($totals ?: '-').'</td>';
                              echo '<td>'.($lates ?: '-').'</td>';
							  echo '<td><i class="fa-solid fa-circle-info" title="'.($remarks ?: '-').'" style=" color:khaki;"></i></td>';
                              // accumulate
                              $portionVal = 0.0; 
                              if ($position !== '-' && $position !== '') { $portionVal = (float)$position; }
                              $sumPortion += $portionVal;
                              $sumTotSecs += $parseHms($totals ?: '00:00:00');
                              $sumLateSecs += $parseHms($lates ?: '00:00:00');
                            } else {
							
			/*$fdata = [
            'staffId'        	=> $staffid,
            'shiftID'       	=> $shift_id,
            'staffType'     	=> $staff_type,
			'date'     		    => $cell['date'],
            ];*/
$result=getAttendanceStatus($staffid, $shift_id, $cell['date'], $staff_type);
			
							 if(date('l', strtotime($cell['date']))=='Sunday'){
					         $bgClass="tw-bg-danger-200";
					         }elseif(date('l', strtotime($cell['date']))=='Saturday'){
					         $bgClass="tw-bg-warning-200";
					         }
							 
$firstHalf=$result['status'];
$secondHalf=$result['substatus'];

$position=number_format($result['position'],2);		
$remarks=$result['remarks'];	
				
$inTime="00.00";$outTime="00.00";							 
                              echo '<td>'.$inTime.'</td><td>'.$outTime.'</td><td>'.get_attendance_status_title($firstHalf).'</td><td>'.get_attendance_status_title($secondHalf).'</td><td>'.$position.'</td><td>00:00:00</td><td>00:00:00</td><td><i class="fa-solid fa-circle-info" title="'.($remarks ?: '-').'" style=" color:khaki;"></i></td>';
                              // accumulate defaults
                              $sumPortion += (float)$position;
                              $sumTotSecs += 0;
                              $sumLateSecs += 0;
                            }
                          ?>
                        </tr>
                      <?php } 
                      $sumTotStr = $fmtHms($sumTotSecs);
                      $sumLateStr = $fmtHms($sumLateSecs);
                      ?>
					  <?php /*?><tr>
                        <td>&nbsp;</th>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
						<td>&nbsp;</td>
                        <td title="Portion"><?php echo number_format($sumPortion, 2); ?></td>
							<td title="Tot. Hrs."><?php echo $sumTotStr; ?></td>
                        <td title="LateMark"><?php echo $sumLateStr; ?></td>
                      </tr><?php */?>
                    </tbody>
					
                  </table>
                </div>
              </div>
            </div>
            <?php } ?>
            
            
            
          </div>
        </div>
      </div>
    </div>
	
   <?php } ?>
  </div>
</div>

<!-- Attendance Logs Modal -->
<div class="modal fade" id="att_logs_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Attendance Logs - <span id="logs-date"></span></h4>
      </div>
      <div class="modal-body">
        <div id="logs-loading" class="text-center" style="padding: 20px;">
          <i class="fa fa-spinner fa-spin fa-2x"></i> Loading logs...
        </div>
        <div id="logs-content" style="display: none;">
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Punch Type</th>
                <th>Punch Time</th>
                <th>IP Address</th>
              </tr>
            </thead>
            <tbody id="logs-tbody">
            </tbody>
          </table>
          <div id="logs-empty" style="display: none; padding: 20px; text-align: center;">
            <p class="text-muted">No logs found for this attendance record.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Update Request Modal -->
<div class="modal fade" id="att_req_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Request Attendance Update</h4>
      </div>
      <div class="modal-body">
        <form id="att-req-form">
          <input type="hidden" name="attendance_id" id="req-att-id" value="0">
          <div class="row">
            <div class="col-md-4"><div class="form-group"><label>Date</label><input type="date" name="entry_date" id="req-entry-date" class="form-control" readonly></div></div>
            <div class="col-md-4"><div class="form-group"><label>In Time</label><input type="time" name="in_time" id="req-in-time" class="form-control"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Out Time</label><input type="time" name="out_time" id="req-out-time" class="form-control"></div></div>
          </div>
          <div class="row">
            <div class="col-md-12"><div class="form-group"><label>Remarks</label><input type="text" name="remarks" id="req-remarks" class="form-control" maxlength="255"></div></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="att-req-submit">Submit Request</button>
      </div>
    </div>
  </div>
  </div>



<?php init_tail(); ?>
<script>
$(function(){
  // Handle click on date to show logs
  $(document).on('click', 'a.open-att-logs', function(){
    var entry = $(this).data('entry_date');
    var attId = $(this).data('att_id') || 0;
    
    if (attId === 0) {
      alert('No attendance record found for this date.');
      return false;
    }
    
    // Show modal and set date
    $('#logs-date').text(entry);
    $('#att_logs_modal').modal('show');
    
    // Show loading, hide content
    $('#logs-loading').show();
    $('#logs-content').hide();
    $('#logs-empty').hide();
    $('#logs-tbody').empty();
    
    // Fetch logs via AJAX
    $.get(admin_url + 'hrd/get_attendance_logs', { attendance_id: attId }, function(resp){
      $('#logs-loading').hide();
      
      if (resp && resp.success && resp.logs && resp.logs.length > 0) {
        var tbody = $('#logs-tbody');
        tbody.empty();
        
        $.each(resp.logs, function(index, log){
          var row = '<tr>' +
            '<td>' + (index + 1) + '</td>' +
            '<td>' + (log.punch_type ? log.punch_type : '-') + '</td>' +
            '<td>' + (log.punch_time ? log.punch_time : '-') + '</td>' +
            '<td>' + (log.ip ? log.ip : '-') + '</td>' +
            '</tr>';
          tbody.append(row);
        });
        
        $('#logs-content').show();
      } else {
        $('#logs-empty').show();
      }
    }, 'json').fail(function(){
      $('#logs-loading').hide();
      alert('Failed to load logs. Please try again.');
    });
    
    return false;
  });

  // Keep the old attendance request modal handler (if needed)
  $(document).on('click', 'a.open-att-req', function(){
    var entry = $(this).data('entry_date');
    var attId = $(this).data('att_id') || 0;
    $('#req-entry-date').val(entry);
    $('#req-att-id').val(attId);
    $('#req-in-time').val('');
    $('#req-out-time').val('');
    $('#req-remarks').val('');
    $('#att_req_modal').modal('show');
    return false;
  });

  $('#att-req-submit').on('click', function(){
    var payload = {
      attendance_id: $('#req-att-id').val(),
      entry_date: $('#req-entry-date').val(),
      in_time: $('#req-in-time').val(),
      out_time: $('#req-out-time').val(),
      remarks: $('#req-remarks').val()
    };
    $.post(admin_url + 'hrd/attendance_update_request_add', payload, function(resp){
      if (resp && resp.success) {
        alert('Request submitted');
        $('#att_req_modal').modal('hide');
      } else {
        alert('Failed to submit');
      }
    }, 'json');
  });
});
</script>

</body></html>

