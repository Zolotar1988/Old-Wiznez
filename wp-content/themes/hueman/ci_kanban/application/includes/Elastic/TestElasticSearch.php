<?php
include("ElasticSearch.php");

$searchClient       = new ElasticSearch("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com", "9200");

$queryFields        = array("title", "description");
$returnFields       = array("title", "description", "tags", "datetime", "hits");

$sortFields[]       = array("datetime"=>array("order"=>"asc"));

$highlightFields[]  = array("description" => array("fragment_size" => "150", "number_of_fragments" => "5")); //fragment_size = number of chars, number_of_fragments = sentences
$highlightFields[]  = array("title" => array("fragment_size" => "150", "number_of_fragments" => "5"));

$facets["tags"]     = array("terms" => array("field" => "tags"));

$facetFilters[] = array("tags" => "music"); //here you can add more tags by adding to the array: $facetFilters[] = array("tags" => "dirty vegas");

$result = $searchClient->query("includu", //The index to query
                                "video", //The type used in the index
                                "song", //The keywords to use in query
                                $queryFields, //The fields that will be queried
                                $returnFields, //The fields that will be returned in the result set
                                0, //number that determines where to start the result set listing (used for paging)
                                10, //number that determines how many results to list
                                $sortFields, //The fields that we will sort on - Add null to ignore and give back most relevant content
                                $highlightFields, //The fields that get returned with highlighting (marked <em></em>)
                                $facets, //The fields that we would like to use for faceted browsing
                                null //$facetFilters //List of tags that we will use in faceted browsing (on tags)
                                );
//print_r($result);

$hits = $result["hits"];

$total = $hits["total"]; //The total number of hits in the query

print "Total number of hits: $total <br><br>";

$resultset = $hits["hits"]; //get the resultset from the hits

print "Results: <br><br>";

//here we loop the resultset and we get each individual result in a $document variable
foreach($resultset as $document) {

    $id = $document["_id"]; //we get the id
    $fields = $document["fields"]; //this is all the fields of the document

    print "Id: $id <br>";

    foreach($fields as $field => $value) //we loop the fields array to get all the fields
        if($field == "tags") { //if the field name is "tags" we know in Includu that it is an array that can be looped
            $tagList = "";
            foreach($value as $tag)
                $tagList .= $tag . ", ";
           
            print "Tags: $tagList <br>";     
        } else
            print "$field - $value <br>";   
            
    $highlights = $document["highlight"]; //The highlighted fields are here.

    foreach($highlights as $field => $highlights)
    { //we loop the array to get all the highlighted fields (can be multiple like title, description, etc..)
            print "$field (highlighted) - ";   
            
            foreach($highlights as $highlight) //There can be multiple sentences highlighted so we loop the array to print the all.
                print "$highlight ... "; 
    }

    print "<hr>";

}

//now we are going to loop the facets (tags) and list how many documents is in each tag facet. we get the facets from the response
$tags = $result["facets"]["tags"];

$totalTags = $tags["total"]; //we get the total number of tags that is in the search result
print "<br> Total number of tags in search result: $totalTags<br><br>";

$tagList = $tags["terms"];
foreach($tagList as $tag) {
    print "Tag: " . $tag["term"] . " - Count: " . $tag["count"] . "<br>";
}

  
?>
