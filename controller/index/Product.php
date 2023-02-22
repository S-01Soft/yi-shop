<?php

namespace app\shop\controller\index;

class Product extends Base 
{
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\api\ProductLogic::instance();
    }

    public function list()
    {
        $params = $this->request->get();
        try {
            $data = ev('ShopSearch', $params);
            $paginator = \support\Paginator::create($data['total'], $data['per_page']);
            $categroies = \app\shop\logic\api\CategoryLogic::instance()->getTreeArray();
            $this->assign('product_categories', $categroies);
            $this->assign('category_id', request()->get('category_id'));
            $this->assign('paginator', $paginator);
            $this->assign('data', $data);
            return $this->fetch('shop/list');
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function info()
    {
        $id = $this->request->get('id');
        try {
            $data = $this->logic->info($id);
            $payload = (object) [
                'product' => $data,
                'user' => get_user()->getUser()
            ];
            event('ShopViewProduct', $payload);
            $this->assign('product', $payload->product);
            return $this->fetch('shop/info');
        } catch(Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}