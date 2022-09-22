<?php

namespace app\shop\events\order;

/**
 * 订单创建后
 */
class OrderCreateAfter
{
    public function handle($payload)
    {
        $order = $payload->order;
        // 订单限时支付
        $config  = get_module_group_config('shop', 'order');
        
        // 创建订单减库存
        $params = [
            'order_sn' => $order->order_sn,
            'type' => 2,
            'method' => 'DEC'
        ];
        ev('QueueSend', 'ShopUpdateStock', $params);
    }
}