<?php
namespace app\shop\validate\admin;

use yi\Validate;

class OrderValidate extends Validate
{
    protected $rule =  [
        'order_sn|订单编号' => 'require|length:0,50',
        'express_code|快递公司编号' => 'length:0,45',
        'express_no|快递单号' => 'length:0,45',
        'contactor|联系人' => 'require|length:0,45',
        'contactor_phone|联系电话' => 'require|length:0,45',
        'address|送货地址' => 'length:0,500',
        'pay_type|支付平台' => 'length:0,15',
        'pay_method|支付方式' => 'length:0,15',
        'saler_remark|买家备注' => 'length:0,255',
        'remark|用户备注' => 'length:0,400',
        'order_sn_re|out_trade_no' => 'length:0,45',
        'after_buyer_remark|售后买家留言' => 'length:0,400',
        'after_saler_remark|售后卖家留言' => 'length:0,400',
        'score_price|score_price' => 'require',
        'use_score|use_score' => 'require',
        'money_price|money_price' => 'require',   
    ];
    
    protected $message  =  [];
    
    protected $scene = [
        'add' => ['order_sn', 'express_code', 'express_no', 'contactor', 'contactor_phone', 'address', 'pay_type', 'pay_method', 'saler_remark', 'remark', 'order_sn_re', 'after_buyer_remark', 'after_saler_remark', 'score_price', 'use_score', 'money_price'],
        'edit' => ['order_sn', 'express_code', 'express_no', 'contactor', 'contactor_phone', 'address', 'pay_type', 'pay_method', 'saler_remark', 'remark', 'order_sn_re', 'after_buyer_remark', 'after_saler_remark', 'score_price', 'use_score', 'money_price'],
    ];    
}