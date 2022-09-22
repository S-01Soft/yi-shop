<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordOrderContactorNameLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $admin = $payload->admin;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::NONE,
            'desc' => '管理员修改联系人姓名:' . $payload->oldvalue . ' -> ' . $payload->newvalue. ' [操作人:' . $admin->nickname . '(' . $admin->id . ')]',
            'extend' => json_encode($order->toArray(), 1)
        ];
        OrderLogModel::create($data);
    }
}