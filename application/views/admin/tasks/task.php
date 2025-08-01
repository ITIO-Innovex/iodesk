<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_open_multipart(admin_url('tasks/task' . ($id ? '/' . $id : '')), ['id' => 'task-form']); ?>
<div class="modal fade<?php if (isset($task)) {
    echo ' edit';
} ?>" id="_task_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" <?php if ($this->input->get('opened_from_lead_id')) {
    echo 'data-lead-id=' . $this->input->get('opened_from_lead_id');
} ?>>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
				<!-- Button to close the modal -->
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<!-- The "X" symbol to close the modal -->
					<span aria-hidden="true">&times;</span>
				</button>
	
				<h4 class="modal-title" id="myModalLabel">
					<!-- Output the title variable passed from the controller -->
					<?php echo e($title); ?>
				</h4>
			</div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                 <?php
					// Initialize variables for relation type, relation ID, and form type
					$rel_type = '';
					$rel_id   = '';
					$form_type= '';
					
					// Check if task is set or if there are query parameters for 'rel_id' and 'rel_type'
					if (isset($task) || ($this->input->get('rel_id') && $this->input->get('rel_type'))) {
						// If task is set, assign the task's rel_id and rel_type. Otherwise, use the query parameters
						$rel_id   = isset($task) ? $task->rel_id : $this->input->get('rel_id');
						$rel_type = isset($task) ? $task->rel_type : $this->input->get('rel_type');
					}
					// Check if task is set and if the task is billed
                   if (isset($task) && $task->billed == 1) {
					// Display an alert that the task is billed, with a link to the related invoice
                       echo '<div class="alert alert-success text-center no-margin">' . _l('task_is_billed', '<a href="' . admin_url('invoices/list_invoices/' . $task->invoice_id) . '" target="_blank">' . e(format_invoice_number($task->invoice_id))) . '</a></div><br />';
                   }
					// Check if a 'form_type' is provided in the query parameters
					if ($this->input->get('form_type')) {
						// If 'form_type' is present, set it to 'short'
						$form_type= 'short';
					}
                  ?>
                        <?php if (isset($task)) { ?>
						<!-- Check if the task is set before displaying the menu -->
                        <div class="pull-right mbot10 task-single-menu task-menu-options">
						<div class="content-menu hide">
							<ul>
								<?php if (staff_can('create',  'tasks')) { ?>
								<!-- Check if the staff member has permission to create tasks -->
								<?php
								// Initialize variable for storing HTML content for the copy template
								$copy_template = '';
				
								// Check if the task has assignees and if so, add a checkbox to copy them
								if (total_rows(db_prefix() . 'task_assigned', ['taskid' => $task->id]) > 0) {
									$copy_template .= "<div class='checkbox checkbox-primary'><input type='checkbox' name='copy_task_assignees' id='copy_task_assignees' checked><label for='copy_task_assignees'>" . _l('task_single_assignees') . '</label></div>';
								}
				
								// Check if the task has followers and if so, add a checkbox to copy them
								if (total_rows(db_prefix() . 'task_followers', ['taskid' => $task->id]) > 0) {
									$copy_template .= "<div class='checkbox checkbox-primary'><input type='checkbox' name='copy_task_followers' id='copy_task_followers' checked><label for='copy_task_followers'>" . _l('task_single_followers') . '</label></div>';
								}
				
								// Check if the task has checklist items and if so, add a checkbox to copy them
								if (total_rows(db_prefix() . 'task_checklist_items', ['taskid' => $task->id]) > 0) {
									$copy_template .= "<div class='checkbox checkbox-primary'><input type='checkbox' name='copy_task_checklist_items' id='copy_task_checklist_items' checked><label for='copy_task_checklist_items'>" . _l('task_checklist_items') . '</label></div>';
								}
				
								// Check if the task has attachments and if so, add a checkbox to copy them
								if (total_rows(db_prefix() . 'files', ['rel_id' => $task->id, 'rel_type' => 'task']) > 0) {
									$copy_template .= "<div class='checkbox checkbox-primary'><input type='checkbox' name='copy_task_attachments' id='copy_task_attachments'><label for='copy_task_attachments'>" . _l('task_view_attachments') . '</label></div>';
								}
				
								// Add a paragraph for task status
								$copy_template .= '<p>' . _l('task_status') . '</p>';
				
								// Apply filters for custom task statuses and add them as radio buttons
								$task_copy_statuses = hooks()->apply_filters('task_copy_statuses', $task_statuses);
								foreach ($task_copy_statuses as $copy_status) {
									$copy_template .= "<div class='radio radio-primary'><input type='radio' value='" . $copy_status['id'] . "' name='copy_task_status' id='copy_task_status_" . $copy_status['id'] . "'" . ($copy_status['id'] == hooks()->apply_filters('copy_task_default_status', 1) ? ' checked' : '') . "><label for='copy_task_status_" . $copy_status['id'] . "'>" . $copy_status['name'] . '</label></div>';
								}
				
								// Add a confirmation button to copy the task
								$copy_template .= "<div class='text-center'>";
								$copy_template .= "<button type='button' data-task-copy-from='" . $task->id . "' class='btn btn-success copy_task_action'>" . _l('copy_task_confirm') . '</button>';
								$copy_template .= '</div>';
								?>
								
								<!-- Add a list item with a popover showing the task copy options -->
								<li> 
									<a href="#" onclick="return false;" data-placement="bottom"
										data-toggle="popover"
										data-content="<?php echo htmlspecialchars($copy_template); ?>"
										data-html="true">
										<?php echo _l('task_copy'); ?>
									</a>
								</li>
								<?php } ?>
				
								<?php if (staff_can('delete',  'tasks')) { ?>
								<!-- Check if the staff member has permission to delete tasks -->
								<li>
									<!-- Provide a link to delete the task -->
									<a href="<?php echo admin_url('tasks/delete_task/' . $task->id); ?>"
										class="_delete task-delete">
										<?php echo _l('task_single_delete'); ?>
									</a>
								</li>
								<?php } ?>
							</ul>
						</div>
				
						<?php if (staff_can('delete',  'tasks') || staff_can('create',  'tasks')) { ?>
						<!-- If the staff member can delete or create tasks, show a trigger for the menu -->
						<a href="#" onclick="return false;" class="trigger manual-popover mright5">
							<i class="fa-regular fa-circle fa-sm"></i>
							<i class="fa-regular fa-circle fa-sm"></i>
							<i class="fa-regular fa-circle fa-sm"></i>
						</a>
						<?php } ?>
					</div>
					<?php } ?>
                        <div class="checkbox checkbox-primary checkbox-inline task-add-edit-public tw-pt-2">
							<!-- Checkbox for marking the task as public -->
                            <input type="checkbox" id="task_is_public" name="is_public" <?php if (isset($task)) {
                               if ($task->is_public == 1) {
                                   echo 'checked';
                               }
                           }; ?>>
                            <label for="task_is_public" data-toggle="tooltip" data-placement="bottom"
                                title="<?php echo _l('task_public_help'); ?>"><?php echo _l('task_public'); ?></label>
                        </div>
                        <div class="checkbox checkbox-primary checkbox-inline task-add-edit-billable tw-pt-2">
						<!-- Checkbox for marking the task as billable -->
                            <input type="checkbox" id="task_is_billable" name="billable" <?php if ((isset($task) && $task->billable == 1) || (!isset($task) && get_option('task_biillable_checked_on_creation') == 1)) {
                               echo ' checked';
                           }?>>
                            <label for="task_is_billable"><?php echo _l('task_billable'); ?></label>
                        </div>
                        <div class="task-visible-to-customer tw-pt-2 checkbox checkbox-inline checkbox-primary<?php if ((isset($task) && $task->rel_type != 'project') || !isset($task) || (isset($task) && $task->rel_type == 'project' && total_rows(db_prefix() . 'project_settings', ['project_id' => $task->rel_id, 'name' => 'view_tasks', 'value' => 0]) > 0)) {
                               echo ' hide';
                           } ?>">
                            <input type="checkbox" id="task_visible_to_client" name="visible_to_client" <?php if (isset($task)) {
                               if ($task->visible_to_client == 1) {
                                   echo 'checked';
                               }
                           } ?>>
                            <label for="task_visible_to_client"><?php echo _l('task_visible_to_client'); ?></label>
                        </div>
                        <?php if (!isset($task)) { ?>
                        <a href="#" class="pull-right tw-pt-2"
                            onclick="slideToggle('#new-task-attachments'); return false;">
                            <?php echo _l('attach_files'); ?>
                        </a>
                    <div id="new-task-attachments" class="hide">
						<!-- This div contains the section for adding new task attachments, initially hidden -->
					
						<hr class="-tw-mx-3.5" />
						<!-- A horizontal line to separate the attachments section -->
					
						<div class="row attachments">
							<!-- Row that contains the attachment input field -->
					
							<div class="attachment">
								<!-- Individual attachment container -->
					
								<div class="col-md-12">
									<!-- Full-width column for the attachment input -->
					
									<div class="form-group">
										<!-- Form group for the attachment input field -->
					
										<label for="attachment" class="control-label">
											<!-- Label for the attachment input field -->
											<?php echo _l('add_task_attachments'); ?>
										</label>
					
										<div class="input-group">
											<!-- Input group for the attachment input and button -->
                                                <input type="file"
                                                    extension="<?php echo str_replace('.', '', get_option('allowed_files')); ?>"
                                                    filesize="<?php echo file_upload_max_size(); ?>"
                                                    class="form-control" name="attachments[0]">
												<!-- Button group for additional actions -->
                                                <span class="input-group-btn">
                                                    <button class="btn btn-default add_more_attachments"
                                                        type="button"><i class="fa fa-plus"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            if ($this->input->get('ticket_to_task')) {
                                echo form_hidden('ticket_to_task', $rel_id);
                            }
                        } ?>
                        <hr class="-tw-mx-3.5" />
                        <?php $value = (isset($task) ? $task->name : ''); ?>
                        <?php echo render_input('name', 'task_add_edit_subject', $value); ?>
                        <div class="task-hours<?php if (isset($task) && $task->rel_type == 'project' && total_rows(db_prefix() . 'projects', ['id' => $task->rel_id, 'billing_type' => 3]) == 0) {
                            echo ' hide';
                          } ?>">
                            <?php $value = (isset($task) ? $task->hourly_rate : 0); ?>
                            <?php echo render_input('hourly_rate', 'task_hourly_rate', $value); ?>
                        </div>
						
						<div <?php if($form_type=='short') echo 'class="hide"';?>>
						
                        <div class="project-details<?php if ($rel_type != 'project') {
                            echo ' hide';
                          } ?>">
                            <div class="form-group">
                                <label for="milestone"><?php echo _l('task_milestone'); ?></label>
                                <select name="milestone" id="milestone" class="selectpicker" data-width="100%"
                                    data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                    <option value=""></option>
                                    <?php foreach ($milestones as $milestone) { ?>
                                    <option value="<?php echo e($milestone['id']); ?>" <?php if (isset($task) && $task->milestone == $milestone['id']) {
                      echo 'selected';
                  } ?>><?php echo e($milestone['name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (isset($task)) {
                      $value = _d($task->startdate);
                  } elseif (isset($start_date)) {
                      $value = $start_date;
                  } else {
                      $value = _d(date('Y-m-d'));
                  }
                        $date_attrs = [];
                        if (isset($task) && $task->recurring > 0 && $task->last_recurring_date != null) {
                            $date_attrs['disabled'] = true;
                        }
                        ?>
                                <?php echo render_date_input('startdate', 'task_add_edit_start_date', $value, $date_attrs); ?>
                            </div>
                            <div class="col-md-6">
                                <?php $value = (isset($task) ? _d($task->duedate) : ''); ?>
                                <?php echo render_date_input('duedate', 'task_add_edit_due_date', $value, $project_end_date_attrs); ?>
                            </div>
							</div>
							<div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="priority"
                                        class="control-label"><?php echo _l('task_add_edit_priority'); ?></label>
                                    <select name="priority" class="selectpicker" id="priority" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <?php foreach (get_tasks_priorities() as $priority) { ?>
                                        <option value="<?php echo e($priority['id']); ?>" <?php if (isset($task) && $task->priority == $priority['id'] || !isset($task) && get_option('default_task_priority') == $priority['id']) {
                            echo ' selected';
                        } ?>><?php echo e($priority['name']); ?></option>
                                        <?php } ?>
                                        <?php hooks()->do_action('task_priorities_select', (isset($task) ? $task : 0)); ?>
                                    </select>
                                </div>
                            </div>
							
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="repeat_every"
                                        class="control-label"><?php echo _l('task_repeat_every'); ?></label>
                                    <select name="repeat_every" id="repeat_every" class="selectpicker" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""></option>
                                        <option value="1-week" <?php if (isset($task) && $task->repeat_every == 1 && $task->recurring_type == 'week') {
                            echo 'selected';
                        } ?>><?php echo _l('week'); ?></option>
                                        <option value="2-week" <?php if (isset($task) && $task->repeat_every == 2 && $task->recurring_type == 'week') {
                            echo 'selected';
                        } ?>>2 <?php echo _l('weeks'); ?></option>
                                        <option value="1-month" <?php if (isset($task) && $task->repeat_every == 1 && $task->recurring_type == 'month') {
                            echo 'selected';
                        } ?>>1 <?php echo _l('month'); ?></option>
                                        <option value="2-month" <?php if (isset($task) && $task->repeat_every == 2 && $task->recurring_type == 'month') {
                            echo 'selected';
                        } ?>>2 <?php echo _l('months'); ?></option>
                                        <option value="3-month" <?php if (isset($task) && $task->repeat_every == 3 && $task->recurring_type == 'month') {
                            echo 'selected';
                        } ?>>3 <?php echo _l('months'); ?></option>
                                        <option value="6-month" <?php if (isset($task) && $task->repeat_every == 6 && $task->recurring_type == 'month') {
                            echo 'selected';
                        } ?>>6 <?php echo _l('months'); ?></option>
                                        <option value="1-year" <?php if (isset($task) && $task->repeat_every == 1 && $task->recurring_type == 'year') {
                            echo 'selected';
                        } ?>>1 <?php echo _l('year'); ?></option>
                                        <option value="custom" <?php if (isset($task) && $task->custom_recurring == 1) {
                            echo 'selected';
                        } ?>><?php echo _l('recurring_custom'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="recurring_custom <?php if ((isset($task) && $task->custom_recurring != 1) || (!isset($task))) {
                            echo 'hide';
                        } ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <?php $value = (isset($task) && $task->custom_recurring == 1 ? $task->repeat_every : 1); ?>
                                    <?php echo render_input('repeat_every_custom', '', $value, 'number', ['min' => 1]); ?>
                                </div>
                                <div class="col-md-6">
                                    <select name="repeat_type_custom" id="repeat_type_custom" class="selectpicker"
                                        data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value="day" <?php if (isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'day') {
                            echo 'selected';
                        } ?>><?php echo _l('task_recurring_days'); ?></option>
                                        <option value="week" <?php if (isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'week') {
                            echo 'selected';
                        } ?>><?php echo _l('task_recurring_weeks'); ?></option>
                                        <option value="month" <?php if (isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'month') {
                            echo 'selected';
                        } ?>><?php echo _l('task_recurring_months'); ?></option>
                                        <option value="year" <?php if (isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'year') {
                            echo 'selected';
                        } ?>><?php echo _l('task_recurring_years'); ?></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="cycles_wrapper" class="<?php if (!isset($task) || (isset($task) && $task->recurring == 0)) {
                            echo ' hide';
                        }?>">
                            <?php $value = (isset($task) ? $task->cycles : 0); ?>
                            <div class="form-group recurring-cycles">
                                <label for="cycles"><?php echo _l('recurring_total_cycles'); ?>
                                    <?php if (isset($task) && $task->total_cycles > 0) {
                            echo '<small>' . e(_l('cycles_passed', $task->total_cycles)) . '</small>';
                        }
                        ?>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" <?php if ($value == 0) {
                            echo ' disabled';
                        } ?> name="cycles" id="cycles" value="<?php echo e($value); ?>" <?php if (isset($task) && $task->total_cycles > 0) {
                            echo 'min="' . e($task->total_cycles) . '"';
                        } ?>>
                                    <div class="input-group-addon">
                                        <div class="checkbox">
                                            <input type="checkbox" <?php if ($value == 0) {
                            echo ' checked';
                        } ?> id="unlimited_cycles">
                                            <label for="unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
							<!-- Begin a row for form layout -->
                            <div class="col-md-6">
								<!-- Column for selecting the related entity (project, invoice, etc.) -->
                                <div class="form-group">
									<!-- Form group for the related entity input -->
									<!-- Label for the related entity dropdown -->
                                    <label for="rel_type"
                                        class="control-label"><?php echo _l('task_related_to'); ?></label>
                                    <select name="rel_type" class="selectpicker" id="rel_type" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                        <option value=""></option>
                                        <option value="project" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'project') {
                                echo 'selected';
                            }
                        } ?>><?php echo _l('project'); ?></option>
                                        <option value="invoice" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'invoice') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('invoice'); ?>
                                        </option>
                                        <option value="customer" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'customer') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('client'); ?>
                                        </option>
                                        <option value="estimate" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'estimate') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('estimate'); ?>
                                        </option>
                                        <option value="contract" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'contract') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('contract'); ?>
                                        </option>
                                        <option value="ticket" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'ticket') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('ticket'); ?>
                                        </option>
                                        <option value="expense" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'expense') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('expense'); ?>
                                        </option>
                                        <option value="lead" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'lead') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('lead'); ?>
                                        </option>
                                        <option value="quotation" <?php if (isset($task) || $this->input->get('rel_type')) {
                            if ($rel_type == 'quotation') {
                                echo 'selected';
                            }
                        } ?>>
                                            <?php echo _l('quotation'); ?>
                                        </option>
                                        <?php
                                hooks()->do_action('task_modal_rel_type_select', ['task' => (isset($task) ? $task : 0), 'rel_type' => $rel_type]);
                            ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
							<!-- Column for selecting the related entity ID (based on the selected rel_type) -->
                              
							  <!-- Hide this field if no related entity ID is selected-->
							  <div class="form-group<?php if ($rel_id == '') {
                                echo ' hide';
                            } ?>" id="rel_id_wrapper">

                                    <label for="rel_id" class="control-label"><span class="rel_id_label"></span></label>
									<!-- Label for the related entity ID dropdown -->
                                    <div id="rel_id_select">
                                        <select name="rel_id" id="rel_id" class="ajax-sesarch" data-width="100%"
                                            data-live-search="true"
                                            data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                          <?php if ($rel_id != '' && $rel_type != '') {
                                $rel_data = get_relation_data($rel_type, $rel_id);
                                $rel_val  = get_relation_values($rel_data, $rel_type);
                                echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                            } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (!isset($task)) { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group select-placeholder>">
                                    <label for="assignees"><?php echo _l('task_single_assignees'); ?></label>
                                    <select name="assignees[]" id="assignees" class="selectpicker" data-width="100%"
                                        data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"
                                        multiple data-live-search="true">
                                        <?php foreach ($members as $member) { ?>
                                        <option value="<?php echo e($member['staffid']); ?>" <?php if ((get_option('new_task_auto_assign_current_member') == '1') && get_staff_user_id() == $member['staffid']) {
                                echo 'selected';
                            } ?>>
                                            <?php echo e($member['firstname'] . ' ' . $member['lastname']); ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php
                     $follower = (get_option('new_task_auto_follower_current_member') == '1') ? [get_staff_user_id()] : '';
                     echo render_select('followers[]', $members, ['staffid', ['firstname', 'lastname']], 'task_single_followers', $follower, ['multiple' => true], [], '', '', false);
                     ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                  if (isset($task)
                     && $task->status == Tasks_model::STATUS_COMPLETE
                     && (staff_can('create', 'tasks') || staff_can('edit', 'tasks'))) {
                      echo render_datetime_input('datefinished', 'task_finished', _dt($task->datefinished));
                  }
               ?>
                        <div class="form-group checklist-templates-wrapper<?php if (count($checklistTemplates) == 0 || isset($task)) {
                   echo ' hide';
               }  ?>">
                            <label for="checklist_items"><?php echo _l('insert_checklist_templates'); ?></label>
                            <select id="checklist_items" name="checklist_items[]"
                                class="selectpicker checklist-items-template-select" multiple="1"
                                data-none-selected-text="<?php echo _l('dropdown_non_selected_tex') ?>"
                                data-width="100%" data-live-search="true" data-actions-box="true">
                                <option value="" class="hide"></option>
                                <?php foreach ($checklistTemplates as $chkTemplate) { ?>
                                <option value="<?php echo e($chkTemplate['id']); ?>">
                                    <?php echo e($chkTemplate['description']); ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <div id="inputTagsWrapper">
                                <label for="tags" class="control-label"><i class="fa fa-tag" aria-hidden="true"></i>
                                    <?php echo _l('tags'); ?></label>
                                <input type="text" class="tagsinput" id="tags" name="tags"
                                    value="<?php echo(isset($task) ? prep_tags_input(get_tags_in($task->id, 'task')) : ''); ?>"
                                    data-role="tagsinput">
                            </div>
                        </div>
                        <?php $rel_id_custom_field = (isset($task) ? $task->id : false); ?>
                        <?php echo render_custom_fields('tasks', $rel_id_custom_field); ?>
                        <hr />
                        <p class="bold"><?php echo _l('task_add_edit_description'); ?></p>
                        <?php
					   // onclick and onfocus used for convert ticket to task too
					   echo render_textarea('description', '', (isset($task) ? $task->description : ''), ['rows' => 6, 'placeholder' => _l('task_add_description'), 'data-task-ae-editor' => true, !is_mobile() ? 'onclick' : 'onfocus' => (!isset($task) || isset($task) && $task->description == '' ? 'init_editor(\'.tinymce-task\', {height:200, auto_focus: true});' : '')], [], 'no-mbot', 'tinymce-task'); ?>
						</div>
					</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-primary matcheddate"><?php echo _l('submit'); ?></button>
            </div>
        </div>
    </div>
    <?php echo form_close(); ?>
    <script>
    var _rel_id = $('#rel_id'),
        _rel_type = $('#rel_type'),
        _rel_id_wrapper = $('#rel_id_wrapper'),
        _current_member = undefined,
        data = {};

    var _milestone_selected_data;
    _milestone_selected_data = undefined;

    <?php if (get_option('new_task_auto_assign_current_member') == '1') { ?>
    _current_member = "<?php echo get_staff_user_id(); ?>";
    <?php } ?>
    $(function() {

        $("body").off("change", "#rel_id");

        var inner_popover_template =
            '<div class="popover"><div class="arrow"></div><div class="popover-inner"><h3 class="popover-title"></h3><div class="popover-content"></div></div></div>';

        $('#_task_modal .task-menu-options .trigger').popover({
            html: true,
            placement: "bottom",
            trigger: 'click',
            title: "<?php echo _l('actions'); ?>",
            content: function() {
                return $('body').find('#_task_modal .task-menu-options .content-menu').html();
            },
            template: inner_popover_template
        });

        custom_fields_hyperlink();

        appValidateForm($('#task-form'), {
            name: 'required',
            startdate: 'required',
            repeat_every_custom: {
                min: 1
            },
        }, function(form) {
            // Custom validation for Start Date <= Due Date
            var startDate = $('#startdate').val();
            var dueDate = $('#duedate').val();
            if (startDate && dueDate) {
                var start = new Date(startDate);
                var due = new Date(dueDate);
                if (start > due) {
                    alert('Start Date must be less than or equal to Due Date.');
                    return false;
                }
            }
            task_form_handler(form);
        });

        $('.rel_id_label').html(_rel_type.find('option:selected').text());

        _rel_type.on('change', function() {

            var clonedSelect = _rel_id.html('').clone();
            _rel_id.selectpicker('destroy').remove();
            _rel_id = clonedSelect;
            $('#rel_id_select').append(clonedSelect);
            $('.rel_id_label').html(_rel_type.find('option:selected').text());

            task_rel_select();
            if ($(this).val() != '') {
                _rel_id_wrapper.removeClass('hide');
            } else {
                _rel_id_wrapper.addClass('hide');
            }
            init_project_details(_rel_type.val());
        });

        init_datepicker();
        init_color_pickers();
        init_selectpicker();
        task_rel_select();

        var _allAssigneeSelect = $("#assignees").html();

        $('body').on('change', '#rel_id', function() {
            if ($(this).val() != '') {
                if (_rel_type.val() == 'project') {
                    $.get(admin_url + 'projects/get_rel_project_data/' + $(this).val() + '/' + taskid,
                        function(project) {
                            $("select[name='milestone']").html(project.milestones);
                            if (typeof(_milestone_selected_data) != 'undefined') {
                                $("select[name='milestone']").val(_milestone_selected_data.id);
                                $('input[name="duedate"]').val(_milestone_selected_data.due_date)
                            }
                            $("select[name='milestone']").selectpicker('refresh');

                            $("#assignees").html(project.assignees);
                            if (typeof(_current_member) != 'undefined') {
                                $("#assignees").val(_current_member);
                            }
                            $("#assignees").selectpicker('refresh')
                            if (project.billing_type == 3) {
                                $('.task-hours').addClass('project-task-hours');
                            } else {
                                $('.task-hours').removeClass('project-task-hours');
                            }

                            if (project.deadline) {
                                var $duedate = $('#_task_modal #duedate');
                                var currentSelectedTaskDate = $duedate.val();
                                $duedate.attr('data-date-end-date', project.deadline);
                                $duedate.datetimepicker('destroy');
                                init_datepicker($duedate);

                                if (currentSelectedTaskDate) {
                                    var dateTask = new Date(unformat_date(currentSelectedTaskDate));
                                    var projectDeadline = new Date(project.deadline);
                                    if (dateTask > projectDeadline) {
                                        $duedate.val(project.deadline_formatted);
                                    }
                                }
                            } else {
                                reset_task_duedate_input();
                            }
                            init_project_details(_rel_type.val(), project.allow_to_view_tasks);
                        }, 'json');



                } else {
                    reset_task_duedate_input();
                }
            }
        });

        <?php if (!isset($task) && $rel_id != '') { ?>
        _rel_id.change();
        <?php } ?>

        _rel_type.on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
            if (previousValue == 'project') {
                $("#assignees").html(_allAssigneeSelect);
                if (typeof(_current_member) != 'undefined') {
                    $("#assignees").val(_current_member);
                }
                $("#assignees").selectpicker('refresh')
            }
        });

    });

    <?php if (isset($_milestone_selected_data)) { ?>
    _milestone_selected_data = '<?php echo json_encode($_milestone_selected_data); ?>';
    _milestone_selected_data = JSON.parse(_milestone_selected_data);
    <?php } ?>

    function task_rel_select() {
        var serverData = {};
        serverData.rel_id = _rel_id.val();
        data.type = _rel_type.val();
        init_ajax_search(_rel_type.val(), _rel_id, serverData);
    }

    function init_project_details(type, tasks_visible_to_customer) {
        var wrap = $('.non-project-details');
        var wrap_task_hours = $('.task-hours');
        if (type == 'project') {
            if (wrap_task_hours.hasClass('project-task-hours') == true) {
                wrap_task_hours.removeClass('hide');
            } else {
                wrap_task_hours.addClass('hide');
            }
            wrap.addClass('hide');
            $('.project-details').removeClass('hide');
        } else {
            wrap_task_hours.removeClass('hide');
            wrap.removeClass('hide');
            $('.project-details').addClass('hide');
            $('.task-visible-to-customer').addClass('hide').prop('checked', false);
        }
        if (typeof(tasks_visible_to_customer) != 'undefined') {
            if (tasks_visible_to_customer == 1) {
                $('.task-visible-to-customer').removeClass('hide');
                $('.task-visible-to-customer input').prop('checked', true);
            } else {
                $('.task-visible-to-customer').addClass('hide')
                $('.task-visible-to-customer input').prop('checked', false);
            }
        }
    }

    function reset_task_duedate_input() {
        var $duedate = $('#_task_modal #duedate');
        $duedate.removeAttr('data-date-end-date');
        $duedate.datetimepicker('destroy');
        init_datepicker($duedate);
    }
    </script>