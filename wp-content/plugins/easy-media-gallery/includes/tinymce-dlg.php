<?php
$shortname = 'emgtinymce';

$optn = array (
array( "name" => "Order media by",
	"id" => $shortname."_select_method",
	"type" => "select",
	"options" => array( "Select", "Category", "Media" ),
	"std" => "Choose a category" ),	

array( "name" => "Choose a category",
	"id" => $shortname."_select_cat",
	"type" => "selectcat",
	"options" => "",
	"std" => "" ),
	
array( "name" => "Choose media",
	"id" => $shortname."_select_sing_media",
	"type" => "selectmedia",
	"options" => "",
	"std" => "" ),	
);


if ( strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post.php' ) ) {
	
// ADD STYLE & SCRIPT
	add_action( 'admin_head', 'emg_editor_add_init' );
		function emg_editor_add_init() {
			
			if ( get_post_type( get_the_ID() ) != 'easymediagallery' ) {
				
				wp_enqueue_style( 'easymedia-tinymce' );
				wp_enqueue_style( 'jquery-multiselect-css' );
				wp_enqueue_style( 'jquery-ui-themes-redmond' );
				wp_enqueue_script( 'jquery-ui' );
				wp_enqueue_script( 'jquery-multi-sel' );
				wp_enqueue_script( 'easymedia-cpscript', plugins_url( 'functions/tinymce-dlg.js' , __FILE__ ) );
				wp_enqueue_script( 'jquery-i-button', plugins_url( 'js/jquery/jquery.ibutton.js' , __FILE__ ) );
				wp_enqueue_style( 'metabox-ibuttoneditor', plugins_url( 'css/ibutton.css' , __FILE__ ), false, EASYMEDIA_VERSION );
		?>
        <?php
			}
			
		}
	
// ADD MEDIA BUTOON	
	add_action( 'media_buttons_context', 'add_emg_shortcode_button', 1 );
		function add_emg_shortcode_button($context) {
			$img = plugins_url( 'images/easymedia-32x32.png' , __FILE__ );
			$container_id = 'modal';
			$title = 'Easy Media Shortcode';
			$context .= '
			<a class="thickbox button" id="add_emg_shortcode_button" title="'.$title.'" style="outline: medium none !important; cursor: pointer;" >
			<img src="'.$img.'" alt="Easy Media Gallery" width="20" height="20" style="position:relative; top:-1px"/>Easy Media Gallery</a>';
			return $context;
		}	
}


// GENERATE POPUP CONTENT
add_action('admin_footer', 'emg_popup_content');	
function emg_popup_content() {

if ( strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post-new.php' ) || strstr( $_SERVER['REQUEST_URI'], 'wp-admin/post.php' ) ) {

if ( get_post_type( get_the_ID() ) != 'easymediagallery' ) {
// START GENERATE POPUP CONTENT

?>
<div id="modal" style="display:none;">
<div id="tinyform" style="width: 550px;">
<form method="post">

<?php 

global $optn;
foreach ( $optn as $value ) {
switch ( $value['type'] ) {
	
case "text":
?>

<div class="sc_input sc_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>	<br />
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php $value['std']; ?>" />
<div class="clearfix"></div>
 </div>
<?php break;	
	
case 'select':
?>

<div class="sc_input sc_select" id="<?php echo $value['id']; ?>_div">
<label class="label_optionttl" for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
<select class="tinymce_select" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
    <?php foreach ( $value['options'] as $state ){ ?>
        <option id="<?php echo $state; ?>" value="<?php echo $state; ?>"><?php echo $state; ?></option>
    <?php }
	?>
</select>

<div class="clearfix"></div>
</div>
<?php
break;	
	
	
case 'selectcat':
?>

<div class="sc_input sc_select" id="<?php echo $value['id']; ?>_div">
<label class="label_optionttl" for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
<?php $states = get_terms( 'emediagallery', array( 'hide_empty' => true ) ); ?>
<select class="tinymce_select" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
    <?php foreach ( $states as $state ){ ?>
        <option id="<?php echo $state->term_id; ?>" value="<?php echo $state->term_id; ?>"><?php echo $state->name; ?></option>
    <?php }
	?>
</select>
<div class="clearfix"></div>
</div>
<?php
break;		


case 'selectmedia':
?>

<div class="sc_input sc_select" id="<?php echo $value['id']; ?>_div">
<label class="label_optionttl" for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	<select class="tinymce_select" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php 

global $post;
$args = array(
  'post_type' => 'easymediagallery',
  'order' => 'ASC',
  'post_status' => 'publish',
  'posts_per_page' => -1
);

$myposts = get_posts( $args );
foreach( $myposts as $post ) :	setup_postdata($post); ?>
<option id="<?php echo $post->ID; ?>" type="text" value="<?php echo $post->ID; ?>" /><?php echo esc_html(esc_js(the_title(NULL, NULL, FALSE))); ?></option>
<?php endforeach; 

/*
Thanks to Kevin Falcoz (aka 0pc0deFR) for this discovery and this patch.
::: esc_html(esc_js(the_title(NULL, NULL, FALSE))); :::
*/

?>
</select>
<div class="clearfix"></div>
</div>
<?php
break;
}} ?>


<div class="sc_input sc_select" id="custom_col_div">
<label class="label_optionttl" for="emgtinymce_custom_columns">Custom columns</label>
                <div>
                <input type="hidden" name="emgtinymce_custom_columns" value="off" />
                <input class="switch" type="checkbox" id="emgtinymce_custom_columns" value="off" /></div>
			<div id="customcolumns" style="margin-top: 10px;">
		<label class="label_suboption">Columns :</label><div>
					
<select class="tinymce_select" name="select_custom_cola" id="select_custom_col">
        <option value="0">Select</option>
		 <option value="1">1</option>
		  <option value="2">2</option>
          <option value="3">3</option>
	</select>					
					</div></div>
<div class="clearfix"></div>
</div>

<div class="sc_input sc_select" id="custom_align_div">
<label class="label_optionttl" for="emgtinymce_custom_align">Custom alignment</label>
                <div>
                <input type="hidden" name="emgtinymce_custom_align" value="off" />
                <input class="switch" type="checkbox" id="emgtinymce_custom_align" value="off" /></div>
			<div id="customalign" style="margin-top: 10px;">
		<label class="label_suboption">Align :</label><div>					
<select class="tinymce_select" name="select_cus_align" id="select_cus_align">
        <option value="0">Select</option>
		   <option value="Center">Center</option>
           <option value="None">None</option>
	</select>					
					</div></div>
<div class="clearfix"></div>
</div>

<div class="sc_input sc_select" id="upd_topro">
<p class="emg_blink" style="color:#F00;">UPGRADE to PRO Version and get Advanced Shortcode, <a target="_blank" href="<?php echo plugins_url( 'includes/images/pro-version-shortcode-manager.png' , dirname(__FILE__) ) ?>" style="text-decoration:underline !important;">Click for Screenshot</a></p>
<div class="clearfix"></div>
</div>

<div class="sc_button1">
<input type="button" value="Insert Shortcode" name="emg_insert_scrt" id="emg_insert_scrt" class="button-secondary" />	
<div class="clearfix"></div>
</div>
<div style="display:none;" id="thisresult"></div>

</form>
</div>
</div>
<?php 
	}
  } //END
}

?>