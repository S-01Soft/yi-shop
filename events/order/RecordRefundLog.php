<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordRefundLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $desc = '订单退款: ' . $order->refund_fee . '元';
        $desc .= '，返还余额：'  . $order->money_price;
        $desc .= '，返还积分：'  . $order->use_score;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::REFUND,
            'desc' => $desc,
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}