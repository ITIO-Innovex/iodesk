<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white">DAR Master</h4>
        <div class="panel_s">
          <div class="panel-body">
            <form method="get" action="" class="mbot15">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" class="form-control" value="<?php echo e($filters['date'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Staff</label>
                    <select name="staffid" class="form-control">
                      <option value="">All Staff</option>
                      <?php foreach (($staffs ?? []) as $s) { ?>
                        <option value="<?php echo (int) $s['staffid']; ?>" <?php echo (!empty($filters['staffid']) && (int)$filters['staffid'] === (int)$s['staffid']) ? 'selected' : ''; ?>>
                          <?php echo e(($s['firstname'] ?? '') . ' ' . ($s['lastname'] ?? '')); ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <label>&nbsp;</label>
                  <div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    <a href="<?php echo admin_url('hrd/setting/dar_master'); ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($dars)) { ?>
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Staff</th>
                      <th>Attachments</th>
					  <th>Actions</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($dars as $dar) { ?>
                      <?php
                        $files = [];
                        if (!empty($dar['file'])) {
                          $files = array_filter(array_map('trim', explode(',', $dar['file'])));
                        }
                      ?>
                      <tr>
                        <td><?php echo e(date('d-m-Y', strtotime($dar['addedon']))); ?></td>
                        <td><?php echo e(trim(($dar['firstname'] ?? '') . ' ' . ($dar['lastname'] ?? ''))); ?></td>
                        <td>
                          <?php if (!empty($files)) { ?>
                            <ul class="list-unstyled">
                              <?php foreach ($files as $file) { ?>
                                <li><a href="<?php echo base_url($file); ?>" target="_blank"><?php echo e(basename($file)); ?></a></li>
                              <?php } ?>
                            </ul>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
						<td>
              <button type="button" class="btn btn-info btn-xs dar-view"
                      data-description="<?php echo htmlspecialchars($dar['descriptions'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                View
              </button>
            </td>
                        <td>
                          <?php if ((int) ($dar['status'] ?? 2) === 1) { ?>
                            <span class="label label-success">Submitted</span>
                          <?php } else { ?>
                            <span class="label label-default">Draft</span>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-info">No DAR records found.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="darViewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">DAR Description</h4>
      </div>
      <div class="modal-body">
        <textarea id="dar-view-description" class="form-control editor" rows="6"></textarea>
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
    $('#dar-view-description').jqte();
    $('.dar-view').on('click', function() {
      var desc = $(this).data('description') || '';
      var decoded = $('<textarea/>').html(desc).text();
      $('#dar-view-description').jqteVal(decoded);
      $('#darViewModal').modal('show');
    });
  });
</script>
</body></html>
