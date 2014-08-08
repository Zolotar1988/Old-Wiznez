<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Marketplace_interface_controller extends CI_Controller
{
    function __Construct()
    {
        parent::__construct();
        $this->load->model('marketplace/marketplace_interface_model');
    }


    function index()
    {
        $data['products'] = $this->marketplace_interface_model->products();

        $this->framework->renderComponent('marketplace', 'marketplace_index_view', $data);
    }


    function category()
    {
        $data['category']   = $this->framework->uriSegment1;
        $data['products']   = $this->marketplace_interface_model->products($data['$category']);

        $this->framework->renderComponent('marketplace', 'marketplace_index_view', $data);
    }


    function subcategory()
    {
        $data['subcategory']    = $this->framework->uriSegment1;
        $data['products']       = $this->marketplace_interface_model->products(FALSE, $data['$subcategory']);

        $this->framework->renderComponent('marketplace', 'marketplace_index_view', $data);
    }


    function product()
    {
        $data['productId']  = $this->framework->uriSegment1;
        $data['products']   = $this->marketplace_interface_model->product($data['productId']);

        $this->framework->renderComponent('marketplace', 'marketplace_product_view', $data);
    }


    function create()
    {
        $data['imageUploadConfig'] = array(
            'multi' => 'false',
            'buttonText' => ($this->user->currentUserData->profile_type == 'startup' ? 'Upload logo' : 'Upload your photo'),
            'uploadScript' => '/imageupload/imageupload_actions_controller/upload?ccn=marketplace',
            'onUploadComplete' => 'marketplace.imageUpload.onUploadComplete',
            'onProgress' => 'marketplace.imageUpload.onProgress',
            'onUpload' => 'marketplace.imageUpload.onUpload',
        );

        $data['temporary_id'] = $this->user->id . '_' . md5(microtime()) . '_' . rand(1000,9999);
        $this->framework->renderComponent('marketplace', 'marketplace_create_view', $data);
    }
}
