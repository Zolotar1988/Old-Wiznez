<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagsystem_actions_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('tagsystem/tagsystem_actions_model');
    }


    function save_tags()
    {
        $interests_temp = $this->input->post('interests');

        $interests  = array();
        $allowed    = array('-', '_', ' ', 'å', 'ø', 'æ', 'Å', 'Ø', 'Æ');

        foreach($interests_temp as $interest)
        {
            if(strlen($interest) < 25 && ctype_alnum(str_replace($allowed, '', $interest))) $interests[] = strtolower($interest);
        }

        $component = 'profile';
        $response = $this->tagsystem_actions_model->processTags($component, $interests, $this->user->id);

        $this->user->flushUserData($this->user->id);

        echo $response;
    }
}