<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg"><i class="fa-solid fa-user-check tw-mr-2"></i>Employee Details Form</h4>
			<hr class="hr-panel-heading">
            <?php echo form_open_multipart(admin_url('hrd/employee_details_form'), ['id' => 'employee-details-form']); ?>
              <input type="hidden" name="status" id="employee-details-status" value="<?php echo e($form['status'] ?? 'Draft'); ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="name" class="control-label">Name : <small class="req text-danger">* </small></label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($form['name'] ?? ''); ?>" required>
                  </div>
                </div>
                
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Contact Number : <small class="req text-danger">* </small></label>
                    <input type="text" name="contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['contact_number'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Emergency Contact Number : <small class="req text-danger">* </small></label>
                    <input type="text" name="emergency_contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['emergency_contact_number'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Email : <small class="req text-danger">* </small></label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($form['email'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>PAN Number : <small class="req text-danger">* </small></label>
                    <input type="text" name="pan_number" class="form-control" maxlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}" value="<?php echo e($form['pan_number'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhaar Number : <small class="req text-danger">* </small></label>
                    <input type="text" name="aadhaar_number" class="form-control" maxlength="12" pattern="\d{12}" inputmode="numeric" value="<?php echo e($form['aadhaar_number'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Date of Birth : <small class="req text-danger">* </small></label>
                    <input type="date" name="date_of_birth" class="form-control" value="<?php echo e($form['date_of_birth'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Assigned Designation : <small class="req text-danger">* </small></label>
                    <input type="text" name="assigned_designation" class="form-control" value="<?php echo e($form['assigned_designation'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Department : <small class="req text-danger">* </small></label>
                    <input type="text" name="department" class="form-control" value="<?php echo e($form['department'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Date of Joining : <small class="req text-danger">* </small></label>
                    <input type="date" name="date_of_joining" class="form-control" value="<?php echo e($form['date_of_joining'] ?? ''); ?>" required>
                  </div>
                </div>
              </div>
              </div>
              </div>
              <hr>
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Address</h4></div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Current Address : <small class="req text-danger">* </small></label>
                    <textarea name="current_address" class="form-control" rows="3" required><?php echo e($form['current_address'] ?? ''); ?></textarea>
                  </div>
                </div>
				<div class="col-md-12">
                  <div class="form-group">
                    <label>Permanent Address : <small class="req text-danger">* </small></label>
                    <textarea name="permanent_address" class="form-control" rows="3" required><?php echo e($form['permanent_address'] ?? ''); ?></textarea>
                  </div>
                </div>
              </div>
              </div>
              <hr>

              <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Documents</h4></div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Educational Testimonials : <small class="req text-danger">* </small></label>
                    
                    <input type="file" name="educational_testimonials" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <?php if (!empty($form['educational_testimonials'])) { ?>
                      <a href="<?php echo base_url($form['educational_testimonials']); ?>" target="_blank" class="btn btn-sm btn-info">View File</a>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>ID Proof : <small class="req text-danger">* </small></label>
                    
                    <input type="file" name="id_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if (!empty($form['id_proof'])) { ?>
                      <a href="<?php echo base_url($form['id_proof']); ?>" target="_blank" class="btn btn-sm btn-info">View File</a>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Address Proof : <small class="req text-danger">* </small></label>
                    <input type="file" name="address_proof" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <?php if (!empty($form['address_proof'])) { ?>
                      <a href="<?php echo base_url($form['address_proof']); ?>" target="_blank" class="btn btn-sm btn-info">View File</a>
                    <?php } ?>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Previous Company Documents : <small class="req text-danger">* </small></label>
                    
                    <input type="file" name="previous_company_documents" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                    <?php if (!empty($form['previous_company_documents'])) { ?>
                      <a href="<?php echo base_url($form['previous_company_documents']); ?>" target="_blank" class="btn btn-sm btn-info">View File</a>
                    <?php } ?>
                  </div>
                </div>
              </div>
              </div>
              <hr>

              <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">PLEASE MENTION BELOW FIVE(MINIMUM) REFERENCES ALONG WITH REQUIRED DETAILS :</h4></div>
                <?php for ($i = 1; $i <= 8; $i++): 
				$req="required";
				$star="*";
				if($i >5){ $req="";$star="";}
				?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Reference <?php echo $i; ?> Name : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="ref<?php echo $i; ?>_name" class="form-control" value="<?php echo e($form['ref' . $i . '_name'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Reference <?php echo $i; ?> Relation : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="ref<?php echo $i; ?>_relation" class="form-control" value="<?php echo e($form['ref' . $i . '_relation'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Reference <?php echo $i; ?> Contact : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="ref<?php echo $i; ?>_contact" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['ref' . $i . '_contact'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <?php if ($i % 2 == 0): ?>
                </div><div class="row">
                <?php endif; ?>
                <?php endfor; ?>
              </div>
              </div>
              <hr>
			  

              <div class="tw-flex tw-gap-2">
                <button type="button" class="btn btn-primary" data-status="Submitted" id="save-submit">Submit</button>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  $(function() {
    $('#save-submit').on('click', function() {
      var status = $(this).data('status');
      $('#employee-details-status').val(status);
      $('#employee-details-form').submit();
    });
  });
</script>
</body></html>
