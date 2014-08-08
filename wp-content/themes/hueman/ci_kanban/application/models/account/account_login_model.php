<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_login_model extends CI_Model
{

    function login($parameters)
    {
        # Extracts
            # user_ip
            # username
            # password
        extract($parameters);

        # Clean
        $username_clean   = $username;
        $username         = $this->db->escape($username);
        $password_clean   = $password;
        $password         = $this->db->escape($password);

        # Run the logging in function and save whatever it outputs in a variable
        $userId = $this->validate($username, $password, $username_clean, $password_clean);

        # If the userId is not numeric, then logging in failed
        if(!is_numeric($userId)) return $this->framework->returnJsonResponse('error', array('msg' => ($userId ? 'The password you entered was incorrect' : 'We could not find an account with this username')), TRUE);

        # Checks if user is banned
        $banned = $this->db->query("SELECT banned FROM users WHERE id = $userId LIMIT 1")->row('banned');

        # If the user has a pending delete, remove it from here and then make $banned false
        if($this->db->query("SELECT id FROM users_pendingDelete WHERE userId = $userId")->row('id'))
        {
            $this->user->unAsTakingABreak($userId);
            $this->db->query("DELETE FROM users_pendingDelete WHERE userId = $userId");
            $this->db->query("UPDATE users SET banned = 0 WHERE id = $userId");
            $this->db->query("INSERT INTO users_deleteHistory (userId, returning) VALUES ($userId, 1)");
            $this->db->query("INSERT INTO users_log (id, actionBy, actionTowards, action, data, info, datetime) VALUES (NULL, '$userId', '$userId', 'profileDeleteCancelled', '', '', CURRENT_TIMESTAMP);");
            $banned = FALSE;
        }


        # If user is banned, return
        if($banned) return $this->framework->returnJsonResponse('warning', 'Your account has been disabled');

        # Log this login
//        $this->db->query("INSERT INTO users_logins ( userId, remoteAddr, fbLogin, site, isAppLogin) VALUES ($userId , inet_aton('$ip'), $fb_login, '$site', $isAppLogin)");

        # Just clean the user cache
        $this->user->flushUserData($userId);

        # Set the cookie
        $this->account->setLoginCookie($userId);

        # Finally!
        return $this->framework->returnJsonResponse('success', array('userId' => $userId));
    }

    
    function validate($username, $password, $username_clean, $password_clean)
    {
        # Check for a pending password change, first by checking if this username/pass combination will match.
        # Step 1 checks, step 2 deletes from pending, step 3 is a safety measure to not login when user inputs userId only
        $willMatch   = $this->db->query("SELECT id FROM users WHERE rtrim(username) = $username AND password = $password")->row('id');
        if($willMatch)  $this->db->query("DELETE FROM users_pendingPasswords WHERE userId = $willMatch");
        if(!$willMatch) if(is_numeric($username)) die;

        # This is password related as well. If a user is pending a new password, we set it here
        $uIdFromName= $this->user->idFromUsername($username_clean);
        if($uIdFromName)
        {
            $newPass    = $this->db->query("SELECT password FROM users_pendingPasswords WHERE userId = $uIdFromName")->row('password');
            if($newPass && $newPass == $password_clean)
            {
                $this->db->query("UPDATE users SET password = '$newPass' WHERE id = $uIdFromName LIMIT 1");
                $this->db->query("DELETE FROM users_pendingPasswords WHERE userId = $uIdFromName");
            }
        }

        # Finally checking if this username/password combination matches. If so, returning the userId
        $userId         = $this->db->query("SELECT id FROM users WHERE rtrim(username) = $username AND password = $password")->row('id');
        if($userId)     return $userId;

        # If not the step above, checking if the username exists, if so, return it
        $username       = $this->db->query("SELECT username FROM users WHERE rtrim(username) = $username")->row('username');
        if($username)   return $username;

        # Just return FALSE if all else fails
        return FALSE;
    }


    function logout()
    {
        #Updates last active time
        $userId = $this->user->id();
        if ($userId && $userId > 0) {
            $this->db->query("UPDATE users_data SET lastActive = '', lastLogout = NOW() WHERE id = $userId");
        }
        $this->user->cleanBasicInfoCache($userId);
    }



}