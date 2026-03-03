<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); //print_r($files); ?>
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
        <div class="tw-mb-2 sm:tw-mb-4"> <a href="<?php echo admin_url('drive/create_doc'); ?>" class="btn btn-sm btn-primary" target="_blank"> <i class="fa-solid fa-file-word tw-mr-1"></i> <?php echo 'Add New Document'; ?> </a><button class="btn btn-default btn-sm btn-default-dt-options tw-mx-1 bg-info refreshfolder" 
			  type="button" title="Refresh Files"><span><i class="fa-solid fa-retweet" id="refresh-loader"></i></span></button>
			  <a href="<?php echo admin_url('drive/document'); ?>" class="btn btn-sm btn-info pull-right  tw-mx-2"  title="Google Sheet Home"> <i class="fa-solid fa-file-excel"></i></a><a href="<?php echo admin_url('drive/excel'); ?>" class="btn btn-sm btn-info pull-right"  title="Google Document Home"> <i class="fa-solid fa-file-word tw-mr-1"></i></a>
			  </div>
        <div class="panel_s">
          <div class="panel-body panel-table-full">
		 <div class="row">
            <?php if (isset($files) && count($files) > 0) { ?>
			<?php foreach($files as $file): ?>
			<?php //echo $file->getName(); ?> <?php //echo $file->getCreatedTime(); ?>
			<div class="col-sm-3">
			<div class="attachment-card mail-bg">
                
                

<!-- File Icon Preview -->
<div class="file-icon">
<?php  echo '<i class="fa-solid fa-file-word text-primary"></i>';?>
</div>

                

<div class="file-name"><a href="<?php echo $file['web_link']; ?>" target="_blank"><?php //echo $file->getName(); ?><?php echo $file['file_name']; ?></a></div>
<div class="pull-right">
<a href="<?php echo $file['web_link']; ?>" target="_blank" class="btn btn-info btn-icon"><i class="fa fa-pencil"></i></a> 
<a href="<?php echo admin_url('drive/delete_doc/'.$file['file_id']); ?>" class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
</div>
                    
                

            </div>
			</div>
			<?php endforeach; ?>
           
            <?php }  ?>
<div class="col-sm-3">
<a href="<?php echo admin_url('drive/create_doc'); ?>" target="_blank">
<div class="attachment-card mail-bg">
<!-- File Icon Preview -->
<div class="file-icon"><i class="fa-solid fa-file-word text-primary"></i></div>
<div class="file-name btn btn-warning" style="margin-top:10px;">Add New Document</div>
</a>
</div>
                    
                

            </div>
			</div>
         
          </div></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- /.modal -->

<?php init_tail(); ?>
<script>
$('.refreshfolder').click(function(){ 
		 $("#refresh-loader").addClass('fa-spin-pulse');
		 $.post(admin_url + 'drive/sync_drive_files', {
        })
        .done(function(response) { 
            response = JSON.parse(response);
			if(response.alert_type=="success"){
			 alert_float(response.alert_type, response.message);
			 $("#refresh-loader").removeClass('fa-spin-pulse');
			 location.reload(); // Reloads the current page
			}else{
			 alert_float(response.alert_type, response.message);
			 $("#refresh-loader").removeClass('fa-spin-pulse');
			}
            
        });
});
</script>
</body></html> 

