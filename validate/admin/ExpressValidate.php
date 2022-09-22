<?php
namespace app\shop\validate\admin;

use yi\Validate;

class ExpressValidate extends Validate
{
    protected $rule =  [
        'code|快递公司编号' => 'require|length:0,50',
        'name|快递公司名称' => 'require|length:0,100',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['code', 'name'],
        'edit' => ['code', 'name'],
    ];    
}