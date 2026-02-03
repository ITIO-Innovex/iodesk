<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
              <h4 class="tw-mb-0 tw-font-semibold tw-text-lg">Sent Renewal Reminder</h4>
              <a href="<?php echo base_url('cronjob/send_renewal_reminder'); ?>" class="btn btn-info pull-right" target="_blank">
              <i class="fa-solid fa-envelopes-bulk"></i> Send Renewal Reminder                                    </a>
            </div>
            <?php render_datatable([
              'ID',
              'Company Name',
              'Mail To',
              'Renewal Date',
              'Sent On',
            ], 'services_subscriptions_reminder_email'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  $(function(){
    initDataTable('.table-services_subscriptions_reminder_email', window.location.href, [], [], undefined, [0, 'desc']);
  });
</script>
</body>
</html>
