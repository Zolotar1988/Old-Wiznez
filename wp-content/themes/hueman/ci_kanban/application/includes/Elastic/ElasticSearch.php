<?php
class ElasticSearch
{
//    public $url;
//    public $port;

    public function __construct ($url = null, $port = null)
    {
        $this->url  = $url;
        $this->port = $port;
    }



    public function deleteElement($index, $type, $id) {
        $restRequest = new RestRequest($this->url . ":" . $this->port . "/$index/$type/" . $id, "DELETE");
        $restRequest->execute();

        return $restRequest->getResponseBody();
    }

    public function query($index,
                          $type,
                          $query,
                          $queryFields,
                          $returnFields,
                          $from=null,
                          $size=null,
                          $sortFields=null,
                          $highlightFields=null,
                          $facets=null,
                          $facetFilters=null
    ) {

        $query_string["query_string"]   = array("fields" => $queryFields, "query" => $query, "use_dis_max" => "true");

        $searchJson["query"]            = $query_string;
        $searchJson["fields"]           = $returnFields;


        if($from != null)
            $searchJson["from"] = $from;

        if($size != null)
            $searchJson["size"] = $size;

        if($sortFields != null)
            $searchJson["sort"] = $sortFields;

        if($highlightFields != null)
            $searchJson["highlight"]["fields"] = $highlightFields;

        if($facets != null)
            $searchJson["facets"] = $facets;


        if($facetFilters != null) {
            if(count($facetFilters) == 1)
                $searchJson["filter"]["term"] = $facetFilters[0];
            else {
                $filters = array();
                foreach($facetFilters as $filter) {
                    $filters[] = array("term" => $filter);
                }

                $searchJson["filter"]["and"] = $filters;
            }
        }

        $searchJson = json_encode($searchJson);


        echo $searchJson;
        echo '<br>';
        echo '{ "query": { "match_phrase_prefix": { "tag": { "query": "mercedes sl", "max_expansions": 5 } } } }';
//        die;

        $restRequest = new RestRequest($this->url . ":" . $this->port . "/$index/$type/_search", "POST", '{ "query": { "match_phrase_prefix": { "tag": { "query": "mercedes sl", "max_expansions": 5 } } } }', "json");
        $restRequest->execute();

        //print $restRequest->getResponseBody();

        $response = json_decode($restRequest->getResponseBody(), true);

        return $response;
    }


}

?>
