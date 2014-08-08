<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_interface_controller extends CI_Controller
{

//    must set cookie to remember the username and password in the login field of the frontpage

    function __Construct()
    {
        parent::__construct();
    }


    # logout
    function logout()
    {
        $this->account->logout();
        redirect('/');
    }


    # manage account
    function manage()
    {
        # Load the view
        $this->framework->renderComponent('account', 'account_manage_view');
    }
}