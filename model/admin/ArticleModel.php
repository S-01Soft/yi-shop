<?php

namespace app\shop\model\admin;

class ArticleModel extends Model 
{
    protected $table = "shop_article";

    
    public function categories()
    {
        return $this->belongsToMany(ArticleCategoryModel::class, 'shop_articles_categories', 'article_id', 'category_id');
    }

    public function getContentAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }
}