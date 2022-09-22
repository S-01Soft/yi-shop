<?php

namespace app\shop\logic\admin;

class UnitLogic extends Logic
{
    protected $toggleFields = ['is_default'];

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\UnitModel::class;
        parent::initialize();
    }

    protected function beforeAll($query)
    {
        $query->orderByRaw('is_default DESC');
    }
    
}