<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Template Name: Forrest
 * Description: Forrest theme for Launch Pad.
 * Version: 1.0.0
 */
global $woodojo_launch_pad;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php $this->page_title(); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo esc_attr( $settings['launchpad_path'] ); ?>style.css" media="screen" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link href='http://fonts.googleapis.com/css?family=Dosis:400,500,600,700,800' rel='stylesheet' type='text/css'>
<?php $this->maintenance_head(); ?>
<?php $woodojo_launch_pad->countdown_javascript_init(); ?>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
	<div id="header" class="col-full">
		<div id="logo">
			<?php $woodojo_launch_pad->logo_image_markup(); ?>
		</div><!-- /#logo -->
		<?php $woodojo_launch_pad->social_profiles_markup(); ?>
	</div><!-- /#header -->
	<div id="content" class="col-full">
    	<div id="main">
	       	<div id="intro" class="block">
	    		<h3><span><?php echo $this->the_title(); ?></span></h3>
	    		<p><?php echo $this->the_note(); ?></p>
	    	</div><!-- #intro -->
    		<?php $woodojo_launch_pad->countdown_markup(); ?>
    		<?php $woodojo_launch_pad->newsletter_subscribe_markup(); ?>
   		</div><!--/#main-->
    </div><!-- /#content -->
	<div id="footer" class="col-full">
		<div id="copyright">
			<?php $woodojo_launch_pad->custom_footer_text_markup(); ?>
		</div>
	</div><!-- /#footer  -->
</div><!-- /#wrapper -->
<?php $this->maintenance_end_body(); ?>
</body>
</html>