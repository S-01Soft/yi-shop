<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordCancelPostSaleLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::CANCEL_APPLY_REFUND,
            'desc' => '取消售后申请',
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}