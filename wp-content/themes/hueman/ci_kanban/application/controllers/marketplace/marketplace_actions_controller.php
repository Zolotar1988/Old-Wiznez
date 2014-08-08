<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('marketplace/marketplace_actions_model');
    }


    function product_create()
    {
        # Vars
        $temporary_id = $this->input->post('temporary_id');

        # Namespace
        $namespace = 'marketplace_';

        # Build the inputs
        $marketplaceConfig = $this->config->item('marketplace');
        foreach($marketplaceConfig['product_fields'] as $key => $data) $inputs[$key] = $this->input->post($namespace.$key);

        # Save
        $productId = $this->marketplace_actions_model->product_create($inputs, $temporary_id);

    }
}
