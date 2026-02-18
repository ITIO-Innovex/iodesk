<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php //print_r($_SESSION['subfolderlist']);exit;?>
<style>
@media (min-width: 768px) {
    .modal-dialog {
        width: unset !important;
    }
	table.number-index-2 tbody>tr>td:nth-child(2), table.number-index-2 thead>tr>th:nth-child(2) {
        text-align: left !important;
    }
	
	input.form-control, input[type=text] { border-radius: 0px !important; }
	.input-sm { border-radius : 0px !important; }
}
.dropdown-menu { width:275px !important;}
.copy-email.pull-right{ width:38px !important;margin-top: -5px;}
.attachment-card {
    width: 130px;
    border: 1px solid #e4e6eb;
    border-radius: 10px;
    padding: 8px;
    margin: 8px;
    display: inline-block;
    text-align: center;
    background: #fff;
    transition: 0.2s;
}

.attachment-card:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.thumb-img {
    width: 100%;
    height: 90px;
    object-fit: cover;
    border-radius: 6px;
}

.file-icon {
    font-size: 50px;
    padding: 15px 0;
}

.file-name {
    font-size: 12px;
    margin-top: 6px;
    word-break: break-word;
}
.file-name {
    font-size: 12px;
    margin-top: 6px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
		<?php if(!empty($_SESSION['mailersdropdowns'])){ ?>
            
			
			<div class="col-md-2 picker">

<div>			
<span class="dropdown">
  <button class="btn btn-default buttons-collection btn-default-dt-options dropdown-toggle" type="button" data-toggle="dropdown" style="width: 180px !important;"><span title="<?=$_SESSION['webmail']['mailer_email'] ?? '';?>"><?=substr($_SESSION['webmail']['mailer_email'] ?? '',0,18);?></span>
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
				
				<?php  
				
				foreach ($_SESSION['folderlist'] as $item => $val) { ?>
                    <li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=<?php echo $val['folder'];?>" class="mail-loader <?php if($_SESSION['webmail']['folder']==$val['folder']){ echo 'folder-active';} ?>"><?php echo ucwords(strtolower($val['folder']));?></a>
                    </li>
					<?php } $folderNames = array_column($_SESSION['folderlist'], 'folder'); ?>  
					<li role="presentation" class="menu-item-leads ">
					<?php if (!in_array('Outbox', $folderNames)) { ?>
                        <a href="inbox?fd=Outbox" class="mail-loader <?php if($_SESSION['webmail']['folder']=='Outbox'){ echo 'folder-active';} ?>">Outbox</a>
						<?php } ?>
						<li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=Flagged" class="mail-loader <?php if($_SESSION['webmail']['folder']=='Flagged'){ echo 'folder-active';} ?>">Flagged</a>
						<li role="presentation" class="menu-item-leads ">
                        <a href="inbox?fd=Deleted" class="mail-loader <?php if($_SESSION['webmail']['folder']=='Deleted'){ echo 'folder-active';} ?>">Deleted</a>
                    </li><?php if(count($_SESSION['folderlist']) <= 3 ){?>
					<li class="tw-p-1" style="display: flex;justify-content: right;"> <a href="<?php echo admin_url('webmail/getfolderlist'); ?>" class="text-danger _delete"><i class="fa-solid fa-folder-plus text-warning fa-2x" title="Fetch all folder"></i></a> </li><?php } ?>
                </ul>
            </div>
            <div class="col-md-10">
			
			 <div class="row">
			 <form>
			  <div class="col-md-7 mbot10"><div class="dt-buttons btn-group">
			 <?php if(!empty($_SESSION['webmail']['folder'])){ ?>
			  <button class="btn btn-default buttons-collection btn-sm btn-default-dt-options"  type="button" aria-haspopup="true"><span><?php echo $_SESSION['webmail']['folder'];?></span></button> <?php if(isset($_SESSION['inbox-total-email'])&&!empty($_SESSION['inbox-total-email'])){?><button class="btn btn-default btn-sm btn-default-dt-options bg-danger" type="button" ><span><?=$_SESSION['inbox-total-email'];?></span></button> <?php } ?>
			  <?php } ?><button class="btn btn-default btn-sm btn-default-dt-options bg-info refreshemail" 
			  type="button" title="Refresh <?php echo $_SESSION['webmail']['folder'];?> Box Online"><span><i class="fa-solid fa-retweet" id="refresh-loader"></i></span></button>
			  <span id="mail-loader"></span>
<a href="<?php echo admin_url('webmail/contacts') ?>"  target="_blank" class="btn btn-default btn-sm btn-default-dt-options bg-info" 
			  type="button" title="Manage Contacts"><i class="fa-solid fa-user-plus"></i></a>
<a href="<?php echo admin_url('webmail/appoinment') ?>" target="_blank" class="btn btn-default btn-sm btn-default-dt-options bg-info" 
			  type="button" title="Manage Appoinment"><i class="fa-solid fa-calendar-plus"></i></a>
			  </div></div>
			  <div class="col-md-5 mbot10">
			  <div class="dt-buttons btn-group55 tw-text-right">
			  <div class="w-full tw-inline-flex sm:max-w-xs">
<select name="stype" class="form-control input-group-addon" id="search_code" style="width:auto;border-top-left-radius: .375rem;border-bottom-left-radius: .375rem;" required>
<option value="">Select type</option>
<!--<option value="from_email" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="from_email"){ ?> selected="selected"<?php }?>>From Email</option>
<option value="from_name" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="from_name"){ ?> selected="selected"<?php }?>>From Name</option>-->
<!--<option value="to_emails" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="to_emails"){ ?> selected="selected"<?php }?>>To Email</option>
<option value="cc_emails" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="cc_emails"){ ?> selected="selected"<?php }?>>CC Email</option>-->
<!--<option value="bcc_emails" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="bcc_emails"){ ?> selected="selected"<?php }?>>BCC Email</option>-->
<option value="subject" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="subject"){ ?> selected="selected"<?php }?>>Subject</option>
<option value="body" <?php if(isset($_SESSION['stype'])&&$_SESSION['stype']=="body"){ ?> selected="selected"<?php }?>>Mail Body</option>
</select>

              <input type="text" class="form-control" name="skey" placeholder="Enter Search Keywords" value="<?php if(isset($_SESSION['skey'])&&$_SESSION['skey']){echo trim($_SESSION['skey']); } ?>" required>
              <button type="submit" class="input-group-addon" style="padding-right: 25px;"><span class="fa fa-search"></span></button>
                </div>
				</div>
				</div>
				</form>
			  </div>

        
                <div class="panel_s">
                    <div class="panel-body panel-table-full mail-bg">

