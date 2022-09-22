<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=标签商品管理,weigh=10000,ignore=tree|tree_list,ismenu=0)
 */
class TagProducts extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\TagProductsValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\TagProductsLogic::instance();
    }
}