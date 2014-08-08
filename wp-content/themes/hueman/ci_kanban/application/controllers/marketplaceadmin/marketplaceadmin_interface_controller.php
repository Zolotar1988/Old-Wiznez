<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplaceadmin_interface_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
        $this->load->model('marketplace/marketplaceadmin_interface_model');
    }


    function index()
    {
        $data['dashboardData'] = $this->marketplaceadmin_interface_model->dashboardData();

        $this->framework->renderComponent('marketplaceadmin', 'marketplaceadmin_index_view', $data);
    }

}
