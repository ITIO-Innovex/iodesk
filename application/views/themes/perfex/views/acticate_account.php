<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
#vsidebar { display:none !important;}
</style>
<div class="mtop40">
    <div class="company-logo text-center out-form">
        <?php echo get_company_logo(get_admin_uri() . '/', 'navbar-brand logo v-logo')?>
    </div>
    <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
        <div class="panel_s box-shadow-bg">
            <div class="panel-body out-form text-center">
                <h1 class="tw-font-semibold mt-0 pt-0"><?php echo $title;?></h1>
				<?php if(isset($title)&&$title<>404){ ?>
				<p class="tw-text-white">Your account has been successfully activated!</p>
                <p class="tw-text-white">You can now log in using your registered email and password. Sent with seperate email</p>
                <p class="tw-text-white"><a href="<?php echo admin_url('authentication');?>" class="btn btn-primary">Click here to Login</a></p>              <?php }else{ ?>
				<p class="tw-text-white">404 Page Not Found</p>
				<?php } ?>
                <div class="mtop20">
                   
                </div>
            </div>



        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $("#varea").removeClass("col-md-9 col-lg-10");
});
</script>