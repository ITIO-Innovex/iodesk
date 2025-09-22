<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  #sortable { list-style-type: none;}
  #sortable li { margin: 10px; padding: 10px; }
  </style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" onclick="new_branch_manager(); return false;" class="btn btn-primary"> <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Branch Manager'); ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (count($branch_managers) > 0) { ?>
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
                            <thead>
                                <th>Branch Name</th>
                                <th>Branch Address</th>
                                <th><?php echo _l('status'); ?></th>
                                <th><?php echo _l('options'); ?></th>
                            </thead>
                            <tbody>
                                <?php foreach ($branch_managers as $branch_manager) { ?>
                                <tr>
                                    <td>
                                        <a href="#"
                                            onclick="edit_branch_manager(this,<?php echo e($branch_manager['id']); ?>);return false;"
                                            data-branch-name="<?php echo e($branch_manager['branch_name']); ?>"
                                            data-branch-address="<?php echo e($branch_manager['branch_address']); ?>"
                                        ><?php echo e($branch_manager['branch_name']); ?></a><br />
                                    </td>
                                    <td><?php echo e($branch_manager['branch_address']); ?></td>
                                    <td>
                                        <a href="javascript:void(0);" onclick="toggleBranchManagerStatus(<?php echo $branch_manager['id']; ?>, <?php echo $branch_manager['status']; ?>)" id="status-label-<?php echo $branch_manager['id']; ?>">
                                        <?php if ($branch_manager['status']) { ?>
                                            <span class="label label-success">Active</span>
                                        <?php } else { ?>
                                            <span class="label label-danger">Deactive</span>
                                        <?php } ?>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="tw-flex tw-items-center tw-space-x-3">
                                            <a href="#"
                                                onclick="edit_branch_manager(this,<?php echo e($branch_manager['id']); ?>);return false;"
                                                data-branch-name="<?php echo e($branch_manager['branch_name']); ?>"
                                                data-branch-address="<?php echo e($branch_manager['branch_address']); ?>"
                                                class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                                                <i class="fa-regular fa-pen-to-square fa-lg"></i>
                                            </a>
                                            <a href="<?php echo admin_url('hrd/delete_branch_manager/' . $branch_manager['id']); ?>"
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
            <p class="no-margin">No branch managers found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="branch_manager" tabindex="-1" role="dialog">
  <div class="modal-dialog"> <?php echo form_open(admin_url('hrd/branchmanager'), ['id' => 'branch-manager-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"> <span class="edit-title">Edit Branch Manager</span> <span class="add-title"><?php echo _l('Add New Branch Manager'); ?></span> </h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div id="additional"></div>
            <?php echo render_input('branch_name', 'Branch Name'); ?> 
            <div class="form-group">
                <label for="branch_address">Branch Address</label>
                <textarea name="branch_address" id="branch_address" class="form-control" rows="3" required></textarea>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <!-- /.modal-content -->
    <?php echo form_close(); ?> </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#branch-manager-form'), {
        branch_name: 'required',
        branch_address: 'required'
    }, manage_branch_manager);
    $('#branch_manager').on("hidden.bs.modal", function (event) {
        $('#additional').html('');
        $('#branch_manager input[name="branch_name"]').val('');
        $('#branch_manager textarea[name="branch_address"]').val('');
        $('.add-title').removeClass('hide');
        $('.edit-title').removeClass('hide');
    });
});

// Create new branch manager
function new_branch_manager() {
    $('#branch_manager').modal('show');
    $('.edit-title').addClass('hide');
}

// Edit branch manager function which init the data to the modal
function edit_branch_manager(invoker, id) {
    $('#additional').append(hidden_input('id', id));
    $('#branch_manager input[name="branch_name"]').val($(invoker).data('branch-name'));
    $('#branch_manager textarea[name="branch_address"]').val($(invoker).data('branch-address'));
    $('#branch_manager').modal('show');
    $('.add-title').addClass('hide');
}

// Form handler function for branch manager
function manage_branch_manager(form) {
    var data = $(form).serialize();
    var url = form.action;
    $.post(url, data).done(function (response) {
        window.location.reload();
    });
    return false;
}

function toggleBranchManagerStatus(id, currentStatus) {
  $.post(admin_url + 'hrd/toggle_branch_manager/' + id, {status: currentStatus == 1 ? 0 : 1}, function(response) {
    if (response.success) {
      var label = $('#status-label-' + id + ' span');
      if (response.new_status == 1) {
        label.removeClass('label-danger').addClass('label-success').text('Active');
      } else {
        label.removeClass('label-success').addClass('label-danger').text('Deactive');
      }
      $('#status-label-' + id).attr('onclick', 'toggleBranchManagerStatus(' + id + ', ' + response.new_status + ')');
    } else {
      alert('Failed to update status');
    }
  }, 'json');
}
</script>
<?php init_tail(); ?>
</body></html>

