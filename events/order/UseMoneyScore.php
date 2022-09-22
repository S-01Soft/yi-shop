<?php

namespace app\shop\events\order;

use app\system\model\admin\UserModel;

class UseMoneyScore
{
    public function handle($payload)
    {
        $skuLogic = $payload->skuLogic;
        $skuLogic->calcScorePayPrice()->calcMoneyPayPrice();
    }
}