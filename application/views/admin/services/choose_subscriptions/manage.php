<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); 
$plantitle="";
if(isset($planstype)&&$planstype=="Upgrade"){
$plantitle="For Upgrade";
}

?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-6 tw-font-semibold tw-text-lg"><i class="fa-solid fa-cart-plus"></i> Choose Subscription Plan <?php echo $plantitle;?></h4>
            <hr class="hr-panel-heading">
            <?php if (!empty($plans)) { ?>
              <div class="row ">
                <?php foreach ($plans as $plan) {
                  $price = number_format((float) $plan['price'], 2);
                  $currency = $plan['currency'] ?? 'INR';
                  $cycle = ucfirst(str_replace('_', ' ', $plan['billing_cycle'] ?? 'monthly'));
                  $duration = isset($plan['duration']) ? (int) $plan['duration'] : 0;
                  $noOfStaff = isset($plan['no_of_staff']) ? (int) $plan['no_of_staff'] : 0;
                  $features = [];
                  if (!empty($plan['features'])) {
                      $features = array_filter(array_map('trim', explode(',', $plan['features'])));
                  }
				  
				  if($plan['billing_cycle']=='pro_data'){
				  $datediff=get_remaining_days_in_month();
				  $price = $price * $datediff;
				  $duration= $duration * $datediff;
				  }
				  
                ?>
                  <div class="col-md-4 mb-4 ">
                    <div class="panel_s h-100">
                      <div class="panel-body text-center mail-bg">
                        <h4 class="tw-font-semibold"><?php echo e($plan['plan_name']); ?></h4>
                        <div class="tw-text-2xl tw-font-bold tw-mt-2"><?php echo e($currency); ?> <?php echo e($price); ?>
						
						</div>
                        <p class="text-muted tw-mt-2">
                          <?php if ($duration > 0) { ?>
                            <?php echo e($cycle); ?> (<?php echo e($duration); ?> Days)
                          <?php } else { ?>
                            <?php echo e($cycle); ?>
                          <?php } ?>
                        </p>
                        <p class="tw-mb-2">No of Staff: <?php echo $noOfStaff > 0 ? e($noOfStaff) : 'N/A'; ?></p>
                        <?php if (!empty($features)) { ?>
                          <ul class="list-unstyled tw-mb-4">
                            <?php foreach ($features as $feature) { ?>
                              <li><?php echo e($feature); ?></li>
                            <?php } ?>
                          </ul>
                        <?php } ?>
<?php if(isset($planstype)&&$planstype=="Upgrade"){ ?>
<a href="<?php echo admin_url('services/upgrated_plan_details/'.$plan['id']); ?>" class="btn btn-primary">Choose Plan</a>
<?php }else{ ?>
<a href="<?php echo admin_url('services/plan_details/'.$plan['id']); ?>" class="btn btn-primary">Choose Plan</a>
<?php }?>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No active plans available <?php echo strtolower($plantitle);?>.</div>
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
