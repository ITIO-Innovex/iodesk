<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg">
              <i class="fa-solid fa-calendar-times menu-icon tw-mr-2"></i> Top 10 Leave Takers
            </h4>

            <!-- Search Form -->
            <form method="get" action="" class="mbot15" style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 4px;">
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Select Month</label>
                    <input type="month" name="month" class="form-control" value="<?php echo e($month); ?>" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-primary" title="Search">
                        <i class="fa fa-search"></i> Search
                      </button>
                      <a href="<?php echo admin_url('hrd/setting/top_10_leave_takers'); ?>" class="btn btn-default" title="Reset">
                        <i class="fa-solid fa-xmark"></i> Reset
                      </a>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="well" style="margin: 0; padding: 10px; text-align: center;">
                      <strong>Selected Month:</strong><br>
                      <span style="font-size: 16px; color: #337ab7;"><?php echo date('F Y', strtotime($month . '-01')); ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </form>

            <?php if (!empty($top_10)) { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                Showing top 10 employees with the most leave applications for the selected month.
              </div>

              <table class="table table-bordered table-striped dt-table" data-order-col="3" data-order-type="desc">
                <thead>
                  <tr style="background-color: #f9f9f9;">
                    <th style="width: 5%;">Rank</th>
                    <th style="width: 35%;">Employee Name</th>
                    <th style="width: 20%;">Total Leave</th>
                    <th style="width: 40%;">Options</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $rank = 1;
                  foreach ($top_10 as $employee) { 
                    $total_leaves = (int)$employee['total_leaves'];
                    // Determine badge color based on count
                    if ($total_leaves >= 10) {
                      $badge_class = 'danger';
                    } elseif ($total_leaves >= 5) {
                      $badge_class = 'warning';
                    } else {
                      $badge_class = 'info';
                    }
                  ?>
                    <tr>
                      <td style="text-align: center; font-weight: bold;">
                        <span class="badge badge-<?php echo $badge_class; ?>" style="font-size: 14px; padding: 6px 10px;">
                          #<?php echo $rank++; ?>
                        </span>
                      </td>
                      <td>
                        <strong><?php echo e($employee['full_name']); ?></strong>
                        <?php if (!empty($employee['employee_code'])) { ?>
                          <br><small class="text-muted">Code: <?php echo e($employee['employee_code']); ?></small>
                        <?php } ?>
                        <br><small class="text-muted">ID: <?php echo (int)$employee['staffid']; ?></small>
                      </td>
                      <td style="text-align: center;">
                        <span class="badge badge-<?php echo $badge_class; ?>" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo $total_leaves; ?>
                        </span>
                      </td>
                      <td style="text-align: center;">
                        <button type="button" 
                                class="btn btn-info btn-sm view-details-btn" 
                                data-staffid="<?php echo (int)$employee['staffid']; ?>"
                                data-staffname="<?php echo e($employee['full_name']); ?>"
                                data-totalleaves="<?php echo $total_leaves; ?>"
                                data-month="<?php echo e($month); ?>">
                          <i class="fa-solid fa-eye"></i> View Details
                        </button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="2" style="text-align: right;">Total Employees:</td>
                    <td style="text-align: center;">
                      <span class="badge badge-default" style="font-size: 16px; padding: 8px 12px;">
                        <?php echo count($top_10); ?>
                      </span>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                </tfoot>
              </table>
            <?php } else { ?>
              <div class="alert alert-warning">
                <i class="fa-solid fa-exclamation-triangle"></i> 
                No leave applications found for the selected month.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Leave Details Modal -->
<div class="modal fade" id="leave_details_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <i class="fa-solid fa-calendar-check"></i> 
          Leave Details - <span id="modal-staff-name"></span>
          <small class="text-muted">(<span id="modal-total-leaves"></span> leaves for <span id="modal-month"></span>)</small>
        </h4>
      </div>
      <div class="modal-body">
        <div id="leave-details-container">
          <p class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading leave details...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(document).ready(function(){
  // Store leaves data
  var leavesData = <?php echo json_encode($top_10); ?>;
  
  // View details button click
  $(document).on('click', '.view-details-btn', function(){
    var staffid = $(this).data('staffid');
    var staffName = $(this).data('staffname');
    var totalLeaves = $(this).data('totalleaves');
    var month = $(this).data('month');
    
    // Find employee's leaves
    var employeeLeaves = null;
    for(var i = 0; i < leavesData.length; i++) {
      if(leavesData[i].staffid == staffid) {
        employeeLeaves = leavesData[i].leaves;
        break;
      }
    }
    
    if(!employeeLeaves || employeeLeaves.length === 0) {
      alert('No leave details found for this employee');
      return;
    }
    
    // Update modal title
    $('#modal-staff-name').text(staffName);
    $('#modal-total-leaves').text(totalLeaves);
    $('#modal-month').text(formatMonth(month));
    
    // Build leave details table HTML
    var html = '<div class="table-responsive">';
    html += '<table class="table table-bordered table-striped">';
    html += '<thead>';
    html += '<tr>';
    html += '<th>#</th>';
    html += '<th>From Date</th>';
    html += '<th>To Date</th>';
    html += '<th>Leave Type</th>';
    html += '<th>For</th>';
    html += '<th>Status</th>';
    html += '<th>Reason</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody>';
    
    for(var i = 0; i < employeeLeaves.length; i++) {
      var leave = employeeLeaves[i];
      var fromDate = leave.from_date || '-';
      var toDate = leave.to_date || '-';
      var leaveType = leave.leave_type || '-';
      var leaveFor = (parseInt(leave.leave_for) === 2) ? 'Half Day' : 'Full Day';
      var statusVal = parseInt(leave.leave_status) || 0;
      var statusLbl = '';
      var statusClass = '';
      if(statusVal === 1) {
        statusLbl = 'Approved';
        statusClass = 'success';
      } else if(statusVal === 2) {
        statusLbl = 'Rejected';
        statusClass = 'danger';
      } else {
        statusLbl = 'Pending';
        statusClass = 'warning';
      }
      var reason = leave.leave_reson || '-';
      
      html += '<tr>';
      html += '<td>' + (i + 1) + '</td>';
      html += '<td>' + escapeHtml(fromDate) + '</td>';
      html += '<td>' + escapeHtml(toDate) + '</td>';
      html += '<td>' + escapeHtml(leaveType) + '</td>';
      html += '<td>' + escapeHtml(leaveFor) + '</td>';
      html += '<td><span class="label label-' + statusClass + '">' + escapeHtml(statusLbl) + '</span></td>';
      html += '<td>' + escapeHtml(reason) + '</td>';
      html += '</tr>';
    }
    
    html += '</tbody>';
    html += '</table>';
    html += '</div>';
    
    // Update modal content
    $('#leave-details-container').html(html);
    
    // Show modal
    $('#leave_details_modal').modal('show');
  });
  
  // Helper function to format month (YYYY-MM to "Month Year")
  function formatMonth(monthStr) {
    if(!monthStr || monthStr.length !== 7) return monthStr;
    var parts = monthStr.split('-');
    if(parts.length !== 2) return monthStr;
    var year = parts[0];
    var month = parseInt(parts[1]);
    var monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                     'July', 'August', 'September', 'October', 'November', 'December'];
    if(month >= 1 && month <= 12) {
      return monthNames[month - 1] + ' ' + year;
    }
    return monthStr;
  }
  
  // Helper function to escape HTML
  function escapeHtml(text) {
    if(text === null || text === undefined) return '';
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
  }
});
</script>
<?php hooks()->do_action('app_admin_footer'); ?>

