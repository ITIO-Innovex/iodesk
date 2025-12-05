<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h4 class="tw-text-xl tw-font-bold tw-text-neutral-700 mb-3"><i class="fa-solid fa-trophy tw-mr-2 "></i> Awards Gallery</h4>
    <?php if (!empty($awards)): ?>
      <div class="row">
        <?php foreach ($awards as $award):
          $award_id = (int)$award['id'];
          $award_title = $award['award_title'] ?? '';
          $images = is_array($award['images']) ? array_values($award['images']) : []; // ensure index
          if (!empty($images)):
            $main_img = $images[0];
            $main_path = $main_img['image_path'];
            ?>
          <div class="col-md-4 col-sm-6 mb-4">
            <div class="panel_s" style="min-height:180px;">
              <div class="panel-heading">
                <h4 class="tw-font-semibold" style="margin-bottom:10px;" title="<?php echo e($award_title); ?>">
                  <i class="fa fa-trophy" style="color:goldenrod;"></i> <?php echo substr(e($award_title), 0, 30); ?>
                </h4>
              </div>
              <div class="panel-body">
                <div class="gallery-wrap">
                  <?php if($main_path && file_exists(FCPATH.$main_path)): ?>
                    <a href="<?php echo base_url($main_path); ?>"
                        class="glightbox gallery-img-link"
                        data-gallery="award<?php echo $award_id; ?>"
                        data-title="<?php echo htmlspecialchars($award_title); ?>">
                      <img src="<?php echo base_url($main_path); ?>"
                           alt="<?php echo htmlspecialchars($award_title); ?>"
                           style="max-width: 100%; object-fit: cover; border: 1px solid #ddd; border-radius: 5px; padding: 2px; transition: box-shadow 0.2s;">
                    </a>
                  <?php endif; ?>
                  <!-- Hidden links for other images -->
                  <?php for($j=1; $j<count($images); $j++): 
                    $img = $images[$j]; $pth = $img['image_path'];
                    if ($pth && file_exists(FCPATH.$pth)): ?>
                    <a href="<?php echo base_url($pth); ?>" class="glightbox"
                      data-gallery="award<?php echo $award_id; ?>" data-title="<?php echo htmlspecialchars($award_title); ?>" style="display:none"></a>
                  <?php endif; endfor; ?>
                </div>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="col-md-4 col-sm-6 mb-4">
            <div class="panel_s" style="min-height:180px;">
              <div class="panel-heading">
                <h4 class="tw-font-semibold" style="margin-bottom:10px;">
                  <i class="fa fa-trophy" style="color:goldenrod;"></i> <?php echo e($award['award_title']); ?>
                </h4>
              </div>
              <div class="panel-body"><span>No images</span></div>
            </div>
          </div>
        <?php endif; endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No awards found.</div>
    <?php endif; ?>
  </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  GLightbox({ selector: '.glightbox', loop:true });
});
</script>
<style>
.gallery-wrap {
  gap: 4px;
}
.gallery-img-link img:hover {
  box-shadow: 0 0 8px #7dccfc;
}
.mb-4 {margin-bottom:22px;}
</style>
<?php init_tail(); ?>
