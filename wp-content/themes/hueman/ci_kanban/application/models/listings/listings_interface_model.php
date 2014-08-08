<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class listings_interface_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    # View listings a user applied to
    function applicationsOfUser($userId)
    {
        # Init
        $response = array();

        # Fetch all the IDs
        $ids = $this->ci_redis->lrange($this->techpear->redis_listings_userApplicationsLocation($userId), 0, -1);

        # Now the data
        $x = 0;
        foreach($ids as $id)
        {
            $x++;
            $response[$x]['listing']    = $this->techpear->listings_listingData($id);
            $response[$x]['userData']   = $this->user->userData($response[$x]['listing']->user_id);
        }

        # Return
        return $response;
    }


    # This shows applications towards a specific startup
    function applicationsToStartup($startupId)
    {
        # Init
        $response = array();

        # First fetch all listings
        $listings   = $this->techpear->listings_byUser($startupId);

        # Then foreach them to fetch applicants, and data
        foreach($listings as $listing)
        {
            # First just do this
            $response[$listing->id]['listingData'] = $listing;

            # Now the applications
            foreach($this->ci_redis->lrange($this->techpear->redis_listings_listingApplicationsLocation($listing->id), 0, -1) as $userId) $response[$listing->id]['users'][] = $this->user->userData($userId);
        }

        return $response;
    }


    # This shows invitations to a specific user
    function userInvitationsToApply($userId)
    {
        # Init
        $response = array();

        # First fetch all listings
        $startups   = $this->ci_redis->lrange($this->techpear->redis_listings_userInvitationsToApply_userReceived($userId), 0, -1);

        # Then foreach them to fetch applicants, and data
        foreach($startups as $startup)
        {
            # First just do this
            $response[] = $this->user->userData($startup);
        }

        return $response;
    }
}