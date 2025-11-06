<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  Leave Balance <i class="fa-solid fa-circle-info" title="Passed Holiday display in color" style=" color:khaki;"></i></span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
         
            <form method="get" action="" class="mbot15" style="margin-bottom:15px;">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Month - Year</label>
                    <input type="month" name="month_year" class="form-control" value="<?php echo e($month_year); ?>" />
                  </div>
                </div>
                <div class="col-md-9">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-default" title="Search"><i class="fa fa-search"></i></button>
                      <a href="<?php echo admin_url('hrd/leave_balance'); ?>" class="btn btn-default" title="Reset"><i class="fa fa-times"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table dt-table" data-order-col="0" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Month Year</th>
                    <th>PL</th>
                    <th>WL</th>
                    <th>AD</th>
                    <th>Total Balance PL</th>
                    <th>Total Balance WL</th>
                    <th>Total (PL + WL)</th>
                    <th>Adjust Leave</th>
                    <th>Balanced</th>
                    <th>Added On</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($rows)) { foreach ($rows as $r) { ?>
                    <tr>
                      <td><?php echo e($r['month_year'] ?? ''); ?></td>
                      <td><?php echo e($r['PL'] ?? ''); ?></td>
                      <td><?php echo e($r['WL'] ?? ''); ?></td>
                      <td><?php echo e($r['AD'] ?? ''); ?></td>
                      <td><?php echo e($r['total_balance_pl'] ?? ''); ?></td>
                      <td><?php echo e($r['total_balance_wl'] ?? ''); ?></td>
                      <td><?php echo e($r['total_pl_wl'] ?? ''); ?></td>
                      <td><?php echo e($r['adjust_leave'] ?? ''); ?></td>
                      <td><?php echo e($r['balanced'] ?? ''); ?></td>
                      <td><?php echo e($r['addedon'] ?? ''); ?></td>
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


