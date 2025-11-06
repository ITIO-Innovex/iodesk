<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
              <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Uploaded Documents</h4>
            </div>
            <div class="table-responsive">
              <table class="table dt-table" data-order-col="3" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Staff</th>
                    <th>Document Name</th>
                    <th>Document</th>
                    <th>Added on</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($documents)) { foreach ($documents as $d) { 
                    $name = isset($d['document_title']) ? $d['document_title'] : '';
                    $path = isset($d['document_path']) ? $d['document_path'] : '';
                    $added = isset($d['addedon']) ? $d['addedon'] : '';
                    $statusVal = isset($d['status']) ? (int)$d['status'] : 2;
                    $statusLbl = $statusVal === 1 ? '<span class="label label-success">Approved</span>' : ($statusVal === 2 ? '<span class="label label-default">Pending</span>' : '<span class="label label-danger">Rejected</span>');
                    $staffName = trim(($d['firstname'] ?? '') . ' ' . ($d['lastname'] ?? ''));
                  ?>
                  <tr>
                    <td><?php echo e($staffName); ?></td>
                    <td><?php echo e($name); ?></td>
                    <td>
                      <?php if ($path) { ?>
                        <a href="<?php echo base_url($path); ?>" target="_blank" class="btn btn-default btn-sm">View</a>
                      <?php } ?>
                    </td>
                    <td><?php echo e($added); ?></td>
                    <td><?php echo $statusLbl; ?></td>
                    <td>
                      <input type="text" class="form-control input-sm doc-remark" data-id="<?php echo (int)$d['id']; ?>" value="<?php echo isset($d['remarks']) ? e($d['remarks']) : ''; ?>" placeholder="Remark...">
                    </td>
                    <td>
                      <div class="btn-group">
                        <a href="#" class="btn btn-success btn-sm" onclick="updateDocStatus(<?php echo (int)$d['id']; ?>,1,this);return false;">Approve</a>
                        <a href="#" class="btn btn-danger btn-sm" onclick="updateDocStatus(<?php echo (int)$d['id']; ?>,0,this);return false;">Reject</a>
                      </div>
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
<?php init_tail(); ?>
<script>
function updateDocStatus(id, status, el){
  var $tr = $(el).closest('tr');
  var remarks = $tr.find('input.doc-remark[data-id="'+id+'"]').val();
  $.post(admin_url + 'hrd/document_update_status', {id: id, status: status, remarks: remarks}, function(resp){
    if(resp && resp.success){ window.location.reload(); } else { alert('Failed'); }
  }, 'json');
}
</script>
</body></html>


