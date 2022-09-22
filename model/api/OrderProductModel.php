<?php

namespace app\shop\model\api;

class OrderProductModel extends Model 
{
    protected $table = 'shop_order_product';    

    protected $visible = [
        'id', 'order_id', 'product_id', 'sku_id', 'title', 'description', 'images', 'attributes',
        'price', 'quantity', 'product_price', 'order_price', 'discount_price', 'order', 'comments',
        'buyer_comment', 'create_time_text', 'product', 'sku'
    ];
    protected $appends = [
        'images', 'create_time_text'
    ];

    
    public function getCreateTimeTextAttribute($value)
    {
        $data = $this->attributes;
        $value = $value ? $value : (isset($data['created_at']) ? $data['created_at'] : '');
        return is_numeric($value) ? date("Y-m-d H:i", $value) : $value;
    }
    
    public function getImagesAttribute($value)
    {
        return explode(',', $this->attributes['image']);
    }

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id', 'id');
    }

    public function orderProductComments()
    {
        return $this->hasMany(CommentModel::class, 'order_product_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(CommentModel::class, 'sku_id', 'sku_id');
    }

    public function product() {
        return $this->hasOne(ProductModel::class, 'id', 'product_id');
    }

    public function sku()
    {
        return $this->hasOne(ProductSkuModel::class, 'id', 'sku_id');
    }
}