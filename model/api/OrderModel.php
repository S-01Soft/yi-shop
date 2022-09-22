<?php

namespace app\shop\model\api;

use Illuminate\Database\Eloquent\SoftDeletes;

class OrderModel extends Model 
{
    protected $table = "shop_order";

    use SoftDeletes;

    protected $hidden = [
        'deleted_at', 'after_saler_remark'
    ];
    protected $appends = [
        'create_time_text', 'state_tip', 'after_sale_status_tip'
    ];
    
    /** 订单状态 ↓ */
    public const ORDER_STATUS_NORMAL = 0; // 待付款
    public const ORDER_STATUS_WAIT_SHIP = 1; // 待发货
    public const ORDER_STATUS_WAIT_RECEIV = 2; // 待收货
    public const ORDER_STATUS_RECEIVED = 3; // 已收货
    // public const ORDER_STATUS_AFTER_START = 4; // 开始售后流程
    // public const ORDER_STATUS_AFTER_DONE = 5; // 售后完成
    public const ORDER_STATUS_DONE = 6; // 订单完成
    public const ORDER_STATUS_CANCEL = -1; // 已取消

    public const ORDER_STATUS = [
        self::ORDER_STATUS_NORMAL => '待付款',
        self::ORDER_STATUS_WAIT_SHIP => '待发货',
        self::ORDER_STATUS_WAIT_RECEIV => '待收货',
        self::ORDER_STATUS_RECEIVED => '已收货',
        // self::ORDER_STATUS_AFTER_START => '售后中',
        // self::ORDER_STATUS_AFTER_DONE => '售后完成',
        self::ORDER_STATUS_DONE => '订单完成',
        self::ORDER_STATUS_CANCEL => '已取消'
    ];

    /** 售后状态 */
    public const AFTER_SALE_NORMAL = 0; // 初始状态，无售后
    public const AFTER_SALE_APPLY_REFUND = 1; // 客户申请售后
    public const AFTER_SALE_REFUND = 2; // 售后完成
    public const AFTER_SALE_CANCEL = -1; // 售后取消
    public const AFTER_SALE_REJECT = -2; // 售后驳回

    public const WAIT_PAY = 0;
    public const PAY_SUCCESS = 1;

    /**
     * 获取快递名称
     */
    public function getExpress()
    {
        if (empty($this->express_code)) $name = '';
        else if ($express = ExpressModel::where('code', $this->express_code)->first()) {
            $name = $express->name;
        } else {
            $name = '';
        }
        $this->express_name = $name;
        return $this;
    }
    
    public function getCreateTimeTextAttribute($value)
    {
        $data = $this->attributes;
        $value = $value ? $value : (isset($data['created_at']) ? $data['created_at'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }

    public function getStateTipAttribute($value)
    {
        $data = $this->attributes;
        if (empty($data)) return '';
        $val = '';
        $status = intval($data['status']);
        switch ($status) {
            case static::ORDER_STATUS_NORMAL:
                $val = '待付款';
                break;
            case static::ORDER_STATUS_WAIT_SHIP:
                $val = '待发货';
                break;
            case static::ORDER_STATUS_WAIT_RECEIV:
                $val = '待收货';
                break;
            case static::ORDER_STATUS_RECEIVED:
                if ($data['buyer_comment'] == 1) {
                    $val = '已完成';
                } else {
                    $val = '待评价';
                }
                break;
            case static::ORDER_STATUS_DONE:
                $val = '已完成';
                break;
            case static::ORDER_STATUS_CANCEL:
                $val = '已失效';
                break;
        }
        return $val;
    }

    public function getAfterSaleDataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getAfterSaleStatusTipAttribute($value)
    {
        $data = $this->attributes;
        if (empty($data)) return '';
        $val = intval($data['after_sale_status']);
        switch ($val) {
            case static::AFTER_SALE_APPLY_REFUND : 
                return [
                    'text' => '申请售后',
                    'tip' => '请等待商家处理'
                ];
            case static::AFTER_SALE_REFUND :
                return [
                    'text' => '已退款',
                    'tip' => '该订单已退款'
                ];
            case static::AFTER_SALE_CANCEL :
                return [
                    'text' => '售后取消',
                    'tip' => '您的售后申请已取消'
                ];
            case static::AFTER_SALE_REJECT :
                return [
                    'text' => '售后已拒绝',
                    'tip' => $data['after_saler_remark']
                ];
        }
        return null;
    }

    public function express()
    {
        return $this->hasOne(ExpressModel::class, 'code', 'express_code');
    }

    public function products()
    {
        return $this->hasMany(OrderProductModel::class, 'order_id', 'id');
    }

}