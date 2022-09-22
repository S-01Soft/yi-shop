<?php

namespace app\shop\model\admin;

class UserModel extends Model 
{
    protected $table = "user";

    protected $hidden = ["uid", "password", "salt", "avatar", "email", "mobile", "score", "money", "loginfailure", "logintime", "loginip", "created_at", "updated_at", "token", "status"];
}