<div id="chat_container">

    <div id="chat_bar_tab" style="cursor: pointer" onclick="chat.open()">Online</div>

    <div id="chat_bar">
        <div id="chat_conversation_container">
            <?php
            ;
            ?>
        </div>

        <div class="chat_bar_container">
            <?php include( plugin_dir_path( __FILE__ ) . 'chat_inbox.php'); ?>
            <div class="tabs cf">
                <a href="#" class="close wtf" onclick="chat.close(); return false;" title="Close">X</a>
                <a href="#" class="tab-inbox active" onclick="chat.sidebarList_showMessages(); return false;">Inbox</a>
                <a href="#" class="tab-contacts" onclick="chat.sidebarList_showContacts(); return false;">Contacts</a>
            </div>
        </div>
    </div>

    <script>jQuery(".chat_conversation_window section").scrollTop(100000000);</script>

</div>

<script>
    jQuery(function() { if(jQuery.cookie('isChatWindowOpen') == 1) { chat.open(); } });

    jQuery(function() {
        jQuery.each(JSON.parse(jQuery.cookie('chat_conversationTabsAndPosition')), function (index, value) {
            chat.open_conversation(value.partnerId);
        });
    });

    var this_username = '<?php echo get_userdata(get_current_user_id())->user_nicename; ?>', this_avatar = '<?php echo bp_core_fetch_avatar(array('item_id' => get_current_user_id())); ?>', this_avatar_large = '<?php echo bp_core_fetch_avatar(array('item_id' => get_current_user_id())); ?>', this_userId = <?php echo get_current_user_id(); ?>;
</script>