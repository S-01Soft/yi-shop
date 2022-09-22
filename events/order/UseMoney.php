<?php

namespace app\shop\events\order;

use app\system\model\admin\UserModel;

class UseMoney
{
    public function handle($payload)
    {
        $order = $payload->order;
        if ((float)$order->money_price != 0) {
            UserModel::addMoney($order->user_id, -$order['money_price'], '余额抵扣,订单:' . $order->order_sn);
        }
    }
}