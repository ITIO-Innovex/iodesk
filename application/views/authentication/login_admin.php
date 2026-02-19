<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="login_admin" style="background-image: url(<?php echo base_url('uploads/bg/iodesk-bg1502.jpg');?>)  !important; " >

    <div class="tw-max-w-md tw-mx-auto tw-pt-24 authentication-form-wrapper tw-relative tw-z-20 out-form">
        <div class="company-logo text-center"><?php echo get_company_logo(get_admin_uri() . '/', '!tw-mt-0')?></div>
        <div class=" tw-mx-2 sm:tw-mx-6 tw-py-6 tw-px-6 sm:tw-px-8 tw-shadow tw-rounded-lg" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
<h1 class="tw-text-2xl tw-text-white text-center tw-font-semibold"><?php echo _l('admin_auth_login_heading');?></h1>
            <?php $this->load->view('authentication/includes/alerts'); ?>

            <?php echo form_open($this->uri->uri_string()); ?>

            <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

            <?php hooks()->do_action('after_admin_login_form_start'); ?>

            <div class="form-group">
                <label for="email" class="tw-text-white">
                    <?php echo _l('admin_auth_login_email'); ?>
                </label>
                <input type="email" id="email" name="email" class="form-control" autofocus="1">
            </div>

            <div class="form-group">
                <label for="password" class="tw-text-white">
                    <?php echo _l('admin_auth_login_password'); ?>
                </label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control">
                    <span class="input-group-addon" id="toggle-password" style="cursor:pointer;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                    </span>
                </div>
            </div>

            <?php if (show_recaptcha()) { ?>
            <div class="g-recaptcha tw-mb-4" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
            <?php } ?>

            <div class="form-group">
                <div class="checkbox checkbox-inline tw-text-white">
                    <input type="checkbox" value="estimate" id="remember" name="remember">
                    <label for="remember"> <?php echo _l('admin_auth_login_remember_me'); ?></label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <?php echo _l('admin_auth_login_button'); ?>
                </button>
            </div>

            <div class="form-group tw-text-white">
                <a href="<?php echo admin_url('authentication/forgot_password'); ?>" title="Forgot Password">
                    <?php echo _l('admin_auth_login_fp'); ?>
                </a>
				<a href="<?php echo base_url('authentication/get_register'); ?>" class="pull-right" title="Register your company">
                    <?php echo 'Register your company'; ?>
                </a>
            </div>

            <?php hooks()->do_action('before_admin_login_form_close'); ?>

            <?php echo form_close(); ?>
        </div>
    </div>

</body>

</html>
<script>
(function(){
  var toggle = document.getElementById('toggle-password');
  if (toggle) {
    toggle.addEventListener('click', function(){
      var input = document.getElementById('password');
      if (!input) return;
      var isPwd = input.getAttribute('type') === 'password';
      input.setAttribute('type', isPwd ? 'text' : 'password');
      var icon = this.querySelector('i');
      if (icon) {
        icon.className = isPwd ? 'fa fa-eye-slash' : 'fa fa-eye';
      }
    });
  }
})();
</script>