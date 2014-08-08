<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User
{

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->id              = ($this->CI->account->currentUserId ? $this->CI->account->currentUserId : FALSE);
        $this->id              = 1;
        $this->currentUserData = $this->userData($this->id);
        $this->username        = ($this->id ? $this->username($this->id) : null);
        $this->fetchedUserData = new stdClass();
    }



    # Returns the age
    function age($date)                                             { return (empty($date) ? 0 : floor((time() - strtotime($date))/31556926)); }

    # This just returns the type of profile
    function profileType($user)                                     { return $this->user->userData($user)->profileType; }

    # This is for the gender
    function gender($user)                                          { return $this->userData($user)->gender; }
    # This is gender but in a way that the system understands it

    # regardless of site language
    function gender_system($user)                                   { if(!$this->id) return FALSE; $gender = $this->userData($user)->gender; return ($gender == 'mann' || $gender == 'Mann' ? 'man' : 'woman'); }

    # The URL to a user profile
    function profileUrl($user)                                      { return '/' . $this->username($user); }

    # Geting the user ID via a username
    function idByUsername($username)                                { return $this->CI->db->query("SELECT id FROM users WHERE username = " . $this->CI->db->escape($username))->row('id'); }

    # A username
    function username($user)                                          { return $this->userData($user)->username; }

    # If the user is online
    function isOnline($user)                                        { $lastActive = $this->userData($user); return ($lastActive ? ((((time()-strtotime($lastActive))/1800) < 1) ? TRUE : FALSE) : FALSE); }

    # City of a user
    function city($id)                                              { return $this->userData($id)->city; }

    # Country of a user
    function country($id)                                           { return $this->userData($id)->country; }

    # The avatar, full size
    function avatar($user)                                          { return $this->userData($user)->avatar; }

    # The photo ID of the avatar
    function avatarPhotoId($user)                                   { return $this->userData($user)->avatarPhotoId; }

    # The avatar's thumbnail
    function avatar_thumb($user) { return $this->userData($user)->thumb; }


    function user_tags($id, $allowEdit = TRUE)
    {
        if(!isset($this->userData($id)->tags) || !$this->userData($id)->tags) return FALSE;
        return $this->tags_process($id, $this->userData($id)->tags, $allowEdit);
    }


    function tags_process($tags_ofUserId, $tags, $allowEdit)
    {
        # If we don't allow edit then it's assumed its viewing tags of another other
        $return                 = '';
        if(!$tags)         return '';
        $tags                   = json_decode($tags);
        $highlightMatched       = FALSE;
        $username               = $this->CI->user->username($tags_ofUserId);

        if(!$allowEdit && $tags_ofUserId != $this->CI->user->id)
        {
            $highlightMatched   = TRUE;
            $myTags             = (!isset($this->CI->user->userData($this->CI->user->id)->tags) || !$this->CI->user->userData($this->CI->user->id)->tags ? array() : json_decode($this->CI->user->userData($this->CI->user->id)->tags, TRUE));
        }

        foreach($tags as $tag)
        {
            $highlight  = ($highlightMatched && in_array($tag, myTags) ? TRUE : FALSE);
            $return     .= '<div class="tag ' . ($allowEdit ? 'editAllowed' : 'not_editAllowed') . ' ' . ($highlight ? 'tag_highlighted wtf' : 'not_tag_highlighted') . '"' . 'data-tag="' . $tag . '" >' . lang('icon-heart') . '<span>' . $tag . '</span>' . ($allowEdit ? '<a class="close" href="#" onclick="tagsystem.removeTag(this); return false"><i class="icon-close3"></i></a>' : '') . '</div>';
        }

        return $return;
    }


    function redis_userDataLocation($userId)
    {
        return $this->CI->config->item('site_name') . ':users:userData_v2:' . $userId;
    }

    function userData($user)
    {
        if (isset($this->fetchedUserData->$user) && $this->fetchedUserData->$user) return $this->fetchedUserData->$user;
        if ($user == 0 || !is_numeric($user)) return FALSE;

        $userData = json_decode($this->CI->ci_redis->get($this->redis_userDataLocation($user)));

        if(!$userData || !is_object($userData)) return $this->flushUserData($user);

        $userData->isOnline           = $this->isOnline($this->CI->ci_redis->get($this->redis_lastActiveTimeLocation($user)));
        $this->fetchedUserData->$user = $userData;
        return $userData;
    }


    # This updates the user data - its not really flushing it i 'spose
    function flushUserData($userId)
    {
        # Fetch data
//        $profile_type   = $this->CI->db->query("SELECT profile_type FROM users WHERE id = $userId")->row('profile_type');
//        $userData       = $this->CI->db->query("SELECT * FROM users u WHERE u.id = $userId LIMIT 1")->row();
//        unset($userData->password);
//
//
//        # Remove data thats cached in the object
//        $this->fetchedUserData->$userId = FALSE;
//
//        # Save
//        $this->CI->ci_redis->set($this->redis_userDataLocation($userId), json_encode($userData));
//
//        # Return
//        $this->userData($userId);
    }


    function redis_lastActiveTimeLocation($userId)
    {
        return $this->CI->config->item('site_name') . ':users:lastActiveLocation_v1:' . $userId;
    }



    # Username relation
    function updateRedis_usernameIdRelation($userId, $oldUsernameToUnset = FALSE)
    {
        if(!is_numeric($userId) || !$userId) return FALSE;
        $username  = $this->CI->db->query("SELECT username FROM users WHERE id = $userId")->row('username');
        $this->CI->ci_redis->del('accountConnect:users:usernameIdRelation:' . strtolower($oldUsernameToUnset));
        return $this->CI->ci_redis->set('accountConnect:users:usernameIdRelation:' . strtolower($username), $userId);
    }

    function idFromUsername($username)
    {
//        return $this->CI->db->query("SELECT id FROM users WHERE username = $username")->row('id');
        return $this->CI->ci_redis->get('accountConnect:users:usernameIdRelation:' . strtolower($username));
    }


    # Here is the different types of user fields
    function profileTypes_all()
    {
        return $this->CI->config->item('profile_types');
    }

    function profileTypes_fields($profileType)
    {
        $types = $this->CI->config->item('profile_fields');
        return $types[$profileType];
    }


}