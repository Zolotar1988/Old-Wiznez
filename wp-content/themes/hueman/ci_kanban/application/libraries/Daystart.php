<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daystart
{
    public function __construct()
    {
        $this->CI =& get_instance();

        // Start of the day
        $dayOrganization[]['brush_teeth'] = 0;
        $dayOrganization[]['wash_face'] = 1;
        $dayOrganization[]['vitamins'] = 0;
        $dayOrganization[]['drink_water'] = 0;
        $dayOrganization[]['email_check'] = 0;
        $dayOrganization[]['jog'] = 1;
        $dayOrganization[]['drink_water'] = 0;
        $dayOrganization[]['meditate'] = 0;

        $dayOrganization[]['separator'] = 0;

        // After the start "ritual"
        $dayOrganization[]['work_prepare'] = 1;
        $dayOrganization[]['work_shortBreak'] = 1;
        $dayOrganization[]['work_session'] = 1;
        $dayOrganization[]['drink_water'] = 1;
        $dayOrganization[]['work_break'] = 0;
        $dayOrganization[]['work_session'] = 1;
        $dayOrganization[]['shower'] = 1;
        $dayOrganization[]['lunch'] = 0;
        $dayOrganization[]['drink_water'] = 0;
        $dayOrganization[]['work_session'] = 0;
        $dayOrganization[]['work_break'] = 1;
        $dayOrganization[]['work_session'] = 1;

        $dayOrganization[]['separator'] = 0;

        // Go jogging, do more things
        $dayOrganization[]['jog'] = 1;
        $dayOrganization[]['drink_water'] = 0;
        $dayOrganization[]['meditate'] = 0;
        $dayOrganization[]['face_mask'] = 0;

        // Work more
        $dayOrganization[]['work_session'] = 0;
        $dayOrganization[]['work_break'] = 1;
        $dayOrganization[]['work_session'] = 1;

        // Build
        $this->dayOrganization = $dayOrganization;
    }







    //================================================================================================================================================================
    // General functions
    //================================================================================================================================================================
    # Fetch current queue
    function fetch_queue($userId, $onlyActive = FALSE)
    {
        if($onlyActive)
            return $this->CI->db->query("SELECT * FROM daystart_queue WHERE user_id = $userId AND completed = 0 AND active = 1")->row();
        else
            return $this->CI->db->query("SELECT * FROM daystart_queue WHERE user_id = $userId AND completed = 0")->result();
    }


    # Send the notification to my phone
    function send_pushNotification($message) {
        $activateUrl = 'http://monkeywork.io/activate_nextQueueItem';
        curl_setopt_array($ch = curl_init(), array(CURLOPT_URL => "https://api.pushover.net/1/messages.json",CURLOPT_POSTFIELDS => array("token" => "a2zxKZp3KJtrgTFobnNYvvfbTbv1zP", "user" => "ugMR6AENNf9CX6fsDUAm2iaeVPDD6d","message" => $message . "\n" . $activateUrl, 'url' => $activateUrl)));
        curl_exec($ch);
        curl_close($ch);
    }


    # This just runs the test so I can see the output
    function run_test()
    {
        echo 'Total items: ' . count($this->dayOrganization) . '<br>';
        echo 'Push notifications sent: <span id="pushNotifSent"></span><br><hr></hr><br>';

        $startingTime       = date('H:i', strtotime('00:00:00'));
        $currentTime        = $startingTime;
        $currentTimeSection = $startingTime;
        $elapsedMinutes     = 0;
        $pushNotifSent      = 0;

        foreach($this->dayOrganization as $array)
        {
            foreach($array as $key => $sendPush)
            {
                if($key == 'separator') { echo '<hr></hr><br>'; $currentTimeSection = $startingTime; continue; }

                $pushNotifSent          = $pushNotifSent + ($sendPush ? 1 : 0);
                $todo                   = $this->CI->db->query("SELECT * FROM daystart_todos WHERE todo_key = '$key'")->row();
                $currentTime            = date('H:i', strtotime($currentTime . ' +' . $todo->time_minutes . ' minutes'));
                $currentTimeSection     = date('H:i', strtotime($currentTimeSection . ' +' . $todo->time_minutes . ' minutes'));
                $elapsedMinutes         = $elapsedMinutes + $todo->time_minutes;
                ?>
                <div>
                    <span>Key: <?php echo $todo->todo_key; ?></span><br>
                    <span>Eta: <?php echo $todo->time_minutes; ?></span><br>
                    <span>Current time: <?php echo $currentTime; ?></span><br>
                    <span>Section time: <?php echo $currentTimeSection; ?></span><br>
                    <span>Push notif: <?php echo ($sendPush ? '<strong>Yes</strong>' : 'No'); ?></span>
                </div>
                <br><br>
                <script>document.getElementById('pushNotifSent').innerHTML = <?php echo $pushNotifSent; ?></script>
            <?php
            }
        }
    }


    # Wraps up the days and last functions
    function finish_day()
    {

    }








    //================================================================================================================================================================
    // Building the queue
    //================================================================================================================================================================
    function buildQueue()
    {
        # Truncate
        $this->CI->db->query("TRUNCATE daystart_queue");

        # Build
        foreach($this->dayOrganization as $array)
        {
            foreach($array as $key => $sendPush)
            {
                $todo = $this->CI->db->query("SELECT * FROM daystart_todos WHERE todo_key = '$key'")->row();
                if(!is_object($todo)) continue;
                $insert = array('todo_id' => $todo->id, 'user_id' => $todo->user_id, 'time_left' => $todo->time_minutes, 'todo_key' => $todo->todo_key, 'send_pushNotification' => $sendPush);
                $this->CI->db->insert('daystart_queue', $insert);
            }
        }
    }










    //================================================================================================================================================================
    // Run cron
    //================================================================================================================================================================
    function run_cron()
    {
        # Get the current active item and see if the time ran out
        $currentQueueItem = $this->CI->db->query("SELECT * FROM daystart_queue WHERE active = 1")->row();
        if(is_object($currentQueueItem) && $currentQueueItem->time_left > 0) return $this->CI->db->query("UPDATE daystart_queue SET time_left = time_left - 1 WHERE active = 1");

        # Set current active as done
        $this->CI->db->query("UPDATE daystart_queue SET completed = 1 WHERE active = 1");

        # Inactivate all
        $this->CI->db->query("UPDATE daystart_queue SET active = 0");

        # Fetch the next to do
        $nextUp  = $this->CI->db->query("SELECT * FROM daystart_queue WHERE completed = 0 ORDER BY id ASC LIMIT 1")->row();
        $nextUp2 = $this->CI->db->query("SELECT * FROM daystart_queue WHERE completed = 0 AND id = " . ($nextUp->id + 1))->row();

        # Activate it
        $this->CI->db->query("UPDATE daystart_queue SET active = 1, time_left = time_left - 1 WHERE id = " . $nextUp->id);

        # Get it's to-do data and update it's analytics
        $todo  = $this->CI->db->query("SELECT * FROM daystart_todos WHERE id = " . $nextUp->todo_id)->row();
        if(is_object($nextUp2)) $todo2 = $this->CI->db->query("SELECT * FROM daystart_todos WHERE id = " . $nextUp2->todo_id)->row();
//        $this->CI->db->query("UPDATE daystart_todos SET analytics_timesRan = analytics_timesRan + 1 WHERE id = " . $nextUp->todo_id);

        # Build for push
        $now = $todo->title;
        $after = (is_object($todo2) ? $todo2->title : 'anything');
        $pushMsg = "Now : $now \n After: $after \n\n\n\n\n";
        if($nextUp->send_pushNotification) $this->send_pushNotification($pushMsg);
    }
}