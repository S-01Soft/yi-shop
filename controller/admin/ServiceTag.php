<?php

namespace app\shop\controller\admin;

/**
 * @Menu(title=服务标签管理,weigh=88000)
 */
class ServiceTag extends Base 
{
    protected $validate_cls = \app\shop\validate\admin\ServiceTagValidate::class;

    public function before()
    {
        parent::before();
        $this->logic = new \app\shop\logic\admin\ServiceTagLogic;
    }
}