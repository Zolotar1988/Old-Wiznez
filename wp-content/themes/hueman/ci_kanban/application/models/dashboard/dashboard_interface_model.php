<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_interface_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    # User listings
    function listings()
    {
        return $this->techpear->listings_byUser($this->user->id, FALSE);
    }
}