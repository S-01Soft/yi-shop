<?php

namespace app\shop\model\admin;

class UnitModel extends Model 
{
    protected $table = "shop_unit";

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