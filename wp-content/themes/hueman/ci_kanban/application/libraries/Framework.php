<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Framework
{

    public function __construct()
    {
        $this->CI =& get_instance();

        $this->templateVersion              = 'v5';
        $this->uriSegment1                  = $this->CI->uri->segment(1);
        $this->uriSegment2                  = $this->CI->uri->segment(2);
        $this->uriSegment3                  = $this->CI->uri->segment(3);
        $this->uriSegment4                  = $this->CI->uri->segment(4);
        $this->isMobile                     = FALSE;
        $this->siteTitle                    = 'Dev';
    }


    function returnJsonResponse($status, $data, $set_notificationCookie = FALSE)
    {
        # Set notification cookie if set
//        if($set_notificationCookie)

        # Build response
        $response             = array();
        $response['status']   = $status;
        $response['data']     = $data;
        return json_encode($response);
    }


    function renderComponent($name, $path, $componentData = array())
    {
        $data['component_name']         = $name;
        $data['component_path']         = $name . '/views/' . $path;
        $data['component_data']         = $componentData;

        $this->CI->load->view('layout/index', $data);
    }


    # Notification cookie
    function setNotificationCookie($message, $type = 'danger')
    {
        return setcookie("notificationCookie", json_encode(array('type' => $type, 'message' => $message)), time()+45, "/");
    }



    # This is like the CI flashdata but just custom - it's deleted once used once
    function set_singleAccessVariable($name, $data)
    {
        return setcookie($name, $data, time()+60, "/");
    }

    # This access the functon above
    function get_singleAccessVariable($name)
    {
        $ccokie = (isset($_COOKIE[$name]) ? $_COOKIE[$name] : FALSE);
        setcookie($name, '', time()-3600, "/");
        return $ccokie;
    }
}
