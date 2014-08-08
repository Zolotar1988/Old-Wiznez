<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function cdn_url($type = '',$file=''){
    $ci = & get_instance();

    $ci->load->config("cdn");
    switch($type){
        case "css":
            $host = $ci->config->item("cdn_host_css");
            break;

        case "thumbs":
            $host = $ci->config->item('cdn_host_thumbs');
            break;

        case "images-thumbs":
            $host = $ci->config->item("cdn_host_css");
            break;

        case "profile-thumbs":
            $host = 'http://likables.no';
            break;

        case 'img';
        case "images":
            $host = 'http://likables.no';
            break;
        
        case "js":
            $host = $ci->config->item("cdn_host_js");
            break;

        default:
            $host = $ci->config->item("cdn_host_css");
            break;
    }

    if(empty($host)){
        $host = '';
    }
    if($file!=''){
        return $host .'/'.$file;
    }
    else {
        return $host;
    }
}





function uploadToCdn($file='',$path=''){
    
}

    






