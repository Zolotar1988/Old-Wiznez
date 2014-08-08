<?php
/*
Plugin Name: RF Chat
Description: A chat plugin for WP
Version: 0.9
Author: Revolver Factory
*/




function rfchat_ajax_queryVars($vars) {
    $vars[] = 'partner';
    $vars[] = 'userId';
    $vars[] = 'rf_chat';
    return $vars;
}
add_filter('query_vars', 'rfchat_ajax_queryVars');



function open_conversation($rfchat, $wp)
{
    $partner = $wp->query_vars['partner'];
    if(!$partner) die;

    $message = $rfchat->messages_conversation(get_current_user_id(), $partner);
    include( plugin_dir_path( __FILE__ ) . 'views/conversation_window.php');

    die();
}

function clear_notifications($rfchat, $wp)
{
    $partner = $wp->query_vars['partner'];
    $rfchat->clearNotifications(get_current_user_id(), $partner);
    exit('Completed');
}

function userData($rfchat, $wp)
{
    header('Content-type: application/json');
    $userId             = $wp->query_vars['userId'];
    $userData           = new stdClass();
    $userData->username = get_userdata($userId)->user_nicename;
    $userData->thumb    = bp_core_fetch_avatar(array('item_id' => $userId));
    exit(json_encode($userData));
}

function send_message($rfchat, $wp)
{
    header('Content-type: application/json');
    $to         = $_POST['to'];
    $message    = $_POST['message'];
    $isReply    = FALSE;
    $response   = $rfchat->send_message(get_current_user_id(), $to, $message, $isReply);
    exit($response);
}


function rfchat_parse_request($wp) {
    if(!array_key_exists('rf_chat', $wp->query_vars)) return FALSE;

    # Initialize rf chat
    if(!class_exists('Rfchat')) include( plugin_dir_path( __FILE__ ) . 'inc/Rfchat.php');
    $rfchat = new Rfchat();

    switch($wp->query_vars['rf_chat'])
    {
        case 'open_conversation':
            open_conversation($rfchat, $wp);
            break;

        case 'clear_notifications':
            clear_notifications($rfchat, $wp);
            break;

        case 'userData':
            userData($rfchat, $wp);
            break;

        case 'send_message':
            send_message($rfchat, $wp);
            break;
    }
}
add_action('parse_request', 'rfchat_parse_request');





// This initializes the chat
function rfchat_load() {

    # CSS and JS
    wp_register_style('rfchat_css', 'http://95.85.16.177/wp-content/plugins/chatplugin/assets/rfchat.css');
    wp_enqueue_style('rfchat_css');

    wp_register_script('rfchat_socketio', 'http://decate.no/nodejs/client/socket.io.min.js');
    wp_enqueue_script('rfchat_socketio');

    wp_register_script('rfchat_js', 'http://95.85.16.177/wp-content/plugins/chatplugin/assets/rfchat.js');
    wp_enqueue_script('rfchat_js');

    # Initializes the class
    include( plugin_dir_path( __FILE__ ) . 'inc/Rfchat.php');
    $rfchat = new Rfchat();
    $rfchat->setChatUserData(get_current_user_id());

    # The main window of the chat
    include( plugin_dir_path( __FILE__ ) . 'views/chat_main_window.php');

    # Notification count
    $xxxxxx = 123123123;
}


// Returns new message count
function rfchat_newMessagesCount() {
    if(!class_exists('Rfchat')) include( plugin_dir_path( __FILE__ ) . 'inc/Rfchat.php');
    $rfchat = new Rfchat();
    return $rfchat->newMessagesCount(get_current_user_id());
}


// Function to load previous conversations
function rfchat_openActiveConversationTabs()
{

}