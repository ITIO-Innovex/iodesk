<div class="table-responsive">
    <table class="table table-bordered roles no-margin">
        <thead>
            <tr>
                <th><?= _l('features') ?></th>
                <th><?= _l('capabilities') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
			
            if (isset($member)) {
                $is_admin = is_admin($member->staffid);
            }
         foreach (get_available_staff_permissions($funcData) as $feature => $permission) { ?>
            <tr data-name="<?php echo e($feature); ?>">
                <td>
                    <b><?php echo e($permission['name']); ?></b>
                </td>
                <td>
                    <?php
                  if (isset($permission['before'])) {
                      echo $permission['before'];
                  }
                  ?>
                    <?php foreach ($permission['capabilities'] as $capability => $name) {
                      $checked  = '';
                      $disabled = '';
                      if ((isset($is_admin) && $is_admin) ||
                   (is_array($name) && isset($name['not_applicable']) && $name['not_applicable']) ||
                   (
                       ($capability == 'view_own' || $capability == 'view'
                          && array_key_exists('view_own', $permission['capabilities']) && array_key_exists('view', $permission['capabilities']))
                      &&
                        (
                            (isset($member)
                         && staff_can(($capability == 'view' ? 'view_own' : 'view'), $feature, $member->staffid))
                        ||
                        (isset($role)
                         && has_role_permission($role->roleid, ($capability == 'view' ? 'view_own' : 'view'), $feature))
                        )
                   )
                  ) {
                          $disabled = ' disabled ';
                      } elseif ((isset($member) && staff_can($capability, $feature, $member->staffid))
                    || isset($role) && has_role_permission($role->roleid, $capability, $feature)) {
                          $checked = ' checked ';
                      } ?>
                    <div class="tw-ml-2">
                        <div class="checkbox last:tw-mb-0">
                           <input <?php if ($capability == 'view') { ?> data-can-view <?php } ?>
                                <?php if ($capability == 'view_own') { ?> data-can-view-own <?php } ?>
                                <?php if (is_array($name) && isset($name['not_applicable']) && $name['not_applicable']) { ?>
                                data-not-applicable="true" <?php } ?> type="checkbox" <?php echo e($checked); ?>
                                class="capability" id="<?php echo $feature . '_' . $capability; ?>"
                                name="permissions[<?php echo e($feature); ?>][]" value="<?php echo e($capability); ?>"
                                <?php echo e($disabled); ?> >
                            <label for="<?php echo $feature . '_' . $capability; ?>">
                                <?php echo !is_array($name) ? $name : $name['name']; ?>
                            </label>
                            <?php
                      if (isset($permission['help']) && array_key_exists($capability, $permission['help'])) {
                          echo '<i class="fa-regular fa-circle-question" data-toggle="tooltip" data-title="' . e($permission['help'][$capability]) . '"></i>';
                      } ?>
                        </div>
                    </div>
                    <?php
                  } ?>
                    <?php
                  if (isset($permission['after'])) {
                      echo $permission['after'];
                  }
                  ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
