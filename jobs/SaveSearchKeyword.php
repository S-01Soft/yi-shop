<?php

namespace app\shop\jobs;

use app\shop\model\admin\SearchModel;

class SaveSearchKeyword extends Consumer
{
    public $queue = 'ShopSaveSearchKeyword';

    public function consume($payload)
    {
        $keyword = $payload['kw'];
        $model = SearchModel::where('keyword', $keyword)->first();
        if ($model) {
            $model->hits ++;
            $model->save();
        } else {
            $data = [
                'keyword' => $keyword
            ];
            SearchModel::create($data);
        }
    }
}