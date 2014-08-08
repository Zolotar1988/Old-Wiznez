<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kanban
{
    public function __construct()
    {
        $this->CI =& get_instance();
    }


    function my_projects()
    {
        return $this->CI->db->query("SELECT * FROM rf_kanban_projects WHERE user_id = ?", $this->CI->user->id)->result();
    }

    function my_boards()
    {
        return $this->CI->db->query("SELECT * FROM rf_kanban_boards WHERE user_id = ?", $this->CI->user->id)->result();
    }


    function project_data($projectId)
    {
        return $this->CI->db->query("SELECT * FROM rf_kanban_projects WHERE id = ?", $projectId)->row();
    }


    function project_boards($projectId)
    {
        return $this->CI->db->query("SELECT * FROM rf_kanban_boards WHERE project_id = ?", $projectId)->result();
    }


    function board_sections($boardId)
    {
        return $this->CI->db->query("SELECT * FROM rf_kanban_sections WHERE board_id = ?", $boardId)->result();
    }


    function task_files($taskId)
    {
        return $this->CI->db->query("SELECt * FROM kanban_fileUploads WHERE taskId = $taskId")->result();
    }


    function task_labels()
    {
        return array('#34B27D', '#DBDB57', '#E09952', '#CB4D4D', '#4D77CB', '#93C');
    }

    function amISubscribedToTask($taskId)
    {
        $myId = $this->CI->user->id;
        return $this->CI->db->query("SELECT id FROM rf_kanban_tasks_subscriptions WHERE user_id = $myId AND task_id = $taskId LIMIT 1")->row('id');
    }
}