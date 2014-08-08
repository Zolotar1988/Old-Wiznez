<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace
{

    public function __construct()
    {
        $this->CI =& get_instance();
    }


    function product_dataLocation($productId)
    {
        return $this->CI->config->item('site_name') . 'products_v2:data:' . $productId;
    }


    function product_index($productId)
    {
        $productData = $this->CI->db->query("SEELCT * FROM marketplace_products WHERE id = $productId")->row();
        $this->CI->ci_redis->set($this->product_dataLocation($productId), json_encode($productData));
    }


    function product_data($productId)
    {
        return json_decode($this->CI->ci_redis->get($this->product_dataLocation($productId)));
    }




    function categories()
    {
        return $this->CI->db->query("SELECT * FROM marketplace_categories")->result();
    }


    function subcategories($parentId)
    {
        return $this->CI->db->query("SELECT * FROM marketplace_subcategories WHERE parent = $parentId")->result();
    }


    function products_browse()
    {
        return $this->CI->db->query("SELECT * FROM marketplace_products")->result();
    }

    function products_byCategory($categoryId)
    {
        return $this->CI->db->query("SELECT * FROM marketplace_products WHERE category = $categoryId")->result();
    }


    function products_bySubcategory($categoryId)
    {
        return $this->CI->db->query("SELECT * FROM marketplace_products WHERE subcategory = $categoryId")->result();
    }
}