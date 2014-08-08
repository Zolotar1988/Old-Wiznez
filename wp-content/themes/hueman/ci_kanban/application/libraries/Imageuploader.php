<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imageuploader
{

    public function __construct()
    {
        $this->CI =& get_instance();
        $imageuploadconfig              = $this->CI->config->item('imageupload');
        $this->uploadServerPath         = $imageuploadconfig['uploadServerPath'];
        $this->uploadServerTempPath     = $imageuploadconfig['uploadServerTempPath'];
        $this->maxImageWidth            = $imageuploadconfig['maxImageWidth'];
    }


    function upload($options)
    {
        # Vars
        $imageuploadconfig              = $this->CI->config->item('imageupload');
        $this->uploadServerPath         = $imageuploadconfig['uploadServerPath'];
        $this->uploadServerTempPath     = $imageuploadconfig['uploadServerTempPath'];
        $this->maxImageWidth            = $imageuploadconfig['maxImageWidth'];


        # sha1_file the image
        $hash = sha1_file($options['tmp_locationName']);


        # Just create the response array first and also set the hash
        $response           = array();
        $response['hash']   = $hash;


        # Return if there is no file
        if(!is_file($options['tmp_locationName'])) return FALSE;


        # Get the image type, height and width and then add it to the response array
        list($originalWidth, $originalHeight, $type) = getimagesize($options['tmp_locationName']);
        $response['width']  = $originalWidth;
        $response['height'] = $originalHeight;


        # Figure out if it's an allowed type
        switch($type)
        {
            case 1:  $ext = 'gif'; break;
            case 2:  $ext = 'jpg'; break;
            case 3:  $ext = 'png'; break;
            default: $ext = FALSE; break;
        }


        # Add to response array
        $response['ext']    = $ext;


        # If no extension, return FALSE
        if(!$ext) return FALSE;

        # First make sure that the end path for the image and thumb folder do exist as well as set the end path
        $options['path']                                = $this->uploadServerPath . $options['path'];
        $options['path_thumb']                          = $this->uploadServerPath . $options['path_thumb'];
        if(!file_exists($options['path']))                mkdir($options['path'], 0755, TRUE);
        if(!file_exists($options['path_thumb']))          mkdir($options['path_thumb'], 0755, TRUE);


        # Set some variables as well as name the image
        $options['imageName']       = $options['userId'] . '_' . md5(microtime() . rand() . rand() . rand() . time());
        $options['tempCropName']    = $options['path'] . '/' . $options['userId'] . '_' . md5(microtime() . rand() . rand() . rand() . time()) . '.' . $ext;
        $options['uploadedImage']   = $options['path'] . '/' . $options['imageName'] . '.' . $ext;
        $response['name']           = $options['imageName'];


        # Load the image library
        $this->CI->load->library('image_lib');


        # We move the image from /tmp here and we also resize the image if it's too big, so we don't get images that are 4900x3000
        $originalResizeHeight  = $this->calculateResizeHeight(($originalWidth > $this->maxImageWidth ? $this->maxImageWidth : $originalWidth), $originalWidth, $originalHeight);
        $this->CI->image_lib->initialize(array('source_image' => $options['tmp_locationName'], 'new_image' => $options['uploadedImage'], 'maintain_ratio' => true, 'width' => $this->maxImageWidth, 'height' => $originalResizeHeight));
        $this->CI->image_lib->resize();



        /*#################################################################################################################################*/
        /*######################################### ----->>>>> RESIZING <<<<<----- ########################################################*/
        /*#################################################################################################################################*/

        # Foreach the sizes (widths) and resize (and maybe crop)
        foreach($options['sizes'] as $max => $squareOrOriginalAspectRatio)
        {
            # If it's set to TRUE, append the thumbnail size to folder
            $options['path_thumb_alt']  = $options['path_thumb'] . '/' . $options['imageName'];
            if(!file_exists($options['path_thumb_alt']))    mkdir($options['path_thumb_alt'], 0755, TRUE);
            $options['path_thumb_blur'] = $options['path_thumb_alt'] . '/' . $options['imageName'] . $this->size_prefix($max) . '_blurred.' . $ext;
            $options['path_thumb_alt']  = $options['path_thumb_alt'] . '/' . $options['imageName'] . $this->size_prefix($max) . '.' . $ext;


            # If it's crop to square, the resize saves to TEMP, else it saves to thumb itself
            if($squareOrOriginalAspectRatio == 'square')
            {
                # Since this is originally for Flickr, with "max", we just rename it to width
                $width  = $max;

                # Delete the temp image
                $temp_image_needs_deleting  = TRUE;

                # Set the thumbnail height
                $resize  = $this->calculateResizeHeightWidth_forCrop($width, $originalWidth, $originalHeight);

                # Resize it
                $this->CI->image_lib->initialize(array('source_image' => $options['uploadedImage'], 'new_image' => $options['tempCropName'], 'maintain_ratio' => true, 'width' => $resize['width'], 'height' => $resize['height']));
                $this->CI->image_lib->resize();

                # Crop it
                $this->CI->image_lib->initialize(array('image_library' => 'gd2', 'source_image' => $options['tempCropName'], 'new_image' => $options['path_thumb_alt'], 'maintain_ratio' => false, 'width' => $width, 'height' => $width, 'x_axis' => 0, 'y_axis' => 0));
                $this->CI->image_lib->crop();

                # Add to response the width and height of the thumbnail
                $response['thumb_' . $width]['width']    = $width;
                $response['thumb_' . $width]['height']   = $width;
            }


            # Just normal
            if($squareOrOriginalAspectRatio == 'ratio')
            {
                # Since this is originally for Flickr, with "max", we just rename it to width
                $width  = $max;

                # Set the thumbnail height
                $size['height']  = $this->calculateResizeHeight($width, $originalWidth, $originalHeight);

                # Resize it
                $this->CI->image_lib->initialize(array('source_image' => $options['uploadedImage'], 'new_image' => $options['path_thumb_alt'], 'maintain_ratio' => true, 'width' => $width, 'height' => $size['height']));
                $this->CI->image_lib->resize();

                # Add to response the width and height of the thumbnail
                $response['thumb_' . $width]['width']    = $width;
                $response['thumb_' . $width]['height']   = $size['height'];
            }


            elseif(is_array($squareOrOriginalAspectRatio))
            {
                $hasCroppedASquare  = TRUE;

                # Set the thumbnail height
                $resize  = $this->calculateResizeHeightWidth_forCrop_notSquared($squareOrOriginalAspectRatio['width'], $squareOrOriginalAspectRatio['height'], $originalWidth, $originalHeight);

                # Resize it
                $this->CI->image_lib->initialize(array('source_image' => $options['uploadedImage'], 'new_image' => $options['tempCropName'], 'maintain_ratio' => true, 'width' => $resize['width'], 'height' => $resize['height']));
                $this->CI->image_lib->resize();

                # Crop it
                $this->CI->image_lib->initialize(array('image_library' => 'gd2', 'source_image' => $options['tempCropName'], 'new_image' => $options['path_thumb_alt'], 'maintain_ratio' => false, 'width' => $squareOrOriginalAspectRatio['width'], 'height' => $squareOrOriginalAspectRatio['height'], 'x_axis' => 0, 'y_axis' => 0));
                $this->CI->image_lib->crop();

                # Add to response the width and height of the thumbnail
                $response['thumb_' . $squareOrOriginalAspectRatio['width']]['width']    = $squareOrOriginalAspectRatio['width'];
                $response['thumb_' . $squareOrOriginalAspectRatio['width']]['height']   = $squareOrOriginalAspectRatio['height'];
            }



            # If we are to create a blurred version of this photo
            if(isset($options['blurSizes']) && in_array($max, $options['blurSizes']))
            {
                $image      = new Imagick($options['path_thumb_alt']);
                $image->blurImage(40, 8);
                $image->writeImage($options['path_thumb_blur']);
            }


            # Clear the image library cache
            $this->CI->image_lib->clear();
        }

        return $response;
    }





    function calculateResizeHeight($thumbWidth, $originalWidth, $originalHeight)
    {
        return round($thumbWidth * ($originalHeight / $originalWidth));
    }


    function calculateLongestSide($max, $originalWidth, $originalHeight)
    {
        if($originalWidth > $originalHeight)
            return array('width' => $max, 'height' => ceil($originalHeight / ($originalWidth / $max)));

        elseif($originalWidth < $originalHeight)
            return array('width' => ceil($originalWidth / ($originalHeight / $max)), 'height' => $max);

        elseif($originalWidth == $originalHeight)
            return array('width' => $max, 'height' => $max);

        return FALSE;
    }


    function calculateResizeHeightWidth_forCrop($thumb_width_height, $originalWidth, $originalHeight)
    {
        if($originalWidth > $originalHeight)
            return array('width' => ceil($originalWidth / ($originalHeight / $thumb_width_height)), 'height' => $thumb_width_height);

        elseif($originalWidth < $originalHeight)
            return array('width' => $thumb_width_height, 'height' => ceil($originalHeight / ($originalWidth / $thumb_width_height)));

        elseif($originalWidth == $originalHeight)
            return array('width' => $thumb_width_height, 'height' => $thumb_width_height);

        return FALSE;
    }


    function calculateResizeHeightWidth_forCrop_notSquared($thumb_width, $thumb_height, $originalWidth, $originalHeight)
    {
        # First figure out the aspect ratio the thumb will be
        $thumb_aspectRatio      = $thumb_width / $thumb_height;
        $original_aspectRatio   = $originalWidth / $originalHeight;

        if($original_aspectRatio > $thumb_aspectRatio)
            return array('width' => ceil($thumb_height * $original_aspectRatio), 'height' => $thumb_height);

        elseif($original_aspectRatio < $thumb_aspectRatio)
            return array('width' => $thumb_width, 'height' => ($thumb_width / $originalWidth) * $originalHeight);

        elseif($original_aspectRatio == $thumb_aspectRatio)
            return array('width' => $thumb_width, 'height' => $thumb_height);

        return array('width' => $thumb_width, 'height' => $thumb_height);
    }


    function size_prefix($size)
    {
        return '_' . $size;
    }
}