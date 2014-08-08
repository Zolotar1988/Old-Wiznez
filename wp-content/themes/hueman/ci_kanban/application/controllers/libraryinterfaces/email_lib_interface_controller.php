<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_lib_interface_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
    }


    function verifiyEmail()
    {
        $key            = $this->framework->uriSegment3;
        $validatedUser  = $this->email->verification_validate($key);

        if($validatedUser)
        {
            $this->mailchimp->subscribe($validatedUser);
            $this->framework->setNotificationCookie('Your email has been verified', 'info');
            redirect('/');
        }
    }


}