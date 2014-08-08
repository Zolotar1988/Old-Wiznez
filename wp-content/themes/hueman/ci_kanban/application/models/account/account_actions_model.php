<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_actions_model extends CI_Model
{

    function manage($inputs)
    {
        # Store previous email in case of change
        $currentEmail   = $this->user->currentUserData->username;

        # Process the handling of new passwords
        if(strlen($inputs['password']) > 0)
        {
            if($inputs['password'] != $inputs['password_repeat'])
                return $this->framework->returnJsonResponse('error', array('msg' => 'Passwords do not match'));

            $inputs['password'] = md5($inputs['password']);
        }
        else
        {
            unset($inputs['password']);
        }

        # As we don't save password repeat
        unset($inputs['password_repeat']);


        # Now for the email, if it's taken
        $emailTaken = $this->user->idByUsername($inputs['username']);
        if($emailTaken && $emailTaken != $this->user->id)
                return $this->framework->returnJsonResponse('error', array('msg' => 'Email already in use'));
        else
            $inputs['email'] = $inputs['username'];


        # Update the user
        $this->db->where('id', $this->user->id);
        $this->db->update('users', $inputs);

        # Update user data
        $this->user->flushUserData($this->user->id);

        # If the emai is different, resend email verification
        if($currentEmail != $inputs['email']) $this->email->verification_sendCode($this->user->id);
    }
}