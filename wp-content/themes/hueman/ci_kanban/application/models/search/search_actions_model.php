<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_actions_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    # Search for users
    function search_users()
    {
        # Build the query
        $queryString    = "SELECT user_id FROM `users_data-type_intern` WHERE data_filled = 1";
        $queryString   .= ($this->input->post('interns_city') ? ' AND city = ' . $this->db->escape($this->input->post('interns_city')) : '');
        $queryString   .= ($this->input->post('interns_country') ? ' AND country = ' . $this->db->escape($this->input->post('interns_country')) : '');
        $queryString   .= ($this->input->post('interns_intern_type') ? ' AND profession = ' . $this->db->escape($this->input->post('interns_intern_type')) : '');

        $ids      = $this->db->query($queryString)->result();
        $response = array();


        foreach($ids as $user)
        {
            $response[] = $this->user->userData($user->user_id);
        }

        return $response;
    }


    # Search for listings
    function search_listings()
    {
        # Build the query
        $queryString    = "SELECT id, user_id FROM techpear_listings WHERE isActive = 1";
        $queryString   .= ($this->input->post('listings_city') ? ' AND city = ' . $this->db->escape($this->input->post('listings_city')) : '');
        $queryString   .= ($this->input->post('listings_country') ? ' AND country = ' . $this->db->escape($this->input->post('listings_country')) : '');
        $queryString   .= ($this->input->post('listings_intern_type') ? ' AND intern_type = ' . $this->db->escape($this->input->post('listings_intern_type')) : '');
        $queryString   .= ($this->input->post('listings_time_workHours') ? ' AND time_workHours = ' . $this->db->escape($this->input->post('listings_time_workHours')) : '');
        $queryString   .= (strlen($_POST['listings_intern_onLocation']) > 0 ? ' AND intern_onLocation = ' . $this->db->escape($this->input->post('listings_intern_onLocation')) : '');

        $ids            = $this->db->query($queryString)->result();
        $x              = 0;
        $response       = array();

        foreach($ids as $id)
        {
            $x++;
            $response[$x]['listing']    = $this->techpear->listings_listingData($id->id);
            $response[$x]['userData']   = $this->user->userData($response[$x]['listing']->user_id);
        }

        return $response;
    }
}