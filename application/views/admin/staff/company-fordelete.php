<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($member); ?>
<div id="wrapper">
    <div class="content">
        <?php if (isset($member)) { ?>
        <?php $this->load->view('admin/staff/stats'); ?>
        <div class="member">
            <?php echo form_hidden('isedit'); ?>
            <?php echo form_hidden('memberid', $member->staffid); ?>
        </div>
        <?php } ?>
        <div class="row">
            <?php if (isset($member)) { ?>
            <div class="col-md-12">
                <?php if (total_rows(db_prefix() . 'departments', ['email' => $member->email]) > 0) { ?>
                <div class="alert alert-danger">
                    The staff member email exists also as support department email, according to the docs, the support
                    department email must be unique email in the system, you must change the staff email or the support
                    department email in order all the features to work properly.
                </div>
                <?php } ?>
                <div class="tw-flex tw-justify-between">
                    <h4 class="tw-mb-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
                        <?php echo e($member->firstname . ' ' . $member->lastname); ?>
                        <?php if ($member->last_activity && $member->staffid != get_staff_user_id()) { ?>
                        <small> - <?php echo _l('last_active'); ?>:
                            <span class="text-has-action" data-toggle="tooltip"
                                data-title="<?php echo e(_dt($member->last_activity)); ?>">
                                <?php echo e(time_ago($member->last_activity)); ?>
                            </span>
                        </small>
                        <?php } ?>
                    </h4>
                    <a href="#" onclick="small_table_full_view(); return false;" data-placement="left"
                        data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>"
                        class="toggle_view tw-mt-3 tw-shrink-0 tw-inline-flex tw-items-center tw-justify-center hover:tw-text-neutral-800 active:tw-text-neutral-800 hover:tw-bg-neutral-300 tw-h-10 tw-w-10 tw-rounded-full tw-bg-neutral-200 tw-text-neutral-500">
                        <i class="fa fa-expand"></i></a>
                </div>
            </div>
            <?php } ?>
            <?php echo form_open_multipart($this->uri->uri_string(), ['class' => 'staff-form', 'autocomplete' => 'off']); ?>
            <div class="col-md-<?php if (!isset($member)) {
    echo '8 col-md-offset-2';
} else {
    echo '5';
} ?>" id="small-table">
                <div class="panel_s">
                    <div class="panel-body ">
                        <div class="horizontal-scrollable-tabs panel-full-width-tabs">
                            <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                            <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                            <div class="horizontal-tabs">
                                <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_staff_profile" aria-controls="tab_staff_profile" role="tab"
                                            data-toggle="tab">
                                            <?php echo _l('Company Details'); ?>
                                        </a>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content tw-mt-5">
                               
                      
<?php $attrs = (isset($member) ? [] : ['autofocus' => true]); ?>
<?php $required = (isset($member) ? [] : ['required' => 1]); ?>

<?php $value = (isset($member) ? $member->firstname : ''); ?>
<?php echo render_input('companyname', '<small class="req text-danger">* </small> Company Name', $value, 'text', $required); ?>
<?php $value = (isset($member) ? $member->firstname : ''); ?>
<?php echo render_input('website', '<small class="req text-danger">* </small> Website', $value, 'url', $required); ?>
<?php $value = (isset($member) ? $member->firstname : ''); ?>
<?php echo render_input('firstname', 'staff_add_edit_firstname', $value, 'text'); ?>
<?php $value = (isset($member) ? $member->lastname : ''); ?>
<?php echo render_input('lastname', 'staff_add_edit_lastname', $value); ?>
<?php $value = (isset($member) ? $member->email : ''); ?>
<?php $value = (isset($member) ? $member->phonenumber : ''); ?>
<?php echo render_input('email', 'Email (for admin User)', $value, 'email', ['autocomplete' => 'off']); ?>
<?php $value = (isset($member) ? $member->email : ''); ?>
<?php echo render_input('phonenumber', '<small class="req text-danger">* </small> Phone / Mobile', $value, 'text', $required); ?>
<?php if (!isset($member) || is_admin() || !is_admin() && $member->admin == 0) { ?>
                                <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                       <input type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
                       <input type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1"/>
                         <label for="password" class="control-label"><?php echo _l('staff_add_edit_password'); ?></label>
                                <div class="input-group">
                                    <input type="password" class="form-control password" name="password" autocomplete="off">
                                    <span class="input-group-addon tw-border-l-0">
                                        <a href="#password" class="show_password"
                                            onclick="showPassword('password'); return false;"><i class="fa fa-eye"></i></a>
                                    </span>
                                    <span class="input-group-addon">
                                        <a href="#" class="generate_password"
                                            onclick="generatePassword(this);return false;"><i class="fa fa-refresh"></i></a>
                                    </span>
                                </div>
<?php } ?>
								<div class="tw-mt-2">
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
            </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
				s
            </div>
            
            <?php echo form_close(); ?>
          
        </div>
        <div class="btn-bottom-pusher"></div>
    </div>
    <?php init_tail(); ?>
    <script>
    $(function() {

        $('select[name="role"]').on('change', function() {
            var roleid = $(this).val();
            init_roles_permissions(roleid, true);
        });

        $('input[name="administrator"]').on('change', function() {
            var checked = $(this).prop('checked');
            var isNotStaffMember = $('.is-not-staff');
            if (checked == true) {
                isNotStaffMember.addClass('hide');
                $('.roles').find('input').prop('disabled', true).prop('checked', false);
            } else {
                isNotStaffMember.removeClass('hide');
                isNotStaffMember.find('input').prop('checked', false);
                $('.roles').find('.capability').not('[data-not-applicable="true"]').prop('disabled',
                    false)
            }
        });

        $('#is_not_staff').on('change', function() {
            var checked = $(this).prop('checked');
            var row_permission_leads = $('tr[data-name="leads"]');
            if (checked == true) {
                row_permission_leads.addClass('hide');
                row_permission_leads.find('input').prop('checked', false);
            } else {
                row_permission_leads.removeClass('hide');
            }
        });

        init_roles_permissions();

        appValidateForm($('.staff-form'), {
            firstname: 'required',
            lastname: 'required',
            username: 'required',
            password: {
                required: {
                    depends: function(element) {
                        return ($('input[name="isedit"]').length == 0) ? true : false
                    }
                }
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: admin_url + "misc/staff_email_exists",
                    type: 'post',
                    data: {
                        email: function() {
                            return $('input[name="email"]').val();
                        },
                        memberid: function() {
                            return $('input[name="memberid"]').val();
                        }
                    }
                }
            }
        });
    });
    </script>
    </body>
    </html>