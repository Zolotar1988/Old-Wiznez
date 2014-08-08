<?php
class Sendmail
{
    function send($keyTitleRelationship)
    {
        foreach($_POST as $key => $val) if($val && $val != 'Velg kjÃ¸nn') $array[$key] = "'".$val."'";

        $to      = 'stilov@online.no';
        $subject = 'Bestilling';
        $headers = "From: tina@turtleweb.no\r\n";
        $headers .= "Reply-To: noreply@bestilling.no\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; utf-8\r\n";

        $message  = '<html><body>';
        $message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
        $message .= "<tr style='background: #eee;'><td><strong>Field:</strong> </td><td>User input</td></tr>";
        foreach($array as $key => $val) $message .= "<tr><td><strong>" . ucfirst($keyTitleRelationship[$key]) . ":</strong> </td><td>" . $val . "</td></tr>";
        $message .= "</table>";
        $message .= "</body></html>";

        return mail($to, $subject, $message, $headers);
    }
}