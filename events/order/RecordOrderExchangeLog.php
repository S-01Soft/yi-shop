<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordOrderExchangeLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::REFUND,
            'desc' => '订单换货：' . $order->after_sale_data['express_code'] . $order->after_sale_data['express_no'],
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}