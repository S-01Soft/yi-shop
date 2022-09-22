<?php

namespace app\shop\model\api;

class ProductSkuModel extends Model
{
    protected $table = "shop_product_sku";

    public $timestamps = false;

    protected $appends = [
        'price', 'image'
    ];

    public function getPriceAttribute($value)
    {
        $data = $this->attributes;
        $payload = (object)['data' => $data];
        event('ShopGetSkuPrice', $payload);
        return $payload->data['price'];
    }

    public function getImageAttribute($value)
    {
        $data = $this->attributes;
        return empty($data['image']) ? "" : fixurl($data['image'], true);
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'id');
    }
}
