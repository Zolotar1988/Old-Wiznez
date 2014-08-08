<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wp
{
    public function __construct()
    {
        $this->CI =& get_instance();
    }


    function users()
    {
        return $this->db->query("SELECT * FROM wp_users")->result();
    }
}
