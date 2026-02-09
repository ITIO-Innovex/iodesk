<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head();
//print_r($_SESSION);

$price = number_format((float) $plan['price'], 2);
$duration = isset($plan['duration']) ? (int) $plan['duration'] : 0;
$startDate = new DateTime();              // today
$days=e($duration -1 ?? 0);
$endDate   = (clone $startDate)->modify("+$days days");

$start_date = $startDate->format('F d, Y');
$end_date   = $endDate->format('F d, Y');
//echo $_SESSION['cms_subscription_staff_limit'];

$additionalAmount=0;
// for Extra Staff Amount Return Amount
if($_SESSION['cms_subscription_staff_limit'] > $old_plan['no_of_staff']){
$extrastaffcount=($_SESSION['cms_subscription_staff_limit'] - $old_plan['no_of_staff']) ?? 0;
$additionalAmount=$this->services_subscriptions_model->get_staff_expansion($extrastaffcount);
}
//checkextrastaffamount();
//echo "XXXXXXXX";
//echo $old_plan['price'];
//echo $old_plan['duration'];
//echo $_SESSION['cms_subscription_end_date'];

//echo "===========>>";echo $old_plan['price'];echo $old_plan['duration'];echo $_SESSION['cms_subscription_end_date'];
    $unusedBalance = calculateUnusedPlanBalance(
    $old_plan['price'],        // price
    $old_plan['duration'],     // duration (days)
    $_SESSION['cms_subscription_end_date']      // subscription end date
);

if($additionalAmount > 0){
$unusedBalance =$unusedBalance + calculateUnusedPlanBalance(
    $additionalAmount,        // price
    $old_plan['duration'],     // duration (days)
    $_SESSION['cms_subscription_end_date']      // subscription end date
);
}

?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-6 tw-font-semibold tw-text-lg"><i class="fa-solid fa-credit-card"></i> Upgrated Plan Details</h4>
            <hr class="hr-panel-heading">
            <div class="row">
			  <div class="col-md-6">
                <div class="panel_s">
                  <div class="panel-body mail-bg">
                    <h4 class="tw-font-semibold">Current Plan : <?php echo e($old_plan['plan_name']); ?></h4>
                    <?php /*?><p class="text-muted">Plan ID: <?php echo e($plan['id']); ?></p><?php */?>
                    <p><strong>Price:</strong> <?php echo e($old_plan['currency'] ?? 'INR'); ?> <?php echo $old_plan['price']; ?></p>
					<?php /*?><p><strong>Tax:</strong> <?php echo number_format((float) $plan['tax'], 2); ?> %</p><?php */?>
                    <p><strong>Billing Cycle:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $old_plan['billing_cycle'] ?? 'monthly'))); ?></p>
                    <p><strong>Duration:</strong> <?php echo $old_plan['duration']; ?> Days</p>
                    <p><strong>No of Staff:</strong> <?php echo isset($old_plan['no_of_staff']) ? e($old_plan['no_of_staff']) : 'N/A'; ?></p><p class="mtop20">&nbsp;</p>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="panel_s">
                  <div class="panel-body mail-bg">
                    <h4 class="tw-font-semibold">Upgrated Plan : <?php echo e($plan['plan_name']); ?></h4>
                    <?php /*?><p class="text-muted">Plan ID: <?php echo e($plan['id']); ?></p><?php */?>
                    <p><strong>Price:</strong> <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo $price; ?></p>
					<?php /*?><p><strong>Tax:</strong> <?php echo number_format((float) $plan['tax'], 2); ?> %</p><?php */?>
                    <p><strong>Billing Cycle:</strong> <?php echo e(ucfirst(str_replace('_', ' ', $plan['billing_cycle'] ?? 'monthly'))); ?></p>
                    <p><strong>Duration:</strong> <?php echo $duration; ?> Days</p>
                    <p><strong>No of Staff:</strong> <?php echo isset($plan['no_of_staff']) ? e($plan['no_of_staff']) : 'N/A'; ?></p><p class="mtop20">&nbsp;</p>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
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
					<?php

//$amount = "9,999.00";        // string
//$unusedBalance = "932.4";    // string

$amount = (float) str_replace(',', '', $price);
$unusedBalance = (float) str_replace(',', '', $unusedBalance);

$result = $amount - $unusedBalance;
$tot_amount=number_format($result, 2);
					?>
					<p>Unused Amount for Current plan : <?php echo $unusedBalance;?></p>
<h5 class="tw-font-semibold">Plan Amount : <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo $price; ?></h5>
<h4 class="tw-font-semibold">Amount to pay : <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo $tot_amount; ?> + Tax:</strong> <?php echo number_format((float) $plan['tax'], 2); ?> %</h4>
					
<?php echo form_open(admin_url('services/subscriptions_plan_upgrade'), ['id' => 'post-staff-form']); ?>
<input type="hidden" name="old_subscription_id" value="<?php echo e($old_plan['id']); ?>" />
<input type="hidden" name="new_subscription_id" value="<?php echo e($plan['id']); ?>" />
<input type="hidden" name="unused_amount" value="<?php echo number_format((float) $unusedBalance, 2);?>" />
<div class="mtop20">
<?php if($plan['price'] > 0){ ?>
<a href="<?php echo admin_url('services/my_subscriptions'); ?>" class="btn btn-danger" title="Back to subscriptions">Back</a>
                       <button type="submit" class="btn btn-primary">Pay Now</button> 
					   <?php }else{ ?>
<a href="<?php echo admin_url('services/my_subscriptions'); ?>" class="btn btn-danger" title="Back to subscriptions">Your current plan does not support staff upgrades</a>
					   <?php } ?>
					   </div>
<?php echo form_close(); ?>
                    
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
