<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=商品分类管理,weigh=99000)
 */
class Category extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\CategoryValidate::class;
    
    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\admin\CategoryLogic;
    }
}