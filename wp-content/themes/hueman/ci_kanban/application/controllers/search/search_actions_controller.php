<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('search/search_actions_model');
    }


    # Search for users
    function search_users()
    {
        $results = $this->search_actions_model->search_users();
        $this->load->view('components/search/views/search_response_users', array('results' => $results));
    }


    # Search for listings
    function search_listings()
    {
        $results = $this->search_actions_model->search_listings();
        $this->load->view('components/search/views/search_response_listings', array('results' => $results));
    }
}