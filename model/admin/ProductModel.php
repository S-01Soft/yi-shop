<?php

namespace app\shop\model\admin;

class ProductModel extends Model 
{
    protected $table = "shop_product";

    public static function booted()
    {
        static::created(function ($row) {
            ev('ShopProductAfterInsert', $row, 'admin');
        });
        static::updated(function($row) {
            ev('ShopProductAfterUpdate', $row, 'admin');
        });
        static::deleting(function($row) {
            ev('ShopProductBeforeDelete', $row, 'admin');
        });
        static::deleted(function($row) {
            ev('ShopProductAfterDelete', $row, 'admin');
        });
    }
    public function getContentAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }

    public function category()
    {
        return $this->belongsTo(\app\shop\model\admin\CategoryModel::class, 'category_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(\app\shop\model\admin\UnitModel::class, 'unit_id', 'id');
    }
    public function services()
    {
        return $this->belongsToMany(ServiceTagModel::class, 'shop_product_service', 'product_id', 'service_id');
    }
    public function deliveryTpl()
    {
        return $this->belongsTo(\app\shop\model\admin\DeliveryTplModel::class, 'delivery_tpl_id', 'id');
    }

    public function createUserC()
    {
        return $this->belongsTo(\app\system\model\admin\AdminModel::class, 'create_user', 'id');
    }

    public function skus()
    {
        return $this->hasMany(ProductSkuModel::class, 'product_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(TagProductsModel::class, 'product_id', 'id');
    }
}