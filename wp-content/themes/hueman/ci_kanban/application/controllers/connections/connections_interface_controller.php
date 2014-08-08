<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connections_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('connections/connections_interface_model');
    }


    function display()
    {
        $function           = $this->framework->uriSegment2;
        $data['contacts']   = $this->connections_interface_model->$function($this->user->id);
        $this->framework->renderComponent('connections', 'connections-display', 'connections/connections_display_view', $data);
    }
}