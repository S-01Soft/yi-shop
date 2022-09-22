<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=商品管理,weigh=98000,ignore=tree|tree_list,ismenu=1)
 */
class Product extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\ProductValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\admin\ProductLogic;
    }
}