<?php

class Notifications
{

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->notificationCount = $this->get_notificationsCount($this->CI->user->id);
    }


    //================================================================================================================================================================
    // Redis locations
    //================================================================================================================================================================
    function notifications_notificationForUserLocation($userId, $notification_type)
    {
        return $this->CI->config->item('site_name') . "notifications_v1:" . ':' . $notification_type . ':' .  $userId;
    }


    function notifications_notificationForUserQueue($userId, $onlyNew)
    {
        return $this->CI->config->item('site_name') . "notifications_v1:" . ':queue:' . ($onlyNew ? 'new': 'all') . ':' .  $userId;
    }





    //================================================================================================================================================================
    // Methods
    //================================================================================================================================================================

    # These are the types of notifications which are available to the system
    function get_availableNotificationsType()
    {
        return array('new_connections', 'new_message', 'listing_application', 'listing_invite');
    }


    # Get notification count
    function get_notificationsCount($userId)
    {
        $notifications = $this->CI->ci_redis->lrange($this->notifications_notificationForUserQueue($userId, TRUE), 0, -1);
        return count($notifications);
    }


    # Clear notifications
    function clear_newNotifications($userId)
    {
        $this->CI->ci_redis->del($this->notifications_notificationForUserQueue($userId, TRUE));
    }


    # Sets a notification
    function set_notification($userId, $from, $notificationType, $data = array())
    {
        # Modify the variables to how the system will use it
        $data   = json_encode($data);

        # First check if this is a valid notifcation type, if not just return FALSE that it was not set
        if(!in_array($notificationType, $this->get_availableNotificationsType())) return FALSE;

        # Insert his notification into the database and fetch a notification Id to set it's object
        $this->CI->db->query("INSERT INTO notifications (user_id, from_user_id, notification_type, notification_data) VALUES ($userId, $from, '$notificationType', '$data')");

        # Fetch the ID
        $notificationId = $this->CI->db->insert_id();

        # Set the notification object
        $this->CI->ci_redis->set($this->notifications_notificationForUserLocation($userId, $notificationType), $data);
        $this->CI->ci_redis->lpush($this->notifications_notificationForUserQueue($userId, TRUE), $notificationId);
        $this->CI->ci_redis->lpush($this->notifications_notificationForUserQueue($userId, FALSE), $notificationId);

        return $notificationId;
    }
}