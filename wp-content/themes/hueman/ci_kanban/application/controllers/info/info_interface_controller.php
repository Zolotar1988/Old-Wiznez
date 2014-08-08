<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info_interface_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function display()
    {
        $this->framework->renderComponent('info', 'info_display_' . $this->framework->uriSegment2, TRUE);
    }

    function contactpage()
    {
        $this->framework->renderPosterBGComponent('info', 'info-display', 'info/info_display_contact', TRUE);
    }
}