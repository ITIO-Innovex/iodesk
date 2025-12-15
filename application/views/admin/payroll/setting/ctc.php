<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700"><i class="fa-solid fa-users menu-icon"></i> Payroll CTC</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-full">
            <div class="row">
              <div class="col-md-12">
                <table class="table dt-table" data-order-col="0" data-order-type="asc">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Employee Code</th>
                      <th>Email</th>
                      <th>Branch</th>
                      <th>Department</th>
                      <th>Designation</th>
                      <th>Joining Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($staff_rows)) { $i = 1; foreach ($staff_rows as $row) { ?>
                      <tr>
                        <?php
                          $approver_data = [];
                          if (!empty($row['approver'])) {
                            $approver_data = json_decode($row['approver'], true);
                            if (!is_array($approver_data)) {
                              $approver_data = [];
                            }
                          }
                          $rowPayload = [
                              'staffid' => (int)($row['staffid'] ?? 0),
                              'firstname' => (string)($row['firstname'] ?? ''),
                              'lastname' => (string)($row['lastname'] ?? ''),
                              'employee_code' => (string)($row['employee_code'] ?? ''),
                              'branch' => (string)($row['branch'] ?? ''),
                              'department' => (string)($row['department_id'] ?? ''),
                              'designation' => (string)($row['designation_id'] ?? ''),
                              'staff_type' => (string)($row['staff_type'] ?? ''),
                              'phonenumber' => (string)($row['phonenumber'] ?? ''),
                              'joining_date' => (string)($row['joining_date'] ?? ''),
                              'dob' => (string)($row['dob'] ?? ''),
                              'gender' => (string)($row['gender'] ?? ''),
                              'hr_approver' => isset($approver_data['hr_approver']) ? (string)$approver_data['hr_approver'] : '',
                              'admin_approver' => isset($approver_data['admin_approver']) ? (string)$approver_data['admin_approver'] : '',
                              'hr_manager_approver' => isset($approver_data['hr_manager_approver']) ? (string)$approver_data['hr_manager_approver'] : '',
                              'reporting_approver' => isset($approver_data['reporting_approver']) ? (string)$approver_data['reporting_approver'] : '',
                              'employee_status' => (string)($row['employee_status'] ?? ''),
                              'reporting_manager' => (string)($row['reporting_manager'] ?? ''),
                          ];
                        ?>
                        <td><?php echo e($row['firstname'] ?? ''); ?> <?php echo e($row['lastname'] ?? ''); ?></td>
                        <td><?php echo e($row['employee_code'] ?? ''); ?></td>
                        <td><?php echo e($row['email'] ?? ''); ?></td>
                        <td><?php echo e($row['branch_name'] ?? ''); ?></td>
                        <td><?php echo e($row['department'] ?? ''); ?></td>
                        <td><?php echo e($row['designation'] ?? ''); ?></td>
                        <td><?php echo isset($row['joining_date']) ? $row['joining_date'] : ''; ?></td>
                        <td>
                          <div class="btn-group" role="group">
                            <button type="button" class="btn btn-default btn-xs" onclick='openStaffModal(<?php echo json_encode($rowPayload); ?>); return false;'>
                              <i class="fa-solid fa-user-pen"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-xs btn-ctc" data-staff='<?php echo json_encode([
                              'staffid' => (int)($row['staffid'] ?? 0),
                              'name' => trim(($row['firstname'] ?? '') . ' ' . ($row['lastname'] ?? '')),
                            ]); ?>'>CTC</button>
                            <a href="<?php echo admin_url('payroll/setting/ctc-details?staffid=' . (int)$row['staffid']); ?>" class="btn btn-success btn-xs" target="_blank">View</a>
                          </div>
                        </td>
                      </tr>
                    <?php } } ?>
                  </tbody>
                </table>
              </div>

