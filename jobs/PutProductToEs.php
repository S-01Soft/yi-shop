<?php

namespace app\shop\jobs;

use app\shop\model\api\ProductModel;

class PutProductToEs extends Consumer
{
    public $queue = 'ShopPutProductEs';

    public function consume($params)
    {
        $id = $params['id'];
        $row = ProductModel::with(['skus', 'tags'])->where('id', $id)->first();
        if (!$row) return;
        $config = get_module_group_config('shop', 'search');
        $index = $config['index'];
        $docs = [];
        $docs[] = ['index'=> ['_index' => $index, '_id' => $row['id'], '_type' => '_doc']];
        $docs[] = ev('ShopParseProductEsData', $row);
        ev('EsPutDocs', $index, $docs);
    }
}