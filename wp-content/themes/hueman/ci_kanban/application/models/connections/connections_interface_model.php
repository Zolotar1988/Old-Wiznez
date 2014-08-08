<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connections_interface_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }

    function contacts($userId)
    {
        return $this->ci_redis->smembers($this->idmag->user_followingDataLocation($userId));
    }

    function visitors($userId)
    {
        return $this->ci_redis->lrange($this->user->redis_visitorsLocation($userId), 0, 50);
    }

    function attracted()
    {
        return $this->ci_redis->lrange($this->misc->attraction_redis_userVotedOnFeed($this->user->id, 'attracted'), 0, -1);
    }

    function attracted_toMe()
    {
        return $this->ci_redis->lrange($this->misc->attraction_redis_userVotedOnMeFeed($this->user->id, 'attracted'), 0, -1);
    }
}