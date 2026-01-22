<?php defined('BASEPATH') or exit('No direct script access allowed');
init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
              <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Service Subscriptions</h4>
              <a href="#" class="btn btn-primary" onclick="open_subscription_modal(); return false;">
                <i class="fa-regular fa-plus tw-mr-1"></i> Add New Subscription
              </a>
            </div>
            <?php render_datatable([
              'ID',
              'Plan Name',
              'Price',
              'Currency',
              'Billing Cycle',
              'Duration',
              'No of Staff',
              'Tax (%)',
              'Status',
              _l('options'),
            ], 'services_subscriptions'); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="subscription_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('services/subscriptions_manage'), ['id' => 'subscription-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="subscription_modal_label">Add Subscription</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="subscription_id" value="">
        <div class="form-group">
          <label for="plan_name" class="control-label">Plan Name</label>
          <input type="text" class="form-control" id="plan_name" name="plan_name" placeholder="e.g. Starter">
        </div>
        <div class="form-group">
          <label for="price" class="control-label">Price</label>
          <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="0.00">
        </div>
        <div class="form-group">
          <label for="currency" class="control-label">Currency</label>
          <input type="text" class="form-control" id="currency" name="currency" value="INR">
        </div>
        <div class="form-group">
          <label for="billing_cycle" class="control-label">Billing Cycle</label>
          <select class="form-control selectpicker" name="billing_cycle" id="billing_cycle" data-none-selected-text="Select billing cycle">
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
            <option value="per_month">Pro Month / Pro Data</option>
          </select>
        </div>
        <div class="form-group">
          <label for="duration" class="control-label">Duration</label>
          <input type="number" class="form-control" id="duration" name="duration" placeholder="Number of months or years">
        </div>
        <div class="form-group">
          <label for="no_of_staff" class="control-label">No of Staff</label>
          <input type="number" class="form-control" id="no_of_staff" name="no_of_staff" placeholder="Number of staff">
        </div>
        <div class="form-group">
          <label for="tax" class="control-label">Tax (%)</label>
          <input type="number" step="0.01" class="form-control" id="tax" name="tax" placeholder="0.00">
        </div>
        <div class="form-group">
          <label for="features" class="control-label">Features</label>
          <textarea class="form-control" id="features" name="features" rows="3" placeholder="Comma separated features"></textarea>
        </div>
        <div class="form-group">
          <label for="status" class="control-label">Status</label>
          <select class="form-control selectpicker" name="status" id="status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
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
  function open_subscription_modal() {
    $('#subscription_modal_label').text('Add Subscription');
    $('#subscription_id').val('');
    $('#plan_name').val('');
    $('#price').val('');
    $('#currency').val('INR');
    $('#billing_cycle').selectpicker('val', 'monthly');
    $('#duration').val('');
    $('#no_of_staff').val('');
    $('#tax').val('');
    $('#features').val('');
    $('#status').selectpicker('val', 'active');
    $('#subscription_modal').modal('show');
  }

  function edit_subscription(el, id) {
    $('#subscription_modal_label').text('Edit Subscription');
    $('#subscription_id').val(id);
    $('#plan_name').val($(el).data('plan-name'));
    $('#price').val($(el).data('price'));
    $('#currency').val($(el).data('currency'));
    $('#billing_cycle').selectpicker('val', $(el).data('billing-cycle'));
    $('#duration').val($(el).data('duration'));
    $('#no_of_staff').val($(el).data('no-of-staff'));
    $('#tax').val($(el).data('tax'));
    $('#features').val($(el).data('features'));
    $('#status').selectpicker('val', $(el).data('status'));
    $('#subscription_modal').modal('show');
  }

  $(function(){
    // Columns: 0 ID, 1 Plan Name, 2 Price, 3 Currency, 4 Billing Cycle, 5 Duration, 6 No of Staff, 7 Tax, 8 Status, 9 Options
    initDataTable('.table-services_subscriptions', window.location.href, [9], [9], undefined, [0, 'desc']);

    $('#subscription-form').on('submit', function(e){
      e.preventDefault();
      var $f = $(this);
      $.post($f.attr('action'), $f.serialize()).done(function(resp){
        var r = {};
        try { r = JSON.parse(resp); } catch(e) {}
        if (r.success) {
          alert_float('success', r.message || 'Saved');
          $('#subscription_modal').modal('hide');
          $('.table-services_subscriptions').DataTable().ajax.reload();
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
