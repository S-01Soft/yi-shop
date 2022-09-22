<?php

namespace app\shop\model\admin;

class TagProductsModel extends Model 
{
    protected $table = "shop_tag_products";
    public $timestamps = false;
    public function tag()
    {
        return $this->belongsTo(\app\shop\model\admin\ProductTagModel::class, 'tag_id', 'id');
    }
    public static function booted()
    {
        static::created(function($row) {
            ev('ShopTagProductAfterInsert', $row);
        });
        static::deleted(function($row) {
            ev('ShopTagProductAfterDelete', $row);
        });
    }
}