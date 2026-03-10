<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class=" tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white">Drafts 
		  <a href="<?php echo admin_url('webmail/compose');?>" class="btn btn-primary pull-right " ><i class="fa-regular fa-paper-plane tw-mr-1"></i> New Email</a>
		  <a href="<?php echo admin_url('webmail/inbox');?>" class="btn btn-primary pull-right tw-mx-2" ><i class="fa-regular fa-envelope menu-icon"></i> Webmail</a>
		  </h4>
          
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($contacts)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  
                  <tbody>
                    <?php foreach ($contacts as $c) { 
					$subject="no subject)";
					if(isset($c['subject'])&&$c['subject']){ $subject = $c['subject'];}
					?>
                    <tr>
                        <td><a href="<?php echo admin_url('webmail/compose');?>/<?php echo (int) $c['id']; ?>">Draft</a></td>
						<td><a href="<?php echo admin_url('webmail/compose');?>/<?php echo (int) $c['id']; ?>"><?php echo $c['status'] ?? ''; ?></a></td>
                        <td><?php echo $c['subject'] ?? '(no subject)'; ?></td>
                        <td><?php echo $c['body'] ?? ''; ?></td>
						<td><?php echo $c['created_at'] ?? ''; ?></td>
                        <td>
<button type="button" class="btn btn-xs btn-danger delete-draft" data-id="<?php echo (int) $c['id']; ?>">Delete</button>
                          
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">You don't have any saved drafts.<br />

Saving a draft allows you to keep a message you aren't ready to send yet.</div>
            <?php } ?>
          </div>
        </div>
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

    $('.delete-draft').on('click', function() {
      var id = $(this).data('id');
      if (!id) { return; }
      if (!confirm('Delete this draft?')) { return; }
      $.post('<?php echo admin_url('webmail/delete_draft'); ?>/' + id, {
        <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
      }, function(resp) {
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Draft deleted');
          location.reload();
        } else {
          alert_float('warning', resp && resp.message ? resp.message : 'Failed to delete Draft');
        }
      }, 'json');
    });

    $('#contactModal').on('hidden.bs.modal', function() {
      $('#contact-form')[0].reset();
      $('#contact-id').val('');
      $('#contactModal .modal-title').text('New Contact');
    });

    $('.reply-email').on('click', function() {
      var email = $(this).data('email') || '';
      if (!email) { return; }
      $('#reply-email-form')[0].reset();
      $('#recipientEmailIT').val(email);
      $('#replyEmailModal').modal('show');
    });
  });
</script>
</body></html>
