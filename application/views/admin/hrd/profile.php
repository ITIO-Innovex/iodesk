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
                    $img = isset($me['profile_image']) && $me['profile_image'] ? base_url($me['profile_image']) : base_url('assets/images/not-user.jpg');
                  ?>
                  <img src="<?php echo $img; ?>" class="img img-responsive img-thumbnail" alt="Profile" />
                  <div class="mtop10">
                    <?php echo form_open_multipart(admin_url('hrd/profile_image_update'), ['id'=>'img-form']); ?>
                      <input type="file" name="profile_image" class="form-control" required>
                      <button type="submit" class="btn btn-default btn-sm mtop10">Update Image</button>
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
                  <div class="col-md-4"><div class="form-group"><label>Mobile</label><input type="text" class="form-control" value="<?php echo e($me['mobile']??''); ?>" readonly></div></div>
                  <div class="col-md-4"><div class="form-group"><label>Aadhar</label><input type="text" class="form-control" value="<?php echo e($me['aadhar']??''); ?>" readonly></div></div>
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
          <div class="col-md-6"><div class="form-group"><label>Mobile</label><input type="text" name="mobile" class="form-control" value="<?php echo e($me['mobile']??''); ?>"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Aadhar</label><input type="text" name="aadhar" class="form-control" value="<?php echo e($me['aadhar']??''); ?>"></div></div>
          <div class="col-md-6"><div class="form-group"><label>PAN</label><input type="text" name="pan" class="form-control" value="<?php echo e($me['pan']??''); ?>"></div></div>
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
  $('#personal-form').on('submit', function(e){ e.preventDefault(); var f=this; $.post(f.action, $(f).serialize(), function(resp){ if(resp&&resp.success){ location.reload(); } else { alert('Failed'); } }, 'json'); });
  $('#social-form').on('submit', function(e){ e.preventDefault(); var f=this; $.post(f.action, $(f).serialize(), function(resp){ if(resp&&resp.success){ location.reload(); } else { alert('Failed'); } }, 'json'); });
  $('#img-form').on('submit', function(e){ e.preventDefault(); var f=this; var fd=new FormData(f); $.ajax({url:f.action,method:'POST',data:fd,contentType:false,processData:false,dataType:'json'}).done(function(resp){ if(resp&&resp.success){ location.reload(); } else { alert('Failed to upload'); } }); });
});
</script>
</body></html>


