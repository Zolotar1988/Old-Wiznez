<?php

class MailChimp
{
    function __construct()
    {
        $this->CI =& get_instance();
        $this->api_key          = 'b471ee64da972d6ac480f8b39d01c250-us7';
        $this->api_endpoint     = 'https://us7.api.mailchimp.com/2.0';
        $this->listsIds_users   = 'bfdde503f4';
        $this->verify_ssl       = FALSE;
    }


    function subscribe($userId)
    {
        $full_name              = $this->CI->user->userData($userId)->name;
        $name_explode           = explode(' ', $full_name);
        $first_name             = $name_explode[0];
        unset($name_explode[0]);
        $birth_date             = (isset($this->CI->user->userData($userId)->birthdate) ? $this->CI->user->userData($userId)->birthdate : '');
        $birth_date_formatted   = date('m/d', strtotime($birth_date));
        $profession             = $this->CI->user->userData($userId)->profession;
        $country                = $this->CI->user->userData($userId)->country;
        $last_name              = implode(' ', $name_explode);

        $arguments                    = new stdClass();
        $arguments->apikey            = $this->api_key;
        $arguments->id                = $this->listsIds_users;
        $arguments->email             = array('email' => $this->CI->user->userData($userId)->username);
        $arguments->merge_vars        = array('USERID' => $userId, 'FNAME' => $first_name, 'LNAME' => $last_name, 'BDATE' => $birth_date_formatted, 'PROFESSION' => $profession, 'COUNTRY' => $country);
        $arguments->double_optin      = FALSE;
        $arguments->send_welcome      = FALSE;
//        $this->email_type       = 'html';

        return $this->makeRequest('lists/subscribe', $arguments);
    }



    function fetch_membersOfList($listId)
    {
        $arguments     = new stdClass();
        $arguments->id = $listId;
        return $this->makeRequest('lists/members', $arguments);
    }



    function makeRequest($method, $arguments=array())
    {
        $arguments->apikey  = $this->api_key;
        $url                = $this->api_endpoint.'/'.$method.'.json';

        if (function_exists('curl_init') && function_exists('curl_setopt')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arguments));
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $json_data = json_encode($arguments);
            $result    = file_get_contents($url, null, stream_context_create(array(
                'http' => array(
                    'protocol_version' => 1.1,
                    'user_agent'       => 'PHP-MCAPI/2.0',
                    'method'           => 'POST',
                    'header'           => "Content-type: application/json\r\n".
                        "Connection: close\r\n" .
                        "Content-length: " . strlen($json_data) . "\r\n",
                    'content'          => $json_data,
                ),
            )));
        }

        return $result ? json_decode($result, true) : false;
    }
}
