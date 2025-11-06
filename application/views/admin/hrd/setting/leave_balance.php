<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><i class="fa fa-sliders tw-mr-2"></i> Leave Balance (Settings)</h4>
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
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Staff</label>
                    <select name="staff_id" class="form-control">
                      <option value="">-- All Staff --</option>
                      <?php if (!empty($all_staff)) { foreach ($all_staff as $st) { $sel = ((int)$staff_filter === (int)$st['staffid']) ? 'selected' : ''; ?>
                        <option value="<?php echo (int)$st['staffid']; ?>" <?php echo $sel; ?>><?php echo e($st['full_name']); ?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-default" title="Search"><i class="fa fa-search"></i></button>
                      <a href="<?php echo admin_url('hrd/setting/leave_balance'); ?>" class="btn btn-default" title="Reset"><i class="fa fa-times"></i></a>
                      <a href="#" class="btn btn-primary" onclick="$('#lb_modal').modal('show');return false;">Add</a>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <div class="table-responsive">
              <table class="table dt-table" data-order-col="0" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Staff</th>
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
                      <td><?php echo get_staff_full_name($r['staffid'] ?? ''); ?></td>
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

<div class="modal fade" id="lb_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/setting/leave_balance_add'), ['id' => 'lb-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Leave Balance</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Staff</label>
            <select name="staffid" class="form-control" required>
              <option value="">-- Select Staff --</option>
              <?php if (!empty($all_staff)) { foreach ($all_staff as $st) { ?>
                <option value="<?php echo (int)$st['staffid']; ?>"><?php echo e($st['full_name']); ?></option>
              <?php } } ?>
            </select>
          </div></div>
          <div class="col-md-2"><div class="form-group"><label>PL</label><input type="number" name="pl" class="form-control" min="0" value="0" required></div></div>
          <div class="col-md-2"><div class="form-group"><label>WL</label><input type="number" name="wl" class="form-control" min="0" value="0" required></div></div>
          <div class="col-md-2"><div class="form-group"><label>AD</label><input type="number" name="ad" class="form-control" min="0" value="0" required></div></div>
        </div>
        <p class="text-muted">Month will be set to current month automatically.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(function(){
  $('#lb-form').on('submit', function(e){
    e.preventDefault();
    var form = this;
    $.post(form.action, $(form).serialize(), function(resp){
      if(resp && resp.success){
        $('#lb_modal').modal('hide');
        window.location.reload();
      } else {
        alert('Failed to add');
      }
    }, 'json');
  });
});
</script>
</body></html>


