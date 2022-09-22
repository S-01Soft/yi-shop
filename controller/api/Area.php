<?php

namespace app\shop\controller\api;

class Area extends Base 
{
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\api\AreaLogic;
    }

    public function index()
    {
        try {
            $data = $this->logic->getList();
            return $this->success($data);
        } catch (\support\exception\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}