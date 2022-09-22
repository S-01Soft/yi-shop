<?php

namespace app\shop\logic\api;

class CommentLogic extends Logic
{
    
    protected function initialize()
    {
        $this->static = \app\shop\model\api\CommentModel::class;
        parent::initialize();
    }

    public function getList($attributes)
    {
        extract($attributes);
        $query = $this->static::with(['user' => function($q) {
        }, 'sku'])->where(['product_id' => $id, 'status' => 1]);
        $data = $query->orderBy('id', 'desc')->paginate(5);
        foreach ($data as $row) {
            $row->user->setVisible(['nickname', 'avatar']);
        }
        $data = $data->toArray();
        if (!empty($data['data'])) {
            $star = $this->static::where('product_id', $id)->sum('star');
            $totalStar = $this->static::where('product_id', $id)->count() * 5;
            $data['good_comment'] = number_format($star / $totalStar * 100, 2, '.', '') . "%";
        } else $data['good_comment'] = '无';
        return $data;
    }
    

}