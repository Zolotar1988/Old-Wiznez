<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_actions_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    # Save
    function edit($inputs)
    {
        # Mark that data is filled
        $inputs['data_filled'] = 1;

        # Conditional for different profile types
        if($this->user->currentUserData->profile_type == 'startup')
        {
            # Process the cofounders
            $cofounders         = $inputs['cofounders'];
            $cofoundersArray    = explode("\n", $cofounders);

            # Foreach the array then either build the user data or send email
            foreach($cofoundersArray as $email)
            {
                $userId = $this->user->idByUsername($email);
                if($userId)
                {
                    $cofoundersProcessed[$email]['exists']  = 1;
                    $cofoundersProcessed[$email]['id']      = $userId;
                }
                else
                {
                    $cofoundersProcessed[$email]['exists']  = 0;
                }
            }

            $inputs['cofounders_processed'] = json_encode($cofoundersProcessed);
        }
        elseif($this->user->currentUserData->profile_type == 'intern')
        {
            # Birthdate
            $inputs['birthDate'] = $this->input->post('profile_birthdate_year') . '-' . $this->input->post('profile_birthdate_month') . '-' . $this->input->post('profile_birthdate_day');
            $inputs['age']       = floor((time() - strtotime($inputs['birthDate']))/31556926);
        }

        # Update the user
        $this->db->where('user_id', $this->user->id);
        $this->db->update('users_data-type_' . $this->user->currentUserData->profile_type, $inputs);

        # Update user data
        $this->user->flushUserData($this->user->id);
    }


    # Save the avatar
    function editAvatar($userId, $photoData)
    {
        # Vars
        $avatarData = json_encode($photoData);

        # Update that the user uploaded an avatar and the avatar photo data
        $this->db->query("UPDATE users SET uploadedAvatar = 1, avatarData = '$avatarData' WHERE id = $userId");

        # Flush and update
        $this->user->flushUserData($userId);

        # Return the avatar
        return $this->user->userData($userId)->thumb;
    }
}