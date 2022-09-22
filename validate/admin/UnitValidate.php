<?php
namespace app\shop\validate\admin;

use yi\Validate;

class UnitValidate extends Validate
{
    protected $rule =  [
        'name|名称' => 'require|length:0,50',
        'code|符号' => 'require|length:0,50',
        'is_default|是否默认' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['name', 'code', 'is_default'],
        'edit' => ['name', 'code', 'is_default'],
    ];    
}