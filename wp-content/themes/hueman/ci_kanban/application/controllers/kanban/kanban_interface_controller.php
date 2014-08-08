<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kanban_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
        $this->load->model('kanban/kanban_interface_model');
    }

    function index()
    {
        if($this->user->id) redirect('/projects');
        $this->framework->renderComponent('kanban', 'kanban_index_view');
    }



    function create_view()
    {
        $view               = $this->input->get('view');
        $data               = array();
        $data['projectId']  = $this->input->get('projectId');
        $data['boardId']    = $this->input->get('boardId');
        $data['sectionId']  = $this->input->get('sectionId');
        $data['taskId']     = $this->input->get('taskId');

        if($data['taskId']) $data['task'] = $this->kanban_interface_model->task_data($data['taskId']);

        $this->load->view('components/kanban/views/create_views/kanban_createView_' . $view, $data);
    }



    function display_projects()
    {
        $data['projects']    = $this->kanban->my_projects();
        $this->framework->renderComponent('kanban', 'kanban_display_projects', $data);
    }


    function project_boards()
    {
        $projectId          = $this->uri->segment(2);
        if(!$projectId) redirect('/projects');

        $data['project']    = $this->kanban->project_data($projectId);
        $data['boards']     = $this->kanban->project_boards($projectId);
        $this->framework->renderComponent('kanban', 'kanban_display_boards', $data);
    }


    function display_board()
    {
        $boardId = $this->uri->segment(2);
        $data    = $this->kanban_interface_model->board_data($boardId);
        $this->framework->renderComponent('kanban', 'kanban_display_board', $data);
    }
}