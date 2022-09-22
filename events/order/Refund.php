<?php

namespace app\shop\events\order;

use app\system\model\admin\UserModel;
use app\shop\model\api\OrderModel;

/**
 * 订单退款
 */
class Refund
{
    public function handle($payload)
    {
        $order = $payload->order;

        if ($order->order_type == 0) { // 普通订单
            $order->after_sale_status = OrderModel::AFTER_SALE_REFUND;
        }

    }
}