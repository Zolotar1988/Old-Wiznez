<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imageupload_actions_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }



    function upload($userId, $imageuploadconfig, $componentConfig)
    {
        # Get the sizes which should be cropped
        $sizes  = $imageuploadconfig['sizes'];


        # Build the options to pass to the image uploader
        $options['userId']                      = $userId;
        $options['sizes']                       = $sizes;
        $options['path']                        = sprintf($componentConfig['path'], $userId);;
        $options['path_thumb']                  = sprintf($componentConfig['path_thumb'], $userId);;
        $options['tmp_locationName']            = $_FILES["Filedata"]["tmp_name"];
        $options['filedata']                    = $_FILES["Filedata"];


        # Upload the photo
        $photo          = $this->imageuploader->upload($options);
        $photo['title'] = $_FILES["Filedata"]["name"];


        # If no photo, return
        if(!$photo) return FALSE;


        # If we should save it into the database
        if($componentConfig['db_table'])
        {
            # Get data organized to upload it
            $db_data                = array();
            $db_data['uploader']    = $userId;
            $db_data['name']        = $photo['name'];
            $db_data['title']       = $photo['title'];
            $db_data['width']       = $photo['width'];
            $db_data['height']      = $photo['height'];
            $db_data['hash']        = $photo['hash'];
            $db_data['data']        = json_encode($photo);


            # Upload
            $this->db->insert($componentConfig['db_table'], $db_data);

            # Build into the $photo var
            $photo['id']    = $this->db->insert_id();
            $db_data['id']  = $photo['id'];
        }


        # Return
        return ($componentConfig['db_table'] ? $photo['id'] : json_decode(json_encode($photo)));
    }



}