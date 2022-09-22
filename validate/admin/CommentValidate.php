<?php
namespace app\shop\validate\admin;

use yi\Validate;

class CommentValidate extends Validate
{

    public function __construct()
    {
        $this->field = [
            'id' => lang('Id'),
            'user_id' => lang('User Id'),
            'order_product_id' => lang('Order Product Id'),
            'order_id' => lang('Order Id'),
            'product_id' => lang('Product Id'),
            'sku_id' => lang('Sku Id'),
            'images' => lang('Images'),
            'content' => lang('Content'),
            'star' => lang('Star'),
            'created_at' => lang('Created At'),
            'updated_at' => lang('Updated At'),
            'deleted_at' => lang('Deleted At'),
            'status' => lang('Status')
        ];
    }

    protected $rule =  [
        'images' => 'length:0,1000',
        'status' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['images', 'status'],
        'edit' => ['images', 'status'],
    ];    
}