<?php  if (count($inboxemail) == 0) { ?>
<div class="alert alert-info text-center">

    <?php echo _l('Records Not Found'); ?>
</div>
<?php } ?>
<div class="table-responsive">
 <table class="table table-clients number-index-2 dataTable no-footer">

<?php


if(isset($_GET['mode'])&&$_GET['mode']=="attached"){
$searchemail=$_GET['skey'];

        $this->db->select('attachments,');
        $this->db->where('from_email', $searchemail);
		$this->db->where('status', 1);
		$this->db->where('attachments !=', '');
        $attachmentslist=$this->db->get(db_prefix() . 'emails')->result_array();
		//print_r($attachmentslist);
        //echo $this->db->last_query();


foreach ($attachmentslist as $row) {

    if (!empty($row['attachments'])) {

        $files = explode(',', $row['attachments']);

        foreach ($files as $file) {

            $file = trim($file);
            $ext  = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $url  = base_url($file);
            $name = basename($file);

            ?>
            
            <div class="attachment-card">
                
                <?php if (in_array($ext, ['jpg','jpeg','png','gif','webp'])) { ?>
                    
                    <!-- Image Thumbnail -->
                    <a href="<?= $url ?>" target="_blank">
                        <img src="<?= $url ?>" class="thumb-img">
                    </a>

                <?php } else { ?>

                    <!-- File Icon Preview -->
                    <div class="file-icon">
                        <?php
                        if ($ext == 'pdf') echo '<i class="fa fa-file-pdf text-danger"></i>';
                        elseif (in_array($ext,['doc','docx'])) echo '<i class="fa fa-file-word text-primary"></i>';
                        elseif (in_array($ext,['xls','xlsx','csv','ods'])) echo '<i class="fa fa-file-excel text-success"></i>';
                        else echo '<i class="fa fa-file text-secondary"></i>';
                        ?>
                    </div>

                <?php } ?>

                <div class="file-name">
                    <a href="<?= $url ?>" target="_blank"><?= $name ?></a>
                </div>

            </div>

            <?php
        }
    }
}



		
		
}else{
$cnt = 101;
$lastGroup = '';
$todayDate = date('Y-m-d');
$yesterdayDate = date('Y-m-d', strtotime('-1 day'));
$last7Date = date('Y-m-d', strtotime('-7 days'));
foreach ($inboxemail as $message) { 
    $cnt++;
    $msgDateRaw = $message['date'] ?? '';
    $msgDate = $msgDateRaw ? date('Y-m-d', strtotime($msgDateRaw)) : '';
    if ($msgDate === $todayDate) {
        $groupLabel = 'Today';
    } elseif ($msgDate === $yesterdayDate) {
        $groupLabel = 'Yesterday';
    } elseif ($msgDate !== '' && $msgDate >= $last7Date) {
        $groupLabel = 'Last 7 days';
    } else {
        $groupLabel = 'Older';
    }
    if ($groupLabel !== $lastGroup) {
        $lastGroup = $groupLabel;
        echo '<tr class="mail-separator"><td colspan="4" style="background:#f5ecc8;font-weight:600;">' . $groupLabel . '</td></tr>';
    }
//print_r($message);//exit;
$string = "tw-bg-warning-600 tw-bg-primary-600 tw-bg-danger-600 tw-bg-danger-600 tw-bg-neutral-600 tw-bg-success-600 tw-bg-warning-800 tw-bg-primary-800 tw-bg-danger-800 tw-bg-danger-800 tw-bg-neutral-800 tw-bg-success-800";
// Step 1: Convert string to array of words
$words = preg_split('/\s+/', $string); // Split by spaces or multiple spaces
// Step 2: Select a random word
$randomWord = $words[array_rand($words)];
$mailcss="";
if(isset($message['status'])&&$message['status']==1){ $mailcss="isread"; }



?>
<tr class="table<?=$message['id'];?>">
<td style="width:35px;"><div class="tw-rounded-full <?php echo $randomWord;?> tw-text-white tw-inline-flex tw-items-center tw-justify-center tw-h-8 tw-w-8 -tw-mt-1 group-hover:!tw-bg-primary-700"><?=strtoupper(substr($message['from_email'] ?? '',0,2));?></div></td>
<td style="width:50px;"><div>
<?php if(isset($message['isfalg'])&&$message['isfalg']==1){ ?>
<i class="fa-solid fa-fire-flame-simple tw-text-info-800 tw-cursor-pointer isflag" data-mid="<?=$message['id'];?>" data-fid="0" title="Click for normal"></i>
<?php }else{ ?>
<i class="fa-solid fa-fire-flame-simple tw-text-info-300 tw-cursor-pointer isflag isflag<?=$message['id'];?>" data-mid="<?=$message['id'];?>" data-fid="1" title="Click for important"></i>
<?php } ?>
<?php if(isset($message['is_deleted'])&&$message['is_deleted']==0){ ?>
<i class="fa-solid fa-trash text-danger tw-cursor-pointer isdelete" data-mid="<?=$message['id'];?>" data-fid="1" title="Delete"></i>
<?php }else{ ?>
<i class="fa-solid fa-envelope-circle-check text-warning tw-cursor-pointer isdelete " data-mid="<?=$message['id'];?>" data-fid="0" title="Move to inbox"></i>
<i class="fa-solid fa-square-xmark text-danger tw-cursor-pointer isdelete" data-mid="<?=$message['id'];?>" data-fid="3" title="Delete Permanent"></i>
<?php } ?>
<?php if(isset($message['isattachments'])&&$message['isattachments']==1&&$message['folder']!='Spam'){ ?>
&nbsp;<i class="fa-solid fa-paperclip" style="color: #000000;"></i>
<?php } ?>
<?php if(isset($message['folder'])&&$message['folder']=='Spam'&&$message['is_deleted']==0){ ?>
<i class="fa-solid fa-envelope-circle-check text-warning isdelete" data-mid="<?=$message['id'];?>" data-fid="2" title="Move to inbox"></i>
<?php } ?>

<?php if(isset($message['folder'])&&$message['folder']=='Outbox'){ ?>
<a href="<?php echo admin_url('webmail/update_schedule/');?><?php echo $message['uniqid'];?>"<i class="fa-solid fa-pen-to-square text-success" data-mid="<?=$message['id'];?>" title="Edit"></i></a>
<?php } ?>


</div>
</td>

	<td class="hrefmodal tw-cursor-pointer <?php echo $mailcss;?> isread<?=$message['id'];?>" data-mid="<?=$message['id'];?>" data-fid="0" data-tid="<?=$message['subject'];?>" data-id="msg<?=$cnt;?>" title="<?=$message['subject'];?>" mailto="<?=htmlspecialchars($message['from_email'] ?? '');?>" mailtox="<?=htmlspecialchars($message['to_emails'] ?? '');?>" mailcc="<?=htmlspecialchars($message['cc_emails'] ?? '');?>" mailbcc="<?=htmlspecialchars($message['bcc_emails'] ?? '');?>" messageid="<?=$message['messageid'];?>" data-date="<?=$message['date'];?>" data-folder="<?=$message['folder'];?>" data-body="<?=htmlspecialchars($message['body'] ?? '', ENT_QUOTES, 'UTF-8');?>" data-attachments="<?=htmlspecialchars(isset($message['attachments']) && $message['attachments'] !== null ? $message['attachments'] : '', ENT_QUOTES, 'UTF-8');?>"><div class="w-36 h-36 bg-red-600 rounded-full"></div> <span> <b><?=$message['subject'];?></b><br>From : <?=htmlspecialchars($message['from_email'] ?? '');?> To : <?=htmlspecialchars($message['to_emails'] ?? '');?></span></td>
	<td class="w-25 text-end" style="min-width: 130px;"><span><?=$message['date'];?></span><?php if(!empty($_SESSION['webmail']['folder'])&&$_SESSION['webmail']['folder']=="Search"){ echo "<br><span  class='text-info'> ".$message['folder']."</span>"; } ?></td>
</tr>
<tr><td colspan="2" style="display:none;" id="msg<?=$cnt;?>">


<?php
echo '<iframe srcdoc="' . htmlspecialchars($message['body'] ?? '') . '" style="width: 100%; min-height:50px; border: none;" onload="adjustIframeHeight(this)"></iframe>';
// Directory to save attachments

?>
<?php if(isset($message['attachments'])&&$message['attachments']){

$attachments = explode(',', $message['attachments']);

/////////////////////////
// Remove duplicates
$uniqueArray = array_unique($attachments);

// Convert back to a string
$attachments = implode(",", $uniqueArray);

////////////////////////
$attachments = explode(',', $attachments);

foreach($attachments as $attach){

if($_SESSION['webmail']['folder']=="Outbox"){
$filePath = site_url() . 'uploads/email_queue/' . $attach;
}else{
$filePath = site_url() . '/' . $attach;
}
?>
<i class="fa-solid fa-paperclip"></i> <a href="<?=$filePath;?>" target="_blank" title="Click to view"><?=$filePath;?></a><br>
<?php }} ?>



</td></tr>
<?php } } ?>

  </tbody>
  </table>
  </div>