<!-- Payroll Structure Modal -->
<div class="modal fade" id="ctc_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Payroll CTC : <span id="ctc-staff-name"></span></h4>
      </div>
      <div class="modal-body">
        <form id="ctc-form">
          <input type="hidden" name="staffid" id="ctc-staff-id" />
          <div class="row">
            <div class="col-md-4">
              <?php echo render_input('base_salary', 'Base Salary', '', 'number', ['step' => '0.01', 'min' => '0', 'id' => 'ctc-base-salary']); ?>
            </div>
            <div class="col-md-8 tw-flex tw-items-end tw-justify-end">
              <button type="button" class="btn btn-default" id="add-ctc-row"><i class="fa fa-plus"></i> Add Component</button>
            </div>
          </div>
          <div class="table-responsive tw-mt-3">
            <table class="table table-bordered" id="ctc-components-table">
              <thead>
                <tr>
                  <th>Component</th>
                  <th>Calc Type</th>
                  <th>Amount / %</th>
                  <th>Percent Of</th>
                  <th style="width:40px;"></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-ctc-btn">Save</button>
      </div>
    </div>
  </div>
</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="staff_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl">
    <?php echo form_open(admin_url('payroll/staffentry'), ['id' => 'staff-form']); ?>
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
          <div class="col-md-4"><div class="form-group"><label>Staff Type</label>
            <select name="staff_type" class="form-control">
              <option value="">-- Select Staff Type --</option>
              <?php if (!empty($staff_types)) { foreach ($staff_types as $st) { ?>
                <option value="<?php echo (int)$st['id']; ?>"><?php echo e($st['title']); ?></option>
              <?php } } ?>
            </select>
          </div></div>
          <div class="col-md-4"><div class="form-group"><label>Date of Birth</label><input type="date" name="dob" class="form-control"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Joining Date</label><input type="date" name="joining_date" class="form-control"></div></div>
        </div>
        <div class="row"><div class="col-md-12"><hr><h5><strong>Approvers</strong></h5></div></div>
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>HR Approver</label>
            <select name="hr_approver" class="form-control">
              <option value="">-- Select HR Approver --</option>
              <?php if (!empty($dept9_employees)) { foreach ($dept9_employees as $emp) { ?>
                <option value="<?php echo (int)$emp['staffid']; ?>"><?php echo e($emp['full_name']); ?> (<?php echo e($emp['email']); ?>)</option>
              <?php } } ?>
            </select>
          </div></div>
          <div class="col-md-6"><div class="form-group"><label>Admin Approver</label>
            <select name="admin_approver" class="form-control">
              <option value="">-- Select Admin Approver --</option>
              <?php if (!empty($dept9_employees)) { foreach ($dept9_employees as $emp) { ?>
                <option value="<?php echo (int)$emp['staffid']; ?>"><?php echo e($emp['full_name']); ?> (<?php echo e($emp['email']); ?>)</option>
              <?php } } ?>
            </select>
          </div></div>
        </div>
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>HR Manager Approver</label>
            <select name="hr_manager_approver" class="form-control">
              <option value="">-- Select HR Manager Approver --</option>
              <?php if (!empty($dept9_employees)) { foreach ($dept9_employees as $emp) { ?>
                <option value="<?php echo (int)$emp['staffid']; ?>"><?php echo e($emp['full_name']); ?> (<?php echo e($emp['email']); ?>)</option>
              <?php } } ?>
            </select>
          </div></div>
          <div class="col-md-6"><div class="form-group"><label>Reporting Approver</label>
            <select name="reporting_approver" class="form-control">
              <option value="">-- Select Reporting Approver --</option>
              <?php if (!empty($all_employees)) { foreach ($all_employees as $emp) { ?>
                <option value="<?php echo (int)$emp['staffid']; ?>"><?php echo e($emp['full_name']); ?> (<?php echo e($emp['email']); ?>)</option>
              <?php } } ?>
            </select>
          </div></div>
        </div>
        <div class="row"><div class="col-md-12"><hr><h5><strong>Status</strong></h5></div></div>
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Employee Status</label>
            <select name="employee_status" class="form-control">
              <option value="">-- Employee Status --</option>
              <option value="Active">Active</option>
              <option value="Absconding">Absconding</option>
              <option value="Termination">Termination</option>
              <option value="Resignation">Resignation</option>
              <option value="Retired">Retired</option>
            </select>
          </div></div>
          <div class="col-md-6"><div class="form-group"><label>Reporting Manager / Team Leader</label>
            <select name="reporting_manager" class="form-control">
              <option value="">-- Select Reporting Manager --</option>
              <?php if (!empty($all_employees)) { foreach ($all_employees as $emp) { ?>
                <option value="<?php echo (int)$emp['staffid']; ?>"><?php echo e($emp['full_name']); ?> (<?php echo e($emp['email']); ?>)</option>
              <?php } } ?>
            </select>
          </div></div>
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
function loadDesignations(depId, done) {
  var $des = $("select[name=designation]");
  $des.html('<option value="">Loading...</option>');
  $.get(admin_url + 'hrd/designations_by_department', { department_id: depId }, function(resp) {
    var items = [];
    if (Array.isArray(resp)) { items = resp; }
    else if (resp && resp.success && Array.isArray(resp.data)) { items = resp.data; }

    var opts = '<option value="">-- Select Designation --</option>';
    for (var i = 0; i < items.length; i++) {
      var r = items[i] || {};
      opts += '<option value="' + (r.id || '') + '">' + (r.title || '') + '</option>';
    }
    $des.html(opts);
    if ($des.hasClass('selectpicker')) { $des.selectpicker('refresh'); }
    if (typeof done === 'function') { done(); }
  }, 'json');
}

