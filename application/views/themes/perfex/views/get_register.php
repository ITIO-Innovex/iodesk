<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
#vsidebar { display:none !important;}
</style>
<div class="mtop40">
  <div class="company-logo text-center out-form"> <?php echo get_company_logo(get_admin_uri() . '/', 'navbar-brand logo v-logo')?> </div>
  <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2"> 
  <?php echo form_open(site_url('authentication/post_register'), ['id' => 'company-form']); ?>
    <div class="panel_s box-shadow-bg">
      <div class="panel-body out-form">
        <h1 class="tw-font-semibold text-center tw-mt-0"> <?php echo _l('clients_register_heading'); ?> </h1>
        <div class="row">
          <div class="col-sm-6">
            <div class="form-group register-firstname-group">
              <label class="tw-text-white" for="<?php echo e($fields['firstname']); ?>"> <span class="text-danger">*</span> <?php echo _l('clients_firstname'); ?> </label>
              <input type="text" class="form-control" name="<?php echo e($fields['firstname']); ?>"
                        id="<?php echo e($fields['firstname']); ?>" value="" required>
              </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group register-lastname-group">
              <label class="tw-text-white" for="<?php echo e($fields['lastname']); ?>"> <span class="text-danger">*</span> <?php echo _l('clients_lastname'); ?> </label>
              <input type="text" class="form-control" name="<?php echo e($fields['lastname']); ?>"
                        id="<?php echo e($fields['lastname']); ?>" value="" required>
            </div>
          </div>
        </div>
        <div class="form-group register-email-group">
          <label class="tw-text-white" for="<?php echo e($fields['email']); ?>"> <small class="req text-danger">* </small> Email <i class="fa-solid fa-circle-info text-warning" data-toggle="tooltip" data-title="Please enter the email address. This email will be used for the admin login with full access permissions" ></i> </label>
          <input type="email" class="form-control" name="<?php echo e($fields['email']); ?>"
                        id="<?php echo e($fields['email']); ?>" value="" required></div>
        <div class="form-group register-contact-phone-group">
          <label class="tw-text-white" for="phonenumber"> <span class="text-danger">*</span> <?php echo _l('clients_phone'); ?> </label>
          <input type="text" class="form-control" name="phonenumber" id="phonenumber" value="" required>
          </div>
        <div class="form-group register-contact-companyname-group">
          <label class="tw-text-white" for="companyname"> <span class="text-danger">*</span> Company Name </label>
          <input type="text" class="form-control" name="companyname" id="companyname" value="" required>
          </div>
        <div class="form-group register-contact-website-group">
          <label class="tw-text-white" for="website"> <span class="text-danger">*</span>website </label>
          <input type="url" class="form-control" name="website" id="website" value="" required>
          <?php echo form_error('website'); ?> </div>
        <?php if (is_gdpr() && get_option('gdpr_enable_terms_and_conditions') == 1) { ?>
        <div class="checkbox tw-text-white">
          <input type="checkbox" name="accept_terms_and_conditions" id="accept_terms_and_conditions"
                            <?php echo set_checkbox('accept_terms_and_conditions', 'on'); ?>>
          <label for="accept_terms_and_conditions"> <?php echo _l('gdpr_terms_agree', '#'); ?> </label>
        </div>
        <?php } ?>
        <div class="form-group">
          <?php /*?><button type="submit" autocomplete="off" 
                        class="btn btn-primary btn-block"> <?php echo _l('clients_register_string'); ?> </button><?php */?>
						<button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
        </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function () {
    $("#varea").removeClass("col-md-9 col-lg-10");

    appValidateForm($('#company-form'), {
        <?php echo e($fields['firstname']); ?>: 'required',
        <?php echo e($fields['lastname']); ?>: 'required',
        <?php echo e($fields['email']); ?>: {
            required: true,
            email: true,
            remote: {
                url: "<?php echo site_url('authentication/staff_email_exists'); ?>",
                type: 'post',
                data: {
                    email: function() {
                        return $('input[name="<?php echo e($fields['email']); ?>"]').val();
                    }
                }
            }
        },
        phonenumber: 'required',
        companyname: 'required',
        website: 'required'
    }, function(form) {
        form.submit();
    });

    $('input[name="<?php echo e($fields['email']); ?>"]').attr('data-msg-remote', 'Email already registered . Please change');
	//$("#email-error").addClass("tw-font-bold");


});
</script>
