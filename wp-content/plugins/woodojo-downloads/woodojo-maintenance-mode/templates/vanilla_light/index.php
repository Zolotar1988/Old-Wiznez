<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Template Name: Vanilla Light
 * Description: Vanilla Light theme for Maintenance Mode.
 * Version: 1.0.1
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php $this->page_title(); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo esc_attr( esc_url( $settings['path'] . 'style.css' ) ); ?>" media="screen" />
<link href="http://fonts.googleapis.com/css?family=Oswald:400,300|Muli" rel="stylesheet" type='text/css'>
</head>
<body <?php body_class(); ?>>
<div id="wrapper">
	<div id="content" class="col-full">
    	<div id="main">
	       	<div id="intro" class="block">
	    		<h3><span><?php echo $this->the_title(); ?></span></h3>
	    		<p><?php echo $this->the_note(); ?></p>
	    	</div><!-- #intro -->
   		</div>
    </div><!-- /#content -->
    <a id="login" href="<?php echo esc_url( wp_login_url( site_url( '/' ) ) ); ?>" title="<?php esc_attr_e( 'Log in to your WordPress dashboard', 'woodojo-maintenance-mode' ); ?>"><?php _e( 'Log In', 'woodojo-maintenance-mode' ); ?></a>
</div><!-- /#wrapper -->
</body>
</html>