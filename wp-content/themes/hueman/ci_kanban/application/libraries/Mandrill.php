<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mandrill
{
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->api_key              = '1U8lx7pBBA4YSdFL0ZEbRQ';
        $this->default_sender       = 'noreply@techpear.com';
        $this->default_sender_name  = 'Techpear';
        $this->api_url              = 'https://mandrillapp.com/api/1.0/messages/send.json';
    }



    function sendMail($recipient, $recipient_name, $content, $subject, $from = FALSE, $from_name = FALSE)
    {
        header('Content-Type: application/json');

        $from       = ($from ? $from : $this->default_sender);
        $from_name  = ($from_name ? $from_name : $this->default_sender_name);

        $queryString                                = new stdClass();
        $queryString->async                         = FALSE;
        $queryString->key                           = $this->api_key;
        $queryString->message->html                 = $content;
        $queryString->message->text                 = strip_tags($content);
        $queryString->message->subject              = $subject;
        $queryString->message->from_email           = $from;
        $queryString->message->from_name            = $from;
        $queryString->message->to                   = array(array('email' => $recipient, 'name' => $recipient_name));
        $queryString->message->tags                 = array('email_verification');
        $queryString->message->from_name            = $from_name;
        $queryString->message->track_opens          = TRUE;
        $queryString->message->track_clicks         = TRUE;
        $queryString->message->auto_text            = TRUE;
        $queryString->message->url_strip_qs         = TRUE;
        $queryString->message->preserve_recipients  = TRUE;

//        echo json_encode($queryString);die;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($queryString));
        $result = curl_exec($ch);
        return $result;
    }
}