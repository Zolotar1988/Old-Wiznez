<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['default_controller'] = 'kanban/kanban_interface_controller/index';
$route['error_404']= 'showerror/error_404';





$route['create_view'] = 'kanban/kanban_interface_controller/create_view';


$route['projects'] = 'kanban/kanban_interface_controller/display_projects';
$route['project/(:num)'] = 'kanban/kanban_interface_controller/project_boards';
$route['board/(:num)'] = 'kanban/kanban_interface_controller/display_board';