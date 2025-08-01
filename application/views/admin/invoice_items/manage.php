<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <?php if (staff_can('delete',  'items')) { ?>
                <a href="#" data-toggle="modal" data-table=".table-invoice-items" data-target="#items_bulk_actions"
                    class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>
                <div class="modal fade bulk_actions" id="items_bulk_actions" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                            </div>
                            <div class="modal-body">
                                <?php if (staff_can('delete',  'items')) { ?>
                                <div class="checkbox checkbox-danger">
                                    <input type="checkbox" name="mass_delete" id="mass_delete">
                                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                                </div>
                                <!-- <hr class="mass_delete_separator" /> -->
                                <?php } ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default"
                                    data-dismiss="modal"><?php echo _l('close'); ?></button>
                                <a href="#" class="btn btn-primary"
                                    onclick="items_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->
                <?php } ?>

                <?php hooks()->do_action('before_items_page_content'); ?>

                <?php if (staff_can('create',  'items')) { ?>
                <div class="_buttons tw-mb-2 sm:tw-mb-4 tw-inline-block tw-w-full">
                    <a href="#" class="btn btn-primary pull-left" data-toggle="modal" data-target="#sales_item_modal">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_invoice_item'); ?>
                    </a>
                    <a href="<?php echo admin_url('invoice_items/import'); ?>" class="btn btn-primary pull-left mleft5">
                        <i class="fa-solid fa-upload tw-mr-1"></i>
                        <?php echo _l('import_items'); ?>
                    </a>
                    <a href="#" class="btn btn-default pull-left mleft5" data-toggle="modal" data-target="#groups">
                        <i class="fa-solid fa-layer-group tw-mr-1"></i>
                        <?php echo _l('item_groups'); ?>
                    </a>
                </div>
                <div class="clearfix"></div>
                <?php } ?>
				<div class="tw-mb-2 sm:tw-mb-4">
                <div class="panel_s">
                <div class="panel-body panel-table-full">
                                    
									
									
			<?php if (isset($products)&&count($products) > 0) { ?>
			
            <table class="table dt-table" data-order-col="0" data-order-type="desc" >
                            <thead>
							    <th><?php echo _l('ID'); ?></th>
								<th><?php echo _l('Image'); ?></th>
                                <th><?php echo _l('Title'); ?></th>
								<th><?php echo _l('Description'); ?></th>
								<th><?php echo _l('Rate'); ?></th>

								
                            </thead>
                            <tbody>
<?php foreach ($products as $prd) {?> 
								
								
                                <tr>
                                 <td><?php echo $prd['id']; ?></td>   
								 <td><img src="<?php echo !empty($prd['image']) ? base_url('uploads/product/images/' . $prd['image']) : base_url('uploads/product/images/dummy.jpg'); ?>" alt="Card Header Image" style="width: 150px; height:100px; border-top-left-radius: 5px; border-top-right-radius: 5px;"></td>
                                 <td><?php echo $prd['description']; ?><br />
<a href="#" data-toggle="modal" data-target="#sales_item_modal" data-id="<?= $prd['id']; ?>">Edit </a>&nbsp;&nbsp;<a href="<?php echo admin_url('invoice_items/delete'); ?>/<?php echo $prd['id']; ?>"   title="Delete" class="text-danger" onclick="return confirm('Do you really want to Delete?');">Delete </a></td>
								 <td><?php echo $prd['long_description']; ?></td>
								 <td><?php echo $prd['rate']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
						

		
						
            <?php } else { ?>
            <p class="no-margin"><?php echo _l('Product Not Found'); ?></p>
            <?php } ?>
                     </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('admin/invoice_items/item'); ?>
<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">
                    <?php echo _l('item_groups'); ?>
                </h4>
            </div>
            <div class="modal-body">
                <?php if (staff_can('create',  'items')) { ?>
                <div class="input-group">
                    <input type="text" name="item_group_name" id="item_group_name" class="form-control"
                        placeholder="<?php echo _l('item_group_name'); ?>">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" id="new-item-group-insert">
                            <?php echo _l('new_item_group'); ?>
                        </button>
                    </span>
                </div>
                <hr />
                <?php } ?>
                <div class="row">
                    <div class="container-fluid">
                        <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
                            <thead>
                                <tr>
                                    <th><?php echo _l('id'); ?></th>
                                    <th><?php echo _l('item_group_name'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items_groups as $group) { ?>
                                <tr class="row-has-options" data-group-row-id="<?php echo e($group['id']); ?>">
                                    <td data-order="<?php echo e($group['id']); ?>"><?php echo e($group['id']); ?></td>
                                    <td data-order="<?php echo e($group['name']); ?>">
                                        <span class="group_name_plain_text"><?php echo e($group['name']); ?></span>
                                        <div class="group_edit hide">
                                            <div class="input-group">
                                                <input type="text" class="form-control">
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary update-item-group"
                                                        type="button"><?php echo _l('submit'); ?></button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row-options">
                                            <?php if (staff_can('edit',  'items')) { ?>
                                            <a href="#" class="edit-item-group">
                                                <?php echo _l('edit'); ?>
                                            </a>
                                            <?php } ?>
                                            <?php if (staff_can('delete',  'items')) { ?>
                                            | <a href="<?php echo admin_url('invoice_items/delete_group/' . $group['id']); ?>"
                                                class="delete-item-group _delete text-danger">
                                                <?php echo _l('delete'); ?>
                                            </a>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
$(function() {

    var notSortableAndSearchableItemColumns = [];
    <?php if (staff_can('delete',  'items')) { ?>
    notSortableAndSearchableItemColumns.push(0);
    <?php } ?>


    <?php if ($this->input->get('id')) { ?>
    var id = "<?php echo $this->input->get('id') ?>";
    if (typeof(id) !== 'undefined') {
        var $itemModal = $('#sales_item_modal');
        $('input[name="itemid"]').val(id);
        requestGetJSON('invoice_items/get_item_by_id/' + id).done(function(response) {
            $itemModal.find('input[name="description"]').val(response.description);
            $itemModal.find('textarea[name="long_description"]').val(response.long_description.replace(
                /(<|<)br\s*\/*(>|>)/g, " "));
            $itemModal.find('input[name="rate"]').val(response.rate);
            $itemModal.find('#group_id').selectpicker('val', response.group_id);
            $.each(response, function(column, value) {
                if (column.indexOf('rate_currency_') > -1) {
                    $itemModal.find('input[name="' + column + '"]').val(value);
                }
            });

            $('#custom_fields_items').html(response.custom_fields_html);

            init_selectpicker();
            init_color_pickers();
            init_datepicker();

            $itemModal.find('.add-title').addClass('hide');
            $itemModal.find('.edit-title').removeClass('hide');
            validate_item_form();
        });
        $itemModal.modal('show');
    }
    <?php } ?>

    initDataTable('.table-invoice-items', admin_url + 'invoice_items/table',
        notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns, 'undefined', [1, 'asc']);

    if (get_url_param('groups_modal')) {
        // Set time out user to see the message
        setTimeout(function() {
            $('#groups').modal('show');
        }, 1000);
    }

    $('#new-item-group-insert').on('click', function() {
        var group_name = $('#item_group_name').val();
        if (group_name != '') {
            $.post(admin_url + 'invoice_items/add_group', {
                name: group_name
            }).done(function() {
                window.location.href = admin_url + 'invoice_items?groups_modal=true';
            });
        }
    });

    $('body').on('click', '.edit-item-group', function(e) {
        e.preventDefault();
        var tr = $(this).parents('tr'),
            group_id = tr.attr('data-group-row-id');
        tr.find('.group_name_plain_text').toggleClass('hide');
        tr.find('.group_edit').toggleClass('hide');
        tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

    $('body').on('click', '.update-item-group', function() {
        var tr = $(this).parents('tr');
        var group_id = tr.attr('data-group-row-id');
        name = tr.find('.group_edit input').val();
        if (name != '') {
            $.post(admin_url + 'invoice_items/update_group/' + group_id, {
                name: name
            }).done(function() {
                window.location.href = admin_url + 'invoice_items';
            });
        }
    });
});

function items_bulk_action(event) {
    if (confirm_delete()) {
        var mass_delete = $('#mass_delete').prop('checked');
        var ids = [];
        var data = {};

        if (mass_delete == true) {
            data.mass_delete = true;
        }

        var rows = $('.table-invoice-items').find('tbody tr');
        $.each(rows, function() {
            var checkbox = $($(this).find('td').eq(0)).find('input');
            if (checkbox.prop('checked') === true) {
                ids.push(checkbox.val());
            }
        });
        data.ids = ids;
        $(event).addClass('disabled');
        setTimeout(function() {
            $.post(admin_url + 'invoice_items/bulk_action', data).done(function() {
                window.location.reload();
            }).fail(function(data) {
                alert_float('danger', data.responseText);
            });
        }, 200);
    }
}
</script>
<!-- Show More Less -->
<script>
function showMore(id) {
    // Hide the short description and show the long description
    document.getElementById('shortDesc_' + id).style.display = 'none';
    document.getElementById('longDesc_' + id).style.display = 'block';
}

function showLess(id) {
    // Show the short description and hide the long description
    document.getElementById('shortDesc_' + id).style.display = 'block';
    document.getElementById('longDesc_' + id).style.display = 'none';
}
</script>
</body>

</html>