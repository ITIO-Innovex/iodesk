<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); 



                  $price = number_format((float) $plan['price'], 2);
                  $duration = isset($plan['duration']) ? (int) $plan['duration'] : 0;

                  if($plan['billing_cycle']=='pro_data'){
				  $datediff=get_remaining_days_in_month();
				  $price = $price * $datediff;
				  $duration= $duration * $datediff;
				  }
				  
$startDate = new DateTime();              // today
$days=e($duration -1 ?? 0);
$endDate   = (clone $startDate)->modify("+$days days");

$start_date = $startDate->format('F d, Y');
$end_date   = $endDate->format('F d, Y');
?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-6 tw-font-semibold tw-text-lg"><i class="fa-solid fa-credit-card"></i> Subscription Plan Details</h4>
            <hr class="hr-panel-heading">
            <div class="row">
              <div class="col-md-6">
                <div class="panel_s">
                  <div class="panel-body mail-bg">
                    <h4 class="tw-font-semibold">Plan : <?php echo e($plan['plan_name']); ?></h4>
                    <?php /*?><p class="text-muted">Plan ID: <?php echo e($plan['id']); ?></p><?php */?>
                    <p><strong>Price:</strong> <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo $price; ?></p>
					<?php /*?><p><strong>Tax:</strong> <?php echo number_format((float) $plan['tax'], 2); ?> %</p><?php */?>
                    <p><strong>Billing Cycle:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $plan['billing_cycle'] ?? 'monthly'))); ?></p>
                    <p><strong>Duration:</strong> <?php echo $duration; ?> Days</p>
                    <p><strong>No of Staff:</strong> <?php echo isset($plan['no_of_staff']) ? e($plan['no_of_staff']) : 'N/A'; ?></p><p class="mtop20">&nbsp;</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="panel_s">
                  <div class="panel-body mail-bg">
                    <h4 class="tw-font-semibold">Features</h4>
					
                    <?php if (!empty($plan['features'])) {
                      $features = array_filter(array_map('trim', explode(',', $plan['features'])));
                    } ?>
                    <?php if (!empty($features)) { ?>
                      <ul class="list-unstyled">
                        <?php foreach ($features as $feature) { ?>
                          <li><?php echo e($feature); ?></li>
						  <li>CRM subscription period:  <strong><?php echo $start_date;?></strong> - <strong><?php echo $end_date;?></strong></li>
                        <?php } ?>
                      </ul>
                    <?php } else { ?>
                      <p>No features listed.</p>
                    <?php } ?>
					<h4 class="tw-font-semibold">Amount to pay : <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo $price; ?> + Tax:</strong> <?php echo number_format((float) $plan['tax'], 2); ?> %</h4>
                    <div class="mtop20">
					<a href="<?php echo admin_url('services/choose_subscriptions'); ?>" class="btn btn-danger" title="Back to subscriptions">
                        Back
                      </a>
                      <a href="<?php echo admin_url('services/subscriptions_payment?pid='.$plan['id']); ?>" class="btn btn-primary">
                        Pay Now
                      </a> <p class="text-right"><a href="<?php echo admin_url('services/plan_details/4'); ?>" class="mtop20" title="Choose Pro Rata Plan">Choose Pro Rata Plan</a>

                    </div>
                  </div>
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
