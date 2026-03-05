<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-flex tw-justify-between tw-items-center tw-mb-3">
          <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-white"><i class="fa fa-tty"></i> <?php echo html_escape($title ?? _l('add_new', _l('lead_lowercase'))); ?> </h4>
          <a href="<?php echo admin_url('leads'); ?>" class="btn btn-default"> <?php echo 'Back to Leads'; ?> </a> </div>
        <div class="panel_s">
          <div class="panel-body">
            <div class="row"> <?php echo form_open(admin_url('leads/add_new_leads'), ['id' => 'add_new_leads_form']); ?>
              <div class="clearfix"></div>
              <div class="lead-edit<?php if (isset($lead)) {} ?>">
                <div class="clearfix"></div>
                <hr class="no-mtop mbot15" />
                <div class="col-md-6">
                  <div class="form-group" app-field-wrapper="email">
                    <label for="name" class="control-label"> <small class="req text-danger">* </small>Name</label>
                    <input type="text" id="name" name="name" class="form-control" required="true" >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group" app-field-wrapper="email">
                  <label for="phonenumber" class="control-label"> <small class="req text-danger">* </small>Email Address</label>
				  <span class="text-danger" id="emailError"></span>
                    <input type="email" id="email" name="email" class="form-control" required="true" >
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="col-md-4 tw-px-0">
               
                <?php echo render_input('country_code', 'ISD Code', '','number',['maxlength' => '3','minlength' => '1','onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57']); ?>
                  </div>
                  <div class="col-md-8 tw-px-0"> <?php echo render_input('phonenumber', 'lead_add_edit_phonenumber', '','number',['maxlength' => '15','minlength' => '10','onkeypress' => 'return event.charCode >= 48 && event.charCode <= 57']); ?> </div>
                  <?php
               $countries                = get_all_countries();
               $customer_default_country = get_option('customer_default_country');
               $selected                 = (isset($lead) ? $lead->country : $customer_default_country);
               echo render_select('country', $countries, [ 'country_id', [ 'short_name']], 'Resident`s Country', $selected, ['data-none-selected-text' => _l('dropdown_non_selected_tex')]);
               ?>
                </div>
                <div class="col-md-6"> <?php echo render_input('company', 'Business Name', ''); //lead_company to Business Name ?> </div>
                <div class="col-md-6">
<?php echo render_input('website', 'Business URL (add with https:// or http://)', '');//lead_website to Business URL ?>
                </div>
                <div class="col-md-12"> <?php echo render_textarea('address', 'lead_address', '', ['rows' => 1, 'style' => 'height:36px;font-size:100%;']); ?> </div>
                <div class="col-md-12"> <?php echo render_textarea('description', 'lead_description', ''); ?> </div>
                <div class="col-md-12 text-right mtop20">
                  <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>
  (function () {
    var $email = $('#email');
	
    if ($email.length === 0) { return; }

    var lastChecked = '';
    var lastCheckResult = true; // true = available, false = duplicate

    function checkDuplicateEmail() {
      var email = $.trim($email.val());
      if (!email || email === lastChecked) {
        return;
      }
      lastChecked = email;

      $.post(admin_url + 'misc/lead_email_exists', {
        email: email
      }).done(function (response) {
        var result = response;
        try {
          result = (typeof response === 'string' ? JSON.parse(response) : response);
        } catch (e) {}

        // misc/lead_email_exists returns true if available, false if duplicate
        lastCheckResult = result;
        if (result === false) {
          alert('<?php echo _l('lead_email_already_exists'); ?>');
		  $('#emailError').text("[Email already exists]");
          $email.focus();
        }
      });
    }

    $email.on('blur', checkDuplicateEmail);

    // On submit, do a synchronous duplicate check to be 100% sure and block submit if duplicate
    $('#add_new_leads_form').on('submit', function (e) {
      var email = $.trim($email.val());
      if (!email) {
        alert('<?php echo _l('client_email'); ?>' + ' ' + '<?php echo _l('is_required'); ?>');
        $email.focus();
        e.preventDefault();
        return false;
      }

      var isDuplicate = (lastChecked === email && lastCheckResult === false);

      if (!isDuplicate) {
        // Final synchronous check
        $.ajax({
          url: admin_url + 'misc/lead_email_exists',
          type: 'POST',
          data: { email: email },
          async: false,
          success: function (response) {
            var result = response;
            try {
              result = (typeof response === 'string' ? JSON.parse(response) : response);
            } catch (e) {}
            if (result === false) {
              isDuplicate = true;
            }
          }
        });
      }

      if (isDuplicate) {
	  
        alert('<?php echo _l('lead_email_already_exists'); ?>');
		$('#emailError').text("[Email already exists]");
        $email.focus();
        e.preventDefault();
        return false;
      }

      return true;
    });
  })();
</script>
</body></html>