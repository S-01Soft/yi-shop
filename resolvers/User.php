<?php

namespace app\shop\resolvers;

use Yi\GraphQL\Exception;
use app\shop\model\api\FavoriteModel;

class User 
{
    public function getInfo()
    {
        $user = get_user();
        if ($user) return $user->getUser();
    }

    public function favorite($fieldValue)
    {
        $user_id = get_user()->id;
        $product_id = $fieldValue->getArg('product_id');
        $state = $fieldValue->getArg('state');
        $data = FavoriteModel::where('user_id', $user_id)->where('product_id', $product_id)->first();
        if ($state) {
            if ($data) {
                throw new Exception("商品已收藏");
            }
            $data = FavoriteModel::create([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
        } else {
            if ($data) {
                $data->delete();
            } else {
                throw new Exception("商品已取消收藏");
            }
        }
        return true;
    }
}