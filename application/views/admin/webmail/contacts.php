<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white">Contacts</h4>
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contactModal">
            <i class="fa-regular fa-plus"></i> New Contact
          </button>
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($contacts)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Company</th>
                      <th>Phone</th>
                      <th>Added On</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($contacts as $c) { ?>
                    <tr>
                        <td><?php echo e($c['first_name'] ?? ''); ?></td>
                        <td><?php echo e($c['last_name'] ?? ''); ?></td>
                        <td><?php echo e($c['email_id'] ?? ''); ?></td>
                        <td><?php echo e($c['company_name'] ?? ''); ?></td>
                        <td><?php echo e($c['phonenumber'] ?? ''); ?></td>
                        <td><?php echo e($c['addedon'] ?? ''); ?></td>
                        <td>
                          <button type="button" class="btn btn-xs btn-info edit-contact"
                                  data-id="<?php echo (int) $c['id']; ?>"
                                  data-first_name="<?php echo e($c['first_name'] ?? ''); ?>"
                                  data-last_name="<?php echo e($c['last_name'] ?? ''); ?>"
                                  data-email_id="<?php echo e($c['email_id'] ?? ''); ?>"
                                  data-company_name="<?php echo e($c['company_name'] ?? ''); ?>"
                                  data-phonenumber="<?php echo e($c['phonenumber'] ?? ''); ?>">
                            Edit
                          </button>
                          <button type="button" class="btn btn-xs btn-danger delete-contact"
                                  data-id="<?php echo (int) $c['id']; ?>">
                            Delete
                          </button>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No contacts found.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width:360px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">New Contact</h4>
      </div>
      <div class="modal-body">
        <form id="contact-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="contact_id" id="contact-id" value="">
          <div class="form-group">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_id" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" class="form-control">
          </div>
          <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="phonenumber" class="form-control">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-contact">Save</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  $(function() {
    $('#save-contact').on('click', function() {
      var $form = $('#contact-form');
      var id = $('#contact-id').val();
      var url = id ? ('<?php echo admin_url('webmail/update_contact'); ?>/' + id) : '<?php echo admin_url('webmail/add_contact'); ?>';
      $.post(url, $form.serialize(), function(resp) {
        if (resp && resp.success) {
          $('#contactModal').modal('hide');
          alert_float('success', resp.message || 'Contact saved');
          location.reload();
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to save contact');
        }
      }, 'json');
    });

    $('.edit-contact').on('click', function() {
      var $btn = $(this);
      $('#contact-id').val($btn.data('id'));
      $('#contact-form [name="first_name"]').val($btn.data('first_name'));
      $('#contact-form [name="last_name"]').val($btn.data('last_name'));
      $('#contact-form [name="email_id"]').val($btn.data('email_id'));
      $('#contact-form [name="company_name"]').val($btn.data('company_name'));
      $('#contact-form [name="phonenumber"]').val($btn.data('phonenumber'));
      $('#contactModal .modal-title').text('Edit Contact');
      $('#contactModal').modal('show');
    });

    $('.delete-contact').on('click', function() {
      var id = $(this).data('id');
      if (!id) { return; }
      if (!confirm('Delete this contact?')) { return; }
      $.post('<?php echo admin_url('webmail/delete_contact'); ?>/' + id, {
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      }, function(resp) {
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Contact deleted');
          location.reload();
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to delete contact');
        }
      }, 'json');
    });

    $('#contactModal').on('hidden.bs.modal', function() {
      $('#contact-form')[0].reset();
      $('#contact-id').val('');
      $('#contactModal .modal-title').text('New Contact');
    });
  });
</script>
</body></html>
