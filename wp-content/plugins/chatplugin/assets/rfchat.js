var s_io = io.connect('http://95.85.16.177:8080/', {
    'reconnect': true,
    'reconnection delay': 500,
    'max reconnection attempts': 50
});

s_io.on('disconnect', function () {
    socket.handle_disconnect();
});

s_io.on('connect_failed', function () {
    socket.handle_disconnect();
});

s_io.on('error', function () {
    socket.handle_disconnect();
});

s_io.on('connect', function () {
    if(socket.hasBeenDisconnected)
    {
        socket.set_userData();
    }
});




//================================================================================================================================================================
// Chat preload
//================================================================================================================================================================
var chat_preload = {
    openChatBars: [],
    addOpenChatBar: function(partner) {
        if(chat_preload.openChatBars.indexOf(partner) == -1) chat_preload.openChatBars.push(partner);
    },
    removeOpenChatBar: function(partner) {
        chat_preload.openChatBars.splice(chat_preload.openChatBars.indexOf(partner), 10);
    }
}






//================================================================================================================================================================
// "On" commands
//================================================================================================================================================================

s_io.on('receive_ownMessage', function (data) {
    var msg_row         = {};
    msg_row.avatar      = data.from.avatar;
    msg_row.partner     = data.from.id;
    msg_row.message     = data.message;
    msg_row.username    = data.from.username;

    if(typeof data.isPhoto != "undefined") msg_row.isPhoto = 1; else msg_row.isPhoto = 0;

    if(data.partner == messages_controller.currentActiveChat)
    {
        messages_controller.clear_notifications(data.from.id);
        messages_controller.render_message_row(msg_row);
    }
});


s_io.on('receive_message', function(data) {
    var msg_row         = {};

    msg_row.avatar      = data.from.avatar;
    msg_row.partner     = data.from.id;
    msg_row.message     = data.message;
    msg_row.username    = data.from.username;

    chat.lastReceivedAlertWasFromUser = data.from.id;

    if(typeof data.isPhoto != "undefined") msg_row.isPhoto = 1; else msg_row.isPhoto = 0;

    chat.render.message_row(msg_row);
    chat.add_notification(data.from.id, data);
    jQuery("#chat-msgReadStatus-" + data.from.id).hide();
});




s_io.on('receive_isTyping', function(data) {
    var msg_window        = jQuery("#chat_conversation_window-" + data.from.id + " section"),
        isTypingContainer = jQuery("#is_typing-" + data.from.id);

    isTypingContainer.show();
    msg_window.scrollTop(100000000);

    clearTimeout(chat.isTyping_timeouts[data.from.id]);
    chat.isTyping_timeouts[data.from.id] = setTimeout(function(){ isTypingContainer.slideUp(); },3000);
});




s_io.on('user_activity', function(data) {
});



s_io.on('clear_tabBarNotifications', function() {
    node.clear_tabBarNotification();
});





//================================================================================================================================================================
// Node/Socket variables
//================================================================================================================================================================

jQuery(window).blur(function(){
    node.hasFocusOnSite = false;
});

jQuery(window).focus(function(){
    node.hasFocusOnSite = true;
    node.clear_tabBarNotification();
    s_io.emit('clear_tabBarNotifications');
});

var node = {
    tabBarNotificationCount: 0,
    hasFocusOnSite: true,

    add_tabBarNotification: function() {
//        if(node.hasFocusOnSite) return true;
//
//        node.tabBarNotificationCount++;
//        jQuery("title").text('[+' + node.tabBarNotificationCount + '] ' + originalPageTitle);
//        favicon.badge(node.tabBarNotificationCount);
    },

    clear_tabBarNotification: function() {
//        node.tabBarNotificationCount = 0;
//        favicon.badge(0);
//        jQuery("title").text(originalPageTitle)
    }
};


var socket = {
    hasBeenDisconnected: false,

    my: {
        id: this_userId,
//        key: this_chatUserKey,
        username: this_username,
        avatar: this_avatar
    },

    set_userData: function() {
        s_io.emit('set_userData', socket.my.id);
    },

    send_message: function(data) {
        s_io.emit('send_message', data)
    },

    send_isTyping: function(data) {
//        s_io.emit('send_isTyping', data)
    },

    handle_disconnect: function() {
        socket.hasBeenDisconnected  = true;
    }
};

socket.set_userData();










//================================================================================================================================================================
// Messages
//================================================================================================================================================================

