<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.control-label, label {
    color: aliceblue !important;
}
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <?php echo render_date_input('activity_log_date', 'utility_activity_log_filter_by_date', '', [], [], '', 'activity-log-date'); ?>
                    </div>
                    <div class="col-md-8 text-right mtop20">
<h4 class=" tw-font-semibold tw-text-lg tw-text-neutral-700  tw-inline-flex tw-items-center tw-mx-2">
<?php if(isset($_GET['sid'])&&$_GET['sid']){ echo base64_decode($_GET['sid']); }?></h4>
                        <?php /*?><a class="btn btn-danger _delete"
                            href="<?php echo admin_url('utilities/clear_activity_log'); ?>"><?php echo _l('clear_activity_log'); ?></a><?php */?>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="panel-table-full">
                            <?php render_datatable([
                            _l('utility_activity_log_dt_description'),
                            _l('utility_activity_log_dt_date'),
                            _l('utility_activity_log_dt_staff'),
                            ], 'activity-log'); ?>
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