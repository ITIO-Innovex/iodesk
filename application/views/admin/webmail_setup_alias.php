<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Webmail Alias</h4>
          <a href="<?php echo admin_url('webmail_setup'); ?>" class="btn btn-default">
            <i class="fa-solid fa-arrow-left tw-mr-1"></i> Back
          </a>
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($aliases)) { ?>
              <table class="table dt-table" data-order-col="0" data-order-type="desc">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Webmail</th>
                    <th>Sender Email</th>
                    <th>Sender Name</th>
                    <th>Verified</th>
                    <th><?php echo _l('options'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($aliases as $a) { ?>
                    <tr>
                      <td><?php echo (int) ($a['id'] ?? 0); ?></td>
                      <td>
                        <?php echo e(($a['webmail_name'] ?? '') . ' - ' . ($a['webmail_email'] ?? '')); ?>
                        <div class="text-muted tw-text-xs">Webmail ID: <?php echo (int) ($a['webmail_id'] ?? 0); ?></div>
                      </td>
                      <td><?php echo e($a['senderEmail'] ?? ''); ?></td>
                      <td><?php echo e($a['senderName'] ?? ''); ?></td>
                      <td>
                        <?php if ((int) ($a['verified'] ?? 1) === 1) { ?>
                          <span class="label label-success">Yes</span>
                        <?php } else { ?>
                          <span class="label label-default">No</span>
                        <?php } ?>
                      </td>
                      <td>
                        <div class="tw-flex tw-items-center tw-space-x-3">
                          <a href="javascript:void(0);"
                             class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 edit-alias"
                             data-id="<?php echo (int) ($a['id'] ?? 0); ?>">
                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                          </a>
                          <a href="<?php echo admin_url('webmail_setup/delete_alias/' . (int) ($a['id'] ?? 0)); ?>"
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
              <p class="text-muted">No aliases found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Edit Alias Modal -->
<div class="modal fade" id="editAliasModal" tabindex="-1" role="dialog" aria-labelledby="editAliasModalLabel">
  <div class="modal-dialog" role="document" style="max-width:650px;">
    <div class="modal-content">
      <?php echo form_open('', ['id' => 'edit-alias-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="editAliasModalLabel">Edit Alias</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit_alias_id" value="0">

        <div class="form-group">
          <label>Sender Email <span class="text-danger">*</span></label>
          <input type="email" id="edit_senderEmail" class="form-control" required maxlength="100">
        </div>

        <div class="form-group">
          <label>Sender Name</label>
          <input type="text" id="edit_senderName" class="form-control" maxlength="50">
        </div>

        <div class="form-group">
          <label>Verified</label>
          <select id="edit_verified" class="form-control">
            <option value="1">Verified</option>
            <option value="0">Not Verified</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary" id="editAliasSubmitBtn"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
  var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
  var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

  $('body').on('click', '.edit-alias', function() {
    var id = parseInt($(this).data('id'), 10) || 0;
    if (!id) return;

    $.get(admin_url + 'webmail_setup/alias_entry/' + id, function(resp) {
      var row = resp;
      try { row = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
      if (!row || !row.id) {
        alert_float('danger', 'Alias not found');
        return;
      }
      $('#edit_alias_id').val(row.id);
      $('#edit_senderEmail').val(row.senderEmail || '');
      $('#edit_senderName').val(row.senderName || '');
      $('#edit_verified').val(String(row.verified === 0 ? 0 : 1));
      $('#editAliasModal').appendTo('body').modal('show');
      setTimeout(function(){ $('#edit_senderEmail').focus(); }, 150);
    });
  });

  $('#edit-alias-form').on('submit', function(e) {
    e.preventDefault();
    var id = parseInt($('#edit_alias_id').val(), 10) || 0;
    if (!id) return;
    var payload = {
      senderEmail: $.trim($('#edit_senderEmail').val()),
      senderName: $.trim($('#edit_senderName').val()),
      verified: $('#edit_verified').val()
    };
    payload[csrfName] = csrfHash;

    var $btn = $('#editAliasSubmitBtn');
    $btn.prop('disabled', true);
    $.post(admin_url + 'webmail_setup/update_alias/' + id, payload)
      .done(function(resp) {
        try { resp = typeof resp === 'string' ? JSON.parse(resp) : resp; } catch(e) {}
        if (resp && resp.success) {
          alert_float('success', resp.message || 'Updated');
          location.reload();
        } else {
          alert_float('danger', (resp && resp.message) ? resp.message : 'Failed to update alias');
        }
      })
      .fail(function() {
        alert_float('danger', 'Failed to update alias');
      })
      .always(function() {
        $btn.prop('disabled', false);
      });
  });
});
</script>
</body>
</html>

