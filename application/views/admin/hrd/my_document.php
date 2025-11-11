<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-chart-gantt tw-mr-2 "></i>  My Document <i class="fa-solid fa-circle-info" title="Passed Holiday display in color" style=" color:khaki;"></i></span><span class="tw-inline pull-right"><?php echo e(get_staff_full_name()); ?> <?php  if(isset($GLOBALS['current_user']->branch)&&$GLOBALS['current_user']->branch) { echo "[ ".get_staff_branch_name($GLOBALS['current_user']->branch)." ]";} ?></span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
              <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">My Documents</h4>
              <a href="#" class="btn btn-primary" onclick="$('#doc_modal').modal('show');return false;">Add Document</a>
            </div>
            <div class="table-responsive">
              <table class="table dt-table" data-order-col="2" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Document Name</th>
                    <th>Document</th>
                    <th>Added on</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($documents)) { foreach ($documents as $d) { 
                    $name = isset($d['document_title']) ? $d['document_title'] : '';
                    $path = isset($d['document_path']) ? $d['document_path'] : '';
                    $added = isset($d['addedon']) ? $d['addedon'] : '';
                    $statusVal = isset($d['status']) ? (int)$d['status'] : 2;
                    $statusLbl = $statusVal === 1 ? '<span class="label label-success">Active</span>' : ($statusVal === 2 ? '<span class="label label-default">Pending</span>' : '<span class="label label-danger">Inactive</span>');
                  ?>
                  <tr>
                    <td><?php echo e($name); ?></td>
                    <td>
                      <?php if ($path) { ?>
                        <a href="<?php echo base_url($path); ?>" target="_blank" class="btn btn-default btn-sm">View</a>
                      <?php } ?>
                    </td>
                    <td><?php echo e($added); ?></td>
                    <td><?php echo $statusLbl; ?></td>
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
    <?php echo form_open_multipart(admin_url('hrd/my_document_add'), ['id' => 'doc-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Document</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Document Name</label>
          <input type="text" name="document_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Document</label>
          <input type="file" name="document[]" class="form-control" multiple required>
          <small class="text-muted">You can select multiple files. A separate entry will be created per file.</small>
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
</body></html>


