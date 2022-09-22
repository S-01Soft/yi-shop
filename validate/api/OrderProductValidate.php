<?php

namespace app\shop\validate\api;

class OrderProductValidate extends Validate 
{
    protected $rule = [
        'id' => 'require|number',
        'star' => 'require|number',
        'content' => 'max:250'
    ];

    protected $message = [
    ];

    protected $scene = [
        'comment' => ['id', 'star', 'content']
    ];
}