<div class="dataTables_paginate paging_simple_numbers" id="clients_paginate"><ul class="pagination">
<?php
// Paging
// Configuration
$totalRecords = $_SESSION['inbox-total-email']; // Total number of records (replace with your DB query result)
$recordsPerPage = $_SESSION['mail_limit']; // Records per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page from URL
$current_page = max(1, $current_page); // Ensure current page is at least 1

// Calculate total pages and boundaries
$totalPages = ceil($totalRecords / $recordsPerPage);
$startPage = max(1, $current_page - 5); // Start page for display
$endPage = min($totalPages, $startPage + 9); // End page for display

// Ensure proper range of start and end pages
if ($endPage - $startPage < 9) {
    $startPage = max(1, $endPage - 9);
}

// Generate Previous and Next page numbers
$prevPage = $current_page > 1 ? $current_page - 1 : null;
$nextPage = $current_page < $totalPages ? $current_page + 1 : null;

// Display the pagination
//echo '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center ">';

// Previous Button
if ($prevPage) {
    echo '<li class="paginate_button previous" id="clients_previous"><a class="page-link " href="?page=' . $prevPage . '">Previous</a></li>';
}

// Page Links
for ($i = $startPage; $i <= $endPage; $i++) {
    if ($i == $current_page) {
        echo '<li class="paginate_button active 44"><a class="page-link">' . $i . '</a></li>';
    } else {
        echo '<li class="paginate_button 11"><a class="page-link"  href="?page=' . $i . '">' . $i . '</a></li>';
    }
}

// Next Button
if ($nextPage) {
    echo '<li class="paginate_button next" id="clients_next"><a class="page-link" href="?page=' . $nextPage . '">Next</a></li>';
}

//echo '</ul></nav>';

// Styling for pagination (optional, Bootstrap 5 example)
?>
</ul></div>
  



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

<div class="modal" id="myModal12">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <span class="modal-title"></span>
          
        

      </div>
      <!-- Modal body -->
      <div class="modal-body">
        <div id="messageDisplay" class="p-4"></div>
		<div id="replyform p-2 border rounded">
  <p class="d-inline-flex gap-1 text-end">
 
  <a class="btn btn-warning mtop10" id="reply-button"><i class="fa-solid fa-reply"></i> Reply</a>
  <a class="btn btn-info mtop10" id="forward-button" style="margin-left: 10px;"><i class="fa-solid fa-share"></i> Forward</a>
