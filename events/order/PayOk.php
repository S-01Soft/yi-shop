<?php

namespace app\shop\events\order;

/**
 * 支付成功
 */
class PayOk
{
    /**
     * @param OrderModel $order
     */
    public function handle($payload)
    {
        $order = $payload->order;

        ev('QueueSend', 'ShopOrderPaySuccess', [
            'order_sn' => $order->order_sn
        ]);
        
        // 付款减库存
        $params = [
            'order_sn' => $order->order_sn,
            'type' => 1,
            'method' => 'DEC'
        ];
        ev('QueueSend', 'ShopUpdateStock', $params);
    }
}
