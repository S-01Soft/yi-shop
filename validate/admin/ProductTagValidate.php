<?php
namespace app\shop\validate\admin;

use yi\Validate;

class ProductTagValidate extends Validate
{
    protected $rule =  [
        'title|名称' => 'require|length:0,255',
        'desc|描述' => 'require|length:0,255',
        'weigh|排序' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title', 'desc', 'weigh'],
        'edit' => ['title', 'desc', 'weigh'],
    ];    
}