</p>
<div class="collapse" id="reply-box">
  <div class="card card-body">
  
  
  
    <form action="<?=  admin_url('webmail/Reply') ?>" method="post" enctype="multipart/form-data">
	<!-- CSRF Token -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
               value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" name="redirect" value="inbox.php">
	<input type="hidden" name="messageid" id="messageidIT" value="">
	<input type="hidden" name="messagetype" id="messagetypeIT" value="Reply">
      <div class="mb-3">
        <label for="recipientEmail" class="form-label">Recipient Email</label>
        <input type="text" class="form-control" id="recipientEmailIT" name="recipientEmail" value="" placeholder="Enter recipient email" required>
      </div>
	  <div class="mb-3">
        <label for="recipientCCIT" class="form-label mtop10">CC</label>
        <input type="text" class="form-control" id="recipientCCIT" name="recipientCC" value="" placeholder="Enter CC email" >
      </div>
	  <div class="mb-3">
        <label for="recipientBCCEmail" class="form-label mtop10">BCC</label>
        <input type="text" class="form-control" id="recipientBCCIT" name="recipientBCC" value="" placeholder="Enter BCC email" >
      </div>
      <div class="mb-3">
        <label for="emailSubject" class="form-label mtop10">Subject</label>
        <input type="text" class="form-control" id="emailSubjectIT" name="emailSubject" value="" placeholder="Enter email subject" required>
      </div>
      <div class="mb-3">
    
	   <textarea  name="emailBody" id="emailBody" class="form-control editor" required></textarea>
        <div class="checkbox checkbox-primary">
<input type="checkbox" id="toggleSignature" name="toggleSignature" value="1">
<label for="SignatureX">Add Signature</label>
</div>                       
      </div>
	  <div class="mb-3">
	  <div class="tw-text-right">
	  <a name="send" class="ailoader" onclick="get_content();return false;"><img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" /></a>
	 
	  </div>

	  </div>
	  
	  <div class="mb-3">
        <label for="recipientEmail" class="form-label">Attach Files:</label>
        <input type="file" name="attachments[]"  class="form-control" multiple>
      </div>
      <button type="submit" name="send" class="btn btn-primary mtop20 submitemailxxx">Send Email</button>
    </form>
    <div id="resultMessage" class="mt-4"></div>
  </div>
</div>
<div class="collapse" id="forward-box">
  <div class="card card-body">
  
  
  
    <form action="<?=  admin_url('webmail/Reply') ?>" method="post" enctype="multipart/form-data">
	<!-- CSRF Token -->
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" 
               value="<?= $this->security->get_csrf_hash(); ?>">
	<input type="hidden" name="redirect" value="inbox.php">
	<input type="hidden" name="messageid" id="messageidFW" value="">
	<input type="hidden" name="messagetype" id="messagetypeFW" value="Forward">
      <div class="mb-3">
        <label for="recipientEmailFW" class="form-label">Recipient Email</label>
        <input type="text" class="form-control" id="recipientEmailFW" name="recipientEmail" value="" placeholder="Enter recipient email" required>
      </div>
	  <div class="mb-3">
        <label for="recipientCCFW" class="form-label mtop10">CC</label>
        <input type="text" class="form-control" id="recipientCCFW" name="recipientCC" value="" placeholder="Enter CC email" >
      </div>
	  <div class="mb-3">
        <label for="recipientBCCFW" class="form-label mtop10">BCC</label>
        <input type="text" class="form-control" id="recipientBCCFW" name="recipientBCC" value="" placeholder="Enter BCC email" >
      </div>
      <div class="mb-3">
        <label for="emailSubjectFW" class="form-label mtop10">Subject</label>
        <input type="text" class="form-control" id="emailSubjectFW" name="emailSubject" value="" placeholder="Enter email subject" required>
      </div>
      <div class="mb-3">
    
	   <textarea  name="emailBody" id="emailBodyFW" class="form-control editor" required></textarea>
        <div class="checkbox checkbox-primary">
<input type="checkbox" id="toggleSignatureFW" name="toggleSignature" value="1">
<label for="SignatureXFW">Add Signature</label>
</div>                       
      </div>
	  <div class="mb-3">
	  <div class="tw-text-right">
	  <a name="send" class="ailoader" onclick="get_content_forward();return false;"><img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" /></a>
	 
	  </div>

	  </div>
	  
	  <div class="mb-3">
        <label for="recipientEmail" class="form-label">Attach Files:</label>
        <input type="file" name="attachments[]"  class="form-control" multiple>
      </div>
      <button type="submit" name="send" class="btn btn-primary mtop20 submitemailforward">Send Email</button>
    </form>
    <div id="resultMessage" class="mt-4"></div>
  </div>
</div>
  </div>
      </div>
      <!-- Modal footer -->
      <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                
            </div>
    </div>
  </div>
</div>

<div class="modal fade" id="emailContactModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width:500px;min-width:400px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Add to Contacts</h4>
      </div>
      <div class="modal-body">
        <form id="email-contact-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" name="email_id" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" class="form-control">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="phonenumber" class="form-control">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-email-contact">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="scrubEmailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width:360px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">Scrub – All Folder</h4>
      </div>
      <div class="modal-body">
        <p id="scrub-email-message" class="no-margin"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning" id="scrub-archive">Archive All</button>
        <button type="button" class="btn btn-danger" id="scrub-delete">Delete All</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" style="max-width:420px;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title">New Appointment</h4>
      </div>
      <div class="modal-body">
        <form id="appointment-form">
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
          <div class="form-group">
            <label>Consultations</label>
            <select name="consultations" class="form-control" required>
              <option value="15">15 Minute</option>
              <option value="30">30 Minute</option>
              <option value="45">45 Minute</option>
              <option value="60">60 Minute</option>
            </select>
          </div>
          <div class="form-group">
            <label>Date and Time</label>
            <input type="datetime-local" name="date_time" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Host</label>
            <input type="text" name="consultant" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Participant</label>
            <input type="text" name="customer" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="3"></textarea>
          </div>
          <div class="checkbox checkbox-primary">
            <input type="checkbox" id="appointment-notify" name="notification" value="1">
            <label for="appointment-notify">Send notifications</label>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="save-appointment">Save</button>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/editor/css/jquery-te.css'); ?>"/>

