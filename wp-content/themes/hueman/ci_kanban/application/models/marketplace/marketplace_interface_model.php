<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace_interface_model extends CI_Model
{

    function __Construct()
    {
        parent::__construct();
    }


    # Load products - maybe from a cat/subcat
    function products($categoryId = FALSE, $subcategoryId = FALSE)
    {
        if($categoryId)
            $products = $this->marketplace->products_byCategory($categoryId);
        elseif($subcategoryId)
            $products = $this->marketplace->products_bySubategory($categoryId);
        else
            $products = $this->marketplace->products_browse();

        return $products;
    }


    # Single product view
    function product($productId)
    {
        return $this->marketplace->product_data($productId);
    }
}