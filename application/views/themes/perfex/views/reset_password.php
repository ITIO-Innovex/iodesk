<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<style>
a, label, .control-label, a:focus, a:hover {
    color: #FFFFFF;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4 mtop40 text-center">
            
            <div class="panel_s text-left box-shadow-bg">
                <div class="panel-body out-form">
				<h1 class="tw-font-semibold"><?php echo _l('customer_reset_password_heading'); ?></h1>
                    <?php echo form_open($this->uri->uri_string()); ?>
                    <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
                    <?php if ($this->session->flashdata('message-danger')) { ?>
                    <div class="alert alert-danger">
                        <?php echo $this->session->flashdata('message-danger'); ?>
                    </div>
                    <?php } ?>
                    <?php echo render_input('password', 'customer_reset_password', '', 'password'); ?>
                    <?php echo render_input('passwordr', 'customer_reset_password_repeat', '', 'password'); ?>
                    <div class="form-group">
                        <button type="submit"
                            class="btn btn-primary btn-block"><?php echo _l('customer_reset_action'); ?></button>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
