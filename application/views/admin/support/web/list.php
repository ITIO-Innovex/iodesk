<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.priority-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.priority-Low { background: #28a745; color: #fff; }
.priority-Medium { background: #ffc107; color: #000; }
.priority-High { background: #fd7e14; color: #fff; }
.priority-Urgent { background: #dc3545; color: #fff; }

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}
.status-Open { background: #17a2b8; color: #fff; }
.status-In-Progress { background: #007bff; color: #fff; }
.status-On-Hold { background: #6c757d; color: #fff; }
.status-Resolved { background: #28a745; color: #fff; }
.status-Closed { background: #343a40; color: #fff; }
</style>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <i class="fa-solid fa-ticket tw-mr-2"></i> <?php echo $title; ?>
                    </h4>
                    <a href="<?php echo admin_url('support/web/add'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus"></i> New Ticket
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body panel-table-fullxx">
                        <?php if (!empty($tickets)) { ?>
                            <div class="table-responsive">
                                <table class="table table-bordered dt-table" data-order-col="0" data-order-type="desc">
                                    <thead>
                                        <tr>
                                            <th width="60">ID</th>
                                            <th>Subject</th>
                                            <th width="100">Priority</th>
                                            <th width="110">Status</th>
                                            <th width="150">Created</th>
                                            <th width="150">Updated</th>
                                            <th width="80">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($tickets as $t) { 
                                            $statusClass = str_replace(' ', '-', $t['status']);
                                        ?>
                                        <tr>
                                            <td>#<?php echo (int) $t['id']; ?></td>
                                            <td><?php echo e($t['subject']); ?></td>
                                            <td>
                                                <span class="priority-badge priority-<?php echo e($t['priority']); ?>">
                                                    <?php echo e($t['priority']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="status-badge status-<?php echo $statusClass; ?>">
                                                    <?php echo e($t['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('d M Y H:i', strtotime($t['created_at'])); ?></td>
                                            <td><?php echo $t['updated_at'] ? date('d M Y H:i', strtotime($t['updated_at'])) : '-'; ?></td>
                                            <td>
                                                <a href="<?php echo admin_url('support/web/view/' . (int) $t['id']); ?>" class="btn btn-xs btn-info">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-info">
                                <i class="fa-solid fa-info-circle tw-mr-1"></i> 
                                No support tickets found. 
                                <a href="<?php echo admin_url('support/web/add'); ?>">Create your first ticket</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php init_tail(); ?>
</body></html>
