<!DOCTYPE html> 
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php wp_title(''); ?></title>

	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	
	<?php wp_head(); ?>
	<style>
.cloudMenu
{
display: none;
}

.column{
    width:32%;
    margin-right:.5%;
    float:left;
        min-height: 300px;
}
.column .dragbox{
    margin:5px;
    background:#fff;
    position:relative;
    border:1px solid #ddd;
}
.column .dragbox h2{
    margin:0;
    font-size:12px;
    padding:5px;
    background:#f0f0f0;
    color:#000;
    border-bottom:1px solid #eee;
    font-family:Verdana;
    cursor:move;
}
.dragbox-content{
    background:#fff;
    min-height:100px; margin:5px;
    font-family:'Lucida Grande', Verdana; font-size:0.8em; line-height:1.5em;
}
.column  .placeholder{
    background: #f0f0f0;
    border:2px dashed #883533;
}

</style>
	<script>
	 $('.column').sortable({
    connectWith: '.column',
    handle: 'h2',
    cursor: 'move',
    placeholder: 'placeholder',
    forcePlaceholderSize: true,
    opacity: 0.4,
        stop: function(event, ui){
            //saveState();
        }
})
.disableSelection();
	</script>
</head>

<body <?php body_class(); ?>>

<div id="wrapper">

	<header id="header">
	
		<?php if (has_nav_menu('topbar')): ?>
			<nav class="nav-container group" id="nav-topbar">
				<div class="nav-toggle"><i class="fa fa-bars"></i></div>
				<div class="nav-text"><!-- put your mobile menu text here --></div>
				<div class="nav-wrap container"><?php wp_nav_menu(array('theme_location'=>'topbar','menu_class'=>'nav container-inner group','container'=>'','menu_id' => '','fallback_cb'=> false)); ?></div>
				
				<div class="container">
					<div class="container-inner">		
						<div class="toggle-search"><i class="fa fa-search"></i></div>
						<div class="search-expand">
							<div class="search-expand-inner">
								<?php get_search_form(); ?>
							</div>
						</div>
					</div><!--/.container-inner-->
				</div><!--/.container-->
				
			</nav><!--/#nav-topbar-->
		<?php endif; ?>
		
		
				
				<?php if (has_nav_menu('header')): ?>
					<nav class="nav-container group" id="nav-header">
						<div class="nav-toggle"><i class="fa fa-bars"></i></div>
						<div class="nav-text"><!-- put your mobile menu text here --></div>
						<div class="nav-wrap container"><?php wp_nav_menu(array('theme_location'=>'header','menu_class'=>'nav container-inner group','container'=>'','menu_id' => '','fallback_cb'=> false)); ?></div>
					</nav><!--/#nav-header-->
				<?php endif; ?>
				
			</div><!--/.container-inner-->
		</div><!--/.container-->
		
	</header><!--/#header-->
	
	<div class="container" id="page">
		<div class="container-inner">
			<div class="main">
				<div class="main-inner group">
