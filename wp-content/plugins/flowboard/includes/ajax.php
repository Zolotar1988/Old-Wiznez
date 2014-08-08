<?php

class FlowBoard_Ajax{

    function __construct()
    {
        add_action('wp_ajax_flowboard_insert_note', array(&$this, 'insert_note'));
        add_action('wp_ajax_nopriv_flowboard_insert_note', array(&$this, 'insert_note'));
        add_action('wp_ajax_flowboard_update_position', array(&$this, 'update_position'));
        add_action('wp_ajax_nopriv_flowboard_update_position', array(&$this, 'update_position'));
        add_action('wp_ajax_nopriv_flowboard_delete_note', array(&$this, 'delete_note'));

        add_action('wp_ajax_flowboard_note_form', array(&$this, 'get_note_form'));
        add_action('wp_ajax_nopriv_flowboard_note_form', array(&$this, 'get_note_form'));

        add_action('wp_ajax_flowboard_note', array(&$this, 'get_note'));
        add_action('wp_ajax_nopriv_flowboard_note', array(&$this, 'get_note'));

        add_action('wp_ajax_flowboard_boardkey', array(&$this, 'get_boardkey'));

    }

    function get_note(){
        $id = (int)esc_attr($_REQUEST['id']);
        $note = new FlowBoard_Note($id);
        echo $note->html(false);
        die(0);
    }

    function get_note_form(){

        $id = (int)esc_attr($_REQUEST['id']);
        $board = (int)esc_attr($_REQUEST['board']);

        $note = new FlowBoard_Note($id);

        if ($board) $note->board = $board;

        echo $note->html(true);

        echo $note->form();

        die(0);

    }

    function insert_note()
    {
        // Error reporting
        error_reporting(E_ALL^E_NOTICE);
        // Checking whether all input variables are in place:
        if(!is_numeric($_POST['zindex']) || !isset($_POST['author']) || !isset($_POST['body']) || !in_array($_POST['color'],array('yellow','green','blue','purple','orange','pink')))
        {
            echo 'Not valid input to save note!';
            die(0);
        }

        /*
        if(ini_get('magic_quotes_gpc'))
        {
            // If magic_quotes setting is on, strip the leading slashes that are automatically added to the string:
            $_POST['author']=stripslashes($_POST['author']);
            $_POST['body']=stripslashes($_POST['body']);
        }*/

        // Escaping the input data:
        $author         = esc_attr($_POST['author']);
        $body           = esc_html($_POST['body']);
        $color          = esc_attr($_POST['color']);
        $id             = (int)esc_attr($_POST['id']);
        $pos            = (int)esc_attr($_POST['pos']);
        $zindex         = (int)esc_attr($_POST['zindex']);
        $board          = (int)esc_attr($_POST['board']);
        $estimate       = (int)esc_attr($_POST['estimate']);
        $timeleft       = (int)esc_attr($_POST['timeleft']);
        $postcontent    = wp_kses_post($_POST['postcontent']);
		$add_link 			= $_POST['add_link'] ? true : false;

        $dataArr = array('id'=>0,'author'=>$author,'color'=>$color,'zindex'=>$zindex,'estimate'=>$estimate,'timeleft'=>$timeleft,'xyz'=>$pos,'add_link'=>$add_link);

        if (is_numeric($id) && $id>0)
        {
            global $user_ID;

            // Update post 37
            $my_post = array();
            $my_post['ID'] = $id;

            if ($body!="###imported item###")
            {
                $my_post['post_title'] = $body;
                $my_post['post_content'] = $postcontent;
            }

            // Update the post into the database
            wp_update_post( $my_post );

            /*if (!update_post_meta($id, flowboard_metakey(), $board))
            {
                add_post_meta($id, flowboard_metakey(), $board, true);
            }*/

            $dataString = get_post_meta($id,flowboard_metadata());
            $dataArrOld = json_decode($dataString[0]);

            $dataArr['xyz'] = $dataArrOld->xyz;
            $dataArr['id'] = $id;
            if (!update_post_meta($id, flowboard_metadata(), json_encode($dataArr)))
            {
                add_post_meta($id, flowboard_metadata(), json_encode($dataArr),true);
            }

            echo $id;

        }
        else
        {
            if (!$board) {
                echo 'Unknown board ID';
                die(0);
            }

            global $user_ID;
            $new_post = array(
                'post_title' => $body,
                'post_content' => $postcontent,
                'post_status' => 'publish',
                'post_date' => current_time('mysql'),
                'post_author' => $user_ID,
                'post_type' => 'flowboard_note',
                'post_category' => array(0)
            );
            $id = wp_insert_post($new_post);

            add_post_meta($id, flowboard_metakey(), $board, true);

            $dataArr['id'] = $id;

            //If new check if other in the same place and increase pos and zindex!
            $posts = FlowBoard_Board::all_notes($board);

            $xyz = "42x72x1";
            $xyz = $this->reverse_pos($xyz, $posts);

            //Default status:
            $dataArr['status'] = $this->board_default_status($board);

            $dataArr['xyz'] = $xyz;
            add_post_meta($id, flowboard_metadata(), json_encode($dataArr), true);

            echo $id;
        }

        die(0);
    }

    function board_default_status($board){
        $meta = get_post_meta($board, flowboard_metadata(), true);
        $meta = json_decode($meta);
        if (is_array($meta->zones)) return $meta->zones[0];
        return "";
    }

    function reverse_pos($xyz, $posts){

        $moved = false;
        list($left, $top, $zindex) = explode('x', $xyz);
        foreach($posts as $post){
            $meta = get_post_meta($post->ID, flowboard_metadata(), true);
            $meta = json_decode($meta);
            list( $l , $t , $z ) = explode('x', $meta->xyz);
            if ( $l == $left && $t == $top ) {
                $moved = true;
                $left += 4;
                $top += 4;
                $zindex = $z + 1;
            }
            if ($z>$zindex) $zindex = $z+1;
        }
        $result = $left . 'x' . $top . 'x' . $zindex;

        if ($moved) {
            return $this->reverse_pos($result, $posts);
        }
        else
        {
            return $result;
        }
    }


    function update_position()
    {
        // Error reporting
        error_reporting(E_ALL^E_NOTICE);
        // Validating the input data:
        if(!is_numeric($_GET['id']) || !is_numeric($_GET['x']) || !is_numeric($_GET['y']))
        {
            die("0");
        }


        // Escaping:
        $id = (int)$_GET['id'];
        $status = esc_attr($_GET['status']);
        $x = (int)$_GET['x'];
        $y = (int)$_GET['y'];
        $z = 0;
        if(is_numeric($_GET['z']))
        {
            $z = (int)$_GET['z'];
        }
        $pos = $x.'x'.$y.'x'.$z;
        // Saving the position and z-index of the note:

        $dataString = get_post_meta($id,flowboard_metadata());

        $dataArr = json_decode($dataString[0]);

        $dataArr->xyz = $pos;

        if (!empty($status)) $dataArr->status = $status;

        if (!update_post_meta($id, flowboard_metadata(), json_encode($dataArr)))
        {
            add_post_meta($id, flowboard_metadata(), json_encode($dataArr),true);
        }

        die(1);
    }

    function get_boardkey(){
        $options = get_option('flowboard_plugin_options');
        $board_key = ((int)$options['board_key'])+1;
        $options['board_key'] = $board_key;
        update_option('flowboard_plugin_options',$options);
        echo $board_key;
        die(0);
    }

    function delete_note(){

    }

}



?>