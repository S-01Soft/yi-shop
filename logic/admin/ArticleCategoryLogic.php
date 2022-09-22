<?php

namespace app\shop\logic\admin;

class ArticleCategoryLogic extends Logic
{

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\ArticleCategoryModel::class;
        parent::initialize();
    }

    protected function beforePaginate($query)
    {
        $query->with(['parent']);
    }
    protected function afterPaginate($result)
    {
        foreach($result as $row) {
            $row->setVisible(['id','parent_id','name','description','sort','parent']);
            if($row->parent) $row->parent->setVisible(['id', 'name']);
        }
        return $result;
    }
    protected function beforeSelect($query)
    {
        $query->with(['parent']);
    }
    protected function afterSelect($result)
    {
        foreach($result as $row) {
            $row->setVisible(['id','parent_id','name','description','sort','parent']);
            if($row->parent) $row->parent->setVisible(['id', 'name']);
        }
        return $result;
    }
    protected function beforeAll($query)
    {
        $query->with(['parent']);
    }
    protected function afterAll($result)
    {
        foreach($result as $row) {
            $row->setVisible(['id','parent_id','name','description','sort','parent']);
            if($row->parent) $row->parent->setVisible(['id', 'name']);
        }
        return $result;
    }
}