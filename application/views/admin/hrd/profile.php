<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">My Profile</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-3">
                <div class="text-center">
                  <?php 
                    $hasProfileImage = !empty($me['profile_image']);
                    $img = $hasProfileImage ? base_url($me['profile_image']) : base_url('assets/images/user-placeholder.jpg');
                  ?>
                  <img src="<?php echo $img; ?>" class="img img-responsive img-thumbnail" alt="Profile" />
                  <div class="mtop10">
                    <?php echo form_open_multipart(admin_url('hrd/profile_image_update'), ['id'=>'img-form']); ?>
                      <input type="file" name="profile_image" class="form-control" required>
                      <button type="submit" class="btn btn-default btn-sm mtop10"><i class="fa-solid fa-pen-to-square"></i> Update Image</button>
					  <?php if ($hasProfileImage) { ?>
                      <button type="button" class="btn btn-danger btn-sm mtop10" id="delete-profile-image" data-url="<?php echo admin_url('hrd/profile_image_delete'); ?>"><i class="fa-solid fa-trash-can"></i> Delete Image</button>
                    <?php } ?>
                    <?php echo form_close(); ?>
                    
                  </div>
                </div>
              </div>
              <div class="col-md-9">
                <h5 class="tw-font-semibold">Staff Details</h5>
                <div class="row">
                  <div class="col-md-6"><div class="form-group"><label>Name</label><input type="text" class="form-control" value="<?php echo e(($me['firstname']??'').' '.($me['lastname']??'')); ?>" readonly></div></div>
                  <div class="col-md-6"><div class="form-group"><label>Email</label><input type="text" class="form-control" value="<?php echo e($me['email']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Employee Code</label><input type="text" class="form-control" value="<?php echo e($me['employee_code']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Branch</label><input type="text" class="form-control" value="<?php echo e($branchName); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Department</label><input type="text" class="form-control" value="<?php echo e($deptName); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Designation</label><input type="text" class="form-control" value="<?php echo e($desigName); ?>" readonly></div></div>
                </div>
              </div>
            </div>

            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
                  <h5 class="tw-font-semibold">Personal Details</h5>
                  <a href="#" class="btn btn-default btn-sm" onclick="$('#personal_modal').modal('show');return false;">Edit</a>
                </div>
                <div class="row">
                  <div class="col-md-4"><div class="form-group"><label>Father Name</label><input type="text" class="form-control" value="<?php echo e($me['father_name']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Personal Email</label><input type="text" class="form-control" value="<?php echo e($me['email_personal']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Mobile</label><input type="number" class="form-control" value="<?php echo e($me['mobile']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Aadhar</label><input type="number" class="form-control" value="<?php echo e($me['aadhar']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>PAN</label><input type="text" class="form-control" value="<?php echo e($me['pan']??''); ?>" readonly></div></div>
                </div>
              </div>
            </div>

            <hr>
            <div class="row">
              <div class="col-md-12">
                <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
                  <h5 class="tw-font-semibold">Social Media</h5>
                  <a href="#" class="btn btn-default btn-sm" onclick="$('#social_modal').modal('show');return false;">Edit</a>
                </div>
                <div class="row">
                  <div class="col-md-4"><div class="form-group"><label>LinkedIn</label><input type="text" class="form-control" value="<?php echo e($me['linkedin']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Facebook</label><input type="text" class="form-control" value="<?php echo e($me['facebook']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Twitter</label><input type="text" class="form-control" value="<?php echo e($me['twitter']??''); ?>" readonly></div></div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Personal Modal -->
<div class="modal fade" id="personal_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/profile_update_personal'), ['id'=>'personal-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Personal Details</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Father Name</label><input type="text" name="father_name" class="form-control" value="<?php echo e($me['father_name']??''); ?>"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Personal Email</label><input type="email" name="email_personal" class="form-control" value="<?php echo e($me['email_personal']??''); ?>"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Mobile</label><input type="text" name="mobile" class="form-control" value="<?php echo e($me['mobile']??''); ?>" maxlength="10" pattern="\d{10}" inputmode="numeric" title="Enter 10 digit mobile number"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Aadhar</label><input type="text" name="aadhar" class="form-control" value="<?php echo e($me['aadhar']??''); ?>" maxlength="12" pattern="\d{12}" inputmode="numeric" title="Enter 12 digit Aadhar number"></div></div>
          <div class="col-md-6"><div class="form-group"><label>PAN</label><input type="text" name="pan" class="form-control" value="<?php echo e($me['pan']??''); ?>" maxlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}" title="Format: ABCDE1234F"></div></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<!-- Social Modal -->
<div class="modal fade" id="social_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open(admin_url('hrd/profile_update_social'), ['id'=>'social-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Social Links</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12"><div class="form-group"><label>LinkedIn</label><input type="url" name="linkedin" class="form-control" value="<?php echo e($me['linkedin']??''); ?>"></div></div>
          <div class="col-md-12"><div class="form-group"><label>Facebook</label><input type="url" name="facebook" class="form-control" value="<?php echo e($me['facebook']??''); ?>"></div></div>
          <div class="col-md-12"><div class="form-group"><label>Twitter</label><input type="url" name="twitter" class="form-control" value="<?php echo e($me['twitter']??''); ?>"></div></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(function(){
  $('#personal-form').on('submit', function(e){
    e.preventDefault();
    var $f = $(this);
    var mobile = ($f.find('[name="mobile"]').val() || '').replace(/\D+/g, '');
    var aadhar = ($f.find('[name="aadhar"]').val() || '').replace(/\D+/g, '');
    var pan = ($f.find('[name="pan"]').val() || '').trim().toUpperCase();
    if (mobile && !/^\d{10}$/.test(mobile)) { alert('Invalid mobile number'); return; }
    if (aadhar && !/^\d{12}$/.test(aadhar)) { alert('Invalid Aadhar number'); return; }
    if (pan && !/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(pan)) { alert('Invalid PAN number'); return; }
    $f.find('[name="mobile"]').val(mobile);
    $f.find('[name="aadhar"]').val(aadhar);
    $f.find('[name="pan"]').val(pan);
    $.post(this.action, $f.serialize(), function(resp){ if(resp&&resp.success){ location.reload(); } else { alert(resp && resp.message ? resp.message : 'Failed'); } }, 'json');
  });
  $('#social-form').on('submit', function(e){ e.preventDefault(); var f=this; $.post(f.action, $(f).serialize(), function(resp){ if(resp&&resp.success){ location.reload(); } else { alert('Failed'); } }, 'json'); });
  $('#img-form').on('submit', function(e){ e.preventDefault(); var f=this; var fd=new FormData(f); $.ajax({url:f.action,method:'POST',data:fd,contentType:false,processData:false,dataType:'json'}).done(function(resp){ if(resp&&resp.success){ location.reload(); } else { alert('Failed to upload'); } }); });
  $('#delete-profile-image').on('click', function(e){
    e.preventDefault();
    if (!confirm('Delete your profile image?')) { return; }
    var url = $(this).data('url');
    $.post(url, function(resp){
      if(resp && resp.success){ location.reload(); } else { alert('Failed to delete'); }
    }, 'json');
  });
});
</script>
</body></html>


