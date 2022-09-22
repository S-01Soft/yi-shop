<?php

namespace app\shop\jobs;

use app\shop\model\api\OrderModel;
use app\shop\model\api\OrderProductModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\logic\api\ProductLogic;

class OrderAfterCancel extends Consumer
{
    public $queue = 'ShopAfterOrderCancel';

    public function consume($params)
    {
        $order = OrderModel::where('order_sn', $params['order_sn'])->first();
        if (!$order) return ;
        $list = OrderProductModel::with(['product', 'sku'])->where('order_id', $order->id)->get();
        if ($order->is_pay == 1) {
            $logic = ProductLogic::instance();
            $logic->updateProductSold($list, 'DEC'); // 减少销量
        }
        foreach ($list as $row) {
            if ((float)$row->freeze_stock > 0) ProductSkuModel::where('id', $row->sku_id)->increment('stock', $row->freeze_stock);
        }
    }
}