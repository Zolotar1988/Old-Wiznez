<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accountconnect_lib_interface_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
    }


    function forceLogoutUser()
    {
        $userId    = $_GET['userId'];
        if(!is_numeric($userId))   die;
        if($this->user->id != 1)    die;

        $this->accountconnect->forceLogoutUser($userId);
        echo 'Will logout';
    }


    function unsetForceLogoutUser()
    {
        $userId    = $_GET['userId'];
        if(!is_numeric($userId))   die;
//        if($this->user->id != 1)    die;

        $this->accountconnect->unsetForceLogoutUser($userId);
        echo 'Will not logout';
    }


}