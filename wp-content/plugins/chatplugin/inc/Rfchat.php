<?php

class Rfchat
{
    public function __construct()
    {
        include "/var/www/wp-config.php";
        $this->initialNewMsgFromUser    = array();
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        $single_server = array('host'=> '127.0.0.1', 'port' => 6379);

        include( plugin_dir_path( __FILE__ ) . 'predis/autoload.php');
        $this->redis = new Predis\Client($single_server);
    }



    //================================================================================================================================================================
    // Compability/temporary fixes due to CI migration
    //================================================================================================================================================================
    function lang($str)
    {
        return (substr($str, 0, 3) == 'icon' ? '' : '');
    }

    function setChatUserData($userId)
    {
        $userData           = new stdClass();
        $userData->id       = $userId;
        $userData->username = get_userdata($userId)->user_nicename;
        $userData->thumb    = bp_core_fetch_avatar(array('item_id' => $userId));
        $this->redis->set('rfchat:users:userData:' . $userId, json_encode($userData));
//        wp_die(var_dump($this->redis->get('rfchat:users:userData:' . $userId)));
    }


    //================================================================================================================================================================
    // Messages
    //================================================================================================================================================================

    # Messages are stored in a sender-to-sender key/val store, with the id of the lowest sender first
    # and the id that's largest second.
    # User 4 sending a message to id 7 will store the messages in "messages:4-7", the message from 7 to 4 will be stored there as well

    function redis_messsagesBetweenUsersLocation($userId1, $userId2)
    {
        return 'rfchat:messages_v1:conversations:' . ($userId1 < $userId2 ? "$userId1-$userId2" : "$userId2-$userId1");
    }

    function redis_messsagesNewMesssagesFromUserCount($userId, $fromUserId)
    {
        return 'rfchat:messages_v1:newMessagesFromUserCount:' . $userId . ':' . $fromUserId;
    }

    function redis_messsagesInboxQueue($userId)
    {
        return 'rfchat:messages_v1:inbox:' . $userId;
    }

    function redis_messsagesOutboxQueue($userId)
    {
        return 'rfchat:messages_v1:outbox:' . $userId;
    }

    function redis_messsagesNewMesssagesListing($userId)
    {
        return 'rfchat:messages_v1:newMessages:' . $userId;
    }

    function redis_messsagesnewMesssagesPendingRepliesListings($userId)
    {
        return 'rfchat:messages_v1:pendingReplies:' . $userId;
    }

    function redis_newChatMessageCountLocation($userId)
    {
        return 'rfchat:messages_v1:newMessages:' . $userId;
    }

    function newMessagesCount($userId)
    {
        return $this->redis->get($this->redis_newChatMessageCountLocation($userId));
    }

    function send_message($from, $to, $message, $isReply = FALSE)
    {
        if(!is_numeric($from) || !is_numeric($to)) die;

        # Variables
        $strlen             = strlen($message);
        $message            = ($message);
        $messageUnescaped   = $message;
        $message            = ($message);


        # Insert into db
        $this->db->query("INSERT INTO rf_messages (fromId, toId, message, strlen) VALUES ($from, $to, '$message', $strlen)");

        # Notification
        $this->redis->incr($this->redis_newChatMessageCountLocation($to));
        $this->redis->incr($this->redis_messsagesNewMesssagesFromUserCount($to, $from));

        return array('status' => 'success');
    }
    
    
    


    //================================================================================================================================================================
    // General chat things
    //================================================================================================================================================================

    function chatWindowOpenStatusLocation($userId)
    {
        return 'rfchat:chatWindowOpenStatus:' . $userId;
    }

    function userKeyLocation($userId)
    {
        return 'rfchat:userKeys:' . $userId;
    }

    function userKeyUserRefLocation($key)
    {
        return 'rfchat:userKeysRef:' . $key;
    }


    function messages($userId)
    {
        $response   = array();
        $query      = $this->db->query("SELECT * FROM rf_messages WHERE toId = $userId GROUP BY fromId");

        while($row = $query->fetch_assoc()) {
            $response[] = json_decode(json_encode($row));
        }

        return $response;
    }



    function messages_conversation($userId, $partner)
    {
        $response   = array();
        $query      = $this->db->query("SELECT * FROM rf_messages WHERE (toId = $userId AND fromid = $partner) OR (toId = $partner AND fromid = $userId)");

        while($row = $query->fetch_assoc()) {
            $response[] = json_decode(json_encode($row));
        }

        return $response;
    }



    function contacts($userId)
    {
        $response   = array();
        $query      = $this->db->query("SELECT * FROM wp_bp_friends WHERE initiator_user_id = $userId");

        while($row = $query->fetch_assoc()) {
            $response[] = $row['friend_user_id'];
        }

        return $response;
    }

    function getUserKey($userId)
    {
        return FALSE;
        $keyLocation        = $this->userKeyLocation($userId);
        $key                = $this->redis->get($keyLocation);
        $hasReference       = $this->redis->get($this->userKeyUserRefLocation($key));

        if(!$key || !$hasReference)
        {
            $key                = sha1(generateRandomString(5) . $userId . microtime() . rand() . 'iLoveMoneyAndTitties') . $userId;
            $keyRefLocation     = $this->userKeyUserRefLocation($key);

            $this->redis->set($keyLocation, $key);
            $this->redis->set($keyRefLocation, $userId);
        }

        return $key;
    }


    function clearNotifications($userId, $partner)
    {
        if(!is_numeric($userId) || !is_numeric($partner)) die;
        $count  = $this->redis->get($this->redis_messsagesNewMesssagesFromUserCount($userId, $partner));
        if($count < 1) return TRUE;

        $x = 0;
        while($x < $count)
        {
            $x++;
            $this->redis->decr($this->redis_newChatMessageCountLocation($to));
        }
        return $this->redis->del($this->redis_messsagesNewMesssagesFromUserCount($userId, $partner));
    }


    function clear_AllNotifications($userId)
    {
        return FALSE;
        if(!is_numeric($userId)) die;
        $this->resetNotifications($userId, 'new_chatMessage');
    }


    function openActiveConversationTabs()
    {
        return FALSE;
        if(!isset($_COOKIE['chat_conversationTabsAndPosition'])) return FALSE;

        $conversationTabsAndPosition    = json_decode($_COOKIE['chat_conversationTabsAndPosition']);

        foreach($conversationTabsAndPosition as $tab)
        {
            if(!isset($tab->partnerId)) continue;

            $partner                        = $tab->partnerId;
            $data['message']                = array_reverse($this->CI->messages_interface_model->message(get_current_user_id(), $partner, 20));
            $data['partner']                = $partner;
            $data['haveIBlockedUser']       = $this->haveIBlockedUser(get_current_user_id(), $partner);
            $data['hasPartnerUnreadMsgs']   = $this->redis->get($this->redis_messsagesNewMesssagesFromUserCount($partner, get_current_user_id()));
            $data['style']                  = 'right:' . $tab->position . 'px';
            $data['minimized']              = $tab->minimized;

            $this->CI->load->view('modules/components/chat/conversation_window', $data);
        }
    }
}