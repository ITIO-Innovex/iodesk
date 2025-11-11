<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
      <i class="fa-solid fa-file-upload menu-icon tw-mr-2"></i> Uploaded Document by User
    </h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($user_list)) { ?>
              <table class="table dt-table" data-order-col="2" data-order-type="desc">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Employee Name</th>
                    <th>Number of Files</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  $index = 1;
                  foreach ($user_list as $user) { 
                  ?>
                    <tr>
                      <td><?php echo $index++; ?></td>
                      <td>
                        <strong><?php echo e($user['full_name']); ?></strong>
                        <?php if (!empty($user['employee_code'])) { ?>
                          <br><small class="text-muted">Code: <?php echo e($user['employee_code']); ?></small>
                        <?php } ?>
                        <br><small class="text-muted">ID: <?php echo (int)$user['staffid']; ?></small>
                      </td>
                      <td>
                        <span class="badge badge-primary" style="font-size: 16px; padding: 8px 12px;">
                          <?php echo (int)$user['file_count']; ?>
                        </span>
                      </td>
                      <td>
                        <button type="button" 
                                class="btn btn-info btn-sm view-files-btn" 
                                data-staffid="<?php echo (int)$user['staffid']; ?>"
                                data-staffname="<?php echo e($user['full_name']); ?>"
                                data-filecount="<?php echo (int)$user['file_count']; ?>">
                          <i class="fa-solid fa-eye"></i> View Files
                        </button>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
                <tfoot>
                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                    <td colspan="2" style="text-align: right;">Total Users:</td>
                    <td>
                      <span class="badge badge-default" style="font-size: 16px; padding: 8px 12px;">
                        <?php echo count($user_list); ?>
                      </span>
                    </td>
                    <td>&nbsp;</td>
                  </tr>
                </tfoot>
              </table>
            <?php } else { ?>
              <div class="alert alert-info">
                <i class="fa-solid fa-info-circle"></i> 
                No uploaded documents found.
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Files Modal -->
<div class="modal fade" id="files_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">
          <i class="fa-solid fa-file"></i> 
          Uploaded Files - <span id="modal-staff-name"></span>
          <small class="text-muted">(<span id="modal-file-count"></span> files)</small>
        </h4>
      </div>
      <div class="modal-body">
        <div id="files-list-container">
          <p class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading files...</p>
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
  // Store documents data
  var documentsData = <?php echo json_encode($user_list); ?>;
  
  // View files button click
  $(document).on('click', '.view-files-btn', function(){
    var staffid = $(this).data('staffid');
    var staffName = $(this).data('staffname');
    var fileCount = $(this).data('filecount');
    
    // Find user's documents
    var userDocs = null;
    for(var i = 0; i < documentsData.length; i++) {
      if(documentsData[i].staffid == staffid) {
        userDocs = documentsData[i].documents;
        break;
      }
    }
    
    if(!userDocs || userDocs.length === 0) {
      alert('No files found for this user');
      return;
    }
    
    // Update modal title
    $('#modal-staff-name').text(staffName);
    $('#modal-file-count').text(fileCount);
    
    // Build files list HTML
    var html = '<div class="table-responsive">';
    html += '<table class="table table-bordered table-striped">';
    html += '<thead>';
    html += '<tr>';
    html += '<th>#</th>';
    html += '<th>Document Name</th>';
    html += '<th>Added On</th>';
    html += '<th>Status</th>';
    html += '<th>Download</th>';
    html += '</tr>';
    html += '</thead>';
    html += '<tbody>';
    
    for(var i = 0; i < userDocs.length; i++) {
      var doc = userDocs[i];
      var docName = doc.document_title || 'Untitled';
      var docPath = doc.document_path || '';
      var addedOn = doc.addedon || '-';
      var statusVal = parseInt(doc.status) || 2;
      var statusLbl = '';
      if(statusVal === 1) {
        statusLbl = '<span class="label label-success">Approved</span>';
      } else if(statusVal === 0) {
        statusLbl = '<span class="label label-danger">Rejected</span>';
      } else {
        statusLbl = '<span class="label label-default">Pending</span>';
      }
      
      html += '<tr>';
      html += '<td>' + (i + 1) + '</td>';
      html += '<td><strong>' + escapeHtml(docName) + '</strong></td>';
      html += '<td>' + escapeHtml(addedOn) + '</td>';
      html += '<td>' + statusLbl + '</td>';
      html += '<td>';
      if(docPath) {
        var downloadUrl = '<?php echo base_url(); ?>' + docPath;
        html += '<a href="' + downloadUrl + '" target="_blank" class="btn btn-primary btn-sm" download>';
        html += '<i class="fa-solid fa-download"></i> Download';
        html += '</a>';
      } else {
        html += '<span class="text-muted">-</span>';
      }
      html += '</td>';
      html += '</tr>';
    }
    
    html += '</tbody>';
    html += '</table>';
    html += '</div>';
    
    // Update modal content
    $('#files-list-container').html(html);
    
    // Show modal
    $('#files_modal').modal('show');
  });
  
  // Helper function to escape HTML
  function escapeHtml(text) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    return (text || '').replace(/[&<>"']/g, function(m) { return map[m]; });
  }
});
</script>
<?php hooks()->do_action('app_admin_footer'); ?>

