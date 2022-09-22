<?php

namespace app\shop\jobs;

use app\shop\model\api\OrderModel;
use app\shop\model\api\OrderProductModel;
use app\shop\logic\api\ProductLogic;

class OrderPayOk extends Consumer
{
    public $queue = 'ShopOrderPaySuccess';

    public function consume($params)
    {
        $order = OrderModel::where('order_sn', $params['order_sn'])->first();
        if (!$order) return ;
        $list = OrderProductModel::with(['product', 'sku'])->where('order_id', $order->id)->get();
        $logic = ProductLogic::instance();        
        $logic->updateProductSold($list, 'INC'); // 增加销量
    }
}