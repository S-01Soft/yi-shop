<?php

namespace app\shop\controller\index;

class User extends Base 
{
    public $needLogin = ['*'];

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\UserLogic;
    }
}