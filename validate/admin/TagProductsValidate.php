<?php
namespace app\shop\validate\admin;

use yi\Validate;

class TagProductsValidate extends Validate
{
    protected $rule =  [
        'tag_id|标签' => 'require',
        'product_id|商品' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['tag_id', 'product_id'],
        'edit' => ['tag_id', 'product_id'],
    ];    
}