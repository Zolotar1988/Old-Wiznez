<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagsystem_interface_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    function tag_suggestion()
    {
        $tag        = $this->input->get('tag');
        $query      = $this->db->query("SELECT * FROM tagsystem_tags WHERE tag LIKE '$tag%'")->result();
        $response   = array();

        foreach($query as $result)
            $response[$result->tag] = $result->tag;

        unset($response[$tag]);
        $response[$tag] = $tag;

        echo ($response ? $this->framework->returnJsonResponse('success', $response) : '');
    }
}