<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($staff_members);?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <div id="" class="alert d-none ajaxAlert" role="alert"></div>
                <?php if (staff_can('create',  'staff') || is_department_admin()) { ?>
                <div class="tw-mb-2 sm:tw-mb-4">
                    <a href="<?php echo admin_url('staff/member'); ?>" class="btn btn-primary">
                        <i class="fa-regular fa-plus tw-mr-1"></i>
                        <?php echo _l('new_staff'); ?>
                    </a>
                </div>
                <?php } ?>
                <div class="panel_s">
                    <div class="panel-body panel-table-full">
                        <?php
                        $table_data = [
                            _l('staff_dt_name'),
                            _l('staff_dt_email'),
                            _l('role'),
                            _l('staff_dt_last_Login'),
                            _l('staff_dt_active'),
							_l('Company Name'),
                            
                            /*'Webmail Setup',*/
							
                        ];
                        $custom_fields = get_custom_fields('staff', ['show_on_table' => 1]);
                        foreach ($custom_fields as $field) {
                            array_push($table_data, [
                                'name'     => $field['name'],
                                'th_attrs' => ['data-type' => $field['type'], 'data-custom-field' => 1],
                            ]);
                        }
						//print_r($table_data);exit;
                        render_datatable($table_data, 'staff');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="delete_staff" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('staff/delete', ['delete_staff_form'])); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo _l('delete_staff'); ?></h4>
            </div>
            <div class="modal-body">
                
                <div class="delete_id">
                    <?php echo form_hidden('id'); ?>
                </div>
                <p><?php echo _l('delete_staff_info'); ?></p>
                <?php
                echo render_select('transfer_data_to', $staff_members, ['staffid', ['firstname', 'lastname']], 'staff_member', get_staff_user_id(), [], [], '', '', false);
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-danger _delete"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Assigned Model -->
<div class="modal fade" id="assignMail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('staff/assignedTo')); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Mail Assigning </h4>
            </div>
            <div class="modal-body">
            <div id="" class="alert d-none ajaxAlert" role="alert"></div>
                <input name="staffId" id="staffId" type="hidden" class="form-control">
                <div class="form-group">
                    <label for="interests">Available Mailers:</label>
                    <select name="mailerId" class="custom-select form-control" id="myMailerSelect">
                    <!-- Dynamically Populate Here -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-success"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Assigned Model  -->
<!-- Webmail Setup Modal -->
<div class="modal fade" id="webmailSetupModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <?php echo form_open(admin_url('webmail_setup/webmail_setup_create/'), ['data-create-url' => admin_url('webmail_setup/webmail_setup_create/'), 'data-update-url' => admin_url('webmail_setup/webmail_setup_update'), 'id' => 'webmail-setup-form']); ?>
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo 'Webmail Setup'; ?> <span id="staff-info-header" class="text-muted"></span></h4>
            </div>
            <div class="modal-body">
                <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
                <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1" />
                <input type="hidden" name="staffid" id="webmail_staffid" value="" />
				<input type="hidden" name="source" value="staff" />
                 
                <div class="form-group">
                    <label for="mailer_name" class="control-label">Full Name</label>
                    <?php echo render_input('mailer_name', '', '', 'text', ['required' => 'true', 'id' => 'mailer_name']); ?>
                </div>
                
                <div class="form-group">
                    <label for="mailer_email" class="control-label">Zoho Email </label>
                    <?php echo render_input('mailer_email', '', '', 'text', ['required' => 'true', 'id' => 'mailer_email']); ?>
                </div>
                
                <div class="loaderssmtp"></div>
                
                <div class="form-group">
                    <label for="mailer_password" class="control-label">Password (Zoho Email)</label>
                    <?php echo render_input('mailer_password', '', '', 'password', ['required' => 'true', 'id' => 'mailer_password']); ?>
                </div>
                           
            </div>
            <div class="modal-footer">
               <!-- <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>-->
                <button type="submit" class="btn btn-primary loaderssmtp"><?php echo _l('submit'); ?></button>
            </div>
        </div>
        <!-- /.modal-content -->
        <?php echo form_close(); ?>
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- End Webmail Setup Modal -->
<?php init_tail(); ?>
<script>
$(function() {
    initDataTable('.table-staff', window.location.href);
});

function delete_staff_member(id) {
    $('#delete_staff').modal('show');
    $('#transfer_data_to').find('option').prop('disabled', false);
    $('#transfer_data_to').find('option[value="' + id + '"]').prop('disabled', true);
    $('#delete_staff .delete_id input').val(id);
    $('#transfer_data_to').selectpicker('refresh');
}
function assignMail(id){
    $('#assignMail').modal('show');
    $('#staffId').val(id);
    // Send the data via AJAX
    $.ajax({
        type: 'POST',
        url:'staff/getUnassignedWebMails', // Replace with your actual URL
        data: {
            id: id
        },
        success: function(response) {
            let jsonResponse = JSON.parse(response);
            if(jsonResponse.status=='success'){
                if(jsonResponse.data.length > 0){
                    var options = '<option value="0">Select User</option>'; // Default option
                    $.each(jsonResponse.data, function (index, item){
                        options += '<option value="' + item.id + '">' + item.mailer_name +' ( '+item.mailer_email+' ) '+ '</option>';
                    });
                    // Append generated list items to an existing UL or OL
                     $('#myMailerSelect').html(options);
                }
            }else{
                showAlert("Fetching Mailer failed or Mailer Not Available!", "alert-danger");
            }
        },
        error: function(error) {
            // Handle error
            showAlert("Fetching Mailer failed or Mailer Not Available!", "alert-danger");
            console.error('Error sending message:', error.message);
        }
    });
}

