<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tagsystem_actions_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    function redis_tagLocation($tag)
    {
        return $this->config->item('site_name') . ':tagsystem_v1:tags:' . $tag;
    }


    function processTags($component, $tags, $userId)
    {
        foreach($tags as $tag) $this->processTag($tag, $userId);

        # Component functions
        if($component == 'profile')
        {
            if($this->user->userData($userId)->profile_type == 'intern')
            {
                $profile_tags = json_encode($tags);
                $this->db->query("UPDATE `users_data-type_intern` SET tags = '$profile_tags' WHERE user_id = $userId LIMIT 1");
            }
            elseif($this->user->userData($userId)->profile_type == 'startup')
            {

            }
        }

        return TRUE;
    }


    function processTag($tag, $userId)
    {
        # Lowercase it so everything is always the same
        $tag    = strtolower(trim($tag));

        # Return if it's nothing
        if(!$tag || $tag == 'undefined') return FALSE;

        # First check if it exists or not
        $tag_rdis   = json_decode($this->ci_redis->get($this->redis_tagLocation($tag)));
        $exists     = ($this->ci_redis->get($this->redis_tagLocation($tag)) ? TRUE : FALSE);
        $exists     = (empty($tag_rdis) ? FALSE : TRUE);


        if($exists)
        {
//            $redis_tag              = json_decode($this->ci_redis->get($this->redis_tagLocation($tag)));
//            $timesUsed              = $redis_tag->timesUsed + 1;
//            $tagId                  = $redis_tag->id;
//            $redis_tag->timesUsed   = $timesUsed;
//            $redis_tag              = json_encode($redis_tag);

            $tag = $this->db->escape($tag);
            $this->db->query("UPDATE tagsystem_tags SET times_used = times_used+1 WHERE tag = $tag");

//            $this->ci_redis->set($this->redis_tagLocation($tag), $redis_tag);
            return TRUE;
        }

        # Add it to MySQL (hehehehehehehehehe)
        $insert_query   = $this->db->insert_string('tagsystem_tags', array('tag' => $tag, 'addedBy' => $userId));
        $insert_query   = str_replace('INSERT INTO','INSERT IGNORE INTO',$insert_query);
        $this->db->query($insert_query);

        $tagId          = $this->db->insert_id();
        $tag_escaped    = $this->db->escape($tag);
        $tagId          = ($tagId ? $tagId : $this->db->query("SELECT id FROM tagsystem_tags WHERE tag = $tag_escaped LIMIT 1")->row('id'));

        # Add it to Redis
        $this->ci_redis->set($this->redis_tagLocation($tag), json_encode($this->db->query("SELECT * FROM tagsystem_tags WHERE id = $tagId LIMIT 1")->row()));

        # Add to elsaticsearch
//        $this->elastic->index_tag($tag, $tagId, 1);
    }
}