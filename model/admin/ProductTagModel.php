<?php

namespace app\shop\model\admin;

class ProductTagModel extends Model 
{
    protected $table = "shop_product_tag";

    public function products()
    {
        return $this->belongsToMany(ProductModel::class, 'shop_tag_products', 'tag_id', 'product_id');
    }

    public function tags()
    {
        return $this->hasMany(TagProductsModel::class, 'tag_id', 'id');
    }

}