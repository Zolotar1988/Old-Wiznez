<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Techpear
{

    public function __construct()
    {
        $this->CI =& get_instance();
    }


    function redis_listings_listingDataLocation($listingId)
    {
        return $this->CI->config->item('site_name') . 'listings_v2:data:' . $listingId;
    }


    function indexListing($listingId)
    {
        return $this->CI->ci_redis->set($this->redis_listings_listingDataLocation($listingId), json_encode($this->CI->db->query("SELECT * FROM techpear_listings WHERE id = $listingId")->row()));
    }


    function unIndexListing($listingId)
    {
        return $this->CI->ci_redis->del($this->redis_listings_listingDataLocation($listingId));
    }


    function listings_listingData($listingId)
    {
        if(!is_numeric($listingId)) die;
        return $this->CI->db->query("SELECT * FROM techpear_listings WHERE id = $listingId")->row();
    }


    function listings_byUser($userId, $filterInactive = TRUE)
    {
        return $this->CI->db->query("SELECT * FROM techpear_listings WHERE user_id = $userId AND deleted = 0 " . ($filterInactive ? ' AND isActive = 1' : '') . ' ORDER BY isActive DESC, id DESC')->result();
    }


    function redis_listings_listingApplicationsLocation($listingId)
    {
        return $this->CI->config->item('site_name') . 'listings_v2:listingApplicants:' . $listingId;
    }


    function redis_listings_userApplicationsLocation($userId)
    {
        return $this->CI->config->item('site_name') . 'listings_v2:userApplications:' . $userId;
    }


    function redis_listings_userInvitationsToApply_userReceived($userId)
    {
        return $this->CI->config->item('site_name') . 'listings_v2:userInvitationsToApply_userReceived:' . $userId;
    }


    function redis_listings_userInvitationsToApply_startupSent($userId)
    {
        return $this->CI->config->item('site_name') . 'listings_v2:userInvitationsToApply_startupSent:' . $userId;
    }


    function listings_hasUserAppliedToListing($userId, $listingId)
    {
        return $this->CI->ci_redis->doesValueExistInList($listingId, $this->redis_listings_userApplicationsLocation($userId));
    }


    function listings_applyBtn($userId, $listingId)
    {
        if($this->listings_hasUserAppliedToListing($userId, $listingId))
            return '<div class="listingApplyBtnContainer-' . $listingId . '"><a href="#" onclick="listings.listing.revoke(' . $listingId . '); return false;" class="btn position_applyBtn red">Revoke application</a></div>';
        else
            return '<div class="listingApplyBtnContainer-' . $listingId . '"><a href="/listings/apply/' . $listingId . '" class="btn position_applyBtn">Apply for position</a></div>';
    }
}