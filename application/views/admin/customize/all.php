<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                            <?php echo _l('customize_settings'); ?>
                        </h4>
                    </div>
                </div>
                <div class="panel_s">
                    <div class="panel-body">
                        <?php echo form_open_multipart(admin_url('customize'), ['id' => 'customize-form']); ?>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <?php $this->load->view('admin/customize/includes/appearance'); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right">
                                    <i class="fa fa-check"></i> <?php echo _l('submit'); ?>
                                </button>
                            </div>
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
    // Initialize form validation
    $('#customize-form').on('submit', function(e) {
        // Add any custom validation here if needed
        return true;
    });
    
    // Live preview functionality for company name
    $('input[name="settings[customize_company_name]"]').on('input', function() {
        var companyName = $(this).val();
        if (companyName) {
            $('#preview-company-name').text(companyName);
        } else {
            $('#preview-company-name').text('<?php echo _l('customize_sample_company_name'); ?>');
        }
    });
    
    // Live preview functionality for company domain
    $('input[name="settings[customize_company_domain]"]').on('input', function() {
        var companyDomain = $(this).val();
        if (companyDomain) {
            $('#preview-company-domain').text(companyDomain);
        } else {
            $('#preview-company-domain').text('<?php echo _l('customize_sample_domain'); ?>');
        }
    });
    
    // File upload preview for logo
    $('input[name="customize_company_logo"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-logo').html('<img src="' + e.target.result + '" alt="Logo Preview" style="max-height: 40px; max-width: 150px;" />');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // File upload preview for favicon
    $('input[name="customize_favicon"]').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-favicon').html('<img src="' + e.target.result + '" alt="Favicon Preview" style="max-height: 32px; max-width: 32px;" />');
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
</body>
</html> 