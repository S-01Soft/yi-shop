<?php
namespace app\shop\validate\admin;

use yi\Validate;

class PageTemplateValidate extends Validate
{
    protected $rule =  [
        'title|模板说明' => 'require|length:0,500'
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title'],
        'edit' => ['title'],
    ];    
}