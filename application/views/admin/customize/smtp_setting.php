<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="clearfix mtop10 mbot20">
                            <h4 class="pull-left"><?php echo 'SMTP Settings'; ?></h4>
                        </div>
                        <?php echo form_open(admin_url('customize/smtp_setting'), ['id' => 'smtp-settings-form']); ?>
                        <ul class="nav nav-tabs" role="tablist" >
                            <li role="presentation" class="active modal-content">
                                <a href="#nda-smtp" aria-controls="nda-smtp" role="tab" data-toggle="tab">
                                    <?php echo 'NDA SMTP Settings'; ?>
                                </a>
                            </li>
                            <li role="presentation" class="mail-bg">
                                <a href="#direct-smtp" aria-controls="direct-smtp" role="tab" data-toggle="tab">
                                    <?php echo 'Direct Email SMTP Settings'; ?>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active modal-content" id="nda-smtp">
                                <div class="row tw-p-2">
                                    <?php foreach ($smtp_fields as $field_key => $label): ?>
                                        <?php
                                            $input_type = 'text';
                                            if ($field_key === 'smtp_password') {
                                                $input_type = 'password';
                                            } elseif ($field_key === 'smtp_port') {
                                                $input_type = 'number';
                                            }
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nda-<?php echo $field_key; ?>">
                                                    <?php echo html_escape($label); ?>
                                                </label>
                                                <input
                                                    type="<?php echo $input_type; ?>"
                                                    class="form-control"
                                                    id="nda-<?php echo $field_key; ?>"
                                                    name="nda[<?php echo $field_key; ?>]"
                                                    value="<?php echo html_escape($nda_smtp[$field_key] ?? ''); ?>"
                                                    autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane mail-bg" id="direct-smtp" style="border: 1px solid rgba(0, 0, 0, .2);
    border-radius: 6px;">
                                <div class="row tw-p-2">
                                    <?php foreach ($smtp_fields as $field_key => $label): ?>
                                        <?php
                                            $input_type = 'text';
                                            if ($field_key === 'smtp_password') {
                                                $input_type = 'password';
                                            } elseif ($field_key === 'smtp_port') {
                                                $input_type = 'number';
                                            }
                                        ?>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="direct-<?php echo $field_key; ?>">
                                                    <?php echo html_escape($label); ?>
                                                </label>
                                                <input
                                                    type="<?php echo $input_type; ?>"
                                                    class="form-control"
                                                    id="direct-<?php echo $field_key; ?>"
                                                    name="direct[<?php echo $field_key; ?>]"
                                                    value="<?php echo html_escape($direct_smtp[$field_key] ?? ''); ?>"
                                                    autocomplete="off"
                                                />
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-check"></i> <?php echo _l('submit'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    (function($) {
        'use strict';
        $('#smtp-settings-form').on('submit', function () {
            $(this).find('button[type="submit"]').prop('disabled', true);
        });
    })(jQuery);
</script>
</body>
</html>
