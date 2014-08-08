<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('profile/profile_actions_model');
    }


    # Save profile edit
    function edit()
    {
        # If the user data is currently filled out or not
        $data_filled = $this->user->currentUserData->data_filled;

        # Namespace
        $namespace = 'profile_';

        # Build the inputs
        foreach($this->user->profileTypes_fields($this->user->currentUserData->profile_type) as $key => $data)
        {
            # Get the input
            $inputs[$key] = $this->input->post($namespace.$key);

            # Overwrite if it's a url by adding http:// if needed
            if(substr($key, 0, 4) == 'url_') $inputs[$key] = appendHttpToString($this->input->post($namespace.$key));
        }

        # Save
        $this->profile_actions_model->edit($inputs);

        # Send verification email if no data
        if(!$data_filled && !$this->email->fetch_isUserEmailVerified($this->user->id)) $this->email->verification_sendCode($this->user->id);

        # Redirect
        if($this->user->currentUserData->profile_type == 'startup')
            if(!$data_filled)
                redirect('profile/' . $this->user->id . '?action=create_listing');
            else
                redirect('profile/' . $this->user->id);
        else
            redirect('profile/' . $this->user->id);
//            redirect('search');
    }
}