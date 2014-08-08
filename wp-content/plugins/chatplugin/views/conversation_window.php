<?php
$style          = (isset($style) ? $style : '');
$minimizedCls   = (isset($minimized) && $minimized ? ' minimized' : '');
?>

<div class="chat_conversation_window<?php echo $minimizedCls; ?>" id="chat_conversation_window-<?php echo $partner; ?>" data-partner="<?php echo $partner; ?>" style="<?php echo $style; ?>">
    <div class="minimized_bar" id="minimized_bar-<?php echo $partner; ?>">
        <header class="chatBarHeader" onclick="chat.maximizeTab(<?php echo $partner; ?>);">
            <div class="left"><?php echo bp_core_fetch_avatar(array('item_id' => $partner)); ?></div>
            <div class="right">
                <a href="#" onclick="chat.maximizeTab(<?php echo $partner; ?>); return false;"><?php echo $rfchat->lang('icon-user'); ?><span><?php echo get_userdata($partner)->user_nicename; ?></span></a>
                <span class="notification_container"><?php echo (isset($rfchat->initialNewMsgFromUser[$partner]) && $rfchat->initialNewMsgFromUser[$partner] > 0 ? '<div class="count">' . $rfchat->initialNewMsgFromUser[$partner] . '</div>' : ''); ?></span>
            </div>
        </header>
    </div>

    <div class="container">
        <header class="chatBarHeader">
            <div class="left"><?php echo bp_core_fetch_avatar(array('item_id' => $partner, TRUE)); ?></div>
            <div class="right">
                <a href="/<?php echo get_userdata($partner)->user_nicename; ?>"><?php echo $rfchat->lang('icon-user'); ?><span><?php echo get_userdata($partner)->user_nicename; ?></span></a>

                <?php
                /*
                 *      <a href="#" title="<?php echo $rfchat->lang('chat-block'); ?>" class="wtf block_user_btn-<?php echo $partner; ?>" onclick="chat.user.block(<?php echo $partner; ?>); return false"><?php echo $rfchat->lang('icon-block'); ?></a>
                <a href="#" title="<?php echo $rfchat->lang('chat-removeBlock'); ?>" class="wtf unBlock_user_btn-<?php echo $partner; ?>" onclick="chat.user.unBlock(<?php echo $partner; ?>); return false"><?php echo $rfchat->lang('icon-unBlock'); ?></a>

                <a title="<?php echo $rfchat->lang('chat-reportUser'); ?>" class="wtf reportUserLink" href="#" onclick="reporting.chat_showReportTab(<?php echo $partner; ?>); return false"><?php echo $rfchat->lang('icon-report'); ?></a>
                <a title="<?php echo $rfchat->lang('chat-deleteFromInbox'); ?>" class="wtf deleteFromInboxLink" href="#" onclick="messages.deleteUserFromInbox(<?php echo $partner; ?>); return false"><?php echo $rfchat->lang('icon-chat-delFromInbox'); ?></a>

                 */
                ?>
                <a title="<?php echo $rfchat->lang('chat-close'); ?>" class="wtf" href="#" onclick="chat.closeTab(<?php echo $partner; ?>); return false"><i class="fa-times fa" style=" font-size: 22px; position: relative; top: 3px; "></i></a>

                <?php // <span>·</span><a href="#" onclick="chat.delete_conversation(<?php echo $partner;)">Delete conversation</a>; ?>
            </div>
        </header>


        <section onclick="chat.activeTabChanged(<?php echo $partner; ?>)">
            <?php
            $messageCount = count($message);

            foreach($message as $m)
            {
                ?>
                <div class="row cf <?php echo ($m->fromId == get_current_user_id() ? 'mine' : 'notMine'); ?>">
                    <div class="left"><div class="overlay"></div><?php echo bp_core_fetch_avatar(array('item_id' => $m->fromId)); ?></div>
                    <div class="right">
                        <div class="top"><?php echo get_userdata($m->fromId)->user_nicename; ?><span class="datetime"> - <?php echo  date("d/m H:i", strtotime($m->datetime)); ?></span></div>
                        <div class="bottom"><?php echo $m->message; ?></div>
                    </div>
                </div>
                <?php
            }
            ?>

            <?php
            /* <div class="row is_typing cf" id="is_typing-<?php echo $partner; ?>">
                <div class="left"><div class="overlay"></div><?php echo bp_core_fetch_avatar(array('item_id' => $partner)); ?></div>
                <div class="right">
                    <div class="top"><?php echo get_userdata($partner)->user_nicename; ?></div>
                </div>
            </div>*/
            ?>

        </section>


        <footer onclick="chat.activeTabChanged(<?php echo $partner; ?>)">
            <div class="sending_area" id="sending_area-<?php echo $partner; ?>">
                <textarea class="sized_fullWidth" onkeypress="return chat.isTyping(event, <?php echo $partner; ?>)"></textarea>
            </div>
        </footer>
    </div>
</div>

<script>
    // Remove buddpress tooltip
    jQuery("#chat_container img").attr('title', '');
</script>

<?php if(!isset($minimized) || !$minimized) { ?><script>chat_preload.addOpenChatBar(<?php echo $partner; ?>)</script><?php } ?>