<script src="<?php echo base_url('assets/editor/js/jquery-te-1.4.0.min.js'); ?>"></script>

<script>

	$('.editor').jqte();
	$('#emailBodyFW').jqte();

</script>
<script>


  
 // Toggle AI BOX	
 function toggleCollapse() {
 const div = document.getElementById('collapseDiv');
    div.classList.toggle('hidden');
  }
  
 $('.mail-loader').click(function(){  
 $("#mail-loader").html("<i class='fa-solid fa-spinner fa-spin-pulse mleft20 mtop5 text-info'></i>");
 });
  // Toggle AI BOX	
$('.submitemailxxx').click(function(){ 
	var recipientEmailIT=$.trim($('#recipientEmailIT').val());
	var emailSubjectIT=$.trim($('#emailSubjectIT').val());
	var emailBody=$.trim($('#emailBody').val());
        
		
		 if(recipientEmailIT==''){
			alert('Please enter to email');
			$('#recipientEmailIT').focus();
			return false;
		}else if(emailSubjectIT==''){
		    alert('Please enter email subject');
			$('#emailSubjectIT').focus();
			return false;
		}else if(emailBody=='' || emailBody.length < 6 ){
		    alert('Please check Email body before submit / Min content length 5 character');
			$('.jqte_editor').focus();
			return false;
		}else{
		$(".submitemailxxx").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
		}


});

$('.submitemailforward').click(function(e){ 
	var recipientEmailFW=$.trim($('#recipientEmailFW').val());
	var emailSubjectFW=$.trim($('#emailSubjectFW').val());
	var emailBodyFW='';
	var isEmpty = true;
	
	// Get email body from jqte editor - try multiple methods
	if($('#emailBodyFW').length) {
		// First, try to sync content from editor to textarea
		var $jqteEditor = $('#emailBodyFW').siblings('.jqte_editor');
		if($jqteEditor.length) {
			var $contentEditable = $jqteEditor.find('[contenteditable="true"]');
			if($contentEditable.length) {
				// Sync content from contenteditable to textarea
				var editorContent = $contentEditable.html() || $contentEditable.text() || '';
				$('#emailBodyFW').val(editorContent);
			}
		}
		
		// Method 1: Try jqteVal() function
		if(typeof $('#emailBodyFW').jqteVal === 'function') {
			emailBodyFW = $('#emailBodyFW').jqteVal() || '';
		}
		
		// Method 2: Try getting from contenteditable div directly
		if((!emailBodyFW || emailBodyFW.trim() === '') && $jqteEditor.length) {
			var $contentEditable = $jqteEditor.find('[contenteditable="true"]');
			if($contentEditable.length) {
				emailBodyFW = $contentEditable.html() || $contentEditable.text() || '';
			}
		}
		
		// Method 3: Fallback to textarea value
		if(!emailBodyFW || emailBodyFW.trim() === '') {
			emailBodyFW = $('#emailBodyFW').val() || '';
		}
		
		// Strip HTML tags and check actual content
		if(emailBodyFW) {
			var tempDiv = document.createElement('div');
			tempDiv.innerHTML = emailBodyFW;
			var textContent = (tempDiv.textContent || tempDiv.innerText || '').trim();
			textContent = textContent.replace(/&nbsp;/g, ' ').replace(/\s+/g, ' ').trim();
			isEmpty = !textContent || textContent === '' || textContent.length < 6;
		}
	}
        
		
		 if(recipientEmailFW==''){
			alert('Please enter to email');
			$('#recipientEmailFW').focus();
			return false;
		}else if(emailSubjectFW==''){
		    alert('Please enter email subject');
			$('#emailSubjectFW').focus();
			return false;
		}else if(isEmpty){
		    alert('Please check Email body before submit / Min content length 5 character');
			// Focus on the editor
			var $jqteEditor = $('#emailBodyFW').siblings('.jqte_editor');
			if($jqteEditor.length) {
				var $contentEditable = $jqteEditor.find('[contenteditable="true"]');
				if($contentEditable.length) {
					$contentEditable[0].focus();
				} else {
					$('#emailBodyFW').focus();
				}
			} else {
				$('#emailBodyFW').focus();
			}
			return false;
		}else{
		$(".submitemailforward").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
		}


});


function get_content() { 

//let str = $('input[name="aicontent"]').val();
let str = $('#emailBody').val();
let aicontent = $.trim(str);


if((aicontent !="") && (aicontent.length >= 5)){
//alert(emailSubject);
$(".ailoader").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
     $.post(admin_url + 'ai_content_generator/generate_email_ai', {
            content_title: aicontent,
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response);
			
			if(response.alert_type=="success"){
			var str = response.message.toString();
            var formattedStr = str.replace(/\\n/g, "<br>");
            var formattedStr = formattedStr.replace(/\\/g, "");
            //alert(formattedStr);
			$('#emailBody').jqteVal(formattedStr);
			$('#emailBody').val(formattedStr);
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			// Usage example: Set cursor after 1 second
            setTimeout(setCursorToEnd, 2000);
			//alert("Please edit the content before send");
			
			}else{
			alert("Not Generated");
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}
			
            
        });
}else{
alert("Enter Correct Email Body with min length 5");
}

   
}

