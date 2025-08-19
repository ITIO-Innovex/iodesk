<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="tw-mb-2 sm:tw-mb-4">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <i class="fa-solid fa-chart-line tw-mr-2"></i>Project Dashboard
                    </h4>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-md-3">
                <div class="widget-card bg-primary text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-chart-gantt"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $stats['total_projects']; ?></h3>
                            <p>Total Projects</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget-card bg-success text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-play-circle"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $stats['active_projects']; ?></h3>
                            <p>Active Projects</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget-card bg-info text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-tasks"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $stats['total_tasks']; ?></h3>
                            <p>Total Tasks</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="widget-card bg-warning text-white">
                    <div class="widget-card-body">
                        <div class="widget-card-icon">
                            <i class="fa-solid fa-percentage"></i>
                        </div>
                        <div class="widget-card-content">
                            <h3><?php echo $stats['task_completion_rate']; ?>%</h3>
                            <p>Task Completion Rate</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title">Project Status Distribution</h4>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="projectStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title">Task Status Distribution</h4>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title">Monthly Projects</h4>
                        <div class="chart-container" style="position: relative; height: 300px; width: 100%;">
                            <canvas id="monthlyProjectsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Listings -->
        <div class="row">
            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-chart-gantt tw-mr-2"></i>Latest Projects
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($latest_projects)): ?>
                                        <?php foreach ($latest_projects as $project): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($project['project_title']); ?></strong>
                                                    <?php if (!empty($project['project_group_name'])): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($project['project_group_name']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($project['status_name'])): ?>
                                                        <span class="label" style="background-color: <?php echo $project['status_color']; ?>; color: white;">
                                                            <?php echo htmlspecialchars($project['status_name']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-default">No Status</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo _dt($project['project_created']); ?></td>
                                                <td>
                                                    <a href="<?php echo admin_url('project/view/' . $project['id']); ?>" class="btn btn-xs btn-info">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No projects found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="<?php echo admin_url('project/'); ?>" class="btn btn-default">View All Projects</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="panel-title">
                            <i class="fa-solid fa-tasks tw-mr-2"></i>Latest Tasks
                        </h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Task</th>
                                        <th>Project</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($latest_tasks)): ?>
                                        <?php foreach ($latest_tasks as $task): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($task['task_name']); ?></strong>
                                                    <?php if (!empty($task['task_owner'])): ?>
                                                        <br><small class="text-muted">Owner: <?php echo get_staff_full_name($task['task_owner']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($task['project_title']); ?></td>
                                                <td>
                                                    <?php if (!empty($task['status_name'])): ?>
                                                        <span class="label" style="background-color: <?php echo $task['status_color']; ?>; color: white;">
                                                            <?php echo htmlspecialchars($task['status_name']); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="label label-default">No Status</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo admin_url('project/tasks_details/' . $task['id']); ?>" class="btn btn-xs btn-info">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No tasks found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center">
                            <a href="<?php echo admin_url('project/tasks'); ?>" class="btn btn-default">View All Tasks</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.widget-card {
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.widget-card-body {
    display: flex;
    align-items: center;
}

.widget-card-icon {
    font-size: 2.5em;
    margin-right: 15px;
    opacity: 0.8;
}

.widget-card-content h3 {
    margin: 0;
    font-size: 2em;
    font-weight: bold;
}

.widget-card-content p {
    margin: 5px 0 0 0;
    opacity: 0.9;
}

.panel_s {
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 20px;
}

.panel-body {
    padding: 15px;
}

.panel-title {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 16px;
    font-weight: bold;
}

.table th {
    background-color: #f5f5f5;
    border-bottom: 2px solid #ddd;
}

.label {
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: bold;
}

/* Chart container styles */
.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
    margin: 0 auto;
}

.chart-container canvas {
    max-height: 100%;
    max-width: 100%;
}

/* Ensure panels have consistent heights */
.panel_s {
    height: 100%;
}

.panel_s .panel-body {
    display: flex;
    flex-direction: column;
}

.panel_s .panel-title {
    margin-bottom: 15px;
    flex-shrink: 0;
}

.chart-container {
    flex: 1;
    min-height: 300px;
}
</style>
<?php init_tail(); ?>
<script src="https://cdn.jsdelivr.net/npm/chart66.js"></script>
<script>
$(document).ready(function() {
    // Project Status Chart
    const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
    const projectStatusData = <?php echo json_encode($project_status_chart); ?>;
    
    new Chart(projectStatusCtx, {
        type: 'doughnut',
        data: {
            labels: projectStatusData.map(item => item.name),
            datasets: [{
                data: projectStatusData.map(item => item.count),
                backgroundColor: projectStatusData.map(item => item.color || '#007bff'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Task Status Chart
    const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
    const taskStatusData = <?php echo json_encode($task_status_chart); ?>;
    
    new Chart(taskStatusCtx, {
        type: 'doughnut',
        data: {
            labels: taskStatusData.map(item => item.name),
            datasets: [{
                data: taskStatusData.map(item => item.count),
                backgroundColor: taskStatusData.map(item => item.color || '#28a745'),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Projects Chart
    const monthlyCtx = document.getElementById('monthlyProjectsChart').getContext('2d');
    const monthlyData = <?php echo json_encode($monthly_projects); ?>;
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Projects Created',
                data: monthlyData.map(item => item.count),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>


</body></html>