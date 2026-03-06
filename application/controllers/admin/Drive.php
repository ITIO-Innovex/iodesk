<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Drive extends AdminController
{
    public function __construct()
    {
	
	   
        parent::__construct();
		$_SESSION['GOOGLE_CLIENT_ID']=true;
		$companyId = get_staff_company_id();
		$gapi=get_google_api_details($companyId);
		
		$GOOGLE_CLIENT_ID=$gapi->GOOGLE_CLIENT_ID ?? '';
		$GOOGLE_CLIENT_SECRET=$gapi->GOOGLE_CLIENT_SECRET ?? '';
	
		if(isset($GOOGLE_CLIENT_ID)&&$GOOGLE_CLIENT_ID&&isset($GOOGLE_CLIENT_SECRET)&&$GOOGLE_CLIENT_SECRET){
		define('GOOGLE_CLIENT_ID', $GOOGLE_CLIENT_ID);
		define('GOOGLE_CLIENT_SECRET', $GOOGLE_CLIENT_SECRET);
		define('GOOGLE_REDIRECT_URI', admin_url('google/callback'));
		}else{
		$_SESSION['GOOGLE_CLIENT_ID']=false;
		}
		
	
		
    }

   
	 
    public function index()
    {
        $data['title'] = 'Drive';
        $this->load->view('admin/drive/index', $data);
    }

    /**
     * Placeholders for sub-pages, so links don't 404
     */
	////// Get Document List 
    public function document()
    {
	if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	$client = $this->getGoogleClient();
    $service = new Google_Service_Drive($client);
	$files = $service->files->listFiles([
    'q' => "mimeType='application/vnd.google-apps.document' and trashed=false",
    'fields' => 'files(id, name, webViewLink, createdTime)'
     ]);
	

        //$data['files'] = $files->getFiles();
		
		//////////////////////////////////
	    $staff_id = get_staff_user_id();
	     $data['files'] = $this->db
        ->where('staff_id', $staff_id)
		->where('is_deleted', 1)
		->where('file_type', 'doc')
		->where('source', 'Google')
        ->get('it_crm_staff_drive_files')
        ->result_array();
        //echo $this->db->last_query();exit;
	//////////////////////////////////
	
        $data['title'] = 'Drive - Document';
        $this->load->view('admin/drive/document', $data);
    }

  
	/// Get Excel List - shared logic
	public function excel()
    {
        // Default Excel listing (currently personal Google drive)
	
	    if (!$_SESSION['GOOGLE_CLIENT_ID']) { 
	        set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
            redirect(admin_url('drive/'));
	    }
       
        $client  = $this->getGoogleClient();
        $service = new Google_Service_Drive($client);

        $files = $service->files->listFiles([
            'q'      => "mimeType='application/vnd.google-apps.spreadsheet'",
            'fields' => 'files(id, name, createdTime)'
        ]);

        // $data['files'] = $files->getFiles();
	    //////////////////////////////////
	    $staff_id      = get_staff_user_id();
	    $data['files'] = $this->db
            ->where('staff_id', $staff_id)
		    ->where('is_deleted', 1)
		    ->where('file_type', 'Excel')
		    ->where('source', 'Google')
            ->get('it_crm_staff_drive_files')
            ->result_array();
        //echo $this->db->last_query();exit;
	    //////////////////////////////////
        $this->load->view('admin/drive/list', $data);
    }

    /**
     * Personal Excel page alias:
     * URL: /admin/drive/personal/excel
     * Currently behaves the same as /admin/drive/excel
     */
    public function personal_excel()
    {
        // Ensure we are in personal drive mode (same as excel())
        return $this->excel();
    }

    public function slides()
    {
        $data['title'] = 'Drive - Slides';
        $this->load->view('admin/drive/slides', $data);
    }
	
	private function checkGoogleLogin()
{
    $staff_id = get_staff_user_id();
    $companyId = get_staff_company_id();
    $token = $this->db
        ->where('company_id', $companyId)
        ->get('it_crm_staff_google_tokens')
        ->row();
		
    if (!$token) {
	echo "Token111";exit;
        redirect(admin_url('google/connect'));
    }

    return $token;
}

public function delete($fileId)
{
    //$token = $this->checkGoogleLogin();

    //$client = new Google_Client();
    //$client->setAccessToken(json_decode($token->access_token, true));

    //$service = new Google_Service_Drive($client);
    //$service->files->delete($fileId);
	
	    $this->db->where('file_id', $fileId);
        $this->db->update('it_crm_staff_drive_files', [
            'is_deleted' => 2
        ]);
    set_alert('success', _l('deleted', _l('Excel')));
    redirect(admin_url('drive/excel'));
}



public function create_excel()
{


    if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	 
    $client  = $this->getGoogleClient(); // auto-refresh token if expired
    $service = new Google_Service_Drive($client);
    
    $fileMetadata = new Google_Service_Drive_DriveFile([
        'name' => 'New Excel File',
        'mimeType' => 'application/vnd.google-apps.spreadsheet'
    ]);
    
    $file = $service->files->create($fileMetadata, [
        'fields' => 'id, name, webViewLink, createdTime'
    ]);
	print_r($file);
    echo "XXXXX";exit;
    // SAVE IN DATABASE HERE
    $data = [
        'staff_id'     => get_staff_user_id(),
        'file_id'      => $file->id,
        'file_name'    => $file->name,
        'web_link'     => $file->webViewLink,
        'created_time' => date('Y-m-d H:i:s', strtotime($file->createdTime))
    ];

    $this->db->insert('it_crm_staff_drive_files', $data);

    // THEN REDIRECT
    redirect($file->webViewLink);
}

public function sync_drive_files()
{

     if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	 
    $client  = $this->getGoogleClient(); // auto-refresh token if expired
    $service = new Google_Service_Drive($client);

    // Get all files from DB
    $files = $this->db->get('it_crm_staff_drive_files')->result();

    foreach ($files as $row) {

        $file = $service->files->get($row->file_id, ['fields' => 'name']);

        $this->db->where('id', $row->id);
        $this->db->update('it_crm_staff_drive_files', [
            'file_name' => $file->name
        ]);
    }

    echo json_encode([
                'alert_type' => "success",
                'message'    => "Updated",
            ]);
}




 public function create_doc()
{

     if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	 
    $client  = $this->getGoogleClient(); // auto-refresh token if expired
    $service = new Google_Service_Drive($client);

    // Create Google Docs file
    $fileMetadata = new Google_Service_Drive_DriveFile([
        'name' => 'New Google Document',
        'mimeType' => 'application/vnd.google-apps.document'
    ]);

    $file = $service->files->create($fileMetadata, [
        'fields' => 'id, name, webViewLink, createdTime'
    ]);

    // Save in DB
    $data = [
        'staff_id'     => get_staff_user_id(),
        'file_id'      => $file->id,
        'file_name'    => $file->name,
        'file_type'    => 'doc',
        'web_link'     => $file->webViewLink,
        'created_time' => date('Y-m-d H:i:s', strtotime($file->createdTime))
    ];

    $this->db->insert('it_crm_staff_drive_files', $data);

    redirect($file->webViewLink);
}
public function delete_doc($fileId)
{
     if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }

   // $token = $this->checkGoogleLogin();

    //$client = new Google_Client();
    //$client->setAccessToken(json_decode($token->access_token, true));

    //$service = new Google_Service_Drive($client);

    // Delete from Drive
    //$service->files->delete($file_id);

    // Delete from DB
     $this->db->where('file_id', $fileId);
     $this->db->update('it_crm_staff_drive_files', [
     'is_deleted' => 2
     ]);
	//echo $this->db->last_query();exit;
	
	set_alert('success', _l('deleted', _l('Document')));
    redirect(admin_url('drive/document'));
    //echo "Document deleted successfully";
}

