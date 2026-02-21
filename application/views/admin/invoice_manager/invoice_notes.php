<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .jqte_tool.jqte_tool_1 .jqte_tool_label { height: 20px !important; }
    .jqte { margin: 10px 0 !important; }
    .jqte_editor { height: 200px !important; }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-my-0 tw-font-semibold"><?php echo $title; ?></h4>
                            <button type="button" class="btn btn-primary" id="addNoteBtn">
                                <i class="fa fa-plus"></i> Add Invoice Note
                            </button>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="invoiceNotesTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Description</th>
                                        <th width="10%">Status</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo $record['description']; ?></td>
                                                <td>
                                                    <?php if ($record['status'] == 1) { ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php } else { ?>
                                                        <span class="label label-default">Inactive</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs edit-note" data-id="<?php echo $record['id']; ?>" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-note" data-id="<?php echo $record['id']; ?>" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="noteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="noteForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="note_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="modalTitle">Add Invoice Note</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="note_description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control editor" name="description" id="note_description" rows="6"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="note_status">Status</label>
                        <select class="form-control" name="status" id="note_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveNoteBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
$(function() {
    $('#note_description').jqte();

    function resetForm() {
        $('#note_id').val('');
        $('#note_description').jqteVal('');
        $('#note_status').val('1');
        $('#modalTitle').text('Add Invoice Note');
    }

    $('#addNoteBtn').on('click', function() {
        resetForm();
        $('#noteModal').modal('show');
    });

    $(document).on('click', '.edit-note', function() {
        var id = $(this).data('id');
        resetForm();
        
        $.get(admin_url + 'invoice_manager/get_invoice_note/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) {
                $('#note_id').val(r.data.id);
                $('#note_description').jqteVal(r.data.description || '');
                $('#note_status').val(r.data.status);
                $('#modalTitle').text('Edit Invoice Note');
                $('#noteModal').modal('show');
            } else {
                alert_float('danger', r.message || 'Record not found');
            }
        }).fail(function() {
            alert_float('danger', 'Failed to load record');
        });
    });

    $('#noteForm').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#saveNoteBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.post(admin_url + 'invoice_manager/save_invoice_note', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save');
            
            if (r.success) {
                alert_float('success', r.message);
                $('#noteModal').modal('hide');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            $btn.prop('disabled', false).html('Save');
            alert_float('danger', 'Failed to save');
        });
    });

    $(document).on('click', '.delete-note', function() {
        if (!confirm('Are you sure you want to delete this invoice note?')) return;
        
        var id = $(this).data('id');
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var data = {};
        data[csrfName] = csrfHash;
        
        $.post(admin_url + 'invoice_manager/delete_invoice_note/' + id, data).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) {
                alert_float('success', r.message);
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            alert_float('danger', 'Failed to delete');
        });
    });

    $('#noteModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});
</script>
</body>
</html>
