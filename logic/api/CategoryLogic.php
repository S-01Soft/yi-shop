<?php

namespace app\shop\logic\api;
use yi\Tree;

class CategoryLogic extends Logic
{
    protected function initialize()
    {
        $this->static = \app\shop\model\api\CategoryModel::class;
        parent::initialize();
    }

    public function getTreeArray()
    {
        $tree = Tree::instance();
        $tree->init($this->model->orderBy('sort','DESC')->get()->toArray(), 'id', 'parent_id');
        return $tree->getTreeArray(0);
    }

    public function getTreeList()
    {
        $tree = Tree::instance();
        return $tree->getTreeList($this->getTreeArray());
    }

    
    public function getChildren($myid, $withself = false)
    {
        $tree = Tree::instance();
        $list = $this->model->get()->toArray();
        $tree->init($list, 'id', 'parent_id');
        return $tree->getChildren($myid, $withself);
    }
}