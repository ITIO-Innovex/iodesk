<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
              <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Service Company Subscriptions</h4>
              <a href="#" class="btn btn-primary" onclick="open_user_subscription_modal(); return false;">
                <i class="fa-regular fa-plus tw-mr-1"></i> Add New Company Subscription
              </a>
            </div>
            <?php render_datatable([
              'ID',
              'Company ID',
              'Subscription ID',
              'Start Date',
              'End Date',
              'Status',
              _l('options'),
            ], 'services_user_subscriptions'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="user_subscription_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('services/user_subscriptions_manage'), ['id' => 'user-subscription-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="user_subscription_modal_label">Add Company Subscription</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="user_subscription_id" value="">
        <div class="form-group">
          <label for="company_id" class="control-label">Company ID</label>
          <input type="number" class="form-control" id="company_id" name="company_id" placeholder="Company ID">
        </div>
        <div class="form-group">
          <label for="subscription_id" class="control-label">Subscription ID</label>
          <input type="number" class="form-control" id="subscription_id" name="subscription_id" placeholder="Subscription ID">
        </div>
        <div class="form-group">
          <label for="start_date" class="control-label">Start Date</label>
          <input type="date" class="form-control" id="start_date" name="start_date">
        </div>
        <div class="form-group">
          <label for="end_date" class="control-label">End Date</label>
          <input type="date" class="form-control" id="end_date" name="end_date">
        </div>
        <div class="form-group">
          <label for="status" class="control-label">Status</label>
          <select class="form-control selectpicker" name="status" id="status">
            <option value="active">Active</option>
            <option value="expired">Expired</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  function open_user_subscription_modal() {
    $('#user_subscription_modal_label').text('Add Company Subscription');
    $('#user_subscription_id').val('');
    $('#company_id').val('');
    $('#subscription_id').val('');
    $('#start_date').val('');
    $('#end_date').val('');
    $('#status').selectpicker('val', 'active');
    $('#user_subscription_modal').modal('show');
  }

  function edit_user_subscription(el, id) {
    $('#user_subscription_modal_label').text('Edit Company Subscription');
    $('#user_subscription_id').val(id);
    $('#company_id').val($(el).data('company-id'));
    $('#subscription_id').val($(el).data('subscription-id'));
    $('#start_date').val($(el).data('start-date'));
    $('#end_date').val($(el).data('end-date'));
    $('#status').selectpicker('val', $(el).data('status'));
    $('#user_subscription_modal').modal('show');
  }

  $(function(){
    // Columns: 0 ID, 1 Company ID, 2 Subscription ID, 3 Start Date, 4 End Date, 5 Status, 6 Options
    initDataTable('.table-services_user_subscriptions', window.location.href, [6], [6], undefined, [0, 'desc']);

    $('#user-subscription-form').on('submit', function(e){
      e.preventDefault();
      var $f = $(this);
      $.post($f.attr('action'), $f.serialize()).done(function(resp){
        var r = {};
        try { r = JSON.parse(resp); } catch(e) {}
        if (r.success) {
          alert_float('success', r.message || 'Saved');
          $('#user_subscription_modal').modal('hide');
          $('.table-services_user_subscriptions').DataTable().ajax.reload();
        } else {
          alert_float('warning', r.message || 'Validation failed');
        }
      }).fail(function(){
        alert_float('danger', 'Request failed');
      })
    });
  });
</script>
</body>
</html>
