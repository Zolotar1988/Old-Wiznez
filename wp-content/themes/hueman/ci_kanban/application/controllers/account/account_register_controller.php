<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_register_controller extends CI_Controller
{

//    on register, pass all the data from the register form to the next section so that i can get stuff like account type, gender, etc
    function __Construct()
    {
        parent::__construct();
        $this->load->model('account/account_login_model');
        $this->load->model('account/account_register_model');
    }


    function register()
    {
        # Build the parameters
        $parameters               = array();
        $parameters['user_ip']    = 'xxx';
        $parameters['username']   = $this->input->post('username');
        $parameters['password']   = md5($this->input->post('password'));
        $parameters['name']       = $this->input->post('name');
        $parameters['accType']    = $this->input->post('accType');

        # Register the account
        $response = $this->account_register_model->register($parameters);

        # Try to login - response is JSON
        if(json_decode($response)->status == 'success') $this->account_login_model->login($parameters);

        # Echo
        echo $response;
    }
}