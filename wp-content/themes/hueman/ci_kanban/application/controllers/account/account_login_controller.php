<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_login_controller extends CI_Controller
{

//    must set cookie to remember the username and password in the login field of the frontpage

    function __Construct()
    {
        parent::__construct();
        $this->load->model('account/account_login_model');
    }


    function login()
    {
        # Build the parameters
        $parameters               = array();
        $parameters['user_ip']    = 'xxx';
        $parameters['username']   = $this->input->post('username');
        $parameters['password']   = md5($this->input->post('password'));

        # Try to login - response is JSON
        $response = $this->account_login_model->login($parameters);

        # As the cookie is set on the model, we can just redirect the user to the frontpage
        echo $response;
    }
}