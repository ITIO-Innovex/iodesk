<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="tw-mb-2 sm:tw-mb-4 tw-flex tw-items-center tw-justify-between">
          <h4 class="tw-m-0 tw-font-semibold tw-text-lg tw-text-neutral-700">
            <?php
              $isCopyMode = !empty($is_copy);
              echo $isCopyMode ? 'Copy Template' : (!empty($template) ? 'Edit Template' : 'Add Template');
            ?>
          </h4>
          <a href="<?php echo admin_url('emailtemplate'); ?>" class="btn btn-default">
            <i class="fa-solid fa-arrow-left tw-mr-1"></i> Back
          </a>
        </div>
		
		<div class="row">
        <div class="col-sm-8">
        <div class="panel_s">
          <div class="panel-body">
            <?php
              $actionUrl = admin_url('emailtemplate/manage' . (!empty($template) ? '/' . (int) $template['id'] : ''));
              if (!empty($is_copy)) {
                $actionUrl = admin_url('emailtemplate/create_manage/' . (int) ($template['id'] ?? 0));
              }
            ?>
            <?php echo form_open($actionUrl, ['id' => 'internal-email-template-form']); ?>
              <input type="hidden" name="id" value="<?php echo (!empty($template) && empty($is_copy)) ? (int) $template['id'] : 0; ?>">

 <div class="form-group">
 <?php if(is_super()){ ?>
            <label><span class="text-danger">*</span> Template Title </label>
            <input type="text" name="template_title" id="template_title" class="form-control" value="<?php echo $template['template_title'] ?? '';?>" required>
			 <?php }else{ ?>
			 <label><span class="text-danger">*</span> Template Title :  <?php echo $template['template_title'] ?? '';?></label>
			 <?php } ?>
          </div> 

 <div class="form-group">
            <label><span class="text-danger">*</span> Subject</label>
            <input type="text" name="subject" id="template_subject" class="form-control" value="<?php echo $template['subject'] ?? '';?>" required>
          </div>             
			  
			  <div class="form-group">
                <label for="email_body">Email Body </label>
                <textarea id="email_body" name="email_body" class="form-control" rows="12" required><?php echo html_escape($template['email_body'] ?? ''); ?></textarea>
              </div>

              <div class="text-right">
                <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
              </div>
            <?php echo form_close(); ?>
          </div>
        </div>
		</div>
		<div class="col-sm-4">
		<div class="panel_s">
          <div class="panel-body">
		  <div style="font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6;">
  <p><strong>Instruction for Dynamic Variables</strong></p>

  <p>
    <code>{{ }}</code> these types of words are dynamic variables. 
    You can use them in emails to automatically display dynamic data.
  </p>

  <p style="color: #d9534f; font-weight: bold;">Important:</p>
  <ul>
    <li>Do not modify or remove these variables.</li>
    <li>Do not change their position while editing the email template.</li>
  </ul>
  <p style="color: #000000; font-weight: bold;">Leave Application:</p>
  <ul>
    <li>Do not modify or remove these variables.</li>
    <li>Do not change their position while editing the email template.</li>
  </ul>
</div>
		  </div></div>
		</div>
		</div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(function () {
  init_editor('#email_body', {
    toolbar_sticky: true
  });

  appValidateForm($('#internal-email-template-form'), {
    template_title: 'required',
    subject: 'required',
    email_body: 'required'
  });
});
</script>
</body>
</html>

