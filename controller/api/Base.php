<?php

namespace app\shop\controller\api;

abstract class Base extends \yi\controller\ApiController
{
    public function before()
    {
        parent::before();
        event('ShopApiController');
        $this->auth = get_user();
    }
}