<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); 

print_r($_SESSION);
$subscription_status=subscription_status();

//echo $datediff=get_remaining_days_in_month();

//print_r($plan);
$activeStaff=(int) ($active_staff_count ?? 0);
$staffLimit=(int)($plan['staff_limit'] ?? 0);
?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
<?php if($subscription_status<>'active'){ ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo _l($subscription_status);?> <span style="float:right"><a href="<?php echo admin_url('services/choose_subscriptions');?>" class="btn btn-warning btn-sm ms-2">Subscribe Now</a></span></div>
                  </div>
<?php } ?>
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-4 tw-font-semibold tw-text-lg">My Subscription 
			
			<a href="<?php echo admin_url('services/upgrade_plan');?>" id="upgrade_plan" class="btn btn-info btn-sm pull-right"><i class="fa-regular fa-circle-up"></i> Upgrade Plan</a>
			<?php //if ($activeStaff >= $staffLimit) { 
			if(isset($plan['price'])&&$plan['price'] > 0){	?>
			<a  id="upgrade_staff" class="btn btn-warning btn-sm tw-mx-2 pull-right"><i class="fa-regular fa-user"></i> Upgrade Staff</a>
			<?php } ?>
			
<?php //if ($activeStaff >= $staffLimit) { 
$enddate = $plan['end_date'] ?? '';
$price = $plan['price'] ?? null;
if (!empty($enddate) && $price !== null) {
  $today = new DateTime();
  $end = new DateTime($enddate);
  $renewalStart = (clone $end)->modify('-7 days');
  //echo $plan['price']."====>";
  if (($today >= $renewalStart && $today <= $end) && $price <> "0.00") {	?>
<a  href="<?php echo admin_url('services/renew_plan');?>" id="renew_plan" class="btn btn-success btn-sm tw-mx-2 pull-right" title="Renew Plan"><i class="fa-regular fa-user"></i> Renew Now</a>
<?php } } ?>
			
			</h4>

            <?php if (!empty($plan)) { ?>
              <div class="panel_s">
                <div class="panel-body">
                  <h4 class="tw-font-semibold"><?php echo e($plan['plan_name']); ?> - (<?php echo e(ucfirst($plan['status'] ?? '')); ?>)</h4>
                  <?php /*?><p class="text-muted">Plan ID: <?php echo e($plan['subscription_id']); ?></p><?php */?>
                  <div class="row">
                    <div class="col-md-4"><strong>Price:</strong> <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo number_format((float) ($plan['price'] ?? 0), 2); ?></div>
                    <div class="col-md-4"><strong>Billing Cycle:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $plan['billing_cycle'] ?? 'monthly'))); ?></div>
                    <div class="col-md-4"><strong>Duration:</strong> <?php echo e($plan['duration'] ?? 0); ?></div>
                  </div>
                  <div class="row mtop10">
                    <div class="col-md-4"><strong>Plan Staff:</strong> <?php echo !empty($plan['no_of_staff']) ? e($plan['no_of_staff']) : 'N/A'; ?></div>
					<div class="col-md-4"><strong>Staff Limit:</strong> <?php echo !empty($plan['staff_limit']) ? e($plan['staff_limit']) : 'N/A'; ?></div>
 					<div class="col-md-4"><strong>Active Staff:</strong> <?php echo (int) ($active_staff_count ?? 0); ?></div>
                    
                  </div>
                  <div class="row mtop10">
				  <div class="col-md-4"><strong>Tax (%):</strong> <?php echo isset($plan['tax']) ? e($plan['tax']) : '0'; ?></div>
                    <div class="col-md-4"><strong>Start Date:</strong> <?php echo e($plan['start_date'] ?? ''); ?></div>
                    <div class="col-md-4"><strong>End Date:</strong> <?php echo e($plan['end_date'] ?? ''); ?></div>
                  </div>
                  
                  <?php if (!empty($plan['features'])) {
                    $features = array_filter(array_map('trim', explode(',', $plan['features'])));
                  } ?>
                  <?php if (!empty($features)) { ?>
                    <div class="mtop10">
                      <strong>Features:</strong>
                      <ul class="list-unstyled">
                        <?php foreach ($features as $feature) { ?>
                          <li><?php echo e($feature); ?></li>
                        <?php } ?>
                      </ul>
                    </div>
                  <?php } ?>
                </div>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No active subscription found.</div>
            <?php } ?>

            <h4 class="tw-mt-6 tw-mb-3 tw-font-semibold">Payment History</h4>
            <?php if (!empty($payments)) { ?>
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Invoice No</th>
                      <th>Amount</th>
                      <th>Tax</th>
                      <th>Total</th>
                      <th>Invoice Date</th>
                      <th>Due Date</th>
                      <th>Status</th>
                      <th>Method</th>
                      <th>Download</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($payments as $payment) { ?>
                      <tr>
                        <td><?php echo e($payment['invoice_no']); ?></td>
                        <td><?php echo e($payment['currency'] ?? 'INR'); ?> <?php echo number_format((float) ($payment['amount'] ?? 0), 2); ?></td>
                        <td><?php echo number_format((float) ($payment['tax'] ?? 0), 2); ?></td>
                        <td><?php echo e($payment['currency'] ?? 'INR'); ?> <?php echo number_format((float) ($payment['total_amount'] ?? 0), 2); ?></td>
                        <td><?php echo e($payment['invoice_date'] ?? ''); ?></td>
                        <td><?php echo e($payment['due_date'] ?? ''); ?></td>
                        <td><?php echo e(ucfirst($payment['payment_status'] ?? '')); ?></td>
                        <td><?php echo e($payment['payment_method'] ?? ''); ?></td>
                        <td>
                          <?php if (!empty($payment['id'])) { ?>
                            <a class="btn btn-default btn-sm" href="<?php echo admin_url('services/subscriptions_invoice_pdf/' . $payment['id']); ?>">
                              <i class="fa-solid fa-download"></i> Download
                            </a>
                          <?php } else { ?>
                            -
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No payments found.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="upgrade_staff_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('services/upgrade_staff'), ['id' => 'upgrade-staff-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Upgrade Staff</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="no_of_staff" class="control-label">No of Staff</label>
          <input type="number" class="form-control" id="no_of_staff" name="no_of_staff" placeholder="Enter staff count" min="1" step="1" inputmode="numeric" required data-rule-digits="true" data-rule-min="1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $(function() {
    $('#upgrade_staff').on('click', function(e) {
      e.preventDefault();
      $('#no_of_staff').val('');
      $('#upgrade_staff_modal').modal('show');
    });
    appValidateForm('#upgrade-staff-form', {
      no_of_staff: {
        required: true,
        digits: true,
        min: 1
      }
    });
  });
</script>
</body>
</html>
