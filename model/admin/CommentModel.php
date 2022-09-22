<?php

namespace app\shop\model\admin;

class CommentModel extends Model 
{
    protected $table = "shop_comment";

    

    protected $hidden = [];

    public function User()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    
    public function ProductSku()
    {
        return $this->belongsTo(ProductSkuModel::class, 'sku_id', 'id');
    }

    public function Product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'id');
    }
}