<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account
{

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->sessionLocation          = $this->CI->config->item('site_name') . ':sessions:web';
        $this->currentUserId            = $this->session_fetchUserId();
//        $this->sess_destroy();
    }


    # The fields for editing the user
    function account_editingFields()
    {
        $accountConfig = $this->CI->config->item('account');
        return $accountConfig['editableFields'];
    }


    # Logs out the user
    function logout()
    {
        $this->sess_destroy();
        session_destroy();
    }


    # This will take the full response result from the login function above and set it
    function setLoginCookie($userId)
    {
        # Set session data then redirect
        $this->session_set($userId);
    }


    # Sets the session
    function session_set($userId)
    {
        if($this->session_fetchUserId()) return TRUE;

        # Start with creating the key
        $sessionId  = ':)' . $this->CI->input->ip_address() . microtime();
        while (strlen($sessionId) < 50) $sessionId .= mt_rand(0, mt_getrandmax());
        $sessionId  = sha1($sessionId) . time();

        $cookieName = $this->CI->config->item('cookie_prefix') . $this->CI->config->item('sess_cookie_name');
        $this->CI->ci_redis->set($this->sessionLocation . ':' . $sessionId, json_encode(array('userId' => $userId, 'userAgent' => substr($this->CI->input->user_agent(), 0, 130))));

        return setcookie($cookieName, $sessionId, time() + (60*60*24*365*2), "/");
    }


    # Fetches the user ID of a session
    function session_fetchUserId()
    {
        $cookieName = $this->CI->config->item('cookie_prefix') . $this->CI->config->item('sess_cookie_name');
        if(!isset($_COOKIE[$cookieName])) return FALSE;

        $cookie = json_decode($this->CI->ci_redis->get($this->sessionLocation . ':' . $_COOKIE[$cookieName]));
        if(!$cookie) return $this->sess_destroy();

        $userId = $cookie->userId;
        $agent  = $cookie->userAgent;

        if(!$userId || substr($this->CI->input->user_agent(), 0, 130) != $agent) return $this->sess_destroy();

        return $userId;
    }


    # Destroys the session as well as some other things
    function sess_destroy() {
        $cookieName = $this->CI->config->item('cookie_prefix') . $this->CI->config->item('sess_cookie_name');
        $this->CI->ci_redis->del($this->sessionLocation . ':' . $_COOKIE[$cookieName]);
        setcookie($cookieName, addslashes(serialize(array())), (time() - 3150000), "/");
    }

}
