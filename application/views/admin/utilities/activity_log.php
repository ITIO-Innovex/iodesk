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
                        <a class="btn btn-danger _delete"
                            href="<?php echo admin_url('utilities/clear_activity_log'); ?>"><?php echo _l('clear_activity_log'); ?></a>
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