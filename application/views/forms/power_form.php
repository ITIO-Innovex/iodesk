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
            
            <?php echo form_open_multipart(base_url('forms/power_form_submit'), ['id' => 'power-form']); ?>
              <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
			  
			  <div class="top_stats_wrapper">
              <div class="tw-text-neutral-800 mtop5 tw-flex tw-items-center tw-justify-between">
			  
              <div class="form-section row">
                <?php /*?><div class="row">
                 <div class="col-md-4"><label for="name" class="control-label">Name : <small class="req text-danger">* </small></label></div>
				<div class="col-md-8">
                <input type="text" name="name" class="form-control" value="<?php echo e($form['name'] ?? ''); ?>" required>
                </div>
			</div><?php */?>
			
			<?php foreach ($fields as $field) {
              $fname = $field['name'];
              $label = $field['label'];
              $required = !empty($field['is_required']);
              $type = $field['type'];
              $opts = [];
              if (!empty($field['options_json'])) {
                  $opts = json_decode($field['options_json'], true) ?: [];
              }
			  $field_width="col-md-6";
			  if (($type === 'textarea') || ($type === 'editor')){
			  $field_width="col-md-12";
			  }
              ?>
              <div class="<?php echo $field_width;?>">
                <div class="form-group ">
				
                  <label class="control-label"><?php echo e($label); ?><?php if ($required) { ?><small class="req text-danger">* </small><?php } ?></label>
                  <?php if ($type === 'text') { ?>
                    <input type="text" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'email') { ?>
                    <input type="email" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'url') { ?>
                    <input type="url" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'number') { ?>
                    <input type="number" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'textarea') { ?>
                    <textarea class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" rows="3" <?php echo $required ? 'required' : ''; ?>></textarea>
                  <?php } elseif ($type === 'editor') { ?>
                    <textarea class="form-control editor web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" rows="4" <?php echo $required ? 'required' : ''; ?>></textarea>
                  <?php } elseif ($type === 'select') { ?>
                    <select name="<?php echo e($fname); ?>" data-field-name="<?php echo e($fname); ?>" class="form-control web-entry-field" <?php echo $required ? 'required' : ''; ?>>
                      <option value="">-- Select --</option>
                      <?php foreach ($opts as $opt) { ?>
                        <option value="<?php echo e($opt); ?>"><?php echo e($opt); ?></option>
                      <?php } ?>
                    </select>
                  <?php } elseif ($type === 'radio') { ?>
                    <div class="web-entry-field" data-field-name="<?php echo e($fname); ?>">
                      <?php foreach ($opts as $opt) { ?>
                        <label class="radio-inline">
                          <input type="radio" name="<?php echo e($fname); ?>" value="<?php echo e($opt); ?>" <?php echo $required ? 'required' : ''; ?>> <?php echo e($opt); ?>
                        </label>
                      <?php } ?>
                    </div>
                  <?php } elseif ($type === 'checkbox') { ?>
                    <div class="web-entry-field" data-field-name="<?php echo e($fname); ?>">
                      <?php foreach ($opts as $opt) { ?>
                        <label class="checkbox-inline">
                          <input type="checkbox" name="<?php echo e($fname); ?>[]" value="<?php echo e($opt); ?>"> <?php echo e($opt); ?>
                        </label>
                      <?php } ?>
                    </div>
                  <?php } elseif ($type === 'datetime') { ?>
                    <input type="datetime-local" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } elseif ($type === 'file') { ?>
                    <input type="file" class="form-control" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>[]" multiple <?php echo $required ? 'required' : ''; ?>>
                    <div class="tw-mt-2 existing-files" data-field-name="<?php echo e($fname); ?>" style="display:none;"></div>
                  <?php } else { ?>
                    <input type="text" class="form-control web-entry-field" data-field-name="<?php echo e($fname); ?>" name="<?php echo e($fname); ?>" <?php echo $required ? 'required' : ''; ?>>
                  <?php } ?>
                </div>
              </div>
          <?php } ?>
              </div>
              </div>
              </div>
              <hr>
			  
			  
			  

              <div class="tw-flex tw-gap-2">
                <button type="submit" class="btn btn-primary" data-status="Submitted" >Submit</button>
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
	<link rel="stylesheet" href="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.css"/>
<script src="https://unpkg.com/jodit@4.7.6/es2021/jodit.min.js"></script>
<script>
//window.email_editor = Jodit.make('#wfFinalBody');
window.editor = Jodit.make('.editor');
</script>


</body>
</html>
