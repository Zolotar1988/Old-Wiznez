<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Daystart_interface_controller extends CI_Controller
{

    function __Construct()
    {
        parent::__construct();
    }

    # Index
    function index()
    {
        $data['queue']      = $this->daystart->fetch_queue($this->user->id);
        $data['current']    = $this->daystart->fetch_queue($this->user->id, TRUE);

        $this->framework->renderComponent('daystart', 'daystart_index_view', $data);
    }


    # Builds queue
    function buildQueue() { $this->daystart->buildQueue(); }

    # Runs the cron
    function run_cron() { $this->daystart->run_cron(); }

    # Finishes the day
    function finish_day() { $this->daystart->finish_day(); }

    # Tests organization hours
    function run_test() { $this->daystart->run_test(); }

    # Activated the next queue item
    function activate_nextQueueItem() { $this->daystart->activate_nextQueueItem(); }
}