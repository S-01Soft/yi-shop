<?php

namespace app\shop\model\api;

class ServiceTagModel extends Model 
{
    protected $table = "shop_service_tag";

    public $timestamps = false;

    protected function getDescriptionAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }

}