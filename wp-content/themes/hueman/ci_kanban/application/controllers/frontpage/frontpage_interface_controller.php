<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Frontpage_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
    }


    # Frontpage index
    function index()
    {
        # Redirect
        if($this->user->id) if($this->user->currentUserData->profile_type == 'startup') redirect('/profile/' . $this->user->id); else redirect('search/listings');

        # Load view
        $this->framework->renderComponent('frontpage', 'frontpage_index_view');
    }
}