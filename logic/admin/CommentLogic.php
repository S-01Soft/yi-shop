<?php

namespace app\shop\logic\admin;

class CommentLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\admin\CommentModel::class;
        parent::initialize();
    }
    protected function beforePaginate($query)
    {
        $query->with(["User", "ProductSku", "Product"]);
    }
}