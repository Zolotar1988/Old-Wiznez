<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace_actions_model extends CI_Model
{
    function __Construct()
    {
        parent::__construct();
    }


    # This is the function for saving a new item into the database
    function item_create($inputs, $temporary_id)
    {
        # Addd some stuff
        $inputs['user_id'] = $this->user->id;
        $inputs['city']    = $this->user->currentUserData->city;

        # Insert it
        $this->db->insert('marketplace_products', $inputs);

        # Get ID
        $productId = $this->db->insert_id();

        # Index
        $this->marketplace->product_index($productId);

        # Connect the images
        $this->db->where('temporary_id', $temporary_id);
        $this->db->update('marketplace_images', array('product_id' => $productId));

        # Return
        return $productId;
    }


    # Delete an item
    function product_delete($userId, $itemId)
    {
        # Run the query, but ofc. check the userId
        return $this->db->query("UPDATE marketplace_items SET isActive = 0 WHERE user_id = $userId AND id = $itemId LIMIT 1");
    }


    # Save an uploaded image with temp id
    function saveImage($userId, $photoData)
    {

    }
}