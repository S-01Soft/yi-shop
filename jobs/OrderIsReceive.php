<?php

namespace app\shop\jobs;

use app\shop\model\api\OrderModel;

class OrderIsReceive extends Consumer
{
    public $queue = 'ShopOrderIsReceive';

    public function consume($payload)
    {
        $order = OrderModel::where('id', $payload['id'])->first();
        if ($order && $order->is_received != 1) {
            $order->is_received = 1;
            $order->save();
        }
    }
}