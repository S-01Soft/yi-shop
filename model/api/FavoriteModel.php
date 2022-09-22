<?php

namespace app\shop\model\api;

class FavoriteModel extends Model 
{
    protected $table = "shop_favorite";

    const UPDATED_AT = NULL;
    
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'id');
    }
}