<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listings_actions_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    # Create
    function create($inputs)
    {
        # Add some stuff
        $inputs['user_id'] = $this->user->id;
        $inputs['city']    = $this->user->currentUserData->city;
        $inputs['country'] = $this->user->currentUserData->country;

        # Deadline
        $inputs['deadline']  = $this->input->post('listings_deadline_year') . '-' . $this->input->post('listings_deadline_month') . '-' . $this->input->post('listings_deadline_day');
        $inputs['date_from'] = $this->input->post('listings_date_from_year') . '-' . $this->input->post('listings_date_from_month') . '-01';
        $inputs['date_to']   = $this->input->post('listings_date_to_year') . '-' . $this->input->post('listings_date_to_month') . '-01';

        # The questions to ask the intern
        $internQuestions         = $inputs['intern_questions'];
        $internQuestionsArray    = explode("\n", $internQuestions);

        # Foreach the array then either build the user data or send email
        $x = 0;
        foreach($internQuestionsArray as $question) if(strlen($question) > 1) $internQuestionsProcessed[++$x]  = $question;
        $inputs['intern_questions'] = json_encode($internQuestionsProcessed);

        # Insert it
        $this->db->insert('techpear_listings', $inputs);

        # Get ID
        $listingId = $this->db->insert_id();

        # Get the data
        $listing = $this->db->query("SELECT * FROM techpear_listings WHERE id = $listingId")->row();

        # Index
        $this->elastic->index($this->elastic->index_listings, $listingId, $listing);
        $this->techpear->indexListing($listingId);

        # Return
        return $listingId;
    }


    # Edit
    function edit($listingId, $inputs)
    {
        # Check if num
        if(!is_numeric($listingId)) die;

        # Check for creator if matches current user
        if($this->db->query("SELECT user_id FROM techpear_listings WHERE id = $listingId")->row('user_id') != $this->user->id) die;

        # Due date
        $inputs['deadline']  = $this->input->post('listings_deadline_year') . '-' . $this->input->post('listings_deadline_month') . '-' . $this->input->post('listings_deadline_day');
        $inputs['date_from'] = $this->input->post('listings_date_from_year') . '-' . $this->input->post('listings_date_from_month') . '-01';
        $inputs['date_to']   = $this->input->post('listings_date_to_year') . '-' . $this->input->post('listings_date_to_month') . '-01';

        # The questions to ask the intern
        $internQuestions         = $inputs['intern_questions'];
        $internQuestionsArray    = explode("\n", $internQuestions);

        # Foreach the array then either build the user data or send email
        $x = 0;
        foreach($internQuestionsArray as $question) if(strlen($question) > 1) $internQuestionsProcessed[++$x]  = $question;
        $inputs['intern_questions'] = json_encode($internQuestionsProcessed);
        
        # Update it
        $this->db->where('id', $listingId);
        $this->db->where('user_id', $this->user->id);
        $this->db->update('techpear_listings', $inputs);

        # Get the data
        $listing = $this->db->query("SELECT * FROM techpear_listings WHERE id = $listingId")->row();

        # Reindex
        $this->elastic->index($this->elastic->index_listings, $listingId, $listing);
        $this->techpear->indexListing($listingId);

        # Return
        return $listingId;
    }


   # Edit
    function edit_delete($listingId)
    {
        # Check if num
        if(!is_numeric($listingId)) die;

        # Get the data
        $listing = $this->db->query("SELECT * FROM techpear_listings WHERE id = $listingId")->row();

        # Check if this user id is the creator
        if($listing->user_id != $this->user->id) die;

        # Update it
        $this->db->where('id', $listingId);
        $this->db->where('user_id', $this->user->id);
        $this->db->update('techpear_listings', array('isActive' => 0, 'deleted' => 1));

        # Reindex
        $this->elastic->deleteElement($this->elastic->index_listings, $listingId);
        $this->techpear->unIndexListing($listingId);

        # Return
        return $listingId;
    }


    # Apply
    function apply($userId, $listingId, $inputs)
    {
        # Check if num
        if(!is_numeric($listingId)) die;

        # Get data
        $listing = $this->techpear->listings_listingData($listingId);

        # Save
        $this->db->insert('techpear_listings_applications', $inputs);
        $insertId = $this->db->insert_id();

        # Save to Redis
        $this->ci_redis->lpush($this->techpear->redis_listings_userApplicationsLocation($userId), $listingId);
        $this->ci_redis->lpush($this->techpear->redis_listings_listingApplicationsLocation($listingId), $userId);

        # Set notification
        $notificationData['listing_id'] = $listingId;
        $notificationId = $this->notifications->set_notification($listing->user_id, $this->user->id, 'listing_application', $notificationData);
        $this->db->query("UPDATE techpear_listings_applications SET notificationId = $notificationId WHERE id = $insertId");

        # Return
        return $listingId;
    }


    # Revoke
    function revoke($userId, $listingId)
    {
        # Check if num
        if(!is_numeric($listingId)) die;

        # Listing data
        $notifId        = $this->db->query("SELECT notificationId FROM techpear_listings_applications WHERE user_id = $userId AND listing_id = $listingId ORDER BY id DESC")->row('notificationId');
        $listingData    = $this->db->query("SELECT * FROM techpear_listings WHERE id = $listingId")->row();

        # Delete from DB
        $this->db->delete('techpear_listings_applications', array('id' => $listingId, 'user_id' => $userId));

        # Remove from Redis
        $this->ci_redis->lrem($this->techpear->redis_listings_userApplicationsLocation($userId), $listingId, 0);
        $this->ci_redis->lrem($this->techpear->redis_listings_listingApplicationsLocation($listingId), $userId, 0);

        # Revoke the notification from the startup
        $this->ci_redis->lrem($this->notifications->notifications_notificationForUserQueue($listingData->user_id,TRUE), $notifId, 0);

    }


    # Revoke
    function request_inviteToApply($startupId, $userId)
    {
        # Check if num
        if(!is_numeric($userId)) die;

        # Check
        if($this->db->query("SELECT id FROM techpear_listings_invitations WHERE invited_user_id = $userId AND startup_id = $startupId")->row('id')) return FALSE;

        # Save
        $this->db->insert('techpear_listings_invitations', array('invited_user_id' => $userId, 'startup_id' => $startupId));

        # Save to Redis
        $this->ci_redis->lrem($this->techpear->redis_listings_userInvitationsToApply_userReceived($userId), $startupId, 0);
        $this->ci_redis->lrem($this->techpear->redis_listings_userInvitationsToApply_startupSent($startupId), $userId, 0);
        $this->ci_redis->lpush($this->techpear->redis_listings_userInvitationsToApply_userReceived($userId), $startupId);
        $this->ci_redis->lpush($this->techpear->redis_listings_userInvitationsToApply_startupSent($startupId), $userId);

        # Set notification
        $notificationData['startup_id'] = $startupId;
        $this->notifications->set_notification($userId, $startupId, 'listing_invite', $notificationData);
    }
}