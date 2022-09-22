<?php

namespace app\shop\events\order;

use app\system\model\admin\UserModel;

class UseScore
{
    public function handle($payload)
    {
        $order = $payload->order;
        if ((float)$order->use_score != 0) {
            UserModel::addScore($order->user_id, -$order['use_score'], '积分抵扣,订单:' . $order->order_sn);
        }
    }
}