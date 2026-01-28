<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head();
$price = number_format((float) $plan['price'], 2);
$duration = isset($plan['duration']) ? (int) $plan['duration'] : 0;
$startDate = new DateTime();              // today
$end_date   = $plan['end_date'];

$start_date = $startDate->format('F d, Y');
//$end_date   = $endDate->format('F d, Y');
?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-6 tw-font-semibold tw-text-lg"><i class="fa-solid fa-users"></i> Upgrade Staff Plan Details</h4>
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
				  
	$planPrice=$plan['price'];
	$baseStaff=$plan['no_of_staff'];
	$extraStaff=$requested_staff;
	$startDate=$startDate->format('Y-m-d');;
	$endDate=$plan['end_date'];
	$taxPercent=$plan['tax'];
	
	$result = calculateExtraStaffPrice(
    planPrice: $planPrice,
    baseStaff: $baseStaff,
    extraStaff: $extraStaff,
    startDate: $startDate,
    endDate: $endDate,
    taxPercent: $taxPercent
);

//print_r($result);
    
echo $base_amount=number_format((float) $result['base_amount'], 2)				  
?>
<?php echo form_open(admin_url('services/subscriptions_add_staff_payment'), ['id' => 'post-staff-form']); ?>
<input type="hidden" name="subscription_id" value="<?php echo e($plan['subscription_id']); ?>" />
<input type="hidden" name="user_subscription_id" value="<?php echo e($plan['id']); ?>" />
<input type="hidden" name="amount" value="<?php  echo number_format((float) $result['total_amount'], 2);?>" />
<input type="hidden" name="currency" value="<?php echo e($plan['currency'] ?? 'INR'); ?>" />
<input type="hidden" name="tax" value="<?php echo number_format((float) $plan['tax'], 2); ?>" />
<input type="hidden" name="extraStaff" value="<?php echo $extraStaff;?>" />
<input type="hidden" name="per_staff_per_day" value="<?php echo $result['per_staff_per_day'];?>" />
<input type="hidden" name="base_amount" value="<?php echo number_format((float) $result['base_amount'], 2);?>" />
<input type="hidden" name="tax_amount" value="<?php echo number_format((float) $result['tax_amount'], 2);?>" />
<input type="hidden" name="total_amount" value="<?php  echo number_format((float) $result['total_amount'], 2);?>" />

                    <p>Add No of Staff : <?php echo $extraStaff;?></p>
					<p>Period:  <strong><?php echo $start_date;?></strong> - <strong><?php echo $end_date;?> Remaining Days (<?php echo $result['remaining_days'];?>)</strong> </p>
					<p>Per Staff Per Day (<?php echo $result['per_staff_per_day'];?>)</p>
					<p>Base Amount (<?php echo number_format((float) $result['base_amount'], 2);?>)</p>
				<p>Tax Amount (<?php echo number_format((float) $result['tax_amount'], 2);?>) <?php echo number_format((float) $plan['tax'], 2); ?> %</p>
					<p>Total Amount (<?php  echo number_format((float) $result['total_amount'], 2);?>)</p>
					<h4 class="tw-font-semibold">Amount to pay : <?php echo $result['total_amount'];?></h4>
                    <div class="mtop20">
					
<?php if($base_amount > 0){ ?>
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
