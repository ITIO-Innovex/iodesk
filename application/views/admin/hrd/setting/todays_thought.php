<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  .jqte_tool.jqte_tool_1 .jqte_tool_label {
    height: 20px !important;
}
.jqte {
    margin: 20px 0 !important;
	}
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_thought(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l("New Today's Thought"); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($thoughts) && count($thoughts) > 0) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
              <thead>
                <th>Details</th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($thoughts as $t) { ?>
                <tr>
                  <td>
                    <?php echo nl2br($t['details']); ?>
                  </td>
                  <td>
                    <a href="javascript:void(0);" onclick="toggleThoughtStatus(<?php echo $t['id']; ?>, <?php echo (int)$t['status']; ?>)" id="status-label-<?php echo $t['id']; ?>">
                      <?php if (!empty($t['status'])) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_thought(this,<?php echo e($t['id']); ?>);return false;"
                         data-details="<?php echo e($t['details']); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_todays_thought/' . $t['id']); ?>"
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
              <p class="no-margin">No records found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="todays_thought" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/todaysthought'), ['id' => 'todays-thought-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit Today's Thought</span>
          <span class="add-title"><?php echo _l("Add New Today's Thought"); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <div class="form-group">
              <label for="details">Details</label>
              <textarea name="details" id="details" class="form-control editor" rows="5" required></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#todays-thought-form'), {
      details: 'required'
    }, manage_thought);
    $('#todays_thought').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#todays_thought textarea[name="details"]').val('');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  // Create new
  function new_thought() {
    $('#todays_thought').modal('show');
    $('.edit-title').addClass('hide');
  }

  // Edit
  function edit_thought(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#todays_thought textarea[name="details"]').jqteVal($(invoker).data('details'));
	$('#todays_thought textarea[name="details"]').val($(invoker).data('details'));
    $('#todays_thought').modal('show');
    $('.add-title').addClass('hide');
  }

  // Form handler
  function manage_thought(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function () {
      window.location.reload();
    });
    return false;
  }

  function toggleThoughtStatus(id, currentStatus) {
    $.post(admin_url + 'hrd/toggle_todays_thought/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
      if (response.success) {
        var label = $('#status-label-' + id + ' span');
        if (response.new_status == 1) {
          label.removeClass('label-danger').addClass('label-success').text('Active');
        } else {
          label.removeClass('label-success').addClass('label-danger').text('Deactive');
        }
        $('#status-label-' + id).attr('onclick', 'toggleThoughtStatus(' + id + ', ' + response.new_status + ')');
      } else {
        alert('Failed to update status');
      }
    }, 'json');
  }
</script>
<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>
	$('.editor').jqte();
	</script>
</body></html>
