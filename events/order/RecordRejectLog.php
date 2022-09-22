<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordRejectLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::SALER_REJECT,
            'desc' => '拒绝退款,理由是：' . $order['after_saler_remark'],
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}