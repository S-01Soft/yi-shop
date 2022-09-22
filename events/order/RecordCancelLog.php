<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordCancelLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::CANCEL,
            'desc' => '订单取消',
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}