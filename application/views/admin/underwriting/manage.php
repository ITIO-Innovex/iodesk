<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
	
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#underwriting_modal" onclick="openUnderwritingModal(); return false;"> <i class="fa-regular fa-plus tw-mr-1"></i> Add New Underwriting </a> </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <table class="table dt-table" data-order-col="1" data-order-type="asc">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>For Company</th>
                  <th>Website</th>
                  <th>MDR (%)</th>
                  <th>Setup Fee (USD)</th>
                  <th>Hold Back</th>
                  <th>Card Type</th>
                  <th>Settlement</th>
                  <th>Settlement Fee</th>
                  <th>Min Settlement</th>
                  <th>Monthly Fee (USD)</th>
                  <th>Status</th>
                  <th>Date Added</th>
                  <th><?php echo _l('options'); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($underwritings)) { ?>
                <?php foreach ($underwritings as $u) { ?>
                <tr>
                  <td><?php echo (int) $u['id']; ?></td>
                  <td><?php echo htmlspecialchars($u['for_company'] ?? '', ENT_QUOTES); ?></td>
                  <td>
                    <?php if (!empty($u['for_web_link'])) {
                        $url = htmlspecialchars($u['web_link'], ENT_QUOTES);
                        echo '<a href="' . $url . '" target="_blank" rel="noopener noreferrer">' . $url . '</a>';
                    } ?>
                  </td>
                  <td><?php echo htmlspecialchars($u['MDR']); ?></td>
                  <td><?php echo htmlspecialchars($u['SetupFee']); ?></td>
                  <td><?php echo htmlspecialchars($u['HoldBack']); ?></td>
                  <td><?php echo htmlspecialchars($u['CardType']); ?></td>
                  <td><?php echo htmlspecialchars($u['Settlement']); ?></td>
                  <td><?php echo htmlspecialchars($u['SettlementFee']); ?>%</td>
                  <td><?php echo htmlspecialchars($u['MinSettlement']); ?>K</td>
                  <td><?php echo htmlspecialchars($u['MonthlyFee']); ?></td>
                  <td><?php
                                                    $statusLabel = 'Pending';
                                                    $statusClass = 'label-warning';
                                                    if ((int)$u['status'] === 1) {
                                                        $statusLabel = 'Approved';
                                                        $statusClass = 'label-success';
                                                    } elseif ((int)$u['status'] === 3) {
                                                        $statusLabel = 'Rejected';
                                                        $statusClass = 'label-danger';
                                                    }
                                                    ?>
                    <span class="label <?php echo $statusClass; ?>"> <?php echo $statusLabel; ?> </span> </td>
                  <td><?php echo date("d-m-Y",strtotime($u['dateadded'])); ?></td>
                  <td>
				  
				    <button type="button"
                                                        class="btn btn-default btn-xs"
                                                        data-toggle="modal"
                                                        data-target="#view_underwriting_modal"
                                                        data-id="<?php echo (int) $u['id']; ?>"
                                                        data-for_company="<?php echo htmlspecialchars($u['for_company'] ?? '', ENT_QUOTES); ?>"
                                                        data-web_link="<?php echo htmlspecialchars($u['web_link'] ?? '', ENT_QUOTES); ?>"
                                                        data-mdr="<?php echo htmlspecialchars($u['MDR'], ENT_QUOTES); ?>"
                                                        data-setupfee="<?php echo htmlspecialchars($u['SetupFee'], ENT_QUOTES); ?>"
                                                        data-holdback="<?php echo htmlspecialchars($u['HoldBack'], ENT_QUOTES); ?>"
                                                        data-cardtype="<?php echo htmlspecialchars($u['CardType'], ENT_QUOTES); ?>"
                                                        data-settlement="<?php echo htmlspecialchars($u['Settlement'], ENT_QUOTES); ?>"
                                                        data-settlementfee="<?php echo htmlspecialchars($u['SettlementFee'], ENT_QUOTES); ?>"
                                                        data-minsettlement="<?php echo htmlspecialchars($u['MinSettlement'], ENT_QUOTES); ?>"
                                                        data-monthlyfee="<?php echo htmlspecialchars($u['MonthlyFee'], ENT_QUOTES); ?>"
                                                        data-descriptor="<?php echo htmlspecialchars($u['Descriptor'], ENT_QUOTES); ?>"
														data-remarks="<?php echo htmlspecialchars($u['Remarks']??'', ENT_QUOTES); ?>"
                                                        
														data-cc_email="<?php echo htmlspecialchars($u['cc_email'] ?? '', ENT_QUOTES); ?>"
                                                        data-status="<?php echo (int) $u['status']; ?>"
                                                        data-reason="<?php echo htmlspecialchars($u['Reason'] ?? '', ENT_QUOTES); ?>"> <i class="fa fa-eye"></i> </button>
														
					<?php if(staff_can('adder', 'under_writing')){ ?>								
                    <button type="button"
                                                        class="btn btn-default btn-xs"
                                                        data-toggle="modal"
                                                        data-target="#underwriting_modal"
                                                        data-id="<?php echo (int) $u['id']; ?>"
                                                        data-for_company="<?php echo htmlspecialchars($u['for_company'] ?? '', ENT_QUOTES); ?>"
                                                        data-web_link="<?php echo htmlspecialchars($u['web_link'] ?? '', ENT_QUOTES); ?>"
                                                        data-mdr="<?php echo htmlspecialchars($u['MDR'], ENT_QUOTES); ?>"
                                                        data-setupfee="<?php echo htmlspecialchars($u['SetupFee'], ENT_QUOTES); ?>"
                                                        data-holdback="<?php echo htmlspecialchars($u['HoldBack'], ENT_QUOTES); ?>"
                                                        data-cardtype="<?php echo htmlspecialchars($u['CardType'], ENT_QUOTES); ?>"
                                                        data-settlement="<?php echo htmlspecialchars($u['Settlement'], ENT_QUOTES); ?>"
                                                        data-settlementfee="<?php echo htmlspecialchars($u['SettlementFee'], ENT_QUOTES); ?>"
                                                        data-minsettlement="<?php echo htmlspecialchars($u['MinSettlement'], ENT_QUOTES); ?>"
                                                        data-monthlyfee="<?php echo htmlspecialchars($u['MonthlyFee'], ENT_QUOTES); ?>"
                                                        data-descriptor="<?php echo htmlspecialchars($u['Descriptor'], ENT_QUOTES); ?>"
														data-remarks="<?php echo htmlspecialchars($u['Remarks'] ?? '', ENT_QUOTES); ?>"
                                                        data-cc_email="<?php echo htmlspecialchars($u['cc_email'] ?? '', ENT_QUOTES); ?>"
                                                        data-status="<?php echo (int) $u['status']; ?>"
                                                        data-reason="<?php echo htmlspecialchars($u['Reason'] ?? '', ENT_QUOTES); ?>"> <i class="fa fa-pencil"></i> </button>
                    <a href="<?php echo admin_url('underwriting/delete/' . (int) $u['id']); ?>"
                                                       class="btn btn-danger btn-xs"
                                                       onclick="return confirm('Are you sure you want to delete this record?');"> <i class="fa fa-trash"></i> </a> 
					<?php } ?>								   
													   </td>
                </tr>
                <?php } ?>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Add/Edit Modal -->
<div class="modal fade" id="underwriting_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog " role="document">
    <div class="modal-content"> <?php echo form_open(admin_url('underwriting/save'), ['id' => 'underwriting-form']); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title" id="underwriting_modal_label">Add Underwriting</h4>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="uw_id" value="">
        <div class="row">
		
		   <div class="col-md-12">
            <div class="form-group">
              <label for="for_company" class="control-label">For Company</label>
              <input type="text" name="for_company" id="for_company" class="form-control" required>
            </div>
          </div>
		   <div class="col-md-12">
            <div class="form-group">
              <label for="web_link" class="control-label">Website</label>
              <input type="text" name="web_link" id="web_link" class="form-control" placeholder="https://example.com">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_MDR" class="control-label">MDR (%)</label>
              <input type="text" name="MDR" id="uw_MDR" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_SetupFee" class="control-label">Setup Fee (USD)</label>
              <input type="text" name="SetupFee" id="uw_SetupFee" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_HoldBack" class="control-label">Hold Back</label>
              <input type="text" name="HoldBack" id="uw_HoldBack" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_CardType" class="control-label">Card Type</label>
              <select name="CardType[]" id="uw_CardType" class="form-control selectpicker" multiple data-none-selected-text="Select card types">
                <option value="VISA 3ds">VISA 3ds</option>
                <option value="MASTER 3ds">MASTER 3ds</option>
                <option value="AMEX">AMEX</option>
                <option value="JCB">JCB</option>
                <option value="RUPAY">RUPAY</option>
                <option value="DINER">DINER</option>
              </select>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_Settlement" class="control-label">Settlement (No of working days)</label>
              <input type="text" name="Settlement" id="uw_Settlement" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_SettlementFee" class="control-label">Settlement Fee</label>
			  <select name="SettlementFee" id="uw_SettlementFee" class="form-control" required>
			  <option value="">Select Settlement Fee</option>
			  <option value="2">2%</option>
			  <option value="3">3%</option>
			  <option value="4">4%</option>
			  <option value="5">5%</option>
			  </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_MinSettlement" class="control-label">Min Settlement</label>
			  <select name="MinSettlement" id="uw_MinSettlement" class="form-control" required>
			  <option value="">Select Min Settlement</option>
			  <option value="5">5K</option>
			  <option value="10">10K</option>
			  <option value="20">20K</option>
			  </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="uw_MonthlyFee" class="control-label">Monthly Fee (USD)</label>
              <input type="text" name="MonthlyFee" id="uw_MonthlyFee" class="form-control" required>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="uw_Descriptor" class="control-label">Descriptor</label>
              <textarea name="Descriptor" id="uw_Descriptor" rows="3" class="form-control" required></textarea>
            </div>
          </div>
		  
		  <div class="col-md-12">
            <div class="form-group">
              <label for="uw_Remarks" class="control-label">Remarks</label>
              <textarea name="Remarks" id="uw_Remarks" rows="3" class="form-control" required></textarea>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              <label for="uw_cc_email" class="control-label">CC Email</label>
              <input type="text" name="cc_email" id="uw_cc_email" class="form-control" placeholder="example@domain.com, example2@domain.com">
            </div>
          </div>
          <?php /*?><div class="col-md-12">
                        <div class="form-group">
                            <label for="uw_status" class="control-label">Status</label>
                            <select name="status" id="uw_status" class="form-control">
                                <option value="2">Pending</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="uw_Reason" class="control-label">Reason</label>
                            <textarea name="Reason" id="uw_Reason" rows="3" class="form-control"></textarea>
                        </div>
                    </div><?php */?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
      <?php echo form_close(); ?> </div>
  </div>
</div>
<!-- View Modal -->
<div class="modal fade" id="view_underwriting_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        <h4 class="modal-title">View Underwriting</h4>
      </div>
      <div class="modal-body">
        <div class="row">
<div class="panel-body panel-table-full">
<table class="table dt-table" data-order-col="1" data-order-type="asc">
<tr><td><strong>For Company:</strong></td><td><span id="view_for_company"></span></td></tr>
<tr><td><strong>Website:</strong></td><td><span id="view_web_link"></span></td></tr>
<tr><td><strong>MDR:</strong></td><td><span id="view_MDR"></span></td></tr>
<tr><td><strong>Setup Fee:</strong></td><td><span id="view_SetupFee"></span></td></tr>
<tr><td><strong>Hold Back:</strong></td><td><span id="view_HoldBack"></span></td></tr>
<tr><td><strong>Card Type:</strong></td><td><span id="view_CardType"></span></td></tr>
<tr><td><strong>Settlement:</strong></td><td><span id="view_Settlement"></span></td></tr>
<tr><td><strong>Settlement Fee:</strong></td><td><span id="view_SettlementFee"></span></td></tr>

<tr><td><strong>Min Settlement:</strong></td><td><span id="view_MinSettlement"></span></td></tr>
<tr><td><strong>Monthly Fee:</strong></td><td><span id="view_MonthlyFee"></span></td></tr>
<tr><td><strong>Descriptor:</strong></td><td><span id="view_Descriptor"></span></td></tr>
<tr><td><strong>Remarks:</strong></td><td><span id="view_Remarks"></span></td></tr>
<?php /*?><tr><td><strong>CC Email:</strong></td><td><span id="view_cc_email"></span></td></tr>
<?php */?><tr><td><strong>Status:</strong></td><td><span id="view_status"></span></td></tr>
<tr><td><strong>Reason:</strong></td><td><span id="view_Reason"></span></td></tr>
</table>

</div>

<?php if(staff_can('approver', 'under_writing')){ ?>
<hr />
<div class="row tw-mx-2"><div class="panel-body panel-table-full">
<h4 class="">Underwriting Approval</h4>
<?php echo form_open(admin_url('underwriting/approve'), ['id' => 'underwriting-approval-form']); ?>
<input type="hidden" name="id" id="uw_idx" value="">
                        <div class="col-md-12">
                        <div class="form-group">
                            <label for="uw_status" class="control-label">Status</label>
                            <select  name="uw_status" id="uw_statusx" class="form-control">
                                <option value="2">Pending</option>
                                <option value="1">Approve</option>
                                <option value="3">Reject</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="uw_Reason" class="control-label">Reason</label>
                            <textarea name="Reason" id="uw_Reason" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
					<div class="col-md-12">
                        <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
					</div></div>
<?php echo form_close(); ?> </div></div></div>
<?php } ?>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
    function openUnderwritingModal() {
        $('#underwriting_modal_label').text('Add Underwriting');
        $('#uw_id').val('');
        $('#for_company').val('');
        $('#web_link').val('');
        $('#uw_MDR').val('');
        $('#uw_SetupFee').val('');
        $('#uw_HoldBack').val('');
        $('#uw_CardType').val([]).change();
        $('#uw_Settlement').val('');
        $('#uw_SettlementFee').val('');
        $('#uw_MinSettlement').val('');
        $('#uw_MonthlyFee').val('');
        $('#uw_Descriptor').val('');
		$('#uw_Remarks').val('');
        $('#uw_cc_email').val('');
        $('#uw_status').val('2');
        $('#uw_Reason').val('');
    }

    $('#underwriting_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        if (!button || !button.data('id')) {
            return;
        }

        $('#underwriting_modal_label').text('Edit Underwriting');
        $('#uw_id').val(button.data('id'));
        $('#for_company').val(button.data('for_company'));
        $('#web_link').val(button.data('web_link'));
        $('#uw_MDR').val(button.data('mdr'));
        $('#uw_SetupFee').val(button.data('setupfee'));
        $('#uw_HoldBack').val(button.data('holdback'));
        var cardTypes = (button.data('cardtype') || '').split(',');
        cardTypes = cardTypes.map(function (c) { return $.trim(c); }).filter(function (c) { return c.length; });
        $('#uw_CardType').val(cardTypes).change();
        $('#uw_Settlement').val(button.data('settlement'));
        $('#uw_SettlementFee').val(button.data('settlementfee'));
        $('#uw_MinSettlement').val(button.data('minsettlement'));
        $('#uw_MonthlyFee').val(button.data('monthlyfee'));
        $('#uw_Descriptor').val(button.data('descriptor'));
		$('#uw_Remarks').val(button.data('remarks'));
        $('#uw_cc_email').val(button.data('cc_email'));
        $('#uw_status').val(button.data('status'));
        $('#uw_Reason').val(button.data('reason'));
    });

    $('#view_underwriting_modal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        $('#view_for_company').text(button.data('for_company') || '');
        $('#view_web_link').text(button.data('web_link') || '');
        $('#view_MDR').text(button.data('mdr') || '');
        $('#view_SetupFee').text(button.data('setupfee') || '');
        $('#view_HoldBack').text(button.data('holdback') || '');
        $('#view_CardType').text(button.data('cardtype') || '');
        $('#view_Settlement').text(button.data('settlement') || '');
        $('#view_SettlementFee').text(button.data('settlementfee') || '');
        $('#view_MinSettlement').text(button.data('minsettlement') || '');
        $('#view_MonthlyFee').text(button.data('monthlyfee') || '');
        $('#view_Descriptor').text(button.data('descriptor') || '');
		$('#view_Remarks').text(button.data('remarks') || '');
        $('#view_cc_email').text(button.data('cc_email') || '');
		$('#uw_idx').val(button.data('id'));

        var status = parseInt(button.data('status'), 10);
        var statusLabel = 'Pending';
        if (status === 1) {
            statusLabel = 'Approved';
        } else if (status === 3) {
            statusLabel = 'Rejected';
        }
        $('#view_status').text(statusLabel);
        $('#view_Reason').text(button.data('reason') || '');
    });
</script>
</body></html>