<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2">
      <i class="fa-solid fa-users tw-mr-2"></i> Approver List
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <?php if (!empty($approver_list)) { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                Total Approvers: <strong><?php echo count($approver_list); ?></strong>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-striped dt-table" data-order-col="0" data-order-type="asc">
                  <thead>
                    <tr style="background-color: #f9f9f9;">
                      <th style="width: 5%;">#</th>
                      <th style="width: 25%;">Employee Name</th>
                      <th style="width: 20%;">Approver Title</th>
                      <th style="width: 20%;">Email</th>
                      <th style="width: 15%;">Phone Number</th>
                      <?php /*?><th style="width: 15%;">Employee Code</th><?php */?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $counter = 1;
                    foreach ($approver_list as $approver) { 
                    ?>
                      <tr>
                        <td><?php echo $counter++; ?></td>
                        <td>
                          <strong><?php echo e($approver['full_name']); ?></strong>
                        </td>
                        <td>
                          <?php 
                          if (!empty($approver['approver_titles']) && is_array($approver['approver_titles'])) {
                            foreach ($approver['approver_titles'] as $title) {
                              $badge_class = 'badge-info';
                              if (strpos($title, 'HR Manager') !== false) {
                                $badge_class = 'badge-warning';
                              } elseif (strpos($title, 'Admin') !== false) {
                                $badge_class = 'badge-primary';
                              } elseif (strpos($title, 'Reporting') !== false) {
                                $badge_class = 'badge-success';
                              }
                          ?>
                            <span class="badge <?php echo $badge_class; ?>" style="margin-right: 5px; margin-bottom: 3px; display: inline-block;">
                              <?php echo e($title); ?>
                            </span>
                          <?php 
                            }
                          } else { 
                          ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
                        <td>
                          <?php if (!empty($approver['email'])) { ?>
                            <a href="mailto:<?php echo e($approver['email']); ?>" title="Send Email">
                              <i class="fa-solid fa-envelope tw-mr-1"></i>
                              <?php echo e($approver['email']); ?>
                            </a>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
                        <td><span class="text-muted">-</span>
                          <?php /*?><?php if (!empty($approver['phonenumber'])) { ?>
                            <a href="tel:<?php echo e($approver['phonenumber']); ?>" title="Call">
                              <i class="fa-solid fa-phone tw-mr-1"></i>
                              <?php echo e($approver['phonenumber']); ?>
                            </a>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?><?php */?>
                        </td>
                        <?php /*?><td>
                          <?php echo !empty($approver['employee_code']) ? e($approver['employee_code']) : '<span class="text-muted">-</span>'; ?>
                        </td><?php */?>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                No approvers found. Please ensure staff members have approver information configured.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<?php hooks()->do_action('app_admin_footer'); ?>

