<?php

class emg_sc_widget extends WP_Widget {

    // Create Widget
    function emg_sc_widget() {
        parent::WP_Widget(false, $name = 'Easy Media Gallery', array('description' => 'Use this widget to display your media as a widget.'));
    }

    // Widget Content
    function widget($args, $instance) { 
        extract( $args );
        $emg_shortcode = strip_tags($instance['emg_shortcode']);

        ?>
            <div id="latest-box">
                <span class="latest-text">
                    <?php echo do_shortcode( $emg_shortcode ) ?>
                </span> <!-- text -->
            </div> <!-- box -->
        <?php
     }

    // Update and save the widget
    function update($new_instance, $old_instance) {
        return $new_instance;
    }

    // If widget content needs a form
    function form($instance) {
        //widgetform in backend
        $emg_shortcode = strip_tags($instance['emg_shortcode']);
        ?>
            <p>
                <label for="<?php echo $this->get_field_id('emg_shortcode'); ?>">Make sure to generate the shortcode from post or page, copy the shortcode and paste to the following field.</label>
                <textarea rows="5" class="widefat" id="<?php echo $this->get_field_id('emg_shortcode'); ?>" name="<?php echo $this->get_field_name('emg_shortcode'); ?>"><?php echo attribute_escape($emg_shortcode); ?></textarea>
            </p>
        <?php       
    }
}
add_action('widgets_init', create_function('', 'return register_widget("emg_sc_widget");'));
?>