<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style>
  .jqte { margin: 0; }
  .jqte_editor { min-height: 220px; }
  .template-actions .btn { margin-right: 5px; }
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
		<?php if(!empty($_SESSION['mailersdropdowns'])){ ?>
		
		    <div class="col-md-2 picker">

<div>			
<span class="dropdown">
  <button class="btn btn-default buttons-collection btn-default-dt-options dropdown-toggle" type="button" data-toggle="dropdown" style="width: 180px !important;"><span title="<?=$_SESSION['webmail']['mailer_email'];?>"><?=substr($_SESSION['webmail']['mailer_email'],0,18);?></span>
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
	<?php  foreach ($_SESSION['mailersdropdowns'] as $item) { ?>
	<li><a href="?mt=<?=$item['id'];?>"><?=$item['mailer_email'];?></a></li>
	<?php  } ?>
  </ul>
</span>
</div>
<div>
<a href="<?php echo site_url('admin/webmail/compose'); ?>" class="btn btn-primary mtop10" style="width: 180px !important;">
        <i class="fa-regular fa-paper-plane tw-mr-1"></i>
        <?php echo _l('New Mail'); ?>
</a>
</div>
                <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked mtop10" id="theme_styling_areas">
				
				<?php  foreach ($_SESSION['folderlist'] as $item => $val) { ?>
                    <li role="presentation" class="menu-item-leads">
                        <a href="<?=admin_url('webmail/inbox') ?>?fd=<?php echo $val['folder'];?>"><?php echo $val['folder'];?></a>
                    </li>
					
<?php  } ?> 
<li role="presentation" class="menu-item-leads "><a href="<?=admin_url('webmail/draft') ?>" class="mail-loader ">Draft</a></li>
<li role="presentation" class="menu-item-leads "><a href="<?=admin_url('webmail/templates') ?>" class="mail-loader ">Templates</a></li>	
						<li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=Flagged" class="mail-loader <?php if($_SESSION['webmail']['folder']=='Flagged'){ echo 'folder-active';} ?>">Flagged</a></li> 
                </ul>
            </div>
            <div class="col-md-10">
                <div class="tw-flex tw-items-center tw-mb-2">
                    <h4 class="tw-my-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mr-4">Email Templates</h4>
             </div>
<div class="panel_s">
<div class="panel-body panel-table mail-bg">

  <div class="tw-mb-3">
    <button type="button" class="btn btn-primary" id="addTemplateBtn">
      <i class="fa fa-plus"></i> Add Template
    </button>
  </div>

  <div class="table-responsive">
    <table class="table dt-table" data-order-col="0" data-order-type="desc">
      <thead>
        <tr>
          <th>Subject</th>
          <th>Created</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach (($templates ?? []) as $t) { 
		$message_body=preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($t['body'])));
		?>
          <tr>
            <td>
			<a href="<?php echo admin_url('webmail/compose/0/' . (int) $t['id']); ?>">
			<?php echo html_escape($t['subject'] ?? ''); ?><br />
			<?php echo substr($message_body,0,80); ?>
			</a>
</td>
            <td><?php echo !empty($t['created_at']) ? _dt($t['created_at']) : '-'; ?></td>
            <td>
              <?php if ((int)($t['status'] ?? 1) === 1) { ?>
                <span class="label label-success">Active</span>
              <?php } else { ?>
                <span class="label label-default">Inactive</span>
              <?php } ?>
            </td>
            <td class="template-actions">
              <button
                type="button"
                class="btn btn-info btn-xs edit-template"
                data-id="<?php echo (int) $t['id']; ?>"
                data-subject="<?php echo html_escape($t['subject'] ?? ''); ?>"
                data-body="<?php echo htmlspecialchars($t['body'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
              >
                <i class="fa fa-pencil"></i> Edit
              </button>
              <a href="<?php echo admin_url('webmail/delete_template/' . (int) $t['id']); ?>" class="btn btn-danger btn-xs _delete">
                <i class="fa fa-trash"></i> Delete
              </a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
            </div>
			
			
		<?php }else{?>
		<div class="alert alert-info text-center">
        <?php echo _l('No Webmail Setup Entries'); ?>
        </div>
		<?php } ?>
        </div>
    </div>
</div>

<!-- Add/Edit Template Modal -->
<div class="modal fade" id="templateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <?php echo form_open(admin_url('webmail/save_template'), ['id' => 'templateForm']); ?>
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="templateModalTitle">Add Template</h4>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="template_id" value="0">

          <div class="form-group">
            <label>Subject <span class="text-danger">*</span></label>
            <input type="text" name="subject" id="template_subject" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Description <span class="text-danger">*</span></label>
            <textarea name="body" id="template_body" class="form-control editor" rows="6" required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
          <button type="submit" class="btn btn-primary"><?php echo _l('submit'); ?></button>
        </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>
<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>
<script>
$(function() {
  $('#template_body').jqte();

  function openAddModal() {
    $('#templateModalTitle').text('Add Template');
    $('#template_id').val(0);
    $('#template_subject').val('');
    $('#template_body').jqteVal('');
    $('#templateModal').modal('show');
  }

  function openEditModal($btn) {
    $('#templateModalTitle').text('Edit Template');
    $('#template_id').val($btn.data('id') || 0);
    $('#template_subject').val($btn.data('subject') || '');
    var body = $btn.data('body') || '';
    $('#template_body').jqteVal(body);
    $('#templateModal').modal('show');
  }

  $('#addTemplateBtn').on('click', function() {
    openAddModal();
  });

  $('body').on('click', '.edit-template', function() {
    openEditModal($(this));
  });

  // Basic required validation (jqte keeps textarea synced, but ensure not empty)
  $('#templateForm').on('submit', function(e) {
    var subject = $.trim($('#template_subject').val());
    var body = $.trim($('#template_body').val());
    if (!subject || !body) {
      e.preventDefault();
      alert('Subject and Description are required');
      return false;
    }
    return true;
  });
});
</script>

</body>
</html>