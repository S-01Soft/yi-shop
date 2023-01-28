<?php

namespace app\shop\resolvers;

use app\shop\logic\api\CategoryLogic;
use app\shop\model\api\TagProductsModel;
use app\shop\model\api\CommentModel;

class Product 
{
    public function getByCategoryId($fieldValue)
    {
        $query = $fieldValue->getValue();
        $hasChild = $fieldValue->getArg('has_children');
        $cat_id = $fieldValue->getArg('category_id');
        if ($cat_id) {
            if (!$hasChild) $query->where('category_id', $cat_id);
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
        $tag_id = $fieldValue->getArg('tag_id');
        if (!empty($tag_id)) {
            $ids = TagProductsModel::where('tag_id', $tag_id)->pluck('product_id')->toArray(); 
            $query->whereIn('id', $ids);
        }
        return $query;
    }

    public function searchByKeyword($fieldValue)
    {
        $query = $fieldValue->getValue();
        $kw = $fieldValue->getArg('kw');
        if (!empty($kw))
        $query->where(function ($q) use ($kw) {
            $q->orWhere('title', 'like', "%$kw%")->orWhere('description', 'like', "%$kw%");
        });
        return $query;
    }

    public function order($fieldValue)
    {
        $query = $fieldValue->getValue();
        $sort = $fieldValue->getArg('sort');
        $priceOrder = $fieldValue->getArg('price_order');
        if (!isset($sort)) {
            $sort = 0;
        }
        $sort = intval($sort);
        switch ($sort) {
            case 0: 
                $query->orderBy('category_recommend', 'DESC')->orderBy('updated_at', 'DESC');
                break;
            case 1: 
                $query->orderBy('sold_count', 'DESC');
                break;
            case 2: {
                if ($priceOrder == 1) {
                    $query->orderBy('price', 'ASC');
                }
                if ($priceOrder == 2) {
                    $query->orderBy('price', 'DESC');
                }
                break;
            }
        }
        return $query;
    }

    public function getGoodComment($fieldValue)
    {
        $id = $fieldValue->args['product_id'];
        $query = CommentModel::where('product_id', $id)->where('status', 1);
        $star = (clone $query)->sum('star');
        $totalStar = (clone $query)->count() * 5;
        
        if (empty($totalStar)) $result = '无';
        else $result = number_format($star / $totalStar * 100, 0, '.', '') . "%";
        return $result;
    }

    public function search($fieldValue)
    {
        $result = ev('ShopSearch', $fieldValue->getArgs());
        $result['pagination'] = [
            'current_page' => $result['current_page'],
            'last_page' => $result['last_page'],
            'total' => $result['total'],
            'per_page' => $result['per_page'],
            'has_more' => $result['has_more']
        ];
        return $result;
    }
}