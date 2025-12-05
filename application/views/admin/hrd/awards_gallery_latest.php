<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <h3 class="tw-text-xl tw-font-bold mb-3">Latest Image Gallery</h3>
    <?php if (!empty($images)): ?>
      <div class="row" id="latest-gallery">
      <?php foreach ($images as $img):
        $path = $img['image_path'];
        $award_title = $img['award_title'] ?? '';
        if ($path && file_exists(FCPATH . $path)):
      ?>
        <div class="col-xs-6 col-sm-4 col-md-3 mb-4">
          <a href="<?php echo base_url($path); ?>" class="glightbox latest-img-item" data-gallery="gallery1" data-title="<?php echo htmlspecialchars($award_title); ?>">
            <div class="gallery-thumb-wrap">
              <img src="<?php echo base_url($path); ?>" alt="<?php echo htmlspecialchars($award_title); ?>" class="img-responsive gallery-thumb">
              <div class="thumb-title-overlay">
                <span><?php echo htmlspecialchars($award_title); ?></span>
              </div>
            </div>
          </a>
        </div>
      <?php endif; endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info">No images found.</div>
    <?php endif; ?>
  </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
  const lightbox = GLightbox({ selector: '.glightbox', loop:true });
});
</script>
<style>
#latest-gallery {margin-left:-7px;margin-right:-7px;}
.latest-img-item {display: block; margin-bottom: 18px;}
.gallery-thumb-wrap {
  position:relative;
  border-radius:8px;
  overflow:hidden;
  box-shadow: 0 2px 12px 0 rgba(40,40,80,0.10);
  transition: box-shadow 0.18s;
}
.gallery-thumb {
  display:block;
  width:100%;
  height:170px;
  object-fit:cover;
  transition:filter 0.2s;
}
.gallery-thumb-wrap:hover { box-shadow:0 6px 24px 2px #5372ff33; }
.gallery-thumb-wrap:hover .gallery-thumb { filter: brightness(0.92); }
.thumb-title-overlay {
  position:absolute;
  left:0;right:0;bottom:0;
  background:linear-gradient(0deg,rgba(0,0,0,0.77),rgba(0,0,0,0.0));
  padding:8px 12px 2px 12px;
  color:#fff;
  min-height: 35px;
  font-size:14px;
  font-weight:600;
  text-align:center;
  opacity:0.9;
  pointer-events:none;
}
.mb-4 {margin-bottom:22px;}
</style>