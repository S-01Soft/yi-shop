<?php

namespace app\shop\model\api;

class DeliveryRuleModel extends Model 
{
    protected $table = 'shop_delivery_rule';
    
    protected $appends = [
        'area_ids', 'area_names'
    ];
    
    public $timestamps = false;
    protected function getAreaIdsAttribute($value)
    {
        return explode(',', $this->attributes['area_ids']);
    }
    public function getAreaNamesAttribute($value)
    {
        return explode(',', $this->attributes['area_names']);
    }
}