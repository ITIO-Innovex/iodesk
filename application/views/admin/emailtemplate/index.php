<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Internal Email Templates</h4>
		  <?php if(is_super()){ ?>
          <a href="<?php echo admin_url('emailtemplate/manage'); ?>" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> Add Template
          </a>
		  <?php } ?>
        </div>

        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($templates)) { ?>
              <table class="table dt-table" data-order-col="0" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Template Title</th>
                    <th>Subject</th>
                    <th><?php echo _l('options'); ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($templates as $t) { ?>
                    <tr>
                      <td><?php echo e($t['template_title'] ?? ''); ?></td>
                      <td><?php echo e($t['subject'] ?? ''); ?></td>
                      <td>
                        <div class="tw-flex tw-items-center tw-space-x-3">
						<?php if(isset($t['company_id'])&&$t['company_id']==1){ ?>
						<?php if(is_super()){ ?>
						<a href="<?php echo admin_url('emailtemplate/manage/' . (int) $t['id']); ?>" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                          </a>
                          <a href="<?php echo admin_url('emailtemplate/delete/' . (int) $t['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                            <i class="fa-regular fa-trash-can fa-lg"></i>
                          </a>
						<?php }else{ ?>
						<a href="<?php echo admin_url('emailtemplate/create_manage/' . (int) $t['id']); ?>" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                          </a>
						<?php } ?>
						
						<?php }else{ ?>
                          <a href="<?php echo admin_url('emailtemplate/manage/' . (int) $t['id']); ?>" class="tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700">
                            <i class="fa-regular fa-pen-to-square fa-lg"></i>
                          </a>
                          <a href="<?php echo admin_url('emailtemplate/delete/' . (int) $t['id']); ?>" class="tw-mt-px tw-text-neutral-500 hover:tw-text-neutral-700 focus:tw-text-neutral-700 _delete">
                            <i class="fa-regular fa-trash-can fa-lg"></i>
                          </a>
						 <?php } ?>
                        </div>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <p class="text-muted">No templates found.</p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
</body>
</html>

