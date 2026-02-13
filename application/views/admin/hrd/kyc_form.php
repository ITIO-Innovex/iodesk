<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg"><i class="fa-solid fa-fingerprint tw-mr-2"></i>KYC Form</h4>
            <?php echo form_open_multipart(admin_url('hrd/kyc_form'), ['id' => 'kyc-form']); ?>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">BACKGROUND CHECK FORM</h4>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Candidate Name</label>
                      <input type="text" name="candidate_name" class="form-control" value="<?php echo e($form['candidate_name'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Father Name</label>
                      <input type="text" name="father_name" class="form-control" value="<?php echo e($form['father_name'] ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Mother Name</label>
                      <input type="text" name="mother_name" class="form-control" value="<?php echo e($form['mother_name'] ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Date of Birth</label>
                      <input type="date" name="date_of_birth" class="form-control" value="<?php echo e($form['date_of_birth'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Marital Status</label>
                      <input type="text" name="marital_status" class="form-control" value="<?php echo e($form['marital_status'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="email" class="form-control" value="<?php echo e($form['email'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Contact Number</label>
                      <input type="text" name="contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['contact_number'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Alternate Contact Number</label>
                      <input type="text" name="alternate_contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['alternate_contact_number'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Aadhaar Number</label>
                      <input type="text" name="aadhaar_number" class="form-control" maxlength="12" pattern="\d{12}" inputmode="numeric" value="<?php echo e($form['aadhaar_number'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>PAN Number</label>
                      <input type="text" name="pan_number" class="form-control" maxlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}" value="<?php echo e($form['pan_number'] ?? ''); ?>" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">PRESENT/CURRENT ADDRESS DETAILS</h4>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Complete Address</label>
                      <textarea name="present_complete_address" class="form-control" rows="2" required><?php echo e($form['present_complete_address'] ?? ''); ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Landmark</label>
                      <input type="text" name="present_landmark" class="form-control" value="<?php echo e($form['present_landmark'] ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>City</label>
                      <input type="text" name="present_city" class="form-control" value="<?php echo e($form['present_city'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>State</label>
                      <input type="text" name="present_state" class="form-control" value="<?php echo e($form['present_state'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Police Station</label>
                      <input type="text" name="present_police_station" class="form-control" value="<?php echo e($form['present_police_station'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>PIN Code</label>
                      <input type="text" name="present_pin_code" class="form-control" maxlength="6" pattern="\d{6}" inputmode="numeric" value="<?php echo e($form['present_pin_code'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Duration of Stay From</label>
                      <input type="date" name="present_stay_from" class="form-control" value="<?php echo e($form['present_stay_from'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Duration of Stay To</label>
                      <input type="date" name="present_stay_to" class="form-control" value="<?php echo e($form['present_stay_to'] ?? ''); ?>" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">PERMANENT ADDRESS DETAILS</h4>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Complete Address</label>
                      <textarea name="permanent_complete_address" class="form-control" rows="2" required><?php echo e($form['permanent_complete_address'] ?? ''); ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Landmark</label>
                      <input type="text" name="permanent_landmark" class="form-control" value="<?php echo e($form['permanent_landmark'] ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>City</label>
                      <input type="text" name="permanent_city" class="form-control" value="<?php echo e($form['permanent_city'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>State</label>
                      <input type="text" name="permanent_state" class="form-control" value="<?php echo e($form['permanent_state'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>PIN Code</label>
                      <input type="text" name="permanent_pin_code" class="form-control" maxlength="6" pattern="\d{6}" inputmode="numeric" value="<?php echo e($form['permanent_pin_code'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Police Station</label>
                      <input type="text" name="permanent_police_station" class="form-control" value="<?php echo e($form['permanent_police_station'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Duration of Stay From</label>
                      <input type="date" name="permanent_stay_from" class="form-control" value="<?php echo e($form['permanent_stay_from'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Duration of Stay To</label>
                      <input type="date" name="permanent_stay_to" class="form-control" value="<?php echo e($form['permanent_stay_to'] ?? ''); ?>" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">EDUCATION QUALIFICATION DETAILS</h4>
                  </div>
                  <div class="col-md-12 ">
                    <div class="row mtop10">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Name of School / College</label>
                          <input type="text" name="edu1_institute_name" class="form-control" placeholder="Name of School / College" value="<?php echo e($form['edu1_institute_name'] ?? ''); ?>" required>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Course Name</label>
                          <input type="text" name="edu1_course_name" class="form-control" placeholder="Course Name" value="<?php echo e($form['edu1_course_name'] ?? ''); ?>" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Passing Year</label>
                          <input type="text" name="edu1_passing_year" class="form-control" placeholder="Passing Year" value="<?php echo e($form['edu1_passing_year'] ?? ''); ?>" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Reg. / Enroll. No</label>
                          <input type="text" name="edu1_registration_number" class="form-control" placeholder="Reg. / Enroll. No" value="<?php echo e($form['edu1_registration_number'] ?? ''); ?>" required>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Mode</label>
                          <select name="edu1_mode" class="form-control" required>
                            <option value="">Mode</option>
                            <?php $m1 = $form['edu1_mode'] ?? ''; ?>
                            <option value="Regular" <?php echo $m1==='Regular'?'selected':''; ?>>Regular</option>
                            <option value="Correspondence" <?php echo $m1==='Correspondence'?'selected':''; ?>>Correspondence</option>
                            <option value="Open" <?php echo $m1==='Open'?'selected':''; ?>>Open</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 ">
                    <div class="row mtop10">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Name of College / University</label>
                          <input type="text" name="edu2_institute_name" class="form-control" placeholder="Name of College / University" value="<?php echo e($form['edu2_institute_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Course Name</label>
                          <input type="text" name="edu2_course_name" class="form-control" placeholder="Course Name" value="<?php echo e($form['edu2_course_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Passing Year</label>
                          <input type="text" name="edu2_passing_year" class="form-control" placeholder="Passing Year" value="<?php echo e($form['edu2_passing_year'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Reg. / Enroll. No</label>
                          <input type="text" name="edu2_registration_number" class="form-control" placeholder="Reg. / Enroll. No" value="<?php echo e($form['edu2_registration_number'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Mode</label>
                          <select name="edu2_mode" class="form-control">
                            <option value="">Mode</option>
                            <?php $m2 = $form['edu2_mode'] ?? ''; ?>
                            <option value="Regular" <?php echo $m2==='Regular'?'selected':''; ?>>Regular</option>
                            <option value="Correspondence" <?php echo $m2==='Correspondence'?'selected':''; ?>>Correspondence</option>
                            <option value="Open" <?php echo $m2==='Open'?'selected':''; ?>>Open</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-12 ">
                    <div class="row mtop10">
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Name of College / Institute</label>
                          <input type="text" name="edu3_institute_name" class="form-control" placeholder="Name of College / Institute" value="<?php echo e($form['edu3_institute_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Course Name</label>
                          <input type="text" name="edu3_course_name" class="form-control" placeholder="Course Name" value="<?php echo e($form['edu3_course_name'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Passing Year</label>
                          <input type="text" name="edu3_passing_year" class="form-control" placeholder="Passing Year" value="<?php echo e($form['edu3_passing_year'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Reg. / Enroll. No</label>
                          <input type="text" name="edu3_registration_number" class="form-control" placeholder="Reg. / Enroll. No" value="<?php echo e($form['edu3_registration_number'] ?? ''); ?>">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group">
                          <label>Mode</label>
                          <select name="edu3_mode" class="form-control">
                            <option value="">Mode</option>
                            <?php $m3 = $form['edu3_mode'] ?? ''; ?>
                            <option value="Regular" <?php echo $m3==='Regular'?'selected':''; ?>>Regular</option>
                            <option value="Correspondence" <?php echo $m3==='Correspondence'?'selected':''; ?>>Correspondence</option>
                            <option value="Open" <?php echo $m3==='Open'?'selected':''; ?>>Open</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">ORGANIZATION DETAILS</h4>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Organization Name</label>
                      <input type="text" name="org1_name" class="form-control" placeholder="Organization Name" value="<?php echo e($form['org1_name'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Designation</label>
                      <input type="text" name="org1_designation" class="form-control" placeholder="Designation" value="<?php echo e($form['org1_designation'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-12 mtop10">
                    <div class="form-group">
                      <label>Address</label>
                      <textarea name="org1_address" class="form-control" rows="2" placeholder="Address" required><?php echo e($form['org1_address'] ?? ''); ?></textarea>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Employee Code</label>
                      <input type="text" name="org1_employee_code" class="form-control" placeholder="Employee Code" value="<?php echo e($form['org1_employee_code'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Date of Joining</label>
                      <input type="date" name="org1_date_of_joining" class="form-control" value="<?php echo e($form['org1_date_of_joining'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Last Working Day</label>
                      <input type="date" name="org1_last_working_day" class="form-control" value="<?php echo e($form['org1_last_working_day'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Salary CTC</label>
                      <input type="text" name="org1_salary_ctc" class="form-control" placeholder="Salary CTC" value="<?php echo e($form['org1_salary_ctc'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-8 mtop10">
                    <div class="form-group">
                      <label>Reason for Leaving</label>
                      <input type="text" name="org1_reason_for_leaving" class="form-control" placeholder="Reason for Leaving" value="<?php echo e($form['org1_reason_for_leaving'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Reporting Manager Name</label>
                      <input type="text" name="org1_reporting_manager_name" class="form-control" placeholder="Reporting Manager Name" value="<?php echo e($form['org1_reporting_manager_name'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Reporting Manager Contact</label>
                      <input type="text" name="org1_reporting_manager_contact" class="form-control" placeholder="Reporting Manager Contact" value="<?php echo e($form['org1_reporting_manager_contact'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>Reporting Manager Email</label>
                      <input type="email" name="org1_reporting_manager_email" class="form-control" placeholder="Reporting Manager Email" value="<?php echo e($form['org1_reporting_manager_email'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>HR1 Name</label>
                      <input type="text" name="org1_hr1_name" class="form-control" placeholder="HR1 Name" value="<?php echo e($form['org1_hr1_name'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>HR1 Contact</label>
                      <input type="text" name="org1_hr1_contact" class="form-control" placeholder="HR1 Contact" value="<?php echo e($form['org1_hr1_contact'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>HR1 Email</label>
                      <input type="email" name="org1_hr1_email" class="form-control" placeholder="HR1 Email" value="<?php echo e($form['org1_hr1_email'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>HR2 Name</label>
                      <input type="text" name="org1_hr2_name" class="form-control" placeholder="HR2 Name" value="<?php echo e($form['org1_hr2_name'] ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>HR2 Contact</label>
                      <input type="text" name="org1_hr2_contact" class="form-control" placeholder="HR2 Contact" value="<?php echo e($form['org1_hr2_contact'] ?? ''); ?>">
                    </div>
                  </div>
                  <div class="col-md-4 mtop10">
                    <div class="form-group">
                      <label>HR2 Email</label>
                      <input type="email" name="org1_hr2_email" class="form-control" placeholder="HR2 Email" value="<?php echo e($form['org1_hr2_email'] ?? ''); ?>">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">PROFESSIONAL REFERENCE DETAILS</h4>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Referee 1 Name</label>
                      <input type="text" name="referee1_name" class="form-control" placeholder="Referee 1 Name" value="<?php echo e($form['referee1_name'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Organization</label>
                      <input type="text" name="referee1_organization" class="form-control" placeholder="Organization" value="<?php echo e($form['referee1_organization'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Designation</label>
                      <input type="text" name="referee1_designation" class="form-control" placeholder="Designation" value="<?php echo e($form['referee1_designation'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Contact</label>
                      <input type="text" name="referee1_contact" class="form-control" placeholder="Contact" value="<?php echo e($form['referee1_contact'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="referee1_email" class="form-control" placeholder="Email" value="<?php echo e($form['referee1_email'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Referee 2 Name</label>
                      <input type="text" name="referee2_name" class="form-control" placeholder="Referee 2 Name" value="<?php echo e($form['referee2_name'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Organization</label>
                      <input type="text" name="referee2_organization" class="form-control" placeholder="Organization" value="<?php echo e($form['referee2_organization'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Designation</label>
                      <input type="text" name="referee2_designation" class="form-control" placeholder="Designation" value="<?php echo e($form['referee2_designation'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Contact</label>
                      <input type="text" name="referee2_contact" class="form-control" placeholder="Contact" value="<?php echo e($form['referee2_contact'] ?? ''); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label>Email</label>
                      <input type="email" name="referee2_email" class="form-control" placeholder="Email" value="<?php echo e($form['referee2_email'] ?? ''); ?>" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <div class="col-md-12">
                    <h4 class="tw-font-semibold text-center tw-font-semibold text-center text-success">MANDATORY DOCUMENTS TO PROVIDE</h4>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Education Verification Doc</label>
                      <input type="file" name="education_verification_doc" class="form-control">
                      <?php if (!empty($form['education_verification_doc'])) { ?>
                      <a href="<?php echo base_url($form['education_verification_doc']); ?>" target="_blank">View</a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Employment Verification Doc</label>
                      <input type="file" name="employment_verification_doc" class="form-control">
                      <?php if (!empty($form['employment_verification_doc'])) { ?>
                      <a href="<?php echo base_url($form['employment_verification_doc']); ?>" target="_blank">View</a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Address/Criminal Verification Doc</label>
                      <input type="file" name="address_criminal_verification_doc" class="form-control">
                      <?php if (!empty($form['address_criminal_verification_doc'])) { ?>
                      <a href="<?php echo base_url($form['address_criminal_verification_doc']); ?>" target="_blank">View</a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>Identity Verification Doc</label>
                      <input type="file" name="identity_verification_doc" class="form-control">
                      <?php if (!empty($form['identity_verification_doc'])) { ?>
                      <a href="<?php echo base_url($form['identity_verification_doc']); ?>" target="_blank">View</a>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>CIBIL Verification Doc</label>
                      <input type="file" name="cibil_verification_doc" class="form-control">
                      <?php if (!empty($form['cibil_verification_doc'])) { ?>
                      <a href="<?php echo base_url($form['cibil_verification_doc']); ?>" target="_blank">View</a>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tw-flex tw-gap-2 mtop10">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <?php echo form_close(); ?> </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>