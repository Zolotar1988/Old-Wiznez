<?php
class Imageuploadcallbacks
{

    public function __construct()
    {
        $this->CI =& get_instance();
    }

    function userAvatar($userId, $photoData)
    {
        $this->load->model('profile/profile_actions_model');
        return $this->profile_actions_model->editAvatar($userId, $photoData);
    }

    function marketplace($userId, $photoData, $temporaryId)
    {
        $this->load->model('marketplace/marketplace_actions_model');
        return $this->marketplace_actions_model->saveImage($userId, $photoData);
    }
}



// Config
$config['imageupload']['allowedComponents']    = array('userAvatar', 'marketplace');
$config['imageupload']['uploadServerPath']     = '/home/techpear/htdocs/cdn/images';
$config['imageupload']['uploadServerTempPath'] = '/home/techpear/htdocs/cdn/images/temporary';
$config['imageupload']['maxImageWidth']        = 2000;



# The different component config etc that and their config
$config['imageupload']['userAvatar'] = array(
    'db_table' => FALSE,
    'path' => '/large/%d',
    'path_thumb' => '/thumbs/%d'
);

$config['imageupload']['marketplace'] = array(
    'db_table' => FALSE,
    'path' => '/large/%d',
    'path_thumb' => '/thumbs/%d'
);



# Sizes to crop and/or resize to
$config['imageupload']['sizes'] = array (
    // Mainly web sizes
    50 => 'square',
    75 => 'square',
    110 => 'square',
    150 => 'square',
    220 => 'square',
    280 => 'ratio',
    350 => 'ratio',

    120 => array('width' => 120, 'height' => 90), // The avatar /2
    260 => array('width' => 260, 'height' => 320), // The avatar
    520 => array('width' => 520, 'height' => 640), // The avatar @2x
    650 => array('width' => 650, 'height' => 430),
    728 => 'ratio',
    729 => 'square'
);