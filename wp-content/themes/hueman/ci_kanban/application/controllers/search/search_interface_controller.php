<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
    }


    # Search for users
    function users()
    {
        $techpearConfig         = $this->config->item('techpear');
        $data['inputFields']    = $techpearConfig['listings_fields_forSearch_interns'];
        $this->framework->renderComponent('search', 'search_users_view', $data);
    }


    # Search for listings
    function listings()
    {
        $techpearConfig         = $this->config->item('techpear');
        $data['inputFields']    = $techpearConfig['listings_fields_forSearch'];
        $this->framework->renderComponent('search', 'search_listings_view', $data);
    }
}