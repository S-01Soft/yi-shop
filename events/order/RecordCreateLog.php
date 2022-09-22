<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordCreateLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::CREATE,
            'desc' => '创建订单' . (empty($order['remark']) ? '' : ',用户留言：' . $order['remark']),
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}