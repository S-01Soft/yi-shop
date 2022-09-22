<?php
namespace app\shop\validate\admin;

use yi\Validate;

class ServiceTagValidate extends Validate
{
    protected $rule =  [
        'title|名称' => 'require|length:0,255',
        'description|描述' => 'require|length:0,255',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title', 'description'],
        'edit' => ['title', 'description'],
    ];    
}