<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
            <i class="fa-solid fa-bell tw-mr-2"></i> Support Maintenance Notice
          </h4>
          <button type="button" class="btn btn-primary" id="add-notice-btn">
            <i class="fa-regular fa-plus"></i> New Notice
          </button>
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <?php if (!empty($notices)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered dt-table" data-order-col="0" data-order-type="desc">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Title</th>
                      <th>Type</th>
                      <th>Start Date</th>
                      <th>End Date</th>
                      <th>Preview</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($notices as $n) { ?>
                    <tr>
                      <td><?php echo (int) $n['id']; ?></td>
                      <td><?php echo e($n['title']); ?></td>
                      <td>
                        <span class="label label-default"><?php echo e($n['notice_type']); ?></span>
                      </td>
                      <td><?php echo $n['start_datetime'] ? date('d M Y H:i', strtotime($n['start_datetime'])) : '-'; ?></td>
                      <td><?php echo $n['end_datetime'] ? date('d M Y H:i', strtotime($n['end_datetime'])) : '-'; ?></td>
                      <td>
                        <div style="padding:5px 10px; border-radius:3px; background-color:<?php echo e($n['background_color']); ?>; color:<?php echo e($n['text_color']); ?>; font-size:11px; text-align:center;">
                          Preview
                        </div>
                      </td>
                      <td>
                        <a href="javascript:void(0);" class="toggle-status" data-id="<?php echo (int) $n['id']; ?>">
                          <?php if ($n['is_active']) { ?>
                            <span class="label label-success">Active</span>
                          <?php } else { ?>
                            <span class="label label-danger">Inactive</span>
                          <?php } ?>
                        </a>
                      </td>
                      <td >
                        <button type="button" class="btn btn-xs btn-info edit-notice"
                                data-id="<?php echo (int) $n['id']; ?>"
                                data-title="<?php echo e($n['title']); ?>"
                                data-message="<?php echo e($n['message']); ?>"
                                data-notice_type="<?php echo e($n['notice_type']); ?>"
                                data-display_position="<?php echo e($n['display_position']); ?>"
                                data-start_datetime="<?php echo e($n['start_datetime']); ?>"
                                data-end_datetime="<?php echo e($n['end_datetime']); ?>"
                                data-background_color="<?php echo e($n['background_color']); ?>"
                                data-text_color="<?php echo e($n['text_color']); ?>"
                                data-is_active="<?php echo (int) $n['is_active']; ?>">
                          <i class="fa fa-edit" title="Edit"></i>
                        </button>
                        <button type="button" class="btn btn-xs btn-danger delete-notice"
                                data-id="<?php echo (int) $n['id']; ?>">
                          <i class="fa fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No notices found. Click "New Notice" to add one.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Notice Modal -->
<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">New Notice</h4>
      </div>
      <div class="modal-body">
        <form id="notice-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="notice_id" id="notice-id" value="">
          
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="notice-title" class="form-control" required maxlength="255">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Notice Type</label>
                <select name="notice_type" id="notice-type" class="form-control">
                  <?php foreach ($notice_types as $type) { ?>
                    <option value="<?php echo e($type); ?>"><?php echo e($type); ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label>Message <span class="text-danger">*</span></label>
            <textarea name="message" id="notice-message" class="form-control" rows="4" required></textarea>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Display Position</label>
                <select name="display_position" id="notice-position" class="form-control">
                  <?php foreach ($display_positions as $pos) { ?>
                    <option value="<?php echo e($pos); ?>"><?php echo e($pos); ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Start Date/Time</label>
                <input type="datetime-local" name="start_datetime" id="notice-start" class="form-control">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>End Date/Time</label>
                <input type="datetime-local" name="end_datetime" id="notice-end" class="form-control">
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Background Color</label>
                <div class="input-group">
                  <input type="color" name="background_color" id="notice-bg-color" value="#ffc107" style="width:50px; height:34px; padding:2px; cursor:pointer;">
                  <input type="text" id="notice-bg-color-text" class="form-control" value="#ffc107" maxlength="20" style="width:100px;">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Text Color</label>
                <div class="input-group">
                  <input type="color" name="text_color" id="notice-text-color" value="#000000" style="width:50px; height:34px; padding:2px; cursor:pointer;">
                  <input type="text" id="notice-text-color-text" class="form-control" value="#000000" maxlength="20" style="width:100px;">
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Preview</label>
                <div id="color-preview" style="padding:8px 15px; border-radius:4px; background-color:#ffc107; color:#000000; text-align:center;">
                  Notice Preview
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div>
                <label>
                  <input type="checkbox" name="is_active" id="notice-active" value="1" checked> Active
                </label>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-notice">Save Notice</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
  // Color picker sync
  $('#notice-bg-color').on('input', function() {
    var color = $(this).val();
    $('#notice-bg-color-text').val(color);
    updatePreview();
  });
  $('#notice-bg-color-text').on('input', function() {
    var color = $(this).val();
    if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
      $('#notice-bg-color').val(color);
    }
    updatePreview();
  });
  $('#notice-text-color').on('input', function() {
    var color = $(this).val();
    $('#notice-text-color-text').val(color);
    updatePreview();
  });
  $('#notice-text-color-text').on('input', function() {
    var color = $(this).val();
    if (/^#[0-9A-Fa-f]{6}$/.test(color)) {
      $('#notice-text-color').val(color);
    }
    updatePreview();
  });

  function updatePreview() {
    var bg = $('#notice-bg-color').val();
    var text = $('#notice-text-color').val();
    $('#color-preview').css({
      'background-color': bg,
      'color': text
    });
  }

  // Add new notice
  $('#add-notice-btn').on('click', function() {
    resetForm();
    $('#noticeModal .modal-title').text('New Notice');
    $('#noticeModal').modal('show');
  });

  // Edit notice
  $('.edit-notice').on('click', function() {
    var $btn = $(this);
    $('#notice-id').val($btn.data('id'));
    $('#notice-title').val($btn.data('title'));
    $('#notice-message').val($btn.data('message'));
    $('#notice-type').val($btn.data('notice_type'));
    $('#notice-position').val($btn.data('display_position'));
    
    // Handle datetime format for datetime-local input
    var startDt = $btn.data('start_datetime');
    var endDt = $btn.data('end_datetime');
    if (startDt) {
      $('#notice-start').val(formatDateTimeLocal(startDt));
    }
    if (endDt) {
      $('#notice-end').val(formatDateTimeLocal(endDt));
    }
    
    var bgColor = $btn.data('background_color') || '#ffc107';
    var textColor = $btn.data('text_color') || '#000000';
    $('#notice-bg-color').val(bgColor);
    $('#notice-bg-color-text').val(bgColor);
    $('#notice-text-color').val(textColor);
    $('#notice-text-color-text').val(textColor);
    updatePreview();
    
    $('#notice-active').prop('checked', $btn.data('is_active') == 1);
    
    $('#noticeModal .modal-title').text('Edit Notice');
    $('#noticeModal').modal('show');
  });

  function formatDateTimeLocal(dt) {
    if (!dt) return '';
    // Convert "2026-02-17 10:30:00" to "2026-02-17T10:30"
    return dt.replace(' ', 'T').substring(0, 16);
  }

  // Save notice
  $('#save-notice').on('click', function() {
    var $form = $('#notice-form');
    var id = $('#notice-id').val();
    var url = id 
      ? '<?php echo admin_url('support_maintenance_notice/update'); ?>/' + id 
      : '<?php echo admin_url('support_maintenance_notice/add'); ?>';
    
    // Validate
    var title = $('#notice-title').val().trim();
    var message = $('#notice-message').val().trim();
    if (!title) {
      alert_float('warning', 'Title is required');
      return;
    }
    if (!message) {
      alert_float('warning', 'Message is required');
      return;
    }
    
    $.post(url, $form.serialize(), function(resp) {
      if (resp && resp.success) {
        $('#noticeModal').modal('hide');
        alert_float('success', resp.message || 'Notice saved');
        location.reload();
      } else {
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to save notice');
      }
    }, 'json');
  });

  // Delete notice
  $('.delete-notice').on('click', function() {
    var id = $(this).data('id');
    if (!id) return;
    if (!confirm('Are you sure you want to delete this notice?')) return;
    
    $.post('<?php echo admin_url('support_maintenance_notice/delete'); ?>/' + id, {
      <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    }, function(resp) {
      if (resp && resp.success) {
        alert_float('success', resp.message || 'Notice deleted');
        location.reload();
      } else {
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to delete notice');
      }
    }, 'json');
  });

  // Toggle status
  $('.toggle-status').on('click', function() {
    var id = $(this).data('id');
    if (!id) return;
    
    $.post('<?php echo admin_url('support_maintenance_notice/toggle_status'); ?>/' + id, {
      <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    }, function(resp) {
      if (resp && resp.success) {
        alert_float('success', resp.message || 'Status updated');
        location.reload();
      } else {
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to update status');
      }
    }, 'json');
  });

  // Reset form on modal close
  $('#noticeModal').on('hidden.bs.modal', function() {
    resetForm();
  });

  function resetForm() {
    $('#notice-form')[0].reset();
    $('#notice-id').val('');
    $('#notice-bg-color').val('#ffc107');
    $('#notice-bg-color-text').val('#ffc107');
    $('#notice-text-color').val('#000000');
    $('#notice-text-color-text').val('#000000');
    $('#notice-active').prop('checked', true);
    $('#color-preview').css({
      'background-color': '#ffc107',
      'color': '#000000'
    });
    $('#noticeModal .modal-title').text('New Notice');
  }
});
</script>
</body></html>
