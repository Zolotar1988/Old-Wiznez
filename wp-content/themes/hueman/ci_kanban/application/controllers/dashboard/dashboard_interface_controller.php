<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('dashboard/dashboard_interface_model');
    }


    # Dashboard index
    function index()
    {
        $data['listings']       = $this->dashboard_interface_model->listings();
        $data['moduleData']     = $data;

        if($this->input->get('created')) $data['newListing'] = $this->techpear->listings_listingData($this->input->get('created'));

        $this->framework->renderComponent('dashboard', 'dashboard_index_view', $data);
    }
}