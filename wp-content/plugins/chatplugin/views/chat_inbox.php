<div class="messages_list" id="chat_messages_list">
    <?php
    $msgsFromUserCount_arr = array();
    foreach($rfchat->messages(get_current_user_id()) as $message)
    {
        ?>
        <div class="row cf <?php echo (isset($message->isNew) && $message->isNew ? 'new' : 'read'); ?>" onclick="chat.open_conversation(<?php echo $message->fromId; ?>);" id="chat_inbox_sg_row-<?php echo $message->fromId; ?>">
            <div class="left"><div class="avatar_container"><div class="overlay"></div><?php echo bp_core_fetch_avatar(array('item_id' => $message->fromId)); ?></div></div>
            <div class="right">
                <div class="right_inner">
                    <div class="container">
                        <span class="top cf"><span class="username"><?php echo get_userdata($message->fromId)->user_nicename; ?></span></span>
                        <span class="bottom cf"><?php echo ''; ?></span>
                    </div>
                </div>
            </div>
            <div class="notification_container"><?php echo (0 ? '<div class="count">' . $message->msgsFromUserCount . '</div>' : ''); ?></div>
        </div>
        <?php
    }
    ?>
</div>



<div class="contacts_list" id="chat_contacts_list" style="display: none">
    <?php
    foreach($rfchat->contacts(get_current_user_id()) as $contact)
//    foreach(array() as $contact)
    {
        ?>
        <div class="row cf" onclick="chat.open_conversation_viaContactsList(<?php echo $contact; ?>);">
            <div class="left"><div class="avatar_container"><div class="overlay"></div><?php echo bp_core_fetch_avatar(array('item_id' => $contact)); ?></div></div>
            <div class="right">
                <div class="right_inner">
                    <div class="container">
                        <span class="top cf"><span class="username"><?php echo get_userdata($contact)->user_nicename; ?></span></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>