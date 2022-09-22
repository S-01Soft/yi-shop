<?php

namespace app\shop\logic\admin;

class TagProductsLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\admin\TagProductsModel::class;
        parent::initialize();
    }

    protected function beforePaginate($query)
    {
        $query->with(['tag']);
    }
    protected function beforeSelect($query)
    {
        $query->with(['tag']);
    }
    protected function beforeAll($query)
    {
        $query->with(['tag']);
    }
    protected function afterAll($result)
    {
        foreach($result as $row) {
            $row->setVisible(['id','tag_id','product_id','tag']);
            if($row->tag) $row->tag->setVisible(['id', 'title']);
        }
        return $result;
    }
}