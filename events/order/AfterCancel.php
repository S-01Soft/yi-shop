<?php

namespace app\shop\events\order;

use app\system\model\admin\UserModel;
use support\excpetion\Exception;
use app\shop\model\api\OrderModel;
use app\shop\model\api\OrderProductModel;
use app\shop\model\api\ProductSkuModel;
use app\shop\logic\api\ProductLogic;

class AfterCancel
{
    public function handle($payload)
    {
        $order = $payload->order;

        if ((float)$order->use_score != 0) {
            UserModel::addScore($order->user_id, $order['use_score'], '订单退款，积分返还。[' . $order->order_sn . ']');
        }

        if ((float)$order->money_price != 0) {
            UserModel::addMoney($order->user_id, $order['money_price'], '订单退款，余额返还。[' . $order->order_sn . ']');
        }


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