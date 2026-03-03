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
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Month</label>
                    <input type="month" name="month" class="form-control" value="<?php echo e($filters['month'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
                  <label>&nbsp;</label>
                  <div>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    <a href="<?php echo admin_url('hrd/setting/dar_master'); ?>" class="btn btn-default"><i class="fa fa-refresh"></i> Reset</a>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($dars)) { ?>
              <?php
                $export_key = !empty($filters['month'])
                    ? $filters['month']
                    : (!empty($filters['date']) ? $filters['date'] : date('Y-m-d'));
              ?>
              <p class="mbot15">
                <a href="#" id="dar-master-download-excel" class="btn btn-success" download="DAR_Master_<?php echo e($export_key); ?>.xls"
                   onclick="return ExcellentExport.excel(this, 'dar-master-export-table', 'DAR_Master_<?php echo e($export_key); ?>');">
                  <i class="fa fa-file-excel-o"></i> Download as Excel
                </a>
              </p>
              <div class="table-responsive">
                <table class="table table-bordered" id="dar-master-table">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Staff</th>
					  <th>Actions</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($dars as $dar) {
                      $files = !empty($dar['file']) ? array_filter(array_map('trim', explode(',', $dar['file']))) : [];
                    ?>
<?php
                     
$desc = $dar['details'] ?? '';
$detailstable="No description found";
if(isset($desc)&&$desc){ 
$details = json_decode($desc, true) ?? [];
if (!empty($details) && is_array($details)) {
$detailstable="<table class='table dt-table' border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;width:100%;'>";
// ===== Header =====
if (!empty($details)) {
$detailstable.="<tr style='background:#f2f2f2;'>";
foreach ($details[0] as $field) {
$detailstable.="<th>" . htmlspecialchars($field['title']) . "</th>";
}
$detailstable.="</tr>";
}

// ===== Rows =====
foreach ($details as $project) {
$detailstable.="<tr>";

foreach ($project as $field) {
$detailstable.="<td>" . htmlspecialchars($field['value']) . "</td>";
}

$detailstable.="</tr>";
}

$detailstable.="</table>";



}
}
                    ?>
                      <tr>
                        <td><?php echo e(date('d-m-Y', strtotime($dar['addedon'] ?? $dar['date']))); ?></td>
                        <td><?php echo e(trim(($dar['firstname'] ?? '') . ' ' . ($dar['lastname'] ?? ''))); ?></td>
						<td>
<button type="button" class="btn btn-info btn-xs dar-view"
data-description="<?php echo htmlspecialchars($detailstable ?? '', ENT_QUOTES, 'UTF-8'); ?>">
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
              <table id="dar-master-export-table" class="hidden" style="display:none;">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Staff</th>
					<th>Status</th>
					<th>Details</th>
                    <th>Details Old</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dars as $dar) {
                    $statusLabel = ((int)($dar['status'] ?? 2) === 1) ? 'Submitted' : 'Draft';
					
$desc = $dar['details'] ?? '';
$detailstable="No description found";
if(isset($desc)&&$desc){ 
$details = json_decode($desc, true) ?? [];
if (!empty($details) && is_array($details)) {
$detailstable="<table class='table dt-table' border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;width:100%;'>";
// ===== Header =====
if (!empty($details)) {
$detailstable.="<tr style='background:#f2f2f2;'>";
foreach ($details[0] as $field) {
$detailstable.="<th>" . htmlspecialchars($field['title']) . "</th>";
}
$detailstable.="</tr>";
}

// ===== Rows =====
foreach ($details as $project) {
$detailstable.="<tr>";

foreach ($project as $field) {
$detailstable.="<td>" . htmlspecialchars($field['value']) . "</td>";
}

$detailstable.="</tr>";
}

$detailstable.="</table>";



}
}
$descriptions= $dar['descriptions'] ?? '';
                  ?>
                  <tr>
                    <td><?php echo e(date('d-m-Y', strtotime($dar['addedon'] ?? $dar['date']))); ?></td>
                    <td><?php echo e(trim(($dar['firstname'] ?? '') . ' ' . ($dar['lastname'] ?? ''))); ?></td>
					<td><?php echo e($statusLabel); ?></td>
                    <td><?php echo $detailstable;?></td>
					<td><?php echo $descriptions;?></td>
                    
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

<div class="modal fade" id="darViewModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl">
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
<script src="<?php echo base_url('assets/plugins/excellentexport/excellentexport.min.js'); ?>"></script>
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
