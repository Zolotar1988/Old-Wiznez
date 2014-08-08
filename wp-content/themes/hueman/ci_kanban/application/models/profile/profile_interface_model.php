<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_interface_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    function profileData_startup($userId)
    {
        # Intitialize
        $return = array();

        # Job listings
        $return['listings'] = $this->techpear->listings_byUser($userId, ($this->user->id == $userId ? FALSE : TRUE));

        # If we have some "via" thing we do it here, if its viewing an analytics
        if($this->input->get('via') == 'listing')
        {
            $listingId = $this->input->get('via_id');
            if(!is_numeric($listingId)) die;
            $this->db->query("UPDATE techpear_listings SET analytics_views = analytics_views + 1 WHERE id = $listingId");
        }


        # In case user wants to create a listing, put listing fields here
        $techpearConfig             = $this->config->item('techpear');
        $return['newListingFields'] = $techpearConfig['listings_fields'];

        # RETURN!!!!!!1
        return $return;
    }


    function profileData_intern($userId)
    {
        # Intitialize
        $return = array();

        $return['hasInvited']= $this->db->query("SELECT id FROM techpear_listings_invitations WHERE invited_user_id = $userId AND startup_id = " . $this->user->id)->row('id');

        # RETURN!!!!!!1
        return $return;
    }
}