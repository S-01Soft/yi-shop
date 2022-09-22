<?php

namespace app\shop\logic\admin;

class SearchLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\admin\SearchModel::class;
        parent::initialize();
    }

    public function paginateView($c)
    {
        $c->hidden = ["import_btn","export_btn"];
    }
}