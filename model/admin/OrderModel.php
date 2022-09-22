<?php

namespace app\shop\model\admin;

use Illuminate\Database\Eloquent\SoftDeletes;

class OrderModel extends Model 
{
    use SoftDeletes;

    protected $table = "shop_order";

    public function user()
    {
        return $this->belongsTo(\app\system\model\admin\UserModel::class, 'user_id', 'id');
    }

    public function express() {
        return $this->belongsTo(ExpressModel::class, 'express_code', 'code', [], 'LEFT')->setEagerlyType(0);
    }

    public function products()
    {
        return $this->hasMany(OrderProductsModel::class, 'order_id', 'id');
    }

    
    public function getAfterSaleDataAttribute($value) 
    {
        return json_decode($value, JSON_UNESCAPED_UNICODE);
    }
}