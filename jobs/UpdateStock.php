<?php

namespace app\shop\jobs;

use app\shop\model\api\OrderModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\model\api\OrderProductModel;
use app\shop\logic\api\ProductLogic;

/**
 * 更新库存
 */
class UpdateStock extends Consumer
{
    public $queue = 'ShopUpdateStock';

    public function consume($params)
    {
        $type = $params['type'];
        $method = $params['method'];
        $order = OrderModel::where('order_sn', $params['order_sn'])->first();
        if (!$order) return ;
        $orderProducts = OrderProductModel::with(['product', 'sku'])->where('order_id', $order->id)->get();
        $logic = ProductLogic::instance();
        foreach ($orderProducts as $item) {
            if ($type == ev('ShopStockType', $item->product)) {
                $item->stock_type = $type;
                $item->freeze_stock = $item->quantity;
                $item->save();
                ProductSkuModel::where('id', $item->sku_id)->decrement('stock', $item->quantity);
            }
        }
    }
}