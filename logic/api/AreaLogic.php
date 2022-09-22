<?php

namespace app\shop\logic\api;

use app\shop\model\api\AreaModel;

class AreaLogic extends Logic
{
    public function getList()
    {
        return AreaModel::where(1)->get();
    }
}