<?php

class FlowBoard_Option{

    function __construct()
    {
        add_action('admin_menu', array(&$this, 'create_flowboard_options_page'));
    }

    function create_flowboard_options_page() {
        add_options_page('FlowBoard', 'FlowBoard', 'administrator', __FILE__, array(&$this, 'build_options_page'));
    }

    function build_options_page() {
    ?>
        <div class="wrap">
            <div class="icon32" id="icon-tools"> <br /> </div>
            <h2>FlowBoard settings</h2>
            <p>Take control of your FlowBoards, by overriding the default settings with your own specific preferences.</p>
            <?php

            $options = get_option('flowboard_plugin_options');

            //Save?
            if (isset($_REQUEST['save'])){

                if (isset($_POST['public_access'])) {
                    $options['public_access'] = true;
                }
                else{
                    $options['public_access'] = false;
                }

                $keys = array_keys($_FILES);
                $i = 0;
                foreach ( $_FILES as $image ) {
                    // if a files was upload
                    if ($image['size']) {
                        // if it is an image
                        if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {
                            $override = array('test_form' => false);
                            // save the file, and store an array, containing its location in $file
                            $file = wp_handle_upload( $image, $override );
                            $options['board_background'] = $file['url'];
                        } else {
                            // Not an image.
                            /*$options = get_option('flowboard_plugin_options');
                            $plugin_options[$keys[$i]] = $options[$logo];*/
                            // Die and let the user know that they made a mistake.
                            wp_die('No image was uploaded.');
                        }
                    }
                    // Else, the user didn't upload a file.
                    // Retain the image that's already on file.
                    else {
                    }
                    $i++;
                }

                update_option('flowboard_plugin_options', $options);

                ?>
                <div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">
                    <p>
                        FlowBoard settings saved <?php echo date('Y-m-d H:i:s'); ?>.
                    </p>
                </div>
                <?php

            }

            ?>
            <form method="post" action="#" enctype="multipart/form-data">

                <p>Background:<br/>
                <?php

                if ($options['board_background']!=NULL) {
                    echo '<img style="width:200px;padding:5px;border:1px solid black;" src="'.$options['board_background'].'" alt="bg" /><br/><br/>';
                }
                echo '<input type="file" name="board_background" />';
                echo '</p>';
                ?>

                <p class="submit">
                    <input name="save" type="submit" class="button-primary" value="<?php esc_attr_e('Save'); ?>" />
                </p>
            </form>
        </div>
    <?php
    }

    function validate_setting($plugin_options) {
        $keys = array_keys($_FILES);
        $i = 0;
        foreach ( $_FILES as $image ) {
            // if a files was upload
            if ($image['size']) {
                // if it is an image
                if ( preg_match('/(jpg|jpeg|png|gif)$/', $image['type']) ) {
                    $override = array('test_form' => false);
                    // save the file, and store an array, containing its location in $file
                    $file = wp_handle_upload( $image, $override );
                    $plugin_options[$keys[$i]] = $file['url'];
                } else {
                    // Not an image.
                    /*$options = get_option('flowboard_plugin_options');
                    $plugin_options[$keys[$i]] = $options[$logo];*/
                    // Die and let the user know that they made a mistake.
                    wp_die('No image was uploaded.');
                }
            }
            // Else, the user didn't upload a file.
            // Retain the image that's already on file.
            else {
                $options = get_option('flowboard_plugin_options');
                $plugin_options[$keys[$i]] = $options[$keys[$i]];
            }
            $i++;
        }
        return $plugin_options;
    }

}