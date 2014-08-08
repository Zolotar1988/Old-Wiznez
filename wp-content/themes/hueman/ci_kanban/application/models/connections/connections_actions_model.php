<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Connections_actions_model extends CI_model
{

    public function __construct()
    {
    }


    function build_connection($connection_from, $connection_to, $connection_type)
    {
        # First insert the connection into the database
        $this->db->query("INSERT INTO connections_$connection_type (connection_from, connection_to) VALUES($connection_from, $connection_to) ON DUPLICATE KEY IGNORE");

        # Secondly set that these 2 users are connected and which connection they formed
        $this->ci_redis->set($this->redis_locations->connections_connectionRelationsLocation($connection_from, $connection_to, $connection_type), 1);

        # Third, add that the "from" has this connection (into their connections list)
        $this->ci_redis->sadd($this->redis_locations->connections_connectedToLocation_from($connection_from, $connection_type), $connection_to);

        # Fourth, the opposite of the last, add this connection to the "to"
        $this->ci_redis->sadd($this->redis_locations->connections_connectedToLocation_to($connection_from, $connection_type), $connection_to);

        # Return
        return TRUE;
    }


    function destroy_connection($connection_from, $connection_to, $connection_type)
    {
        # First, destroy the connection in the database
        $this->db->query("DELET FROM connections_$connection_type WHERE connection_from = $connection_from AND connection_to = $connection_to LIMIT 1");

        # Secondly, destroy the connection on Redis
        $this->ci_redis->del($this->redis_locations->connections_connectionRelationsLocation($connection_from, $connection_to, $connection_type));

        # Third, delete the connection from the "from" list connection
        $this->ci_redis->srem($this->redis_locations->connections_connectedToLocation_from($connection_from, $connection_type), $connection_to);

        # Fourth, same as the last again except the "to"
        $this->ci_redis->srem($this->redis_locations->connections_connectedToLocation_to($connection_from, $connection_type), $connection_to);

        # Return
        return TRUE;
    }
}