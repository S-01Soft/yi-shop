<?php

namespace app\shop\model\api;

class HistoryModel extends Model 
{
    protected $table = "shop_history";

    public $timestamps = false;
    
    public function product()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }
}