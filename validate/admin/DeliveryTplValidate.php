<?php
namespace app\shop\validate\admin;

use yi\Validate;

class DeliveryTplValidate extends Validate
{
    protected $rule =  [
        'title|标题' => 'require|length:0,255',
        'type|计费方式' => 'require',
        'sort|排序' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title', 'type', 'sort'],
        'edit' => ['title', 'type', 'sort'],
    ];    
}