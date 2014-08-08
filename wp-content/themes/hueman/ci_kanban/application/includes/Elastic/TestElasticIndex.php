<?php
include("ElasticSearch.php");

$dbhostname = 'localhost';
$dbusername = 'root';
$dbpassword = 'Dv-22416001896010';
$dbdatabase = 'includu';

$db = new mysqli($dbhostname, $dbusername, $dbpassword, $dbdatabase);
if (!$db)
    die(mysql_error());

$searchClient = new ElasticSearch("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com", "9200");
    
$db->query("SET NAMES 'utf8';");

$res=$db->query("SELECT * FROM videos;"); //select * from articles where id=1187017;
$num=$res->num_rows;
for($i=0;$i<$num;$i++)
{
    $r=$res->fetch_array();
    
    $id = $r['id'];
    
    $indexArray = array();
    
    $indexArray['id'] = $r['id'];
    $indexArray['title'] = $r['title'];
    $indexArray['description'] = $r['description'];
    $indexArray['datetime'] = str_replace(" ", "T", $r['datetime']);
    $indexArray['hits'] = $r['hits'];
    
    $tagsArray = array();
    $tags = unserialize($r['tags']);
    foreach($tags as $tag) {
        $tagsArray[] = trim($tag);
    }
    $indexArray['tags'] = $tagsArray;    

    $result = $searchClient->index("includu", "video", $id, $indexArray);

    print $result;
    
    
    
}    

       


  
?>
