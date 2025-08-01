<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="mtop40" >
    <div class="company-logo text-center  out-form">
<?php echo get_company_logo(get_admin_uri() . '/', 'navbar-brand logo v-logo')?>			</div>
    <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 " >
        <?php echo form_open($this->uri->uri_string(), ['class' => 'login-form']); ?>
        <?php hooks()->do_action('clients_login_form_start'); ?>
        <div class="panel_s box-shadow-bg" >
            <div class="panel-body out-form">
<h1 class="tw-font-semibold text-center mt-0 pt-0">
            <?php
         echo _l(get_option('allow_registration') == 1 ? 'clients_login_heading_register' : 'clients_login_heading_no_register');
         ?>
        </h1>
                <?php if (!is_language_disabled()) { ?>
                <?php /*?><div class="form-group">
                    <label for="language" class="control-label"><?php echo _l('language'); ?>
                    </label>
                    <select name="language" id="language" class="form-control selectpicker"
                        onchange="change_contact_language(this)"
                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                        data-live-search="true">
                        <?php $selected = (get_contact_language() != '') ? get_contact_language() : get_option('active_language'); ?>
                        <?php foreach ($this->app->get_available_languages() as $availableLanguage) { ?>
                        <option value="<?php echo e($availableLanguage); ?>"
                            <?php echo ($availableLanguage == $selected) ? 'selected' : '' ?>>
                            <?php echo e(ucfirst($availableLanguage)); ?>
                        </option>
                        <?php } ?>
                    </select>
                </div><?php */?>
                <?php } ?>

                <div class="form-group">
                    <label for="email" class="tw-text-white"><?php echo _l('clients_login_email'); ?></label>
                    <input type="text" autofocus="true" class="form-control" name="email" id="email">
                    <?php echo form_error('email'); ?>
                </div>

                <div class="form-group">
                    <label for="password" class="tw-text-white"><?php echo _l('clients_login_password'); ?></label>
                    <input type="password" class="form-control" name="password" id="password">
                    <?php echo form_error('password'); ?>
                </div>

                <?php if (show_recaptcha_in_customers_area()) { ?>
                <div class="g-recaptcha tw-mb-4" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
                <?php echo form_error('g-recaptcha-response'); ?>
                <?php } ?>

                <div class="checkbox">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember"  class="tw-text-white">
                        <?php echo _l('clients_login_remember'); ?>
                    </label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">
                        <?php echo _l('clients_login_login_string'); ?>
                    </button>
                    <?php if (get_option('allow_registration') == 1) { ?>
                    <a href="<?php echo site_url('authentication/register'); ?>" class="btn btn-success btn-block">
                        <?php echo _l('clients_register_string'); ?>
                    </a>
                    <?php } ?>
                </div>
                <a href="<?php echo site_url('authentication/forgot_password'); ?>" class="tw-text-white">
                    <?php echo _l('customer_forgot_password'); ?>
                </a>
                <?php hooks()->do_action('clients_login_form_end'); ?>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>