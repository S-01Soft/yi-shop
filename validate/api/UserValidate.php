<?php
namespace app\shop\validate\api;

use yi\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'username|账号' => 'require|length:5,20',
        'password|密码' => 'require|length:6,32',
        'address' => 'require|length:1,40',
        'address_id' => 'number',
        'street|街道' => 'require|length:1,40',
        'phone|手机号' => 'require',
        'contactor_name|姓名' => 'require|length:2,6',
        'nickname|昵称' => 'chsDash|length:2,6'
    ];

    protected $scene = [
        'login' => ['username', 'password'],
        'edit' => ['address', 'address_id', 'street', 'phone', 'contactor_name'],
        'editInfo' => ['nickname', 'password' => 'alphaDash|length:8,16'],
        'deleteAddress' => ['address_id']
    ];   
}