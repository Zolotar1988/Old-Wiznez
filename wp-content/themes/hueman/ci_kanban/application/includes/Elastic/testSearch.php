<?php

include("RestRequest.php");

$index = "includu";
$type = "video";

$jsonSearch = "
{
    'from': '0',
    'size': '10',
    'fields': [ 'title', 'description', 'tags', 'datetime' ],
    'sort': [
        { 'datetime': { 'order': 'desc' } }
    ],
    'query': {
        'query_string': {
            'fields': [ 'title', 'description' ],    
            'query': 'song',
            'use_dis_max': true
        }
    },
    'highlight': { 
        'fields' : {
            'description': {},
            'title': {}
        }
    },
    'facets': {
        'tags': {
            'terms' : { 'field': 'tags' }
        }
    },
    'filter' : {
        'term' : { 'tags' : 'music' }
    }
}
";

$jsonSearch = str_replace("'", "\"", $jsonSearch);



//$restRequest = new RestRequest("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com:9200/" . $index . "/", "DELETE");

$restRequest = new RestRequest("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com:9200/$index/$type/_search", "POST", $jsonSearch, "json");
$restRequest->execute();

//print $restRequest->getResponseBody() . "<br>-<br>";
//echo '<pre>' . print_r($restRequest, true) . '</pre>' . "<br>-<br>";

$response = json_decode($restRequest->getResponseBody(), true);

$hits = $response["hits"];

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
    foreach($highlights as $field => $highlights) { //we loop the array to get all the highlighted fields (can be multiple like title, description, etc..)            
            print "$field (highlighted) - ";   
            
            foreach($highlights as $highlight) //There can be multiple sentences highlighted so we loop the array to print the all.
                print "$highlight ... "; 
    }

    print "<hr>";    

}

//now we are going to loop the facets (tags) and list how many documents is in each tag facet. we get the facets from the response
$tags = $response["facets"]["tags"];

$totalTags = $tags["total"]; //we get the total number of tags that is in the search result
print "<br> Total number of tags in search result: $totalTags<br><br>";

$tagList = $tags["terms"];
foreach($tagList as $tag) {
    print "Tag: " . $tag["term"] . " - Count: " . $tag["count"] . "<br>";
}

  
?>
