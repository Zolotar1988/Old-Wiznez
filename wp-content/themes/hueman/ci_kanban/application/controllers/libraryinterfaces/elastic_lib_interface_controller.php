<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elastic_lib_interface_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
    }



    function logFacebookFriendsInvite()
    {
        $response   = $this->input->post('response');
        $requestId  = $response['request'];
        $friends    = $response['to'];

        echo $this->analytics->logFacebookFriendsInvite($this->user->id, $requestId, $friends);
    }



}