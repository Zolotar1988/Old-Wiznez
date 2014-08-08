<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Misc_lib_interface_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
    }


    //================================================================================================================================================================
    // Growth
    //================================================================================================================================================================
    function growth_manageFbPostToWall()
    {
        if($this->misc->growh_checkForGivenIncentive_redis($this->user->id, 'fbPostToWallCoins') || !$_GET['r'])
        {
            $this->framework->setNotificationCookie(lang('errorOccured'), 'danger');
        }
        else
        {
            # Give the user coins
            $coinsToGive_inCurrency = $this->misc->growthIncentives_coinsGivenPerAction_inCurrency('fbPostToWallCoins');
            $this->misc->coins_addFunds($this->user->id, $coinsToGive_inCurrency, 'fbConnectCoins');
            $this->framework->setNotificationCookie(langWithVar('growthHack-coinsGiven', $this->misc->coins_calculate_ExchangeRate_fromCurr($coinsToGive_inCurrency)), 'info');

            # Now mark the incentive as given
            $this->misc->growth_recordIncentive($this->user->id, 'fbPostToWallCoins');
            $this->misc->growth_recordIncentive_redis($this->user->id, 'fbPostToWallCoins');
        }
    }

    function growth_manageFbPostToWall_deldel()
    {
        echo ($this->ci_redis->del($this->accountconnect->siteName . ':growth:incentives:' . 'fbPostToWallCoins' . ':' . 1) ? TRUE : FALSE);
    }






    //================================================================================================================================================================
    // Whatever
    //================================================================================================================================================================

    function sendPulse()
    {
        $this->misc->sendPulse('pulse');
    }


    function logPulseClick()
    {
        $userId = $this->input->get('userId');
        if(!is_numeric($userId)) die;

        $data['userId']     = $this->user->id;
        $data['ip']         = $_SERVER['REMOTE_ADDR'];
        $data['time']       = getCurrentDateTime();
        $data['clickedOn']     = $userId;

        $this->ci_redis->lpush($this->misc->pulse_clickFeedLocation(), json_encode($data));
    }


    function setStyleOption()
    {
        $index  = $this->input->get('index');
        $this->misc->styling_setStyleOption($index);
    }

    function desiredFrontpage_set()
    {
        $which = $this->input->get('which');
        if($which != 'photos' && $which != 'rendezvous') { redirect('/'); die; }

        $this->misc->desiredFrontpage_set($this->user->id, $which);
        $this->framework->setNotificationCookie(lang('menu-frontpageChanged'), 'info');
        redirect('/');
    }
}