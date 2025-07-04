<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($staff_members);?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
            <div id="" class="alert d-none ajaxAlert" role="alert"></div>
                <?php if (staff_can('create',  'staff')) { ?>
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
                            _l('Assigned To'),
							
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