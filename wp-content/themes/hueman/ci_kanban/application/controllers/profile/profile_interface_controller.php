<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('profile/profile_interface_model');
    }


    # Page to view profile
    function index()
    {
        # Build the vvariables
        $userId             = $this->framework->uriSegment2;
        $userData           = $this->user->userData($userId);
        $modelDataFunc      = 'profileData_' . $userData->profile_type;

        # For startups since it uses a GET string to see if should show form to create listing
        # We just handle it here then redirect
        if($this->input->get('action') == 'create_listing' && $userId == $this->user->id)
        {
            if(!$this->email->fetch_isUserEmailVerified($this->user->id)) redirect('/info/verifyemail');
            $this->framework->set_singleAccessVariable('showCreateListingForm', 'true');
            redirect('/profile/' . $userId);
        }

        # More variables to pass to the view
        $data               = $this->profile_interface_model->$modelDataFunc($userId);
        $data['userId']     = $userId;
        $data['userData']   = $userData;

        # The different fields
        $data['profile_fields_config']  = $this->config->item('profile_fields');
        $data['techpear_config']        = $this->config->item('techpear');

        # Load the view
        $this->framework->renderComponent('profile', 'profile_' . $userData->profile_type . '_view', $data);
    }


    # Page to edit the profile
    function edit()
    {
        $data['imageUploadConfig'] = array(
            'multi' => 'false',
            'buttonText' => ($this->user->currentUserData->profile_type == 'startup' ? 'Upload logo' : 'Upload your photo'),
            'uploadScript' => '/imageupload/imageupload_actions_controller/upload?ccn=userAvatar',
            'onUploadComplete' => 'account.avatarUpload.onUploadComplete',
            'onProgress' => 'account.avatarUpload.onProgress',
            'onUpload' => 'account.avatarUpload.onUpload',
        );

        $this->framework->renderComponent('profile', 'profile_' . $this->user->currentUserData->profile_type . '_edit_view', $data);
    }
}