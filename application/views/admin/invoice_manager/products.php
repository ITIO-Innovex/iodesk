<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-my-0 tw-font-semibold"><?php echo $title; ?></h4>
                            <button type="button" class="btn btn-primary" id="addProductBtn">
                                <i class="fa fa-plus"></i> Add Product
                            </button>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="productsTable">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th width="12%">Price</th>
                                        <th width="10%">Tax %</th>
                                        <th width="8%">Status</th>
                                        <th width="12%">Created At</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo htmlspecialchars($record['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($record['description'] ?? ''); ?></td>
                                                <td class="text-right"><?php echo number_format((float)$record['price'], 2); ?></td>
                                                <td class="text-right"><?php echo number_format((float)$record['tax_percent'], 2); ?>%</td>
                                                <td>
                                                    <?php if ($record['status'] == 1) { ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php } else { ?>
                                                        <span class="label label-default">Inactive</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo !empty($record['created_at']) ? date('d-m-Y H:i', strtotime($record['created_at'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-xs edit-product" data-id="<?php echo $record['id']; ?>" title="Edit">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-product" data-id="<?php echo $record['id']; ?>" title="Delete">
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
<div class="modal fade" id="productModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="productForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="product_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title" id="modalTitle">Add Product</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product_name">Product Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="product_name" id="product_name" required>
                    </div>
                    <div class="form-group">
                        <label for="product_description">Description</label>
                        <textarea class="form-control" name="description" id="product_description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_price">Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="price" id="product_price" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_tax_percent">Tax Percent (%)</label>
                                <select class="form-control" name="tax_percent" id="product_tax_percent">
                                    <option value="0">No Tax</option>
                                    <?php if (!empty($tax_rates)) { ?>
                                        <?php foreach ($tax_rates as $tax) { ?>
                                            <option value="<?php echo $tax['taxrate']; ?>"><?php echo htmlspecialchars($tax['name']); ?> (<?php echo $tax['taxrate']; ?>%)</option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_status">Status</label>
                        <select class="form-control" name="status" id="product_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveProductBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    function resetForm() {
        $('#product_id').val('');
        $('#product_name').val('');
        $('#product_description').val('');
        $('#product_price').val('');
        $('#product_tax_percent').val('0');
        $('#product_status').val('1');
        $('#modalTitle').text('Add Product');
    }

    $('#addProductBtn').on('click', function() {
        resetForm();
        $('#productModal').modal('show');
    });

    $(document).on('click', '.edit-product', function() {
        var id = $(this).data('id');
        resetForm();
        
        $.get(admin_url + 'invoice_manager/get_product/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) {
                $('#product_id').val(r.data.id);
                $('#product_name').val(r.data.product_name || '');
                $('#product_description').val(r.data.description || '');
                $('#product_price').val(r.data.price || '');
                $('#product_tax_percent').val(r.data.tax_percent || '0');
                $('#product_status').val(r.data.status);
                $('#modalTitle').text('Edit Product');
                $('#productModal').modal('show');
            } else {
                alert_float('danger', r.message || 'Record not found');
            }
        }).fail(function() {
            alert_float('danger', 'Failed to load record');
        });
    });

    $('#productForm').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#saveProductBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        
        $.post(admin_url + 'invoice_manager/save_product', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save');
            
            if (r.success) {
                alert_float('success', r.message);
                $('#productModal').modal('hide');
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                alert_float('danger', r.message);
            }
        }).fail(function() {
            $btn.prop('disabled', false).html('Save');
            alert_float('danger', 'Failed to save');
        });
    });

    $(document).on('click', '.delete-product', function() {
        if (!confirm('Are you sure you want to delete this product?')) return;
        
        var id = $(this).data('id');
        var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
        var data = {};
        data[csrfName] = csrfHash;
        
        $.post(admin_url + 'invoice_manager/delete_product/' + id, data).done(function(res) {
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

    $('#productModal').on('hidden.bs.modal', function() {
        resetForm();
    });
});
</script>
</body>
</html>
