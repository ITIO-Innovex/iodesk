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
	<link rel="stylesheet" href="<?php echo base_url('assets/css/webform.css');?>">
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
            
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  
              <div class="form-section row">
               <div class="row">
                 <div class="col-md-12"><label for="name" class="control-label">Thank you for submitting the <strong><?php echo $title;?> Form</strong> to <strong><?php echo $companyname;?></strong>.</label><br>
<p>Your reference number is <strong><?php echo $token;?></strong>. We will get back to you shortly.</p></div>
				
			</div>
			
			
              </div>
              </div>
              </div>
              
            
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
	<link rel="stylesheet" href="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.css"/>
<script src="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.js"></script>

<script>
//window.email_editor = Jodit.make('#wfFinalBody');
window.editor = Jodit.make('.editor');
</script>
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

</body>
</html>
