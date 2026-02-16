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
				<h5>Joining Form</h5>
				</div>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            
            <?php echo form_open(base_url('forms/joining_form_submit'), ['id' => 'joining-form']); ?>
              <input type="hidden" name="status" id="joining-status" value="<?php echo e($form['status'] ?? 'Draft'); ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  
              <div class="form-section">
                <h5>Personal Information</h5>
                <div class="row">
                 <div class="col-md-4"><label for="name" class="control-label">Name : <small class="req text-danger">* </small></label></div>
				<div class="col-md-8">
                <input type="text" name="name" class="form-control" value="<?php echo e($form['name'] ?? ''); ?>" required>
                </div>
			</div>
			
			
			<div class="row">
				
                <div class="col-md-4">
                  <div class="form-group">
					<label for="father_husband_name" class="control-label">Father/Husband Name : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="father_husband_name" class="form-control" value="<?php echo e($form['father_husband_name'] ?? ''); ?>">
                  </div>
                </div>
				</div>
				
				<div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Contact Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['contact_number'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Emergency Contact Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="emergency_contact_number" class="form-control" maxlength="10" pattern="\d{10}" inputmode="numeric" value="<?php echo e($form['emergency_contact_number'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Email : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="email" name="email" class="form-control" value="<?php echo e($form['email'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>PAN Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="pan_number" class="form-control" maxlength="10" pattern="[A-Za-z]{5}[0-9]{4}[A-Za-z]{1}" value="<?php echo e($form['pan_number'] ?? ''); ?>" >
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Aadhaar Number : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="aadhaar_number" class="form-control" maxlength="12" pattern="\d{12}" inputmode="numeric" value="<?php echo e($form['aadhaar_number'] ?? ''); ?>">
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Date of Birth : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="date" name="date_of_birth" class="form-control" value="<?php echo e($form['date_of_birth'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Assigned Designation : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="assigned_designation" class="form-control" value="<?php echo e($form['assigned_designation'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Department : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="text" name="department" class="form-control" value="<?php echo e($form['department'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
                <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Date of Joining : <small class="req text-danger">* </small></label>
                  </div>
                </div>
				<div class="col-md-8">
                  <div class="form-group">
                    <input type="date" name="date_of_joining" class="form-control" value="<?php echo e($form['date_of_joining'] ?? ''); ?>" required>
                  </div>
                </div>
                </div>
              </div>
              </div>
              </div>
              <hr>
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  </div>
			  <div class="form-section">
                <h5>Address Information</h5>
                <div class="row">
                <h6>Current Address</h6>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 1 : <small class="req text-danger">* </small></label>
                    <input type="text" name="current_address_line1" class="form-control" value="<?php echo e($form['current_address_line1'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 2 : </label>
                    <input type="text" name="current_address_line2" class="form-control" value="<?php echo e($form['current_address_line2'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 3 : </label>
                    <input type="text" name="current_address_line3" class="form-control" value="<?php echo e($form['current_address_line3'] ?? ''); ?>">
                  </div>
                </div>
                </div>

                <div class="row">
                <h6>Permanent Address</h6>
				<div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 1 : <small class="req text-danger">* </small></label>
                    <input type="text" name="permanent_address_line1" class="form-control" value="<?php echo e($form['permanent_address_line1'] ?? ''); ?>" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 2 : </label>
                    <input type="text" name="permanent_address_line2" class="form-control" value="<?php echo e($form['permanent_address_line2'] ?? ''); ?>">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Address Line 3 : </label>
                    <input type="text" name="permanent_address_line3" class="form-control" value="<?php echo e($form['permanent_address_line3'] ?? ''); ?>">
                  </div>
                </div>
                </div>
              </div>
              </div>
              <hr>
			  

              <div class="tw-flex tw-gap-2">
                <button type="submit" class="btn btn-primary" data-status="Submitted" id="save-submit">Submit</button>
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
    <script>
        $(document).ready(function() {
            // Form validation
            $('#joining-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2,
                        maxlength: 100,
                        lettersOnly: true
                    },
                    father_husband_name: {
                        required: true,
                        minlength: 2,
                        maxlength: 100,
                        lettersOnly: true
                    },
                    contact_number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        validPhone: true
                    },
                    emergency_contact_number: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        validPhone: true
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 100
                    },
                    pan_number: {
                        required: true,
                        panFormat: true,
                        maxlength: 10
                    },
                    aadhaar_number: {
                        required: true,
                        digits: true,
                        minlength: 12,
                        maxlength: 12,
                        validAadhaar: true
                    },
                    date_of_birth: {
                        required: true,
                        date: true,
                        ageValidation: true
                    },
                    assigned_designation: {
                        required: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    department: {
                        required: true,
                        minlength: 2,
                        maxlength: 100
                    },
                    date_of_joining: {
                        required: true,
                        date: true,
                        joiningDateValidation: true
                    },
                    current_address_line1: {
                        required: true,
                        minlength: 5,
                        maxlength: 200
                    },
                    permanent_address_line1: {
                        required: true,
                        minlength: 5,
                        maxlength: 200
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be at least 2 characters",
                        maxlength: "Name cannot exceed 100 characters",
                        lettersOnly: "Name can only contain letters and spaces"
                    },
                    father_husband_name: {
                        required: "Please enter father/husband name",
                        minlength: "Father/Husband name must be at least 2 characters",
                        maxlength: "Father/Husband name cannot exceed 100 characters",
                        lettersOnly: "Father/Husband name can only contain letters and spaces"
                    },
                    contact_number: {
                        required: "Please enter contact number",
                        digits: "Contact number can only contain digits",
                        minlength: "Contact number must be exactly 10 digits",
                        maxlength: "Contact number must be exactly 10 digits",
                        validPhone: "Please enter a valid 10-digit mobile number"
                    },
                    emergency_contact_number: {
                        required: "Please enter emergency contact number",
                        digits: "Emergency contact number can only contain digits",
                        minlength: "Emergency contact number must be exactly 10 digits",
                        maxlength: "Emergency contact number must be exactly 10 digits",
                        validPhone: "Please enter a valid 10-digit mobile number"
                    },
                    email: {
                        required: "Please enter email address",
                        email: "Please enter a valid email address",
                        maxlength: "Email cannot exceed 100 characters"
                    },
                    pan_number: {
                        required: "Please enter PAN number",
                        panFormat: "PAN number must be in format: ABCDE1234F"
                    },
                    aadhaar_number: {
                        required: "Please enter Aadhaar number",
                        digits: "Aadhaar number can only contain digits",
                        minlength: "Aadhaar number must be exactly 12 digits",
                        maxlength: "Aadhaar number must be exactly 12 digits",
                        validAadhaar: "Please enter a valid 12-digit Aadhaar number"
                    },
                    date_of_birth: {
                        required: "Please select date of birth",
                        date: "Please enter a valid date",
                        ageValidation: "You must be at least 18 years old"
                    },
                    assigned_designation: {
                        required: "Please enter assigned designation",
                        minlength: "Designation must be at least 2 characters",
                        maxlength: "Designation cannot exceed 100 characters"
                    },
                    department: {
                        required: "Please enter department",
                        minlength: "Department must be at least 2 characters",
                        maxlength: "Department cannot exceed 100 characters"
                    },
                    date_of_joining: {
                        required: "Please select date of joining",
                        date: "Please enter a valid date",
                        joiningDateValidation: "Date of joining cannot be in the future"
                    },
                    current_address_line1: {
                        required: "Please enter current address line 1",
                        minlength: "Address must be at least 5 characters",
                        maxlength: "Address cannot exceed 200 characters"
                    },
                    permanent_address_line1: {
                        required: "Please enter permanent address line 1",
                        minlength: "Address must be at least 5 characters",
                        maxlength: "Address cannot exceed 200 characters"
                    }
                },
                errorElement: 'div',
                errorClass: 'error-message',
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                highlight: function(element) {
                    $(element).addClass('error-field');
                    $(element).removeClass('success-field');
                },
                unhighlight: function(element) {
                    $(element).removeClass('error-field');
                    $(element).addClass('success-field');
                }
            });

            // Custom validation methods
            $.validator.addMethod("lettersOnly", function(value, element) {
                return this.optional(element) || /^[a-zA-Z\s]+$/.test(value);
            });

            $.validator.addMethod("validPhone", function(value, element) {
                return this.optional(element) || /^[6-9]\d{9}$/.test(value);
            });

            $.validator.addMethod("panFormat", function(value, element) {
                return this.optional(element) || /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/.test(value.toUpperCase());
            });

            $.validator.addMethod("validAadhaar", function(value, element) {
                return this.optional(element) || /^[2-9]\d{11}$/.test(value);
            });

            $.validator.addMethod("ageValidation", function(value, element) {
                if (this.optional(element)) return true;
                var dob = new Date(value);
                var today = new Date();
                var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
                return age >= 18;
            });

            $.validator.addMethod("joiningDateValidation", function(value, element) {
                if (this.optional(element)) return true;
                var joiningDate = new Date(value);
                var today = new Date();
                return joiningDate <= today;
            });

            // Form submission
            $('#save-submit').on('click', function(e) {
                e.preventDefault();
                
                if ($('#joining-form').valid()) {
                    var status = $(this).data('status');
                    $('#joining-status').val(status);
                    
                    // Show loading state
                    $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
                    
                    // Submit form
                    $('#joining-form').submit();
                } else {
                    // Scroll to first error
                    var firstError = $('.error-message').first();
                    if (firstError.length) {
                        $('html, body').animate({
                            scrollTop: firstError.offset().top - 100
                        }, 500);
                    }
                }
            });

            // Real-time validation feedback
            $('.form-control').on('blur', function() {
                $(this).valid();
            });

            // Add success animation on valid input
            $('.form-control').on('input', function() {
                if ($(this).valid()) {
                    $(this).addClass('success-field');
                }
            });
        });
    </script>

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
