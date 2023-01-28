<?php

namespace app\shop\loaders;

use support\Db;
use app\shop\model\api\ProductSkuModel;
use app\shop\model\api\ProductModel;
use app\shop\model\api\UnitModel;
use app\shop\model\api\ProductServiceModel;
use app\shop\model\api\ServiceTagModel;
use app\shop\model\api\CategoryModel;
use app\shop\model\api\CommentModel;
use app\shop\model\api\OrderModel;
use app\shop\model\api\OrderProductModel;
use app\shop\model\api\FavoriteModel;
use app\shop\model\api\ExpressModel;
use app\shop\model\api\UserModel;

class Shop 
{
    public static function getSkusByProductId($productId)
    {
        return request()->dataloader->make('ShopSkusByProductId')->defer($productId, function($values) {
            if (empty($values)) return [];
            $query = ProductSkuModel::whereIn('product_id', $values);
            $result = $query->get()->groupBy('product_id')->toArray();
            return $result;
        });
    }

    public static function getProductById($id)
    {
        return request()->dataloader->make('ShopProductById')->defer($id, function($values) {
            if (empty($values)) return [];
            return ProductModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getExpressByCode($code)
    {
        return request()->dataloader->make('ShopExpressByCode')->defer($code, function($values) {
            if (empty($values)) return [];
            return ExpressModel::whereIn('code', $values)->get()->keyBy('code')->toArray();
        });
    }

    public static function getUserById($id)
    {
        return request()->dataloader->make('ShopUserById')->defer($id, function($values) {
            if (empty($values)) return [];
            return UserModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getSkuById($id)
    {
        return request()->dataloader->make('ShopSkuById')->defer($id, function($values) {
            if (empty($values)) return [];
            return ProductSkuModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getCategoryByProductId($id)
    {
        return request()->dataloader->make('ShopCategoryByProductId')->defer($id, function($values) {
            if (empty($values)) return [];
            return CategoryModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getUnit($id)
    {
        return request()->dataloader->make('ShopUnitById')->defer($id, function($values) {
            if (empty($values)) return [];
            return UnitModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getServicesByProductId($id)
    {
        return request()->dataloader->make('ShopServicesByProductId')->defer($id, function($values) {
            if (empty($values)) return [];
            $data = ProductServiceModel::with(['ServiceTag'])->whereIn('product_id', $values)->get()->groupBy('product_id')->toArray();
            $result = [];
            foreach ($data as $id => $rows) {
                $list = [];
                foreach ($rows as $row) {
                    $list[] = $row['service_tag'];
                }
                $result[$id] = $list;
            }
            return $result;
        });
    }

    public static function getCommentsByProductId($id, $args, $fieldValue)
    {
        $limit = $fieldValue->getArg('limit');
        return request()->dataloader->make('ShopCommentsByProductId')->defer($id, function($values) use ($limit) {
            if (empty($values)) return [];
            return CommentModel::whereIn('product_id', $values)->limit($limit)->get()->groupBy('product_id')->toArray();
        });
    }

    public static function getOrderById($id)
    {
        return request()->dataloader->make('ShopOrderById')->defer($id, function($values) {
            if (empty($values)) return [];
            return OrderModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getOrderProductById($id)
    {
        return request()->dataloader->make('ShopOrderProductById')->defer($id, function($values) {
            if (empty($values)) return [];
            return OrderProductModel::whereIn('id', $values)->get()->keyBy('id')->toArray();
        });
    }

    public static function getOrderProductsByOrderId($id)
    {
        return request()->dataloader->make('ShopOrderProductsByOrderId')->defer($id, function($values) {
            if (empty($values)) return [];
            return OrderProductModel::whereIn('order_id', $values)->get()->groupBy('order_id')->toArray();
        });
    }

    public static function getIsFavoriteByProductId($id)
    {
        return request()->dataloader->make('ShopFavoriteByProductId')->defer($id, function($values) {
            if (empty($values)) return [];
            if (!get_user()->isLogin()) return [];
            return FavoriteModel::whereIn('product_id', $values)->where('user_id', get_user()->id)->get()->keyBy('product_id')->toArray();
        }, false);
    }

    public static function getFavoriteByProductId($id)
    {
        return request()->dataloader->make('ShopFavoriteByProductId')->defer($id, function($values) {
            if (empty($values)) return [];
            if (!get_user()->isLogin()) return [];
            return FavoriteModel::whereIn('product_id', $values)->where('user_id', get_user()->id)->get()->keyBy('product_id')->toArray();
        });
    }
}