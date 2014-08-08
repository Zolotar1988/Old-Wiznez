<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Elastic
{

    public function __construct()
    {
        require_once(APPPATH . "/includes/Elastic/ElasticSearch.php");
        include_once(APPPATH . "/includes/Elastic/RestRequest.php");

        $this->CI           =& get_instance();
        $this->port         = $this->CI->config->item('elasticsearch_port');
        $this->host         = $this->CI->config->item('elasticsearch_host');
        $this->index        = $this->CI->config->item('site_name');

        // Different indexes
        $this->index_listings   = 'listings';
        $this->tags_index       = 'tags';
    }





    //================================================================================================================================================================
    // Functions to communicate with the database
    //================================================================================================================================================================

    # Index - Used for anything
    function index($type, $id, $indexArray)
    {
        $restRequest = new RestRequest($this->host . ":" . $this->port . "/" . $this->index . "/$type/" . $id, "PUT", json_encode($indexArray, JSON_NUMERIC_CHECK), "json");
        $restRequest->execute();
        return $restRequest->getResponseBody();
    }

    # Delete an element
    function deleteElement($type, $id) {
        $restRequest = new RestRequest($this->host . ":" . $this->port . "/" . $this->index . "/$type/" . $id, "DELETE");
        $restRequest->execute();
        return $restRequest->getResponseBody();
    }

    # Search - Search requests
    function query($type, $queryString, $clean = TRUE)
    {
        $restRequest = new RestRequest($this->host . ":" . $this->port . $this->index . "/$type/_search", "POST", $queryString, "json");
        $restRequest->execute();
        $response = json_decode($restRequest->getResponseBody(), true);
        return ($clean ? $response['hits']['hits'] : $response);
    }
}