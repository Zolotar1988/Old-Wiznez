<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email
{
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('mandrill');
        $this->CI->load->library('mailchimp');
    }



    //================================================================================================================================================================
    // Email verification
    //================================================================================================================================================================

    function redis_isUserVerifiedLocation($userId)
    {
        return $this->CI->config->item('site_name') . ':email:verified_v3:' . $userId;
    }


    function fetch_isUserEmailVerified($userId)
    {
        return $this->CI->ci_redis->get($this->redis_isUserVerifiedLocation($userId));
    }


    function set_isUserEmailVerified($userId)
    {
        return $this->CI->ci_redis->set($this->redis_isUserVerifiedLocation($userId), 1);
    }


    # This sends a verification code and it also invalidates a verified user
    function verification_generateCode($userId)
    {
        $code   = md5(microtime() . '-fatpeoplehate');
        $code   = $userId . strtoupper(substr($code, 2, 6));
        $this->CI->db->query("INSERT INTO email_verification (userId, verificationKey) VALUES ($userId, '$code') ON DUPLICATE KEY UPDATE verificationKey = '$code', verified = 0");
        $this->CI->ci_redis->del($this->redis_isUserVerifiedLocation($userId));
        return $code;
    }


    function verification_sendCode($userId)
    {
        $recipient          = $this->CI->user->userData($userId)->username;
        $recipient_name     = $this->CI->user->userData($userId)->name;
        $subject            = 'Welcome to Techpear';
        $verificationCode   = $this->verification_generateCode($userId);
        $verificationUrl    = 'http://techpear.com/email_verification/' . $userId . '/' . $verificationCode;
        $message            =   'Thank you for registering to Techpear.<br>' .
                                'To complete your registration please varify your email address by clicking the follow URL address:<br>' .
                                '<a href="' . $verificationUrl . '">' . $verificationUrl . '</a><br><br>' .
                                'If you cannot click the link just copy/paste it to your browser.<br>' .
                                'Best wishes,<br>' .
                                'The Techpear Team';

        return $this->CI->mandrill->sendMail($recipient, $recipient_name, $message, $subject);
    }


    function verification_validate($code)
    {
//        $ipAddr     = $this->CI->db->escape($_SERVER['REMOTE_ADDR']);
//        if($this->CI->db->query("SELECT ip FROM user_keyCodeLogins_blockedIps WHERE ip = $ipAddr LIMIT 1")->row('ip')) { redirect('/'); die; };
        $code       = $this->CI->db->escape($code);
        $userId     = $this->CI->db->query("SELECT userId FROM email_verification WHERE verificationKey = $code LIMIT 1")->row('userId');

        if(!$userId)
        {
//            $this->CI->db->query("INSERT INTO user_keyCodeLogins_failedHistory (ip, code) VALUES ($ipAddr, $code)");
//            $failedCount = $this->CI->db->query("SELECT count(ip) as count FROM user_keyCodeLogins_failedHistory WHERE ip = $ipAddr LIMIT 1")->row('count');
//            if($failedCount > 3) $this->CI->db->query("INSERT INTO user_keyCodeLogins_blockedIps (ip) VALUES ($ipAddr)");
            redirect('/');
        }
        else
        {
//            $this->CI->db->query("UPDATE user_keyCodeLogins_history SET used = 1 WHERE code = $code LIMIT 1");
            $this->CI->db->query("UPDATE email_verification SET verified = 1, verified_dateTime = CURRENT_TIMESTAMP WHERE verificationKey = $code");
            $this->set_isUserEmailVerified($userId);
        }

        return $userId;
    }


}