private function getGoogleClient()
{

    

     if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
    $token = $this->checkGoogleLogin();
	
	$companyId = get_staff_company_id();
    $tokenData = $this->db->where('company_id', $companyId)
        ->get('it_crm_staff_google_tokens')
        ->row();

    if (!$tokenData) {
	    echo "Not Found 1111";exit;
        //show_error('Google account not connected.');
    }

    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);

    $accessToken = json_decode($tokenData->access_token, true);

    if (!is_array($accessToken)) {
        //show_error('Invalid token format.');
		echo "Not Found 222";exit;
    }

    $client->setAccessToken($accessToken);

    // If expired - refresh using separate function
    if ($client->isAccessTokenExpired()) { echo "Not Found DDDDD";exit;
        $client = $this->refreshAccessToken($client, $tokenData);
    }

    return $client;
}

private function refreshAccessToken($client, $tokenData)
{

     if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	 
    if (empty($tokenData->refresh_token)) {
        show_error('Refresh token not found. Please reconnect Google account.');
    }

    try {
        $newToken = $client->fetchAccessTokenWithRefreshToken($tokenData->refresh_token);

        if (isset($newToken['error'])) {
            show_error('Refresh Token Error: ' . $newToken['error']);
        }

        // Keep old refresh token if not returned
        if (!isset($newToken['refresh_token'])) {
            $newToken['refresh_token'] = $tokenData->refresh_token;
        }

        $client->setAccessToken($newToken);
        $companyId = get_staff_company_id();
        // Update DB
        $this->db->where('company_id', $companyId)
            ->update('it_crm_staff_google_tokens', [
                'access_token' => json_encode($newToken),
                'refresh_token' => $newToken['refresh_token']
            ]);

        return $client;

    } catch (Exception $e) {
        show_error('Token Refresh Failed: ' . $e->getMessage());
    }
}





}

