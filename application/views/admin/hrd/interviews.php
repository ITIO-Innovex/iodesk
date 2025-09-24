<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_interview(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Interview'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
		  <div class="row">
		  <div class="col-sm-11">
            <form method="get" action="" class="mbot15 togglesearch" style="border: 1px solid rgb(204, 204, 204);
    padding: 25px 10px 10px;background: lightsteelblue; display:none;">
              <div class="row">
                <div class="col-md-2"><div class="form-group"><label>Full Name</label><input type="text" name="full_name" class="form-control" value="<?php echo e($filters['full_name'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Phone</label><input type="text" name="phone_number" class="form-control" value="<?php echo e($filters['phone_number'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Email</label><input type="text" name="email_id" class="form-control" value="<?php echo e($filters['email_id'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Qualification</label><input type="text" name="qualification" class="form-control" value="<?php echo e($filters['qualification'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Designation</label><input type="text" name="designation" class="form-control" value="<?php echo e($filters['designation'] ?? ''); ?>" /></div></div>
             
                <div class="col-md-2"><div class="form-group"><label>Total Experience</label><input type="text" name="total_experience" class="form-control" value="<?php echo e($filters['total_experience'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Current Salary</label><input type="text" name="current_salary" class="form-control" value="<?php echo e($filters['current_salary'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Notice From (days)</label><input type="number" name="notice_from" class="form-control" value="<?php echo e($filters['notice_from'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Notice To (days)</label><input type="number" name="notice_to" class="form-control" value="<?php echo e($filters['notice_to'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Location</label><input type="text" name="location" class="form-control" value="<?php echo e($filters['location'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="<?php echo e($filters['city'] ?? ''); ?>" /></div></div>
             
                <div class="col-md-2"><div class="form-group"><label>Status</label>
                  <?php $st = (string)($filters['status'] ?? ''); ?>
                  <select name="status" class="form-control">
                    <option value="" <?php echo ($st==='')?'selected="selected"':''; ?>>-- All --</option>
                    <option value="0" <?php echo ($st==='0')?'selected="selected"':''; ?>>Inactive</option>
                    <option value="1" <?php echo ($st==='1')?'selected="selected"':''; ?>>Active</option>
                  </select>
                </div></div>
                <div class="col-md-2"><div class="form-group"><label>Added From</label><input type="date" name="added_from" class="form-control" value="<?php echo e($filters['added_from'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Added To</label><input type="date" name="added_to" class="form-control" value="<?php echo e($filters['added_to'] ?? ''); ?>" /></div></div>
                <div class="col-md-2">
                  <label>&nbsp;</label><div class="form-group">
                    <button type="submit" class="btn btn-default"><i class="fa-solid fa-magnifying-glass" title="Search"></i></button>
					<a href="<?php echo admin_url('hrd/leave_manager'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-rotate"></i></a>
                  </div>
                </div>
              </div>
            </form>
            </div>
			<div class="col-sm-1 tw-text-right"><i class="fa-solid fa-filter tw-py-2" style="color: lightsteelblue;" id="toggleBtn" title="Search"></i></div>
		  </div>
            <?php if (!empty($interviews)) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
              <thead>
                <th>#</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Qualification</th>
                <th>Designation</th>
                <th>Experience</th>
                <th>Current Salary</th>
                <th>Notice (days)</th>
                <th>Location</th>
                <th>City</th>
                <th>Status</th>
                <th>Added On</th>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($interviews as $it) { $st=(int)($it['status']??1); ?>
                <tr>
                  <td><?php echo (int)$it['id']; ?></td>
                  <td><?php echo e($it['full_name']); ?></td>
                  <td><?php echo e($it['phone_number']); ?></td>
                  <td><?php echo e($it['email_id']); ?></td>
                  <td><?php echo e($it['qualification']); ?></td>
                  <td><?php echo e($it['designation']); ?></td>
                  <td><?php echo e($it['total_experience']); ?></td>
                  <td><?php echo e($it['current_salary']); ?></td>
                  <td><?php echo (int)$it['notice_period_in_days']; ?></td>
                  <td><?php echo e($it['location']); ?></td>
                  <td><?php echo e($it['city']); ?></td>
                  <td><?php echo $st===1?'<span class="label label-success">Active</span>':'<span class="label label-danger">Inactive</span>'; ?></td>
                  <td><?php echo e($it['addedon']); ?></td>
                  <td>
                    <div class="tw-flex tw-items-center tw-space-x-3">
                      <a href="#" onclick="view_interview(this);return false;" data-all='<?php echo json_encode($it, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-eye fa-lg"></i></a>
                      <a href="#" onclick="edit_interview(this);return false;" data-all='<?php echo json_encode($it, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?><p class="no-margin">No records found.</p><?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="interviews_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/interviewentry'), ['id' => 'interviews-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="edit-title">Edit Interview</span><span class="add-title"><?php echo _l('Add Interview'); ?></span></h4>
      </div>
      <div class="modal-body">
        <div id="additional"></div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div></div>
          <div class="col-md-4"><div class="form-group"><label>Phone</label><input type="text" name="phone_number" class="form-control" required></div></div>
          <div class="col-md-4"><div class="form-group"><label>Email</label><input type="email" name="email_id" class="form-control"></div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Qualification</label><input type="text" name="qualification" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Designation</label><input type="text" name="designation" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Total Experience</label><input type="text" name="total_experience" class="form-control"></div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Current Salary</label><input type="text" name="current_salary" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Notice Period (days)</label><input type="number" name="notice_period_in_days" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Location</label><input type="text" name="location" class="form-control"></div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>City</label><input type="text" name="city" class="form-control"></div></div>
          <div class="col-md-8"><div class="form-group"><label>Comments</label><input type="text" name="comments" class="form-control"></div></div>
        </div>
        <div class="form-group"><label>Status</label>
          <select name="status" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
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

<div class="modal fade" id="interviews_details" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Interview Details</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4"><strong>Name:</strong> <span id="d-name"></span></div>
          <div class="col-md-4"><strong>Phone:</strong> <span id="d-phone"></span></div>
          <div class="col-md-4"><strong>Email:</strong> <span id="d-email"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-4"><strong>Qualification:</strong> <span id="d-qual"></span></div>
          <div class="col-md-4"><strong>Designation:</strong> <span id="d-desig"></span></div>
          <div class="col-md-4"><strong>Experience:</strong> <span id="d-exp"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-4"><strong>Current Salary:</strong> <span id="d-salary"></span></div>
          <div class="col-md-4"><strong>Notice (days):</strong> <span id="d-notice"></span></div>
          <div class="col-md-4"><strong>Status:</strong> <span id="d-status" class="label"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-6"><strong>Location:</strong> <span id="d-location"></span></div>
          <div class="col-md-6"><strong>City:</strong> <span id="d-city"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-12"><strong>Comments:</strong> <span id="d-comments"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#interviews-form'), {full_name:'required',phone_number:'required'}, manage_interview);
    $('#interviews_modal').on("hidden.bs.modal", function () { $('#additional').html(''); $('#interviews_modal input, #interviews_modal textarea').val(''); $('#interviews_modal select').val(''); $('.add-title').removeClass('hide'); $('.edit-title').removeClass('hide'); });
  });
  function new_interview(){ $('#interviews_modal').modal('show'); $('.edit-title').addClass('hide'); }
  function edit_interview(invoker){ var it=$(invoker).data('all'); $('#additional').append(hidden_input('id', it.id)); $('#interviews_modal input[name=full_name]').val(it.full_name); $('#interviews_modal input[name=phone_number]').val(it.phone_number); $('#interviews_modal input[name=email_id]').val(it.email_id); $('#interviews_modal input[name=qualification]').val(it.qualification); $('#interviews_modal input[name=designation]').val(it.designation); $('#interviews_modal input[name=total_experience]').val(it.total_experience); $('#interviews_modal input[name=current_salary]').val(it.current_salary); $('#interviews_modal input[name=notice_period_in_days]').val(it.notice_period_in_days); $('#interviews_modal input[name=location]').val(it.location); $('#interviews_modal input[name=city]').val(it.city); $('#interviews_modal input[name=comments]').val(it.comments); $('#interviews_modal select[name=status]').val(it.status||1); $('#interviews_modal').modal('show'); $('.add-title').addClass('hide'); }
  function view_interview(invoker){ var it=$(invoker).data('all'); $('#d-name').text(it.full_name); $('#d-phone').text(it.phone_number); $('#d-email').text(it.email_id||''); $('#d-qual').text(it.qualification||''); $('#d-desig').text(it.designation||''); $('#d-exp').text(it.total_experience||''); $('#d-salary').text(it.current_salary||''); $('#d-notice').text(it.notice_period_in_days||''); var st=parseInt(it.status||1,10); $('#d-status').removeClass('label-success label-danger').addClass(st===1?'label-success':'label-danger').text(st===1?'Active':'Inactive'); $('#d-location').text(it.location||''); $('#d-city').text(it.city||''); $('#d-comments').text(it.comments||''); $('#interviews_details').modal('show'); }
  function manage_interview(form){ var data=$(form).serialize(); $.post(form.action, data).done(function(){ window.location.reload(); }); return false; }
</script>
<?php init_tail(); ?>
<script>
$(document).ready(function(){
    $('#toggleBtn').click(function(){
        $('.togglesearch').slideToggle(); // smoothly show/hide form
    });
});
</script>
</body></html>
