<?php
/**
 * Plugin Name: Kanban for WizNez
 * Version: 1.0
 * Author: Dzunenko, Sergey & Tarasov, Dmitriy
 */

include 'classes/class.kanban.settings.php';
include 'classes/class.kanban.templater.php';

function add_menu_link()
{
    $page_title = 'Kanban settings';
    $menu_title = 'Kanban';
    $capability = 8;
    $menu_slug = 'Kanban';
    $icon = plugins_url('img/editcopy.png',__FILE__);
    $init_function = array('KanbanSettings', 'initialize_settings');

    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $init_function, $icon);
}
add_action('admin_menu', 'add_menu_link');
add_action('init', array('KanbanTemplater','get_instance'));
