<?php

namespace app\shop\model\api;

class ProductModel extends Model 
{
    protected $table = "shop_product";

    protected $appends = ['image'];

    protected $hidden = [
        'created_at', 'updated_at', 'create_user'
    ];
    
    const STOCK_TYPE_PAY = 1; // 付款口库存
    const STOCK_TYPE_SUBMIT = 2; // 下单扣库存

    public function getContentAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }
    
    public function decars()
    {
        $attrItems = [];
        $attrGroup = [];
        if (!empty($this->skus)) {
            $attrGroup = explode(',', $this->skus[0]['keys']);
            $result = [];
            foreach ($this->skus as $i => $item) {
                $result[] = explode(',', $item->value);
            }
            $attrItems = reverse_descartes($result);
        }
        $this->attrGroup = $attrGroup;
        $this->attrItems = $attrItems;
        return $this;
    }

    public function getImageAttribute($value)
    {
        return explode(',', $this->attributes['image']);
    }

    public function category()
    {
        return $this->belongsTo(\app\shop\model\api\CategoryModel::class, 'category_id', 'id');
    }
    public function unit()
    {
        return $this->belongsTo(UnitModel::class, 'unit_id', 'id');
    }
    public function services()
    {
        return $this->belongsToMany(\app\shop\model\admin\ServiceTagModel::class, 'shop_product_service', 'product_id', 'service_id');
    }    
    public function skus()
    {
        return $this->hasMany(ProductSkuModel::class, 'product_id', 'id');
    }

    public function favorite()
    {
        return $this->hasOne(FavoriteModel::class, 'product_id', 'id');
    }

    public function tags()
    {
        return $this->hasMany(TagProductsModel::class, 'product_id', 'id');
    }

    public static function booted()
    {
        static::created(function($row) {
            ev('ShopProductAfterInsert', $row, 'api');
        });
        static::updated(function($row) {
            ev('ShopProductAfterUpdate', $row, 'api');
        });
        static::deleting(function($row) {
            ev('ShopProductBeforeDelete', $row, 'api');
        });
        static::deleted(function($row) {
            ev('ShopProductAfterDelete', $row, 'api');
        });
    }
}