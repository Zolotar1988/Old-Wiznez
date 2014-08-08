<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('account/account_actions_model');
    }


    # Save the acount manage thing
    function manage()
    {
        # Namspace
        $namespace = 'account_';

        # Build the inputs
        foreach($this->account->account_editingFields() as $key => $data) $inputs[$key] = $this->input->post($namespace.$key);

        # Save
        $save = $this->account_actions_model->manage($inputs);

        # If it went ok
        if(json_decode($save)->status == 'error')
        {
            echo $save;
        }
        else
        {
            redirect('/profile/' . $this->user->id);
        }
    }
}