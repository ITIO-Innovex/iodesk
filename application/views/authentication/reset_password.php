<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="authentication reset-password" style="background-image: url(<?php echo base_url('uploads/bg/iodesk-bg1502.jpg');?>)  !important; ">
    <div class="tw-max-w-md tw-mx-auto tw-pt-24 authentication-form-wrapper tw-relative tw-z-20">

        <div class="company-logo text-center">
            <?php echo get_company_logo(get_admin_uri() . '/', 'v-logo')?>
        </div>


        <div class=" tw-mx-2 sm:tw-mx-6 tw-py-6 tw-px-6 sm:tw-px-8 tw-shadow tw-rounded-lg" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
<h1 class="tw-text-2xl tw-text-white text-center tw-font-semibold"><?php echo _l('admin_auth_reset_password_heading'); ?></h1>
            <?php echo form_open($this->uri->uri_string()); ?>
            <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
            <?php $this->load->view('authentication/includes/alerts'); ?>
            <?php echo render_input('password', 'admin_auth_reset_password', '', 'password'); ?>
            <?php echo render_input('passwordr', 'admin_auth_reset_password_repeat', '', 'password'); ?>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <?php echo _l('auth_reset_password_submit'); ?>
                </button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</body>

</html>