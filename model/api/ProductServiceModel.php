<?php

namespace app\shop\model\api;

class ProductServiceModel extends Model 
{
    protected $table = "shop_product_service";

    public function ServiceTag()
    {
        return $this->hasOne(ServiceTagModel::class, 'id', 'service_id');
    }
}