function openStaffModal(data) {
  var $m = $('#staff_modal');
  var $f = $m.find('form');
  $f.find('#additional').html('<input type="hidden" name="staffid" value="' + (data.staffid || '') + '"/>');
  $('#sidx').text((data.firstname || '') + ' ' + (data.lastname || '') + ' (' + (data.staffid || '') + ')');
  $f.find('select[name=title]').val(data.title || '');
  $f.find('input[name=firstname]').val(data.firstname || '');
  $f.find('input[name=lastname]').val(data.lastname || '');
  $f.find('input[name=employee_code]').val(data.employee_code || '');
  $f.find('input[name=phonenumber]').val(data.phonenumber || '');
  $f.find('select[name=branch]').val(data.branch || '');
  $f.find('select[name=department]').val(data.department || '');
  loadDesignations(data.department || '', function() {
    $f.find('select[name=designation]').val(data.designation || '');
    var $des = $f.find('select[name=designation]');
    if ($des.hasClass('selectpicker')) { $des.selectpicker('refresh'); }
  });
  $f.find('select[name=staff_type]').val(data.staff_type || '');
  $f.find('input[name=joining_date]').val((data.joining_date || '').substring(0, 10));
  $f.find('input[name=dob]').val((data.dob || '').substring(0, 10));
  $f.find('select[name=gender]').val(data.gender || '');
  $f.find('select[name=hr_approver]').val(data.hr_approver || '');
  $f.find('select[name=admin_approver]').val(data.admin_approver || '');
  $f.find('select[name=hr_manager_approver]').val(data.hr_manager_approver || '');
  $f.find('select[name=reporting_approver]').val(data.reporting_approver || '');
  $f.find('select[name=employee_status]').val(data.employee_status || '');
  $f.find('select[name=reporting_manager]').val(data.reporting_manager || '');
  $m.modal('show');
}
</script>
<?php init_tail(); ?>
<script>
$(function() {
  appValidateForm($("#staff-form"), {
    firstname: 'required',
    lastname: 'required'
  }, function(form) {
    var data = $(form).serialize();
    $.ajax({
      url: form.action,
      method: 'POST',
      data: data,
      dataType: 'json'
    }).done(function(resp) {
      if (resp && resp.success) {
        window.location.href = admin_url + 'payroll/setting/ctc';
        return;
      }
      window.location.href = admin_url + 'payroll/setting/ctc';
    }).fail(function() {
      window.location.href = admin_url + 'payroll/setting/ctc';
    });
    return false;
  });

  $("select[name=department]").on('change', function() {
    var dep = $(this).val();
    if (!dep) {
      $("select[name=designation]").html('<option value="">-- Select Designation --</option>');
      return;
    }
    loadDesignations(dep);
  });
});
</script>
<script>
const payrollComponents = <?php echo json_encode($payroll_components ?? []); ?>;

function htmlEscape(str) {
  if (str === null || str === undefined) { return ''; }
  return $('<div/>').text(str).html();
}

const payrollComponentOptions = payrollComponents.map(function(component){
  var label = component.title || component.name || ('Component #' + component.id);
  return '<option value="' + component.id + '">' + htmlEscape(label) + '</option>';
}).join('');

function percentOptions(currentId) {
  var opts = '<option value="">Select Component</option>';
  payrollComponents.forEach(function(component){
    if (parseInt(component.id) === parseInt(currentId)) { return; }
    var label = component.title || component.name || ('Component #' + component.id);
    opts += '<option value="' + component.id + '">' + htmlEscape(label) + '</option>';
  });
  return opts;
}

