<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Activate_account extends App_mail_template
{
    protected $for = 'staff';

    protected $staff_email;

    protected $staffid;

    protected $activation_link;

    public $slug = 'activate-account';

    public $rel_type = 'staff';

    public function __construct($staff_email, $staffid, $activation_link)
    {
        parent::__construct();
        $this->staff_email     = $staff_email;
        $this->staffid         = $staffid;
        $this->activation_link = $activation_link;
    }

    public function build()
    {
        $this->to($this->staff_email)
            ->set_rel_id($this->staffid)
            ->set_merge_fields('staff', [
                'staff_firstname' => get_staff_field($this->staffid, 'firstname'),
                'staff_lastname'  => get_staff_field($this->staffid, 'lastname'),
                'staff_email'    => $this->staff_email,
                'staffid'        => $this->staffid,
            ])
            ->set_merge_fields('activation', [
                'activation_link' => $this->activation_link,
            ]);
    }
}
