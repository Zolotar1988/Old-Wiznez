<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Account_recovery_model extends CI_Model
{

    function password_sendNew($userId)
    {
        # New password
        $cleanPass  = generateRandomString(6);
        $password   = md5($cleanPass);

        #  Save it
        $this->db->query("DELETE FROM users_pendingPasswords WHERE userId = $userId");
        $this->db->query("INSERT INTO users_pendingPasswords (userId, password, passwordClean) VALUES ($userId, '$password', '$cleanPass') ON DUPLICATE KEY UPDATE password = '$password', passwordClean = '$cleanPass'");

        # Depending on which system is using this, send email, sms, etc
        $recipient          = $this->user->userData($userId)->username;
        $subject            = 'New Techpear password';
        $recipient_name     = $this->user->userData($userId)->name;
        $message            =   'We have received a request to send you a new password.<br>' .
            'You may login with the following password: ' . $cleanPass . '<br>' .
            'If you did not request a new password, simply ignore this email.<br>' .
            'Best wishes,<br>' .
            'The Techpear Team';

        $this->mandrill->sendMail($recipient, $recipient_name, $message, $subject);

        return TRUE;
    }

}