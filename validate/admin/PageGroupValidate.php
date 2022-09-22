<?php
namespace app\shop\validate\admin;

use yi\Validate;

class PageGroupValidate extends Validate
{

    public function __construct()
    {
        $this->field = [
            'id' => lang('Id'),
            'title' => lang('Title'),
            'content' => lang('Content'),
            'created_at' => lang('Create Time')
        ];
    }

    protected $rule =  [
        'title' => 'require|length:0,255',
        'content' => 'require|length:0,255',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['title'],
        'edit' => ['title'],
    ];    
}