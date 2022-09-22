<?php

namespace app\shop\logic\admin;

class ExpressLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\admin\ExpressModel::class;
        parent::initialize();
    }

    
}