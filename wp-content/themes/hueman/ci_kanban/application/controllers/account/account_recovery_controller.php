<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_recovery_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
        $this->load->model('account/account_recovery_model');
    }


    function password_sendNew()
    {
        # Variables
        $email   = $this->input->post('email');
        $userId  = $this->user->idFromUsername($email);

        # Register the account
        $response = $this->account_recovery_model->password_sendNew($userId);

        # Echo
        echo 'A new password has been sent to your email';
    }
}