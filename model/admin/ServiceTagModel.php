<?php

namespace app\shop\model\admin;

class ServiceTagModel extends Model 
{
    protected $table = "shop_service_tag";
    
    public $timestamps = false;

    public function getDescriptionAttribute($value)
    {
        return empty($value) ? '' : htmlspecialchars_decode($value);
    }

}