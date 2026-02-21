<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  Holiday List <i class="fa-solid fa-circle-info" title="Passed Holiday display in color" style=" color:khaki;"></i></span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span></h4>
  
    <div class="row tw-mt-2" style="clear:both">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">Holidays List</h4>
            <div class="table-responsive">
              <table class="table dt-table" data-order-col="0" data-order-type="asc">
                <thead>
                  <tr>
                    <th style="display:none;">Date</th>
					<th >Date</th>
                    <th>Day</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($holidays)) { foreach ($holidays as $h) { 
                    $holiday_date = isset($h['holiday_date']) ? $h['holiday_date'] : '';
					$holiday_remark = isset($h['holiday_remark']) ? $h['holiday_remark'] : '';
					$today = strtotime(date("Y-m-d"));
					$holidayDates = strtotime($holiday_date);
                    $isPast = ($holidayDates < $today); // check if holiday is before today
                  ?>
                  <tr <?php if($isPast){ echo 'style="background-color: khaki;"'; }?>>
                    <td style="display:none;" ><?php echo $holiday_date; ?></td>
					<td ><?php echo date("d F Y", strtotime($holiday_date)); ?></td>
					
                    <td><?php if (!empty($holiday_date)){ echo date("l", strtotime($holiday_date)); }?></td>
                    <td><?php echo $holiday_remark; ?></td>
                  </tr>
                  <?php } } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>


