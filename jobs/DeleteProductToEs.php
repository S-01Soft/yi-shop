<?php

namespace app\shop\jobs;

use app\shop\model\api\ProductModel;

class DeleteProductToEs extends Consumer
{    
    public $queue = 'ShopDeleteProductEs';

    public function consume($params)
    {
        $id = $params['id'];
        $config = get_module_group_config('shop', 'search');
        $index = $config['index'];
        ev('EsDeleteDoc', $index, $id);
    }
}