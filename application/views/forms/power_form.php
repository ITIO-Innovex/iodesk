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

       
   	 /* responsive */
    @media (max-width:562px){
      .content-card {
      max-width: 100% !important;
      } 
	  .content-card {
      padding: 5px !important;  
	  }
	  .form-section {
	  padding: 5px !important;  
	  }
	   .col-md-4, .col-md-8, .col-md-6, .col-md-12 {
	  padding: 2px !important;  
	  }
	  .h5, h5, .h4, h4 {
    font-size: 1.0rem !important;  
    }
	th {
	font-size: 12px !important;  
    }
}
        
    </style>
</head>
<body>


    <section class="hero">
        <div class="container">
            <div class="hero-content">
				<div class="content-card">
				<div class="company-header">
				<h4><?php echo $companyname;?></h4>
				<h5><?php echo $title;?></h5>
				</div>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body mail-bg">
            
            <?php echo form_open(base_url('forms/power_form_submit'), ['id' => 'joining-form']); ?>
              <input type="hidden" name="status" id="joining-status" value="<?php echo e($form['status'] ?? 'Draft'); ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  
              <div class="form-section">
                <div class="row">
                 <div class="col-md-4"><label for="name" class="control-label">Name : <small class="req text-danger">* </small></label></div>
				<div class="col-md-8">
                <input type="text" name="name" class="form-control" value="<?php echo e($form['name'] ?? ''); ?>" required>
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
           

            // Form submission
            $('#save-submitxx').on('click', function(e) {
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
