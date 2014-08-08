<?php
//print_r($results);die;
$isEmpty = FALSE;
foreach($results as $fullData)
    if(isset($fullData['users']))
        foreach($fullData['users'] as $userData) $this->load->view('renderers/users/views/renderer_user_item', array('userData' => $userData));
    else
        $isEmpty = TRUE;


if($isEmpty) echo '<div class="empty_content">Nobody has applied to your startup yet. Why not go find a couple of interns and invite them to apply?</div>';