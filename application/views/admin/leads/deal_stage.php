<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  </style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_stage(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Deal Stage'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($stages) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Stage Name</th>
								<?php /*?><th><?php echo _l('company'); ?></th><?php */?>
                                <th><?php echo _l('status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($stages as $stage) { ?>
                                <tr>
                                    <td>
                                        <i class="fa-solid fa-palette" style="color:<?php echo e($stage['color']); ?>;"></i>&nbsp;&nbsp;<a href="#"
                                            onclick="edit_stage(this,<?php echo e($stage['id']); ?>);return false;"
                                            data-color="<?php echo e($stage['color']); ?>"
                                            data-name="<?php echo e($stage['stage']); ?>"
                                            data-order="<?php echo e($stage['statusorder']); ?>"
                                            data-status="<?php echo e($stage['status']); ?>"
                                        ><?php echo e($stage['stage']); ?></a><br />
                                    </td>
									 <?php /*?><td><?php echo e($stage['company_id']); ?></td><?php */?>
                                    <td>
                                        <a href="javascript:void(0);" onclick="toggleStageStatus(<?php echo $stage['id']; ?>, <?php echo $stage['status']; ?>)" id="status-label-<?php echo $stage['id']; ?>">
                                        <?php if ($stage['status']) { ?>
                                            <span class="label label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-danger">Deactive</span>
                                        <?php } ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_stage(this,<?php echo e($stage['id']); ?>);return false;"
                                                data-color="<?php echo e($stage['color']); ?>"
                                                data-name="<?php echo e($stage['stage']); ?>"
                                                data-order="<?php echo e($stage['statusorder']); ?>"
                                                data-status="<?php echo e($stage['status']); ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <a href="<?php echo admin_url('leads/delete_deal_stage/' . $stage['id']); ?>"
                                                class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                                                <i class="fa-regular fa-trash-can fa-lg"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
            <?php } else { ?>
            <p class="no-margin">No deal stages found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="stage" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('leads/dealstage'), ['id' => 'deal-stage-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Stage</span> <span class="add-title"><?php echo _l('Add New Deal Stage'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('name', 'Stage Title'); ?> 
			<?php echo render_color_picker('color', _l('Stage Color')); ?> 
			<?php //echo render_input('statusorder', 'leads_stage_add_edit_order', count($stages) + 1, 'number'); ?>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control">
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                </select>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <!-- /.modal-content -->
    <?php echo form_close(); ?> </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#deal-stage-form'), {
        name: 'required'
    }, manage_deal_stages);
    $('#stage').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#stage input[name="name"]').val('');
        $('#stage input[name="color"]').val('');
        $('#stage input[name="statusorder"]').val('');
        $('#stage select[name="status"]').val('1');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
        $('#stage input[name="statusorder"]').val($('table tbody tr').length + 1);
        $('#stage select[name="status"]').val('1');
    });
});

// Create new deal stage
function new_stage() {
    $('#stage').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit stage function which init the data to the modal
function edit_stage(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#stage input[name="name"]').val($(invoker).data('name'));
    $('#stage .colorpicker-input').colorpicker('setValue', $(invoker).data('color'));
    $('#stage input[name="statusorder"]').val($(invoker).data('order'));
    $('#stage select[name="status"]').val($(invoker).data('status'));
    $('#stage').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for deal stages
function manage_deal_stages(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}

function toggleStageStatus(id, currentStatus) {
  $.post(admin_url + 'leads/toggle_deal_stage_status/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleStageStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html> 