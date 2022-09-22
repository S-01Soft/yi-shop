<?php

namespace app\shop\model\api;

class UserModel extends Model 
{
    protected $table = 'user';

    protected $visible = ['id', 'username', 'nickname', 'money', 'score', 'avatar'];

    public function getAvatarAttribute($value) 
    {
        return empty($value) ? fixurl('/static/images/missing-face.png') : $value;
    }
}