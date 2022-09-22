<?php

namespace app\shop\model\admin;

class DeliveryTplModel extends Model 
{
    protected $table = "shop_delivery_tpl";

    public $timestamps = false;

    public static function booted()
    {
        static::updated(function($row) {
            if ($row->is_default == 1) {
                static::where('id', '<>', $row->id)->update(['is_default' => 0]);
            }
        });
        static::created(function($row) {
            if ($row->is_default == 1) {
                static::where('id', '<>', $row->id)->update(['is_default' => 0]);
            }
        });
    }

}