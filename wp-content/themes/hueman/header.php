<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php wp_title(''); ?></title>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php wp_head(); ?>
    <link rel="stylesheet" href="/wp-content/themes/hueman/styles/patrick.css">
</head>

<body <?php body_class(); ?>>

<?php
if (function_exists("rfchat_load")) {
    echo rfchat_load();
    $newMessageCount = rfchat_newMessagesCount();
}
?>

<div id="wrapper">

    <?php
    $headerAvatarBg =  bp_core_fetch_avatar( array( 'item_id' => get_current_user_id(), 'type' => 'full', 'width' => 500, 'height' => 500, 'html' => false ));
    ?>
    <header id="header"<?php echo (strpos($headerAvatarBg, 'gravatar.com') === FALSE ? ' style="background-image:url(' . $headerAvatarBg . ') !important"' : ''); ?>>

        <?php if (has_nav_menu('topbar')): ?>
            <nav class="nav-container group" id="nav-topbar">
                <div class="nav-toggle"><i class="fa fa-bars"></i></div>
                <div class="nav-text"><!-- put your mobile menu text here --></div>
                <div class="nav-wrap container"><?php wp_nav_menu(array('theme_location'=>'topbar','menu_class'=>'nav container-inner group','container'=>'','menu_id' => '','fallback_cb'=> false)); ?></div>

                <script>
                    // Beta msg
                    jQuery("#menu-menu1").prepend('<div class="beta_msg"></div>')
                    // For the avatar on the menu
                    jQuery("#menu-item-214").html('<a href="/your-profile"><span id="profile_avatar_menu"><?php echo bp_core_fetch_avatar(array('item_id' => get_current_user_id())); ?></div> <span class="fontawesome-text"> Profile</span></a>');
                    jQuery("#profile_avatar_menu img").attr('title', '');

                    // Removes the dot from the menu
                    jQuery.each(jQuery("#menu-menu1 .fontawesome-text"), function (index, item) {
                        var selector = jQuery(item);
                        if(selector.text().length < 3) selector.text('');
                    });

                    // Kanban menu
                    jQuery("#menu-item-195").attr('ng-click', 'newKanban()');

                    // Appends chat button
                  //  jQuery("#menu-menu1").append('<li id="chat_opener_btn" style="float: right" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-121"><a title="Chat" href="#" onclick="chat.open(); return false;"><i class="fa-comment fa"></i><span class="fontawesome-text" id="rfchatNotificationsCount"><?php echo (isset($newMessageCount) && $newMessageCount ? " $newMessageCount " : ''); ?></span></a></li>');
                </script>

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

        <div class="container group">
            <div class="container-inner">

                <div class="group pad">
                    <div id="inHeader_menu">
                        <a href="/members/<?php echo get_userdata(get_current_user_id())->user_nicename; ?>/media">Media</a>
                        <a href="/members/<?php echo get_userdata(get_current_user_id())->user_nicename; ?>/profile/change-avatar">Change background</a>
                    </div>


                    <div class="social-links" id="social-links-vertical">

                        <div style="color:grey;render:block;">
                            <a class="social-tooltip" target="Array" href="#" title="Facebook" rel="nofollow" style="color:grey;render:block;">
                                <i class="fa fa-facebook" style="color:grey;render:block;"></i>
                            </a>
                        </div>
                        <div style="color:grey;render:block;">
                            <a class="social-tooltip" target="Array" href="#" title="Twitter" rel="nofollow" style="color:grey;render:block;">
                                <i class="fa fa-twitter" style="color:grey;render:block;"></i>
                            </a>
                        </div>

                        <div style="color:grey;render:block;">
                            <a class="social-tooltip" target="Array" href="#" title="LinkedIn" rel="nofollow" style="color:grey;render:block;">
                                <i class="fa fa-linkedin" style="color:grey;render:block;"></i>
                            </a>
                        </div>

                        <div style="color:grey;render:block;">
                            <a class="social-tooltip" target="Array" href="#" title="Instagram" rel="nofollow" style="color:grey;render:block;">
                                <i class="fa fa-instagram" style="color:grey;render:block;"></i>
                            </a>
                        </div>

                    </div>

                </div>

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
                    <?php print_r(the_author_meta()); ?>