<?php

namespace app\shop\model\api;

class CommentModel extends Model 
{
    protected $table = "shop_comment";

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }

    public function sku()
    {
        return $this->belongsTo(ProductSkuModel::class, 'sku_id', 'id');
    }

    public function getImagesAttribute($value)
    {
        return empty($value) ? [] : explode(',', $value);
    }
}