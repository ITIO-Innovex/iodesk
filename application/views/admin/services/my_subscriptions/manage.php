<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); 
$subscription_status=subscription_status();

//echo $datediff=get_remaining_days_in_month();
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
            <h4 class="tw-mb-4 tw-font-semibold tw-text-lg">My Subscription</h4>

            <?php if (!empty($plan)) { ?>
              <div class="panel_s">
                <div class="panel-body">
                  <h4 class="tw-font-semibold"><?php echo e($plan['plan_name']); ?></h4>
                  <p class="text-muted">Plan ID: <?php echo e($plan['subscription_id']); ?></p>
                  <div class="row">
                    <div class="col-md-4"><strong>Price:</strong> <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo number_format((float) ($plan['price'] ?? 0), 2); ?></div>
                    <div class="col-md-4"><strong>Billing Cycle:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $plan['billing_cycle'] ?? 'monthly'))); ?></div>
                    <div class="col-md-4"><strong>Duration:</strong> <?php echo e($plan['duration'] ?? 0); ?></div>
                  </div>
                  <div class="row mtop10">
                    <div class="col-md-4"><strong>No of Staff:</strong> <?php echo !empty($plan['no_of_staff']) ? e($plan['no_of_staff']) : 'N/A'; ?></div>
                    <div class="col-md-4"><strong>Tax (%):</strong> <?php echo isset($plan['tax']) ? e($plan['tax']) : '0'; ?></div>
                    <div class="col-md-4"><strong>Status:</strong> <?php echo e(ucfirst($plan['status'] ?? '')); ?></div>
                  </div>
                  <div class="row mtop10">
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
<?php init_tail(); ?>
</body>
</html>
