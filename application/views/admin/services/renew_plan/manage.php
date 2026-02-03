<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head();
$price = number_format((float) $plan['price'], 2);
$duration = isset($plan['duration']) ? (int) $plan['duration'] : 0;
$startDate = new DateTime();              // today
$end_date   = $plan['end_date'];
$start_date = $startDate->format('F d, Y');
$numberofstaff=isset($plan['no_of_staff']) ? e($plan['no_of_staff']) : 0;
$totaladdedstaff=isset($plan['staff_limit']) ? e($plan['staff_limit']) : 0;
$extraStaff=$totaladdedstaff - $numberofstaff;
?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-6 tw-font-semibold tw-text-lg"><i class="fa-solid fa-users"></i> Renew Subscription Plan Details</h4>
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
					 <p><strong>Service Period:</strong> <strong><?php echo $start_date;?></strong> - <strong><?php echo date('F d, Y',strtotime($end_date));?> </strong></p>
                    <p><strong>Duration:</strong> <?php echo $duration; ?> Days</p>
                    <p><strong>No of Staff:</strong> <?php echo "(".$totaladdedstaff.")"; ?> <?php echo $numberofstaff; if($extraStaff > 0) { echo " Base + ".$extraStaff." Added";} ?></p><p class="mtop20">&nbsp;</p>
					<h4 class="tw-font-semibold">Features</h4>
					
                    <?php if (!empty($plan['features'])) {
                      $features = array_filter(array_map('trim', explode(',', $plan['features'])));
                    } ?>
                    <?php if (!empty($features)) { ?>
                      <ul class="list-unstyled">
                        <?php foreach ($features as $feature) { ?>
                          <li><?php echo e($feature); ?></li>
						  <?php /*?><li>CRM subscription period:  <strong><?php echo $start_date;?></strong> - <strong><?php echo $end_date;?></strong></li><?php */?>
                        <?php } ?>
                      </ul>
                    <?php } else { ?>
                      <p>No features listed.</p>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6 ">
                <div class="panel_s">
                  <div class="panel-body mail-bg">
				  
<?php

$extraAmount=0;
if($extraStaff > 0){
$extraAmount= $extraStaff * ($price / $duration );
}
$baseAmount= $price + $extraAmount;

$taxRate = number_format((float) $plan['tax'], 2);
$taxAmount = ($baseAmount * $taxRate) / 100;
$totalAmount=($baseAmount + $taxAmount);

$today = date('Y-m-d');

if ($end_date && strtotime($today) <= strtotime($end_date)) {
    // Renew before expiry
    $new_start_date = date('Y-m-d', strtotime($end_date.' +1 day'));
} else {
    // Renew after expiry
    $new_start_date = $today;
}
$new_end_date = date("Y-m-d", strtotime($new_start_date." +$duration days"));

?>
<?php echo form_open(admin_url('services/subscriptions_renew_plan'), ['id' => 'post-renew-plan-form']); ?>
<input type="hidden" name="subscription_id" value="<?php echo e($plan['subscription_id']); ?>" />
<input type="hidden" name="user_subscription_id" value="<?php echo e($plan['id']); ?>" />
<input type="hidden" name="extraStaff" value="<?php echo $extraStaff;?>" />
<input type="hidden" name="endDate" value="<?php echo $end_date;?>" />

<p><strong>No of Staff:</strong> <?php echo $totaladdedstaff; ?></p>
<p>Period:  <strong><?php echo date('F d, Y',strtotime($new_start_date));?></strong> - <strong><?php echo date('F d, Y',strtotime($new_end_date));?></strong> </p>
<p>Extra for additional Staff : <?php echo e($plan['currency'] ?? 'INR'); ?>  <?php  echo number_format((float) $extraAmount, 2);?></p>
<p>Base Amount : <?php echo e($plan['currency'] ?? 'INR'); ?> <?php echo number_format((float) $baseAmount, 2);?></p>
<p>Tax Amount (<?php echo number_format((float) $taxAmount, 2);?>) <?php echo number_format((float) $plan['tax'], 2); ?> %</p>
<p>Total Amount (<?php  echo number_format((float) $totalAmount, 2);?>)</p>
<h4 class="tw-font-semibold">Amount to pay : <?php  echo number_format((float) $totalAmount, 2);?></h4>
<div class="mtop20">
					
<?php if($baseAmount > 0){ ?>
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
