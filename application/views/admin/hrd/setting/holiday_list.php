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
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_holiday(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Holiday'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($holiday_lists) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Holiday Title</th>
                                <th>Holiday Remark</th>
                                <th>Holiday Date</th>
                                <th><?php echo _l('status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($holiday_lists as $holiday) { ?>
                                <tr>
                                    <td>
                                        <a href="#"
                                            onclick="edit_holiday(this,<?php echo e($holiday['id']); ?>);return false;"
                                            data-holiday-title="<?php echo e($holiday['holiday_title']); ?>"
                                            data-holiday-remark="<?php echo e($holiday['holiday_remark']); ?>"
                                            data-holiday-date="<?php echo e($holiday['holiday_date']); ?>"
                                        ><?php echo e($holiday['holiday_title']); ?></a><br />
                                    </td>
                                    <td><?php echo e($holiday['holiday_remark']); ?></td>
                                    <td><?php echo e($holiday['holiday_date']); ?></td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="toggleHolidayStatus(<?php echo $holiday['id']; ?>, <?php echo $holiday['status']; ?>)" id="status-label-<?php echo $holiday['id']; ?>">
                                        <?php if ($holiday['status']) { ?>
                                            <span class="label label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-danger">Deactive</span>
                                        <?php } ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_holiday(this,<?php echo e($holiday['id']); ?>);return false;"
                                                data-holiday-title="<?php echo e($holiday['holiday_title']); ?>"
                                                data-holiday-remark="<?php echo e($holiday['holiday_remark']); ?>"
                                                data-holiday-date="<?php echo e($holiday['holiday_date']); ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <a href="<?php echo admin_url('hrd/delete_holiday_list/' . $holiday['id']); ?>"
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
            <p class="no-margin">No holidays found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="holiday" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('hrd/holidaylist'), ['id' => 'holiday-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Holiday</span> <span class="add-title"><?php echo _l('Add New Holiday'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('holiday_title', 'Holiday Title'); ?> 
            <div class="form-group">
                <label for="holiday_remark">Holiday Remark</label>
                <textarea name="holiday_remark" id="holiday_remark" class="form-control" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label for="holiday_date">Holiday Date</label>
                <input type="date" name="holiday_date" id="holiday_date" class="form-control" placeholder="YYYY-MM-DD" />
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
    appValidateForm($("body").find('#holiday-form'), {
        holiday_title: 'required',
        holiday_date: 'required'
    }, manage_holiday);
    $('#holiday').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#holiday input[name="holiday_title"]').val('');
        $('#holiday textarea[name="holiday_remark"]').val('');
        $('#holiday input[name="holiday_date"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

// Create new holiday
function new_holiday() {
    $('#holiday').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit holiday function which init the data to the modal
function edit_holiday(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#holiday input[name="holiday_title"]').val($(invoker).data('holiday-title'));
    $('#holiday textarea[name="holiday_remark"]').val($(invoker).data('holiday-remark'));
    $('#holiday input[name="holiday_date"]').val($(invoker).data('holiday-date'));
    $('#holiday').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for holiday
function manage_holiday(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}

function toggleHolidayStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_holiday_list/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleHolidayStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>