var messages = {

    send_message: function (to, message) {
//        jQuery.get('/index.php?rf_chat=userData&userId=' + partner, function(userData) {
                jQuery.post('/index.php?rf_chat=send_message', {
                to: to,
                message: message
            },
            function (response) {
                if (response.status) {
                    topSiteAlert(response.data, 'error');
                    return;
                }

                $("#writeMessageArea").remove();
                $("#messages_container").prepend(response);
            });
    },

    send_messageReply: function (to) {
        $.post('/messages/messages_actions_controller/send_message', {
                to: to,
                isReply: 1,
                message: $("#message_textBox-" + to).val()
            },
            function (response) {
                if (typeof response.status == 'string') {
                    topSiteAlert(response.data, 'error');
                    return;
                }

                $("#writeMessageArea").remove();
                $("#messages_container").prepend(response);
            });
    },

    deleteUserFromInbox: function (userId) {
        $.get('/messages/messages_actions_controller/deleteUserFromInbox?userIdToRemove=' + userId);
        chat.closeTab(userId);
        $("#chat_inbox_sg_row-" + userId).remove();
    }
};












//================================================================================================================================================================
// Chat
//================================================================================================================================================================

var chat = {
    lastReceivedAlertWasFromUser:0,
    activeConversation: 0,
    isChatWindowOpen: false,
    open_conversation_skipStuff: false,
    isTyping_timeouts: {},


    open: function(callback) {
        jQuery("body").addClass('chat_open');

        jQuery.cookie('isChatWindowOpen', 1, {path: '/'});

        chat.isChatWindowOpen = true;
        jQuery("#dropdown_container_inner-chat .badge").text('');
//        jQuery.get('/chat/chat_actions_controller/clear_AllNotifications');
        jQuery("#dropdown_container_inner-chat.active").removeClass('active');
        if(callback) callback();
//        lightbox.onEscAction = chat.close;

        if(chat.activeConversation) jQuery("#sending_area-" + chat.activeConversation + " textarea").focus();

        if(chat.lastReceivedAlertWasFromUser) chat.open_conversation(chat.lastReceivedAlertWasFromUser);

//        jQuery("#chat_bar_tab").attr('onClick', 'chat.close(); return false;')
        jQuery("#chat_opener_btn a").attr('onClick', 'chat.close(); return false;');

        // Remove buddpress tooltip
        jQuery("#chat_container img").attr('title', '');
    },


    close: function() {
        jQuery("body").removeClass('chat_open');

        jQuery.cookie('isChatWindowOpen', 0, {path: '/'});

        chat.isChatWindowOpen = false;
        jQuery("#chat_container .messages_list .row").removeClass('active');
//        lightbox.onEscAction = function() {};

//        jQuery("#chat_bar_tab").attr('onClick', 'chat.open(); return false;')
        jQuery("#chat_opener_btn a").attr('onClick', 'chat.open(); return false;')
    },


    calculateConversationsPosition: function() {
        var windows     = jQuery("#chat_conversation_container .chat_conversation_window"),
            spacing     = 3,
            currWidth   = spacing;

        jQuery.each(windows, function (index, item) {
            var selector    = jQuery(item);
            selector.css('right', currWidth + 'px');
            currWidth = currWidth + selector.width() + spacing;
        });

        chat.saveConversationTabsAndPosition();
    },


    saveConversationTabsAndPosition: function() {
        var saveEverything = function() {
            var container   = jQuery("#chat_conversation_container");
            var windows     = jQuery("#chat_conversation_container .chat_conversation_window");
            var storeData   = [];

            jQuery.each(windows, function (index, item) {
                var selector    = jQuery(item);
                var data        = {};
                data.position   = parseInt(selector.css('right'), 10);
                data.partnerId  = selector.attr('data-partner');
                data.minimized  = (selector.hasClass('minimized') ? 1 : 0);

                storeData.push(data);
            });

            jQuery.cookie('chat_conversationTabsAndPosition', JSON.stringify(storeData), {path: '/'});
        }

        saveEverything();
        setTimeout(saveEverything, 500);
        setTimeout(saveEverything, 1000);
    },


    minimizeTab: function(partner) {
        var chatBarEl       = jQuery("#chat_conversation_window-" + partner);
        chatBarEl.addClass('minimized');
        chat.saveConversationTabsAndPosition();
        chat.isChatWindowOpen   = false;
        chat.activeConversation = 0;
        jQuery("#chat_container .messages_list .row").removeClass('active');
        chat.calculateConversationsPosition();
        chat_preload.removeOpenChatBar(partner);
        chat.setActiveTab(false);
    },

    maximizeTab: function(partner) {
        jQuery("#chat_conversation_window-" + partner).removeClass('minimized');
        chat.saveConversationTabsAndPosition();
        chat.open_conversation_skipStuff = true;
        chat.open_conversation(partner);
        chat.calculateConversationsPosition();
        chat_preload.addOpenChatBar(partner);
    },

    setActiveTab: function(partner) {
        jQuery(".chat_conversation_window").removeClass('active');
        if(!partner) return false;
        jQuery("#chat_conversation_window-" + partner).addClass('active');
    },

    closeTab: function(partner) {
        jQuery("#chat_conversation_window-" + partner).remove();
        chat.calculateConversationsPosition();
        chat_preload.removeOpenChatBar(partner);
    },

    activeTabChanged: function(partner) {
        chat.open_conversation_skipStuff = true;
        chat.open_conversation(partner);
    },

    open_conversation: function(partner) {

        chat.lastReceivedAlertWasFromUser = 0;

        this.setActiveTab(partner);
        this.clear_notifications(partner);
        this.activeConversation = partner;
        var partnerWindow = jQuery("#chat_conversation_window-" + partner);
        s_io.emit('set_activeConversation', {activeConversation:chat.activeConversation});

        jQuery("#chat_container .messages_list .row").removeClass('active');
        jQuery("#chat_container .messages_list #chat_inbox_sg_row-" + partner).addClass('active');

        if(partnerWindow.length)
        {
            if(!chat.open_conversation_skipStuff)
            {
                chat.maximizeTab(partner);
                chat.calculateConversationsPosition();
            }

            jQuery("#sending_area-" + partner + " textarea").focus();
            jQuery("#chat_conversation_window-" + partner + ' section').scrollTop(100000000);
//            lightbox.closeLoading();
        }
        else
        {
            chat.server.get_conversationWindow(partner);
        }

        chat.open_conversation_skipStuff = false;
        chat_preload.addOpenChatBar(partner);
    },

    open_conversation_viaContactsList: function(userId)
    {
//        lightbox.openLoading();
        chat.sidebarList_showMessages();
        chat.open_newMessageArea(userId);
    },

    sidebarList_showContacts: function() {
        jQuery("#chat_messages_list").hide();
        jQuery("#chat_contacts_list").show();

        jQuery("#chat_container #chat_bar .tabs .tab-inbox").removeClass('active');
        jQuery("#chat_container #chat_bar .tabs .tab-contacts").addClass('active');
    },

    sidebarList_showMessages: function() {
        jQuery("#chat_contacts_list").hide();
        jQuery("#chat_messages_list").show();

        jQuery("#chat_container #chat_bar .tabs .tab-contacts").removeClass('active');
        jQuery("#chat_container #chat_bar .tabs .tab-inbox").addClass('active');
    },

    open_newMessageArea: function(partner) {
        if(!jQuery("#chat_messages_list #chat_inbox_sg_row-" + partner).length)
        {
            jQuery.get('/index.php?rf_chat=userData&userId=' + partner, function(userData) {
                jQuery("#chat_messages_list").prepend('<div class="row cf active" onclick="chat.open_conversation(' + partner + ');" id="chat_inbox_sg_row-' + partner + '"><div class="left"><div class="avatar_container">' + userData.thumb + '</div></div><div class="right"><div class="top">' + userData.username + '</div><div class="bottom"></div></div><div class="notification_container"></div></div>');
            });
        }
        chat.open(function() {
            chat.open_conversation(partner);
        })
    },

    isTyping: function(e, partner) {
        chat.is_typing(partner);
        if(e.keyCode == 13 && !e.shiftKey) {
            chat.send_message(partner);
            return false;
        }
    },


    send_message: function(partner) {
        // Variables
        var msg_textarea    = jQuery("#sending_area-" + partner + " textarea");
        var message         = msg_textarea.val();
        var msg_row         = {};
        var socketData      = {};

        // Render it to sender
        msg_row.message     = message;
        msg_row.partner     = partner;
        msg_row.avatar      = socket.my.avatar;
        msg_row.username    = socket.my.username;

        msg_textarea.val('');
        chat.render.message_row(msg_row);

        // Send it over
        socketData.senderKey    = socket.my.id;
        socketData.message      = message;
        socketData.toId         = partner;
        socket.send_message(socketData);

        // Save
        messages.send_message(partner, message);
    },



    is_typing: function(partner) {
        // Variables
        var msg_row         = {};
        var socketData      = {};

        // Render it to sender
        msg_row.partner     = partner;
        msg_row.username    = socket.my.username;

        // Send it over
        socketData.senderKey    = socket.my.key;
        socketData.toId         = partner;
        socket.send_isTyping(socketData);
    },


    clear_notifications: function(partner) {
        jQuery.get('/index.php?rf_chat=clear_notifications&partner=' + partner);
//        jQuery.get('/chat/chat_actions_controller/clear_notifications?partner=' + partner);

        var notificationCount       = parseInt(jQuery("#chat_inbox_sg_row-" + partner + ' .notification_container .count').text(), 10);
        var totNotificationCount    = parseInt(jQuery("#rfchatNotificationsCount").text());
        var remainingTotNotif       = totNotificationCount - notificationCount;
        jQuery("#chat_inbox_sg_row-" + partner + ' .notification_container').empty();
        jQuery("#minimized_bar-" + partner + ' .notification_container').empty();

        if(!notificationCount) return true; // If there was no notifications, no need to do the rest

        jQuery("#rfchatNotificationsCount").text(remainingTotNotif);

        if(remainingTotNotif < 1)
        {
            jQuery("#dropdown_container_inner-chat .badge").text('');
            jQuery("#dropdown_container_inner-chat.active").removeClass('active');
        }

    },


    add_notification: function(partner, data) {
        partner = parseInt(partner);

        node.add_tabBarNotification();

        // If the user is chating with this "partner", remove alerts and return
        if(chat.activeConversation == partner || chat_preload.openChatBars.indexOf(partner) > -1)
        {
            jQuery.get('/chat/chat_actions_controller/clear_partnerNotifications?partner=' + partner);
            return true;
        }

        // Here we check if there is a row for this user or not, if not, we add
        if(!jQuery("#chat_messages_list #chat_inbox_sg_row-" + partner).length)
        {
            jQuery("#chat_messages_list").prepend('<div class="row cf" onclick="chat.open_conversation(' + partner + ');" id="chat_inbox_sg_row-' + partner + '"><div class="left"><div class="avatar_container">' + data.from.avatar + '</div></div><div class="right"><div class="top">' + data.from.username + '</div><div class="bottom">' + data.message + '</div></div><div class="notification_container"></div></div>');
        }

        // If the chat window isn't open, we add the notification to the dropdown badge thing
//        if(!chat.isChatWindowOpen)
//        {
        var badge_count_container   = jQuery("#dropdown_container_inner-chat .badge");
        var badge_count             = parseInt(badge_count_container.text());
        if(!badge_count || isNaN(badge_count)) badge_count = 0;

        badge_count_container.text(badge_count + 1);
        jQuery("#dropdown_container_inner-chat").addClass('active');
//        }

        // Everything above Ok, so we continue
        var count_containerContainer    = jQuery("#chat_inbox_sg_row-" + partner + ' .notification_container');
        var count_container             = jQuery("#chat_inbox_sg_row-" + partner + ' .notification_container .count');
        if(!count_container.length)
        {
            count_containerContainer.html('<div class="count">1</div>');
        }
        else
        {
            var notification_count = parseInt(count_container.text());
            count_container.text(notification_count + 1);
        }


        if(!jQuery("#minimized_bar-" + partner + ' .notification_container .count').length)
        {
            jQuery("#minimized_bar-" + partner + ' .notification_container').html('<div class="count">1</div>');
        }
        else
        {
            jQuery("#minimized_bar-" + partner + ' .notification_container .count').text(notification_count + 1);
        }

    },

    server: {

        get_conversationWindow: function(partner) {
            jQuery.get('/index.php?rf_chat=open_conversation&partner=' + partner,
//            jQuery.get('/chat/chat_interface_controller/open_conversation?partner=' + partner,
                function(response) {
                    jQuery("#chat_conversation_container").prepend(response);
                    jQuery("#sending_area-" + partner + " textarea").focus();
                    jQuery("#chat_conversation_window-" + partner + ' section').scrollTop(100000000);
                    chat.calculateConversationsPosition();
                }
            );
        }
    },



    render: {

        message_row: function(data) {
            if(!data.message) return false;
            jQuery("#chat_conversation_window-" + data.partner + " .chat_attractedMsg").hide();

            var postedDate  = new Date(new Date().getTime());
            data.datetime = postedDate.getDate() + '/' + postedDate.getMonth() + ' ' + postedDate.getHours() + ':' + postedDate.getMinutes();
            var msg_window = jQuery("#chat_conversation_window-" + data.partner + " section");

            msg_window.append('<div class="row cf"><div class="left">' + data.avatar + '</div><div class="right"><div class="top"><a href="/' + data.username + '">' + data.username + '</a><span class="datetime"> · ' + data.datetime + '</span></div><div class="bottom">' + data.message + '</div></div></div>');

            var isTypingContainer = jQuery("#is_typing-" + data.partner);
            isTypingContainer.hide();
            isTypingContainer.appendTo(isTypingContainer.parent());


            msg_window.scrollTop(100000000);
        }
    },

    user: {

        block: function(userId) {
            jQuery("#chat_conversation_window-" + userId + ' .user_blocked').show();
            accountConnect_client.user.block(userId);
        },

        unBlock: function(userId) {
            jQuery("#chat_conversation_window-" + userId + ' .user_blocked').hide();
            accountConnect_client.user.unBlock(userId);
        }
    }
};