<?php

namespace app\shop\controller\api;
use support\exception\Exception;

class Product extends Base
{
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\api\ProductLogic::instance();
    }

    public function qrcode()
    {
        $id = request()->get('id');
        $this->logic->getWxaQrcode($id);
        return $this->success();
    }

    public function index()
    {
        $id = $this->request->post('id');
        try {
            $data = $this->logic->info($id);
            $payload = (object) [
                'product' => $data,
                'user' => get_user()->getUser()
            ];
            event('ShopViewProduct', $payload);
            return $this->success($payload->product);
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function sku()
    {
        $id = $this->request->post('id');
        try {
            $data = $this->logic->sku($id);
            return $this->success($data);
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getList()
    {        
        $params = $this->request->get();
        try {
            $data = ev('ShopSearch', $params);
            return $this->success($data);
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    
    public function getComments()
    {
        $params = $this->request->post();
        try {
            $data = $this->logic->getComments($params);
            return $this->success($data);
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}