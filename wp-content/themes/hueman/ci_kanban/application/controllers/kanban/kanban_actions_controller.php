<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kanban_actions_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('kanban/kanban_actions_model');
    }


    function create_project()
    {
        $inputs['user_id']      = $this->user->id;
        $inputs['title']        = $this->input->post('title');
        $inputs['description']  = $this->input->post('description');
        echo $this->kanban_actions_model->create_project($inputs);
    }


    function create_board()
    {
        $inputs['user_id']      = $this->user->id;
        $inputs['project_id']   = $this->input->post('project_id');
        $inputs['title']        = $this->input->post('title');
        $inputs['description']  = $this->input->post('description');
        echo $this->kanban_actions_model->create_board($inputs);
    }


   function create_section()
    {
        $inputs['board_id']     = $this->input->post('board_id');
        $inputs['title']        = $this->input->post('title');
        $this->kanban_actions_model->create_section($inputs);
    }


    function create_task()
    {
        $inputs['user_id']      = $this->user->id;
        $inputs['board_id']     = $this->input->post('board_id');
        $inputs['project_id']   = $this->input->post('project_id');
        $inputs['section_id']   = $this->input->post('section_id');
        $inputs['title']        = $this->input->post('title');
        $inputs['responsible']  = $this->input->post('responsible');
        $inputs['description']  = $this->input->post('description');
        $inputs['due_date']     = $this->input->post('due_date');
        $task = $this->kanban_actions_model->create_task($inputs);

        $this->load->view('renderers/kanban/task_row', array('task' => $task));
    }


    function board_moveToProject()
    {
        $this->kanban_actions_model->board_moveToProject($this->input->post('id'), $this->input->post('to'));
    }


    function section_moveCards()
    {
        $this->kanban_actions_model->section_moveCards($this->input->post('from'), $this->input->post('to'));
    }


    function delete_section()
    {
        $sectionId = $this->input->post('sectionId');
        $this->kanban_actions_model->delete_section($sectionId);
    }


    function task_copy()
    {
        $taskId = $this->input->post('taskId');
        $this->kanban_actions_model->task_copy($taskId);
    }


    function task_subscribeToggle()
    {
        $taskId = $this->input->post('taskId');
        echo $this->kanban_actions_model->task_subscribeToggle($taskId);
    }


    function card_todoAdd()
    {
        $todo = $this->input->post('todo');
        $taskId = $this->input->post('taskId');
        echo $this->kanban_actions_model->card_todoAdd($taskId, $todo);
    }


    function card_todoRemove()
    {
        $taskId = $this->input->post('taskId');
        $todoId = $this->input->post('taskId');
        echo $this->kanban_actions_model->task_subscribeToggle($taskId);
    }



    function card_addMember()
    {
        $taskId = $this->input->post('taskId');
        $userId = $this->input->post('userId');
        $this->kanban_actions_model->card_addMember($taskId, $userId);
    }


    function edit_dueDate()
    {
        $taskId = $this->input->post('taskId');
        $dueDate = $this->input->post('dueDate');
        $this->kanban_actions_model->edit_dueDate($taskId, $dueDate);
    }



    function task_addLabel()
    {
        $taskId = $this->input->post('taskId');
        $label = $this->input->post('label');
        $this->kanban_actions_model->task_addLabel($taskId, $label);
    }


    function task_move()
    {
        $boardId = $this->input->post('boardId');
        $taskId  = $this->input->post('taskId');
        $sectionId  = $this->input->post('sectionId');
        $this->kanban_actions_model->task_move($taskId, $boardId, $sectionId);
        $this->framework->setNotificationCookie('Task has been moved', 'info');
    }


    function task_archive()
    {
        $taskId = $this->input->post('taskId');
        $this->kanban_actions_model->task_archive($taskId);
    }


    function task_delete()
    {
        $taskId = $this->input->post('taskId');
        $this->kanban_actions_model->task_delete($taskId);
    }

    function task_update_section()
    {
        $taskId         = $this->input->post('taskId');
        $sectionId      = $this->input->post('sectionId');
        $sectionOrder   = $this->input->post('sectionOrder');
        $this->kanban_actions_model->task_update_section($taskId, $sectionId, $sectionOrder);
    }
}