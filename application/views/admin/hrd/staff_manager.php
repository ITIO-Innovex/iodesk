<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa-solid fa-users menu-icon"></i>  Staff Management</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            
            <div class="row">
              <div class="col-md-12">
                <table class="table dt-table" data-order-col="1" data-order-type="asc">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Employee Code</th>
                      <th>Email</th>
                      <th>Branch</th>
                      <th>Department</th>
                      <th>Designation</th>
                      <th>Phone</th>
                      <th>Joining Date</th>
                      <th><?php echo _l('options'); ?></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($staff_rows)) { $i=1; foreach ($staff_rows as $row) { ?>
                      <tr>
                        <td><?php echo (int)$i++; ?></td>
                        <td><?php echo e($row['firstname'] ?? ''); ?> <?php echo e($row['lastname'] ?? ''); ?></td>
                        <td><?php echo e($row['employee_code'] ?? ''); ?></td>
                        <td><?php echo e($row['email'] ?? ''); ?></td>
                        <td><?php echo e($row['branch_name'] ?? ''); ?></td>
                        <td><?php echo e($row['department'] ?? ''); ?></td>
                        <td><?php echo e($row['designation'] ?? ''); ?></td>
                        <td><?php echo e($row['phonenumber'] ?? ''); ?></td>
                        <td><?php echo isset($row['joining_date']) ? $row['joining_date'] : ''; ?></td>
                        <td>
                          <a href="#" class="btn btn-default btn-sm" onclick='openStaffModal(<?php echo json_encode([
                            'staffid' => (int)($row['staffid']??0),
                            'title' => (string)($row['title'] ?? ''),
                            'firstname' => (string)($row['firstname'] ?? ''),
                            'lastname' => (string)($row['lastname'] ?? ''),
                            'employee_code' => (string)($row['employee_code'] ?? ''),
                            'branch' => (string)($row['branch'] ?? ''),
                            'department' => (string)($row['department_id'] ?? ''),
                            'designation' => (string)($row['designation_id'] ?? ''),
                            'phonenumber' => (string)($row['phonenumber'] ?? ''),
                            'joining_date' => (string)($row['joining_date'] ?? ''),
                            'dob' => (string)($row['dob'] ?? ''),
                            'gender' => (string)($row['gender'] ?? ''),
                          ]); ?>); return false;'>Edit</a>
                        </td>
                      </tr>
                    <?php } } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Staff Edit Modal -->
