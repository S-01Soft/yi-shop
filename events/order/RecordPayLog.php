<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordPayLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::PAY,
            'desc' => $payload->message ?? '订单支付',
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}