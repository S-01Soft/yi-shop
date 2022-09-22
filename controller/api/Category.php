<?php

namespace app\shop\controller\api;

class Category extends Base 
{
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\CategoryLogic;
    }

    public function index()
    {
        try {
            $data = $this->logic->getTreeArray();
            return $this->success($data);
        } catch (\support\exception\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}