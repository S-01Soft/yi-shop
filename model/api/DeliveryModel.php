<?php

namespace app\shop\model\api;

class DeliveryModel extends Model 
{
    protected $table = 'shop_delivery_tpl';
    protected $visible = [
        'id', 'title', 'type'
    ];

    public $timestamps = false;
    
    public function deliveryRules()
    {
        return $this->hasMany(DeliveryRuleModel::class, 'tpl_id', 'id');
    }
}