<?php

namespace app\shop\events\order;

use app\shop\model\api\OrderLogModel;

class RecordApplyRefundLog
{
    public function handle($payload)
    {
        $order = $payload->order;
        $data = [
            'order_id' => $order['id'],
            'action' => OrderLogModel::APPLY_REFUND,
            'extend' => json_encode($order->toArray(), 1)
        ];
        $d = $order->after_sale_data;
        $types = [
            1 => '仅退款',
            2 => '退款退货',
            3 => '换货'
        ];
        $data['desc'] = "申请售后【{$types[$d['type']]}】";
        if ($d['type'] != 1) $data['desc'] .= "，单号：" . $d['no'];
        $data['desc'] .= '，备注：' . $d['remark'];
        OrderLogModel::create($data);
    }
}