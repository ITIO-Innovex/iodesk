<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($upcoming); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white">Appointments</h4>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appointmentModal">
            <i class="fa-regular fa-plus"></i> New Appointment
          </button>
        </div>
<div class="panel_s">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-upcoming" data-toggle="tab">Upcoming</a></li>
          <li><a href="#tab-past" data-toggle="tab">Past</a></li>
        </ul>

        <div class="tab-content mtop20 ">
          <div class="tab-pane active" id="tab-upcoming">
            <?php if (!empty($upcoming)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
					  <th>Appoinment ID</th>
                      <th>Date & Time</th>
                      <th>Consultations</th>
                      <th>Consultant</th>
                      <th>Customer</th>
					  <th>Notes</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($upcoming as $row) { ?>
                      <tr>
					    <td># 000<?php echo e($row['id'] ?? ''); ?></td>
                        <td><?php echo e($row['date_time'] ?? ''); ?></td>
                        <td><?php echo e($row['consultations'] ?? ''); ?> Min</td>
                        <td><?php echo e($row['consultant'] ?? ''); ?></td>
                        <td><i class="fa-solid fa-reply reply-email" data-email="<?php echo e($row['customer'] ?? ''); ?>"></i> <?php echo e($row['customer'] ?? ''); ?></td>
						<td><span title="<?php echo e($row['notes'] ?? ''); ?>"><?php echo substr($row['notes'],0,15) ?? ''; ?></span></td>
                        <td>
                          <select class="form-control input-sm appointment-status" data-id="<?php echo (int) $row['id']; ?>">
                            <option value="1" <?php echo (int) ($row['status'] ?? 1) === 1 ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo (int) ($row['status'] ?? 1) === 0 ? 'selected' : ''; ?>>Inactive</option>
                            <option value="2" <?php echo (int) ($row['status'] ?? 1) === 2 ? 'selected' : ''; ?>>Completed</option>
                          </select>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No upcoming appointments found.</div>
            <?php } ?>
          </div>

          <div class="tab-pane" id="tab-past">
            <?php if (!empty($past)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
					  <th>Appoinment ID</th>
                      <th>Date & Time</th>
                      <th>Consultations</th>
                      <th>Consultant</th>
                      <th>Customer</th>
					  <th>Notes</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($past as $row) { ?>
                      <tr>
					    <td># 000<?php echo e($row['id'] ?? ''); ?></td>
                        <td><?php echo e($row['date_time'] ?? ''); ?></td>
                        <td><?php echo e($row['consultations'] ?? ''); ?> Min</td>
                        <td><?php echo e($row['consultant'] ?? ''); ?></td>
                        <td><i class="fa-solid fa-reply reply-email" data-email="<?php echo e($row['customer'] ?? ''); ?>"></i> <?php echo e($row['customer'] ?? ''); ?></td>
						<td><span title="<?php echo e($row['notes'] ?? ''); ?>"><?php echo substr($row['notes'],0,15) ?? ''; ?></span></td>
                        <td>
                          <select class="form-control input-sm appointment-status" data-id="<?php echo (int) $row['id']; ?>">
                            <option value="1" <?php echo (int) ($row['status'] ?? 1) === 1 ? 'selected' : ''; ?>>Active</option>
                            <option value="0" <?php echo (int) ($row['status'] ?? 1) === 0 ? 'selected' : ''; ?>>Inactive</option>
                            <option value="2" <?php echo (int) ($row['status'] ?? 1) === 2 ? 'selected' : ''; ?>>Completed</option>
                          </select>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No past appointments found.</div>
            <?php } ?>
          </div>
        </div>
		</div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width:420px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">New Appointment</h4>
      </div>
      <div class="modal-body">
        <form id="appointment-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="form-group">
            <label>Consultations</label>
            <select name="consultations" class="form-control" required>
              <option value="15">15 Minut</option>
              <option value="30">30 Minut</option>
              <option value="45">45 Minut</option>
              <option value="60">60 Minut</option>
            </select>
          </div>
          <div class="form-group">
            <label>Date and Time</label>
            <input type="datetime-local" name="date_time" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Consultant</label>
            <input type="text" name="consultant" class="form-control" value="<?php echo e($_SESSION['webmail']['mailer_email'] ?? ''); ?>" readonly>
          </div>
          <div class="form-group">
            <label>Customer</label>
            <select name="customer" class="form-control" required>
              <option value="">Select customer</option>
              <?php foreach (($contacts ?? []) as $c) { ?>
                <option value="<?php echo e($c['email_id'] ?? ''); ?>"><?php echo e($c['email_id'] ?? ''); ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
          <div class="checkbox checkbox-primary">
            <input type="checkbox" id="appointment-notify" name="notification" value="1">
            <label for="appointment-notify">Send notifications for Customer</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-appointment">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="replyEmailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width:520px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Reply Email</h4>
      </div>
      <div class="modal-body">
        <form action="<?php echo admin_url('webmail/Reply'); ?>" method="post" enctype="multipart/form-data" id="reply-email-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="redirect" value="webmail/appoinment">
          <input type="hidden" name="recipientEmail" id="recipientEmailIT" value="">
          <input type="hidden" name="messagetype" value="Reply">
          <div class="form-group">
            <label>Subject</label>
            <input type="text" name="emailSubject" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Message</label>
            <textarea name="emailBody" id="replyEmailBody" class="form-control editor" required></textarea>
          </div>
          <div class="form-group">
            <label>Attach Files:</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
          </div>
          <button type="submit" name="send" class="btn btn-primary">Send Email</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
  $(function() {
    $('.editor').jqte();
    $('#save-appointment').on('click', function() {
      var $form = $('#appointment-form');
      $.post('<?php echo admin_url('webmail/add_appointment'); ?>', $form.serialize(), function(resp) {
        if (resp && resp.success) {
          $('#appointmentModal').modal('hide');
          alert_float('success', resp.message || 'Appointment saved');
          location.reload();
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to save appointment');
        }
      }, 'json');
    });

    $('.appointment-status').on('change', function() {
      var id = $(this).data('id');
      var status = $(this).val();
      $.post('<?php echo admin_url('webmail/update_appointment_status'); ?>/' + id, {
        status: status,
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      }, function(resp) {
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Status updated');
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to update status');
        }
      }, 'json');
    });

    $('.reply-email').on('click', function() {
      var email = $(this).data('email') || '';
      if (!email) { return; }
      $('#recipientEmailIT').val(email);
      $('#reply-email-form')[0].reset();
      $('#recipientEmailIT').val(email);
      $('#replyEmailModal').modal('show');
    });
  });
</script>
</body></html>
