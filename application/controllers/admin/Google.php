<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Google extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'vendor/autoload.php';
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

		public function connect()
		{
		
	  if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	 
			$client = new Google_Client();
			$client->setClientId(GOOGLE_CLIENT_ID);//YOUR_CLIENT_ID
			$client->setClientSecret(GOOGLE_CLIENT_SECRET);//YOUR_CLIENT_SECRET
			$client->setRedirectUri(GOOGLE_REDIRECT_URI);
		
			$client->addScope(Google_Service_Drive::DRIVE);
			$client->setAccessType('offline');
			$client->setPrompt('consent');
		
			$authUrl = $client->createAuthUrl();
			redirect($authUrl);
		}
		
    public function callback()
    {
	
	 if(!$_SESSION['GOOGLE_CLIENT_ID']){ 
	 set_alert('warning', 'GOOGLE CLIENT ID NOT CONFIGURED');
     redirect(admin_url('drive/'));
	 }
	 
	 
    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);//YOUR_CLIENT_ID
    $client->setClientSecret(GOOGLE_CLIENT_SECRET); //YOUR_CLIENT_SECRET
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);

    if ($this->input->get('code')) {

        $token = $client->fetchAccessTokenWithAuthCode(
            $this->input->get('code')
        );

        $staff_id = get_staff_user_id();

        $this->db->where('staff_id', $staff_id)
                 ->delete('staff_google_tokens');

        $this->db->insert('staff_google_tokens', [
            'staff_id'      => $staff_id,
            'access_token'  => json_encode($token),
            'refresh_token' => $token['refresh_token'] ?? null,
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        redirect(admin_url('drive'));
    }
}

}