function get_content_forward() { 

//let str = $('input[name="aicontent"]').val();
let str = $('#emailBodyFW').val();
let aicontent = $.trim(str);


if((aicontent !="") && (aicontent.length >= 5)){
//alert(emailSubject);
$(".ailoader").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
     $.post(admin_url + 'ai_content_generator/generate_email_ai', {
            content_title: aicontent,
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response);
			
			if(response.alert_type=="success"){
			var str = response.message.toString();
            var formattedStr = str.replace(/\\n/g, "<br>");
            var formattedStr = formattedStr.replace(/\\/g, "");
            //alert(formattedStr);
			$('#emailBodyFW').jqteVal(formattedStr);
			$('#emailBodyFW').val(formattedStr);
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			// Usage example: Set cursor after 1 second
            setTimeout(setCursorToEndForward, 2000);
			//alert("Please edit the content before send");
			
			}else{
			alert("Not Generated");
			$(".ailoader").html('<img src="<?php echo base_url('assets/images/artificial-intelligence.png')?>" title="Draft with AI"  style="width:30px;" />');
			
			}
			
            
        });
}else{
alert("Enter Correct Email Body with min length 5");
}

   
}

function setCursorToEnd() {
  var iframe = $('.jqte_editor')[0]; // Get the editable div (jqte_editor)
  var range = document.createRange();
  var selection = window.getSelection();

  range.selectNodeContents(iframe);
  range.collapse(false); // false = to end of the content
  selection.removeAllRanges();
  selection.addRange(range);
}

function setCursorToEndForward() {
  var iframe = $('#emailBodyFW').siblings('.jqte_editor').find('[contenteditable="true"]')[0]; // Get the editable div for forward editor
  if(iframe) {
    var range = document.createRange();
    var selection = window.getSelection();

    range.selectNodeContents(iframe);
    range.collapse(false); // false = to end of the content
    selection.removeAllRanges();
    selection.addRange(range);
  }
}
  </script>
