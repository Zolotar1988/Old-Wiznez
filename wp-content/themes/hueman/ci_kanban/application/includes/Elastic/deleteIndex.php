<?php

/* deletes entire index */

include("RestRequest.php");

$index = "includu";

if($index == "") {
    print "Error. Please name index to delete";
    exit;
}

$restRequest = new RestRequest("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com:9200/" . $index . "/", "DELETE");
$restRequest->execute();



echo '<pre>' . print_r($restRequest, true) . '</pre>';
  
?>
