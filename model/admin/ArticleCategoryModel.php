<?php

namespace app\shop\model\admin;

class ArticleCategoryModel extends Model 
{
    protected $table = "shop_article_category";
    
    public $timestamps = false;

    public function parent()
    {
        return $this->belongsTo(\app\shop\model\admin\ArticleCategoryModel::class, 'parent_id', 'id');
    }

}