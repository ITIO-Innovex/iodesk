<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white"> <i class="fa-regular fa-bell  tw-mr-2"></i> Project & Task Notifications</h4>
        <div class="panel_s">
          <div class="panel-body">

            <h4 class="tw-mt-0 tw-font-semibold alert alert-info">Today Added Assigned Projects</h4>
            <?php if (!empty($today_projects)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Project</th>
                      <th>Client</th>
                      <th>Start Date</th>
                      <th>Deadline</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($today_projects as $p) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('projects/view/' . (int) $p['id']); ?>">
                            <?php echo html_escape($p['name'] ?? ('#' . $p['id'])); ?>
                          </a>
                        </td>
                        <td><?php echo html_escape($p['client_company'] ?? ''); ?></td>
                        <td><?php echo isset($p['start_date']) ? _d($p['start_date']) : '-'; ?></td>
                        <td><?php echo isset($p['deadline']) ? _d($p['deadline']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No projects assigned and added today.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-success">Project End Date - 1 Day Before</h4>
            <?php if (!empty($projects_deadline_tomorrow)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Project</th>
                      <th>Client</th>
                      <th>Deadline</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($projects_deadline_tomorrow as $p) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('projects/view/' . (int) $p['id']); ?>">
                            <?php echo html_escape($p['name'] ?? ('#' . $p['id'])); ?>
                          </a>
                        </td>
                        <td><?php echo html_escape($p['client_company'] ?? ''); ?></td>
                        <td><?php echo isset($p['deadline']) ? _d($p['deadline']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No projects reaching end date tomorrow.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-warning">Project End Date - Today</h4>
            <?php if (!empty($projects_deadline_today)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Project</th>
                      <th>Client</th>
                      <th>Deadline</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($projects_deadline_today as $p) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('projects/view/' . (int) $p['id']); ?>">
                            <?php echo html_escape($p['name'] ?? ('#' . $p['id'])); ?>
                          </a>
                        </td>
                        <td><?php echo html_escape($p['client_company'] ?? ''); ?></td>
                        <td><?php echo isset($p['deadline']) ? _d($p['deadline']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No projects ending today.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-danger">Project End Date Passed (Not Completed)</h4>
            <?php if (!empty($projects_overdue)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Project</th>
                      <th>Client</th>
                      <th>Deadline</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($projects_overdue as $p) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('projects/view/' . (int) $p['id']); ?>">
                            <?php echo html_escape($p['name'] ?? ('#' . $p['id'])); ?>
                          </a>
                        </td>
                        <td><?php echo html_escape($p['client_company'] ?? ''); ?></td>
                        <td><?php echo isset($p['deadline']) ? _d($p['deadline']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No overdue projects assigned to you.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-info">Today Added Assigned Tasks</h4>
            <?php if (!empty($today_tasks)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Task</th>
                      <th>Project</th>
                      <th>Date Added</th>
                      <th>Due Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($today_tasks as $t) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('tasks/view/' . (int) $t['id']); ?>">
                            <?php echo html_escape($t['name'] ?? ('#' . $t['id'])); ?>
                          </a>
                        </td>
                        <td>
                          <?php if (!empty($t['rel_type']) && $t['rel_type'] === 'project' && !empty($t['rel_id'])) { ?>
                            <a href="<?php echo admin_url('projects/view/' . (int) $t['rel_id']); ?>">
                              Project #<?php echo (int) $t['rel_id']; ?>
                            </a>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
                        <td><?php echo isset($t['dateadded']) ? _dt($t['dateadded']) : '-'; ?></td>
                        <td><?php echo isset($t['duedate']) ? _d($t['duedate']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No tasks assigned and added today.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-success">Task End Date - 1 Day Before</h4>
            <?php if (!empty($tasks_deadline_tomorrow)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Task</th>
                      <th>Project</th>
                      <th>Due Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tasks_deadline_tomorrow as $t) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('tasks/view/' . (int) $t['id']); ?>">
                            <?php echo html_escape($t['name'] ?? ('#' . $t['id'])); ?>
                          </a>
                        </td>
                        <td>
                          <?php if (!empty($t['rel_type']) && $t['rel_type'] === 'project' && !empty($t['rel_id'])) { ?>
                            <a href="<?php echo admin_url('projects/view/' . (int) $t['rel_id']); ?>">
                              Project #<?php echo (int) $t['rel_id']; ?>
                            </a>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
                        <td><?php echo isset($t['duedate']) ? _d($t['duedate']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No tasks reaching end date tomorrow.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-warning">Task End Date - Today</h4>
            <?php if (!empty($tasks_deadline_today)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Task</th>
                      <th>Project</th>
                      <th>Due Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tasks_deadline_today as $t) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('tasks/view/' . (int) $t['id']); ?>">
                            <?php echo html_escape($t['name'] ?? ('#' . $t['id'])); ?>
                          </a>
                        </td>
                        <td>
                          <?php if (!empty($t['rel_type']) && $t['rel_type'] === 'project' && !empty($t['rel_id'])) { ?>
                            <a href="<?php echo admin_url('projects/view/' . (int) $t['rel_id']); ?>">
                              Project #<?php echo (int) $t['rel_id']; ?>
                            </a>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
                        <td><?php echo isset($t['duedate']) ? _d($t['duedate']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No tasks ending today.</p>
            <?php } ?>

            <hr>

            <h4 class="tw-mt-0 tw-font-semibold alert alert-danger">Task End Date Passed (Not Completed)</h4>
            <?php if (!empty($tasks_overdue)) { ?>
              <div class="table-responsive mtop10">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Task</th>
                      <th>Project</th>
                      <th>Due Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tasks_overdue as $t) { ?>
                      <tr>
                        <td>
                          <a href="<?php echo admin_url('tasks/view/' . (int) $t['id']); ?>">
                            <?php echo html_escape($t['name'] ?? ('#' . $t['id'])); ?>
                          </a>
                        </td>
                        <td>
                          <?php if (!empty($t['rel_type']) && $t['rel_type'] === 'project' && !empty($t['rel_id'])) { ?>
                            <a href="<?php echo admin_url('projects/view/' . (int) $t['rel_id']); ?>">
                              Project #<?php echo (int) $t['rel_id']; ?>
                            </a>
                          <?php } else { ?>
                            <span class="text-muted">-</span>
                          <?php } ?>
                        </td>
                        <td><?php echo isset($t['duedate']) ? _d($t['duedate']) : '-'; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            <?php } else { ?>
              <p class="text-muted">No overdue tasks assigned to you.</p>
            <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>

