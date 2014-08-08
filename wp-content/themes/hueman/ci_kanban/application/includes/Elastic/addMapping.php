<?php
//This mapping is neccissary so that the tags field is not tokenized
//I tried running it from here but didnt work, but when running from command line with curl it works. I don't know why
//This will not work once there are any documents in the index, then it will return an error. 

/*
curl -XPOST http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com:9200/includu/video/_mapping -d '{    "video" : {        "properties" : {            "tags" : { "type" : "string", "analyzer" : "keyword" } } } }'
*/

include("RestRequest.php");

$jsonstring = "
{
    'video' : {
        'properties' : {
            'tags' : { 'type' : 'string', 'analyzer' : 'keyword' }
        }
    }
}
";

$jsonstring = str_replace("'", "\"", $jsonstring);


$restRequest = new RestRequest("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com:9200/includu/video/_mapping", "POST", json_encode($jsonstring), "json");
$restRequest->execute();


echo '<pre>' . print_r($restRequest, true) . '</pre>' . "<br>-<br>"; 
print $restRequest->getResponseBody();
  
?>
