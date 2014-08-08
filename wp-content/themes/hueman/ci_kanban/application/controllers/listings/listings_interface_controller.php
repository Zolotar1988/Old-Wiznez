<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('listings/listings_interface_model');
    }


    # Page to view profile
    function index()
    {
        $data['listing']        = $this->techpear->listings_listingData($this->framework->uriSegment2);
        $data['listingCreator'] = $this->user->userData($data['listing']->user_id);
        $this->framework->renderComponent('listings', 'listings_index_view', $data);
    }


    # Page to create a listing
    function create()
    {
        $techpearConfig         = $this->config->item('techpear');
        $data['inputFields']    = $techpearConfig['listings_fields'];
        $this->framework->renderComponent('listings', 'listings_create_view', $data);
    }


    # Page to edit a listing
    function edit()
    {
        $techpearConfig         = $this->config->item('techpear');
        $data['inputFields']    = $techpearConfig['listings_fields'];
        $data['listing']        = $this->techpear->listings_listingData($this->framework->uriSegment3);
        $this->framework->renderComponent('listings', 'listings_create_view', $data);
    }


    # Page to apply to a listing
    function apply()
    {
        if(!$this->email->fetch_isUserEmailVerified($this->user->id)) redirect('/info/verifyemail');

        $techpearConfig         = $this->config->item('techpear');
        # The different fields
        $data['profile_fields_config']  = $this->config->item('profile_fields');
        $data['techpear_config']        = $this->config->item('techpear');

        $data['inputFields']    = $techpearConfig['listings_fields'];
        $data['listing']        = $this->techpear->listings_listingData($this->framework->uriSegment3);
        $data['startup']        = $this->user->userData($data['listing']->user_id);
        $this->framework->renderComponent('listings', 'listings_aply_view', $data);
    }


    # View listings a user applied to
    function applied()
    {
        $results   = $this->listings_interface_model->applicationsOfUser($this->user->id);
        $this->framework->renderComponent('listings', 'listings_applied_view', array('results' => $results));
    }


    # This shows the applications sent to a specific startup
    function applications()
    {
        # Clear notifications first
        $this->notifications->clear_newNotifications($this->user->id);

        $results   = $this->listings_interface_model->applicationsToStartup($this->user->id);
        $this->framework->renderComponent('listings', 'listings_applications_view', array('results' => $results));
    }


    # This shows the invitations to apply sent to a specific user
    function invitations()
    {
        # Clear notifications first
        $this->notifications->clear_newNotifications($this->user->id);

        $results   = $this->listings_interface_model->userInvitationsToApply($this->user->id);
        $this->framework->renderComponent('listings', 'listings_invitations_view', array('results' => $results));
    }
}