function openWebmailSetup(staffId, staffEmail) {
    $('#webmailSetupModal').modal('show');
    $('#webmail_staffid').val(staffId);
    
    // If email is passed from table, pre-fill it immediately
    if (staffEmail) {
        $('#webmailSetupModal #mailer_email').val(staffEmail);
    }
    
    // Get staff information via AJAX (for full name and to confirm email)
    $.get(admin_url + 'staff/getStaffInfo/' + staffId, function(staffInfo) {
        if (staffInfo && staffInfo.email) {
            // Display staff information in modal header
            var staffName = staffInfo.firstname + ' ' + staffInfo.lastname;
            $('#staff-info-header').html(' - <small>' + staffName + ' (' + staffInfo.email + ')</small>');
            
            // Pre-populate email field with staff email
            $('#webmailSetupModal #mailer_email').val(staffInfo.email);
            // Pre-populate name field with staff full name
            $('#webmailSetupModal #mailer_name').val(staffName);
        }
        
        // Check if webmail setup already exists for this staff
        $.get(admin_url + 'staff/getStaffWebmail/' + staffEmail, function(response) {
            if (response && response.id) {
                // Edit mode - populate form with existing data
                var $form = $('#webmail-setup-form');
                $form.attr('action', $form.data('update-url') + '/' + response.id);
                $form.find('#mailer_name').val(response.mailer_name || '');
                $form.find('#mailer_email').val(response.mailer_email || '');
                // Populate password field with existing password
                $form.find('#mailer_password').val(response.mailer_password || '');
            } else {
                // Create mode - form already populated with staff info above
                var $form = $('#webmail-setup-form');
                $form.attr('action', $form.data('create-url'));
            }
        }, 'json').fail(function() {
            // If no existing setup, form is already set for create mode
            var $form = $('#webmail-setup-form');
            $form.attr('action', $form.data('create-url'));
        });
    }, 'json').fail(function() {
        // If staff info fetch fails, just show modal
        $('#staff-info-header').html('');
    });
}

// Webmail Setup Modal handlers
var $webmailModal = $('#webmailSetupModal');
$(function() {
    appValidateForm($webmailModal.find('form'), {
        mailer_name: 'required',
        mailer_email: 'required',
        mailer_password: 'required',
    });
    
    setTimeout(function() {
        $($webmailModal.find('form')).trigger('reinitialize.areYouSure');
    }, 1000);
    
    $webmailModal.on('hidden.bs.modal', function() {
        var $form = $webmailModal.find('form');
        $form.attr('action', $form.data('create-url'));
        $form[0].reset();
        $('#staff-info-header').html(''); // Clear staff info header
    });
    
    // Handle form submission
    $('#webmail-setup-form').on('submit', function(e) {
        e.preventDefault();
		
		  
        // Additional client-side validation for password
        var password = $('#mailer_password').val().trim();
        if (!password) {
            alert_float('warning', 'Password is required');
            $('#mailer_password').focus();
            return false;
        }
        
        // Check if password contains only whitespace
        if (password.length === 0 || /^\s*$/.test(password)) {
            alert_float('warning', 'Password cannot be empty or contain only spaces');
            $('#mailer_password').focus();
            return false;
        }
        
		$(".loaderssmtp").html('<i class="fa fa-spinner fa-spin"></i> Processing...');
        var formData = $(this).serialize();
        var url = $(this).attr('action');
        
        $.post(url, formData).done(function(response) {
            try {
                var result = typeof response === 'string' ? JSON.parse(response) : response;
                
                if (result.success === false) {
				$(".loaderssmtp").html('<?php echo _l('submit'); ?>');
				alert("Wrong Password - please check your password");
                    // Show validation error message
                    alert_float('danger', result.message || 'Failed to save webmail setup');
                } else {
                    // Success case
                    alert_float('success', 'Webmail setup saved successfully');
                    $('#webmailSetupModal').modal('hide');
                    // Reload the table
                    if ($.fn.DataTable.isDataTable('.table-staff')) {
                        $('.table-staff').DataTable().ajax.reload(null, false);
                    } else {
                        window.location.reload();
                    }
                }
            } catch(e) {
                // If response is not JSON, assume success (backward compatibility)
                alert_float('success', 'Webmail setup saved successfully');
                $('#webmailSetupModal').modal('hide');
                if ($.fn.DataTable.isDataTable('.table-staff')) {
                    $('.table-staff').DataTable().ajax.reload(null, false);
                } else {
                    window.location.reload();
                }
            }
        }).fail(function(xhr) {
            var message = 'Failed to save webmail setup';
            if (xhr.responseText) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        message = response.message;
                    }
                } catch(e) {
                    // Not JSON, use default message
                }
            }
            alert_float('danger', message);
        });
    });
});

$(document).ready(function(){
    $("#webmailSetupModal #mailer_email").on("keyup", function(){
        // In future, you can add additional reactive behaviors here if needed
    });
});
</script>
<script>
    // Prevent the dropdown from closing when a checkbox is clicked
    $('.dropdown-menu').on('click', function (e) {
      e.stopPropagation();
    });
    // Function to show and auto-hide the alert
function showAlert(message, alertType) {
    var alertBox = $(".ajaxAlert");
    alertBox.removeClass("d-none alert-success alert-danger") // Remove previous styles
           .addClass(alertType) // Add success or error class
           .html(message) // Set message
           .fadeIn(); // Show alert

    setTimeout(function() {
        alertBox.fadeOut(); // Hide after 5 sec
    }, 5000);
}
</script>
</body>

</html>