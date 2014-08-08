<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kanban_actions_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    function create_project($inputs)
    {
        $this->db->insert('rf_kanban_projects', $inputs);
        return $this->db->insert_id();
    }


    function create_board($inputs)
    {
        $this->db->insert('rf_kanban_boards', $inputs);
        return $this->db->insert_id();
    }


    function create_section($inputs)
    {
        $this->db->insert('rf_kanban_sections', $inputs);
    }


    function create_task($inputs)
    {
        $this->db->insert('rf_kanban_tasks', $inputs);
        return $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = " . $this->db->insert_id())->row()    ;
    }


    function board_moveToProject($id, $to)
    {
        $this->db->query("UPDATE rf_kanban_boards SET project_id = $to WHERE id = $id");
    }


    function section_moveCards($from, $to)
    {
        $this->db->query("UPDATE rf_kanban_tasks SET section_id = $to WHERE section_id = $from");
    }


    function delete_section($sectionId)
    {
        $this->db->query("DELETE FROM rf_kanban_sections WHERE id = $sectionId LIMIT 1");
        $this->db->query("DELETE FROM rf_kanban_tasks WHERE section_id = $sectionId");
    }


    function task_move($taskId, $boardId, $sectionId)
    {
        $this->db->query("UPDATE rf_kanban_tasks SET board_id = $boardId, section_id = $sectionId WHERE id = $taskId");
    }


    function task_copy($taskId)
    {
        $task = $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1")->row_array();
        unset($task['id']);
        $this->db->insert('rf_kanban_tasks', $task);
    }


    function task_subscribeToggle($taskId)
    {
        $myId = $this->user->id;
        if($this->kanban->amISubscribedToTask($taskId))
        {
            $this->db->query("DELETE FROM rf_kanban_tasks_subscriptions WHERE user_id = $myId AND task_id = $taskId");
            return 'Subscribe';
        }
        else
        {
            $this->db->query("INSERT INTO rf_kanban_tasks_subscriptions (task_id, user_id) VALUES ($taskId, $myId)");
            return 'Unsubscribe';
        }
    }


    function edit_dueDate($taskId, $dueDate)
    {
        $dueDate = date("Y-m-d", strtotime($dueDate));
        $this->db->query("UPDATE rf_kanban_tasks SET due_date = '$dueDate' WHERE id = $taskId LIMIT 1");
    }


    function card_todoAdd($taskId, $todo)
    {
        $task = $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1")->row();
        $todos = json_decode($task->todo, TRUE);
        if(!$todos) $todos = array();
        if(in_array($todo, $todos)) return TRUE;
        $todos[] = $todo;
        $todos = json_encode($todos);
        $this->db->query("UPDATE rf_kanban_tasks SET todo = '$todos' WHERE id = $taskId LIMIT 1");
    }



    function card_addMember($taskId, $userId)
    {
        $task = $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1")->row();
        $users = json_decode($task->team_members, TRUE);
        if(!$users) $users = array();
        if(in_array($userId, $users)) return TRUE;
        $users[] = $userId;
        $users = json_encode($users);
        $this->db->query("UPDATE rf_kanban_tasks SET team_members = '$users' WHERE id = $taskId LIMIT 1");
    }


    function task_addLabel($taskId, $label)
    {
        $task = $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1")->row();
        $labels = json_decode($task->labels, TRUE);
        if(!$labels) $labels = array();
        if(in_array($label, $labels)) return TRUE;
        $labels[] = $label;
        $labels = json_encode($labels);
        $this->db->query("UPDATE rf_kanban_tasks SET labels = '$labels' WHERE id = $taskId LIMIT 1");
    }


    function task_archive($taskId)
    {
        $task = $this->db->query("SELECT * FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1")->row_array();
        $this->db->query("DELETE FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1");
        unset($task['id']);
        $this->db->insert('rf_kanban_tasks_archive', $task);
    }


    function task_delete($taskId)
    {
        $this->db->query("DELETE FROM rf_kanban_tasks WHERE id = $taskId LIMIT 1");
    }

    function task_update_section($taskId, $sectionId, $sectionOrder)
    {
        $this->db->query("UPDATE rf_kanban_tasks SET section_id = $sectionId, section_order = $sectionOrder WHERE id = $taskId LIMIT 1");
    }
}