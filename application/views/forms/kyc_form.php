<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'User Documentation'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
     

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
           
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float 20s ease-in-out infinite;
        }

      
		

        .content-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            padding: 3rem;
            max-width: 85%;
            margin: 0 auto;
            color: var(--gray-800);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
            text-align: left;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }

        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #667eea);
            background-size: 300% 100%;
            animation: gradient-shift 3s ease infinite;
        }

        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .form-group {
            margin-bottom: 5px;
            position: relative;
        }

        .form-control {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            font-size: 16px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15), 0 8px 25px rgba(102, 126, 234, 0.1);
            background: #ffffff;
            transform: translateY(-2px);
        }

        .form-label, .control-label {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: block;
            position: relative;
            padding-left: 5px;
        }

        .form-label::before, .control-label::before {
            content: '';
            position: absolute;
            left: -10px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .panel-body {
            padding: 20px 0;
        }

        .panel_s {
            border: none;
            box-shadow: none;
            background: transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border: none;
            border-radius: 30px;
            padding: 15px 40px;
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(-1px) scale(0.98);
        }

        .row {
            margin-bottom: 20px;
            padding: 0 10px;
        }

        h4, h5 {
            color: #2c3e50;
            font-weight: 800;
            position: relative;
            margin-bottom: 25px;
        }

        h4::after, h5::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        .req {
            color: #e74c3c;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .hero-content {
            animation: fadeInUp 0.8s ease-out;
			margin-bottom:50px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-section {
            margin: 30px 0;
            padding: 25px;
            background: rgba(248, 249, 250, 0.5);
            border-radius: 15px;
            border: 1px solid rgba(102, 126, 234, 0.1);
        }

        .form-section:hover {
            background: rgba(248, 249, 250, 0.8);
            border-color: rgba(102, 126, 234, 0.2);
            transition: all 0.3s ease;
        }

        .company-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }

        .company-header h4 {
            font-size: 28px;
            font-weight: 900;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
        }

        .company-header h5 {
            font-size: 18px;
            font-weight: 600;
            color: #6c757d;
            font-style: italic;
        }

       

        
    </style>
</head>
<body>


    <section class="hero">
        <div class="container">
            <div class="hero-content">
				<div class="content-card">
				<div class="company-header">
				<h4>ITIO INNOVEX PVT LTD</h4>
				<h5><?php echo $title ?? "";?></h5>
				</div>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            
            <?php echo form_open_multipart(base_url('forms/kyc_form_submit'), ['id' => 'kyc_form']); ?>
               <input type="hidden" name="status" id="kyc-status" value="<?php echo e($form['status'] ?? 'Draft'); ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
              <div class="form-section">
                <h5>Personal Information</h5>
                <div class="row">
                 <div class="col-md-4"><label for="candidate_name" class="control-label">Candidate Name : <small class="req text-danger">* </small></label></div>
				<div class="col-md-8">
                <input type="text" name="candidate_name" class="form-control" required>
                </div>
			</div>
			
			<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="father_name" class="control-label">Father Name : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="father_name" class="form-control" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="mother_name" class="control-label">Mother Name :  : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="mother_name" class="form-control" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="date_of_birth" class="control-label">Date of Birth : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="date" name="date_of_birth" class="form-control" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="marital_status" class="control-label">Marital Status : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="marital_status" class="form-control" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="email" class="control-label">Email : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="email" name="email" class="form-control" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="contact_number" class="control-label">Contact Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="alternate_contact_number" class="control-label">Alternate Contact Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="alternate_contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="aadhaar_number" class="control-label">Aadhaar Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="aadhaar_number" class="form-control" maxlength="12" pattern="\d{12}" inputmode="numeric" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label for="pan_number" class="control-label">PAN Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="pan_number" class="form-control" maxlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}" required>
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
                  
                  <h5>Present/Current Address Details</h5>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Complete Address : <small class="req text-danger">* </small></label>
                      <textarea name="present_complete_address" class="form-control" rows="2" required></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Landmark</label>
                      <input type="text" name="present_landmark" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>City : <small class="req text-danger">* </small></label>
                      <input type="text" name="present_city" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>State : <small class="req text-danger">* </small></label>
                      <input type="text" name="present_state" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Police Station : <small class="req text-danger">* </small></label>
                      <input type="text" name="present_police_station" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>PIN Code : <small class="req text-danger">* </small></label>
                      <input type="text" name="present_pin_code" class="form-control" maxlength="6" pattern="\d{6}" inputmode="numeric"  required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Stay From : <small class="req text-danger">* </small></label>
                      <input type="text" name="present_stay_from" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Stay To : <small class="req text-danger">* </small></label>
                      <input type="text" name="present_stay_to" class="form-control" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  
				  <h5>Permanent Address Details</h5>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label>Complete Address : <small class="req text-danger">* </small></label>
                      <textarea name="permanent_complete_address" class="form-control" rows="2" required></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Landmark</label>
                      <input type="text" name="permanent_landmark" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>City : <small class="req text-danger">* </small></label>
                      <input type="text" name="permanent_city" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>State : <small class="req text-danger">* </small></label>
                      <input type="text" name="permanent_state" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>PIN Code : <small class="req text-danger">* </small></label>
                      <input type="text" name="permanent_pin_code" class="form-control" maxlength="6" pattern="\d{6}" inputmode="numeric" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Police Station : <small class="req text-danger">* </small></label>
                      <input type="text" name="permanent_police_station" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Stay From : <small class="req text-danger">* </small></label>
                      <input type="text" name="permanent_stay_from" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label>Stay To : <small class="req text-danger">* </small></label>
                      <input type="text" name="permanent_stay_to" class="form-control" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <h5>Education Qualification Details</h5>
				  <table class="table">
  <thead>
    <tr>
      <th scope="col">Qualification Details</th>
      <th scope="col">Education 01</th>
      <th scope="col">Education 02</th>
      <th scope="col">Education 03</th>
    </tr>
  </thead>
  <tbody>
               
    <tr>
      <th scope="row"><label class="fw-normal">School/Institute/College/<Br>University Name</label> </th>
      <td><input type="text" name="edu1_institute_name" class="form-control" placeholder="Name of School / College *" value="" required></td>
      <td><input type="text" name="edu2_institute_name" class="form-control" placeholder="Name of College / University" value=""></td>
      <td><input type="text" name="edu3_institute_name" class="form-control" placeholder="Name of College / University" value=""></td>
    </tr>
	    <tr>
      <th scope="row"><label class="fw-normal">Course Name</label></th>
      <td><input type="text" name="edu1_course_name" class="form-control" placeholder="Course Name *" value="" required></td>
      <td><input type="text" name="edu2_course_name" class="form-control" placeholder="Course Name" value=""></td>
      <td><input type="text" name="edu3_course_name" class="form-control" placeholder="Course Name" value=""></td>
    </tr>
	    <tr>
      <th scope="row"><label class="fw-normal">Passing Year</label></th>
      <td><input type="text" name="edu1_passing_year" class="form-control" placeholder="Passing Year *" value="" required></td>
      <td><input type="text" name="edu2_passing_year" class="form-control" placeholder="Passing Year" value="" ></td>
      <td><input type="text" name="edu3_passing_year" class="form-control" placeholder="Passing Year" value="" ></td>
    </tr>
	
	    <tr>
      <th scope="row"><label class="fw-normal">Registration/Enrollment/Roll<Br>/Seat/Certificate Number</label></th>
      <td><input type="text" name="edu1_registration_number" class="form-control" placeholder="Reg. / Enroll. No *" value="" required></td>
      <td><input type="text" name="edu2_registration_number" class="form-control" placeholder="Reg. / Enroll. No" value="" ></td>
      <td><input type="text" name="edu3_registration_number" class="form-control" placeholder="Reg. / Enroll. No" value="" ></td>
    </tr>
	
	    <tr>
      <th scope="row"><label class="fw-normal">Regular/Correspondence/Open</label></th>
      <td><select name="edu1_mode" class="form-control" required>
                            <option value="">Mode</option>
                            <option value="Regular">Regular</option>
                            <option value="Correspondence">Correspondence</option>
                            <option value="Open">Open</option>
                          </select></td>
      <td><select name="edu2_mode" class="form-control">
                            <option value="">Mode</option>
                            <option value="Regular">Regular</option>
                            <option value="Correspondence">Correspondence</option>
                            <option value="Open">Open</option>
                          </select></td>
      <td><select name="edu3_mode" class="form-control">
                            <option value="">Mode</option>
                            <option value="Regular">Regular</option>
                            <option value="Correspondence">Correspondence</option>
                            <option value="Open">Open</option>
                          </select></td>
    </tr>
  
  </tbody>
</table>
                  
                  
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <h5>Organization Details</h5>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Organization Name  : </label>
                      <input type="text" name="org1_name" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Designation  : </label>
                      <input type="text" name="org1_designation" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-12 mtop10">
                    <div class="form-group">
                      <label>Address  : </label>
                      <textarea name="org1_address" class="form-control" rows="2" ></textarea>
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Employee Code  : </label>
                      <input type="text" name="org1_employee_code" class="form-control"  >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Date of Joining  : </label>
                      <input type="date" name="org1_date_of_joining" class="form-control"  >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Last Working Day  : </label>
                      <input type="date" name="org1_last_working_day" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Salary CTC  : </label>
                      <input type="text" name="org1_salary_ctc" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Reason for Leaving : </label>
                      <input type="text" name="org1_reason_for_leaving" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Reporting Manager Name : </label>
                      <input type="text" name="org1_reporting_manager_name" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Reporting Manager Contact  : </label>
                      <input type="text" name="org1_reporting_manager_contact" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>Reporting Manager Email  : </label>
                      <input type="email" name="org1_reporting_manager_email" class="form-control"  >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>HR1 Name :  </label>
                      <input type="text" name="org1_hr1_name" class="form-control"  >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>HR1 Contact  : </label>
                      <input type="text" name="org1_hr1_contact" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>HR1 Email : </label>
                      <input type="email" name="org1_hr1_email" class="form-control" >
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>HR2 Name</label>
                      <input type="text" name="org1_hr2_name" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>HR2 Contact</label>
                      <input type="text" name="org1_hr2_contact" class="form-control">
                    </div>
                  </div>
                  <div class="col-md-6 mtop10">
                    <div class="form-group">
                      <label>HR2 Email</label>
                      <input type="email" name="org1_hr2_email" class="form-control">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <h5>Professional Reference Details</h5>
				  <table class="table">
  <thead>
    <tr>
      <th scope="col">Referee Details</th>
      <th scope="col">Referee 01 <small class="req text-danger">* </small></th>
      <th scope="col">Referee 02 <small class="req text-danger">* </small></th>
    </tr>
  </thead>
  <tbody>
 
    <tr>
      <th scope="row"><label class="fw-normal">Referee Name</label></th>
      <td><input type="text" name="referee1_name" class="form-control" placeholder="Referee 1 Name" required></td>
      <td> <input type="text" name="referee2_name" class="form-control" placeholder="Referee 2 Name" required></td>
    </tr>
	
	 <tr>
      <th scope="row"><label class="fw-normal">Organization Name</label></th>
      <td><input type="text" name="referee1_organization" class="form-control" placeholder="Organization" required></td>
      <td><input type="text" name="referee2_organization" class="form-control" placeholder="Organization" required></td>
    </tr>
	
	 <tr>
      <th scope="row"><label class="fw-normal">Designation</label></th>
      <td><input type="text" name="referee1_designation" class="form-control" placeholder="Designation" required></td>
      <td><input type="text" name="referee2_designation" class="form-control" placeholder="Designation" required></td>
    </tr>
	
	 <tr>
      <th scope="row"><label class="fw-normal">Contact Number</label></th>
      <td><input type="text" name="referee1_contact" class="form-control" placeholder="Contact" required></td>
      <td><input type="text" name="referee2_contact" class="form-control" placeholder="Contact" required></td>
    </tr>
	
	 <tr>
      <th scope="row"><label class="fw-normal">Email Id</label></th>
      <td><input type="email" name="referee1_email" class="form-control" placeholder="Email"  required></td>
      <td><input type="email" name="referee2_email" class="form-control" placeholder="Email" required></td>
    </tr>
  
  </tbody>
</table>


                  
				  
                </div>
              </div>
            </div>
            <hr>
            <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
                <div class="row">
                  <h5>Documents To Provide</h5>
				  
			<div class="col-md-12">
				<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Education Verification Doc : </label>
                  </div>
				  </div>
				   <div class="col-md-8">
				   <div class="form-group">
                    <input type="file" name="education_verification_doc" class="form-control">
                  </div>
				   </div>
                </div>
				</div>
				
			<div class="col-md-12">
				<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Employment Verification Doc : </label>
                  </div>
				  </div>
				   <div class="col-md-8">
				   <div class="form-group">
                    <input type="file" name="employment_verification_doc" class="form-control">
                  </div>
				   </div>
                </div>
				</div>
				
			<div class="col-md-12">
				<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address/Criminal Verification Doc : </label>
                  </div>
				  </div>
				   <div class="col-md-8">
				   <div class="form-group">
                    <input type="file" name="address_criminal_verification_doc" class="form-control">
                  </div>
				   </div>
                </div>
				</div>
				
			<div class="col-md-12">
				<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Identity Verification Doc : </label>
                  </div>
				  </div>
				   <div class="col-md-8">
				   <div class="form-group">
                    <input type="file" name="identity_verification_doc" class="form-control">
                  </div>
				   </div>
                </div>
				</div>
				
			<div class="col-md-12">
				<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>CIBIL Verification Doc : </label>
                  </div>
				  </div>
				   <div class="col-md-8">
				   <div class="form-group">
                    <input type="file" name="cibil_verification_doc" class="form-control">
                  </div>
				   </div>
                </div>
				</div>
				
			
				
                  
                  
                  
                  
                  
                </div>
              </div>
            </div>
            <div class="tw-flex tw-gap-2 mtop10">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <?php echo form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

				</div>
               
            </div>
        </div>
    </section>

    

    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    

    <style>
        .error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            font-weight: 600;
            background: rgba(231, 76, 60, 0.1);
            padding: 8px 12px;
            border-radius: 6px;
            border-left: 3px solid #e74c3c;
            animation: shake 0.5s ease-in-out;
        }

        .error-field {
            border-color: #e74c3c !important;
            background-color: rgba(231, 76, 60, 0.05) !important;
            box-shadow: 0 0 0 2px rgba(231, 76, 60, 0.2) !important;
        }

        .success-field {
            border-color: #28a745 !important;
            background-color: rgba(40, 167, 69, 0.05) !important;
            box-shadow: 0 0 0 2px rgba(40, 167, 69, 0.2) !important;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .form-control.error-field {
            animation: shake 0.5s ease-in-out;
        }

        .error-message::before {
            content: '⚠';
            margin-right: 5px;
            font-weight: bold;
        }
    </style>
</body>
</html>
