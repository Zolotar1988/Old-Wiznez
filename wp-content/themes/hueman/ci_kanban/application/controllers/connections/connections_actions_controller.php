<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connections_actions_controller extends CI_controller
{

    public function __construct()
    {
        parent::_construct();
        $this->load->model('connections/connections_actions_model');
    }


    function build_connection()
    {
        $connection_from    = $this->user->id();
        $connection_to      = $this->input->get('connection_to');
        $connection_type    = $this->input->get('connection_type');

        $this->connections_actions_model->buld_connection($connection_from, $connection_to, $connection_type);
    }


    function destroy_connection()
    {
        $connection_from    = $this->user->id();
        $connection_to      = $this->input->get('connection_to');
        $connection_type    = $this->input->get('connection_type');

        $this->connections_actions_model->destroy_connection($connection_from, $connection_to, $connection_type);
    }
}