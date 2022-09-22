<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=商品标签管理,weigh=90000,ignore=tree|tree_list,ismenu=1)
 */
class ProductTag extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\ProductTagValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\ProductTagLogic::instance();
    }

}