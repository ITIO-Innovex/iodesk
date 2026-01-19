<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row mb-2">
      <div class="panel_s">
        <div class="panel-body panel-table-full">
		
          <div class="row ">
            <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6"> <i class="fa-regular fa-circle-check menu-icon tw-mx-2 text-success"></i> <span class="tw-truncate tw-text-sm">Name : <?php echo e(get_staff_full_name()); ?></span> </div>
            <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6"> <i class="fa-regular fa-circle-check menu-icon  tw-mx-2 text-success"></i> <span class="tw-truncate tw-text-sm">Email : <?php echo $GLOBALS['current_user']->email; ?></span> </div>
            <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6"> <i class="fa-regular fa-circle-check menu-icon tw-mx-2 text-success"></i> <span class="tw-truncate tw-text-sm">Role :
              <?php  if(isset($GLOBALS['current_user']->role)&&$GLOBALS['current_user']->role) { echo get_staff_role_name($GLOBALS['current_user']->role);} ?>
              <?php  if(isset($GLOBALS['current_user']->designation_id)&&$GLOBALS['current_user']->designation_id) { echo get_staff_designations_name($GLOBALS['current_user']->designation_id);} ?>
              [
              <?=get_user_type();?>
              ] </span> </div>
            <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6 "> <i class="fa-regular fa-circle-check menu-icon  tw-mx-2 text-success"></i> <span class="tw-truncate tw-text-sm">Company Name : <?php echo get_staff_company_name(); ?></span> </div>
            <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6"> <i class="fa-regular fa-circle-check menu-icon  tw-mx-2 text-success"></i> <span class="tw-truncate tw-text-sm">Created At : <?php echo $GLOBALS['current_user']->datecreated; ?></span> </div>
            <div class="tw-font-medium tw-inline-flex text-neutral-600 tw-items-center tw-truncate tw-my-2 col-sm-6"> <i class="fa-regular fa-circle-check menu-icon tw-mx-2 text-success"></i> <span class="tw-truncate tw-text-sm">Last Login : <?php echo $GLOBALS['current_user']->last_login; ?></span> </div>
            </span> </div>
		  <div class="row ">

<h4>Global Variable</h4>
<table border="1" cellpadding="8" cellspacing="0" width="100%" class="table table-clients number-index-2 dataTable no-footer">
  <thead>
    <tr>
      <th>Field</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($GLOBALS['current_user'] as $key => $value): ?>
      <?php if (!is_array($value)): ?>
        <tr>
          <td><?= htmlspecialchars($key) ?></td>
          <td><?= htmlspecialchars((string)$value) ?></td>
        </tr>
      <?php endif; ?>
    <?php endforeach; ?>
  </tbody>
</table>
		</div>
          <h4>Session Stored Value</h4>
          <table border="1" cellpadding="8" cellspacing="0" class="table table-clients number-index-2 dataTable no-footer">
            <thead>
              <tr>
                <th>Key</th>
                <th>Value</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($_SESSION as $key => $value): ?>
              <tr>
                <td><?= htmlspecialchars($key) ?></td>
                <td><?php
        if (is_array($value) || is_object($value)) {
            echo htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT));
        } else {
            echo htmlspecialchars((string) $value);
        }
        ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<?php init_tail(); ?>
</html>