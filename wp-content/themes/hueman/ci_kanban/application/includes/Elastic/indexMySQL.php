<?php
$dbhostname = 'localhost';
$dbusername = 'root';
$dbpassword = 'Dv-22416001896010';
$dbdatabase = 'includu';

include("RestRequest.php");

$db = new mysqli($dbhostname, $dbusername, $dbpassword, $dbdatabase);
if (!$db)
    die(mysql_error());

    
$db->query("SET NAMES 'utf8';");


$res=$db->query("SELECT * FROM includu_videos;"); //select * from articles where id=1187017;
$num=$res->num_rows;
for($i=0;$i<$num;$i++)
{
    $r=$res->fetch_array();
    //print_r($r) . "<br><br>";
    
    //$content["content"] = $r;
    
    $id = $r['id'];
    
    $indexArray = array();
    
    $indexArray['id'] = $r['id'];
    $indexArray['title'] = $r['title'];
    $indexArray['description'] = $r['description'];
    $indexArray['datetime'] = str_replace(" ", "T", $r['datetime']);
    
    $tagsArray = array();
    $tags = unserialize($r['tags']);
    foreach($tags as $tag) {
        $tagsArray[] = trim($tag);
    }
    $indexArray['tags'] = $tagsArray;    
    
    $restRequest = new RestRequest("http://ec2-176-34-201-189.eu-west-1.compute.amazonaws.com:9200/includu/video/" . $id, "PUT", json_encode($indexArray), "json");
    $restRequest->execute();

    echo '<pre>' . print_r($restRequest, true) . '</pre>';
    //exit;

    
    
}    

       
       /*
        $vids   = $db->query("SELECT * FROM includu_videos")->result_array();
       /* foreach($vids as $vid)
        {
            $vid['uploaderUsername'] = $this->user->username($vid['uploader']);
            print_r($vid); echo '\n\n<br><br>';
//            if($this->elastica->save($vid)) { echo 'Saved id: ' . $vid['id'] . '<br>' ; };
        }
        */



  
?>
