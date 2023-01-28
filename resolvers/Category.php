<?php

namespace app\shop\resolvers;

use app\shop\logic\api\CategoryLogic;
use app\shop\model\api\TagProductsModel;

class Product 
{
    public function getByCategoryId($fieldValue)
    {
        $query = $fieldValue->getValue();
        $hasChild = $fieldValue->getArg('hasChild');
        $cat_id = $fieldValue->getArg('id');
        if ($cat_id) {
            if (!$hasChild) $query->where('id', $cat_id);
            else $query->where(function ($query) use ($cat_id) {
                $categoryLogic = CategoryLogic::instance();
                $query->whereIn('category_id', array_column($categoryLogic->getChildren($cat_id, true), 'id'));
            });
        }
        return $query;
    }

    public function getByTagId($fieldValue)
    {
        $query = $fieldValue->getValue();
        $tag_id = $fieldValue->getArg('tagId');
        if (!empty($tag_id)) {
            $ids = TagProductsModel::where('tag_id', $tag_id)->pluck('product_id')->toArray(); 
            $query->whereIn('id', $ids);
        }
        return $query;
    }
}