<script>
$('.isread').click(function(){ 
         var mid=$(this).attr('data-mid');
		 var fid=$(this).attr('data-fid');
		 var resultid='.isread'+mid;
		//return;
		 $.post(admin_url + 'webmail/make_isread', {
            mid: mid,
			fid: fid
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			 //alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('isread');
			}else{
			 //alert_float(response.alert_type, response.message);
			 
			}
            
        });
		 
});
  
  function buildEmailLinks(list, scrubbed) {
     
	 var displaylist="tw-hidden";
	 if(scrubbed){
	 var displaylist="vikash";
	 }
    if (!list) { return '<span>-</span>'; }
    var items = list.split(/[;,]/).map(function(item){ return item.trim(); }).filter(Boolean);
    if (!items.length) { return '<span>-</span>'; }
    var html = '';
    for (var i = 0; i < items.length; i++) {
      var email = items[i];
      html += '<span class="dropdown email-action-dropdown">' +
        ' <i class="fa-regular fa-paper-plane"></i> <a href="#" class="dropdown-toggle email-action-toggle" data-toggle="dropdown" data-email="' + escapeHtml(email) + '">' +
          escapeHtml(email) +
        '</a>' +
        '<ul class="dropdown-menu mail-bg">' +
          '<li class="email-action-header" style="padding:6px 12px;">' +
            '<span>' + escapeHtml(email) + '</span> ' +
            '<a href="javascript:void(0)" class="copy-email pull-right" data-email="' + escapeHtml(email) + '"><i class="fa-solid fa-copy"></i></a>' +
          '</li>' +
          '<li class="divider"></li>' +
          '<li><a href="javascript:void(0)" class="email-action-item" data-action="add_contact" data-email="' + escapeHtml(email) + '">Add to contacts</a></li>' +
          '<li><a href="javascript:void(0)" class="email-action-item" data-action="send_email" data-email="' + escapeHtml(email) + '">Send email</a></li>' +
          '<li><a href="javascript:void(0)" class="email-action-item" data-action="view_history" data-email="' + escapeHtml(email) + '">View email history from the contact</a></li>' +
          '<li><a href="javascript:void(0)" class="email-action-item" data-action="view_attachments" data-email="' + escapeHtml(email) + '">View attachments from the contact</a></li>' +
          '<li class="' + displaylist + '"><a href="javascript:void(0)" class="email-action-item" data-action="scrub_email" data-email="' + escapeHtml(email) + '">Scrub email from the contact</a></li>' +
          '<li><a href="javascript:void(0)" class="email-action-item" data-action="book_appointment" data-email="' + escapeHtml(email) + '">Book an appointment</a></li>' +
        '</ul>' +
      '</span>';
      if (i < items.length - 1) { html += ' '; }
    }
    return html;
  }

  $('.hrefmodal').click(function(){ 

         //alert(11111);
         var tid=$(this).attr('data-tid');
		 var mailto=$(this).attr('mailto');
		 var mailtox=$(this).attr('mailtox');
		 var mailcc=$(this).attr('mailcc');
		 var mailbcc=$(this).attr('mailbcc');
		 var messageid=$(this).attr('messageid');
		 var did=$(this).attr('data-id');
		 var ddate=$(this).attr('data-date');
		 // Get email body from data attribute (browser automatically decodes HTML entities)
		 var emailBody=$(this).attr('data-body') || '';
		 var attachments=$(this).attr('data-attachments') || '';
		 const formattedDate = moment(ddate).format('ddd, DD MMM YYYY h:mm:ss A Z');
		 //alert(tid);alert(mailto);alert(formattedDate);
		 
		 // Hide both forms initially
		 $('#reply-box').hide();
		 $('#forward-box').hide();
		 
		 $('#myModal12').modal('show');
		  $('#myModal12 .modal-dialog').css({"max-width":"80%", "margin-top": "20px"});
		 //$('#myModal12').modal('show').find('.modal-body').load(urls);
      $('#myModal12 .modal-title').html(
        '<span class="h4"><b>' + tid + '</b></span><br>' +
        '<span class="h6 text-primary">' +
          ' From : ' + buildEmailLinks(mailto,'scrubbed') + '<br>' +
          ' To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ' + buildEmailLinks(mailtox,'') + '<br>' +
          ' CC&nbsp;&nbsp;&nbsp;&nbsp;: ' + buildEmailLinks(mailcc,'') + '<br>' +
          /*' BCC : ' + buildEmailLinks(mailbcc) + '<br>' +*/
          formattedDate +
        '</span>'
      );
     $('#myModal12 .email-action-item').off('click').on('click', function(e) {
        e.preventDefault();
        var action = $(this).data('action');
        var email = $(this).data('email');
        // Hook for future actions
        if (action === 'send_email') {
          window.location.href = '<?php echo admin_url('webmail/compose'); ?>?id=' + encodeURIComponent(email);
          return;
        }
        if (action === 'view_history') {
          window.location.href = '<?php echo admin_url('webmail/inbox'); ?>?stype=from_email&skey=' + encodeURIComponent(email);
          return;
        }
        if (action === 'view_attachments') {
          window.location.href = '<?php echo admin_url('webmail/inbox'); ?>?stype=from_email&mode=attached&skey=' + encodeURIComponent(email);
          return;
        }
        if (action === 'add_contact') {
          $('#email-contact-form')[0].reset();
          $('#email-contact-form [name="email_id"]').val(email);
          $('#emailContactModal').modal('show');
          return;
        }
        if (action === 'scrub_email') {
          $('#scrub-email-message').text('Email in all folder, from ' + email + ' will be affected');
          $('#scrubEmailModal').data('email', email).modal('show');
          return;
        }
        if (action === 'book_appointment') {
          var consultant = '<?php echo e($_SESSION['webmail']['mailer_email'] ?? ''); ?>';
          $('#appointment-form')[0].reset();
          $('#appointment-form [name="consultant"]').val(consultant);
          $('#appointment-form [name="customer"]').val(email);
          $('#appointmentModal').modal('show');
          return;
        }
        // Default: no-op for now
      });
		// $('#emailSubject').val(tid);
		 
		 // Set values for Reply form
		 $('#emailSubjectIT').val(tid);
		 $('#messagetypeIT').val('Reply');
		 
		 if($(this).attr('data-folder')=="Sent"){
		 $('#recipientEmailIT').val(mailtox);
		 }else{
		 $('#recipientEmailIT').val(mailto);
		 }
		 $('#recipientCCIT').val(mailcc);
		 $('#recipientBCCIT').val(mailbcc);
		 $('#messageidIT').val(messageid);
		 
		 // Set values for Forward form
		 var forwardSubject = 'Fwd: ' + tid;
		 $('#emailSubjectFW').val(forwardSubject);
		 $('#messagetypeFW').val('Forward');
		 $('#recipientEmailFW').val('');
		 $('#recipientCCFW').val('');
		 $('#recipientBCCFW').val('');
		 $('#messageidFW').val(messageid);
		 
		 var contents=$('#'+did).html();
		 $('#messageDisplay').html(contents);
		 
		 // Build attachment links if attachments exist
		 var attachmentHtml = '';
		 if(attachments && attachments.trim() !== '') {
			 var attachmentArray = attachments.split(',');
			 attachmentArray.forEach(function(attach) {
				 if(attach && attach.trim() !== '') {
					 var filePath = '<?php echo site_url(); ?>/' + attach.trim();
					 attachmentHtml += '<i class="fa-solid fa-paperclip"></i> <a href="' + filePath + '" target="_blank" title="Click to view">' + filePath + '</a><br>';
				 }
			 });
		 }
		 
		 // Store original message content for forward
		 window.originalMessageContent = contents;
		 
		 // Set forward body with full original message content (not just iframe HTML)
		 var forwardBody = '<br><br>---------- Forwarded message ----------<br>From: ' + escapeHtml(mailto) + '<br>Date: ' + formattedDate + '<br>Subject: ' + escapeHtml(tid) + '<br>To: ' + escapeHtml(mailtox);
		 if(mailcc && mailcc.trim() !== '') {
			 forwardBody += '<br>CC: ' + escapeHtml(mailcc);
		 }
		 if(mailbcc && mailbcc.trim() !== '') {
			 forwardBody += '<br>BCC: ' + escapeHtml(mailbcc);
		 }
		 forwardBody += '<br><br>' + emailBody;
		 if(attachmentHtml !== '') {
			 forwardBody += '<br><br>Attachments:<br>' + attachmentHtml;
		 }
		 
		 // Initialize forward editor body after a short delay to ensure editor is ready
		 // This will be set when forward button is clicked
		 window.forwardBodyContent = forwardBody;

$('#emailBody_ifr').contents().find('#tinymce').html(content);
		 

	});
	
	$( "#reply-button" ).click(function() {
    $( "#forward-box" ).hide();
    $( "#reply-box" ).toggle();
});

	$( "#forward-button" ).click(function() {
    $( "#reply-box" ).hide();
    $( "#forward-box" ).show();
    
    // Initialize editor when forward box is shown
    setTimeout(function() {
        // Check if editor is already initialized
        if(!$('#emailBodyFW').siblings('.jqte_editor').length) {
            $('#emailBodyFW').jqte();
        }
        
        // Set forward body content if available
        if(window.forwardBodyContent) {
            setTimeout(function() {
                if($('#emailBodyFW').length) {
                    if(typeof $('#emailBodyFW').jqteVal === 'function') {
                        $('#emailBodyFW').jqteVal(window.forwardBodyContent);
                    } else {
                        $('#emailBodyFW').val(window.forwardBodyContent);
                    }
                }
            }, 200);
        }
    }, 50);
});
  </script>
