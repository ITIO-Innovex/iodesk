<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            <h4 class="tw-mt-0 tw-font-semibold tw-text-lg"><i class="fa-solid fa-briefcase tw-mr-2"></i>Job Application Form</h4>
			<hr class="hr-panel-heading">
            <?php echo form_open(admin_url('hrd/job_application_form'), ['id' => 'job-application-form']); ?>
              <input type="hidden" name="status" id="job-application-status" value="<?php echo e($form['status'] ?? 'Draft'); ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="full_name" class="control-label">Full Name : <small class="req text-danger">* </small></label>
                    <input type="text" name="full_name" class="form-control" value="<?php echo e($form['full_name'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Sex : <small class="req text-danger">* </small></label>
                    <select name="sex" class="form-control" required>
                      <option value="">Select</option>
                      <option value="Male" <?php echo (isset($form['sex']) && $form['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                      <option value="Female" <?php echo (isset($form['sex']) && $form['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                      <option value="Other" <?php echo (isset($form['sex']) && $form['sex'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Applied Post : <small class="req text-danger">* </small></label>
                    <input type="text" name="applied_post" class="form-control" value="<?php echo e($form['applied_post'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile No : <small class="req text-danger">* </small></label>
                    <input type="text" name="mobile_no" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['mobile_no'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Alternative No : </label>
                    <input type="text" name="alternative_no" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['alternative_no'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Email : <small class="req text-danger">* </small></label>
                    <input type="email" name="email" class="form-control" value="<?php echo e($form['email'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Marital Status : <small class="req text-danger">* </small></label>
                    <select name="marital_status" class="form-control" required>
                      <option value="">Select</option>
                      <option value="Single" <?php echo (isset($form['marital_status']) && $form['marital_status'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                      <option value="Married" <?php echo (isset($form['marital_status']) && $form['marital_status'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                      <option value="Divorced" <?php echo (isset($form['marital_status']) && $form['marital_status'] == 'Divorced') ? 'selected' : ''; ?>>Divorced</option>
                      <option value="Widowed" <?php echo (isset($form['marital_status']) && $form['marital_status'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Address with Pincode : <small class="req text-danger">* </small></label>
                    <textarea name="address_with_pincode" class="form-control" rows="3" required><?php echo e($form['address_with_pincode'] ?? ''); ?></textarea>
                  </div>
                </div>
              </div>
              </div>
              </div>
              <hr>
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Educational Qualifications</h4></div>
                <?php for ($i = 1; $i <= 5; $i++): 
				$req="required";
				$star="*";
				if($i > 2){ $req="";$star="";}
				?>
                <div class="col-md-12">
                  <h5>Qualification <?php echo $i; ?></h5>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Degree : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="edu<?php echo $i; ?>_degree" class="form-control" value="<?php echo e($form['edu' . $i . '_degree'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>University : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="edu<?php echo $i; ?>_university" class="form-control" value="<?php echo e($form['edu' . $i . '_university'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Major Subject : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="edu<?php echo $i; ?>_major_subject" class="form-control" value="<?php echo e($form['edu' . $i . '_major_subject'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Year : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="edu<?php echo $i; ?>_year" class="form-control" value="<?php echo e($form['edu' . $i . '_year'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <?php if ($i < 5): ?>
                <div class="col-md-12"><hr></div>
                <?php endif; ?>
                <?php endfor; ?>
              </div>
              </div>
              <hr>
    
              <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Family Details</h4></div>
                <?php for ($i = 1; $i <= 6; $i++): 
				$req="required";
				$star="*";
				if($i > 3){ $req="";$star="";}
				?>
                <div class="col-md-12">
                  <h5>Family Member <?php echo $i; ?></h5>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Name : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="fam<?php echo $i; ?>_name" class="form-control" value="<?php echo e($form['fam' . $i . '_name'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Age : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="number" name="fam<?php echo $i; ?>_age" class="form-control" value="<?php echo e($form['fam' . $i . '_age'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Relationship : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="fam<?php echo $i; ?>_relationship" class="form-control" value="<?php echo e($form['fam' . $i . '_relationship'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Occupation : <small class="req text-danger"><?php echo $star;?> </small></label>
                    <input type="text" name="fam<?php echo $i; ?>_occupation" class="form-control" value="<?php echo e($form['fam' . $i . '_occupation'] ?? ''); ?>" <?php echo $req;?>>
                  </div>
                </div>
                <?php if ($i < 6): ?>
                <div class="col-md-12"><hr></div>
                <?php endif; ?>
                <?php endfor; ?>
              </div>
              </div>
              <hr>
    
              <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Work Experience</h4></div>
                <?php for ($i = 1; $i <= 2; $i++): ?>
                <div class="col-md-12">
                  <h5>Job Experience <?php echo $i; ?></h5>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Job Title : <small class="req text-danger">* </small></label>
                    <input type="text" name="job<?php echo $i; ?>_title" class="form-control" value="<?php echo e($form['job' . $i . '_title'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Start Date : <small class="req text-danger">* </small></label>
                    <input type="date" name="job<?php echo $i; ?>_start_date" class="form-control" value="<?php echo e($form['job' . $i . '_start_date'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>End Date : <small class="req text-danger">* </small></label>
                    <input type="date" name="job<?php echo $i; ?>_end_date" class="form-control" value="<?php echo e($form['job' . $i . '_end_date'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Company : <small class="req text-danger">* </small></label>
                    <input type="text" name="job<?php echo $i; ?>_company" class="form-control" value="<?php echo e($form['job' . $i . '_company'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Designation : <small class="req text-danger">* </small></label>
                    <input type="text" name="job<?php echo $i; ?>_designation" class="form-control" value="<?php echo e($form['job' . $i . '_designation'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Reason for Leaving : <small class="req text-danger">* </small></label>
                    <input type="text" name="job<?php echo $i; ?>_reason_for_leaving" class="form-control" value="<?php echo e($form['job' . $i . '_reason_for_leaving'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Start Salary : <small class="req text-danger">* </small></label>
                    <input type="text" name="job<?php echo $i; ?>_start_salary" class="form-control" value="<?php echo e($form['job' . $i . '_start_salary'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>End Salary : <small class="req text-danger">* </small></label>
                    <input type="text" name="job<?php echo $i; ?>_end_salary" class="form-control" value="<?php echo e($form['job' . $i . '_end_salary'] ?? ''); ?>" required>
                  </div>
                </div>
                <?php if ($i < 2): ?>
                <div class="col-md-12"><hr></div>
                <?php endif; ?>
                <?php endfor; ?>
              </div>
              </div>
              <hr>
    
              <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Additional Information</h4></div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Has LinkedIn Profile : <small class="req text-danger">* </small></label>
                    <select name="has_linkedin" class="form-control" required>
                      <option value="">Select</option>
                      <option value="Yes" <?php echo (isset($form['has_linkedin']) && $form['has_linkedin'] == 'Yes') ? 'selected' : ''; ?>>Yes</option>
                      <option value="No" <?php echo (isset($form['has_linkedin']) && $form['has_linkedin'] == 'No') ? 'selected' : ''; ?>>No</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>LinkedIn Connections : </label>
                    <input type="text" name="linkedin_connections" class="form-control" value="<?php echo e($form['linkedin_connections'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Major Skill 1 : <small class="req text-danger">* </small></label>
                    <input type="text" name="major_skill_1" class="form-control" value="<?php echo e($form['major_skill_1'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label>Major Skill 2 : <small class="req text-danger">* </small></label>
                    <input type="text" name="major_skill_2" class="form-control" value="<?php echo e($form['major_skill_2'] ?? ''); ?>" required>
                  </div>
                </div>
                <?php /*?><div class="col-md-6">
                  <div class="form-group">
                    <label>Candidate Signature : </label>
                    <input type="text" name="candidate_signature" class="form-control" value="<?php echo e($form['candidate_signature'] ?? ''); ?>">
                  </div>
                </div><?php */?>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Coordinating Person Name : </label>
                    <input type="text" name="coordinating_person_name" class="form-control" value="<?php echo e($form['coordinating_person_name'] ?? ''); ?>">
                  </div>
                </div>
              </div>
              </div>
              <hr>
    
              <?php /*?><div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="row">
<div class="col-md-12"><h4 class="text-success">Interview Details</h4></div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Interviewed By 1 : </label>
                    <input type="text" name="interviewed_by_1" class="form-control" value="<?php echo e($form['interviewed_by_1'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Interviewed By 2 : </label>
                    <input type="text" name="interviewed_by_2" class="form-control" value="<?php echo e($form['interviewed_by_2'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Date of Joining : </label>
                    <input type="datetime-local" name="doj_datetime" class="form-control" value="<?php echo e($form['doj_datetime'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Offered Salary : </label>
                    <input type="text" name="offered_salary" class="form-control" value="<?php echo e($form['offered_salary'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Offered Designation : </label>
                    <input type="text" name="offered_designation" class="form-control" value="<?php echo e($form['offered_designation'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Interview Remarks 1 : </label>
                    <textarea name="interview_remarks_1" class="form-control" rows="3"><?php echo e($form['interview_remarks_1'] ?? ''); ?></textarea>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Interview Remarks 2 : </label>
                    <textarea name="interview_remarks_2" class="form-control" rows="3"><?php echo e($form['interview_remarks_2'] ?? ''); ?></textarea>
                  </div>
                </div>
              </div>
              </div>
              <hr><?php */?>
			  
    
              <div class="tw-flex tw-gap-2">
                <button type="button" class="btn btn-primary" data-status="Interviewed" id="save-interview">Mark as Interviewed</button>
                <?php /*?><button type="button" class="btn btn-success" data-status="Selected" id="save-select">Mark as Selected</button>
                <button type="button" class="btn btn-danger" data-status="Rejected" id="save-reject">Mark as Rejected</button><?php */?>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
  $(function() {
    $('#save-interview, #save-select, #save-reject').on('click', function() {
      var status = $(this).data('status');
      $('#job-application-status').val(status);
      $('#job-application-form').submit();
    });
  });
</script>
</body></html>