<div class="modal fade" id="staff_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <?php echo form_open(admin_url('hrd/staffentry'), ['id' => 'staff-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Staff : <span id="sidx"></span></h4>
      </div>
      <div class="modal-body">
        <div id="additional"></div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Title</label>
            <select name="title" class="form-control">
              <option value="">-- Select --</option>
              <option value="MR">MR</option>
              <option value="MRS">MRS</option>
              <option value="Other">Other</option>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group"><label>First Name</label><input type="text" name="firstname" class="form-control" required></div></div>
          <div class="col-md-4"><div class="form-group"><label>Last Name</label><input type="text" name="lastname" class="form-control" required></div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Employee Code</label><input type="text" name="employee_code" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Phone</label><input type="text" name="phonenumber" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Gender</label>
            <select name="gender" class="form-control">
              <option value="">-- Select --</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div></div>
        </div>
        <div class="row">
          <div class="col-md-4"><div class="form-group"><label>Branch</label>
            <select name="branch" class="form-control">
              <option value="">-- Select Branch --</option>
              <?php if (!empty($branches)) { foreach ($branches as $b) { ?>
                <option value="<?php echo (int)$b['id']; ?>"><?php echo e($b['branch_name']); ?></option>
              <?php } } ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group"><label>Department</label>
            <select name="department" class="form-control">
              <option value="">-- Select Department --</option>
              <?php if (!empty($departments)) { foreach ($departments as $d) { ?>
                <option value="<?php echo (int)$d['departmentid']; ?>"><?php echo e($d['name']); ?></option>
              <?php } } ?>
            </select>
          </div></div>
		  <div class="col-md-4"><div class="form-group"><label>Designation</label>
            <select name="designation" class="form-control">
              <option value="">-- Select Designation --</option>
              <?php if (!empty($designations)) { foreach ($designations as $ds) { ?>
                <option value="<?php echo (int)$ds['id']; ?>"><?php echo e($ds['title']); ?></option>
              <?php } } ?>
            </select>
          </div></div>
        </div>
        <div class="row">
          
          <div class="col-md-6"><div class="form-group"><label>Date of Birth</label><input type="date" name="dob" class="form-control"></div></div>
		  <div class="col-md-6"><div class="form-group"><label>Joining Date</label><input type="date" name="joining_date" class="form-control"></div></div>
        </div>
        <div class="row">
          
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
<script>
function loadDesignations(depId, done){
  var $des = $("select[name=designation]");
  $des.html('<option value="">Loading...</option>');
  $.get(admin_url + 'hrd/designations_by_department', {department_id: depId}, function(resp){
  
    var items = [];
    if (Array.isArray(resp)) { items = resp; }
    else if (resp && resp.success && Array.isArray(resp.data)) { items = resp.data; }

    var opts = '<option value="">-- Select Designation --</option>';
    for (var i=0;i<items.length;i++){
      var r = items[i] || {};
      opts += '<option value="'+(r.id||'')+'">'+(r.title||'')+'</option>';
    }
    $des.html(opts);
    if ($des.hasClass('selectpicker')) { $des.selectpicker('refresh'); }
    if (typeof done === 'function') done();
  }, 'json');
}

function openStaffModal(data){
  var $m = $('#staff_modal');
  var $f = $m.find('form');
  $f.find('#additional').html('<input type="hidden" name="staffid" value="'+(data.staffid||'')+'"/>');
  //$f.find('input[name=title]').val(data.title||'');
  $('#sidx').text(data.firstname + ' ' + data.lastname + ' (' + data.staffid + ') ');
  $f.find('select[name=title]').val(data.title||'');
  $f.find('input[name=firstname]').val(data.firstname||'');
  $f.find('input[name=lastname]').val(data.lastname||'');
  $f.find('input[name=employee_code]').val(data.employee_code||'');
  $f.find('input[name=phonenumber]').val(data.phonenumber||'');
  $f.find('select[name=branch]').val(data.branch||'');
  $f.find('select[name=department]').val(data.department||'');
  loadDesignations(data.department||'', function(){
    $f.find('select[name=designation]').val(data.designation||'');
    var $des = $f.find('select[name=designation]');
    if ($des.hasClass('selectpicker')) { $des.selectpicker('refresh'); }
  });
  $f.find('input[name=joining_date]').val((data.joining_date||'').substring(0,10));
  $f.find('input[name=dob]').val((data.dob||'').substring(0,10));
  $f.find('select[name=gender]').val(data.gender||'');
  $m.modal('show');
}

</script>
<?php init_tail(); ?>
<script>
$(function(){
  appValidateForm($("#staff-form"), {
    firstname: 'required',
    lastname: 'required'
  }, function(form){
    var data = $(form).serialize();
    $.ajax({
      url: form.action,
      method: 'POST',
      data: data,
      dataType: 'json'
    }).done(function(resp){
      // On JSON success
      if(resp && resp.success){ window.location.href = admin_url + 'hrd/staff_manager'; return; }
      // Fallback
      window.location.href = admin_url + 'hrd/staff_manager';
    }).fail(function(){
      // In case of HTML redirect response or parse error, just navigate
      window.location.href = admin_url + 'hrd/staff_manager';
    });
    return false;
  });
  // Dependent designation loading
  $("select[name=department]").on('change', function(){ 
    var dep = $(this).val();
    if(!dep){
      $("select[name=designation]").html('<option value="">-- Select Designation --</option>');
      return;
    }
	
    loadDesignations(dep);
  });
});
</script>
</body></html>


