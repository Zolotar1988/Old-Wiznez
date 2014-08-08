<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imageupload_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('imageupload/imageupload_actions_model');
        $this->load->library('imageuploader');
        $this->load->config('imageupload');
    }

    function index()
    {
        $x = 'userAvatar';
        echo imageuploadcallbacks::$x();
    }


    function upload()
    {
        # Variables
        $componentConfigNamespace = $this->input->get('ccn');
        $imageuploadconfig        = $this->config->item('imageupload');

        # Just die if the $componentConfigNamespace is not an active one
        if(!in_array($componentConfigNamespace, $imageuploadconfig['allowedComponents'])) die;

        # Upload
        $response = $this->imageupload_actions_model->upload($this->user->id, $imageuploadconfig, $imageuploadconfig[$componentConfigNamespace]);

        # Callback
        $callbackResponse = imageuploadcallbacks::$componentConfigNamespace($this->user->id, $response);

        # Echo out
        echo $callbackResponse;
    }
}