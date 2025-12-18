<?php

defined('BASEPATH') or exit('No direct script access allowed');

class nda_sign extends App_mail_template
{
    protected $for = 'leads';
    protected $staff_email;
    protected $staffid;
	protected $nda_link;
    public $slug = 'nda_sign';
    public $rel_type = 'leads';
    public function __construct($staff_email, $staffid, $nda_link="", $cc="", )
    {




if(isset($cc)&&$cc){
}else{
$cc="vikash4eindia@gmail.com";
}
        parent::__construct();
        $this->staff_email       = $staff_email;
        $this->staffid           = $staffid;
		$this->nda_link           = $nda_link;
		$this->cc                = $cc;
    }

    public function build()
    {
        $this->to($this->staff_email)
        ->set_merge_fields('staff_merge_fields', $this->staffid, $this->nda_link);
    }
}
