<?php

namespace app\shop\logic\admin;

use support\exception\Exception;

class CategoryLogic extends Logic
{

    protected function initialize()
    {
        $this->static = \app\shop\model\admin\CategoryModel::class;
        parent::initialize();
    }

    protected function beforePaginate($query)
    {
        $query->with(['parent']);
    }
    protected function afterPagination($result)
    {
        foreach($result as $row) {
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
            if($row->parent) $row->parent->setVisible(['id', 'name']);
        }
        return $result;
    }

    protected function beforePostEdit($form, $query)
    {
        if ($form['parent_id'] == $form['id']) throw new Exception("上级分类不能是自身");
        $children = $this->static::where('parent_id', $form['id'])->pluck('id')->toArray();
        if (in_array($form['parent_id'], $children)) throw new Exception("上级分类不能是它的下级");
        return $form;
    }
}