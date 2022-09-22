<?php

namespace app\shop\logic\admin;

class ServiceTagLogic extends Logic
{


    protected function initialize()
    {
        $this->static = \app\shop\model\admin\ServiceTagModel::class;
        parent::initialize();
    }

    public function paginateView($c)
    {
        $c->hidden = ['import_btn', 'export_btn'];
    }
}