<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg"><i class="fa-brands fa-wpforms tw-mr-2"></i>Joining Form</h4>
			<hr class="hr-panel-heading">
            <?php echo form_open(admin_url('hrd/joining_form'), ['id' => 'joining-form']); ?>
              <input type="hidden" name="status" id="joining-status" value="<?php echo e($form['status'] ?? 'Draft'); ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="name" class="control-label">Name : <small class="req text-danger">* </small></label>
                    <input type="text" name="name" class="form-control" value="<?php echo e($form['name'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
					<label for="father_husband_name" class="control-label">Father/Husband Name : <small class="req text-danger">* </small></label>
                    <input type="text" name="father_husband_name" class="form-control" value="<?php echo e($form['father_husband_name'] ?? ''); ?>">
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
                    <input type="text" name="pan_number" class="form-control" maxlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}" value="<?php echo e($form['pan_number'] ?? ''); ?>" >
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhaar Number : <small class="req text-danger">* </small></label>
                    <input type="text" name="aadhaar_number" class="form-control" maxlength="12" pattern="\d{12}" inputmode="numeric" value="<?php echo e($form['aadhaar_number'] ?? ''); ?>">
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
<?php /*?><div class="col-md-12"><h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">ADDRESS</h4></div>
<?php */?><div class="col-md-12"><h4 class="">Current Address</h4></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 1 : <small class="req text-danger">* </small></label>
                    <input type="text" name="current_address_line1" class="form-control" value="<?php echo e($form['current_address_line1'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 2 : </label>
                    <input type="text" name="current_address_line2" class="form-control" value="<?php echo e($form['current_address_line2'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 3 : </label>
                    <input type="text" name="current_address_line3" class="form-control" value="<?php echo e($form['current_address_line3'] ?? ''); ?>">
                  </div>
                </div>
<div class="col-md-12"><h4 class="">Permanent Address</h4></div>
				<div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 1 : <small class="req text-danger">* </small></label>
                    <input type="text" name="permanent_address_line1" class="form-control" value="<?php echo e($form['permanent_address_line1'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 2 : </label>
                    <input type="text" name="permanent_address_line2" class="form-control" value="<?php echo e($form['permanent_address_line2'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 3 : </label>
                    <input type="text" name="permanent_address_line3" class="form-control" value="<?php echo e($form['permanent_address_line3'] ?? ''); ?>">
                  </div>
                </div>
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
      $('#joining-status').val(status);
      $('#joining-form').submit();
    });
  });
</script>
</body></html>
