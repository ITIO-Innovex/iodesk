<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="tw-flex tw-justify-between tw-items-center tw-mb-4">
                            <h4 class="tw-my-0 tw-font-semibold"><?php echo $title; ?></h4>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered dt-table" id="kycFormTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Candidate Name</th>
                                        <th>Father Name</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($records)) { ?>
                                        <?php $cnt = 1; foreach ($records as $record) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td><?php echo htmlspecialchars($record['candidate_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['father_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['contact_number'] ?? ''); ?></td>
                                                <td>
                                                    <?php
                                                    $s = $record['status'] ?? 'Draft';
                                                    $statusClass = 'label-warning';
                                                    if ($s === 'Submitted') $statusClass = 'label-info';
                                                    if ($s === 'Verified') $statusClass = 'label-success';
                                                    if ($s === 'Rejected') $statusClass = 'label-danger';
                                                    ?>
                                                    <span class="label <?php echo $statusClass; ?>"><?php echo htmlspecialchars($s); ?></span>
                                                </td>
                                                <td><?php echo !empty($record['created_at']) ? date('d-m-Y H:i', strtotime($record['created_at'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs view-kyc-details" data-id="<?php echo $record['id']; ?>" title="View"><i class="fa fa-eye"></i></button>
                                                    <button type="button" class="btn btn-primary btn-xs edit-kyc-details" data-id="<?php echo $record['id']; ?>" title="Edit"><i class="fa fa-pencil"></i></button>
                                                    <a href="<?php echo admin_url('powerform/download_kyc_form_pdf/' . $record['id']); ?>" class="btn btn-danger btn-xs" title="Download PDF"><i class="fa-solid fa-file-pdf"></i></a>
                                                    <button type="button" class="btn btn-danger btn-xs delete-kyc-details" data-id="<?php echo $record['id']; ?>" title="Delete"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewKycModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                <h4 class="modal-title">KYC Form Details</h4>
            </div>
            <div class="modal-body" id="viewKycContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editKycModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editKycForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="kyc_edit_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Edit KYC Form</h4>
                </div>
                <div class="modal-body" style="max-height:70vh;overflow-y:auto;">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#kycBasic" data-toggle="tab">Basic</a></li>
                        <li><a href="#kycPresent" data-toggle="tab">Present Address</a></li>
                        <li><a href="#kycPermanent" data-toggle="tab">Permanent Address</a></li>
                        <li><a href="#kycEdu" data-toggle="tab">Education</a></li>
                        <li><a href="#kycOrg" data-toggle="tab">Organization</a></li>
                        <li><a href="#kycRef" data-toggle="tab">Referees</a></li>
                    </ul>
                    <div class="tab-content mtop15">
                        <div class="tab-pane active" id="kycBasic">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Candidate Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="candidate_name" id="kyc_candidate_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Father Name</label>
                                        <input type="text" class="form-control" name="father_name" id="kyc_father_name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mother Name</label>
                                        <input type="text" class="form-control" name="mother_name" id="kyc_mother_name">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date of Birth</label>
                                        <input type="date" class="form-control" name="date_of_birth" id="kyc_date_of_birth">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Marital Status</label>
                                        <input type="text" class="form-control" name="marital_status" id="kyc_marital_status">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="kyc_status">
                                            <option value="Draft">Draft</option>
                                            <option value="Submitted">Submitted</option>
                                            <option value="Verified">Verified</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="kyc_email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="text" class="form-control" name="contact_number" id="kyc_contact_number">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Alternate Contact</label>
                                        <input type="text" class="form-control" name="alternate_contact_number" id="kyc_alternate_contact_number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Aadhaar Number</label>
                                        <input type="text" class="form-control" name="aadhaar_number" id="kyc_aadhaar_number">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>PAN Number</label>
                                        <input type="text" class="form-control" name="pan_number" id="kyc_pan_number">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kycPresent">
                            <div class="form-group">
                                <label>Complete Address</label>
                                <textarea class="form-control" name="present_complete_address" id="kyc_present_complete_address" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Landmark</label>
                                        <input type="text" class="form-control" name="present_landmark" id="kyc_present_landmark">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="present_city" id="kyc_present_city">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" class="form-control" name="present_state" id="kyc_present_state">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pin Code</label>
                                        <input type="text" class="form-control" name="present_pin_code" id="kyc_present_pin_code">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Police Station</label>
                                        <input type="text" class="form-control" name="present_police_station" id="kyc_present_police_station">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Stay From</label>
                                        <input type="text" class="form-control" name="present_stay_from" id="kyc_present_stay_from">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Stay To</label>
                                        <input type="text" class="form-control" name="present_stay_to" id="kyc_present_stay_to">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kycPermanent">
                            <div class="form-group">
                                <label>Complete Address</label>
                                <textarea class="form-control" name="permanent_complete_address" id="kyc_permanent_complete_address" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Landmark</label>
                                        <input type="text" class="form-control" name="permanent_landmark" id="kyc_permanent_landmark">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="permanent_city" id="kyc_permanent_city">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type="text" class="form-control" name="permanent_state" id="kyc_permanent_state">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Pin Code</label>
                                        <input type="text" class="form-control" name="permanent_pin_code" id="kyc_permanent_pin_code">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Police Station</label>
                                        <input type="text" class="form-control" name="permanent_police_station" id="kyc_permanent_police_station">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Stay From</label>
                                        <input type="text" class="form-control" name="permanent_stay_from" id="kyc_permanent_stay_from">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Stay To</label>
                                        <input type="text" class="form-control" name="permanent_stay_to" id="kyc_permanent_stay_to">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kycEdu">
                            <?php for ($i = 1; $i <= 3; $i++) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Education <?php echo $i; ?></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Institute Name</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_institute_name" id="kyc_edu<?php echo $i; ?>_institute_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Course Name</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_course_name" id="kyc_edu<?php echo $i; ?>_course_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Passing Year</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_passing_year" id="kyc_edu<?php echo $i; ?>_passing_year" placeholder="YYYY">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Registration Number</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_registration_number" id="kyc_edu<?php echo $i; ?>_registration_number">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mode</label>
                                                <select class="form-control" name="edu<?php echo $i; ?>_mode" id="kyc_edu<?php echo $i; ?>_mode">
                                                    <option value="">--</option>
                                                    <option value="Regular">Regular</option>
                                                    <option value="Correspondence">Correspondence</option>
                                                    <option value="Open">Open</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="kycOrg">
                            <div class="form-group">
                                <label>Organization Name</label>
                                <input type="text" class="form-control" name="org1_name" id="kyc_org1_name">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="org1_address" id="kyc_org1_address" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Designation</label>
                                        <input type="text" class="form-control" name="org1_designation" id="kyc_org1_designation">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Employee Code</label>
                                        <input type="text" class="form-control" name="org1_employee_code" id="kyc_org1_employee_code">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Date of Joining</label>
                                        <input type="date" class="form-control" name="org1_date_of_joining" id="kyc_org1_date_of_joining">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Last Working Day</label>
                                        <input type="date" class="form-control" name="org1_last_working_day" id="kyc_org1_last_working_day">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Salary CTC</label>
                                        <input type="text" class="form-control" name="org1_salary_ctc" id="kyc_org1_salary_ctc">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Reason for Leaving</label>
                                <input type="text" class="form-control" name="org1_reason_for_leaving" id="kyc_org1_reason_for_leaving">
                            </div>
                            <h5 class="tw-font-semibold tw-mb-2">Reporting Manager</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="org1_reporting_manager_name" id="kyc_org1_reporting_manager_name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" class="form-control" name="org1_reporting_manager_contact" id="kyc_org1_reporting_manager_contact">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="org1_reporting_manager_email" id="kyc_org1_reporting_manager_email">
                                    </div>
                                </div>
                            </div>
                            <h5 class="tw-font-semibold tw-mb-2">HR 1</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="org1_hr1_name" id="kyc_org1_hr1_name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" class="form-control" name="org1_hr1_contact" id="kyc_org1_hr1_contact">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="org1_hr1_email" id="kyc_org1_hr1_email">
                                    </div>
                                </div>
                            </div>
                            <h5 class="tw-font-semibold tw-mb-2">HR 2</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" class="form-control" name="org1_hr2_name" id="kyc_org1_hr2_name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="text" class="form-control" name="org1_hr2_contact" id="kyc_org1_hr2_contact">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="org1_hr2_email" id="kyc_org1_hr2_email">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="kycRef">
                            <div class="panel panel-default">
                                <div class="panel-heading">Referee 1</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="referee1_name" id="kyc_referee1_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Organization</label>
                                                <input type="text" class="form-control" name="referee1_organization" id="kyc_referee1_organization">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Designation</label>
                                                <input type="text" class="form-control" name="referee1_designation" id="kyc_referee1_designation">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact</label>
                                                <input type="text" class="form-control" name="referee1_contact" id="kyc_referee1_contact">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="referee1_email" id="kyc_referee1_email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">Referee 2</div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="referee2_name" id="kyc_referee2_name">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Organization</label>
                                                <input type="text" class="form-control" name="referee2_organization" id="kyc_referee2_organization">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Designation</label>
                                                <input type="text" class="form-control" name="referee2_designation" id="kyc_referee2_designation">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Contact</label>
                                                <input type="text" class="form-control" name="referee2_contact" id="kyc_referee2_contact">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" class="form-control" name="referee2_email" id="kyc_referee2_email">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="kycSaveBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
var kycBaseUrl = '<?php echo base_url(); ?>';
$(function() {
    function esc(v) { return v == null || v === '' ? '-' : String(v); }
    function statusClass(s) {
        if (s === 'Submitted') return 'label-info';
        if (s === 'Verified') return 'label-success';
        if (s === 'Rejected') return 'label-danger';
        return 'label-warning';
    }
    function docLink(path, label) {
        if (!path) return '-';
        var url = kycBaseUrl + (path.indexOf('http') === 0 ? '' : path);
        return '<a href="' + url + '" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-download"></i> ' + (label || 'Download') + '</a>';
    }
    function buildViewHtml(d) {
        var h = '<div class="row"><div class="col-md-12">';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3">Basic Information</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Candidate Name</th><td>' + esc(d.candidate_name) + '</td><th width="22%">Father Name</th><td>' + esc(d.father_name) + '</td></tr>';
        h += '<tr><th>Mother Name</th><td>' + esc(d.mother_name) + '</td><th>Date of Birth</th><td>' + esc(d.date_of_birth) + '</td></tr>';
        h += '<tr><th>Marital Status</th><td>' + esc(d.marital_status) + '</td><th>Status</th><td><span class="label ' + statusClass(d.status) + '">' + esc(d.status) + '</span></td></tr>';
        h += '<tr><th>Email</th><td>' + esc(d.email) + '</td><th>Contact Number</th><td>' + esc(d.contact_number) + '</td></tr>';
        h += '<tr><th>Alternate Contact</th><td>' + esc(d.alternate_contact_number) + '</td><th>Aadhaar / PAN</th><td>' + esc(d.aadhaar_number) + ' / ' + esc(d.pan_number) + '</td></tr>';
        h += '</table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Present Address</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Complete Address</th><td>' + esc(d.present_complete_address) + '</td></tr>';
        h += '<tr><th>Landmark</th><td>' + esc(d.present_landmark) + '</td><th>City / State</th><td>' + esc(d.present_city) + ' / ' + esc(d.present_state) + '</td></tr>';
        h += '<tr><th>Pin Code</th><td>' + esc(d.present_pin_code) + '</td><th>Police Station</th><td>' + esc(d.present_police_station) + '</td></tr>';
        h += '</table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Permanent Address</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Complete Address</th><td>' + esc(d.permanent_complete_address) + '</td></tr>';
        h += '<tr><th>Landmark</th><td>' + esc(d.permanent_landmark) + '</td><th>City / State</th><td>' + esc(d.permanent_city) + ' / ' + esc(d.permanent_state) + '</td></tr>';
        h += '</table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Education</h5>';
        h += '<table class="table table-bordered table-condensed"><thead><tr><th>#</th><th>Institute</th><th>Course</th><th>Year</th><th>Mode</th></tr></thead><tbody>';
        for (var i = 1; i <= 3; i++) {
            var inst = d['edu' + i + '_institute_name'], course = d['edu' + i + '_course_name'], yr = d['edu' + i + '_passing_year'], mode = d['edu' + i + '_mode'];
            if (inst || course || yr) h += '<tr><td>' + i + '</td><td>' + esc(inst) + '</td><td>' + esc(course) + '</td><td>' + esc(yr) + '</td><td>' + esc(mode) + '</td></tr>';
        }
        h += '</tbody></table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Organization</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Name</th><td>' + esc(d.org1_name) + '</td></tr>';
        h += '<tr><th>Address</th><td>' + esc(d.org1_address) + '</td></tr>';
        h += '<tr><th>Designation</th><td>' + esc(d.org1_designation) + '</td><th>Employee Code</th><td>' + esc(d.org1_employee_code) + '</td></tr>';
        h += '<tr><th>DOJ / LWD</th><td>' + esc(d.org1_date_of_joining) + ' / ' + esc(d.org1_last_working_day) + '</td><th>Salary CTC</th><td>' + esc(d.org1_salary_ctc) + '</td></tr>';
        h += '<tr><th>Reporting Manager</th><td>' + esc(d.org1_reporting_manager_name) + ' - ' + esc(d.org1_reporting_manager_contact) + '</td></tr>';
        h += '</table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Referees</h5>';
        h += '<table class="table table-bordered table-condensed"><tr><th>Referee 1</th><td>' + esc(d.referee1_name) + ' - ' + esc(d.referee1_organization) + ' - ' + esc(d.referee1_contact) + '</td></tr>';
        h += '<tr><th>Referee 2</th><td>' + esc(d.referee2_name) + ' - ' + esc(d.referee2_organization) + ' - ' + esc(d.referee2_contact) + '</td></tr></table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Documents</h5>';
        h += '<table class="table table-bordered table-condensed">';
        h += '<tr><th width="22%">Education Verification</th><td>' + docLink(d.education_verification_doc, 'Download') + '</td></tr>';
        h += '<tr><th>Employment Verification</th><td>' + docLink(d.employment_verification_doc, 'Download') + '</td></tr>';
        h += '<tr><th>Address/Criminal Verification</th><td>' + docLink(d.address_criminal_verification_doc, 'Download') + '</td></tr>';
        h += '<tr><th>Identity Verification</th><td>' + docLink(d.identity_verification_doc, 'Download') + '</td></tr>';
        h += '<tr><th>CIBIL Verification</th><td>' + docLink(d.cibil_verification_doc, 'Download') + '</td></tr>';
        h += '</table>';
        h += '</div></div>';
        return h;
    }
    function setEditForm(d) {
        $('#kyc_edit_id').val(d.id);
        $('#kyc_candidate_name').val(d.candidate_name || '');
        $('#kyc_father_name').val(d.father_name || '');
        $('#kyc_mother_name').val(d.mother_name || '');
        $('#kyc_date_of_birth').val(d.date_of_birth || '');
        $('#kyc_marital_status').val(d.marital_status || '');
        $('#kyc_email').val(d.email || '');
        $('#kyc_contact_number').val(d.contact_number || '');
        $('#kyc_alternate_contact_number').val(d.alternate_contact_number || '');
        $('#kyc_aadhaar_number').val(d.aadhaar_number || '');
        $('#kyc_pan_number').val(d.pan_number || '');
        $('#kyc_status').val(d.status || 'Draft');
        $('#kyc_present_complete_address').val(d.present_complete_address || '');
        $('#kyc_present_landmark').val(d.present_landmark || '');
        $('#kyc_present_city').val(d.present_city || '');
        $('#kyc_present_state').val(d.present_state || '');
        $('#kyc_present_pin_code').val(d.present_pin_code || '');
        $('#kyc_present_police_station').val(d.present_police_station || '');
        $('#kyc_present_stay_from').val(d.present_stay_from || '');
        $('#kyc_present_stay_to').val(d.present_stay_to || '');
        $('#kyc_permanent_complete_address').val(d.permanent_complete_address || '');
        $('#kyc_permanent_landmark').val(d.permanent_landmark || '');
        $('#kyc_permanent_city').val(d.permanent_city || '');
        $('#kyc_permanent_state').val(d.permanent_state || '');
        $('#kyc_permanent_pin_code').val(d.permanent_pin_code || '');
        $('#kyc_permanent_police_station').val(d.permanent_police_station || '');
        $('#kyc_permanent_stay_from').val(d.permanent_stay_from || '');
        $('#kyc_permanent_stay_to').val(d.permanent_stay_to || '');
        for (var i = 1; i <= 3; i++) {
            $('#kyc_edu' + i + '_institute_name').val(d['edu' + i + '_institute_name'] || '');
            $('#kyc_edu' + i + '_course_name').val(d['edu' + i + '_course_name'] || '');
            $('#kyc_edu' + i + '_passing_year').val(d['edu' + i + '_passing_year'] || '');
            $('#kyc_edu' + i + '_registration_number').val(d['edu' + i + '_registration_number'] || '');
            $('#kyc_edu' + i + '_mode').val(d['edu' + i + '_mode'] || '');
        }
        $('#kyc_org1_name').val(d.org1_name || '');
        $('#kyc_org1_address').val(d.org1_address || '');
        $('#kyc_org1_designation').val(d.org1_designation || '');
        $('#kyc_org1_employee_code').val(d.org1_employee_code || '');
        $('#kyc_org1_date_of_joining').val(d.org1_date_of_joining || '');
        $('#kyc_org1_last_working_day').val(d.org1_last_working_day || '');
        $('#kyc_org1_salary_ctc').val(d.org1_salary_ctc || '');
        $('#kyc_org1_reason_for_leaving').val(d.org1_reason_for_leaving || '');
        $('#kyc_org1_reporting_manager_name').val(d.org1_reporting_manager_name || '');
        $('#kyc_org1_reporting_manager_contact').val(d.org1_reporting_manager_contact || '');
        $('#kyc_org1_reporting_manager_email').val(d.org1_reporting_manager_email || '');
        $('#kyc_org1_hr1_name').val(d.org1_hr1_name || '');
        $('#kyc_org1_hr1_contact').val(d.org1_hr1_contact || '');
        $('#kyc_org1_hr1_email').val(d.org1_hr1_email || '');
        $('#kyc_org1_hr2_name').val(d.org1_hr2_name || '');
        $('#kyc_org1_hr2_contact').val(d.org1_hr2_contact || '');
        $('#kyc_org1_hr2_email').val(d.org1_hr2_email || '');
        $('#kyc_referee1_name').val(d.referee1_name || '');
        $('#kyc_referee1_organization').val(d.referee1_organization || '');
        $('#kyc_referee1_designation').val(d.referee1_designation || '');
        $('#kyc_referee1_contact').val(d.referee1_contact || '');
        $('#kyc_referee1_email').val(d.referee1_email || '');
        $('#kyc_referee2_name').val(d.referee2_name || '');
        $('#kyc_referee2_organization').val(d.referee2_organization || '');
        $('#kyc_referee2_designation').val(d.referee2_designation || '');
        $('#kyc_referee2_contact').val(d.referee2_contact || '');
        $('#kyc_referee2_email').val(d.referee2_email || '');
    }
    $('.view-kyc-details').on('click', function() {
        var id = $(this).data('id');
        $('#viewKycContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        $('#viewKycModal').modal('show');
        $.get(admin_url + 'powerform/get_kyc_form_details/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) $('#viewKycContent').html(buildViewHtml(r.data));
            else $('#viewKycContent').html('<div class="alert alert-danger">' + (r.message || 'Not found') + '</div>');
        }).fail(function() { $('#viewKycContent').html('<div class="alert alert-danger">Failed to load</div>'); });
    });
    $('.edit-kyc-details').on('click', function() {
        var id = $(this).data('id');
        $.get(admin_url + 'powerform/get_kyc_form_details/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) { setEditForm(r.data); $('#editKycModal').modal('show'); }
            else alert_float('danger', r.message || 'Not found');
        }).fail(function() { alert_float('danger', 'Failed to load'); });
    });
    $('#editKycForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#kycSaveBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.post(admin_url + 'powerform/update_kyc_form_details', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save Changes');
            if (r.success) { alert_float('success', r.message); $('#editKycModal').modal('hide'); setTimeout(function() { location.reload(); }, 1000); }
            else alert_float('danger', r.message);
        }).fail(function() { $btn.prop('disabled', false).html('Save Changes'); alert_float('danger', 'Failed to save'); });
    });
    $('.delete-kyc-details').on('click', function() {
        if (!confirm('Delete this KYC record?')) return;
        var id = $(this).data('id');
        $.post(admin_url + 'powerform/delete_kyc_form_details/' + id, { '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' }).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) { alert_float('success', r.message); setTimeout(function() { location.reload(); }, 1000); }
            else alert_float('danger', r.message);
        }).fail(function() { alert_float('danger', 'Failed to delete'); });
    });
});
</script>
</body>
</html>
