<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_shift_manager(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Shift'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (isset($shift_managers) && count($shift_managers) > 0) { ?>
            <?php
              // Build shift type map for quick lookup id => title
              $shiftTypeMap = [];
              if (!empty($shift_types)) {
                foreach ($shift_types as $t) {
                  $shiftTypeMap[$t['id']] = $t['title'];
                }
              }
              // Build saturday rule map id => title
              $saturdayRuleMap = [];
              if (!empty($saturday_rules)) {
                foreach ($saturday_rules as $sr) {
                  $saturdayRuleMap[$sr['id']] = $sr['title'];
                }
              }
            ?>
            <table class="table dt-table" data-order-col="0" data-order-type="asc">
              <thead>
                <th>Shift Code</th>
                <th>Shift Name</th>
                <th>Shift In</th>
                <th>Shift Out</th>
                <th>First Half Start</th>
                <th>First Half End</th>
                <th>Second Half Start</th>
                <th>Second Half End</th>
                <th>Saturday Rule</th>
                <th>Sat Work Start</th>
                <th>Sat Work End</th>
				<th>Grace (min)</th>
                <th>Shift Type</th>
                <th>Tea (min)</th>
                <th>Lunch (min)</th>
                <th>Dinner (min)</th>
                <th><?php echo _l('status'); ?></th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($shift_managers as $row) { ?>
                <tr>
                  <td>
                    <a href="#"
                       onclick="edit_shift_manager(this,<?php echo (int)$row['shift_id']; ?>);return false;"
                       data-shift_code="<?php echo e($row['shift_code']); ?>"
                       data-shift_name="<?php echo e($row['shift_name']); ?>"
                       data-shift_in="<?php echo e($row['shift_in']); ?>"
                       data-shift_out="<?php echo e($row['shift_out']); ?>"
                       data-grace_period="<?php echo e($row['grace_period']); ?>"
                       data-shift_type="<?php echo e($row['shift_type']); ?>"
                       data-tea_break_in_minut="<?php echo e($row['tea_break_in_minut']); ?>"
                       data-lunch_break_in_minut="<?php echo e($row['lunch_break_in_minut']); ?>"
                       data-dinner_break_in_minut="<?php echo e($row['dinner_break_in_minut']); ?>"
                       data-first_half_start="<?php echo e($row['first_half_start'] ?? ''); ?>"
                       data-first_half_end="<?php echo e($row['first_half_end'] ?? ''); ?>"
                       data-second_half_start="<?php echo e($row['second_half_start'] ?? ''); ?>"
                       data-second_half_end="<?php echo e($row['second_half_end'] ?? ''); ?>"
                       data-saturday_rule="<?php echo e($row['saturday_rule'] ?? ''); ?>"
                       data-saturday_work_start="<?php echo e($row['saturday_work_start'] ?? ''); ?>"
                       data-saturday_work_end="<?php echo e($row['saturday_work_end'] ?? ''); ?>"
                    ><?php echo e($row['shift_code']); ?></a>
                  </td>
                  <td><?php echo e($row['shift_name']); ?></td>
                  <td><?php echo e($row['shift_in']); ?></td>
                  <td><?php echo e($row['shift_out']); ?></td>
                  
                  <td><?php echo e($row['first_half_start'] ?? ''); ?></td>
                  <td><?php echo e($row['first_half_end'] ?? ''); ?></td>
                  <td><?php echo e($row['second_half_start'] ?? ''); ?></td>
                  <td><?php echo e($row['second_half_end'] ?? ''); ?></td>
                  <td><?php echo isset($saturdayRuleMap[$row['saturday_rule'] ?? '']) ? e($saturdayRuleMap[$row['saturday_rule']]) : '-'; ?></td>
                  <td><?php echo e($row['saturday_work_start'] ?? ''); ?></td>
                  <td><?php echo e($row['saturday_work_end'] ?? ''); ?></td>
				  <td><?php echo (int)$row['grace_period']; ?></td>
                  <td><?php echo isset($shiftTypeMap[$row['shift_type']]) ? e($shiftTypeMap[$row['shift_type']]) : '-'; ?></td>
                  <td><?php echo (int)$row['tea_break_in_minut']; ?></td>
                  <td><?php echo (int)$row['lunch_break_in_minut']; ?></td>
                  <td><?php echo (int)$row['dinner_break_in_minut']; ?></td>
                  <td>
                    <a href="javascript:void(0);" onclick="toggleShift(<?php echo $row['shift_id']; ?>, <?php echo (int)$row['status']; ?>)" id="status-label-<?php echo $row['shift_id']; ?>">
                      <?php if (!empty($row['status'])) { ?>
                        <span class="label label-success">Active</span>
                      <?php } else { ?>
                        <span class="label label-danger">Deactive</span>
                      <?php } ?>
                    </a>
                  </td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#"
                         onclick="edit_shift_manager(this,<?php echo (int)$row['shift_id']; ?>);return false;"
                         data-shift_code="<?php echo e($row['shift_code']); ?>"
                         data-shift_name="<?php echo e($row['shift_name']); ?>"
                         data-shift_in="<?php echo e($row['shift_in']); ?>"
                         data-shift_out="<?php echo e($row['shift_out']); ?>"
                         data-grace_period="<?php echo e($row['grace_period']); ?>"
                         data-shift_type="<?php echo e($row['shift_type']); ?>"
                         data-tea_break_in_minut="<?php echo e($row['tea_break_in_minut']); ?>"
                         data-lunch_break_in_minut="<?php echo e($row['lunch_break_in_minut']); ?>"
                         data-dinner_break_in_minut="<?php echo e($row['dinner_break_in_minut']); ?>"
						  data-first_half_start="<?php echo e($row['first_half_start'] ?? ''); ?>"
                       data-first_half_end="<?php echo e($row['first_half_end'] ?? ''); ?>"
                       data-second_half_start="<?php echo e($row['second_half_start'] ?? ''); ?>"
                       data-second_half_end="<?php echo e($row['second_half_end'] ?? ''); ?>"
                       data-saturday_rule="<?php echo e($row['saturday_rule'] ?? ''); ?>"
                       data-saturday_work_start="<?php echo e($row['saturday_work_start'] ?? ''); ?>"
                       data-saturday_work_end="<?php echo e($row['saturday_work_end'] ?? ''); ?>"
                         class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                        <i class="fa-regular fa-pen-to-square fa-lg"></i>
                      </a>
                      <a href="<?php echo admin_url('hrd/delete_shift_manager/' . $row['shift_id']); ?>"
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

<div class="modal fade" id="shift_manager" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl">
    <?php echo form_open(admin_url('hrd/shiftmanager'), ['id' => 'shift-manager-form']); ?>
    <div class="modal-content ">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          <span class="edit-title">Edit Shift</span>
          <span class="add-title"><?php echo _l('Add New Shift'); ?></span>
        </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          
            <div id="additional"></div>
			<div class="col-md-4">
            <?php echo render_input('shift_code', 'Shift Code'); ?>
			</div>
			<div class="col-md-4">
            <?php echo render_input('shift_name', 'Shift Name'); ?>
			</div><div class="col-md-4">
            <div class="form-group">
              <label for="shift_in">Shift In</label>
              <input type="time" name="shift_in" id="shift_in" class="form-control" />
            </div>
			</div><div class="col-md-4">
            <div class="form-group">
              <label for="shift_out">Shift Out</label>
              <input type="time" name="shift_out" id="shift_out" class="form-control" />
            </div>
			</div><div class="col-md-4">
            <?php echo render_input('grace_period', 'Grace Period (minutes)', '', 'number'); ?>
			</div><div class="col-md-4">
            <div class="form-group">
              <label for="shift_type">Shift Type</label>
              <select name="shift_type" id="shift_type" class="form-control">
                <option value="">-- Select Shift Type --</option>
                <?php if (!empty($shift_types)) { foreach ($shift_types as $t) { ?>
                  <option value="<?php echo (int)$t['id']; ?>"><?php echo e($t['title']); ?></option>
                <?php } } ?>
              </select>
            </div>
			</div><div class="col-md-4">
            <?php echo render_input('tea_break_in_minut', 'Tea Break (minutes)', '', 'number'); ?>
			</div><div class="col-md-4">
            <?php echo render_input('lunch_break_in_minut', 'Lunch Break (minutes)', '', 'number'); ?>
			</div><div class="col-md-4">
            <?php echo render_input('dinner_break_in_minut', 'Dinner Break (minutes)', '', 'number'); ?>
			</div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="first_half_start">First Half Start</label>
                <input type="time" name="first_half_start" id="first_half_start" class="form-control" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="first_half_end">First Half End</label>
                <input type="time" name="first_half_end" id="first_half_end" class="form-control" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="second_half_start">Second Half Start</label>
                <input type="time" name="second_half_start" id="second_half_start" class="form-control" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="second_half_end">Second Half End</label>
                <input type="time" name="second_half_end" id="second_half_end" class="form-control" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="saturday_rule">Saturday Rule</label>
                <select name="saturday_rule" id="saturday_rule" class="form-control">
                  <option value="">-- Select Saturday Rule --</option>
                  <?php if (!empty($saturday_rules)) { foreach ($saturday_rules as $sr) { ?>
                    <option value="<?php echo (int)$sr['id']; ?>"><?php echo e($sr['title']); ?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="saturday_work_start">Saturday Work Start</label>
                <input type="time" name="saturday_work_start" id="saturday_work_start" class="form-control" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="saturday_work_end">Saturday Work End</label>
                <input type="time" name="saturday_work_end" id="saturday_work_end" class="form-control" />
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
    appValidateForm($("body").find('#shift-manager-form'), {
      shift_code: 'required',
      shift_name: 'required',
      shift_in: 'required',
      shift_out: 'required',
      shift_type: 'required'
    }, manage_shift_manager);

    $('#shift_manager').on("hidden.bs.modal", function () {
      $('#additional').html('');
      $('#shift_manager input').val('');
      $('#shift_manager select').val('');
      $('.add-title').removeClass('hide');
      $('.edit-title').removeClass('hide');
    });
  });

  function new_shift_manager() {
    $('#shift_manager').modal('show');
    $('.edit-title').addClass('hide');
  }

  function edit_shift_manager(invoker, id) {
    $('#additional').append(hidden_input('shift_id', id));
    $('#shift_manager input[name="shift_code"]').val($(invoker).data('shift_code'));
    $('#shift_manager input[name="shift_name"]').val($(invoker).data('shift_name'));
    $('#shift_manager input[name="shift_in"]').val($(invoker).data('shift_in'));
    $('#shift_manager input[name="shift_out"]').val($(invoker).data('shift_out'));
    $('#shift_manager input[name="grace_period"]').val($(invoker).data('grace_period'));
    $('#shift_manager select[name="shift_type"]').val($(invoker).data('shift_type'));
    $('#shift_manager input[name="tea_break_in_minut"]').val($(invoker).data('tea_break_in_minut'));
    $('#shift_manager input[name="lunch_break_in_minut"]').val($(invoker).data('lunch_break_in_minut'));
    $('#shift_manager input[name="dinner_break_in_minut"]').val($(invoker).data('dinner_break_in_minut'));
    $('#shift_manager input[name="first_half_start"]').val($(invoker).data('first_half_start'));
    $('#shift_manager input[name="first_half_end"]').val($(invoker).data('first_half_end'));
    $('#shift_manager input[name="second_half_start"]').val($(invoker).data('second_half_start'));
    $('#shift_manager input[name="second_half_end"]').val($(invoker).data('second_half_end'));
    $('#shift_manager select[name="saturday_rule"]').val($(invoker).data('saturday_rule'));
    $('#shift_manager input[name="saturday_work_start"]').val($(invoker).data('saturday_work_start'));
    $('#shift_manager input[name="saturday_work_end"]').val($(invoker).data('saturday_work_end'));
    $('#shift_manager').modal('show');
    $('.add-title').addClass('hide');
  }

  function manage_shift_manager(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function () {
      window.location.reload();
    });
    return false;
  }

  function toggleShift(id, currentStatus) {
    $.post(admin_url + 'hrd/toggle_shift_manager/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
      if (response.success) {
        var label = $('#status-label-' + id + ' span');
        if (response.new_status == 1) {
          label.removeClass('label-danger').addClass('label-success').text('Active');
        } else {
          label.removeClass('label-success').addClass('label-danger').text('Deactive');
        }
        $('#status-label-' + id).attr('onclick', 'toggleShift(' + id + ', ' + response.new_status + ')');
      } else {
        alert('Failed to update status');
      }
    }, 'json');
  }
</script>
<?php init_tail(); ?>
</body></html>
