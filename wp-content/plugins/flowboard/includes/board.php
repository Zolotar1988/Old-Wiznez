<?php

class FlowBoard_Board{



    static function default_zones(){
        return array('ToDo','Working','Test','Done');
    }

    static function get_zones($id){
        $meta = get_post_meta($id,flowboard_metadata());
        $meta = json_decode($meta[0]);
        $zones = $meta->zones;
        if (!is_array($zones)){
            $zones = FlowBoard_Board::default_zones();
        }
        return $zones;
    }

    static function get_zindex_top($id){
        $result = 0;
        $posts = FlowBoard_Board::all_notes($id);
        foreach($posts as $post){
            list($left, $top, $zindex) = explode('x', $post->xyz);
            if ($result<$zindex) $result = $zindex;
        }
        return $result;
    }

    static function all_notes($id){
        $args = array('post_type'=>'flowboard_note','numberposts'=>-1,'meta_key'=>flowboard_metakey(),'meta_value'=>$id);
        $posts = get_posts($args);
        return $posts;
    }

    static function all_boards(){
        $args = array('post_type'=>'flowboard_board','numberposts'=>-1,'orderby'=>'post_title','order'=>'ASC');
        $posts = get_posts($args);
        return $posts;
    }

    static function select_options( $exclude_board_id = 0, $selected_board_id = 0 ){

        ?>
        <option value="0"><?php _e( '-- choose a FlowBoard --', 'flowboard' ); ?></option>
        <?php

        foreach( FlowBoard_Board::all_boards() as $post ){

            if( $exclude_board_id && $post->ID == $exclude_board_id ) continue;

            ?>
            <option value="<?php echo $post->ID; ?>" <?php if( $selected_board_id == $post->ID ) echo "selected"; ?>><?php echo $post->post_title; ?></option>
            <?php
        }

    }

}

?>