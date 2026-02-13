<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Daily Activity Report - List
		<a href="<?php echo admin_url('hrd/dar');?>" class="btn btn-primary pull-right" ><i class="fa-regular fa-plus"></i> Add New DAR</a>
		</h4>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($dars)) { ?>
              <table class="table dt-table">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <?php /*?><th>Description</th><?php */?>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dars as $dar) { ?>
                    <?php
                      $files = [];
                      if (!empty($dar['file'])) {
                        $files = array_filter(array_map('trim', explode(',', $dar['file'])));
                      }
                      $desc = htmlspecialchars($dar['descriptions'] ?? '', ENT_QUOTES, 'UTF-8');
                      $filesJson = htmlspecialchars(json_encode(array_values($files)), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                      <td><?php echo e(date('d-m-Y', strtotime($dar['addedon']))); ?></td>
                      <td>
                        <?php if ((int)$dar['status'] === 1) { ?>
                          <span class="label label-success">Submitted</span>
                        <?php } else { ?>
                          <span class="label label-default">Draft</span>
                        <?php } ?>
                      </td>
                      <?php /*?><td><?php echo e(wordwrap(strip_tags($dar['descriptions'] ?? ''), 60, "\n", true)); ?></td><?php */?>
                      <td>
                        <button type="button"
                                class="btn btn-info btn-xs dar-view"
                                data-description="<?php echo $desc; ?>"
                                data-files="<?php echo $filesJson; ?>">
                          View
                        </button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <div class="alert alert-info">No DAR records found.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dar_view_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">DAR Details</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Description</label>
          <div  class="well well-sm" style="min-height:120px;">
		  <textarea id="dar-view-description" name="description" class="form-control editor" ></textarea>
		  </div>
        </div>
        <div class="form-group">
          <label>Attachments</label>
          <div id="dar-view-files"></div>
        </div>
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
  $('.editor').jqte();
</script>
<script>
  $(function() {
    $('.dar-view').on('click', function() {
      var desc = $(this).data('description') || '';
      var filesData = $(this).data('files');
      var files = [];
      if (Array.isArray(filesData)) {
        files = filesData;
      } else if (typeof filesData === 'string') {
        try { files = JSON.parse(filesData); } catch (e) { files = []; }
      } else if (filesData) {
        files = [filesData];
      }

      var decoded = $('<textarea/>').html(desc).text();
      if (decoded) {
        $('#dar-view-description').jqteVal(decoded);
      } else {
        $('#dar-view-description').jqteVal('');
      }

      if (!files.length) {
        $('#dar-view-files').html('<span class="text-muted">No attachments</span>');
      } else {
        var list = '<ul class="list-unstyled">';
        for (var i = 0; i < files.length; i++) {
          var f = files[i];
          var name = f.split('/').pop();
          list += '<li><a href="<?php echo base_url(''); ?>' + f + '" target="_blank">' + $('<div>').text(name).html() + '</a></li>';
        }
        list += '</ul>';
        $('#dar-view-files').html(list);
      }
      $('#dar_view_modal').modal('show');
    });
  });
</script>
</body></html>
