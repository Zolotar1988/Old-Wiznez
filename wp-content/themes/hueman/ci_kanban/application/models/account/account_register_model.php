<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_register_model extends CI_Model
{

    function register($parameters)
    {
        # Extracts
        # user_ip
        # username
        # password
        # name
        extract($parameters);

        # Check if the account type is one allowed
        if(!in_array($accType, $this->config->item('profile_types'))) die;

        # Check if the username is taken
        $username_taken     = $this->db->query("SELECT id FROM users WHERE username = " . $this->db->escape($username))->row('id');


        # Return errors if anything isn't what it's supposed to be
        if($username == 'Brukernavn')   return $this->framework->returnJsonResponse('warning', lang('register_account-pleaseChooseUsername'));
        if(!$username)                  return $this->framework->returnJsonResponse('warning', lang('register_account-pleaseChooseUsername'));
        if(!filter_var($username, FILTER_VALIDATE_EMAIL)) return $this->framework->returnJsonResponse('warning', 'Please enter a valid email');
//        if(!ctype_alnum($username))     return $this->framework->returnJsonResponse('warning', lang('register_account-onlyAlphaNum'));
        if(!$password)                  return $this->framework->returnJsonResponse('warning', lang('register_account-choosePassword'));
        if($username_taken)             return $this->framework->returnJsonResponse('warning', lang('register_account-usernameTaken'));

        # If type is intern so namy is full name, fail if no space
        if($accType == 'intern' && !preg_match('/\s/',$name)) return $this->framework->returnJsonResponse('warning', 'Please enter your full name');


        # Create user then retrieve the user ID
        $this->db->insert(
            'users',
            array(
                'username'          => mysql_real_escape_string($username),
                'email'             => mysql_real_escape_string($username),
                'profile_type'      => mysql_real_escape_string($accType),
                'password'          => $password
            )
        );
        $userId = $this->db->insert_id();


        # Insert other data
        $this->db->insert('users_data-type_' . $accType, array('user_id' => $userId, 'name' => $name));


        # Insert to Redis
        $this->user->flushUserData($userId);
//        $this->user->generate_secretKey($userId, $username);
//        $this->user->updateRedis_user($userId);
//        $this->user->updateRedis_secretKey($userId);
        $this->user->updateRedis_usernameIdRelation($userId);


        # Success!
        return $this->framework->returnJsonResponse('success', array('message' => lang('register_account-success')));
    }



}