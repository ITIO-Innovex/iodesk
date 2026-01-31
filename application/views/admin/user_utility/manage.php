<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4 class="no-margin">
                                    <?php echo $title; ?>
									<a href="javascript:void(0);" class="pull-right" id="crm_instruction" title="Manage Team Document Forms : User Guide"><i class="fa-solid fa-circle-info fa-2x text-warning tw-mx-2"></i></a>
                                    <a href="<?php echo admin_url('user_utility/create'); ?>" class="btn btn-info pull-right">
                                        <i class="fa fa-plus"></i> <?php echo _l('new'); ?>
                                    </a>
                                </h4>
                                <hr class="hr-panel-heading" />
                            </div>
                        </div>
                        
                        <?php if (count($forms) > 0) { ?>
                        <div class="table-responsive">
                            <table class="table dt-table" data-order-col="4" data-order-type="desc">
                                <thead>
                                    <tr>
                                        <th><?php echo _l('id'); ?></th>
                                        <th><?php echo _l('name'); ?></th>
                                        <th>Fields Count</th>
                                        <th>Status</th>
										<th>Has Data</th>
                                        <th><?php echo _l('date_created'); ?></th>
                                        <th><?php echo _l('options'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($forms as $form) { 
                                        $form_fields = json_decode($form->form_fields, true);
                                        $fields_count = is_array($form_fields) ? count($form_fields) : 0;
                                        $has_data = !empty($form->form_data);
										$status = !empty($form->status) ? $form->status : 2;
                                    ?>
                                    <tr>
                                        <td><?php echo $form->id; ?></td>
                                        <td>
                                            <a href="<?php echo admin_url('user_utility/view/' . $form->id); ?>">
                                                <?php echo $form->form_name; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $fields_count; ?></td>
                                        <td>
                                            <?php if ($status==1) { ?>
                                                <span class="label label-success">Completed</span>
                                            <?php } else { ?>
                                                <span class="label label-default">Process</span>
                                            <?php } ?>
                                        </td>
										 <td>
                                            <?php if ($has_data) { ?>
                                                <span class="label label-success">Yes</span>
                                            <?php } else { ?>
                                                <span class="label label-default">No</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo _dt($form->date_created); ?></td>
                                        <td>
                                            <div class="row-options">
                                                <a href="<?php echo admin_url('user_utility/view/' . $form->id); ?>" class="text-success">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="<?php echo admin_url('user_utility/edit/' . $form->id); ?>" class="text-info">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </a>
												<?php if (is_admin()) { ?>
                                                <a href="<?php echo admin_url('user_utility/delete/' . $form->id); ?>" 
                                                   class="text-danger _delete" 
                                                   data-toggle="tooltip" 
                                                   data-title="<?php echo _l('delete'); ?>">
                                                    <i class="fa fa-remove"></i>
                                                </a>
												<?php } ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <?php } else { ?>
                        <div class="text-center">
                            <h4><?php echo _l('no_records_found'); ?></h4>
                            <p>
                                <a href="<?php echo admin_url('user_utility/create'); ?>" class="btn btn-info">
                                    <i class="fa fa-plus"></i> Create your first form
                                </a>
                            </p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End CRM Instruction Modal -->
<div class="modal fade" id="crm_instruction_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Instruction: Team Document Forms Instructions</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">

<h3>Step 1: Create a Dynamic Form</h3>
<ul>
    <li>Click on <strong>Create Dynamic Form</strong>.</li>
    <li>Add a <strong>Form Name</strong>.</li>
    <li>Assign the form to staff members using <strong>multiple select</strong>.</li>
    <li>Click the <strong>Add Field</strong> button to add form fields.</li>
    <li>Select the required <strong>Field Type</strong>:
        <ul>
            <li><strong>Text</strong> – For short text (e.g., Name)</li>
            <li><strong>Textarea</strong> – For detailed input (e.g., Address)</li>
            <li><strong>Editor</strong> – For rich text content</li>
            <li><strong>Listbox</strong> – For dropdown selection</li>
            <li><strong>Radio</strong> – For single-choice options</li>
            <li><strong>Checkbox</strong> – For multiple-choice options</li>
            <li><strong>Date/Time</strong> – For date or time input</li>
            <li><strong>File</strong> – For file uploads</li>
        </ul>
    </li>
    <li>If the field is mandatory, check the <strong>Required</strong> option.</li>
    <li>Ensure the <strong>Field Name</strong> contains <strong>no spaces or special characters</strong>.</li>
    <li>After adding all required fields, click <strong>Submit</strong> to create the form.</li>
</ul>

<h3>Step 2: Submit Form Data</h3>
<ul>
    <li>After submission, the form will appear in the <strong>Form Listing Box</strong>.</li>
    <li>Click <strong>Add Form</strong> to open the form.</li>
    <li>Enter the required values.</li>
    <li>Click <strong>Submit</strong> to save the form data.</li>
</ul>

<h3>Step 3: View Assigned Staff & Comments</h3>
<ul>
    <li>After form submission, all <strong>assigned staff members</strong> will be displayed.</li>
    <li>Assigned staff members can view the submitted data.</li>
    <li>Staff members can add <strong>comments</strong> on the assigned form data.</li>
</ul>

<div class="note">
    <strong>Important Notes:</strong>
    <ul>
        <li>All required fields must be completed before submission.</li>
        <li>Invalid field names may prevent the form from being saved.</li>
        <li>Only assigned staff members can view and comment on the form data.</li>
    </ul>
</div>



				</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  $(function() {
    $('#crm_instruction').on('click', function(e) {
      e.preventDefault();
      $('#crm_instruction_modal').modal('show');
    });
  });
</script>
