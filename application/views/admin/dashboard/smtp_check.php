<?php 
use app\services\imap\Imap;
use app\services\imap\ConnectionErrorException;
use Ddeboer\Imap\Exception\MailboxDoesNotExistException;

defined('BASEPATH') or exit('No direct script access allowed'); 

?>
<?php init_head(); 
$this->db->select('id, mailer_email, mailer_username, mailer_password, mailer_imap_host, encryption, staffid');
$this->db->where('company_id', get_staff_company_id());
$this->db->where('mailer_status', 1);
$webmaillist=$this->db->get(db_prefix().'webmail_setup')->result_array();

 ?>
<div id="wrapper">
  <div class="content">
    <div class="row mb-2">
      <div class="panel_s">
        <div class="panel-body panel-table-full">
		
          
		  <div class="row ">


<h4>SMTP Details</h4>
<table border="1" cellpadding="8" cellspacing="0" width="100%" class="table table-clients number-index-2 dataTable no-footer">
  <thead>
    <tr>
      <th>Email ID</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($webmaillist)) { ?>
    <?php foreach ($webmaillist as $row) { 
	app_check_imap_open_function();
    $username=$row['mailer_username'] ? $row['mailer_username'] : $row['email'];
	$password=$row['mailer_password'];
	$host=$row['mailer_imap_host'];
	$staffid=$row['staffid'];
	$encryption=$row['encryption'];
        $imap = new Imap(
           $username,
           $password,
           $host,
           $encryption
        );
		$status="";
		try {
            $connection = $imap->testConnection();

            try {
                $folder = $this->input->post('folder');
                $connection->getMailbox(empty($folder) ? 'INBOX' : $folder);
            } catch (MailboxDoesNotExistException $e) {
                $status="Error : ".$e->getMessage();
            }
            $status="Success";
        } catch (ConnectionErrorException $e) {
            $status="Error : ".$e->getMessage();
        }
	
	if($status != 'Success' && isset($staffid)&&$staffid){
	            $notification_data = [
                    'description'     => 'smtp_details_wrong',
                    'touserid'        => $staffid,
                    'link'            => 'webmail_setup'
                ];
                if (add_notification($notification_data)) {
                    pusher_trigger_notification($staffid);
                }

	log_activity($username.' SMTP Details Not Working -  [ ' . $status . ']' , $staffid);
	}
	
	
	?>
        <tr>
          <td><?php echo $staffid; ?> - <?php echo $row['mailer_email']; ?></td>
          <td><strong><?php echo $status;?></strong></td>
        </tr>
     <?php } ?>
    <?php } ?>
  </tbody>
</table>


          
        </div>
      </div>
    </div>
  </div>
</div>
</body>
<?php init_tail(); ?>
</html>