<?php
/*
Template Name: KANBAN 2
*/
define('WP_USE_THEMES', true);
?>
<?php 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
include 'header2.php';//get_header(); 
?> 
<?php //get_header(); ?>

<div id="content">

<!-- This is first column -->
<div id="column_1" class="column">
<h1>TO DO</h1>
<?php //pre_get_posts(); ?>
<?php query_posts( 'post_type=task&category_name=todo'); ?>	
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  <div id="item_1" class="dragbox" style="background-color:<?php print_custom_field('task_label'); ?>">
    <!-- this one is first item of 1st column -->
 <h2>Title</h2>
    <div class="dragbox-content" style="background-color:<?php print_custom_field('task_label'); ?>">
    
    <strong>Start Date:</strong> <?php print_custom_field("task_start_date:datef", ""); ?><br />
		<strong>Due Date</strong> <?php print_custom_field('task_due_date'); ?><br />
		<strong>Members:</strong> <?php print_custom_field("task_member:userinfo"); ?><br />
		<strong>Labels</strong> <?php print_custom_field('task_label'); ?><br />
		<strong>Attachments:</strong> <?php $images = get_custom_field('task_attachment:to_image_src'); 
foreach ($images as $img) {
	printf('<img src="%s"/>', $img);
}
?><br />

  </div>
 </div>
<?php endwhile; // end of the loop. ?>
</div>
 
<!-- second -->
<!-- This is first column -->
<div id="column_1" class="column">
<h1>DOING</h1>
<?php //pre_get_posts(); ?>
<?php query_posts( 'post_type=task&category_name=doing'); ?>	
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  <div id="item_1" class="dragbox" style="background-color:<?php print_custom_field('task_label'); ?>">
    <!-- this one is first item of 1st column -->
 <h2>Title</h2>
    <div class="dragbox-content" style="background-color:<?php print_custom_field('task_label'); ?>">
    
    <strong>Start Date:</strong> <?php print_custom_field("task_start_date:datef", ""); ?><br />
		<strong>Due Date</strong> <?php print_custom_field('task_due_date'); ?><br />
		<strong>Members:</strong> <?php print_custom_field("task_member:userinfo"); ?><br />
		<strong>Labels</strong> <?php print_custom_field('task_label'); ?><br />
		<strong>Attachments:</strong> <?php $images = get_custom_field('task_attachment:to_image_src'); 
foreach ($images as $img) {
	printf('<img src="%s"/>', $img);
}
?><br />

  </div>
 </div>
<?php endwhile; // end of the loop. ?>
</div>


<!--third-->
<!-- This is first column -->
<div id="column_1" class="column">
<h1>DONE</h1>
<?php //pre_get_posts(); ?>
<?php query_posts( 'post_type=task&category_name=done'); ?>	
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
  <div id="item_1" class="dragbox" style="background-color:<?php print_custom_field('task_label'); ?>">
    <!-- this one is first item of 1st column -->
 <h2>Title</h2>
    <div class="dragbox-content" style="background-color:<?php print_custom_field('task_label'); ?>">
    
    <strong>Start Date:</strong> <?php print_custom_field("task_start_date:datef", ""); ?><br />
		<strong>Due Date</strong> <?php print_custom_field('task_due_date'); ?><br />
		<strong>Members:</strong> <?php print_custom_field("task_member:userinfo"); ?><br />
		<strong>Labels</strong> <?php print_custom_field('task_label'); ?><br />
		<strong>Attachments:</strong> <?php $images = get_custom_field('task_attachment:to_image_src'); 
foreach ($images as $img) {
	printf('<img src="%s"/>', $img);
}
?><br />

  </div>
 </div>
<?php endwhile; // end of the loop. ?>
</div>

<hr/>
<?php rtmedia_gallery(); ?>
<hr/>





</div>
 <script src="js/jquery-1.8.3.min.js"></script>
    <script src="js/jquery-ui-1.10.3.custom.min.js"></script>
    <script src="js/jquery.ui.touch-punch.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/bootstrap-select.js"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/flatui-checkbox.js"></script>
    <script src="js/flatui-radio.js"></script>
    <script src="js/jquery.tagsinput.js"></script>
    <script src="js/jquery.placeholder.js"></script>
<?php //get_sidebar(); ?>




<?php get_footer(); ?>
