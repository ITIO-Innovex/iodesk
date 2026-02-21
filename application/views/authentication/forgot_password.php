<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="authentication forgot-password register_admin"  ><?php /*?>style="background-image: url(<?php echo base_url('uploads/bg/iodesk-bg1502.jpg');?>)  !important; "
<?php */?>
    <div class="tw-max-w-md tw-mx-auto tw-pt-24 authentication-form-wrappe tw-relative tw-z-20">
        <div class="company-logo text-center">
            <?php echo get_company_logo(get_admin_uri() . '/', 'v-logo')?>
        </div>

        

        <div class=" tw-mx-2 sm:tw-mx-6 tw-py-6 tw-px-6 sm:tw-px-8 tw-shadow tw-rounded-lg out-form" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
		<h1 class="tw-text-2xl tw-text-white text-center tw-font-semibold tw-mb-5"><?php echo _l('admin_auth_forgot_password_heading'); ?></h1>
            <?php echo form_open($this->uri->uri_string()); ?>

            <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

            <?php $this->load->view('authentication/includes/alerts'); ?>

            <?php echo render_input('email', 'admin_auth_forgot_password_email', set_value('email'), 'email'); ?>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-block">
                    <?php echo _l('admin_auth_forgot_password_button'); ?>
                </button>
            </div>
            <div class="form-group tw-text-white">
                <a href="<?php echo admin_url('authentication/'); ?>">
                    <?php echo _l('Need Login?'); ?>
                </a>
            </div>
            <?php echo form_close(); ?>
        </div>

    </div>
</body>

</html>