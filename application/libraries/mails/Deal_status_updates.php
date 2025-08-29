<?php

defined('BASEPATH') or exit('No direct script access allowed');

class deal_status_updates extends App_mail_template
{
    protected $for = 'leads';

    protected $staff_email;
	
	public $project_id;
	
	public $project_details;

    protected $original_password;

    protected $staffid;

    public $slug = 'deal_status_updates';

    public $rel_type = 'leads';

    public function __construct($staff_email, $staffid, $project_id, $project_details, $mail_subject,$cc="")
    {

	

$_SESSION['templatesub']="CRM Deals Updates";
if(isset($mail_subject)&&$mail_subject){
$_SESSION['templatesub']=" ".$mail_subject;
}


//echo $_SESSION['templatesub'];exit;
if(isset($cc)&&$cc){
}else{
$cc="vikash4eindia@gmail.com";
}

        parent::__construct();
        $this->staff_email       = $staff_email;
        $this->staffid           = $staffid;
		$this->project_id        = $project_id;
        $this->project_details   = $project_details;
		$this->cc                = $cc;
    }

    public function build()
    {
        $this->to($this->staff_email)
		->set_rel_id($this->project_id)
        ->set_merge_fields('staff_merge_fields', $this->staffid, $this->project_details, $this->project_id );
    }
}
