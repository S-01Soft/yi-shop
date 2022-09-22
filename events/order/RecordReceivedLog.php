<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordReceivedLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::RECEIVED,
            'desc' => '确认收货',
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}