<?php

namespace app\shop\events\order;


class AutoReceive
{
    public function handle($payload)
    {
        $order = $payload->order;
        if ($order->is_delivery == 1 && !empty(get_module_config('shop')['auto_receive_time'])) {
            
            $delay = (int)get_module_config('shop')['auto_receive_time'] * 60 * 60;
            $payload = ['id' => $order->id];
            ev('QeueuSend', 'ShopOrderIsReceive', $payload, $delay);
        }
    }
}