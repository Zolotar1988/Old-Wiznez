<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('listings/listings_actions_model');
    }


    # Create new listing
    function create()
    {
        # Namespace
        $namespace = 'listings_';

        # Build the inputs
        $techpearConfig = $this->config->item('techpear');
        foreach($techpearConfig['listings_fields'] as $key => $data) $inputs[$key] = $this->input->post($namespace.$key);

        # Save
        $listingId = $this->listings_actions_model->create($inputs);

        # Redirect
        $this->load->view('components/profile/modules/profile_startup_listing_row', array('listing' => $this->techpear->listings_listingData($listingId), 'techpear_config' => $this->config->item('techpear')));
    }


    # Edit existing
    function edit()
    {
        # Namespace
        $namespace = 'listings_';

        # Build the inputs
        $listingId      = $this->input->get('listingId');
        $techpearConfig = $this->config->item('techpear');
        foreach($techpearConfig['listings_fields'] as $key => $data) $inputs[$key] = $this->input->post($namespace.$key);

        # Save
        $listingId = $this->listings_actions_model->edit($listingId, $inputs);

        # Echo out the row
        $this->load->view('components/profile/modules/profile_startup_listing_row', array('listing' => $this->techpear->listings_listingData($listingId), 'techpear_config' => $this->config->item('techpear')));
    }


    # Delete a listing
    function edit_delete()
    {
        # Build the inputs
        $listingId      = $this->input->get('listingId');

        # Delete
        $listingId = $this->listings_actions_model->edit_delete($listingId);
    }


    # Apply to a listing
    function apply()
    {
        # Build the inputs
        $listingId              = $this->input->post('listingId');
        $userId                 = $this->user->id;
        $insert['listing_id']   = $listingId;
        $insert['user_id']      = $userId;
        $insert['question_replies'] = json_encode($this->input->post('listings_intern_questions'));

        # Save
        $listingId = $this->listings_actions_model->apply($userId, $listingId, $insert);

        # Stats
        $this->db->query("UPDATE techpear_listings SET analytics_applications = analytics_applications + 1 WHERE id = $listingId");

        # Redirect
        redirect('listings/applied');
    }


    # Revoke an application
    function revoke()
    {
        # Build the inputs
        $listingId              = $this->input->get('listingId');
        $userId                 = $this->user->id;

        # Revoke
        $this->listings_actions_model->revoke($userId, $listingId);

        # Echo the btn
        echo $this->techpear->listings_applyBtn($userId, $listingId);
    }


    # Invite an user to apply to a position in your starutp
    function request_inviteToApply()
    {
        # Build the inputs
        $startupId              = $this->user->id;
        $userId                 = $this->input->get('userId');

        # Revoke
        $this->listings_actions_model->request_inviteToApply($startupId, $userId);

        # Echo the btn
        echo 1;
    }
}