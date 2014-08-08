<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Static_interface_controller extends CI_controller
{

    function terms()
    {
        $this->framework->renderComponent('static', 'static_terms_view');
    }
}