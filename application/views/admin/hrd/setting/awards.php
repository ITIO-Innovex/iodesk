<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
  <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-mb-2"><span class="pull-left display-block mright5 tw-mb-2"><i class="fa-solid fa-trophy tw-mr-2 "></i>  Gallery / Awards <i class="fa-solid fa-circle-info" title="Display All Uploaded Gallery / Awards Images" style=" color:khaki;"></i></span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body panel-table-fullxx">
            <div class="tw-flex tw-justify-between tw-items-center tw-mb-2">
              <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700">Gallery / Awards</h4>
              <a href="#" class="btn btn-primary" onclick="$('#award_modal').modal('show');return false;">Add Gallery / Awards Image</a>
            </div>
            <div class="table-responsive">
              <table class="table dt-table" data-order-col="3" data-order-type="desc">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Image(s)</th>
                    <th>Added on</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($awards)) { foreach ($awards as $a) { 
                    $name = isset($a['award_title']) ? $a['award_title'] : '';
                    $added = isset($a['addedon']) ? $a['addedon'] : '';
                    $statusVal = isset($a['status']) ? (int)$a['status'] : 1;
                    $statusLbl = $statusVal === 1 ? '<span class="label label-success">Active</span>' : '<span class="label label-danger">Inactive</span>';
                    $awardId = (int)$a['id'];
                    $galleryType = strtolower($a['gallery_type'] ?? 'awards');
                    $galleryTypeLbl = $galleryType === 'gallery' ? '<span class="label label-info">Gallery</span>' : '<span class="label label-warning">Awards</span>';
                  ?>
                  <tr>
                    <td><?php echo e($name); ?></td>
                    <td><?php echo $galleryTypeLbl; ?></td>
                    <td>
                      <?php if (!empty($a['images'])) {
                        foreach ($a['images'] as $img) {
                          $path = $img['image_path']; $imageId = (int)$img['id'];
                          if ($path && file_exists(FCPATH . $path)) { ?>
                            <div style="display:inline-block;margin-right:8px;margin-bottom:5px;position:relative;">
                              <a href="<?php echo base_url($path); ?>" target="_blank" class="image-preview-link">
                                <img src="<?php echo base_url($path); ?>" alt="<?php echo e($name); ?>" style="max-width: 100px; max-height: 100px; object-fit: contain; border: 1px solid #ddd; padding: 2px; border-radius: 4px; cursor: pointer;">
                              </a>
                              <button type="button" class="btn btn-xs btn-danger delete-award-img-btn" title="Delete image" data-image-id="<?php echo $imageId; ?>" style="position:absolute;top:1px;right:1px;padding:1px 5px;line-height:1;z-index:9;">&times;</button>
                            </div>
                          <?php } else { ?>
                            <span class="text-muted">Image not found</span><br>
                          <?php }
                        }
                      } else { ?>
                        <span class="text-muted">No images found</span>
                      <?php } ?>
                    </td>
                    <td><?php echo e($added); ?></td>
                    <td><?php echo $statusLbl; ?></td>
                    <td>
                      <a href="<?php echo admin_url('hrd/setting/awards_update/'.$awardId); ?>" class="btn btn-default btn-sm" title="Edit"><i class="fa fa-edit"></i></a>
                      <a href="<?php echo admin_url('hrd/setting/awards_delete/'.$awardId); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this award and all its images?');" title="Delete"><i class="fa fa-trash"></i></a>
                    </td>
                  </tr>
                  <?php } } else { ?>
                  <tr>
                    <td colspan="5" class="text-center">No awards found. Click "Add Award Image" to upload.</td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="award_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <?php echo form_open_multipart(admin_url('hrd/setting/awards_add'), ['id' => 'award-form']); ?>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Gallery / Awards Image</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Title</label>
          <input type="text" name="award_title" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Type</label>
          <select name="gallery_type" class="form-control" required>
            <option value="awards" selected>Awards</option>
            <option value="gallery">Gallery</option>
          </select>
        </div>
        <div class="form-group">
          <label>Image</label>
          <input type="file" name="image[]" class="form-control" multiple accept="image/*" required>
          <small class="text-muted">You can select multiple images. Allowed formats: JPG, JPEG, PNG, GIF, WEBP</small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>
</div>

<style>
.image-preview-link {
  display: inline-block;
}
.image-preview-link img:hover {
  opacity: 0.8;
  border-color: #007bff;
}
</style>

<?php init_tail(); ?>
<script>
$(function(){
  $('.delete-award-img-btn').on('click', function(e) {
    e.preventDefault();
    var btn = $(this), imgid = btn.data('image-id');
    if (!confirm('Delete this award image?')) return;
    btn.prop('disabled', true);
    $.ajax({
      url: '<?php echo admin_url('hrd/setting/delete_award_image/'); ?>' + imgid,
      type: 'POST',
      dataType: 'json',
      success: function(res) {
        if(res && res.success) {
          btn.closest('div').fadeOut(200, function(){ $(this).remove(); });
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
</body></html>

