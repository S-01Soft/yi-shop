<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordShipLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::SHIP,
            'desc' => '订单发货',
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}