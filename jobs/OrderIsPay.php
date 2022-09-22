<?php

namespace app\shop\jobs;

use app\shop\model\api\OrderModel;
use app\system\model\admin\UserModel;

/**
 * 判断订单是否支付，未支付则取消订单，释放库存
 */
class OrderIsPay extends Consumer
{
    public $queue = 'ShopOrderIsPay';

    public function consume($payload)
    {
        $order = OrderModel::where('id', $payload['id'])->first();
        if (!$order) return;
        if ($order->is_pay) return ;
        if ($order->status !== 0) return ;
        ev('ShopOrderCancel', $order->order_sn);
    }
}