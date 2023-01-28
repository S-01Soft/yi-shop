<?php

namespace app\shop\model\api;

class CartModel extends Model
{
    protected $table = 'shop_cart';
    // protected $hidden = [
    //     'user_id', 'product_id', 'sku_id', 'created_at', 'updated_at', 'price', 'product.price'
    // ];

    public function product()
    {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }

    public function sku()
    {
        return $this->hasOne(ProductSkuModel::class, 'id', 'sku_id');
    }

    public function deliveryTpl()
    {
        return $this->hasOne(DeliveryModel::class, 'id', 'delivery_id');
    }
}
