<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); $companyId = get_staff_company_id();?>
<style>
  .email-tags-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    padding: 6px 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    min-height: 38px;
    background: #fff;
    cursor: text;
  }
  .email-tags-container:focus-within {
    border-color: #66afe9;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
  }
  .email-tag {
    display: inline-flex;
    align-items: center;
    background: #e0e0e0;
    color: #333;
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 13px;
  }
  .email-tag .remove-tag {
    margin-left: 6px;
    cursor: pointer;
    font-weight: bold;
    color: #666;
  }
  .email-tag .remove-tag:hover {
    color: #c00;
  }
  .email-input-field {
    flex: 1;
    min-width: 150px;
    border: none;
    outline: none;
    font-size: 14px;
    padding: 2px 0;
  }
  .email-suggestions {
    position: absolute;
    z-index: 1050;
    background: #fff;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 200px;
    overflow-y: auto;
    width: 100%;
    display: none;
  }
  .email-suggestion-item {
    padding: 8px 12px;
    cursor: pointer;
  }
  .email-suggestion-item:hover, .email-suggestion-item.active {
    background: #f0f0f0;
  }
  .email-input-wrapper {
    position: relative;
  }
  .suggestion-name {
    font-weight: 500;
  }
  .suggestion-email {
    color: #666;
    font-size: 12px;
  }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"> <i class="fa-solid fa-file-pen tw-mr-2"></i> Daily Activity Report - List
		<a href="<?php echo admin_url('hrd/dar');?>" class="btn btn-primary pull-right btn-sm" ><i class="fa-regular fa-plus"></i><span class="m-hide" title="Add New DAR"> Add New DAR</span></a>
		</h4>
		</div>
		<div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <?php if (!empty($dars)) { ?>
              <table class="table dt-table" data-order-col="0" data-order-type="asc">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <?php /*?><th>Description</th><?php */?>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dars as $dar) { ?>
                    <?php
                     
$desc = $dar['details'] ?? '';
$detailstable="No description found";
if(isset($desc)&&$desc){ 
$details = json_decode($desc, true) ?? [];
if (!empty($details) && is_array($details)) {
$detailstable="<table class='table dt-table' border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse;width:100%;'>";
// ===== Header =====
if (!empty($details)) {
$detailstable.="<tr style='background:#f2f2f2;'>";
foreach ($details[0] as $field) {
$detailstable.="<th>" . htmlspecialchars($field['title']) . "</th>";
}
$detailstable.="</tr>";
}

// ===== Rows =====
foreach ($details as $project) {
$detailstable.="<tr>";

foreach ($project as $field) {
$detailstable.="<td>" . htmlspecialchars($field['value']) . "</td>";
}

$detailstable.="</tr>";
}

$detailstable.="</table>";



}
}
                    ?>
                    <tr>
                      <td><?php echo e(date('d-m-Y', strtotime($dar['addedon']))); ?></td>
                      <td>
                        <?php if ((int)$dar['status'] === 1) { ?>
                          <span class="label label-success">Submitted</span>
                        <?php } else { ?>
                          <span class="label label-default">Draft</span>
                        <?php } ?>
                      </td>
                      
                      <td>
                        <button type="button"
                                class="btn btn-info btn-xs dar-view"
                                data-id="<?php echo (int) $dar['id']; ?>"
                                data-status="<?php echo (int) $dar['status']; ?>"
                                data-description="<?php echo htmlspecialchars($detailstable ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                          View
                        </button>
<?php if($dar['status'] == 2 && date('Y-m-d',strtotime($dar['addedon'])) == date('Y-m-d')){ ?>
<a href="<?php echo admin_url('hrd/daily_activity_report_dar');?>" class="btn btn-warning btn-xs" title="View and submit">Edit</a>
<?php } ?>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            <?php } else { ?>
              <div class="alert alert-info">No DAR records found.</div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dar_view_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title">DAR Details</h4>
        </div>
        <div class="modal-body">


          <div class="form-group">
            <label>Description <span class="text-danger" id="desc-required" style="display:none;">*</span></label>
			<div class="table-responsive">
            <div class="dataDisplay" style="min-height:120px;"></div>
            </div>  
            
          </div>
          
          
        </div>
        
      
    </div>
  </div>
</div>

<?php init_tail(); ?>

<script>
  $(function() {
   
    
    $('.dar-view').on('click', function() {
      var desc = $(this).data('description') || '';
	 $(".dataDisplay").html(desc);
	 $('#dar_view_modal').modal('show');
     
    });
    
  
    
  });
</script>
</body></html>
