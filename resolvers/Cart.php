<?php

namespace app\shop\resolvers;

use Yi\GraphQL\Exception;
use app\shop\logic\api\CartLogic;
use app\shop\model\api\CartModel;

class Cart 
{
    public function bySelf($fieldValue)
    {
        $query = $fieldValue->getValue();
        $query->where('user_id', get_user()->id);
        return $query;
    }

    public function add($fieldValue)
    {
        try {
            CartLogic::instance()->add($fieldValue->getArgs());
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
        return true;
    }

    public function edit($fieldValue)
    {
        try {
            return CartLogic::instance()->edit($fieldValue->getArgs());
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function updateStatus($fieldValue)
    {
        try {
            return CartLogic::instance()->updateStatus($fieldValue->getArgs());
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($fieldValue)
    {
        $user_id = get_user()->id;
        $ids = $fieldValue->getArg('ids');
        CartModel::where('user_id', $user_id)->whereIn('id', $ids)->delete();
        return true;
    }

    public function clear()
    {
        return CartModel::where('user_id', get_user()->id)->delete();
    }
}