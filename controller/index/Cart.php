<?php

namespace app\shop\controller\index;

class Cart extends Base 
{
    public $needLogin = ['*'];

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\CartLogic;
    }

    public function index()
    {
        try {
            $data = $this->logic->getList();
            $address_list = \app\shop\logic\api\UserLogic::instance()->getAddress();
            $this->assign('address_list', $address_list);
            $this->assign('data', $data);
            return $this->fetch('shop/user/cart');
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}