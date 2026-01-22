<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4">
          <a href="#" onclick="new_interview(); return false;" class="btn btn-primary">
            <i class="fa-regular fa-plus tw-mr-1"></i> <?php echo _l('New Interview'); ?>
          </a>
        </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
		  <div class="row">
		  <div class="col-sm-11">
            <form method="get" action="" class="mbot15 togglesearch" style="border: 1px solid rgb(204, 204, 204);
    padding: 25px 10px 10px;background: lightsteelblue; display:none;">
              <div class="row">
                <div class="col-md-2"><div class="form-group"><label>Full Name</label><input type="text" name="full_name" class="form-control" value="<?php echo e($filters['full_name'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Phone</label><input type="text" name="phone_number" class="form-control" value="<?php echo e($filters['phone_number'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Email</label><input type="text" name="email_id" class="form-control" value="<?php echo e($filters['email_id'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Qualification</label><input type="text" name="qualification" class="form-control" value="<?php echo e($filters['qualification'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Designation</label><input type="text" name="designation" class="form-control" value="<?php echo e($filters['designation'] ?? ''); ?>" /></div></div>
             
                <div class="col-md-2"><div class="form-group"><label>Total Experience</label><input type="text" name="total_experience" class="form-control" value="<?php echo e($filters['total_experience'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Current Salary</label><input type="text" name="current_salary" class="form-control" value="<?php echo e($filters['current_salary'] ?? ''); ?>" /></div></div>
                
                <div class="col-md-2"><div class="form-group"><label>Location</label><input type="text" name="location" class="form-control" value="<?php echo e($filters['location'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="<?php echo e($filters['city'] ?? ''); ?>" /></div></div>
             
                <div class="col-md-2"><div class="form-group"><label>Status</label>
                  <?php $st = (string)($filters['process_status'] ?? ''); ?>
                  <select name="process_status" class="form-control">
                    <option value="" <?php echo ($st==='')?'selected="selected"':''; ?>>-- All --</option>
                    <?php foreach ($interview_processes as $process) { ?>
                    <option value="<?php echo $process['id']; ?>" <?php echo ($st===$process['id'])?'selected="selected"':''; ?>><?php echo e($process['title']); ?></option>
                    <?php } ?>
                  </select>
                </div></div>
                <div class="col-md-2"><div class="form-group"><label>Added From</label><input type="date" name="added_from" class="form-control" value="<?php echo e($filters['added_from'] ?? ''); ?>" /></div></div>
                <div class="col-md-2"><div class="form-group"><label>Added To</label><input type="date" name="added_to" class="form-control" value="<?php echo e($filters['added_to'] ?? ''); ?>" /></div></div>
                <div class="col-md-2">
                  <label>&nbsp;</label><div class="form-group">
                    <button type="submit" class="btn btn-default"><i class="fa-solid fa-magnifying-glass" title="Search"></i></button>
					<a href="<?php echo admin_url('hrd/interviews'); ?>" class="btn btn-default" title="Reset"><i class="fa-solid fa-rotate"></i></a>
                  </div>
                </div>
              </div>
            </form>
            </div>
			<div class="col-sm-1 tw-text-right"><i class="fa-solid fa-filter tw-py-2" style="color: lightsteelblue;" id="toggleBtn" title="Search"></i></div>
		  </div>
            <?php if (!empty($interviews)) { ?>
            <table class="table dt-table" data-order-col="0" data-order-type="desc">
              <thead>
                <th>#</th>
                <th>Full Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Qualification</th>
                <th>Designation</th>
                <th>Experience</th>
                <?php /*?><th>Current Salary</th>
                <th>Notice (days)</th>
                <th>Location</th>
                <th>City</th>
                <th>Source</th><?php */?>
                <th>Process</th>
                <?php /*?><th>Status</th><?php */?>
                <?php /*?><th>Added On</th><?php */?>
                <th><?php echo _l('options'); ?></th>
              </thead>
              <tbody>
                <?php foreach ($interviews as $it) { $st=(int)($it['status']??1); 
                  // Get source name
                  $source_name = '';
                  if (!empty($it['source'])) {
                    foreach ($interview_sources as $source) {
                      if ($source['id'] == $it['source']) {
                        $source_name = $source['title'];
                        break;
                      }
                    }
                  }
                  
                  // Get process name
                  $process_name = '';
                  if (!empty($it['process_status'])) {
                    foreach ($interview_processes as $process) {
                      if ($process['id'] == $it['process_status']) {
                        $process_name = $process['title'];
                        break;
                      }
                    }
                  }
                ?>
                <tr>
                  <td><?php echo (int)$it['id']; ?></td>
                  <td><?php echo e($it['full_name']); ?></td>
                  <td><?php echo e($it['phone_number']); ?></td>
                  <td><?php echo e($it['email_id']); ?></td>
                  <td><?php echo e($it['qualification']); ?></td>
                  <td><?php echo e($it['designation']); ?></td>
                  <td><?php echo e($it['total_experience']); ?></td>
                  <?php /*?><td><?php echo e($it['current_salary']); ?></td>
                  <td><?php echo (int)$it['notice_period_in_days']; ?></td>
                  <td><?php echo e($it['location']); ?></td>
                  <td><?php echo e($it['city']); ?></td>
                  <td><?php echo e($source_name); ?></td><?php */?>
                  <td><?php echo e($process_name); ?></td>
                 <?php /*?> <td><?php echo $st===1?'<span class="label label-success">Active</span>':'<span class="label label-danger">Inactive</span>'; ?></td><?php */?>
                  <?php /*?><td><?php echo e($it['addedon']); ?></td><?php */?>
                  <td>
				  
				  
                    <div class="tw-flex tw-items-center tw-space-x-3">
					<a href="#" onclick="interview_email(this);return false;" data-id="<?php echo (int)$it['id']; ?>" data-name="<?php echo e($it['full_name']); ?>" data-email="<?php echo e($it['email_id']); ?>" data-designation="<?php echo e($it['designation']); ?>"  class="tw-text-success-500" title="Send Interview Invitation"><i class="fa-regular fa-envelope menu-icon fa-lg text-primary"></i></a>
					
                     <a href="#" onclick="view_interview(this);return false;" data-all='<?php echo json_encode($it, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-eye fa-lg"></i></a>
					 
                      <a href="#" onclick="edit_interview(this);return false;" data-all='<?php echo json_encode($it, JSON_HEX_APOS|JSON_HEX_AMP|JSON_HEX_TAG|JSON_HEX_QUOT); ?>' class="tw-text-neutral-500"><i class="fa-regular fa-pen-to-square fa-lg"></i></a>
					  
                    </div>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?><p class="no-margin">No records found.</p><?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="interviews_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <?php echo form_open(admin_url('hrd/interviewentry'), ['id' => 'interviews-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><span class="edit-title">Edit Interview</span><span class="add-title"><?php echo _l('Add Interview'); ?></span></h4>
      </div>
      <div class="modal-body">
        <div id="additional"></div>
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Full Name</label><input type="text" name="full_name" class="form-control" required></div></div>
          <div class="col-md-6"><div class="form-group"><label>Phone</label><input type="text" name="phone_number" class="form-control" required></div></div>
          <div class="col-md-6"><div class="form-group"><label>Email</label><input type="email" name="email_id" class="form-control"></div></div>
        
          <div class="col-md-6"><div class="form-group"><label>Qualification</label><input type="text" name="qualification" class="form-control"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Designation</label><input type="text" name="designation" class="form-control"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Total Experience</label><input type="text" name="total_experience" class="form-control"></div></div>
       
        
          <div class="col-md-6"><div class="form-group"><label>Current Salary</label><input type="text" name="current_salary" class="form-control"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Notice Period (days)</label><input type="number" name="notice_period_in_days" class="form-control"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Location</label><input type="text" name="location" class="form-control"></div></div>
        
        
          <div class="col-md-6"><div class="form-group"><label>City</label><input type="text" name="city" class="form-control"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Source</label>
            <select name="source" class="form-control">
              <option value="">-- Select Source --</option>
              <?php foreach ($interview_sources as $source) { ?>
                <option value="<?php echo $source['id']; ?>"><?php echo e($source['title']); ?></option>
              <?php } ?>
            </select>
          </div></div>
          <div class="col-md-6"><div class="form-group"><label>Process Status</label>
            <select name="process_status" class="form-control">
              <option value="">-- Select Process --</option>
              <?php foreach ($interview_processes as $process) { ?>
                <option value="<?php echo $process['id']; ?>"><?php echo e($process['title']); ?></option>
              <?php } ?>
            </select>
          </div></div>
        
          <div class="col-md-12"><div class="form-group"><label>Comments</label><textarea  name="comments" rows="5" class="form-control"></textarea></div></div>
        
        <?php /*?><div class="form-group"><label>Status</label>
          <select name="status" class="form-control">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
        </div><?php */?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<div class="modal fade" id="interviews_details" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Interview Details</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4"><strong>Name:</strong> <span id="d-name"></span></div>
          <div class="col-md-4"><strong>Phone:</strong> <span id="d-phone"></span></div>
          <div class="col-md-4"><strong>Email:</strong> <span id="d-email"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-4"><strong>Qualification:</strong> <span id="d-qual"></span></div>
          <div class="col-md-4"><strong>Designation:</strong> <span id="d-desig"></span></div>
          <div class="col-md-4"><strong>Experience:</strong> <span id="d-exp"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-4"><strong>Current Salary:</strong> <span id="d-salary"></span></div>
          <div class="col-md-4"><strong>Notice (days):</strong> <span id="d-notice"></span></div>
          <div class="col-md-4"><strong>Status:</strong> <span id="d-status" class="label"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-6"><strong>Location:</strong> <span id="d-location"></span></div>
          <div class="col-md-6"><strong>City:</strong> <span id="d-city"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-6"><strong>Source:</strong> <span id="d-source"></span></div>
          <div class="col-md-6"><strong>Process:</strong> <span id="d-process"></span></div>
        </div>
        <div class="row mtop10">
          <div class="col-md-12"><strong>Comments:</strong> <span id="d-comments"></span></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
      </div>
    </div>
  </div>
</div>

<script>
  window.addEventListener('load', function () {
    appValidateForm($("body").find('#interviews-form'), {full_name:'required',phone_number:'required'}, manage_interview);
    $('#interviews_modal').on("hidden.bs.modal", function () { $('#additional').html(''); $('#interviews_modal input, #interviews_modal textarea').val(''); $('#interviews_modal select').val(''); $('.add-title').removeClass('hide'); $('.edit-title').removeClass('hide'); });
  });
  function new_interview(){ $('#interviews_modal').modal('show'); $('.edit-title').addClass('hide'); }
  function edit_interview(invoker){ var it=$(invoker).data('all'); $('#additional').append(hidden_input('id', it.id)); $('#interviews_modal input[name=full_name]').val(it.full_name); $('#interviews_modal input[name=phone_number]').val(it.phone_number); $('#interviews_modal input[name=email_id]').val(it.email_id); $('#interviews_modal input[name=qualification]').val(it.qualification); $('#interviews_modal input[name=designation]').val(it.designation); $('#interviews_modal input[name=total_experience]').val(it.total_experience); $('#interviews_modal input[name=current_salary]').val(it.current_salary); $('#interviews_modal input[name=notice_period_in_days]').val(it.notice_period_in_days); $('#interviews_modal input[name=location]').val(it.location); $('#interviews_modal input[name=city]').val(it.city); $('#interviews_modal select[name=source]').val(it.source||''); $('#interviews_modal select[name=process_status]').val(it.process_status||''); $('#interviews_modal textarea[name=comments]').val(it.comments); $('#interviews_modal select[name=status]').val(it.status||1); $('#interviews_modal').modal('show'); $('.add-title').addClass('hide'); }
  
  
  function view_interview(invoker){
    var it = $(invoker).data('all') || {};
    $('#d-name').text(it.full_name || '');
    $('#d-phone').text(it.phone_number || '');
    $('#d-email').text(it.email_id || '');
    $('#d-qual').text(it.qualification || '');
    $('#d-desig').text(it.designation || '');
    $('#d-exp').text(it.total_experience || '');
    $('#d-salary').text(it.current_salary || '');
    $('#d-notice').text(it.notice_period_in_days || '');
    var st = parseInt(it.status || 1, 10);
    $('#d-status').removeClass('label-success label-danger').addClass(st===1?'label-success':'label-danger').text(st===1?'Active':'Inactive');
    $('#d-location').text(it.location || '');
    $('#d-city').text(it.city || '');
  // Get source name
  var source_name = '';
  if (it.source) {
    $('select[name=source] option').each(function() {
      if ($(this).val() == it.source) {
        source_name = $(this).text();
        return false;
      }
    });
  }
  $('#d-source').text(source_name);
  
  // Get process name
  var process_name = '';
  if (it.process_status) {
    $('select[name=process_status] option').each(function() {
      if ($(this).val() == it.process_status) {
        process_name = $(this).text();
        return false;
      }
    });
  }
  $('#d-process').text(process_name);
  
  $('#d-comments').text(it.comments || '');
  $('#interviews_details').appendTo('body').modal('show');
  }


</script>
<?php init_tail(); ?>
<script>
$(document).ready(function(){
    $('#toggleBtn').click(function(){
        $('.togglesearch').slideToggle(); // smoothly show/hide form
    });
});

function interview_email(element) {
    var id = $(element).data('id');
    var name = $(element).data('name');
    var email = $(element).data('email');
    var designation = $(element).data('designation');
    
    // Create a modal for sending interview invitation
    var modalHtml = `
      <div class="modal fade" id="interview_email_modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">Schedule Interview : <span> To ` + name + ` [` + email + `] </span></h4>
            </div>
            <div class="modal-body">
              <!-- Hidden fields -->
              <input type="hidden" name="interviewer_id" value="` + id + `">
              <input type="hidden" name="interviewer_name" value="` + name + `">
              <input type="hidden" name="interviewer_email" value="` + email + `">
              <input type="hidden" name="interviewer_designation" value="` + designation + `">
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    For Post :: ` + designation + `
                  </div>
                </div>
                
              </div>
              
              <div class="row">
                
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Interview Mode</label>
                    <select name="interview_mode" class="form-control" required>
                      <option value="">Select Interview Mode</option>
                      <option value="Onsite">Onsite</option>
                      <option value="Virtual">Virtual</option>
                      <option value="Telephonic">Telephonic</option>
                      <option value="Technical">Technical</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label class="control-label">Date & Time</label>
                    <input type="datetime-local" name="interview_date_time" class="form-control" required>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group" id="interview_location_group" style="display: none;">
                    <label class="control-label">Interview Location</label>
                    <select name="interview_location" class="form-control" required>
                      <option value="">Select Location</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" id="virtual_meeting_group" style="display: none;">
                    <label class="control-label">Virtual Meeting Link</label>
                    <input type="text" name="interview_virtual_meeting_link" class="form-control" placeholder="https://zoom.us/meeting/...">

                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" onclick="save_interview_schedule()">Schedule Interview</button>
            </div>
          </div>
        </div>
      </div>
    `;
    
    // Remove existing modal if present
    $('#interview_email_modal').remove();
    
    // Add modal to body and show it
    $('body').append(modalHtml);
    $('#interview_email_modal').modal('show');
    
    // Load interview locations dynamically after modal is fully shown
    $('#interview_email_modal').on('shown.bs.modal', function() {
        load_interview_locations();
    });
    
    // Handle interview mode change
    $('select[name="interview_mode"]').on('change', function() {
        var mode = $(this).val(); 
        if (mode === 'Virtual') {
            $('#virtual_meeting_group').show();
			$('#interview_location_group').hide();
            $('select[name="interview_location"]').prop('required', false);
            $('input[name="interview_virtual_meeting_link"]').prop('required', true);
		} else if (mode === 'Onsite' || mode === 'Technical') {
		    $('#virtual_meeting_group').hide();
			$('#interview_location_group').show();
            $('select[name="interview_location"]').prop('required', true);
            $('input[name="interview_virtual_meeting_link"]').prop('required', false);
		} else if (mode === 'Telephonic') {
		    $('#virtual_meeting_group').hide();
			$('#interview_location_group').hide();
            $('select[name="interview_location"]').prop('required', false);
            $('input[name="interview_virtual_meeting_link"]').prop('required', false);
        } else {
            $('#virtual_meeting_group').hide();
			$('#interview_location_group').show();
            $('select[name="interview_location"]').prop('required', true);
            $('input[name="interview_virtual_meeting_link"]').prop('required', false);
        }
    });
  }
  
  function load_interview_locations() {
    console.log('Loading interview locations...');
    var url = site_url + 'admin/hrd/get_interview_locations';
    console.log('Request URL:', url);
    
    $.get(url, function(response) {
        console.log('Response received:', response);
        console.log('Response type:', typeof response);
        
        try {
            var locations = JSON.parse(response);
            console.log('Parsed locations:', locations);
            
            var select = $('select[name="interview_location"]');
            console.log('Location select element:', select.length > 0 ? 'found' : 'not found');
            
            if (select.length === 0) {
                console.error('Location select not found in DOM');
                return;
            }
            
            select.empty().append('<option value="">Select Location</option>');
            
            if (locations && locations.length > 0) {
                console.log('Adding', locations.length, 'locations to dropdown');
                $.each(locations, function(index, location) {
                    console.log('Adding location:', location.branch_address);
                    select.append('<option value="' + location.branch_address + '">' + location.branch_address + '</option>');
                });
                console.log('Final select HTML:', select.html());
            } else {
                console.log('No locations found in response');
                select.append('<option value="">No locations available</option>');
            }
        } catch(e) {
            console.error('Error parsing locations response:', e);
            console.error('Response was:', response);
            var select = $('select[name="interview_location"]');
            if (select.length > 0) {
                select.append('<option value="">Error loading locations</option>');
            }
        }
    }).fail(function(xhr, status, error) {
        console.error('Failed to load interview locations:', {
            status: status,
            error: error,
            responseText: xhr.responseText
        });
        var select = $('select[name="interview_location"]');
        if (select.length > 0) {
            select.append('<option value="">Failed to load locations</option>');
        }
    });
  }
  
  function save_interview_schedule() {
    var formData = $('#interview_email_modal').find('input, select, textarea').serialize();
	formData += '&<?= $this->security->get_csrf_token_name(); ?>=<?= $this->security->get_csrf_hash(); ?>';
    //alert(formData);
    // Basic validation
    var interviewerName = $('#interview_email_modal input[name="interviewer_name"]').val();
    var interviewerEmail = $('#interview_email_modal input[name="interviewer_email"]').val();
    var interviewMode = $('#interview_email_modal select[name="interview_mode"]').val();
    var interviewDateTime = $('#interview_email_modal input[name="interview_date_time"]').val();
    var interviewLocation = $('#interview_email_modal select[name="interview_location"]').val();
    var virtualLink = $('#interview_email_modal input[name="interview_virtual_meeting_link"]').val();
    
    if (!interviewerName || !interviewerEmail || !interviewMode || !interviewDateTime) {
        alert_float('warning', 'Please fill in all required fields');
        return;
    }
    
    if (interviewMode === 'Virtual' && !virtualLink) {
        alert_float('warning', 'Please provide virtual meeting link for virtual interviews');
        return;
    }
    
    if ((interviewMode !== 'Virtual' && interviewMode !== 'Telephonic') && !interviewLocation) {
        alert_float('warning', 'Please select interview location');
        return;
    }
    
    // Show loading state
    var saveBtn = $('#interview_email_modal .btn-primary');
    var originalText = saveBtn.text();
    saveBtn.html('<i class="fa fa-spinner fa-spin"></i> Scheduling...').prop('disabled', true);
    
    // Save interview schedule via AJAX
    $.post(site_url + 'admin/hrd/save_interview_schedule', formData)
      .done(function(response) {
        try { 
            var result = JSON.parse(response);
            if (result.success) {
                alert_float('success', 'Interview scheduled successfully');
                $('#interview_email_modal').modal('hide');
                // Optionally reload page to show updated data
                window.location.reload();
            } else {
                alert_float('danger', result.message || 'Failed to schedule interview');
            }
        } catch(e) { 
            alert_float('success', 'Interview scheduled successfully');
            $('#interview_email_modal').modal('hide');
            window.location.reload();
        }
    }).fail(function() { 
        alert_float('danger', 'Error occurred while scheduling interview');
    }).always(function() { 
        // Restore button state
        saveBtn.html(originalText).prop('disabled', false);
    });
  }
  function manage_interview(form){ 
    var data=$(form).serialize();
    $.post(form.action, data).done(function(response){
      try {
        var result = JSON.parse(response);
        if (result.success) {
          $('#interviews_modal').modal('hide');
          window.location.reload();
        }
      } catch(e) {
        window.location.reload();
      }
    }).fail(function() {
      alert('Error occurred while saving interview');
    });
    return false; 
  }
</script>
</body></html>
