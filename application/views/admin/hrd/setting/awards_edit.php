<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-text-neutral-700"><i class="fa-solid fa-trophy tw-mr-2 "></i> Edit Award</h4>
    <div class="panel_s">
      <div class="panel-body">
        <?php echo form_open_multipart(admin_url('hrd/setting/awards_update/'.$award['id'])); ?>
          <div class="form-group">
            <label>Award Title</label>
            <input type="text" name="award_title" class="form-control" value="<?php echo e($award['award_title']); ?>" required>
          </div>
          <div class="form-group">
            <label>Add More Images</label>
            <input type="file" name="image[]" class="form-control" multiple accept="image/*">
            <small>Leave blank if not adding.</small>
          </div>
          <button class="btn btn-primary">Update</button>
          <a href="<?php echo admin_url('hrd/setting/awards'); ?>" class="btn btn-default">Cancel</a>
        <?php echo form_close(); ?>
        <h5 style="margin-top:2em;">Existing Images</h5>
        <?php if (!empty($award['images'])): ?>
          <?php foreach ($award['images'] as $img): ?>
            <span style="display:inline-block;position:relative;margin:0 10px 10px 0;">
              <img src="<?php echo base_url($img['image_path']); ?>" style="max-width:100px;max-height:100px;border:1px solid #ccc;padding:2px;border-radius:4px;">
              <button type="button" class="btn btn-xs btn-danger edit-delete-img-btn" data-image-id="<?php echo $img['id']; ?>" title="Delete image"
                style="position:absolute;top:1px;right:1px;padding:1px 5px;line-height:1;z-index:9;">&times;</button>
            </span>
          <?php endforeach; ?>
        <?php else: ?>
          <span>No images</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<script>
$(function(){
  $('.edit-delete-img-btn').on('click', function(e) {
    e.preventDefault();
    var btn = $(this), imgid = btn.data('image-id');
    if (!confirm('Delete this image?')) return;
    btn.prop('disabled', true);
    $.ajax({
      url: '<?php echo admin_url('hrd/setting/delete_award_image/'); ?>' + imgid,
      type: 'POST',
      dataType: 'json',
      success: function(res) {
        if(res && res.success) {
          btn.closest('span').fadeOut(200, function(){ $(this).remove(); });
        } else {
          alert('Could not delete image.');
          btn.prop('disabled', false);
        }
      },
      error: function(){
        alert('Server error');
        btn.prop('disabled', false);
      }
    });
  });
});
</script>