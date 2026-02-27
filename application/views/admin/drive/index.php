<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.attachment-card {
    width: 100%;
    border: 1px solid #e4e6eb;
    border-radius: 10px;
    padding: 8px;
    margin: 8px;
    display: inline-block;
    text-align: center;
    background: #fff;
    transition: 0.2s;
}
.file-icon {
    font-size: 100px;
    padding: 15px 0;
}
  </style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <h4 class="tw-mb-4"><i class="fa-brands fa-google text-danger"></i> Drive (Google)</h4>
			<div class="col-sm-4">
			<div class="attachment-card mail-bg">
                
<!-- File Icon Preview -->
<div class="file-icon">
<?php  echo '<i class="fa-solid fa-file-excel text-success"></i>';?>
</div>

                

<div class="file-name"><a href="<?php echo admin_url('drive/excel'); ?>">Google Sheet</a></div>
<div class="pull-right">
</div>
                    
                

            </div>
			</div>
			
<div class="col-sm-4">
<div class="attachment-card mail-bg">
<!-- File Icon Preview -->
<div class="file-icon"><i class="fa-solid fa-file-word text-primary"></i></div>
<div class="file-name"><a href="<?php echo admin_url('drive/document'); ?>">Google Docs</a></div>
<div class="pull-right">
</div>
                    
                

            </div>
			</div>
			
<div class="col-sm-4">
<?php /*?>
<div class="attachment-card mail-bg">
<!-- File Icon Preview -->
<div class="file-icon"><i class="fa-solid fa-file-powerpoint text-warning"></i></div>
<div class="file-name"><a href="<?php echo admin_url('drive/slides'); ?>">Google Slides</a></div>
<div class="pull-right">
</div>
</div>
<?php */?>
			</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
</body></html>

