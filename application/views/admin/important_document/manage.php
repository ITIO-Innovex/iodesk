<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
              <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Important Documents</h4>
              <a href="#" class="btn btn-primary" onclick="openDocModal();return false;">Add Document</a>
            </div>
            <div class="table-responsive">
              <table class="table dt-table" data-order-col="4" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Document Title</th>
                    <th>Document</th>
                    <th>Remarks</th>
                    <th>Staff</th>
                    <th>Added on</th>
                    <th>Updated on</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($documents)) { foreach ($documents as $d) {
                    $name = isset($d['document_title']) ? $d['document_title'] : '';
                    $path = isset($d['document_path']) ? $d['document_path'] : '';
                    $remarks = isset($d['remarks']) ? $d['remarks'] : '';
                    $added = isset($d['addedon']) ? $d['addedon'] : '';
                    $updated = isset($d['updatedon']) ? $d['updatedon'] : '';
                    $staffName = isset($d['staff_name']) ? $d['staff_name'] : '';
                    $link = $path ? base_url($path) : '';
                  ?>
                  <tr>
                    <td><?php echo e($name); ?></td>
                    <td>
                      <?php if ($path) { ?>
                        <a href="<?php echo $link; ?>" target="_blank" class="btn btn-default btn-sm">View</a>
                        <a href="<?php echo $link; ?>" class="btn btn-primary btn-sm" download>Download</a>
                        <button type="button" class="btn btn-info btn-sm copy-document-link" data-link="<?php echo e($link); ?>">Copy Link</button>
                      <?php } ?>
                    </td>
                    <td><?php echo e($remarks); ?></td>
                    <td><?php echo e($staffName); ?></td>
                    <td><?php echo e($added); ?></td>
                    <td><?php echo e($updated); ?></td>
                    <td>
                      <button type="button"
                              class="btn btn-default btn-sm edit-document"
                              data-id="<?php echo (int) $d['id']; ?>"
                              data-title="<?php echo e($name); ?>"
                              data-remarks="<?php echo e($remarks); ?>">
                        Edit
                      </button>
                      <a href="<?php echo admin_url('important_document/delete/' . $d['id']); ?>"
                         class="btn btn-danger btn-sm"
                         onclick="return confirm('Delete this document?');">
                        Delete
                      </a>
                    </td>
                  </tr>
                  <?php } } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="doc_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('important_document/save'), ['id' => 'doc-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="docModalTitle">Add Document</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="doc_id">
        <div class="form-group">
          <label>Document Title</label>
          <input type="text" name="document_title" id="document_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Document</label>
          <input type="file" name="document[]" id="document_file" class="form-control" multiple required>
          <small class="text-muted">You can select multiple files. A separate entry will be created per file.</small>
        </div>
        <div class="form-group">
          <label>Remarks</label>
          <input type="text" name="remarks" id="remarks" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<?php init_tail(); ?>
<script>
  function openDocModal() {
    $('#docModalTitle').text('Add Document');
    $('#doc_id').val('');
    $('#document_title').val('');
    $('#remarks').val('');
    $('#document_file').prop('required', true).val('');
    $('#doc_modal').modal('show');
  }

  $(document).on('click', '.edit-document', function() {
    $('#docModalTitle').text('Edit Document');
    $('#doc_id').val($(this).data('id'));
    $('#document_title').val($(this).data('title'));
    $('#remarks').val($(this).data('remarks'));
    $('#document_file').prop('required', false).val('');
    $('#doc_modal').modal('show');
  });

  $(document).on('click', '.copy-document-link', function() {
    var link = $(this).data('link');
    if (!link) {
      return;
    }
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(link).then(function() {
        alert_float('success', 'Link copied');
      });
      return;
    }
    var $temp = $('<input>');
    $('body').append($temp);
    $temp.val(link).select();
    document.execCommand('copy');
    $temp.remove();
    alert_float('success', 'Link copied');
  });
</script>
</body></html>
