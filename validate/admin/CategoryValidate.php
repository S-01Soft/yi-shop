<?php
namespace app\shop\validate\admin;

use yi\Validate;

class CategoryValidate extends Validate
{
    protected $rule =  [
        'parent_id|上级分类' => 'require',
        'name|分类名称' => 'require|length:0,30',
        'image|图标' => 'length:0,500',
        'sort|排序' => 'require',
        'status|启用' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['parent_id', 'name', 'image', 'sort', 'status'],
        'edit' => ['parent_id', 'name', 'image', 'sort', 'status'],
    ];    
}