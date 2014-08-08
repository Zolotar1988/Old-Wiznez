<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kanban_interface_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    function board_data($boardId, $full = TRUE)
    {
        $response = array();
        $response['board'] = $this->db->query("SELECT * FROM rf_kanban_boards WHERE id = $boardId")->row();
        if(!$full) return $response;

        $response['project'] = $this->db->query("SELECT * FROM rf_kanban_projects WHERE id = " . $response['board']->project_id)->row();

        $response['sections'] = $this->db->query("SELECT * FROM rf_kanban_sections WHERE board_id = $boardId")->result();

        $response['tasks'] = $this->db->query("SELECT * FROM rf_kanban_tasks WHERE board_id = $boardId ORDER BY section_order ASC")->result();

        foreach($response['sections'] as $section)
        {
            $response['section_tasks'][$section->id] = array();
        }

        foreach($response['tasks'] as $task)
        {
            $response['section_tasks'][$task->section_id][] = $task;
        }

        return $response;
    }



    function task_data($taskId)
    {
        return $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1")->row();
    }
}