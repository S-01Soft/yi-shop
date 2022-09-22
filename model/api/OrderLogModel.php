<?php

namespace app\shop\model\api;

class OrderLogModel extends Model 
{
    protected $table = 'shop_orderlogs';
    const UPDATED_AT = NULL;

    const NONE = 0;
    const CREATE = 1; // 创建订单
    const PAY = 2; // 付款
    const RECEIVED = 3; // 确认收货
    const APPLY_REFUND = 4; // 申请售后
    const REFUND = 5; // 售后
    const SHIP = 6; // 发货 
    const CANCEL = 7; // 取消订单
    const CANCEL_APPLY_REFUND = 8; // 取消售后
    const SALER_REJECT = 10;
    
}