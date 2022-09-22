<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=计量单位管理,weigh=91000,ignore=tree|tree_list,ismenu=1)
 */
class Unit extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\UnitValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\UnitLogic::instance();
    }
}