<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
                                    <a href="<?php echo admin_url('user_utility/create'); ?>" class="btn btn-info pull-right">
                                        <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
                                    </a>
                                </h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>
                        
                        <?php if (count($forms) > 0) { ?>
                        <div class="table-responsive">
                            <table class="table dt-table" data-order-col="4" data-order-type="desc">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id'); ?></th>
                                        <th><?php echo _l('name'); ?></th>
                                        <th>Fields Count</th>
                                        <th>Has Data</th>
                                        <th><?php echo _l('date_created'); ?></th>
                                        <th><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($forms as $form) { 
                                        $form_fields = json_decode($form->form_fields, true);
                                        $fields_count = is_array($form_fields) ? count($form_fields) : 0;
                                        $has_data = !empty($form->form_data);
                                    ?>
                                    <tr>
                                        <td><?php echo $form->id; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('user_utility/view/' . $form->id); ?>">
                                                <?php echo $form->form_name; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $fields_count; ?></td>
                                        <td>
                                            <?php if ($has_data) { ?>
                                                <span class="label label-success">Yes</span>
                                            <?php } else { ?>
                                                <span class="label label-default">No</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo _dt($form->date_created); ?></td>
                                        <td>
                                            <div class="row-options">
                                                <a href="<?php echo admin_url('user_utility/view/' . $form->id); ?>" class="text-success">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('user_utility/edit/' . $form->id); ?>" class="text-info">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
												<?php if (is_admin()) { ?>
                                                <a href="<?php echo admin_url('user_utility/delete/' . $form->id); ?>" 
                                                   class="text-danger _delete" 
                                                   data-toggle="tooltip" 
                                                   data-title="<?php echo _l('delete'); ?>">
                                                    <i class="fa fa-remove"></i>
                                                </a>
												<?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="text-center">
                            <h4><?php echo _l('no_records_found'); ?></h4>
                            <p>
                                <a href="<?php echo admin_url('user_utility/create'); ?>" class="btn btn-info">
                                    <i class="fa fa-plus"></i> Create your first form
                                </a>
                            </p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
