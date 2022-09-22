<?php

namespace app\shop\events\index;

use app\shop\model\admin\SearchModel;

class HotWords 
{
    public function handle($payload)
    {
        $data = SearchModel::where('status', 1)->orderByRaw('weigh DESC,hits DESC')->limit(10)->get()->toArray();
        $payload->data = array_column($data, 'keyword');
    }
}