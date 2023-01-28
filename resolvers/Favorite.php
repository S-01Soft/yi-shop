<?php

namespace app\shop\resolvers;

use Yi\GraphQL\Exception;
use app\shop\model\api\FavoriteModel;

class Favorite 
{
    public function bySelf($fieldValue)
    {
        $query = $fieldValue->getValue();
        $query->where('user_id', get_user()->id);
        return $query;
    }
}