<script>
  $('body').on('click', '.copy-email', function(e){
    e.preventDefault();
    var email = $(this).data('email') || '';
    if (!email) {
      return;
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(email).then(function(){
        alert_float('success', 'Copied: ' + email);
      }).catch(function(){
        alert('Copied: ' + email);
      });
      return;
    }
    var temp = $('<input>');
    $('body').append(temp);
    temp.val(email).select();
    document.execCommand('copy');
    temp.remove();
    alert_float('success', 'Copied: ' + email);
  });

  $('#save-email-contact').on('click', function() {
    var $form = $('#email-contact-form');
    $.post('<?php echo admin_url('webmail/add_contact'); ?>', $form.serialize(), function(resp) {
      if (resp && resp.success) {
        $('#emailContactModal').modal('hide');
        if (resp.message) {
          alert_float('success', resp.message);
        }
      } else {
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to save contact');
      }
    }, 'json');
  });

  $('#scrub-archive').on('click', function() {
    var email = $('#scrubEmailModal').data('email');
    if (!email) { return; }
    if (!confirm('Archive all emails from this contact?')) { return; }
    $.post('<?php echo admin_url('webmail/scrub_email'); ?>', {
      email: email,
      action: 'archive',
      <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    }, function(resp) {
      if (resp && resp.success) {
        $('#scrubEmailModal').modal('hide');
        alert_float('success', resp.message || 'Archived');
        location.reload();
      } else {
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to archive');
      }
    }, 'json');
  });

  $('#scrub-delete').on('click', function() {
    var email = $('#scrubEmailModal').data('email');
    if (!email) { return; }
    if (!confirm('Delete all emails from this contact?')) { return; }
    $.post('<?php echo admin_url('webmail/scrub_email'); ?>', {
      email: email,
      action: 'delete',
      <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
    }, function(resp) {
      if (resp && resp.success) {
        $('#scrubEmailModal').modal('hide');
        alert_float('success', resp.message || 'Deleted');
        location.reload();
      } else {
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to delete');
      }
    }, 'json');
  });

  $('#save-appointment').on('click', function() {
  $("#save-appointment").html("<i class='fa-solid fa-spinner fa-spin-pulse'></i>");
    var $form = $('#appointment-form');
    $.post('<?php echo admin_url('webmail/add_appointment'); ?>', $form.serialize(), function(resp) {
      if (resp && resp.success) {
        $('#appointmentModal').modal('hide');
		$("#save-appointment").html("Save");
        alert_float('success', resp.message || 'Appointment saved');
      } else {
	    $("#save-appointment").html("Save");
        alert_float('warning', resp && resp.message ? resp.message : 'Failed to save appointment');
      }
    }, 'json');
  });
</script>
  
<script>
$(function() {
    //initDataTable('.table-custom-fields', window.location.href);
});
</script>
<script>
function adjustIframeHeight(iframe) {
    setTimeout(() => {
        iframe.style.height = (iframe.contentWindow.document.body.scrollHeight + 20) + "px";
    }, 100);
}
$(document).ready(function() {
  setTimeout(function() {
    $('#lead-modal').removeAttr('id');
    console.log('ID removed from modal');
  }, 5000); // 5000 ms = 5 seconds
});
</script>

<script> 
 
$('.isflag').click(function(){ 
         //alert(11111);
         var mid=$(this).attr('data-mid');
		 var fid=$(this).attr('data-fid');
		 var resultid='.isflag'+mid;
		 var folderx="<?php echo $_SESSION['webmail']['folder'];?>";
		 //alert(mid);
		 //alert(fid);
		 
		 if(fid==0){
		 $(this).attr('data-fid',1);
		 $(this).removeClass('tw-text-info-800').addClass('tw-text-info-300');
		 }else{
		 $(this).attr('data-fid',0);
		 $(this).removeClass('tw-text-info-300').addClass('tw-text-info-800');
		 }
		 
		 $.post(admin_url + 'webmail/make_isflag', {
            mid: mid,
			fid: fid
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			
			if(fid==1){
			 alert_float(response.alert_type, 'Flagged');
			 }else{
			
			 if(folderx=='Flagged'){ //alert('Redirect');
			 location.reload(true);
			 }
			 
			 alert_float(response.alert_type, response.message);
			 }
			 //$(resultid).removeClass('tw-text-info-300').addClass('tw-text-info-800');
			}else{
			 alert_float(response.alert_type, response.message);
			 //$(resultid).removeClass('tw-text-info-800').addClass('tw-text-info-300');
			}
            
        });
		 
});

$('.isdelete').click(function(){ 
         //alert(11111);
         var mid=$(this).attr('data-mid');
		 var fid=$(this).attr('data-fid');
		 var resultid='.isdelete'+mid;
		 var tableid='.table'+mid;
		 if(fid==0){ var msgx="restore";}else if(fid==1){var msgx="Delete";}else if(fid==2){var msgx="Move to inbox";}else{var msgx="Permanent Delete";}
		 if (!confirm('Are you sure you want to ' + msgx + ' this email?')) {
		 return false;
		 }
		 //alert(resultid);
		// return;
		 $.post(admin_url + 'webmail/make_isdelete', {
            mid: mid,
			fid: fid
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
			if(response.alert_type=="success"){
			 alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('tw-text-warning-100').addClass('tw-text-warning-500'); 
			 $(tableid).hide();
			}else{
			 alert_float(response.alert_type, response.message);
			 $(resultid).removeClass('tw-text-warning-500').addClass('tw-text-warning-100');
			}
            
        });
		 
});

$('.refreshemail').click(function(){ 
		 $("#refresh-loader").addClass('fa-spin-pulse');
		 $.post(admin_url + 'webmail/refresh_email', {
        })
        .done(function(response) { 
            response = JSON.parse(response);
			//alert(response.alert_type);
			//alert(response.message);
			
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
<script>
  //For Add /  Remove Signature
  //toggleSignature function define on asset/js/custom.js
  //need add css editor in jq editor textarea
  const signature = `<br><br><br><br><?php echo $email_signature;?>`;
</script>
<script>
  (function suppressBeforeUnloadOnInbox(){
    function clearBeforeUnload() {
      $(window).off('beforeunload');
      $(window).off('beforeunload.areYouSure');
      window.onbeforeunload = null;
    }
    $(function(){
      clearBeforeUnload();
      // Capture phase stopper to block late handlers
      window.addEventListener('beforeunload', function(e){
        e.stopImmediatePropagation();
      }, true);
      // Extra clears in case handlers are attached after ready
      setTimeout(clearBeforeUnload, 100);
      setTimeout(clearBeforeUnload, 500);
      setTimeout(clearBeforeUnload, 1500);
    });
  })();
</script>
</body>

</html>