function buildCTCRow(rowData) {
  rowData = rowData || {};
  var componentId = rowData.component_id || '';
  var calcType = rowData.calc_type || 'fixed';
  var amount = rowData.amount || '';
  var percentOf = rowData.percent_of_component || '';

  var $row = $('<tr class="ctc-row">');
  var componentSelect = '<select class="form-control component-select"><option value="">Select Component</option>' + payrollComponentOptions + '</select>';
  var calcTypeSelect = '<select class="form-control calc-type-select"><option value="fixed">Fixed</option><option value="percent">Percent</option></select>';
  var percentSelect = '<select class="form-control percent-of-select">' + percentOptions(componentId) + '</select>';

  $row.append($('<td>').html(componentSelect));
  $row.append($('<td>').html(calcTypeSelect));
  $row.append($('<td>').html('<input type="number" step="0.01" min="0" class="form-control amount-input" placeholder="Amount / %" />'));
  $row.append($('<td>').html(percentSelect));
  $row.append($('<td class="text-center">').html('<button type="button" class="btn btn-danger btn-xs remove-ctc-row"><i class="fa fa-times"></i></button>'));

  $row.find('.component-select').val(componentId);
  $row.find('.calc-type-select').val(calcType);
  $row.find('.amount-input').val(amount);
  $row.find('.percent-of-select').val(percentOf);

  togglePercentRow($row);

  return $row;
}

function togglePercentRow($row) {
  var isPercent = $row.find('.calc-type-select').val() === 'percent';
  $row.find('.percent-of-select').prop('disabled', !isPercent);
  if (!isPercent) {
    $row.find('.percent-of-select').val('');
  }
}

function resetCTCModal() {
  $('#ctc-form')[0].reset();
  $('#ctc-components-table tbody').empty();
}

function openCTCModal(staff) {
  resetCTCModal();
  $('#ctc-staff-id').val(staff.staffid);
  $('#ctc-staff-name').text(staff.name + ' (#' + staff.staffid + ')');
  $('#ctc_modal').modal('show');

  $.get(admin_url + 'payroll/get_structure/' + staff.staffid, function(resp){
  
  //alert(JSON.stringify(resp));
    if (!resp || !resp.success) {
      $('#ctc-components-table tbody').append(buildCTCRow());
      return;
    }
  //alert(resp.structure.base_salary);
    $('#base_salary').val(resp.structure.base_salary);

    if (resp.structure.items && resp.structure.items.length) {
      resp.structure.items.forEach(function(item){
        $('#ctc-components-table tbody').append(buildCTCRow(item));
      });
    } else {
      $('#ctc-components-table tbody').append(buildCTCRow());
    }
  }, 'json');
}

$(document).on('change', '.calc-type-select', function(){
  var $row = $(this).closest('tr');
  togglePercentRow($row);
});

$(document).on('click', '#add-ctc-row', function(){
  $('#ctc-components-table tbody').append(buildCTCRow());
});

$(document).on('click', '.remove-ctc-row', function(){
  $(this).closest('tr').remove();
});

$(document).on('click', '.btn-ctc', function(){
  var staff = $(this).data('staff');
  openCTCModal(staff);
});

$('#save-ctc-btn').on('click', function(){
  var $rows = $('#ctc-components-table tbody tr');
  var items = [];
  $rows.each(function(){
    var componentId = $(this).find('.component-select').val();
    var calcType = $(this).find('.calc-type-select').val();
    var amount = $(this).find('.amount-input').val();
    var percentOf = $(this).find('.percent-of-select').val();

    if (!componentId) { return; }
    items.push({
      component_id: componentId,
      calc_type: calcType,
      amount: amount,
      percent_of_component: percentOf
    });
  });

  var payload = {
    staffid: $('#ctc-staff-id').val(),
    base_salary: $('#base_salary').val() || 0,
    items: JSON.stringify(items)
  };
  

  $.post(admin_url + 'payroll/save_structure', payload, function(resp){
    if (resp && resp.success) {
      alert_float('success', 'Payroll structure saved');
      $('#ctc_modal').modal('hide');
    } else {
      alert_float('danger', (resp && resp.message) ? resp.message : 'Unable to save structure');
    }
  }, 'json');
});
</script>
</body></html>
