<?php

namespace app\shop\logic\api;

use support\exception\Exception;
use app\shop\model\api\InvoiceInfoModel;

class InvoiceLogic extends Logic
{
    
    protected function initialize()
    {
        parent::initialize();
    }

    public function getInfoList($user_id)
    {
        return InvoiceInfoModel::where('user_id', $user_id)->get();
    }

}