<?php
namespace app\shop\validate\admin;

use yi\Validate;

class ArticleCategoryValidate extends Validate
{
    protected $rule =  [
        'name|分类名称' => 'require|length:0,255',
        'description|分类描述' => 'length:0,255',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['name', 'description'],
        'edit' => ['name', 'description'],
    ];    
}