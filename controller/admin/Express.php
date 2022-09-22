<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=快递公司管理,weigh=92000,ignore=tree|tree_list,ismenu=1)
 */
class Express extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\ExpressValidate::class;
    protected $has_tree = false;
    
    public function before()
    {
        parent::before();
        $this->logic = \app\shop\logic\admin\ExpressLogic::instance();
    }
}