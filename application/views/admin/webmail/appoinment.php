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
                      <th>Host</th>
                      <th>Participant</th>
					  <th>Notes</th>
                      <th>Status</th>
                      <th>Actions</th>
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
                        <td>
                          <button type="button" class="btn btn-xs btn-info edit-appointment"
                                  data-id="<?php echo (int) $row['id']; ?>"
                                  data-consultations="<?php echo (int) ($row['consultations'] ?? 15); ?>"
                                  data-date_time="<?php echo e($row['date_time'] ?? ''); ?>"
                                  data-consultant="<?php echo e($row['consultant'] ?? ''); ?>"
                                  data-customer="<?php echo e($row['customer'] ?? ''); ?>"
                                  data-notes="<?php echo e($row['notes'] ?? ''); ?>"
                                  data-notification="<?php echo (int) ($row['notification'] ?? 0); ?>">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-danger delete-appointment" data-id="<?php echo (int) $row['id']; ?>">
                            <i class="fa fa-trash"></i>
                          </button>
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
                      <th>Host</th>
                      <th>Participant</th>
					  <th>Notes</th>
                      <th>Status</th>
                      <th>Actions</th>
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
                        <td>
                          <button type="button" class="btn btn-xs btn-info edit-appointment"
                                  data-id="<?php echo (int) $row['id']; ?>"
                                  data-consultations="<?php echo (int) ($row['consultations'] ?? 15); ?>"
                                  data-date_time="<?php echo e($row['date_time'] ?? ''); ?>"
                                  data-consultant="<?php echo e($row['consultant'] ?? ''); ?>"
                                  data-customer="<?php echo e($row['customer'] ?? ''); ?>"
                                  data-notes="<?php echo e($row['notes'] ?? ''); ?>"
                                  data-notification="<?php echo (int) ($row['notification'] ?? 0); ?>">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button type="button" class="btn btn-xs btn-danger delete-appointment" data-id="<?php echo (int) $row['id']; ?>">
                            <i class="fa fa-trash"></i>
                          </button>
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
        <h4 class="modal-title" id="appointment-modal-title">New Appointment</h4>
      </div>
      <div class="modal-body">
        <form id="appointment-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="appointment_id" id="appointment-id" value="">
          <div class="form-group">
            <label>Consultations</label>
            <select name="consultations" id="appointment-consultations" class="form-control" required>
              <option value="15">15 Minut</option>
              <option value="30">30 Minut</option>
              <option value="45">45 Minut</option>
              <option value="60">60 Minut</option>
            </select>
          </div>
          <div class="form-group">
            <label>Date and Time</label>
            <input type="datetime-local" name="date_time" id="appointment-datetime" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Consultant</label>
            <input type="text" name="consultant" id="appointment-consultant" class="form-control" value="<?php echo e($_SESSION['webmail']['mailer_email'] ?? ''); ?>" readonly>
          </div>
          <div class="form-group">
            <label>Customer</label>
            <select name="customer" id="appointment-customer" class="form-control" required>
              <option value="">Select customer</option>
              <?php foreach (($contacts ?? []) as $c) { ?>
                <option value="<?php echo e($c['email_id'] ?? ''); ?>"><?php echo e($c['email_id'] ?? ''); ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" id="appointment-notes" class="form-control" rows="3"></textarea>
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
    
    // Helper to format datetime for datetime-local input
    function formatDateTimeLocal(dt) {
      if (!dt) return '';
      // Convert "2026-02-17 10:30:00" to "2026-02-17T10:30"
      return dt.replace(' ', 'T').substring(0, 16);
    }
    
    // Save appointment (add or update)
    $('#save-appointment').on('click', function() {
      var $form = $('#appointment-form');
      var appointmentId = $('#appointment-id').val();
      var url = appointmentId 
        ? '<?php echo admin_url('webmail/update_appointment'); ?>/' + appointmentId 
        : '<?php echo admin_url('webmail/add_appointment'); ?>';
      
      $.post(url, $form.serialize(), function(resp) {
        if (resp && resp.success) {
          $('#appointmentModal').modal('hide');
          alert_float('success', resp.message || 'Appointment saved');
          location.reload();
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to save appointment');
        }
      }, 'json');
    });

    // Edit appointment
    $('.edit-appointment').on('click', function() {
      var $btn = $(this);
      var id = $btn.data('id');
      var consultations = $btn.data('consultations');
      var dateTime = $btn.data('date_time');
      var consultant = $btn.data('consultant');
      var customer = $btn.data('customer');
      var notes = $btn.data('notes');
      var notification = $btn.data('notification');
      
      $('#appointment-id').val(id);
      $('#appointment-consultations').val(consultations);
      $('#appointment-datetime').val(formatDateTimeLocal(dateTime));
      $('#appointment-consultant').val(consultant);
      $('#appointment-customer').val(customer);
      $('#appointment-notes').val(notes);
      $('#appointment-notify').prop('checked', notification == 1);
      
      $('#appointment-modal-title').text('Edit Appointment');
      $('#appointmentModal').modal('show');
    });
    
    // Delete appointment
    $('.delete-appointment').on('click', function() {
      var id = $(this).data('id');
      if (!id) return;
      if (!confirm('Are you sure you want to delete this appointment?')) return;
      
      $.post('<?php echo admin_url('webmail/delete_appointment'); ?>/' + id, {
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      }, function(resp) {
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Appointment deleted');
          location.reload();
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to delete appointment');
        }
      }, 'json');
    });
    
    // Reset modal on close
    $('#appointmentModal').on('hidden.bs.modal', function() {
      $('#appointment-form')[0].reset();
      $('#appointment-id').val('');
      $('#appointment-modal-title').text('New Appointment');
      $('#appointment-consultant').val('<?php echo e($_SESSION['webmail']['mailer_email'] ?? ''); ?>');
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
