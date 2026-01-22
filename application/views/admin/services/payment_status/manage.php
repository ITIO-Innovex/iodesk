<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
<?php if(e($payment['payment_method'])=='Online'){ ?>
<div class="alert alert-danger tw-bg-danger-500">
<div class="tw-text-white tw-font-bold tw-my-2"><i class="fa-solid fa-credit-card"></i> <?php echo "PG Comming Soon";?> <span style="float:right"></span></div>
                  </div>
<?php } ?>
            <h4 class="tw-mb-4 tw-font-semibold tw-text-lg">Payment Details</h4>
            <div class="panel_s">
              <div class="panel-body">
                <div class="row">
                  <div class="col-md-6"><strong>Invoice No:</strong> <?php echo e($payment['invoice_no']); ?></div>
                  <div class="col-md-6"><strong>Payment Status:</strong> <?php echo e(ucfirst($payment['payment_status'] ?? '')); ?></div>
                </div>
                <div class="row mtop10">
                  <div class="col-md-4"><strong>Amount:</strong> <?php echo e($payment['currency'] ?? 'INR'); ?> <?php echo number_format((float) ($payment['amount'] ?? 0), 2); ?></div>
                  <div class="col-md-4"><strong>Tax:</strong> <?php echo number_format((float) ($payment['tax'] ?? 0), 2); ?></div>
                  <div class="col-md-4"><strong>Total Amount:</strong> <?php echo e($payment['currency'] ?? 'INR'); ?> <?php echo number_format((float) ($payment['total_amount'] ?? 0), 2); ?></div>
                </div>
                <div class="row mtop10">
                  <div class="col-md-4"><strong>Invoice Date:</strong> <?php echo e($payment['invoice_date'] ?? ''); ?></div>
                  <div class="col-md-4"><strong>Due Date:</strong> <?php echo e($payment['due_date'] ?? ''); ?></div>
                  <div class="col-md-4"><strong>Payment Method:</strong> <?php echo e($payment['payment_method'] ?? ''); ?></div>
                </div>
                <div class="mtop20">
                  <a href="<?php echo admin_url('services/my_subscriptions'); ?>" class="btn btn-default">Back to My Subscription</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
