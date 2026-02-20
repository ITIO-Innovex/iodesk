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
                            <table class="table table-striped table-bordered dt-table" id="jobApplicationTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Full Name</th>
                                        <th>Applied Post</th>
                                        <th>Email</th>
                                        <th>Mobile No</th>
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
                                                <td><?php echo htmlspecialchars($record['full_name'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['applied_post'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['email'] ?? ''); ?></td>
                                                <td><?php echo htmlspecialchars($record['mobile_no'] ?? ''); ?></td>
                                                <td>
                                                    <?php
                                                    $s = $record['status'] ?? 'Draft';
                                                    $statusClass = 'label-warning';
                                                    if ($s === 'Interviewed') $statusClass = 'label-info';
                                                    if ($s === 'Selected') $statusClass = 'label-success';
                                                    if ($s === 'Rejected') $statusClass = 'label-danger';
                                                    ?>
                                                    <span class="label <?php echo $statusClass; ?>"><?php echo htmlspecialchars($s); ?></span>
                                                </td>
                                                <td><?php echo !empty($record['created_at']) ? date('d-m-Y H:i', strtotime($record['created_at'])) : '-'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-info btn-xs view-ja-details" data-id="<?php echo $record['id']; ?>" title="View"><i class="fa fa-eye"></i></button>
                                                    <button type="button" class="btn btn-primary btn-xs edit-ja-details" data-id="<?php echo $record['id']; ?>" title="Edit"><i class="fa fa-pencil"></i></button>
                                                    <button type="button" class="btn btn-danger btn-xs delete-ja-details" data-id="<?php echo $record['id']; ?>" title="Delete"><i class="fa fa-trash"></i></button>
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
<div class="modal fade" id="viewJaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Job Application Details</h4>
            </div>
            <div class="modal-body" id="viewJaContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editJaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editJaForm">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="id" id="ja_edit_id">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    <h4 class="modal-title">Edit Job Application</h4>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#jaBasic" data-toggle="tab">Basic</a></li>
                        <li><a href="#jaEducation" data-toggle="tab">Education</a></li>
                        <li><a href="#jaFamily" data-toggle="tab">Family</a></li>
                        <li><a href="#jaJob" data-toggle="tab">Job History</a></li>
                        <li><a href="#jaInterview" data-toggle="tab">Interview / Offer</a></li>
                    </ul>
                    <div class="tab-content mtop15">
                        <div class="tab-pane active" id="jaBasic">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="full_name" id="ja_full_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Applied Post</label>
                                        <input type="text" class="form-control" name="applied_post" id="ja_applied_post">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" id="ja_email">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Mobile No</label>
                                        <input type="text" class="form-control" name="mobile_no" id="ja_mobile_no">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Alternative No</label>
                                        <input type="text" class="form-control" name="alternative_no" id="ja_alternative_no">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sex</label>
                                        <input type="text" class="form-control" name="sex" id="ja_sex">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Marital Status</label>
                                        <input type="text" class="form-control" name="marital_status" id="ja_marital_status">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status" id="ja_status">
                                            <option value="Draft">Draft</option>
                                            <option value="Interviewed">Interviewed</option>
                                            <option value="Selected">Selected</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Has LinkedIn</label>
                                        <select class="form-control" name="has_linkedin" id="ja_has_linkedin">
                                            <option value="">--</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Address with Pincode</label>
                                <textarea class="form-control" name="address_with_pincode" id="ja_address_with_pincode" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Major Skill 1</label>
                                        <input type="text" class="form-control" name="major_skill_1" id="ja_major_skill_1">
                                    </div>
                                </div>
                                        <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Major Skill 2</label>
                                        <input type="text" class="form-control" name="major_skill_2" id="ja_major_skill_2">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>LinkedIn Connections</label>
                                <input type="text" class="form-control" name="linkedin_connections" id="ja_linkedin_connections">
                            </div>
                        </div>
                        <div class="tab-pane" id="jaEducation">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Education <?php echo $i; ?></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Degree</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_degree" id="ja_edu<?php echo $i; ?>_degree">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>University</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_university" id="ja_edu<?php echo $i; ?>_university">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Major Subject</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_major_subject" id="ja_edu<?php echo $i; ?>_major_subject">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <input type="text" class="form-control" name="edu<?php echo $i; ?>_year" id="ja_edu<?php echo $i; ?>_year">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="jaFamily">
                            <?php for ($i = 1; $i <= 6; $i++) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Family <?php echo $i; ?></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input type="text" class="form-control" name="fam<?php echo $i; ?>_name" id="ja_fam<?php echo $i; ?>_name">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Age</label>
                                                <input type="number" class="form-control" name="fam<?php echo $i; ?>_age" id="ja_fam<?php echo $i; ?>_age">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Relationship</label>
                                                <input type="text" class="form-control" name="fam<?php echo $i; ?>_relationship" id="ja_fam<?php echo $i; ?>_relationship">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Occupation</label>
                                                <input type="text" class="form-control" name="fam<?php echo $i; ?>_occupation" id="ja_fam<?php echo $i; ?>_occupation">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="jaJob">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">Job <?php echo $i; ?></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" class="form-control" name="job<?php echo $i; ?>_title" id="ja_job<?php echo $i; ?>_title">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Company</label>
                                                <input type="text" class="form-control" name="job<?php echo $i; ?>_company" id="ja_job<?php echo $i; ?>_company">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Designation</label>
                                                <input type="text" class="form-control" name="job<?php echo $i; ?>_designation" id="ja_job<?php echo $i; ?>_designation">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Reason for Leaving</label>
                                                <input type="text" class="form-control" name="job<?php echo $i; ?>_reason_for_leaving" id="ja_job<?php echo $i; ?>_reason_for_leaving">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Start Date</label>
                                                <input type="date" class="form-control" name="job<?php echo $i; ?>_start_date" id="ja_job<?php echo $i; ?>_start_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>End Date</label>
                                                <input type="date" class="form-control" name="job<?php echo $i; ?>_end_date" id="ja_job<?php echo $i; ?>_end_date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Start Salary</label>
                                                <input type="text" class="form-control" name="job<?php echo $i; ?>_start_salary" id="ja_job<?php echo $i; ?>_start_salary">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>End Salary</label>
                                                <input type="text" class="form-control" name="job<?php echo $i; ?>_end_salary" id="ja_job<?php echo $i; ?>_end_salary">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="jaInterview">
                            <div class="form-group">
                                <label>Coordinating Person Name</label>
                                <input type="text" class="form-control" name="coordinating_person_name" id="ja_coordinating_person_name">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Interviewed By 1</label>
                                        <input type="text" class="form-control" name="interviewed_by_1" id="ja_interviewed_by_1">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Interviewed By 2</label>
                                        <input type="text" class="form-control" name="interviewed_by_2" id="ja_interviewed_by_2">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Interview Remarks 1</label>
                                <textarea class="form-control" name="interview_remarks_1" id="ja_interview_remarks_1" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Interview Remarks 2</label>
                                <textarea class="form-control" name="interview_remarks_2" id="ja_interview_remarks_2" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>DOJ Date/Time</label>
                                        <input type="datetime-local" class="form-control" name="doj_datetime" id="ja_doj_datetime">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Offered Designation</label>
                                        <input type="text" class="form-control" name="offered_designation" id="ja_offered_designation">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Offered Salary</label>
                                <input type="text" class="form-control" name="offered_salary" id="ja_offered_salary">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="jaSaveBtn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php init_tail(); ?>
<script>
$(function() {
    var baseUrl = '<?php echo base_url(); ?>';
    function esc(v) { return v == null || v === '' ? '-' : String(v); }
    function statusClass(s) {
        if (s === 'Interviewed') return 'label-info';
        if (s === 'Selected') return 'label-success';
        if (s === 'Rejected') return 'label-danger';
        return 'label-warning';
    }
    function buildViewHtml(d) {
        var h = '<div class="row"><div class="col-md-12">';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3">Basic Information</h5>';
        h += '<table class="table table-bordered table-condensed"><tr><th width="20%">Full Name</th><td>' + esc(d.full_name) + '</td><th width="20%">Applied Post</th><td>' + esc(d.applied_post) + '</td></tr>';
        h += '<tr><th>Email</th><td>' + esc(d.email) + '</td><th>Mobile No</th><td>' + esc(d.mobile_no) + '</td></tr>';
        h += '<tr><th>Alternative No</th><td>' + esc(d.alternative_no) + '</td><th>Sex</th><td>' + esc(d.sex) + '</td></tr>';
        h += '<tr><th>Marital Status</th><td>' + esc(d.marital_status) + '</td><th>Status</th><td><span class="label ' + statusClass(d.status) + '">' + esc(d.status) + '</span></td></tr>';
        h += '<tr><th>Address with Pincode</th><td colspan="3">' + esc(d.address_with_pincode) + '</td></tr>';
        h += '<tr><th>Major Skill 1</th><td>' + esc(d.major_skill_1) + '</td><th>Major Skill 2</th><td>' + esc(d.major_skill_2) + '</td></tr>';
        h += '<tr><th>Has LinkedIn</th><td>' + esc(d.has_linkedin) + '</td><th>LinkedIn Connections</th><td>' + esc(d.linkedin_connections) + '</td></tr></table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Education</h5>';
        h += '<table class="table table-bordered table-condensed"><thead><tr><th>#</th><th>Degree</th><th>University</th><th>Major Subject</th><th>Year</th></tr></thead><tbody>';
        for (var i = 1; i <= 5; i++) {
            var deg = d['edu' + i + '_degree'], uni = d['edu' + i + '_university'], sub = d['edu' + i + '_major_subject'], yr = d['edu' + i + '_year'];
            if (deg || uni || sub || yr) h += '<tr><td>' + i + '</td><td>' + esc(deg) + '</td><td>' + esc(uni) + '</td><td>' + esc(sub) + '</td><td>' + esc(yr) + '</td></tr>';
        }
        h += '</tbody></table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Family</h5>';
        h += '<table class="table table-bordered table-condensed"><thead><tr><th>#</th><th>Name</th><th>Age</th><th>Relationship</th><th>Occupation</th></tr></thead><tbody>';
        for (var j = 1; j <= 6; j++) {
            var fn = d['fam' + j + '_name'], fa = d['fam' + j + '_age'], fr = d['fam' + j + '_relationship'], fo = d['fam' + j + '_occupation'];
            if (fn || fa || fr || fo) h += '<tr><td>' + j + '</td><td>' + esc(fn) + '</td><td>' + esc(fa) + '</td><td>' + esc(fr) + '</td><td>' + esc(fo) + '</td></tr>';
        }
        h += '</tbody></table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Job History</h5>';
        h += '<table class="table table-bordered table-condensed"><thead><tr><th>#</th><th>Title</th><th>Company</th><th>Designation</th><th>Start</th><th>End</th><th>Reason Leaving</th></tr></thead><tbody>';
        for (var k = 1; k <= 2; k++) {
            var jt = d['job' + k + '_title'], jc = d['job' + k + '_company'], jd = d['job' + k + '_designation'];
            var js = d['job' + k + '_start_date'], je = d['job' + k + '_end_date'], jr = d['job' + k + '_reason_for_leaving'];
            if (jt || jc || jd || js || je || jr) h += '<tr><td>' + k + '</td><td>' + esc(jt) + '</td><td>' + esc(jc) + '</td><td>' + esc(jd) + '</td><td>' + esc(js) + '</td><td>' + esc(je) + '</td><td>' + esc(jr) + '</td></tr>';
        }
        h += '</tbody></table>';
        h += '<h5 class="tw-font-semibold tw-border-b tw-pb-2 tw-mb-3 tw-mt-4">Interview / Offer</h5>';
        h += '<table class="table table-bordered table-condensed"><tr><th width="20%">Coordinating Person</th><td>' + esc(d.coordinating_person_name) + '</td></tr>';
        h += '<tr><th>Interviewed By 1</th><td>' + esc(d.interviewed_by_1) + '</td><th>Interviewed By 2</th><td>' + esc(d.interviewed_by_2) + '</td></tr>';
        h += '<tr><th>Interview Remarks 1</th><td colspan="3">' + esc(d.interview_remarks_1) + '</td></tr>';
        h += '<tr><th>Interview Remarks 2</th><td colspan="3">' + esc(d.interview_remarks_2) + '</td></tr>';
        h += '<tr><th>DOJ</th><td>' + esc(d.doj_datetime) + '</td><th>Offered Designation</th><td>' + esc(d.offered_designation) + '</td></tr>';
        h += '<tr><th>Offered Salary</th><td colspan="3">' + esc(d.offered_salary) + '</td></tr></table>';
        if (d.candidate_signature) h += '<p><a href="' + baseUrl + d.candidate_signature + '" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-download"></i> Signature</a></p>';
        h += '</div></div>';
        return h;
    }
    function setEditForm(d) {
        $('#ja_edit_id').val(d.id);
        $('#ja_full_name').val(d.full_name || '');
        $('#ja_sex').val(d.sex || '');
        $('#ja_applied_post').val(d.applied_post || '');
        $('#ja_mobile_no').val(d.mobile_no || '');
        $('#ja_alternative_no').val(d.alternative_no || '');
        $('#ja_address_with_pincode').val(d.address_with_pincode || '');
        $('#ja_email').val(d.email || '');
        $('#ja_marital_status').val(d.marital_status || '');
        $('#ja_status').val(d.status || 'Draft');
        $('#ja_major_skill_1').val(d.major_skill_1 || '');
        $('#ja_major_skill_2').val(d.major_skill_2 || '');
        $('#ja_has_linkedin').val(d.has_linkedin || '');
        $('#ja_linkedin_connections').val(d.linkedin_connections || '');
        for (var i = 1; i <= 5; i++) {
            $('#ja_edu' + i + '_degree').val(d['edu' + i + '_degree'] || '');
            $('#ja_edu' + i + '_university').val(d['edu' + i + '_university'] || '');
            $('#ja_edu' + i + '_major_subject').val(d['edu' + i + '_major_subject'] || '');
            $('#ja_edu' + i + '_year').val(d['edu' + i + '_year'] || '');
        }
        for (var j = 1; j <= 6; j++) {
            $('#ja_fam' + j + '_name').val(d['fam' + j + '_name'] || '');
            $('#ja_fam' + j + '_age').val(d['fam' + j + '_age'] || '');
            $('#ja_fam' + j + '_relationship').val(d['fam' + j + '_relationship'] || '');
            $('#ja_fam' + j + '_occupation').val(d['fam' + j + '_occupation'] || '');
        }
        for (var k = 1; k <= 2; k++) {
            $('#ja_job' + k + '_title').val(d['job' + k + '_title'] || '');
            $('#ja_job' + k + '_start_date').val(d['job' + k + '_start_date'] || '');
            $('#ja_job' + k + '_end_date').val(d['job' + k + '_end_date'] || '');
            $('#ja_job' + k + '_company').val(d['job' + k + '_company'] || '');
            $('#ja_job' + k + '_designation').val(d['job' + k + '_designation'] || '');
            $('#ja_job' + k + '_reason_for_leaving').val(d['job' + k + '_reason_for_leaving'] || '');
            $('#ja_job' + k + '_start_salary').val(d['job' + k + '_start_salary'] || '');
            $('#ja_job' + k + '_end_salary').val(d['job' + k + '_end_salary'] || '');
        }
        $('#ja_coordinating_person_name').val(d.coordinating_person_name || '');
        $('#ja_interviewed_by_1').val(d.interviewed_by_1 || '');
        $('#ja_interviewed_by_2').val(d.interviewed_by_2 || '');
        var doj = d.doj_datetime;
        if (doj) doj = doj.replace(/\.\d+$/, '').replace(' ', 'T').slice(0, 16);
        $('#ja_doj_datetime').val(doj || '');
        $('#ja_offered_salary').val(d.offered_salary || '');
        $('#ja_offered_designation').val(d.offered_designation || '');
        $('#ja_interview_remarks_1').val(d.interview_remarks_1 || '');
        $('#ja_interview_remarks_2').val(d.interview_remarks_2 || '');
    }
    $('.view-ja-details').on('click', function() {
        var id = $(this).data('id');
        $('#viewJaContent').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        $('#viewJaModal').modal('show');
        $.get(admin_url + 'powerform/get_job_application_details/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) $('#viewJaContent').html(buildViewHtml(r.data));
            else $('#viewJaContent').html('<div class="alert alert-danger">' + (r.message || 'Not found') + '</div>');
        }).fail(function() { $('#viewJaContent').html('<div class="alert alert-danger">Failed to load</div>'); });
    });
    $('.edit-ja-details').on('click', function() {
        var id = $(this).data('id');
        $.get(admin_url + 'powerform/get_job_application_details/' + id).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) { setEditForm(r.data); $('#editJaModal').modal('show'); }
            else alert_float('danger', r.message || 'Not found');
        }).fail(function() { alert_float('danger', 'Failed to load'); });
    });
    $('#editJaForm').on('submit', function(e) {
        e.preventDefault();
        var $btn = $('#jaSaveBtn');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');
        $.post(admin_url + 'powerform/update_job_application_details', $(this).serialize()).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            $btn.prop('disabled', false).html('Save Changes');
            if (r.success) { alert_float('success', r.message); $('#editJaModal').modal('hide'); setTimeout(function() { location.reload(); }, 1000); }
            else alert_float('danger', r.message);
        }).fail(function() { $btn.prop('disabled', false).html('Save Changes'); alert_float('danger', 'Failed to save'); });
    });
    $('.delete-ja-details').on('click', function() {
        if (!confirm('Delete this application?')) return;
        var id = $(this).data('id');
        $.post(admin_url + 'powerform/delete_job_application_details/' + id, { '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>' }).done(function(res) {
            var r = typeof res === 'string' ? JSON.parse(res) : res;
            if (r.success) { alert_float('success', r.message); setTimeout(function() { location.reload(); }, 1000); }
            else alert_float('danger', r.message);
        }).fail(function() { alert_float('danger', 'Failed to delete'); });
    });
});
</script>
</body>
</html>
