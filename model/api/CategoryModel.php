<?php

namespace app\shop\model\api;

class CategoryModel extends Model 
{
    protected $table = "shop_category";
    
    public function parent()
    {
        return $this->belongsTo(\app\shop\model\admin\CategoryModel::class, 'parent_id', 'id');
    }

}